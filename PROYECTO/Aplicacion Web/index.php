<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    
<title>Control Web</title>
<link rel="shortcut icon" href="img/favicon.png" type="image/icon"/>
<link rel="stylesheet" type="text/css" href="css/css_index.css"/>

</head>

<body>

<div class="documento">

<h1>CONTROL WEB</h1>

<br/>

<img src="img/logo_snort.png" alt="Easy Proxy" width="150" height="100" />

<br/><br/>

<form action="control.php" method="post" enctype="multipart/form-data">
<p>

    <label for="usuario_label" id="usuario_label"> <b>&nbsp;&nbsp;&nbsp;Usuario</b> </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="text" name="usuario" id="usuario" size="10" style="text-align: center;"/><br/><br/>

	<label for="password_label" id="password_label"> <b>Contraseña</b> </label>&nbsp;
	<input type="password" name="password" id="pass" size="10" style="text-align: center;"/><br/><br/><br/>

	<input type="submit" style="font-weight: bold;" id="login" value="Entrar" />
	
</p>
</form>

<?php
// Comprobamos si el usuario ha fallado al introducir usuario y contraseña
if (isset($_GET["errorusuario"])) {
    if($_GET["errorusuario"] == "si") {
        echo "<p style='color: red'>Login incorrecto</p>";
    }
}
?>

</div>

</body>

</html>