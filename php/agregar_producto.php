<?php
session_start();
include 'conexion.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
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
        $mensaje = "Producto agregado correctamente ✅";
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

<header class="header-admin">
  <div class="menu container">
    <a href="index.php" class="logo">Logo</a>
    <nav class="navbar">
      <ul>
        <li><a href="catalogo.php">Catálogo</a></li>
        <?php if ($_SESSION['rol'] === 'admin'): ?>
          <li><a href="agregar_producto.php">Agregar Producto</a></li>
        <?php endif; ?>
        <li><a href="logout.php">Cerrar Sesión</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="container" style="padding:40px;">
  <h2>Agregar nuevo producto</h2>
  <?php if (!empty($mensaje)): ?>
    <p style="color:green;"><?php echo htmlspecialchars($mensaje); ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:15px; max-width:400px;">
    <label>Nombre:
      <input type="text" name="nombre" required>
    </label>

    <label>Descripción:
      <textarea name="descripcion" rows="3" required></textarea>
    </label>

    <label>Stock (kg):
      <input type="number" name="stock" min="0" step="0.1" required>
    </label>

    <label>Categoría:
      <select name="categoria" required>
        <option value="chiles">Chiles</option>
        <option value="especias">Especias</option>
        <option value="semillas">Semillas</option>
        <option value="dulces">Dulces</option>
        <option value="frutas-secas">Frutas Secas</option>
        <option value="otros">Otros</option>
      </select>
    </label>

    <label>Imagen:
      <input type="file" name="foto" accept="image/*" required>
    </label>

    <button type="submit" class="btn-1">Agregar Producto</button>
  </form>
</section>

</body>
</html>