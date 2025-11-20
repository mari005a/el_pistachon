<?php
session_start(); 
include 'conexion.php';

// Verificar que el usuario sea admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $stock = $_POST['stock'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';

    // Manejo de imagen
    $foto = 'no_image.png'; // valor por defecto
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "../imagenes/";
        $foto = basename($_FILES['foto']['name']);
        $targetFile = $targetDir . $foto;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = 'no_image.png'; // fallback si falla la subida
        }
    }

    // Insertar en BD
    $sql = "INSERT INTO productos (nombre, descripcion, stock, categoria, foto) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $nombre, $descripcion, $stock, $categoria, $foto);

    if ($stmt->execute()) {
        $mensaje = "Producto agregado correctamente ";
    } else {
        $mensaje = "Error al agregar producto: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrador | Agregar Producto</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="header">
        <div class="nav-container container">
            <a href="index.html">
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
        <h2>Agregar Nuevo Producto</h2>
        
        <?php if (!empty($mensaje)): ?>
            <div class="msg-success"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Nombre del Producto:</label>
            <input type="text" name="nombre" required placeholder="Ingresa el nombre del producto">

            <label>Descripción:</label>
            <textarea name="descripcion" required placeholder="Describe el producto"></textarea>

            <label>Stock (kg):</label>
            <input type="number" name="stock" min="0" step="0.1" required placeholder="0.0">

            <label>Categoría:</label>
            <select name="categoria" required>
                <option value="">Selecciona una categoría</option>
                <option value="chiles">Chiles</option>
                <option value="especias">Especias</option>
                <option value="semillas">Semillas</option>
                <option value="dulces">Dulces</option>
                <option value="frutas-secas">Frutas Secas</option>
                <option value="otros">Otros</option>
            </select>

            <label>Imagen del Producto:</label>
            <input type="file" name="foto" accept="image/*" required>

            <button type="submit" class="btn-admin">Agregar Producto</button>
        </form>
    </div>
</section>
</body>
</html>