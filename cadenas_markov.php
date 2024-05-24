<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadenas de Markov</title>
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


<div class="container-fluid " >
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
            <a class="nav-link hvr-float fw-bold" href="teoria_colas.php">Teoria de colas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold active" href="cadenas_markov.php">Cadenas de Markov</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-10 ml-md-auto px-4 p-3">

<?php
$dir = dirname(__FILE__);
require_once $dir . DIRECTORY_SEPARATOR . "accion_conexion.php";
$conexion = conectarse();

$cat_1 = "";



$sql_cat = "SELECT id, nombre FROM categoria";
$resultado_cat = mysqli_query($conexion, $sql_cat);
?>

<div class="d-flex justify-content-center">
    <form method="POST" action="">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="cat_1" class="form-label">Categoría:</label>
                <select id="cat_1" name="cat_1" class="form-select">
                    <option value="">Categoría</option>
                    <?php while ($row = mysqli_fetch_assoc($resultado_cat)) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php if (isset($_POST['cat_1']) && $_POST['cat_1'] == $row['id']) echo 'selected'; ?>><?php echo $row['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="n_valor" class="form-label">Dia:</label>
                <input type="number" class="form-control" id="n_valor" name="n_valor" value="<?php echo isset($_POST['n_valor']) ? $_POST['n_valor'] : ''; ?>">
            </div>
        </div>

        <div class="col-md-8 mb-3 d-grid gap-2">
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>


<?php
$dir = dirname(__FILE__);
require_once $dir . "/funciones_cadenas_markov.php";
require_once $dir . DIRECTORY_SEPARATOR . "accion_conexion.php";
$conexion = conectarse();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_1 = $_POST["cat_1"];
    $n = $_POST["n_valor"];

$bAA = 0;
$mAA = 0;
$sAA = 0;

//$demandaAA = array(5, 1, 1, 8, 5, 1, 1, 8, 5, 1);
//$demandaAA = array(15, 0, 30, 0, 0);
$demandaAA = obtenerVentasPorProductoCategoriaAntesDeAyer($cat_1); 

foreach ($demandaAA as $value) {
    if ($value == 0) {
        $sAA++;
    }
    if ($value >= 1 && $value <= 5) {
        $mAA++;
    }
    if ($value > 5) {
        $bAA++;
    }
}

$x11 = 0;
$x12 = 0;
$x13 = 0;
$x21 = 0;
$x22 = 0;
$x23 = 0;
$x31 = 0;
$x32 = 0;
$x33 = 0;

//$demandaA = array(4, 1, 2, 3, 5, 1, 1, 1, 6, 1);
$demandaA = obtenerVentasPorProductoCategoriaAyer($cat_1);
//$demandaA = array(15, 0, 30, 0, 0);


foreach ($demandaA as $key => $value) {
    if ($demandaAA[$key] == 0 && $value == 0) {
        $x33++;
    }
    if ($demandaAA[$key] == 0 && $value >= 1 && $value <= 5) {
        $x32++;
    }
    if ($demandaAA[$key] == 0 && $value > 5) {
        $x31++;
    }
    if ($demandaAA[$key] >= 1 && $demandaAA[$key] <= 5 && $value == 0) {
        $x23++;
    }
    if ($demandaAA[$key] >= 1 && $demandaAA[$key] <= 5 && $value >= 1 && $value <= 5) {
        $x22++;
    }
    if ($demandaAA[$key] >= 1 && $demandaAA[$key] <= 5 && $value > 5) {
        $x21++;
    }
    if ($demandaAA[$key] > 5 && $value == 0) {
        $x13++;
    }
    if ($demandaAA[$key] > 5 && $value >= 1 && $value <= 5) {
        $x12++;
    }
    if ($demandaAA[$key] > 5 && $value > 5) {
        $x11++;
    }
}

$bH = 0;
$mH = 0;
$sH = 0;

//$demandaH = array(1, 1, 5, 3, 4, 6, 1, 1, 2, 9);
$demandaH = obtenerVentasPorProductoCategoriaHoy($cat_1);
//$demandaH = array(15, 0, 30, 0, 0);

foreach ($demandaH as $value) {
    if ($value == 0) {
        $sH++;
    }
    if ($value >= 1 && $value <= 5) {
        $mH++;
    }
    if ($value > 5) {
        $bH++;
    }
}

// Matriz 3x3
$a11 = ($bAA != 0) ? (double)$x11 / $bAA : 0;
$a12 = ($bAA != 0) ? (double)$x12 / $bAA : 0;
$a13 = ($bAA != 0) ? (double)$x13 / $bAA : 0;

$a21 = ($mAA != 0) ? (double)$x21 / $mAA : 0;
$a22 = ($mAA != 0) ? (double)$x22 / $mAA : 0;
$a23 = ($mAA != 0) ? (double)$x23 / $mAA : 0;

$a31 = ($sAA != 0) ? (double)$x31 / $sAA : 0;
$a32 = ($sAA != 0) ? (double)$x32 / $sAA : 0;
$a33 = ($sAA != 0) ? (double)$x33 / $sAA : 0;

// Matriz 3x3
$matrix3x3 = array(
    array($a11, $a21, $a31),
    array($a12, $a22, $a32),
    array($a13, $a23, $a33)
);
// Matriz 3x1
$matrix3x1 = array(
    array($bH / count($demandaH)), //D. buena
    array($mH / count($demandaH)), //D. mala
    array($sH / count($demandaH)) //D. no hay
);

$n = $_POST["n_valor"]; // Número de veces que la matriz 3x3 se multiplica por sí misma

//for ($i = 0; $i < 3; $i++) {
//    for ($j = 0; $j < 3; $j++) {
//        echo $matrix3x3[$i][$j] . "  ";
//    }
//    echo "\n";
//}

//echo "\n";

//for ($i = 0; $i < 3; $i++) {
//    echo $matrix3x1[$i][0] . "\n";
//}

echo "\n";
// Método para multiplicar dos matrices 3x3
function multiplyMatrix3x3($A, $B) {
    $result = array_fill(0, 3, array_fill(0, 3, 0));
    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            $result[$i][$j] = 0;
            for ($k = 0; $k < 3; $k++) {
                $result[$i][$j] += $A[$i][$k] * $B[$k][$j];
            }
        }
    }
    return $result;
}

