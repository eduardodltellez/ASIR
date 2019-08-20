<?php

//Inicio la sesión
session_start();

$login = $_SESSION["login"];
$nombre = $_SESSION["nombre"];
$nivel = $_SESSION["nivel"];

//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
if ($_SESSION["autentificado"] != "SI") {  // si la variable de sesión autentificado no es igual a SI

	//le envio a la página de inicio
	header("Location: ../index.php");

	//ademas salgo de este script
	exit();
}

// Si está autenticado
else {

	//calculamos el tiempo transcurrido desde que se ha autentificado
	$fechaGuardada = $_SESSION["ultimoAcceso"];
	$ahora = date("Y-n-j H:i:s");
	$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); // segundos transcurridos

	//comparamos el tiempo transcurrido en segundos
	if( $tiempo_transcurrido >= 300 ) {
   		//si pasaron 5 minutos o más
   		session_destroy(); // destruyo la sesión
   		header("Location: ../index.php"); // y le envío a la pagina de inicio
	}

	else { // sino ha pasado el tiempo, actualizo la fecha de la sesión

  		$_SESSION["ultimoAcceso"] = $ahora; // reinicializa el tiempo transcurrido

     	}	
}

?>