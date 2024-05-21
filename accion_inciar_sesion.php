<?php
$dir = dirname(__FILE__);
require_once $dir . "\accion_conexion.php";
session_start();
// Verificar si se enviaron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establecer conexión con la base de datos (asumiendo que ya tienes la conexión establecida)
    $conexion = conectarse();

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Obtener los datos del formulario
    $ci = $_POST["ci"];
    $contrasena = $_POST["contrasena"];

    // Consultar la base de datos para verificar las credenciales
    $sql = "SELECT * FROM personal WHERE ci = '$ci' AND contrasena = '$contrasena'";
    $resultado = $conexion->query($sql);

    // Verificar si se encontró un registro que coincida con las credenciales proporcionadas
    if ($resultado->num_rows > 0) {
        // Inicio de sesión exitoso
        $_SESSION["ci"] = $ci;
        // Redireccionar a la página de inicio o a otra página segura
        header("Location: menu_principal.php");
    } else {
        // Credenciales incorrectas, almacenar mensaje de error en la sesión
        $_SESSION['error_message'] = "Cédula de identidad o contraseña incorrecta.";
        // Redireccionar de vuelta al formulario de inicio de sesión
        header("Location: index.php");
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
}
?>
