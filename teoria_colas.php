<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teoria de colas</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/hover.css" rel="stylesheet" media="all">


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
            <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="datos.php">Datos</a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>


<div class="container-fluid ">
  <div class="row" >
    <nav class="navbar navbar-expand-lg col-md-2 d-none d-md-block sidebar p-3" style="background-color: rgba(255, 255, 255, 0.8); ">
      <div class="sidebar-sticky">
        <ul class="navbar-nav flex-column">
          
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold" href="eoq.php">EOQ basico</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold" href="eoq_p.php">EOQ con faltantes planeados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold active" href="teoria_colas.php">Teoria de colas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold" href="cadenas_markov.php">Cadenas de Markov</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-10 ml-md-auto px-4 p-3">
      



<?php
// Incluir el archivo de acción de conexión y funciones
$dir = dirname(__FILE__);
require_once $dir . DIRECTORY_SEPARATOR . "accion_conexion.php";
require_once "funciones_teoria_colas.php";

// Establecer la conexión a la base de datos
$conexion = conectarse();

$sql_personal = "SELECT ci, nombre, apellido FROM personal";
$resultado_personal = mysqli_query($conexion, $sql_personal);

$sql_sucursal = "SELECT id, nombre FROM sucursal";
$resultado_sucursal = mysqli_query($conexion, $sql_sucursal);

// Obtener los valores de lambda y mu al cargar la página
$lamda = obtenerLamda();
$mu = obtenerMu();

    if ($lamda != 0) {
        $x = 1 / $lamda;
    } else {
        $x = -1; // Si lamda es cero, x será cero
    }

    if ($mu != 0) {
        $u = 1 / $mu;
    } else {
        $u = -1; // Si mu es cero, u será cero
    }

// Calcular los resultados
$Wq = round(floatval(calcularWq($x, $u)), 2);
$Lq = round(floatval(calcularLq($x, $u)), 2); 
$Ls = round(floatval(calcularLs($x, $u)), 2); 
$Ws = round(floatval(calcularWs($x, $u)), 2); 

$ro = round(floatval(calcularRo($x, $u)), 2);
$ro_muestra = round(floatval(calcularRo($x, $u)), 2)*100;
$n = 0;
$t = 1;

$pn = round(floatval(calcularProbabilidadN($ro, $n)), 2)*100; 
$pWqT = round(floatval(calcularWqt($u, $ro ,$t) ), 2)*100; 
$pWsT =round(floatval(calcularWSt($u, $ro ,$t)), 2)*100; 



// Unidad de tiempo por defecto
$unidad_tiempo = 'segundos';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personal_ci = $_POST['personal_ci'];
    $sucursal_id = $_POST['sucursal_id'];
    $unidad_tiempo = $_POST['unidad_tiempo']; // Obtener la unidad de tiempo del formulario

    // Definir valores iniciales para evitar la división por cero
  

    // Obtener valores de lamda y mu solo si no están vacíos
    if (!empty($personal_ci) && empty($sucursal_id)) {
        $lamda = obtenerLamdaPersonal($personal_ci);
        $mu = obtenerMuPersonal($personal_ci);
    } elseif (empty($personal_ci) && !empty($sucursal_id)) {
        $lamda = obtenerLamdaSucursal($sucursal_id);
        $mu = obtenerMuSucursal($sucursal_id);
    } elseif (!empty($personal_ci) && !empty($sucursal_id)) {
        $lamda = obtenerLamdaPersonalSucursal($personal_ci, $sucursal_id);
        $mu = obtenerMuPersonalSucursal($personal_ci, $sucursal_id);
    }

    // Evitar división por cero
    if ($lamda != 0) {
        $x = 1 / $lamda;
    } else {
        $x = -1; // Si lamda es cero, x será cero
    }

    if ($mu != 0) {
        $u = 1 / $mu;
    } else {
        $u = -1; // Si mu es cero, u será cero
    }


//$x = 3;
//$u = 4;



    // Calcular resultados
$Wq = round(floatval(calcularWq($x, $u)), 2);
$Lq = round(floatval(calcularLq($x, $u)), 2); 
$Ls = round(floatval(calcularLs($x, $u)), 2); 
$Ws = round(floatval(calcularWs($x, $u)), 2);

$ro = round(floatval(calcularRo($x, $u)), 2);
$ro_muestra = round(floatval(calcularRo($x, $u)), 2)*100;

$n = 0;
$t = 1;

