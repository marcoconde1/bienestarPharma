<?php
    function conectarse()
    {
        $server = "localhost";
        $username= "root";
        $pass = "";
        $database = "bienestarpharma";
        $con = mysqli_connect($server, $username, $pass) or die ("Error en la conexion");
        $link = mysqli_select_db($con, $database) or die ("Error en la conexion a la base de datos");
        return $con;
    }
?>