<?php

//Inicio la sesión
session_start();

$nivel=$_SESSION["nivel"];
$nombre=$_SESSION["nombre"];

//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
if ($_SESSION["autentificado"] != "SI") {  // si la variable de sesión autentificado no es igual a SI

	//le envio a la página de inicio
	header("Location: index.php");

	//ademas salgo de este script
	exit();
}

elseif ($nivel <> 2) { // además si el nivel de acceso no es de 2 también lo hecho al index

	//le envio a la página de inicio
	header("Location: index.php");

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
	if($tiempo_transcurrido >= 180) {
   		//si pasaron 3 minutos o más
   		session_destroy(); // destruyo la sesión
   		header("Location: index.php"); //envío al usuario a la pagina de inicio
	}

	else { //sino ha pasado el tiempo, actualizo la fecha de la sesión
  		$_SESSION["ultimoAcceso"] = $ahora; // reinicializa el tiempo transcurrido
     	}	
}

?>