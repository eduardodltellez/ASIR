<?php
require "../seguridad.php";
require "../conexion.php";

// Crear la conexion con la bd
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Checkear la conexion
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}
?>

<!-- Comienzo del código HTML -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    
<title>Control Web</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="../img/favicon.png" type="image/icon"/>
<link rel="stylesheet" type="text/css" href="../css/estilos.css"/>

</head>

<body>

<div class="todo_documento">

<?php
// Mostrar nombre del usuario si este está logueado
if (isset($_SESSION['nivel'])) {
	echo "<div id='log_usu'><img src='../img/usu.png' width='11'/> $nombre &nbsp;</div>";
}
?>

<!-- Cabecera -->

<div class="cabeceraprincipal">

<h1>CONTROL WEB</h1> 
    <a href="inicio.php">
        <img src="../img/logo_snort.png" alt="Inicio" title="Easy Proxy" width="395px" />
    </a>
</div>

<br/>
	<!-- Menú principal-->
    <ul>
        <li><a href="inicio.php"> Inicio </a></li>

        <li><a href='anadir_alerta.php'> Añadir Alerta </a></li>

        <li><a href='borrar_alerta.php' style="background-color: #0012ae"> Borrar Alerta </a></li>

        <li><a href='../salir.php'> Salir <img src='../img/exit.png' alt='Salir' width='15'/></a></li>

    </ul>

    <!-- Menú de administrador -->

    <?php

    if ( $nivel == 2 ) {

        echo "
        <ul class='admin'>

        <li><a href='../usuarios/crear_usuario.php'> Añadir Usuario </a></li>

        <li><a href='../usuarios/borrar_usuario.php'> Borrar Usuario </a></li>
     
        </ul>
        ";
    }

    ?>

<!-- Cuerpo variable -->

<div class="cuerpo">

</br></br>

<?php
///////////////////////////////////////////////////////////////////////////////////

///// Si hemos seleccionado alertas para borrar /////
if( isset($_POST['borrar'])) {

  // array con todos los sid de las reglas seleccionadas para borrar en el formulario
  $borrar=$_POST['borrar'];

  $i=0; // contador de alertas borradas

  if ($borrar != "Borrar") { // si el array $borrar no está vacío seguimos con la ejecución normal

// Borramos las reglas en la bd
  foreach ($borrar as $valor) {

    $delete = "DELETE FROM reglas WHERE sid='$valor'";

    if (mysqli_query($conexion, $delete)) {
        $i++;
    } 
    else {
        echo "<p style='color:red'>Error borrando: " . mysqli_error($conexion) . "</p>";
        }
  }

/////// Ahora hay que actualizar el fichero local.rules con las reglas que quedan en la bd después de borrar las seleccionadas ///////

//// Abrir el fichero local.rules de snort
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
    // Si no quedan reglas en la bd, vaciamos el fichero
    fwrite($fichero, '');
}

// Cerramos el fichero
fclose($fichero);

// Reiniciamos el servicio de Snort para hacer efectivas las reglas
$comando = "sudo service snort restart";

exec($comando);

// Mostramos al usuario el número de alertas borradas
if ($i == 1) {
    echo "<p style='color:green'>Se ha eliminado $i alerta</p>";
} else {
   echo "<p style='color:green'>Se han eliminado $i alertas</p>";
  }

} else {
    echo "<p style='color:red'>No se han seleccionado alertas para borrar</p>";
  }

} /////// fin del isset

///////////////////////////////////////////////////////////////////////////////////

/// Dependiendo del nivel del usuario hacemos una consulta u otra
// Los administradores pueden borrar cualquier alerta, los usuarios normales solo las suyas propias
if ( $nivel == 2 ) {
$consulta = "SELECT sid, origen, url, nombre FROM reglas JOIN usuarios ON usuario=login";
}

if ( $nivel == 1 ) {
  $consulta = "SELECT sid, origen, url FROM reglas WHERE usuario='$login'";  
}

$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

    /// mostrar la tabla de reglas con un checkbox para eliminarlas
    echo "<form name='f1' method='post' action='borrar_alerta.php'>

    <table class='tabla_reglas' border='0' cellspacing='0' width='80%'>

    <tr id='indicador'> 
    <th>ID Alerta</th>
    <th>Origen</th>
    <th>URL</th>";

    if ( $nivel == 2 ) {
        echo "<th>Creador</th>";
    }     
    
    // botón de borrar
    echo "<th><input type='submit' value='Borrar' name='borrar' style='width:85px; height:35px; font-weight: bold; color: red;'/></th> </tr>";

    while($fila = mysqli_fetch_assoc($resultado)) {

        $sid = $fila["sid"];

        if ( $fila["origen"] == "\$HOME_NET") {
            $fila["origen"] = "Cualquier IP";
        }

        echo "<tr> <td>" . $sid . "</td><td>" . $fila["origen"] . "</td><td>" . $fila["url"] . "</td>";

        if ( $nivel == 2 ) {
            echo "<td>" . $fila["nombre"] . "</td>";
        }

        // checkbox, que manda un array con los sid de las reglas a borrar al programa borrar_alerta.php
        echo "<td> <input type='checkbox' name='borrar[]' value='$sid'> </td> </tr>";
    }

    echo "</table> </form></br>";
}

else { 
    echo "<p style='color: red'>No hay alertas activas</p>";
}

mysqli_close($conexion);

?>

<!-- Píe de página -->

<div class="footer">
   
	<p>     
		<strong> Eduardo de Lamo Téllez &copy; </strong>&nbsp;<br/><br/>
		<img src="../img/favicon.png" alt="Logo" title="Easy Proxy"/>
	</p>
			
</div>

</div>

</body>

</html>