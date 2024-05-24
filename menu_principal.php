<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/hover.css" rel="stylesheet" media="all">
<style>
    /* Estilo para las imágenes dentro del carousel */
    #carouselExampleFade .carousel-item img {
        object-fit: cover; /* Corta la imagen para que se ajuste al tamaño del carousel */
        width: 100%; /* Garantiza que la imagen ocupe todo el ancho del carousel */
        height: 100vh; /* Establece la altura del carousel igual al 100% del alto de la ventana del navegador */
    }
</style>



</head>
<body >


<?php
session_start();
if (isset($_SESSION["ci"])) {
    $dir = dirname(__FILE__);
    require_once $dir . "/accion_conexion.php";
    $conexion = conectarse();
    $ci = $_SESSION["ci"];
    $sql_permiso = "SELECT nombre ,permiso FROM personal WHERE ci = '$ci'";
    $resultado_permiso = mysqli_query($conexion, $sql_permiso);
    $row = mysqli_fetch_assoc($resultado_permiso);
?>


<!-- navbar  -->
<nav class="navbar fixed-top navbar-expand-lg  " style="background-color: rgba(255, 255, 255, 0.8);">
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
            <a class="nav-link  hvr-float fw-bolder" aria-current="page" href="mostrar_productos.php">Productos</a>
          </li>
        <?php } ?>
        <?php if ($row['permiso'] == 2) { ?>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder" aria-current="page" href="mostrar_productos.php">Productos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bolder" aria-current="page" href="mostrar_marcas.php">Marcas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder" aria-current="page" href="mostrar_categorias.php">Categorias</a>
          </li>
        <?php } ?>
        <?php if ($row['permiso'] == 3) { ?>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder" aria-current="page" href="venta.php">Venta</a>
          </li>

          <li class="nav-item">
            <a  class="nav-link hvr-float fw-bolder" aria-current="page" href="mostrar_productos.php ">Productos</a>
          </li>

          <li class="nav-item">
            <a class="nav-link hvr-float fw-bolder" aria-current="page" href="mostrar_marcas.php">Marcas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder" aria-current="page" href="mostrar_categorias.php">Categorias</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bolder" aria-current="page" href="mostrar_sucursales.php">Sucursales</a>
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



<div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="imagenes/fondo_0.jpg" alt="First slide">
              <div class="carousel-caption d-none d-md-block text-center" style="top: 65%; transform: translateY(-50%); font-size: 2em;">
                <p class="fs-1 fw-bold hvr-grow-rotate">¡Bienvenido al Sistema de Información de Bienestar Pharma, <?php echo htmlspecialchars($row['nombre']); ?>!</p>
              </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="imagenes/fondo_1.jpg" alt="Second slide">
            <div class="carousel-caption d-none d-md-block text-center" style="top: 65%; transform: translateY(-50%); font-size: 2em;">
                <p class="fs-1 fw-bold hvr-grow-rotate">¡Bienvenido al Sistema de Información de Bienestar Pharma, <?php echo htmlspecialchars($row['nombre']); ?>!</p>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="imagenes/fondo_2.jpg" alt="Third slide">
            <div class="carousel-caption d-none d-md-block text-center" style="top: 65%; transform: translateY(-50%); font-size: 2em;">
                <p class="fs-1 fw-bold hvr-grow-rotate">¡Bienvenido al Sistema de Información de Bienestar Pharma, <?php echo htmlspecialchars($row['nombre']); ?>!</p>
            </div>
        </div>
    </div>
</div>



  
<!-- pie de pagina
<footer class="bg-dark text-light text-center fixed-bottom">
    <div class="container">
        <p class="mt-4">&copy; 2024 BienestarPharma | Todos los derechos reservados.</p>
    </div>
</footer>
-->  




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
