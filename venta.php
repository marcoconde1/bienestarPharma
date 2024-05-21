<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Venta</title>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/hover.css" rel="stylesheet" media="all">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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
    $sql_permiso = "SELECT nombre ,permiso FROM personal WHERE ci = '$ci'";
    $resultado_permiso = mysqli_query($conexion, $sql_permiso);
    $row = mysqli_fetch_assoc($resultado_permiso);
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
            <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="venta.php">Venta</a>
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
            <a class="nav-link hvr-float fw-bolder" aria-current="page" href="mostrar_marcas.php">Marcas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder" aria-current="page" href="mostrar_categorias.php">Categorias</a>
          </li>
        <?php } ?>
        <?php if ($row['permiso'] == 3) { ?>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder active" aria-current="page" href="venta.php">Venta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link hvr-float fw-bolder " aria-current="page" href="mostrar_productos.php">Productos</a>
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

    <?php
    $dir = dirname(__FILE__);
    require_once $dir . "\accion_conexion.php";
    $conexion = conectarse();

    $sql_productos = "SELECT id, nombre, precio, cantidad FROM producto WHERE cantidad > 1"; // Agregado cantidad a la consulta
    $resultado_productos = mysqli_query($conexion, $sql_productos);

    $sql_personal = "SELECT ci, nombre, apellido FROM personal";
    $resultado_personal = mysqli_query($conexion, $sql_personal);

    $sql_sucursal = "SELECT id, nombre FROM sucursal";
    $resultado_sucursal = mysqli_query($conexion, $sql_sucursal);
    ?>
<div class="container mt-1 mb-5">

    <div class="row justify-content-center mt-3 ">
        <div class=" col-lg-5 mt-3  p-4 border rounded">
            <form id="formularioCrearVenta" action="accion_anadir_venta.php" method="post" >
                <div class="mb-3">
                    <label for="ticket" class="form-label">Ticket:</label>
                    <input type="text" id="ticket" name="ticket" class="form-control" required onBlur="verificarTicketExistente()">
                </div>
                <div class="mb-3">
                    <label for="cliente_ci" class="form-label">Cliente CI:</label>
                    <input type="text" id="cliente_ci" name="cliente_ci" class="form-control" required>
                </div>
                <input type="text" id="personal_ci" name="personal_ci" class="form-control" value="<?php echo $ci; ?>" required style="display: none;">
                <div class="mb-3">
                    <label for="sucursal_id" class="form-label">Sucursal:</label>
                    <select id="sucursal_id" name="sucursal_id" class="form-select" required>
                        <option value="">Selecciona la sucursal</option>
                          <?php while ($row = mysqli_fetch_assoc($resultado_sucursal)) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
                            <?php } ?>
                    </select>
                </div>
                
            </form>
        </div>
        <div class="col-lg-5 mt-3  ">

            <div class="table-responsive">
                <table id="tablaProductosAgregados" class="table table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                            <th>Quitar</th>
                        </tr>
                    </thead>
                    <tbody id="productosAgregadosBody" class="table align-middle">
                        <!-- Filas generadas dinámicamente -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th id="totalProductos">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="text-center mb-2 mt-4 d-grid gap-2 col-5 mx-auto">
                    <button type="button" class="btn btn-primary hvr-float" onclick="vender()">Vender</button>
        </div>
    </div>

    <div class="row justify-content-center">
        
    </div>

    <div class="row justify-content-center mt-3">
        <div class="col-lg-10">
          
            <div class="mb-3">
                <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar por producto">
            </div>
            <div class="table-responsive mb-4" id="tablaProductosDisponibles">
                <table id="tablaProductos" class="table table-bordered text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad Disponible</th>
                            <th>Cantidad a Vender</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="table align-middle">
                        <?php while ($row = mysqli_fetch_assoc($resultado_productos)) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['precio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                                    <td>
                                        <input type="number" name="cantidad_<?php echo $row['id']; ?>" value="1" min="1" max="<?php echo $row['cantidad']; ?>" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-dark hvr-float" onclick="agregarProducto('<?php echo $row['id']; ?>')">Añadir</button>
                                    </td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



    <script>
document.addEventListener("DOMContentLoaded", function() {
    const buscarProducto = document.getElementById("buscarProducto");
    const tablaProductos = document.getElementById("tablaProductos").getElementsByTagName("tbody")[0];
    const filas = tablaProductos.getElementsByTagName("tr");

    function filtrarTabla() {
        const filtro = buscarProducto.value.toLowerCase();

        for (let i = 0; i < filas.length; i++) {
            const celdas = filas[i].getElementsByTagName("td");
            const nombreProducto = celdas[0].textContent.toLowerCase();

            if (nombreProducto.includes(filtro)) {
                filas[i].style.display = "";
            } else {
                filas[i].style.display = "none";
            }
        }
    }

    buscarProducto.addEventListener("keyup", filtrarTabla);
});
</script>

    <script>
       var productosAgregados = []; // Estructura de datos para almacenar productos agregados

function agregarProducto(idProducto) {
    var cantidadInput = document.querySelector('input[name="cantidad_' + idProducto + '"]');
    var cantidad = parseInt(cantidadInput.value, 10);
    var filaProducto = cantidadInput.closest('tr');
    var precio = parseFloat(filaProducto.querySelector('td:nth-child(2)').innerText);
    var nombreProducto = filaProducto.querySelector('td:nth-child(1)').innerText;
    var ticket = document.getElementById("ticket").value;

    // Calcular el subtotal
    var subtotal = cantidad * precio;

    // Verificar que productosAgregados esté definido
    if (typeof productosAgregados === 'undefined') {
        productosAgregados = [];
    }

    // Agregar la información del producto a la estructura de datos
    productosAgregados.push({
        ticket: ticket,
        idProducto: idProducto,
        cantidad: cantidad,
        precio: precio,
        subtotal: subtotal
    });

    // Crear una nueva fila para la tabla de productos añadidos
    var fila = document.createElement("tr");
    var celdaNombre = document.createElement("td");
    var celdaCantidad = document.createElement("td");
    var celdaPrecio = document.createElement("td");
    var celdaSubtotal = document.createElement("td");
    var celdaQuitar = document.createElement("td");
    var botonQuitar = document.createElement("button");

    botonQuitar.textContent = "Quitar";
    botonQuitar.className = "btn btn-dark hvr-float";
    botonQuitar.addEventListener("click", function () {
        var indice = Array.from(fila.parentNode.children).indexOf(fila);
        productosAgregados.splice(indice, 1);
        fila.remove();
        actualizarTotal();
    });

    celdaNombre.textContent = nombreProducto;
    celdaCantidad.textContent = cantidad;
    celdaPrecio.textContent = precio.toFixed(2);
    celdaSubtotal.textContent = subtotal.toFixed(2); // Formatear el subtotal a dos decimales
    celdaQuitar.appendChild(botonQuitar);

    fila.appendChild(celdaNombre);
    fila.appendChild(celdaCantidad);
    fila.appendChild(celdaPrecio);
    fila.appendChild(celdaSubtotal);
    fila.appendChild(celdaQuitar);

    document.getElementById("productosAgregadosBody").appendChild(fila);

    // Actualizar el total
    actualizarTotal();

    console.log("ID del producto:", idProducto);
    console.log("Cantidad:", cantidad);
}

// Función para actualizar el total
function actualizarTotal() {
    var total = productosAgregados.reduce(function (suma, producto) {
        return suma + producto.subtotal;
    }, 0);

    document.getElementById("totalProductos").textContent = total.toFixed(2);
}


function vender() {
    var listaProductosInput = document.createElement("input");
    listaProductosInput.type = "hidden";
    listaProductosInput.name = "listaProductos";
    listaProductosInput.value = JSON.stringify(productosAgregados);
    document.getElementById("formularioCrearVenta").appendChild(listaProductosInput);

    document.getElementById("formularioCrearVenta").submit();
}




function verificarTicket(ticket) {
    // Realizar una solicitud AJAX para verificar el ticket en el servidor
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "accion_verificar_ticket.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var respuesta = xhr.responseText;
                if (respuesta === "existe") {
                    // El ticket existe, no hacer nada
                } else if (respuesta === "no_existe") {
                    // El ticket no existe, borrar el valor del campo
                    document.getElementById("ticket").value = "";
                    alert("Ticket invalido.");
                } else {
                    alert("Error: " + respuesta);
                }
            } else {
                alert("Error: No se pudo completar la solicitud.");
            }
        }
    };
    xhr.send("ticket=" + ticket);
}

   
    // Evento que se activa cuando cambia el valor del campo de entrada de ticket
    document.getElementById("ticket").addEventListener("change", function () {
        var ticket = this.value;
        if (ticket.trim() !== "") {
            // Si se ingresó un valor en el campo, verificar el ticket
            verificarTicket(ticket);
        }
    });

    function validarYEnviar() {
    if (document.getElementById("formularioCrearVenta").checkValidity()) {
        verificarTicketExistente();
        vender();
    } else {
        alert("Por favor, complete todos los campos requeridos.");
    }
}
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
