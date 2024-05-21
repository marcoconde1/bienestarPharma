<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sucursales</title>
<!-- Incluye Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Incluye Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/hover.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

</head>
<body>

    <?php
session_start();
require_once "funciones_marca.php";
$marcas = obtenerMarcas();

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


    <div id="map" style="height: 400px;"></div>

    <?php 
    require_once "funciones_sucursal.php";

    $sucursales = obtenerSucursales();
    if (!empty($sucursales)) : ?>
<div class="container mt-3 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <input type="text" id="buscarNombre" class="form-control" placeholder="Buscar por nombre">
        </div>
        <div class="col-md-4 mb-3">
            <input type="text" id="buscarDireccion" class="form-control" placeholder="Buscar por dirección">
        </div>
    </div>

    <div class="table-responsive">
        <table id="tablaSucursales" class="table table-bordered text-center">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="table align-middle">
                <?php foreach ($sucursales as $sucursal): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sucursal["id"]); ?></td>
                        <td><?php echo htmlspecialchars($sucursal["nombre"]); ?></td>
                        <td><?php echo htmlspecialchars($sucursal["direccion"]); ?></td>
                        <td class="latitud" style="display: none;"><?php echo htmlspecialchars($sucursal["latitud"]); ?></td>
                        <td class="longitud" style="display: none;"><?php echo htmlspecialchars($sucursal["longitud"]); ?></td>
                        <td>
                            <form method='post' action='accion_eliminar_sucursal.php' style='display:inline'>
                                <input type='hidden' name='id' value='<?php echo htmlspecialchars($sucursal["id"]); ?>'>
                                <button type='submit' class="btn btn-primary hvr-float">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const buscarNombre = document.getElementById("buscarNombre");
    const buscarDireccion = document.getElementById("buscarDireccion");
    const tablaSucursales = document.getElementById("tablaSucursales").getElementsByTagName("tbody")[0];
    const filas = tablaSucursales.getElementsByTagName("tr");

    function filtrarTabla() {
        const nombreFiltro = buscarNombre.value.toLowerCase();
        const direccionFiltro = buscarDireccion.value.toLowerCase();

        for (let i = 0; i < filas.length; i++) {
            const celdas = filas[i].getElementsByTagName("td");
            const nombre = celdas[1].textContent.toLowerCase();
            const direccion = celdas[2].textContent.toLowerCase();

            if (nombre.includes(nombreFiltro) && direccion.includes(direccionFiltro)) {
                filas[i].style.display = "";
            } else {
                filas[i].style.display = "none";
            }
        }
    }

    buscarNombre.addEventListener("keyup", filtrarTabla);
    buscarDireccion.addEventListener("keyup", filtrarTabla);
});
</script>

   

    <!-- JavaScript para Leaflet -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const buscarNombre = document.getElementById("buscarNombre");
    const buscarDireccion = document.getElementById("buscarDireccion");
    const tablaSucursales = document.getElementById("tablaSucursales").getElementsByTagName("tbody")[0];
    const filas = tablaSucursales.getElementsByTagName("tr");
    const map = L.map('map').setView([0, 0], 2);
    const markers = [];

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    function limpiarMapa() {
        for (let i = 0; i < markers.length; i++) {
            map.removeLayer(markers[i]);
        }
        markers.length = 0;
    }

    function agregarMarcadores() {
        limpiarMapa();
        for (let i = 0; i < filas.length; i++) {
            const celdas = filas[i].getElementsByTagName("td");
            const nombre = celdas[1].textContent.toLowerCase();
            const direccion = celdas[2].textContent.toLowerCase();

            if (nombre.includes(buscarNombre.value.toLowerCase()) && direccion.includes(buscarDireccion.value.toLowerCase())) {
                const latitud = parseFloat(celdas[3].textContent);
                const longitud = parseFloat(celdas[4].textContent);
                const marker = L.marker([latitud, longitud])
                    .addTo(map)
                    .bindPopup('<b>' + nombre + '</b><br>' + direccion);
                markers.push(marker);
            }
        }
    }

    function filtrarTabla() {
        agregarMarcadores();
        for (let i = 0; i < filas.length; i++) {
            const celdas = filas[i].getElementsByTagName("td");
            const nombre = celdas[1].textContent.toLowerCase();
            const direccion = celdas[2].textContent.toLowerCase();
            if (nombre.includes(buscarNombre.value.toLowerCase()) && direccion.includes(buscarDireccion.value.toLowerCase())) {
                filas[i].style.display = "";
            } else {
                filas[i].style.display = "none";
            }
        }
    }

    buscarNombre.addEventListener("keyup", filtrarTabla);
    buscarDireccion.addEventListener("keyup", filtrarTabla);

    agregarMarcadores(); // Agregar marcadores al cargar la página
});
</script>

<?php else: ?>
<div class="container">
    <p class="text-center">No se encontraron sucursales.</p>
</div>
<?php endif; ?>


<!-- boton flotante -->
<div class="fixed-bottom p-3 mt-1">
    <button class="btn btn-dark btn-lg hvr-float">
        <a href="anadir_sucursal.php" class="link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"><i class="fas fa-plus"></i></a>
    </button>
</div>


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