$pn = round(floatval(calcularProbabilidadN($ro, $n)), 2)*100; 
$pWqT = round(floatval(calcularWqt($u, $ro ,$t) ), 2)*100; 
$pWsT =round(floatval(calcularWSt($u, $ro ,$t)), 2)*100; 


    // Convertir resultados según la unidad de tiempo seleccionada
    switch ($unidad_tiempo) {
        case 'minutos':
            $x *= 60;
            $u *= 60;
            $Wq /= 60;
            $Ws /= 60;
            $Lq *= 60;
            $Ls *= 60;

            $x = round($x, 2);
            $u = round($u, 2);
            $Wq = round($Wq, 2);
            $Ws = round($Ws, 2);
            $Lq = round($Lq, 2);
            $Ls = round($Ls, 2);

            $ro = round(floatval(calcularRo($x, $u)), 2);
            $ro_muestra = round(floatval(calcularRo($x, $u)), 2)*100;

            $pn = round(floatval(calcularProbabilidadN($ro, $n)), 2)*100; 
            $pWqT = round(floatval(calcularWqt($u, $ro ,$t) ), 2)*100; 
            $pWsT =round(floatval(calcularWSt($u, $ro ,$t)), 2)*100; 

            break;
        case 'horas':
            $x *= 3600;
            $u *= 3600;
            $Wq /= 3600;
            $Ws /= 3600;
            $Lq *= 3600;
            $Ls *= 3600;

            $x = round($x, 2);
            $u = round($u, 2);
            $Wq = round($Wq, 2);
            $Ws = round($Ws, 2);
            $Lq = round($Lq, 2);
            $Ls = round($Ls, 2);

            $ro = round(floatval(calcularRo($x, $u)), 2);
            $ro_muestra = round(floatval(calcularRo($x, $u)), 2)*100;

            $pn = round(floatval(calcularProbabilidadN($ro, $n)), 2)*100; 
            $pWqT = round(floatval(calcularWqt($u, $ro ,$t) ), 2)*100; 
            $pWsT =round(floatval(calcularWSt($u, $ro ,$t)), 2)*100; 

            break;
        // En el caso de 'segundos', no se necesita conversión
    }

}

// Mostrar el formulario
?>
<form action="" method="POST">
<div class="row">
    <div class="col-md-3 mb-3">
    <label for="personal_ci" class="form-label">Personal:</label>
    <select id="personal_ci" name="personal_ci" class="form-select hvr-float">
        <option value="">Todos</option>
        <?php while ($row = mysqli_fetch_assoc($resultado_personal)) { ?>
            <option value="<?php echo $row['ci']; ?>" <?php if (isset($personal_ci) && $personal_ci == $row['ci']) echo 'selected'; ?>><?php echo $row['nombre']; ?> <?php echo $row['apellido']; ?></option>
        <?php } ?>
    </select>
    </div>
    <div class="col-md-3 mb-3">
    <label for="sucursal_id" class="form-label">Sucursal:</label>
    <select id="sucursal_id" name="sucursal_id" class="form-select hvr-float">
        <option value="">Todas</option>
        <?php while ($row = mysqli_fetch_assoc($resultado_sucursal)) { ?>
            <option value="<?php echo $row['id']; ?>" <?php if (isset($sucursal_id) && $sucursal_id == $row['id']) echo 'selected'; ?>><?php echo $row['nombre']; ?> </option>
        <?php } ?>
    </select>
    </div>
    <div class="col-md-3 mb-3">
    <label for="unidad_tiempo" class="form-label">Unidad de tiempo:</label>
    <select id="unidad_tiempo" name="unidad_tiempo" class="form-select hvr-float">
        <option value="segundos" <?php if ($unidad_tiempo == 'segundos') echo 'selected'; ?>>Segundos</option>
        <option value="minutos" <?php if ($unidad_tiempo == 'minutos') echo 'selected'; ?>>Minutos</option>
        <option value="horas" <?php if ($unidad_tiempo == 'horas') echo 'selected'; ?>>Horas</option>
    </select>
    </div>
    <div class="col-md-3 mb-3 d-grid gap-2">
        <br>
        <button type="submit" class="btn btn-primary hvr-float">Mostrar</button>
    </div>
</div>
</form>


