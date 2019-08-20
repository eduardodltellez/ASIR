<?php
require "../seguridad.php";
require "../conexion.php";

// Comprobacion de seguridad para ver si hemos recibido datos del formulario y el usuario es de nivel 2
if ( !isset($_POST["login"]) || $nivel != 2 ) {

    header ('Location: crear_usuario.php');

    exit();
}

// Conexión con la bd
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Checkeo de la conexión
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

// Recibir los datos del formulario y guardarlos en variables
$login = $_POST["login"];
$nombre = $_POST["nombre"];
$clave = $_POST["clave"];
$nivel = $_POST["nivel"];

// Insertar al nuevo usuario en la tabla correspondiente (la clave va cifrada en MD5 por seguridad)
$consulta = "INSERT INTO usuarios VALUES ('$login', '$nombre', MD5('$clave'), $nivel)";

if (mysqli_query($conexion, $consulta)) { // Si tenemos éxito al insertar
   
   header ('Location: crear_usuario.php?exito=si');
} 
else { // Si no tenemos éxito

   header ('Location: crear_usuario.php?exito=no');
}

mysqli_close($conexion);

?>