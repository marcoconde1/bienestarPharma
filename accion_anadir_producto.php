<?php
require_once "funciones_producto.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $precio_ordenar = $_POST['precio_ordenar'];
    $precio_mantener = $_POST['precio_mantener'];
    $precio_compra = $_POST['precio_compra'];
    $precio_faltantes = $_POST['precio_faltantes'];

    $categoria_id = $_POST['categoria_id'];
    $marca_id = $_POST['marca_id'];

    if(isset($_FILES['imagen'])) {
        $imagen = $_FILES['imagen'];

        if ($imagen['error'] === UPLOAD_ERR_OK) {
            $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
            if (!in_array($imagen['type'], $allowed_types)) {
                echo "Error: Formato de imagen no admitido.";
                exit();
            }

            $max_file_size = 10 * 1024 * 1024; // 10 MB
            if ($imagen['size'] > $max_file_size) {
                echo "Error: La imagen es demasiado grande.";
                exit();
            }

            $imagen_path = $_FILES['imagen']['tmp_name'];


            agregarProducto($nombre, $cantidad, $precio, $imagen_path, $categoria_id, $marca_id);
            $idprod=obtenerUltimoIdProducto();
            agregarLote($idprod, $cantidad, $precio_ordenar, $precio_mantener, $precio_compra, $precio_faltantes);

            header("Location: mostrar_productos.php");
            exit();

        } else {
            echo "Error al cargar la imagen: ";
            switch ($imagen['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    echo "El archivo es demasiado grande.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    echo "El archivo se cargó parcialmente.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    echo "No se seleccionó ningún archivo.";
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
        }
    } else {
        echo "Error: No se seleccionó ninguna imagen.";
    }
} else {
    echo "Error: Método de solicitud incorrecto.";
}
?>


