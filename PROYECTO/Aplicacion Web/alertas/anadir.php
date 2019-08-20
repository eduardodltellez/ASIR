<?php
require "../seguridad.php";
require "../conexion.php";

// Comprobacion de seguridad para ver si hemos recibido datos del formulario
if ( !isset($_POST["boton_radio"]) ) {

    header ('Location: anadir_alerta.php');

    exit();
}

// Conexión con la bd
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Checkeo de la conexión
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

// Sacar el sid y rev para crear la regla de Snort
$consulta = "SELECT sid, rev FROM reglas ORDER BY sid DESC LIMIT 1";

$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {  // si hay resultado

    // sumamos 1 al sid y rev más altos existentes que serán los de la nueva regla
	$fila = mysqli_fetch_assoc($resultado);
	$sid = $fila["sid"]+1;
	$rev = $fila["rev"]+1;
} 
else { // Si no hay resultado esque no hay reglas creadas, entonces ponemos sid y rev a 1
	$sid = 1;
	$rev = 1;
}

// Asignar el valor correspondiente a la variable $origen dependiendo de lo que recibamos del formulario
if ( $_POST["boton_radio"] == "equipo" ) {
	$origen = $_POST["IP_equipo"];
}
else {
	$origen = $_POST["boton_radio"];
}

// Recoger el resto de datos del formulario y asignarlos a sus variables correspondientes
$URL = $_POST["URL"];
$mensaje = $_POST["mensaje"];

// Sacar al usuario que está creando la alerta, ya que está incluido en la variable $login de seguridad.php
$usuario = $login;

// Comprobamos que la URL introducida por el usuario no esté ya en alguna regla de la bd
$consulta = "SELECT sid FROM reglas WHERE url='$URL'";

$resultado = mysqli_query($conexion, $consulta);

// Si hay coincidencia de URL's le mandamos de vuelta a la página de alertas marcando un error
if ( mysqli_num_rows($resultado) > 0 ) {

	header ('Location: anadir_alerta.php?URLexiste=si');

	exit();
}

///// Insertar las opciones que hemos recogido del formulario de la nueva regla en la bd
$insertar = "INSERT INTO reglas VALUES ($sid, $rev, '$origen', '$URL', '$mensaje', '$usuario')";

if (mysqli_query($conexion, $insertar)) {
    $insercion = true;
} 
else {
    echo "Error al insertar en la base de datos: " . $insertar . "<br>" . mysqli_error($conexion);
}

//// Abrir el fichero local.rules de Snort para escribir las reglas de la bd en el mismo
$fichero = fopen("/etc/snort/rules/local.rules","w") or die ("Hubo algún problema al abrir el fichero!");

// Consulta a la bd para ir sacando las reglas
$consulta = "SELECT sid, rev, origen, url, mensaje, usuario FROM reglas";

$resultado = mysqli_query($conexion, $consulta);

// Variable para introducir un salto de línea entre regla y regla
$salto_linea = "\n";

/////// Bucle para ir consultando en la bd e ir insertando en el fichero las reglas
if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

    while($fila = mysqli_fetch_assoc($resultado)) {

    	// Sacamos los resultados a variables
    	$origen=$fila["origen"];
    	$mensaje=$fila["mensaje"];
    	$url=$fila["url"];
    	$sid=$fila["sid"];
    	$rev=$fila["rev"];

    	/// Escribimos la regla en el fichero
    	fwrite($fichero, 'alert tcp ' . $origen . ' any -> any any (msg: "' . $mensaje . '"; content: "' . $url . '"; sid:' . $sid. '; rev:' . $rev . ';)' . $salto_linea);
	}
}
else {
	echo "No hay reglas en la base de datos!";
}

// Cerramos el fichero y la conexión con la bd
fclose($fichero);
mysqli_close($conexion);

// Reiniciamos el servicio de Snort para hacer efectivas las reglas
$comando = "sudo service snort restart";

exec($comando);

// Volvemos a la página de alertas
header ('Location: anadir_alerta.php?exito=si');

?>