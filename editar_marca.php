<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Marca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
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

    require_once "funciones_marca.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $marca = obtenerMarcaPorId($id);
    if ($marca) {
?>


<!-- navbar -->
<nav class="navbar sticky-top navbar-expand-lg  " style="background-color: rgba(255, 255, 255, 0.8);">
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
            <a class="nav-link  hvr-float fw-bolder " aria-current="page" href="mostrar_productos.php">Productos</a>
          </li>
        <?php } ?>
        <?php if ($row['permiso'] == 2) { ?>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder " aria-current="page" href="mostrar_productos.php">Productos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="mostrar_marcas.php">Marcas</a>
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
            <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_productos.php">Productos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="mostrar_marcas.php">Marcas</a>
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
 
<div class="container mt-5 mb-3">
    <div class="row justify-content-center">
        <div class="col-lg-6">   
<form id="formularioCrearMarca" action="accion_editar_marca.php" method="post" enctype="multipart/form-data" class="p-4 border rounded">
    <h2 class="text-center mb-4">Editar Marca</h2>

    <input type="hidden" name="id" value="<?php echo $marca['id']; ?>">
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $marca['nombre']; ?>" required><br>
        </div>
        <div class="col-md-6">
            <label for="anio_fundacion">Año de Fundación:</label>    
        <select id="anio_fundacion" name="anio_fundacion" class="form-select"required>
            <?php
            $anio_actual = date('Y');
            for ($anio = $anio_actual; $anio >= 1900; $anio--) {
                // Comillas dobles dentro de comillas dobles deben ser escapadas
                echo "<option value='$anio'" . ($marca['anio_fundacion'] == $anio ? " selected" : "") . ">$anio</option>";
            }
            ?>
        </select><br>
        </div>
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" class="form-control" rows="3" value="<?php echo $marca['descripcion']; ?>" required><br>
    </div>

    <div class="mb-3">
        <label for="pais_origen" class="form-label">País de Origen:</label>
        <input type="text" id="pais_origen" name="pais_origen" class="form-control" value="<?php echo $marca['pais_origen']; ?>" required><br>
    </div>

    <div class="mb-3">
        <label for="contacto" class="form-label">Contacto:</label>
        <input type="text" id="contacto" name="contacto" class="form-control"  value="<?php echo $marca['contacto']; ?>" required><br>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
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
        echo "No se encontró la marca.";
    }
} else {
    echo "Se requiere el ID de la marca a editar.";
}  
} else {
    // La sesión no está establecida, redirigir al formulario de inicio de sesión
    header("Location: index.php");
    exit; // Asegúrate de terminar la ejecución del script después de redirigir
}
?>
</body>
</html>
