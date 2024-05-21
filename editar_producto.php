<?php
session_start();
require_once "funciones_producto.php";
require_once "funciones_categoria.php";
require_once "funciones_marca.php";
require_once "accion_conexion.php";
require_once "funciones_producto.php";
$conexion = conectarse();

if (isset($_SESSION["ci"])) {

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $producto = obtenerProductoPorId($id);
    if ($producto) {
        // Consulta SQL para obtener las marcas
        $sql_marcas = "SELECT id, nombre FROM marca";
        $resultado_marcas = mysqli_query($conexion, $sql_marcas);

        // Consulta SQL para obtener las categorías
        $sql_categorias = "SELECT id, nombre FROM categoria";
        $resultado_categorias = mysqli_query($conexion, $sql_categorias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<?php


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
            <a class="nav-link hvr-float fw-bolder" aria-current="page" href="venta.php">Venta</a>
          </li>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder active" aria-current="page" href="mostrar_productos.php">Productos</a>
          </li>
        <?php } ?>
        <?php if ($row['permiso'] == 2) { ?>
          <li class="nav-item">
            <a class="nav-link  hvr-float fw-bolder active" aria-current="page" href="mostrar_productos.php">Productos</a>
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
            <a class="nav-link hvr-float fw-bolder active" aria-current="page" href="mostrar_productos.php">Productos</a>
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

<div class="container mt-5 mb-3">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <form method="post" action="accion_editar_producto.php" enctype="multipart/form-data" class="p-4 border rounded">
                <input type="hidden" name="id" value="<?= $producto['id'] ?>">
                <h2 class="text-center mb-4">Editar Producto</h2>

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= $producto['nombre'] ?>" class="form-control" required>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" value="<?= $producto['cantidad'] ?>" class="form-control" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="number" id="precio" name="precio" step="0.01" value="<?= $producto['precio'] ?>" class="form-control" required>
                        </div>
                    </div>
                </div>

                 <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label for="precio_ordenar" class="form-label">Costo por ordenar lote:</label>
                            <input type="number" id="precio_ordenar" name="precio_ordenar" step="0.01" value="<?= $producto['precio_ordenar'] ?>"class="form-control" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="precio_mantener" class="form-label">Costo por mantener diario:</label>
                            <input type="number" id="precio_mantener" name="precio_mantener" step="0.01" value="<?= $producto['precio_mantener'] ?>"class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col"> 
                        <div class="mb-3">
                            <label for="precio_compra" class="form-label">Costo de compra:</label>
                            <input type="number" id="precio_compra" name="precio_compra" step="0.01" value="<?= $producto['precio_compra'] ?>"class="form-control" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label for="precio_faltantes" class="form-label">Costo por faltantes diario:</label>
                            <input type="number" id="precio_faltantes" name="precio_faltantes"step="0.01" value="<?= $producto['precio_faltantes'] ?>" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*" onchange="previewImage(this)" class="form-control">
                    <div id="imagen-preview"></div>
                </div>

                <div class="row ">
                    <div class="col">
                        <div class="mb-3">
                        <label for="categoria_id" class="form-label">Categoría:</label>
                        <select id="categoria_id" name="categoria_id" class="form-select" required>
                            <option value="">Selecciona una categoría</option>
                            <?php while ($categoria = mysqli_fetch_assoc($resultado_categorias)) : ?>
                                <option value="<?= $categoria['id'] ?>" <?= ($categoria['id'] == $producto['categoria_id']) ? 'selected' : '' ?>>
                                    <?= $categoria['nombre'] ?>
                                </option>
                            <?php endwhile ?>
                        </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                        <label for="marca_id" class="form-label">Marca:</label>
                        <select id="marca_id" name="marca_id" class="form-select" required>
                            <option value="">Selecciona una marca</option>
                            <?php while ($marca = mysqli_fetch_assoc($resultado_marcas)) : ?>
                                <option value="<?= $marca['id'] ?>" <?= ($marca['id'] == $producto['marca_id']) ? 'selected' : '' ?>>
                                    <?= $marca['nombre'] ?>
                                </option>
                            <?php endwhile ?>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>

        </div>  
    </div>
</div>

    <script>
        function previewImage(input) {
            var preview = document.getElementById("imagen-preview");
            preview.innerHTML = "";

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = document.createElement("img");
                    img.src = e.target.result;
                    img.classList.add("img-thumbnail");
                    img.style.maxWidth = "250px";
                    img.style.maxHeight = "250px";
                    preview.appendChild(img);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
    } else {
        echo "No se encontró el producto.";
    }
} else {
    echo "Se requiere el ID del producto a editar.";
}
?>
<?php    
} else {
    // La sesión no está establecida, redirigir al formulario de inicio de sesión
    header("Location: index.php");
    exit; // Asegúrate de terminar la ejecución del script después de redirigir
}
?>
