<?php
require "../seguridad.php";

// Si el nivel del usuario no es 2 lo mandamos al inicio
if ( $nivel != 2 ) {

    header ('Location: ../alertas/inicio.php');

    exit();
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
<script type="text/javascript" src="../js/anadir_usuario.js"></script>

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
    <a href="../alertas/inicio.php">
        <img src="../img/logo_snort.png" alt="Inicio" title="Easy Proxy" width="395px" />
    </a>
</div>

<br/>
	<!-- Menú principal-->
    <ul>
        <li><a href="../alertas/inicio.php"> Inicio </a></li>

        <li><a href='../alertas/anadir_alerta.php'> Añadir Alerta </a></li>

        <li><a href='../alertas/borrar_alerta.php'> Borrar Alerta </a></li>

        <li><a href='../salir.php'> Salir <img src='../img/exit.png' alt='Salir' width='15'/></a></li>

    </ul>

    <!-- Menú de administrador -->

    <ul class='admin'>

        <li style="background-color: #36ac44"><a href='crear_usuario.php'> Añadir Usuario </a></li>

        <li><a href='borrar_usuario.php'> Borrar Usuario </a></li>
     
    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

<h2>Crear Usuario</h2>

<?php

// Si hemos tenido éxito al crear un usuario o si este ya existe
if ( isset($_GET["exito"]) ) {
    if ( $_GET["exito"] == "si" ) {
        echo "<p style='color: green'>Usuario creado correctamente</p>";
    }
    if ( $_GET["exito"] == "no" ) {
        echo "<p style='color: red'>El usuario ya existe</p>";
    }
}

?>

<!-- Formulario para la entrada de datos del usuario nuevo -->
<form action="crear.php" name="mi_formulario" method="post" enctype="multipart/form-data">

    <p><b>Login</b></p>
    <input type="text" name="login" id="login" size="25" style="text-align: center;" onblur="compruebaLogin();comprueba_formulario()" onchange="compruebaLogin();comprueba_formulario()" onkeyup="compruebaLogin();comprueba_formulario()"/>

    <p><b>Contraseña</b></p>
    <input type="password" name="clave" id="clave" size="25" style="text-align: center;" onblur="compruebaClave();compruebaLogin();comprueba_formulario()" onchange="compruebaClave();comprueba_formulario()" onkeyup="compruebaClave();comprueba_formulario()"/>

    <p><b>Nombre del usuario</b></p>
    <input type="text" name="nombre" id=nombre size="25" style="text-align: center;" onblur="compruebaNombre()compruebaClave();compruebaLogin();comprueba_formulario()" onchange="compruebaNombre();comprueba_formulario()" onkeyup="compruebaNombre();comprueba_formulario()"/>

    </br>

    <p><b>Privilegios</b></p>
    <select name="nivel" id="nivel" style="text-align: center;">
        <option value="1" selected="selected"> Usuario Normal </option>
        <option value="2"> Administrador </option>
    </select>

    </br></br></br>

    <input type="submit" name="crear_usuario" id="crear_usuario" value="Crear Usuario" style="width:128px; height:40px; font-weight: bold;" disabled="disabled"/>

</form>

</br>

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