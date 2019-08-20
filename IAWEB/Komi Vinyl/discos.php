<?php
require "seguridad0.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    
<title>Komi Vinyl</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="img/favicon.png" type="image/icon"/>
<link rel="stylesheet" type="text/css" href="css/estilos.css"/>

</head>

<body>

<div class="todo_documento">

<?php
// Mostrar nombre del usuario si este está logueado
if (isset($_SESSION['nivel'])) {
    echo "<div id='log_usu'><img src='img/usu.png' width='11'/> $nombre &nbsp;</div>";
}
?>

<!-- Cabecera -->

<div class="cabeceraprincipal">
<h1>Komi Vinyl</h1>
    <a href="index.php">
        <img src="img/vinilo.jpg" alt="Vinilo" title="Komi Vinyl" width="395px" />
    </a>
</div>

<br/>
    <!-- Menú principal-->
    <ul>
        <li><a href="index.php"> Inicio</a></li>
        
        <?php
        // Dependiendo de si estas logueado el menú de discos te lleva a un sitio u otro
        if ($nivel == 2) {
            echo "<li><a href='discos_usu.php' style='background-color: #0067d5'> Administrar discos</a></li>";
        }
        else {
            echo "<li><a href='discos.php' style='background-color: #0067d5'> Discos</a></li>";
        }

        // Dependiendo de si estas logueado el menú de curriculums te lleva a un sitio u otro
        if ($nivel == 1 || $nivel == 2) {
            echo "<li><a href='curriculums_usu.php'> Ver Curriculums</a></li>";
        }
        else {
            echo "<li><a href='curriculums.php'> Curriculums</a></li>";
        }

        // Si hay un usuario logueado en vez de motrar en el menú la opción de login mostramos la de salir
        if ($nivel == 1 || $nivel == 2) {
            echo "<li><a href='salir.php'> Salir <img src='img/exit.png' width='14'/></a></li>";
        }
        else {
            echo "<li><a href='login.php'> Login <img src='img/login.png' width='14'/></a></li>";
        }
        ?>

    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">
    
<br/><h2>Discos en venta</h2><br/>

<?php

// Conexion con la base de datos
require "conexion_bd.php";

// Crear la conexión
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Chequear la conexión
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

// si se ha conectado correctamente a la bd
$sql = "SELECT num_referencia, artista, titulo, nombre, precio_unidad, imagen from discos join sellos_discograficos on cif_sello_discografico=cif order by artista";

$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

	echo "<table class='tablas_discos' border='0' cellspacing='0' width='83%'>

	<tr id='indicador'>
	<th><strong>Referencia</strong></th>
	<th><strong>Artista</strong></th>
	<th><strong>Título</strong></th>
	<th><strong>Sello</strong></th>
	<th><strong>Precio</strong></th>
	</tr>
";

while($fila = mysqli_fetch_assoc($resultado)) {

    if ($fila["imagen"] == null){
        $imagen="img_discos/disco.png";
    }
    else {
        $imagen=$fila["imagen"];
    }

    echo "<tr> <td> " . $fila["num_referencia"]. " </td><td> " . $fila["artista"]. "</td><td> " . $fila["titulo"]. "</td><td> " . $fila["nombre"]. "</td><td> " . $fila["precio_unidad"]. "</td>";

    echo "<td><img id='disco' src='$imagen' alt='Disco' width='45'/>";

    echo '<td class="icono_carro"><a href="" ><img src="img/carrito.png" alt="Comprar" width="40"/></a></td></tr>';

    }

echo "</table>";

echo "<p align='center'><strong>*I.V.A incluido en todos los discos*</strong></p><br/>";

}

mysqli_close($conexion);

?>

</div>

<!-- Píe de página -->

<div class="footer">
   
    <p>		<a id="rss" type="application/rss+xml" href="RSS/rss1.xml" title="RSS"> 
            <img src="img/iconrss.jpg" alt="RSS"/></a>&nbsp;
			<strong>Komi Vinyl, Santa Cruz de Mudela (Ciudad Real) &copy;</strong>&nbsp;     
			<a id="atom" type="application/atom+xml" href="atom/atom.xml" title="ATOM"> 
            <img src="img/iconatom.png" alt="ATOM"/></a>
	</p>
	
    <p id="w3s">
        <a href="http://validator.w3.org/check?uri=referer"><img
         src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
    </p>
		
</div>

</div>

</body>

</html>