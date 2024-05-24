<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EOQ basico</title>
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
            <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="eoq.php">Datos</a>
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
            <a class="nav-link hvr-float fw-bold active" href="eoq.php">EOQ basico</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold" href="eoq_p.php">EOQ con faltantes planeados</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold" href="teoria_colas.php">Teoria de colas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bold" href="cadenas_markov.php">Cadenas de Markov</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="col-md-10 ml-md-auto px-4 p-3">


            
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" class="form-label" method="post">
               <div class="row justify-content-center"> 
                <div class="col-md-3 mb-3 ">
                    <select name="opcion"  class="form-select hvr-float">
                        <option value="diario" <?php if(isset($_POST['opcion']) && $_POST['opcion'] == 'diario') echo 'selected="selected"'; ?>>EOQ Diario</option>
                        <option value="mensual" <?php if(isset($_POST['opcion']) && $_POST['opcion'] == 'mensual') echo 'selected="selected"'; ?>>EOQ Mensual</option>
                        <option value="anual" <?php if(isset($_POST['opcion']) && $_POST['opcion'] == 'anual') echo 'selected="selected"'; ?>>EOQ Anual</option>
                    </select>
                </div>
                
                
                <div class="col-md-3 mb-3 d-grid gap-2">
                    <input  type="submit" value="Seleccionar" class="btn btn-primary hvr-float">
                </div>
                </div>
            </form>

        

        <?php
        require_once $dir . "/funciones_EOQ.php";
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  $opcion = $_POST["opcion"];
                  switch ($opcion) {

                      case "diario":
                        $data = obtenerEoqDiario();
                        echo '<div class="col-md-6 mb-3 mx-auto">
                          <input type="text" id="buscarNombreDiario" class="form-control" placeholder="Buscar por nombre">
                      </div>';

                        echo "<div class='table-responsive'>";
                        echo "<table id='tablaDiario' class='table table-bordered text-center'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr>
                                <th>Producto ID</th>
                                <th>Nombre</th>
                                <th>Demanda diaria</th>
                                <th>Costo de ordenar</th>
                                <th>Costo de mantener</th>
                                <th>Costo de compra</th>
                                <th>Cantidad óptima de pedido</th>
                                <th>Tiempo de ciclo de producción</th>
                                <th>Número de ciclos de producción</th>
                                <th>Costo de producir un lote</th>
                                <th>Costo de mantener un lote en el inventario</th>
                                <th>Costo total por ciclo</th>
                                <th>Costo total diario</th>
                            </tr>";
                        echo "</thead>";
                        echo "<tbody class='table align-middle'>";

                        foreach ($data as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['producto_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . round(htmlspecialchars($row['demanda_diaria']), 3) . "</td>";
                            echo "<td>" . htmlspecialchars($row['precio_ordenar']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['precio_mantener']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['precio_compra']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cantidad_optima_pedido']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tiempo_ciclo_produccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_ciclos_produccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_producir_lote']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_mantener_lote_inventario']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_total_ciclo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_total_diario']) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                        echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const buscarNombreDiario = document.getElementById('buscarNombreDiario');
                                    const tablaDiario = document.getElementById('tablaDiario').getElementsByTagName('tbody')[0];
                                    const filasDiario = tablaDiario.getElementsByTagName('tr');

                                    function filtrarTablaDiario() {
                                        const nombreFiltro = buscarNombreDiario.value.toLowerCase();

                                        for (let i = 0; i < filasDiario.length; i++) {
                                            const celdas = filasDiario[i].getElementsByTagName('td');
                                            const nombre = celdas[1].textContent.toLowerCase();

                                            if (nombre.includes(nombreFiltro)) {
                                                filasDiario[i].style.display = '';
                                            } else {
                                                filasDiario[i].style.display = 'none';
                                            }
                                        }
                                    }

                                    buscarNombreDiario.addEventListener('keyup', filtrarTablaDiario);
                                });
                            </script>";
                        break;


                      case "mensual":
                        $data = obtenerEoqMensual();
                        echo '<div class="col-md-6 mb-3 mx-auto">
                              <input type="text" id="buscarNombreMensual" class="form-control" placeholder="Buscar por nombre">
                          </div>';
                        echo "<div class='table-responsive'>";
                        echo "<table id='tablaMensual' class='table table-bordered text-center'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr>
                                <th>Producto ID</th>
                                <th>Nombre</th>
                                <th>Demanda mensual</th>
                                <th>Costo de ordenar</th>
                                <th>Costo de mantener</th>
                                <th>Costo de compra</th>
                                <th>Cantidad óptima de pedido</th>
                                <th>Tiempo de ciclo de producción</th>
                                <th>Número de ciclos de producción</th>
                                <th>Costo de producir un lote</th>
                                <th>Costo de mantener un lote en el inventario</th>
                                <th>Costo total por ciclo</th>
                                <th>Costo total mensual</th>
                            </tr>";
                        echo "</thead>";
                        echo "<tbody class='table align-middle'>";

                        foreach ($data as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['producto_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . round(htmlspecialchars($row['demanda_mensual']), 3) . "</td>";
                            echo "<td>" . htmlspecialchars($row['precio_ordenar']) . "</td>";
                            echo "<td>" . round(htmlspecialchars($row['precio_mantener'] / 30), 3) . "</td>";
                            echo "<td>" . htmlspecialchars($row['precio_compra']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cantidad_optima_pedido']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tiempo_ciclo_produccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_ciclos_produccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_producir_lote']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_mantener_lote_inventario']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_total_ciclo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_total_mensual']) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";

                        echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const buscarNombreMensual = document.getElementById('buscarNombreMensual');
                                    const tablaMensual = document.getElementById('tablaMensual').getElementsByTagName('tbody')[0];
                                    const filasMensual = tablaMensual.getElementsByTagName('tr');

                                    function filtrarTablaMensual() {
                                        const nombreFiltro = buscarNombreMensual.value.toLowerCase();

                                        for (let i = 0; i < filasMensual.length; i++) {
                                            const celdas = filasMensual[i].getElementsByTagName('td');
                                            const nombre = celdas[1].textContent.toLowerCase();

                                            if (nombre.includes(nombreFiltro)) {
                                                filasMensual[i].style.display = '';
                                            } else {
                                                filasMensual[i].style.display = 'none';
                                            }
                                        }
                                    }

                                    buscarNombreMensual.addEventListener('keyup', filtrarTablaMensual);
                                });
                            </script>";
                        break;
                      case "anual":
                        $data = obtenerEoqAnual();
                        echo '<div class="col-md-6 mb-3 mx-auto">
                              <input type="text" id="buscarNombreAnual" class="form-control" placeholder="Buscar por nombre">
                          </div>';

                        echo "<div class='table-responsive'>";
                        echo "<table id='tablaAnual' class='table table-bordered text-center'>";
                        echo "<thead class='thead-dark'>";
                        echo "<tr>
                                <th>Producto ID</th>
                                <th>Nombre</th>
                                <th>Demanda anual</th>
                                <th>Costo de ordenar</th>
                                <th>Costo de mantener</th>
                                <th>Costo de compra</th>
                                <th>Cantidad óptima de pedido</th>
                                <th>Tiempo de ciclo de producción</th>
                                <th>Número de ciclos de producción</th>
                                <th>Costo de producir un lote</th>
                                <th>Costo de mantener un lote en el inventario</th>
                                <th>Costo total por ciclo</th>
                                <th>Costo total anual</th>
                            </tr>";
                        echo "</thead>";
                        echo "<tbody class='table align-middle'>";

                        foreach ($data as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['producto_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . round(htmlspecialchars($row['demanda_anual']), 3) . "</td>";
                            echo "<td>" . htmlspecialchars($row['precio_ordenar']) . "</td>";
                            echo "<td>" . round(htmlspecialchars($row['precio_mantener'] / 365), 3) . "</td>";
                            echo "<td>" . htmlspecialchars($row['precio_compra']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cantidad_optima_pedido']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tiempo_ciclo_produccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['numero_ciclos_produccion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_producir_lote']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_mantener_lote_inventario']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_total_ciclo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['costo_total_anual']) . "</td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";

                        echo "<script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const buscarNombreAnual = document.getElementById('buscarNombreAnual');
                                    const tablaAnual = document.getElementById('tablaAnual').getElementsByTagName('tbody')[0];
                                    const filasAnual = tablaAnual.getElementsByTagName('tr');

                                    function filtrarTablaAnual() {
                                        const nombreFiltro = buscarNombreAnual.value.toLowerCase();

                                        for (let i = 0; i < filasAnual.length; i++) {
                                            const celdas = filasAnual[i].getElementsByTagName('td');
                                            const nombre = celdas[1].textContent.toLowerCase();

                                            if (nombre.includes(nombreFiltro)) {
                                                filasAnual[i].style.display = '';
                                            } else {
                                                filasAnual[i].style.display = 'none';
                                            }
                                        }
                                    }

                                    buscarNombreAnual.addEventListener('keyup', filtrarTablaAnual);
                                });
                            </script>";

                        break;

                      default:
                          echo "Opción no válida.";
                  }
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
