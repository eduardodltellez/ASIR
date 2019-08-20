<?php

//Inicio la sesión
session_start();

$nivel=$_SESSION["nivel"];
$nombre=$_SESSION["nombre"];

//COMPRUEBA QUE EL USUARIO ESTA AUTENTIFICADO
if ($_SESSION["autentificado"] != "SI") {  // si la variable de sesión autentificado no es igual a SI

	//si no está autenticado, le envio a la página de inicio
	header("Location: index.php");

	//ademas salgo de este script
	exit();
}

else {

	//si está autentificado, calculamos el tiempo transcurrido desde que se autentifica
	$fechaGuardada = $_SESSION["ultimoAcceso"];
	$ahora = date("Y-n-j H:i:s");
	$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); // segundos transcurridos

	//comparamos el tiempo transcurrido en segundos
	if($tiempo_transcurrido >= 180) {
   		//si pasaron 3 minutos o más
   		session_destroy(); // destruyo la sesión
   		header("Location: index.php"); //envío al usuario a la pagina de inicio
	}

	else { // sino ha pasado el tiempo, actualizo la fecha de la sesión
  		$_SESSION["ultimoAcceso"] = $ahora; // reinicializa el tiempo transcurrido
     	}	
}

?>