// Método para multiplicar una matriz 3x3 con una matriz 3x1
function multiplyMatrix3x3With3x1($A, $B) {
    $result = array_fill(0, 3, array(0));
    for ($i = 0; $i < 3; $i++) {
        $result[$i][0] = 0;
        for ($k = 0; $k < 3; $k++) {
            $result[$i][0] += $A[$i][$k] * $B[$k][0];
        }
    }
    return $result;
}

// Método para elevar una matriz 3x3 a la potencia n
function powerMatrix($matrix, $n) {
    $result = array_fill(0, 3, array_fill(0, 3, 0));
    // Inicializar result como la matriz identidad
    for ($i = 0; $i < 3; $i++) {
        $result[$i][$i] = 1;
    }
    for ($i = 0; $i < $n; $i++) {
        $result = multiplyMatrix3x3($result, $matrix);
    }
    return $result;
}

// Multiplicar la matriz 3x3 por sí misma n veces
$poweredMatrix = powerMatrix($matrix3x3, $n);

// Multiplicar la matriz resultante con la matriz 3x1
$result = multiplyMatrix3x3With3x1($poweredMatrix, $matrix3x1);

//for ($i = 0; $i < 3; $i++) {
//    echo $result[$i][0] . "\n";
//}
?>

<div class="container">
    <div class="row">
        <div class="col">
            <h4>Matrix de transicion:</h4>
            <table class="table table-bordered">
                <?php foreach ($matrix3x3 as $row) { ?>
                    <tr>
                        <?php foreach ($row as $cell) { ?>
                            <td><?php echo $cell; ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="col">
            <h4>Vector Inicial:</h4>
            <table class="table table-bordered">
                <?php foreach ($matrix3x1 as $row) { ?>
                    <tr>
                        <?php foreach ($row as $cell) { ?>
                            <td><?php echo $cell; ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h4>Resultado:</h4>
            <table class="table table-bordered">
                <?php foreach ($result as $row) { ?>
                    <tr>
                        <?php foreach ($row as $cell) { ?>
                            <td><?php echo $cell; ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<?php
} else {
    // Si no se recibe una solicitud POST, devuelve un error
    http_response_code(400);
   
}
 
    ?>



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
