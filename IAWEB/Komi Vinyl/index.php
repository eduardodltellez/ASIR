<?php
require "seguridad0.php";
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
        <li><a href="index.php" style="background-color: #0067d5"> Inicio</a></li>

        <?php
        // Dependiendo de si estas logueado el menú de discos te lleva a un sitio u otro
        if ($nivel == 2) {
        	echo "<li><a href='discos_usu.php'> Administrar discos</a></li>";
        }
        else {
        	echo "<li><a href='discos.php'> Discos</a></li>";
    	}

        // Dependiendo de si estas logueado el menú de curriculums te lleva a un sitio u otro
        if ($nivel == 1 || $nivel == 2) {
        	echo "<li><a href='curriculums_usu.php'> Ver Curriculums</a></li>";
        }
        else {
        	echo "<li><a href='curriculums.php'> Curriculums</a></li>";
    	}

        // Si hay un usuario logueado en vez de mostrar en el menú la opción de login mostramos la de salir
        if ($nivel == 1 || $nivel == 2) {
        	echo "<li><a href='salir.php'> Salir <img src='img/exit.png' alt='Salir' width='14'/></a></li>";
        }
        else {
        	echo "<li><a href='login.php'> Login <img src='img/login.png' alt='Entrar' width='14'/></a></li>";
    	}
    	?>

    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

<h2>Líderes en venta de discos on-line</h2>

<p style="font-family: italic; font-size: 20px;">CD's</p>
<p style="font-family: italic; font-size: 20px;">Vinilos</p>
<p style="font-family: italic; font-size: 20px;">Todos tus temas en formato digital (MP3)</p>
<p style="font-family: italic; font-size: 20px;">Las últimas novedades de los mejores sellos discográficos</p>

<img id="dj1" src="img/dj1.jpg" alt="dj1" title="Dj pinchando"/>

<h2>Disco de la semana</h2>    

<object width="510" height="350" 
type="application/x-shockwave-flash" 
data="https://www.youtube.com/v/XIfiNzZ87JI">
	<param name="movie" value="http://www.youtube.com/v/XIfiNzZ87JI" />
	<param name="wmode" value="transparent" />
</object>

<br/><br/>

<p>
<a href="mailto:edelamokomi@gmail.com" title="Contacto">Contacte con nosotros</a>
</p>

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