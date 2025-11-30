<?php
session_start(); 
include 'conexion.php';

// Verificar que el usuario sea admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
}

// Obtener ID del producto
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: listar_productos.php");
    exit;
}

// Consultar producto actual
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if (!$producto) {
    echo "<p style='color:red;'>Producto no encontrado.</p>";
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $stock = $_POST['precio'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $foto = $producto['foto']; // mantener la actual

    // Si se sube nueva imagen
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "../imagenes/";
        $foto = basename($_FILES['foto']['name']);
        $targetFile = $targetDir . $foto;
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = $producto['foto']; // fallback si falla
        }
    }

    $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, categoria=?, foto=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $nombre, $descripcion, $stock, $categoria, $foto, $id);

    if ($stmt->execute()) {
        header("Location: listar_productos.php?msg=updated");
        exit;
    } else {
        $mensaje = "Error al actualizar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="header">
        <div class="nav-container container">
            <a href="../html/index.html">
                <img src="../imagenes/logo_pistachon.png" alt="El Pistachón" class="logo-img">
            </a>
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle">
                <img src="../imagenes/menu_ico.png" class="menu-icon" alt="Menú">
            </label>
            <nav class="navbar">
                <ul>
                    <li><a href="agregar_producto.php">Agregar Producto</a></li>
                    <li><a href="listar_productos.php">Gestionar Productos</a></li>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                </ul>
            </nav>
        </div>

        <div class="hero-content container">
            <h1>Panel de Administrador</h1>
            <p>
                Agrega o gestiona tus productos en las opciones del menú.
            </p>
        </div>
    </header>

    <section class="admin-section">
    <div class="admin-form container">
        <h2>Editar Producto</h2>
        
        <?php if (!empty($mensaje)): ?>
            <div class="msg-error"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required placeholder="Ingresa el nombre del producto">

            <label>Descripción:</label>
            <textarea name="descripcion" rows="3" required placeholder="Describe el producto"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>

            <label>Precio:</label>
            <input type="number" name="precio" min="0" step="0.1" value="<?php echo htmlspecialchars($producto['stock']); ?>" required placeholder="0.0">

            <label>Categoría:</label>
            <select name="categoria" required>
                <option value="">Selecciona una categoría</option>
                <option value="chiles" <?php if($producto['categoria']=='chiles') echo 'selected'; ?>>Chiles</option>
                <option value="especias" <?php if($producto['categoria']=='especias') echo 'selected'; ?>>Especias</option>
                <option value="semillas" <?php if($producto['categoria']=='semillas') echo 'selected'; ?>>Semillas</option>
                <option value="dulces" <?php if($producto['categoria']=='dulces') echo 'selected'; ?>>Dulces</option>
                <option value="frutas-secas" <?php if($producto['categoria']=='frutas-secas') echo 'selected'; ?>>Frutas Secas</option>
                <option value="otros" <?php if($producto['categoria']=='otros') echo 'selected'; ?>>Otros</option>
            </select>

            <div class="current-image">
                <label>Imagen Actual:</label>
                <img src="../imagenes/<?php echo htmlspecialchars($producto['foto']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
            </div>

            <label>Nueva Imagen (opcional):</label>
            <input type="file" name="foto" accept="image/*">

            <button type="submit" class="btn-admin">Actualizar Producto</button>
        </form>
    </div>
</section>
</body>
</html>