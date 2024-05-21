<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
</head>
<body>
   
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcas</title>
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
                    <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="mostrar_categorias.php">Categorias</a>
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
                    <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="mostrar_categorias.php">Categorias</a>
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

 <?php
    require_once "funciones_categoria.php";
    $categorias = obtenerCategorias();

    // Verificar si hay categorías disponibles
    if (!empty($categorias)) {
    ?>
    <div class="container mt-1 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                
                <input type="text" id="buscarNombre" class="form-control" placeholder="Buscar por nombre">
            </div>
            <div class="col-md-4 mb-3">
               
                <input type="text" id="buscarDescripcion" class="form-control"placeholder="Buscar por descripcion">
            </div>
            
        </div>

        <div class="table-responsive">
            <table id="tablaCategorias" class="table table-bordered text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table align-middle">
                    <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($categoria["id"]); ?></td>
                        <td><?php echo htmlspecialchars($categoria["nombre"]); ?></td>
                        <td><?php echo htmlspecialchars($categoria["descripcion"]); ?></td>
                        <td>
                            
                            <form method='post' action='editar_categoria.php' style='display:inline'>
                                <input type='hidden' name='id' value='<?php echo htmlspecialchars($categoria["id"]); ?>'>
                              
                                <button type='submit' class="btn btn-dark hvr-float">Editar</button>
                            
                            </form>
                        </td>
                        <td>
                            <form method='post' action='accion_eliminar_categoria.php' style='display:inline'>
                                <input type='hidden' name='id' value='<?php echo htmlspecialchars($categoria["id"]); ?>'>

                                 
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
    const buscarDescripcion = document.getElementById("buscarDescripcion");
    const tablaCategorias = document.getElementById("tablaCategorias").getElementsByTagName("tbody")[0];
    const filas = tablaCategorias.getElementsByTagName("tr");

    function filtrarTabla() {
        const nombreFiltro = buscarNombre.value.toLowerCase();
        const descripcionFiltro = buscarDescripcion.value.toLowerCase();

        for (let i = 0; i < filas.length; i++) {
            const celdas = filas[i].getElementsByTagName("td");
            const nombre = celdas[1].textContent.toLowerCase();
            const descripcion = celdas[2].textContent.toLowerCase();

            if (nombre.includes(nombreFiltro) && descripcion.includes(descripcionFiltro)) {
                filas[i].style.display = "";
            } else {
                filas[i].style.display = "none";
            }
        }
    }

    buscarNombre.addEventListener("keyup", filtrarTabla);
    buscarDescripcion.addEventListener("keyup", filtrarTabla);
});
</script>

 
<?php } else { ?>
<div class="container mt-5">
    <p class="text-center">No se encontraron categorias.</p>
</div>
<?php } ?>

<!-- boton flotante -->
<div class="fixed-bottom p-3 mt-1">
    <button class="btn btn-dark btn-lg hvr-float">
        <a href="anadir_categoria.php" class="link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"><i class="fas fa-plus"></i></a>
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
