<?php
session_start();
include 'conexion.php';

// Verificar admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
}

// Eliminar producto
if (isset($_GET['eliminar'])) {

    $id = intval($_GET['eliminar']);

    $sql = "DELETE FROM productos WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: listar_productos.php?msg=deleted");

    } else {

        header("Location: listar_productos.php?msg=error");
    }

    exit;
}

// Obtener productos
$result = $conn->query("SELECT * FROM productos ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Administrador | Listar Productos</title>

<link rel="stylesheet" href="../css/style.css">

<style>

table{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
}

th, td{
    border:1px solid #ccc;
    padding:10px;
    text-align:center;
}

th{
    background:#3eb43a;
    color:white;
}

.table-actions{
    display:flex;
    gap:10px;
    justify-content:center;
}

.btn-edit{
    background:#007bff;
    color:white;
    padding:8px 16px;
    border-radius:20px;
    text-decoration:none;
}

.btn-delete{
    background:#dc3545;
    color:white;
    padding:8px 16px;
    border-radius:20px;
    text-decoration:none;
}

.alert{
    padding:12px;
    border-radius:6px;
    margin-bottom:20px;
    text-align:center;
    font-weight:bold;
}

.success{
    background:#d4edda;
    color:#155724;
}

.error{
    background:#f8d7da;
    color:#721c24;
}

.category-badge{
    background:#e1e2e6;
    padding:5px 10px;
    border-radius:15px;
    font-size:12px;
    display:inline-block;
    margin:2px;
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

<section class="container" style="padding:40px;">

<h2>Listado de Productos</h2>

<?php if (isset($_GET['msg'])): ?>

    <?php if ($_GET['msg'] === 'deleted'): ?>

        <div class="alert success">
            Producto eliminado correctamente
        </div>

    <?php elseif ($_GET['msg'] === 'updated'): ?>

        <div class="alert success">
            Producto actualizado correctamente
        </div>

    <?php elseif ($_GET['msg'] === 'error'): ?>

        <div class="alert error">
            Ocurrió un error al procesar la acción
        </div>

    <?php endif; ?>

<?php endif; ?>

<table>

<thead>

<tr>

<th>ID</th>
<th>Imagen</th>
<th>Nombre</th>
<th>Descripción</th>
<th>Precio</th>
<th>Categorías</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()): ?>

<tr>

<td>
<?php echo $row['id']; ?>
</td>

<td>

<img
src="../imagenes/<?php echo htmlspecialchars($row['foto']); ?>"
style="width:60px; height:60px; object-fit:cover;">

</td>

<td>
<?php echo htmlspecialchars($row['nombre']); ?>
</td>

<td>
<?php echo htmlspecialchars($row['descripcion']); ?>
</td>

<td>
$<?php echo htmlspecialchars($row['precio']); ?>
</td>

<td>

<?php

$categorias = explode(',', $row['categoria']);

foreach ($categorias as $cat) {

    echo '<span class="category-badge">'
    . htmlspecialchars($cat) .
    '</span>';
}

?>

</td>

<td>

<div class="table-actions">

<a href="editar_producto.php?id=<?php echo $row['id']; ?>"
class="btn-edit">

Editar

</a>

<a href="listar_productos.php?eliminar=<?php echo $row['id']; ?>"
class="btn-delete"
onclick="return confirm('¿Seguro que deseas eliminar este producto?');">

Eliminar

</a>

</div>

</td>

</tr>

<?php endwhile; ?>

</tbody>
</table>

</section>

</body>
</html>