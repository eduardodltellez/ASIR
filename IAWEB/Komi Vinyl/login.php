<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    
<title>Komi Vinyl</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="shortcut icon" href="img/favicon.png" type="image/icon"/>
<link rel="stylesheet" type="text/css" href="css/estilos.css"/>
<script type="text/javascript" src="js/login.js"></script>

</head>

<body>
<div class="todo_documento">

<!-- Cabezera -->

<div class="cabeceraprincipal">
<h1>Komi Vinyl</h1>
    <a href="index.php">
        <img src="img/vinilo.jpg" alt="Vinilo" title="Komi Vinyl" width="395px" />
    </a>
</div>

<br/>

<!-- Menú horizontal -->

    <ul>
        <li><a href="index.php"> Inicio</a></li>
        <li><a href="discos.php"> Discos</a></li>
        <li><a href="curriculums.php"> Curriculums</a></li>
        <li><a href="login.php" style="background-color: #0067d5"> Login <img src='img/login.png' alt='Entrar' width='14'/></a></li>
    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">
    
<h2>Zona de administradores</h2><br/>

<form action="control.php" method="post" enctype="multipart/form-data">
<p>
    <label for="usuario_label" id="usuario_label"> <b>Usuario</b> </label>
	<input type="text" name="usuario" id="usuario" size="15" onblur="compruebaLogin();" onfocus="compruebaLogin();" onkeydown="compruebaLogin();" />&nbsp;
	<label for="password_label" id="password_label"> <b>Contraseña</b> </label>
	<input type="password" name="password" id="pass1" size="15" onblur="compruebaLogin();" onfocus="compruebaLogin();" onkeydown="compruebaLogin();"/>&nbsp;
	<input type="submit" style="font-weight: bold;" id="login" value="Entrar" disabled="disabled" onclick="compruebaLogin();" onfocus="compruebaLogin();"/>
</p>
</form>

<?php

// Comprobamos si el usuario ha fallado al introducir usuario y contraseña
if (isset($_GET["errorusuario"])) {
    if($_GET["errorusuario"] == "si") {
        echo "<p style='color: red'>Usuario o contraseña no válidos, pruebe de nuevo por favor.</p>";
    }
}
?>
 
<br/>

<img src="img/maleta.png" alt="Maleta de vinilos"/>

<br/><br/>

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

</div>

</body>

</html>