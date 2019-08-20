<?php
require "seguridad2.php";
require "conexion_bd.php";
?>

<!-- Comienzo del código HTML -->

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
// Mostrar nombre del usuario logueado
echo "<div id='log_usu'><img src='img/usu.png' width='11'/> $nombre &nbsp;</div>";
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

        <li><a href='discos_usu.php' style="background-color: #0067d5"> Administrar discos</a></li>

    	<li><a href='curriculums_usu.php'> Ver Curriculums</a></li>

    	<li><a href='salir.php'> Salir <img src='img/exit.png' width='14'/></a></li>

    </ul>

    <!-- Menú admin discos -->
    <ul id="admin_discos">

        <li><a href='discos_usu.php' id="sellos" style="background-color: #00bd42"> Editar disco</a></li>

        <li><a href="añadir_disco.php" id="añadir"> Añadir disco</a></li>

        <li><a href='borrar_discos.php' id="borrar"> Borrar discos</a></li>

        <li><a href='sellos.php' id="sellos"> Información sellos</a></li>

    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

<!--------------------------------------------------------------------------------------------------------->

<h2>Zona de administración de discos</h2><br/>

<?php

// Create connection
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Check connection
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

/// Sacar los datos de la base de datos en una tabla ///
$sql = "SELECT num_referencia, fecha_lanzamiento, artista, titulo, precio_unidad, stock, nombre, imagen from discos join sellos_discograficos on cif_sello_discografico=cif order by artista";

$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

/// mostrar la tabla con un botón de radio para modificar el disco
echo "<form method='post' action='modificar_disco.php'>

<table class='tablas_discos' border='0' cellspacing='0' width='90%'> 
<tr id='indicador'> <th>Referencia</th> <th>Fecha Lanz.</th> <th>Artista</th> <th>Título</th> <th>Precio</th> 
<th>Stock</th> <th>Sello</th> <th>Foto</th> 
<th><input type='submit' value='Editar' name='editar' style='width:85px; height:35px; font-weight: bold; color: #0234a2;'/></th> </tr>";

while($fila = mysqli_fetch_assoc($resultado)) {
    $referencia=$fila["num_referencia"];
    $fecha=$fila["fecha_lanzamiento"];
    $artista=$fila["artista"];
    $titulo=$fila["titulo"];
    $precio=$fila["precio_unidad"];        
    $stock=$fila["stock"];
	$sello=$fila["nombre"];
	$foto=$fila["imagen"];       

    echo "<tr> <td>$referencia</td> <td>$fecha</td> <td>$artista</td> <td>$titulo</td> 
    <td>$precio</td> <td>$stock</td> <td>$sello</td>";

	if ($foto == null) {
	
	    echo "<td> <img id='disco' src='img_discos/disco.png' width='45'/> </td>";
	}
	else {
	    echo "<td> <img id='disco' src='$foto' width='45'/> </td>";
	}
	 
	echo "<td> <input type='radio' name='modificar' value='$referencia'></td>";

    }

    echo "</table> </form>";
} 

else { 
	echo "<p style='color:red'>No hay resultados.</p>";
}

mysqli_close($conexion);

?>

<br/>

<!--------------------------------------------------------------------------------------------------------->

</div>

<!-- Píe de página -->

<div class="footer">
   
	<p>     <a id="rss" type="application/rss+xml" href="RSS/rss1.xml" title="RSS"> 
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