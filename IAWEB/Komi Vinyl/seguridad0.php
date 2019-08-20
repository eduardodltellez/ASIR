<?php
// Inicio de la sesión
session_start();

// Si el usuario está autenticado correctamente (es decir existen variables de sesión)
if (isset($_SESSION['nivel'])) {
	// definición de las variables de sesión
	$nivel = $_SESSION["nivel"];
	$usuario = $_SESSION["usuario"];
	$nombre = $_SESSION["nombre"];
	$fechaGuardada = $_SESSION["ultimoAcceso"];

	// calculamos el tiempo transcurrido desde que se loguea	
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
// Si no está autenticado le pongo el nivel de usuario 0
else {
	$nivel=0;
}

?>