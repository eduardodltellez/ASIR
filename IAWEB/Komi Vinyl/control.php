<?php

require "conexion_bd.php";

$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Chequear la conexión
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

// Sacamos del formulario del login el usuario y contraseña recibidos
$usuario=$_POST["usuario"];
$clave=$_POST["password"];

// Vemos si el usuario y la contraseña son válidos

$sql="select nivel, nombre from usuarios where login='$usuario' and clave=md5('$clave')";

$resultado = mysqli_query($conexion, $sql);

// si la consulta me devuelve una fila es que existe la combinación login+clave en la tabla de usuarios
if (mysqli_num_rows($resultado) > 0) {  

//entonces como el usuario y la contraseña son válidos
//defino una sesion y guardo los datos de la misma
session_start();
$_SESSION["autentificado"] = "SI";

//sacar el nivel, el login y el nombre del usuario de la bd
$resultado = mysqli_query($conexion, $sql);

$fila = mysqli_fetch_assoc($resultado);

$_SESSION["nivel"] = $fila["nivel"];
$_SESSION["usuario"] = $fila["usuario"];
$_SESSION["nombre"] = $fila["nombre"];

// marcamos como acceso este mismo momento
$_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");

// y lo envío al index ya logueado
header ("Location: index.php");

}

//si la combinación login+clave no existe, le mando otra vez a la pagina de login marcando un error de acceso
else {
	header("Location: login.php?errorusuario=si");
}

?>