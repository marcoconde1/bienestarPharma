<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Iniciar Sesión</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="css/hover.css" rel="stylesheet" media="all">
</head>
<body >

<div class="container position-absolute top-50 start-50 translate-middle ">
    <div class="row justify-content-center">
        <div class="col-md-3 ">
            <form action="accion_inciar_sesion.php" method="post">
                <img src="imagenes/logo.png" class="img-fluid hvr-grow" alt="Imagen">
                <div class="mb-3">
                <label class="form-label fw-bold" for="ci">CEDULA IDENTIDAD:</label><br>
                <input type="text" class="form-control" id="ci" name="ci"><br>
                </div>
                <div class="mb-3">
                <label class="form-label fw-bold" for="contrasena">CONTRASEÑA:</label><br>
                <input type="password" class="form-control" id="contrasena" name="contrasena"><br>
                </div>

                <div class="mb-3 text-center">
                <?php
                session_start();
                if(isset($_SESSION['error_message'])){
                    echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
                    unset($_SESSION['error_message']); // Limpiar el mensaje de error
                }
                ?>
                </div>
                <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-dark hvr-float">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- pie de pagina 
<footer class="bg-dark text-light text-center fixed-bottom">
    <div class="container">
        <p class="mt-4">&copy; 2024 BienestarPharma | Todos los derechos reservados.</p>
    </div>
</footer>
-->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