<!-- Showing results -->
<?php if ($x == -1 || $u == -1) { ?>
    <div class="container mt-5">
        <p class="text-center">No se encontraron ventas suficientes.</p>
    </div>
<?php } else { ?>
<div class="container mt-2">
    <div class="row">
        <!-- Lamda and Mu -->
        <div class="col-md-6  ">
            <div class="card mb-3 ">
                <div class="card-body">
                    <h5 class="card-title">Lamda</h5>
                    <p class="card-text"><?php echo $x . " personas por " . $unidad_tiempo; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6  ">
            <div class="card mb-3 ">
                <div class="card-body">
                    <h5 class="card-title">Mu</h5>
                    <p class="card-text"><?php echo $u . " personas por " . $unidad_tiempo; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Wq and Lq -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tiempo de espera en la cola (Wq)</h5>
                    <p class="card-text"><?php echo $Wq . " " . $unidad_tiempo; ?> por persona</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Número promedio de clientes en la cola (Lq)</h5>
                    <p class="card-text"><?php echo $Lq . " personas por " . $unidad_tiempo; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ls and Ws -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Número promedio de clientes en el sistema (Ls)</h5>
                    <p class="card-text"><?php echo $Ls . " personas por " . $unidad_tiempo; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tiempo de espera en el sistema (Ws)</h5>
                    <p class="card-text"><?php echo $Ws . " " . $unidad_tiempo; ?> por persona</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Ro -->
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Ro</h5>
                    <p class="card-text"><?php echo $ro_muestra . "%"; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pn -->
    <div class="row">
        <div class="col-md-12 mb-3 mx-auto">
            <input type="number" class="form-control" id="n_valor" name="n_valor" value="<?php echo $n; ?>" onchange="calcularPn()">
        </div>
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Probabilidad de <span id="n_valor_display"><?php echo $n; ?></span> personas en el sistema</h5>
                    <p class="card-text" id="pn_result"><?php echo $pn . "%"; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- t_valor -->
    <div class="row">
        <div class="col-md-12 mb-3 mx-auto">
            <input type="number" step="0.01" class="form-control" id="t_valor" name="t_valor" value="<?php echo $t; ?>" onchange="calcularPTiempos()">
        </div>
        <!-- pWsT -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Probabilidad de tiempo en el sistemas mayor a <span id="t_valor_display"><?php echo $t." ". $unidad_tiempo; ?></span></h5>
                    <p class="card-text" id="pWsT_result"><?php echo $pWsT . "%"; ?></p>
                </div>
            </div>
        </div>
        <!-- pWqT -->
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Probabilidad de tiempo de la cola mayor a <span id="t_valor_display_1"><?php echo $t." ". $unidad_tiempo; ?></span></h5>
                    <p class="card-text" id="pWqT_result"><?php echo $pWqT . "%"; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    // JavaScript para calcular y actualizar valores de pn, pWsT y pWqT
function calcularPn() {

     var n_valor = document.getElementById("n_valor").value;
   

    // Obtener el valor del input n_valor
    var n = parseFloat(document.getElementById("n_valor").value);

    var ro = <?php echo $ro; ?>;
    var pn = Math.round(((1 - ro) * (ro ** n) * 100) * 100) / 100;
 // Usamos el operador ** para calcular la potencia

    // Actualizar el texto en el elemento HTML
    document.getElementById("pn_result").textContent = pn + "%";
     document.getElementById("n_valor_display").innerText = n_valor;
}


function calcularPTiempos() {
    // Obtener el valor del input t_valor

    var t_valor = document.getElementById("t_valor").value;
    

    var t = parseFloat(document.getElementById("t_valor").value);

    // Pasar los valores de $ro y $u desde PHP a JavaScript
    var ro = <?php echo $ro; ?>;
    var u = <?php echo $u; ?>;

    // Calcular pWsT y pWqT usando los valores de $ro, $u y t
    var pWsT = Math.round((ro * Math.pow(Math.E, (-u * (1 - ro) * t)) * 100) * 100) / 100;
    var pWqT = Math.round((Math.pow(Math.E, (-u * (1 - ro) * t)) * 100) * 100) / 100;


    // Actualizar el texto en los elementos HTML correspondientes
    document.getElementById("pWsT_result").textContent = pWsT + "%";
    document.getElementById("pWqT_result").textContent = pWqT + "%";
    document.getElementById("t_valor_display").innerText = t_valor + " " + "<?php echo $unidad_tiempo; ?>";
    document.getElementById("t_valor_display_1").innerText = t_valor + " " + "<?php echo $unidad_tiempo; ?>";
}


</script>

<?php } ?>




    </main>
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
