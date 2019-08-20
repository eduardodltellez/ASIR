<?php

require "conexion.php";

$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Chequear la conexión
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

// Sacamos del formulario del login el usuario y contraseña recibidos
$usuario=$_POST["usuario"];
$clave=$_POST["password"];

// Vemos si el usuario y la contraseña son válidos
$sql="select login, nombre, nivel from usuarios where login='$usuario' and password=md5('$clave')";

$resultado = mysqli_query($conexion, $sql);

// si la consulta me devuelve una fila es que existe la combinación usuario+password en la tabla usuarios
if (mysqli_num_rows($resultado) > 0) {  

// entonces como el usuario y la contraseña son válidos
// defino una sesión y guardo los datos de la misma
session_start();
$_SESSION["autentificado"] = "SI";

//sacar el login, el nombre y el nivel del usuario de la bd y guardarlos en variables de sesión
$resultado = mysqli_query($conexion, $sql);

$fila = mysqli_fetch_assoc($resultado);

$_SESSION["login"] = $fila["login"];
$_SESSION["nombre"] = $fila["nombre"];
$_SESSION["nivel"] = $fila["nivel"];

// marcamos como acceso este mismo momento
$_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");

// y lo envío a la página de alertas ya logueado
header ("Location: alertas/inicio.php");

}

//si la combinación login+clave no existe, le mando otra vez a la pagina de login marcando un error de acceso
else {
	header("Location: index.php?errorusuario=si");
}

?>