<?php
session_start(); 
include 'conexion.php';

// Verificar que el usuario sea admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
}

$mensaje = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    
    // LIMPIAR PRECIO ($, comas, espacios)
    $precio = $_POST['precio'] ?? '0';
    $precio = str_replace(['$', ',', ' '], '', $precio);
    $precio = floatval($precio);

    // Categorías múltiples
    $categorias = $_POST['categorias'] ?? [];
    if (empty($categorias)) {
        $mensaje = "Debes seleccionar al menos una categoría.";
    } else {
        $categoria = implode(',', $categorias);
    }

    // Manejo de imagen
    $foto = 'no_image.png'; // por defecto
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "../imagenes/";
        $foto = basename($_FILES['foto']['name']);
        $targetFile = $targetDir . $foto;

        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
            $foto = 'no_image.png'; 
        }
    }

    // Insertar en BD
    if (!empty($categoria)) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria, foto) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $categoria, $foto);

        if ($stmt->execute()) {
            $mensaje = "Producto agregado correctamente";
        } else {
            $mensaje = "Error al agregar producto: " . $conn->error;
        }
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
        <p>Agrega o gestiona tus productos en las opciones del menú.</p>
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

        <label>Precio:</label>
        <input type="text" name="precio" required placeholder="Ingresa el precio">

        <label>Categorías:</label>
        <div class="checkbox-group">
            <label><input type="checkbox" name="categorias[]" value="chiles"> Chiles</label>
            <label><input type="checkbox" name="categorias[]" value="especias"> Especias</label>
            <label><input type="checkbox" name="categorias[]" value="semillas"> Semillas</label>
            <label><input type="checkbox" name="categorias[]" value="dulces"> Dulces</label>
            <label><input type="checkbox" name="categorias[]" value="frutas-secas"> Frutas Secas</label>
            <label><input type="checkbox" name="categorias[]" value="otros"> Otros</label>
        </div>

        <label>Imagen del Producto:</label>
        <input type="file" name="foto" accept="image/*">

        <button type="submit" class="btn-admin">Agregar Producto</button>
    </form>
</div>
</section>
</body>
</html>
