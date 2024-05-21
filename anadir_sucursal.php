<!DOCTYPE html>
<html>
<head>
    <title>Añadir sucursal</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/hover.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>
<body>
    <?php
session_start();

if (isset($_SESSION["ci"])) {
    $dir = dirname(__FILE__);
    require_once $dir . "/accion_conexion.php";
    $conexion = conectarse();
    $ci = $_SESSION["ci"];
    $sql_permiso = "SELECT nombre, permiso FROM personal WHERE ci = '$ci'";
    $resultado_permiso = mysqli_query($conexion, $sql_permiso);
    $row = mysqli_fetch_assoc($resultado_permiso);
?>
<nav class="navbar sticky-top navbar-expand-lg" style="background-color: rgba(255, 255, 255, 0.8);">
    <div class="container-fluid">
        <img src="imagenes/logo.png" class="img-fluid hvr-grow" alt="Imagen" style="width: 180px; height: auto;">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <?php if ($row['permiso'] == 1) { ?>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder" aria-current="page" href="venta.php">Venta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_productos.php">Productos</a>
                </li>
                <?php } ?>
                <?php if ($row['permiso'] == 2) { ?>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_productos.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_marcas.php">Marcas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_categorias.php">Categorias</a>
                </li>
                <?php } ?>
                <?php if ($row['permiso'] == 3) { ?>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder" aria-current="page" href="venta.php">Venta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_productos.php">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_marcas.php">Marcas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_categorias.php">Categorias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="mostrar_sucursales.php">Sucursales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder" aria-current="page" href="mostrar_personal.php">Personal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder" aria-current="page" href="datos.php">Datos</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>




<div class="container mt-1 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-6 p-4 border rounded">   

    <h2 class="text-center mb-4">Añadir Sucursal</h2>

    <div class="mb-3 text-center">
        <input id="search-input" class="form-control" type="text" placeholder="Buscar ubicación">
    </div>
    <div class="mb-3">
        <div id="map" style="height: 400px;"></div>
    </div>

    <form id="location-form" action="accion_anadir_sucursal.php" method="post" > <!-- Corregido el atributo action -->
        <input type="hidden" id="latitud" name="latitud">
        <input type="hidden" id="longitud" name="longitud">
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre de la sucursal:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección de la sucursal:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" required>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Añadir sucursal</button>
    </div>
    </form>
</div>
</div>
</div>

    <script>
        var map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker([0, 0], { draggable: true }).addTo(map);

        marker.on('dragend', function(e) {
    var latitude = marker.getLatLng().lat;
    var longitude = marker.getLatLng().lng;

    // Actualizar los campos de entrada ocultos con la latitud y longitud
    document.getElementById('latitud').value = latitude;
    document.getElementById('longitud').value = longitude;
});


        map.on('click', function(e) {
            var latitude = e.latlng.lat;
            var longitude = e.latlng.lng;

            // Actualizar la posición del marcador
            marker.setLatLng([latitude, longitude]);

            // Actualizar los campos de entrada ocultos con la latitud y longitud
            document.getElementById('latitud').value = latitude;
            document.getElementById('longitud').value = longitude;
        });

        // Función para obtener la ubicación actual del usuario
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    // Centrar el mapa en la ubicación actual del usuario
                    map.setView([latitude, longitude], 13);

                    // Mover el marcador a la ubicación actual del usuario
                    marker.setLatLng([latitude, longitude]);

                    // Actualizar los campos de entrada ocultos con la latitud y longitud
                    document.getElementById('latitud').value = latitude;
                    document.getElementById('longitud').value = longitude;
                });
            } else {
                alert("Tu navegador no soporta la geolocalización.");
            }
        }

        // Llamar a la función para obtener la ubicación actual del usuario cuando la página cargue
        getLocation();

        // Función para buscar una ubicación utilizando Nominatim
        function searchLocation(query) {
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + query)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var latitude = parseFloat(data[0].lat);
                        var longitude = parseFloat(data[0].lon);

                        // Centrar el mapa en la ubicación encontrada
                        map.setView([latitude, longitude], 13);

                        // Mover el marcador a la ubicación encontrada
                        marker.setLatLng([latitude, longitude]);

                        // Actualizar los campos de entrada ocultos con la latitud y longitud
                        document.getElementById('latitud').value = latitude;
                        document.getElementById('longitud').value = longitude;
                    } else {
                        alert("No se encontraron resultados para la ubicación proporcionada.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Hubo un problema al buscar la ubicación.");
                });
        }

        // Escuchar el evento de cambio en el campo de búsqueda
        document.getElementById('search-input').addEventListener('change', function(event) {
            var query = event.target.value.trim();
            if (query !== '') {
                searchLocation(query);
            }
        });

        // Envío de datos al servidor cuando se envía el formulario
        document.getElementById('location-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            // Enviar los datos al servidor mediante AJAX
            fetch('accion_anadir_sucursal.php', {  // Corregido el nombre del archivo PHP
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Hubo un problema al guardar la ubicación de la sucursal.');
                }
                return response.text();
            })
            .then(data => {
                window.location.href = 'mostrar_sucursales.php'; // Mensaje de confirmación del servidor
            })
            .catch(error => {
                console.error(error);
                alert('Hubo un problema al guardar la ubicación de la sucursal. Por favor, inténtalo de nuevo.');
            });
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php    
} else {
    // La sesión no está establecida, redirigir al formulario de inicio de sesión
    header("Location: index.php");
    exit; // Asegúrate de terminar la ejecución del script después de redirigir
}
?>
</body>
</html>
