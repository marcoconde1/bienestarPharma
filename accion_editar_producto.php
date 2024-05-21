<?php
require_once "funciones_producto.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $cantidad = intval($_POST['cantidad']);
    $precio = floatval($_POST['precio']);
    $categoria_id = intval($_POST['categoria_id']);
    $marca_id = intval($_POST['marca_id']);

    $precio_ordenar = floatval($_POST['precio_ordenar']);
    $precio_mantener = floatval($_POST['precio_mantener']);
    $precio_compra = floatval($_POST['precio_compra']);
    $precio_faltantes = floatval($_POST['precio_faltantes']);

    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagen = $_FILES['imagen'];

        // Validar el tipo de imagen
        $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
        if (!in_array($imagen['type'], $allowed_types)) {
            echo "Error: Formato de imagen no admitido.";
            exit();
        }

        // Validar el tamaño de la imagen
        $max_file_size = 10 * 1024 * 1024; // 10 MB
        if ($imagen['size'] > $max_file_size) {
            echo "Error: La imagen es demasiado grande.";
            exit();
        }

        $imagen_path = $imagen['tmp_name'];

        // Llamar a editarProducto con la imagen
        editarProducto($id, $nombre, $cantidad, $precio, $imagen_path, $categoria_id, $marca_id);
    } else {
        // Llamar a editarProducto sin imagen
        editarProducto($id, $nombre, $cantidad, $precio, "", $categoria_id, $marca_id);

        // Manejar errores de carga de imagen
        if(isset($_FILES['imagen'])) {
            switch ($_FILES['imagen']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    echo "El archivo es demasiado grande.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    echo "El archivo se cargó parcialmente.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                    echo "Error en el servidor al cargar la imagen.";
                    break;
                default:
                    echo "Error desconocido al cargar la imagen.";
                    break;
            }
        } else {
            echo "Error: No se seleccionó ninguna imagen.";
        }
    }

    // Llamar a editarLote en ambos casos
    editarLote($id, $cantidad, $precio_ordenar, $precio_mantener, $precio_compra, $precio_faltantes);

    // Redirigir después de editar
    header("Location: mostrar_productos.php");
    exit();
} else {
    echo "Error: Método de solicitud incorrecto.";
}
?>
