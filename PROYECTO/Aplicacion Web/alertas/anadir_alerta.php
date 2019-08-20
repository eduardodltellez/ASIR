<?php
require "../seguridad.php";
?>

<!-- Comienzo del código HTML -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    
<title>Control Web</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="../img/favicon.png" type="image/icon"/>
<link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
<script type="text/javascript" src="../js/anadir_alerta.js"></script>

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

        <li><a href='anadir_alerta.php' style="background-color: #0012ae"> Añadir Alerta </a></li>

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

<h2>Configure la alerta</h2>

<?php

// Si el usuario introduce en el formulario una URL que ya existe en la bd le mostramos este mensaje
if ( isset($_GET["URLexiste"]) ) {
    if ( $_GET["URLexiste"] == "si" ) {
        echo "<p style='color: red'>La URL introducida ya está monitorizada en otra alerta</p>";
    }
}

// Si ya hemos creado una alerta con éxito ponemos este mensaje al volver a la página
if ( isset($_GET["exito"]) ) {
    if ( $_GET["exito"] == "si" ) {
        echo "<p style='color: green'>Alerta creada</p>";
    }
}

?>

<!-- Formulario para la entrada de datos de creación de la alerta -->
<form action="anadir.php" name="mi_formulario" method="post" enctype="multipart/form-data">

<fieldset>

<legend><strong>Control para</strong></legend>

<b>Toda la Red</b>

<input type="radio" name="boton_radio" id="radio_red" checked="checked" value="$HOME_NET" onclick="mostrar(0)"/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<b>Equipo específico</b>

<input type="radio" name="boton_radio" id="radio_equipo" value="equipo" onclick="mostrar(1)"/>

<div id="div_equipo" style="display: none;">

<p>IP del equipo</p>

<input type="text" name="IP_equipo" id="IP_equipo" size="10" style="text-align: center" onblur="compruebaIP();comprueba_formulario()" onchange="compruebaIP();comprueba_formulario()" onkeyup="compruebaIP();comprueba_formulario()"/>

</div>

</fieldset></br>

<fieldset>

<legend><strong>URL</strong></legend>

<b>Sitio Web a controlar</b></br></br>

<input type="text" name="URL" id="URL" size="25" style="text-align: center;" onblur="compruebaURL();comprueba_formulario()" onchange="compruebaURL();comprueba_formulario()" onkeyup="compruebaURL();comprueba_formulario()" />

</fieldset></br>

<fieldset>

<legend><strong>Alerta</strong></legend>

<b>Mensaje a mostrar</b></br></br>

<input type="text" name="mensaje" id="mensaje" size="35" style="text-align: center;" onblur="compruebaURL();compruebaMensaje();comprueba_formulario()" onchange="compruebaURL();compruebaMensaje();comprueba_formulario()" onkeyup="compruebaURL();compruebaMensaje();comprueba_formulario()" />

</fieldset></br>

<fieldset>
<br/>
<input type="submit" name="crear_regla" id="crear_regla" value="Crear Alerta" style="width:120px; height:40px; font-weight: bold;" disabled="disabled"/>
</fieldset>

</form></br>

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