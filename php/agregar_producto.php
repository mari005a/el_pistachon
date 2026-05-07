<?php
session_start();
include 'conexion.php';

// Verificar admin
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php?msg=error_user");
    exit;
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
   AGREGAR PRODUCTO
========================= */

if (isset($_POST['guardar_producto'])) {

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

        // Imagen
        $foto = 'no_image.png';

        if (!empty($_FILES['foto']['name'])) {

            $targetDir = "../imagenes/";

            $foto = basename($_FILES['foto']['name']);

            $targetFile = $targetDir . $foto;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
                $foto = 'no_image.png';
            }
        }

        // Insertar producto
        $sql = "INSERT INTO productos
        (nombre, descripcion, precio, categoria, foto)
        VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssdss",
            $nombre,
            $descripcion,
            $precio,
            $categoria,
            $foto
        );

        if ($stmt->execute()) {
            $mensaje = "Producto agregado correctamente";
        } else {
            $mensaje = "Error al agregar producto";
        }
    }
}

/* =========================
   OBTENER CATEGORÍAS
========================= */

$sqlCategorias = "SELECT * FROM clasificaciones ORDER BY nombre ASC";
$resultCategorias = $conn->query($sqlCategorias);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrador | Agregar Producto</title>
    <link rel="stylesheet" href="../css/style.css">

    <style>

/* CONTENEDOR GENERAL */

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

/* CUANDO ESTÁ SELECCIONADA */

.categoria-item:has(input:checked){
    border:2px solid #39b54a;

    background:#f1fff2;
}

/* INPUT + BOTÓN */

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
        <p>Agrega o gestiona tus productos en las opciones del menú.</p>
    </div>

</header>

<section class="admin-section">

<div class="admin-form container">

    <h2>Agregar Nuevo Producto</h2>

    <?php if (!empty($mensaje)): ?>

        <div class="msg-success">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>

    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Nombre del Producto:</label>

        <input type="text"
        name="nombre"
        required
        placeholder="Ingresa el nombre del producto">

        <label>Descripción:</label>

        <textarea name="descripcion"
        required
        placeholder="Describe el producto"></textarea>

        <label>Precio:</label>

        <input type="text"
        name="precio"
        required
        placeholder="Ingresa el precio">

        <label>Categorías:</label>

        <div class="categoria-container">

            <?php while($cat = $resultCategorias->fetch_assoc()): ?>

                <label class="categoria-item">

                    <input type="checkbox"
                    name="categorias[]"
                    value="<?php echo strtolower($cat['nombre']); ?>">

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

        <label>Imagen del Producto:</label>

        <input type="file"
        name="foto"
        accept="image/*">

        <button type="submit"
        name="guardar_producto"
        class="btn-admin">

            Agregar Producto

        </button>

    </form>

</div>
</section>

</body>
</html>