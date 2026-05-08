<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Apartado | El Pistachón</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header class="header">
    <div class="nav-container container">
        <a href="../index.html">
            <img src="../imagenes/logo_pistachon.png" alt="El Pistachón" class="logo-img">
        </a>
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle">
            <img src="../imagenes/menu_ico.png" class="menu-icon" alt="Menú">
        </label>
        <nav class="navbar">
            <ul>
                <li><a href="../index.html">Inicio</a></li>
                <li><a href="catalogo.php">Catálogo</a></li>
                <li><a href="../acerca_de.html">Acerca de</a></li>
                <li><a href="apartado.php">Mi Apartado</a></li>
                <li><a href="login.php">Iniciar Sesión</a></li>
            </ul>
        </nav>
    </div>
    <div class="hero-content container">
        <h1>Mi Apartado</h1>
        <p>Selecciona nuestros productos desde el catálogo y resérvalos temporalmente sin necesidad de realizar un pago inmediato. Posteriormente, podrás pasar a recogerlos y efectuar el pago directamente en tienda.</p>
    </div>
</header>

<div class="container">
    <div class="apartado-container">

        <div class="apartado-lista">
            <h2 class="seccion-titulo">Mi Apartado</h2>
            <div id="lista-productos">
                <p class="vacio-msg">Tu apartado está vacío.</p>
            </div>
            <div class="total-row">Total: <span id="total">$0.00</span></div>
        </div>

        <div class="apartado-contacto">
            <div class="contacto-card">
                <h2>Información de Contacto</h2>
                <input type="text" id="nombre" placeholder="Nombre">
                <input type="tel" id="telefono" placeholder="Número de teléfono">
                <input type="date" id="fecha" min="">
                <small style="display:block; margin-top:-10px; margin-bottom:14px; color:#888; font-size:12px;">Fecha de recogida en tienda</small>
                <button class="btn-pedido" onclick="realizarPedido()">Realizar pedido</button>
            </div>
        </div>

    </div>
</div>

<footer class="footer">
    <div class="footer-content container">
        <div class="footer-section">
            <h3>Sobre Nosotros</h3>
            <ul>
                <li><a href="../acerca_de.html">Historia</a></li>
                <li><a href="../acerca_de.html">Localización</a></li>
                <li><a href="../acerca_de.html">Redes sociales</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Atención al cliente</h3>
            <ul>
                <li><a href="https://wa.me/526462274463">Tel: 646-227-4463</a></li>
            </ul>
        </div>
    </div>
</footer>

<script>
// Fecha mínima = hoy
document.getElementById('fecha').min = new Date().toISOString().split('T')[0];

function cargarApartado() {
    const apartado = JSON.parse(localStorage.getItem('apartado')) || [];
    const lista = document.getElementById('lista-productos');

    if (apartado.length === 0) {
        lista.innerHTML = '<p class="vacio-msg">Tu apartado está vacío.</p>';
        document.getElementById('total').textContent = '$0.00';
        return;
    }

    lista.innerHTML = '';
    let total = 0;

    apartado.forEach((prod, index) => {
        const subtotal = prod.precio * prod.cantidad;
        total += subtotal;

        const item = document.createElement('div');
        item.className = 'apartado-item';
        item.innerHTML = `
            <img src="../imagenes/${prod.foto}" alt="${prod.nombre}">
            <div class="apartado-item-info">
                <h4>${prod.nombre}</h4>
                <p>Clasificación: ${prod.categoria}</p>
                <p>Descripción: ${prod.descripcion}</p>
                <select class="cantidad-select" onchange="cambiarCantidad(${index}, this.value)">
                    ${[0.25, 0.5, 1, 2, 3, 5].map(v =>
                        `<option value="${v}" ${prod.cantidad == v ? 'selected' : ''}>${v} kg</option>`
                    ).join('')}
                </select>
            </div>
            <div class="apartado-item-acciones">
                <span class="apartado-item-precio">$${subtotal.toFixed(2)}</span>
                <button class="btn-eliminar" onclick="eliminar(${index})">Eliminar</button>
            </div>
        `;
        lista.appendChild(item);
    });

    document.getElementById('total').textContent = '$' + total.toFixed(2);
}

function cambiarCantidad(index, cantidad) {
    let apartado = JSON.parse(localStorage.getItem('apartado')) || [];
    apartado[index].cantidad = parseFloat(cantidad);
    localStorage.setItem('apartado', JSON.stringify(apartado));
    cargarApartado();
}

function eliminar(index) {
    let apartado = JSON.parse(localStorage.getItem('apartado')) || [];
    apartado.splice(index, 1);
    localStorage.setItem('apartado', JSON.stringify(apartado));
    cargarApartado();
}

function realizarPedido() {
    const nombre = document.getElementById('nombre').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const fecha = document.getElementById('fecha').value;
    const apartado = JSON.parse(localStorage.getItem('apartado')) || [];

    if (!nombre || !telefono) {
        alert('Por favor completa tu nombre y teléfono.');
        return;
    }
    if (!fecha) {
        alert('Por favor selecciona la fecha en que recogerás tu pedido.');
        return;
    }
    if (apartado.length === 0) {
        alert('Tu apartado está vacío.');
        return;
    }

    // Formatear fecha en español
    const [anio, mes, dia] = fecha.split('-');
    const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    const fechaFormato = `${dia} de ${meses[parseInt(mes)-1]} de ${anio}`;

    let msg = `*Nuevo Apartado - El Pistachón*\n\n`;
    msg += `*Cliente:* ${nombre}\n`;
    msg += `*Tel:* ${telefono}\n`;
    msg += `*Fecha de recogida:* ${fechaFormato}\n\n`;
    msg += `*Productos:*\n`;
    let total = 0;

    apartado.forEach(p => {
        const sub = p.precio * p.cantidad;
        total += sub;
        msg += `- ${p.nombre}: ${p.cantidad} kg x $${p.precio} = $${sub.toFixed(2)}\n`;
    });

    msg += `\n*Total: $${total.toFixed(2)}*`;
    msg += `\n\n_Pasaré a recoger mi pedido el ${fechaFormato}._`;

    const url = `https://wa.me/526462274463?text=${encodeURIComponent(msg)}`;
    window.open(url, '_blank');

    localStorage.removeItem('apartado');
    cargarApartado();
}

cargarApartado();
</script>

</body>
</html>