<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tickets</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="css/hover.css" rel="stylesheet" media="all">



</head>
<body>

<?php 
require_once "funciones_ticket.php";
$ultimoTicket = obtenerUltimoTicket();
if ($ultimoTicket): 
?>  

<div class="container position-absolute top-50 start-50 translate-middle ">
    <div class="row justify-content-center">
        <div class="col-md-3 text-center">
            <img src="imagenes/logo.png" class="img-fluid hvr-grow" alt="Imagen">
                

                <div class="ticket-info bg-light p-3 rounded">
                    <div class="row">
                        <div class="col-6">
                            <strong>ID del Ticket:</strong>
                        </div>
                        <div class="col-6">
                            <?php echo $ultimoTicket["id"]; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Fecha de Emisión:</strong>
                        </div>
                        <div class="col-6">
                            <?php echo $ultimoTicket["fecha_emision"]; ?>
                        </div>
                    </div>
                </div>



            <form id="formularioCrearTicket" action="accion_crear_ticket.php" method="post">
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-dark hvr-float">Crear ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
  
       
<?php else: ?>

    <div class="container position-absolute top-50 start-50 translate-middle ">
    <div class="row justify-content-center">
        <div class="col-md-3 text-center">
            <img src="imagenes/logo.png" class="img-fluid hvr-grow" alt="Imagen">
<form id="formularioCrearTicket" action="accion_crear_ticket.php" method="post">
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-dark hvr-float">Crear ticket</button>
                </div>
            </form>
            </div>
        </div>
    </div>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
