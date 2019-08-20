<?php

require "seguridad2.php";

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

    	<li><a href='salir.php'> Salir <img src='img/exit.png' alt='Salir' width='14'/></a></li>

    </ul>

    <!-- Menú admin discos -->
    <ul id="admin_discos">

        <li><a href='discos_usu.php' id="sellos"> Editar disco</a></li>

        <li><a href="añadir_disco.php"> Añadir disco</a></li>

        <li><a href='borrar_discos.php'> Borrar discos</a></li>

        <li><a href='sellos.php' style="background-color: #00bd42"> Información sellos</a></li>

    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

<!--------------------------------------------------------------------------------------------------------->

<h2>Información de los sellos discográficos</h2>

<?php

$fichero = fopen("sellos.csv", "r") or die("Problemas al abrir el fichero!"); // abrimos el fichero sellos.csv

while(!feof($fichero)) {

    $linea=fgets($fichero);

    if($linea != null) {

        $registros = (explode(":", $linea)); // separar los registros del fichero en un array

        // Ir sacandolos por pantalla
        echo "<hr/>";
        echo "<h3>$registros[1]</h3><hr/>";
        echo "<strong style='color: #012170;'>CIF</strong><br/>";
        echo "$registros[0]<br/><br/>";
        echo "<strong style='color: #012170;'>País</strong><br/>";
        echo "$registros[2]<br/><br/>";
        echo "<strong style='color: #012170;'>Ciudad</strong><br/>";
        echo "$registros[3]<br/><br/>";
        echo "<strong style='color: #012170;'>Dirección</strong><br/>";
        echo "$registros[4]<br/><br/>";
        echo "<strong style='color: #012170;'>E-Mail</strong><br/>";
        echo "$registros[5]<br/><br/>";
        echo "<strong style='color: #012170;'>Teléfono</strong><br/>";
        echo "$registros[6]<br/><br/>";
    }
}

fclose($fichero);

?>

<!--------------------------------------------------------------------------------------------------------->

</div>

<br/>

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