<?php
session_start();
include 'conexion.php';

// Verificar admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
}

// Obtener ID
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: listar_productos.php");
    exit;
}

// Obtener producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$producto = $result->fetch_assoc();

if (!$producto) {
    exit("Producto no encontrado");
}

$mensaje = "";

/* =========================
   AGREGAR NUEVA CATEGORÍA
========================= */

if (isset($_POST['agregar_categoria'])) {

    $nuevaCategoria = trim($_POST['nueva_categoria']);

    if (!empty($nuevaCategoria)) {

        $sqlNueva = "INSERT IGNORE INTO clasificaciones(nombre) VALUES (?)";

        $stmtNueva = $conn->prepare($sqlNueva);

        $stmtNueva->bind_param("s", $nuevaCategoria);

        if ($stmtNueva->execute()) {

            $mensaje = "Clasificación agregada correctamente";

        } else {

            $mensaje = "Error al agregar clasificación";
        }
    }
}

/* =========================
   ACTUALIZAR PRODUCTO
========================= */

if (isset($_POST['actualizar_producto'])) {

    $nombre = $_POST['nombre'] ?? '';

    $descripcion = $_POST['descripcion'] ?? '';

    // Limpiar precio
    $precio = $_POST['precio'] ?? '0';

    $precio = str_replace(['$', ',', ' '], '', $precio);

    $precio = floatval($precio);

    // Categorías
    $categorias = $_POST['categorias'] ?? [];

    if (empty($categorias)) {

        $mensaje = "Debes seleccionar al menos una categoría.";

    } else {

        $categoria = implode(',', $categorias);

        $foto = $producto['foto'];

        // Nueva imagen
        if (!empty($_FILES['foto']['name'])) {

            $targetDir = "../imagenes/";

            $foto = basename($_FILES['foto']['name']);

            $targetFile = $targetDir . $foto;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {

                $foto = $producto['foto'];
            }
        }

        // UPDATE
        $sql = "UPDATE productos
        SET nombre=?, descripcion=?, precio=?, categoria=?, foto=?
        WHERE id=?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssdssi",
            $nombre,
            $descripcion,
            $precio,
            $categoria,
            $foto,
            $id
        );

        if ($stmt->execute()) {

            header("Location: listar_productos.php?msg=updated");

            exit;

        } else {

            $mensaje = "Error al actualizar producto";
        }
    }
}

/* =========================
   OBTENER CATEGORÍAS
========================= */

$sqlCategorias = "SELECT * FROM clasificaciones ORDER BY nombre ASC";

$resultCategorias = $conn->query($sqlCategorias);

// Categorías actuales
$categorias_actuales = explode(',', $producto['categoria']);

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Editar Producto</title>

<link rel="stylesheet" href="../css/style.css">

<style>

/* CONTENEDOR */

.categoria-container{
    display:flex;
    flex-wrap:wrap;
    gap:15px;
    margin-top:15px;
    margin-bottom:20px;
}

/* CARD */

.categoria-item{
    width:115px;
    height:80px;

    background:#f5f5f5;

    border-radius:10px;

    display:flex !important;
    flex-direction:column !important;
    justify-content:center !important;
    align-items:center !important;

    cursor:pointer;

    transition:0.2s ease;

    border:2px solid transparent;

    box-shadow:0 2px 6px rgba(0,0,0,0.05);

    padding:10px;

    text-align:center;
}

/* HOVER */

.categoria-item:hover{
    transform:translateY(-3px);
    background:#efefef;
}

/* CHECKBOX */

.categoria-item input[type="checkbox"]{
    margin:0 0 10px 0 !important;

    width:auto !important;

    height:auto !important;

    transform:scale(0.9);

    cursor:pointer;
}

/* TEXTO */

.categoria-item span{
    font-size:13px;
    font-weight:600;
    color:#444;
    line-height:1.2;
}

/* CARD ACTIVA */

.categoria-item:has(input:checked){
    border:2px solid #39b54a;
    background:#f1fff2;
}

/* NUEVA CATEGORÍA */

.nueva-categoria{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.nueva-categoria input{
    flex:1;
}

/* BOTÓN */

.btn-add{
    background:#33c233;
    color:white;

    border:none;

    border-radius:6px;

    padding:1px 10px;

    font-size:14px;

    line-height:1.1;

    font-weight:600;

    font-family:'Poppins', sans-serif !important;

    cursor:pointer;
}

.btn-add:hover{
    background:#28a428;
}

/* IMAGEN */

.current-image img{
    margin-top:10px;
    border-radius:10px;
}

</style>

</head>
<body>

<header class="header">

<div class="nav-container container">

<a href="../index.html">
<img src="../imagenes/logo_pistachon.png"
alt="El Pistachón"
class="logo-img">
</a>

<input type="checkbox" id="menu-toggle">

<label for="menu-toggle">
<img src="../imagenes/menu_ico.png"
class="menu-icon"
alt="Menú">
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

<div class="msg-error">
<?php echo htmlspecialchars($mensaje); ?>
</div>

<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<label>Nombre del Producto:</label>

<input type="text"
name="nombre"
value="<?php echo htmlspecialchars($producto['nombre']); ?>"
required>

<label>Descripción:</label>

<textarea name="descripcion"
required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>

<label>Precio:</label>

<input type="text"
name="precio"
value="<?php echo "$" . htmlspecialchars($producto['precio']); ?>"
required>

<label>Categorías:</label>

<div class="categoria-container">

<?php while($cat = $resultCategorias->fetch_assoc()): ?>

<?php

$nombreCat = strtolower($cat['nombre']);

$checked = in_array($nombreCat, $categorias_actuales)
? 'checked'
: '';

?>

<label class="categoria-item">

<input type="checkbox"
name="categorias[]"
value="<?php echo $nombreCat; ?>"
<?php echo $checked; ?>>

<span>
<?php echo htmlspecialchars($cat['nombre']); ?>
</span>

</label>

<?php endwhile; ?>

</div>

<label>Añadir nueva categoría:</label>

<div class="nueva-categoria">

<input type="text"
name="nueva_categoria"
placeholder="Nueva categoría">

<button type="submit"
name="agregar_categoria"
class="btn-add">

Agregar

</button>

</div>

<br>

<div class="current-image">

<label>Imagen Actual:</label>

<br>

<img
src="../imagenes/<?php echo htmlspecialchars($producto['foto']); ?>"
alt=""
width="150">

</div>

<label>Nueva Imagen (opcional):</label>

<input type="file"
name="foto"
accept="image/*">

<button type="submit"
name="actualizar_producto"
class="btn-admin">

Actualizar Producto

</button>

</form>

</div>
</section>

</body>
</html>