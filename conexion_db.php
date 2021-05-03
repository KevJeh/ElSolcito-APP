<?php

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'facturacion';


$conexion= new mysqli($host, $user, $password, $db);
// mysqli_close($conexion);

if(mysqli_connect_errno())
{
    printf("Fallo la conexion al Servidor");
}

?>