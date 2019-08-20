<?php
require "../seguridad.php";
require "../conexion.php";

// Crear la conexion con la bd
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Checkear la conexion
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

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

        <li><a href='crear_usuario.php'> Añadir Usuario </a></li>

        <li style="background-color: #36ac44"><a href='borrar_usuario.php'> Borrar Usuario </a></li>
     
    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

</br></br>

<?php

////////////////////////////////////////////////////////////////////////////////

/// Si hemos marcado un usuario para borrar y recibimos los datos del formulario

if( isset($_POST['borrar_usu']) ) {  ///// Comienzo del isset

    $usuario = $_POST['borrar_usu'];

    // Borramos al usuario de la bd

    $borrar = "DELETE FROM usuarios where login='$usuario'";

    if (mysqli_query($conexion, $borrar)) {

        echo "<p style='color: green'>Usuario borrado</p>";
    } 

    else {

        echo "<p style='color:red'>Error borrando: " . mysqli_error($conexion) . "</p>";

        }

    // Cuando borramos un usuario debemos volver a escribir las reglas en el fichero local.rules 
    // ya que estas se eliminan en cascada al eliminar al usuario en la base de datos pero no en el fichero

    //// Abrir el fichero local.rules de snort para escribir las reglas de la bd en él
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
        echo "<p style='color: red'>No quedan alertas activas</p>";
    }

    // Cerramos el fichero y la conexión con la bd
    fclose($fichero);

    // Reiniciamos el servicio de Snort para hacer efectivas las reglas
    $comando = "sudo service snort restart";

    exec($comando);

} ///// fin del isset

////////////////////////////////////////////////////////////////////////////////

// Sacar los usuarios en una tabla con un radio button para eliminar
// Protejo al usuario eduardo para no quedarme sin administradores
$consulta = "SELECT login, nombre, nivel FROM usuarios where login <> 'eduardo'";

$resultado = mysqli_query($conexion, $consulta);

if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

    echo "<form name='f2' method='post' action='borrar_usuario.php'>

    <table class='tabla_reglas' border='0' cellspacing='0' width='80%'>

    <tr id='indicador'>

    <th>Usuario</th>
    <th>Privilegios</th>
    <th><input type='submit' value='Borrar' name='borrar' style='width:85px; height:35px; font-weight: bold; color: red;'/></th>

    </tr>
    ";

    while($fila = mysqli_fetch_assoc($resultado)) {

        $login = $fila["login"];
        $usuario = $fila["nombre"];

        if ( $fila["nivel"] == 2 ) {
            $privilegios = "Administrador";
        }
        else {
            $privilegios = "Usuario Normal";
        }

        echo "<tr> <td>$usuario</td> <td>$privilegios</td>";

        // botón de radio
        echo "<td> <input type='radio' name='borrar_usu' value='$login'> </td> </tr>";
    }

    echo "</table> </form></br>";

}

else { 

    echo "<p style='color: red'>No hay usuarios activos</p>";
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