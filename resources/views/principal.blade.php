<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Moderno</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        /* NAVBAR */

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #111;
            color: white;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .cart {
            cursor: pointer;
            font-size: 18px;
        }

        .cart span {
            background: red;
            padding: 4px 8px;
            border-radius: 50%;
            margin-left: 5px;
        }

        /* HERO */

        .hero {
            height: 300px;
            background: linear-gradient(to right, #111, #444);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .hero h1 {
            font-size: 40px;
        }

        /* CATEGORIAS */

        .categorias {
            padding: 40px;
        }

        .categorias h2 {
            margin-bottom: 20px;
        }

        .categorias-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .categoria {
            background: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            cursor: pointer;
            transition: .3s;
        }

        .categoria:hover {
            transform: scale(1.05);
            background: #eee;
        }

        /* PRODUCTOS */

        .productos {
            padding: 40px;
        }

        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        .card-body h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .precio {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #111;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background: #333;
        }

        /* CARRITO */

        .carrito-panel {
            position: fixed;
            right: -400px;
            top: 0;
            width: 350px;
            height: 100%;
            background: white;
            box-shadow: -3px 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            transition: .4s;
            overflow-y: auto;
        }

        .carrito-panel.active {
            right: 0;
        }

        .carrito-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <nav>

        <div class="logo">ShopModern</div>

        <div class="cart" onclick="toggleCart()">
            🛒 Carrito <span id="cartCount">0</span>
        </div>

    </nav>

    <section class="hero">
        <h1>Bienvenido a nuestra tienda</h1>
        <p>Los mejores productos al mejor precio</p>
    </section>


    <section class="categorias">

        <h2>Categorías</h2>

        <div class="categorias-grid">

            <div class="categoria" onclick="filtrar('Remera')">Remeras</div>
            <div class="categoria" onclick="filtrar('Pantalon')">Pantalones</div>
            <div class="categoria" onclick="filtrar('Camisa')">Camisas</div>
            <div class="categoria" onclick="filtrar('Buzo')">Buzos</div>

        </div>

    </section>


    <section class="productos">

        <h2>Productos</h2>

        <div class="productos-grid" id="productos"></div>

    </section>


    <div class="carrito-panel" id="carritoPanel">

        <h2>Carrito</h2>

        <div id="carritoItems"></div>

        <div class="total">
            Total: $<span id="total">0</span>
        </div>

    </div>


    <script>
        let producto = []
        let carrito = []

        // Cargar productos desde la API
        function cargarProductos() {
            fetch("{{ route('productos') }}")
                .then(response => response.json())
                .then(data => {
                    producto = data
                    mostrarProductos(producto)
                })
                .catch(error => console.error('Error al cargar productos:', error));
        }

        function mostrarProductos(lista) {

            const contenedor = document.getElementById("productos")
            contenedor.innerHTML = ""

            lista.forEach(p => {

                contenedor.innerHTML += `

<div class="card">

<img src="${p.image}">

<div class="card-body">

<h3>${p.titulo}</h3>

<div class="precio">$${p.price}</div>

<button onclick="agregarCarrito(${p.id})">
Agregar al carrito
</button>

</div>

</div>

`

            })

        }

        function filtrar(cat) {

            const filtrados = productos.filter(p => p.category == cat)

            mostrarProductos(filtrados)

        }

        function agregarCarrito(id) {

            const producto = productos.find(p => p.id == id)

            carrito.push(producto)

            actualizarCarrito()

        }

        function actualizarCarrito() {

            const items = document.getElementById("carritoItems")

            items.innerHTML = ""

            let total = 0

            carrito.forEach(p => {

                items.innerHTML += `

<div class="carrito-item">

<span>${p.nombre}</span>

<span>$${p.precio}</span>

</div>

`

                total += p.precio

            })

            document.getElementById("total").innerText = total
            document.getElementById("cartCount").innerText = carrito.length

        }

        function toggleCart() {

            document.getElementById("carritoPanel").classList.toggle("active")

        }

        // Cargar productos al abrir la página
        document.addEventListener('DOMContentLoaded', cargarProductos)
    </script>

</body>

</html>