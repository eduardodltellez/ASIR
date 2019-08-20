<?php
require "../seguridad.php";
require "../conexion.php";

// Crear la conexión con la bd
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Chequear la conexión
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
        <li><a href="inicio.php" style="background-color: #0012ae"> Inicio </a></li>

        <li><a href='anadir_alerta.php'> Añadir Alerta </a></li>

        <li><a href='borrar_alerta.php'> Borrar Alerta </a></li>

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

</br><h2>Alertas Activas</h2><hr></br>

<?php

// Dependiendo del nivel del usuario hacemos una consulta u otra
if ( $nivel == 2 ) {
$consulta = "SELECT origen, url, mensaje FROM reglas";
}

if ( $nivel == 1 ) {
$consulta = "SELECT origen, url, mensaje FROM reglas WHERE usuario='$login'";
}

$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

    // Sacamos por pantalla las reglas activas en una tabla
    echo "<table class='tabla_reglas' border='0' cellspacing='0' width='80%'>

        <tr id='indicador'>
        <th><strong>Origen</strong></th>
        <th><strong>URL</strong></th>
        <th><strong>Alerta</strong></th>
          </tr>
    ";

    while($fila = mysqli_fetch_assoc($resultado)) {

        if ( $fila["origen"] == "\$HOME_NET") {
            $fila["origen"] = "Cualquier IP";
        }

        echo "<tr> <td>" . $fila["origen"] . "</td> <td>" . $fila["url"] . "</td> <td>" . $fila["mensaje"] . "</td> <tr>";      

    }

    echo "</table></br>";

}

else {
    echo "<p style='color: red'>No hay alertas activas</p>";
}

// Cerramos la conexión con la bd
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