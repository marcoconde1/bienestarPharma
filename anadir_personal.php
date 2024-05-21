<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir personal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

require_once "funciones_producto.php";
$productos = obtenerProductos();

if (isset($_SESSION["ci"])) {
    $dir = dirname(__FILE__);
    require_once $dir . "/accion_conexion.php";
    $conexion = conectarse();
    $ci = $_SESSION["ci"];
    $sql_permiso = "SELECT nombre, permiso FROM personal WHERE ci = '$ci'";
    $resultado_permiso = mysqli_query($conexion, $sql_permiso);
    $row = mysqli_fetch_assoc($resultado_permiso);
?>
    <!-- navbar -->
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
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder" aria-current="page" href="mostrar_sucursales.php">Sucursales</a>
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
                    <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="mostrar_personal.php">Personal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hvr-float fw-bolder" aria-current="page" href="datos.php">Datos</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
 
<div class="container mt-5 mb-3">
    <div class="row justify-content-center">
        <div class="col-lg-6">   
            <form id="formularioCrearPersonal" action="accion_anadir_personal.php" method="post" class="p-4 border rounded">
                <h2 class="text-center mb-4">Añadir Personal</h2>
                
                <div class="mb-3">
                    <label for="ci" class="form-label">CI:</label>
                    <input type="text" id="ci" name="ci" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="permiso" class="form-label">Permiso:</label>
                    <select id="permiso" name="permiso" class="form-select">
                        <option value="1">Venta</option>
                        <option value="2">Inventario</option>
                        <option value="3">Administración</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Añadir Personal</button>
                </div>
            </form>
        </div>
    </div>
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

