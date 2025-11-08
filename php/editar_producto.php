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
    $stock = $_POST['stock'] ?? 0;
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

    $sql = "UPDATE productos SET nombre=?, descripcion=?, stock=?, categoria=?, foto=? WHERE id=?";
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
  <div class="menu container">
    <a href="admin.php" class="logo">Panel Admin</a>
    <nav class="navbar">
      <ul>
        <li><a href="agregar_producto.php">Agregar Producto</a></li>
        <li><a href="listar_productos.php">Gestionar Productos</a></li>
        <li><a href="logout.php">Cerrar sesión</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="container" style="padding:40px;">
  <h2>Editar Producto</h2>
  <?php if (!empty($mensaje)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($mensaje); ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:15px; max-width:400px;">
    <label>Nombre:
      <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
    </label>

    <label>Descripción:
      <textarea name="descripcion" rows="3" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
    </label>

    <label>Stock (kg):
      <input type="number" name="stock" min="0" step="0.1" value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
    </label>

    <label>Categoría:
      <select name="categoria" required>
        <option value="chiles" <?php if($producto['categoria']=='chiles') echo 'selected'; ?>>Chiles</option>
        <option value="especias" <?php if($producto['categoria']=='especias') echo 'selected'; ?>>Especias</option>
        <option value="semillas" <?php if($producto['categoria']=='semillas') echo 'selected'; ?>>Semillas</option>
        <option value="dulces" <?php if($producto['categoria']=='dulces') echo 'selected'; ?>>Dulces</option>
        <option value="frutas-secas" <?php if($producto['categoria']=='frutas-secas') echo 'selected'; ?>>Frutas Secas</option>
        <option value="otros" <?php if($producto['categoria']=='otros') echo 'selected'; ?>>Otros</option>
      </select>
    </label>

    <label>Imagen actual:
      <img src="../imagenes/<?php echo htmlspecialchars($producto['foto']); ?>" alt="" style="width:100px; height:100px; object-fit:cover;">
    </label>

    <label>Nueva imagen (opcional):
      <input type="file" name="foto" accept="image/*">
    </label>

    <button type="submit" class="btn-1">Actualizar Producto</button>
  </form>
</section>

</body>
</html>