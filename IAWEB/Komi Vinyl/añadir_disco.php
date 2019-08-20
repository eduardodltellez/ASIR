<?php
require "seguridad2.php";
require "conexion_bd.php";

// Create connection
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Check connection
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

?>

<!-- Comienzo del código HTML -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

<head>
    
<title>Komi Vinyl</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="shortcut icon" href="img/favicon.png" type="image/icon"/>
<link rel="stylesheet" type="text/css" href="css/estilos.css"/>
<script type="text/javascript" src="js/añadir_disco.js"></script>

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
        
        <li><a href="añadir_disco.php" id="añadir" style="background-color: #00bd42"> Añadir disco</a></li>

        <li><a href='borrar_discos.php'> Borrar discos</a></li>

        <li><a href='sellos.php'> Información sellos</a></li>

    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

<!--------------------------------------------------------------------------------------------------------->

<?php
/// Si se ha rellenado el formulario
if (isset($_POST["añadir"])) {

if (basename($_FILES["foto"]["tmp_name"]) != null) { // Comprobamos si han subido una foto

    $correcto = 1;

    $dir = "img_discos/";
    $nombre_foto = basename($_FILES["foto"]["name"]);
    $foto = $dir . $nombre_foto; // obtiene el nombre de fichero "fotos/foto.jpg"
    $tipo = strtolower(pathinfo($foto,PATHINFO_EXTENSION)); // sacar la extensión, por ejemplo: .jpg

    // Checkear si la imagen es real o un fake
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if($check !== false) {
        $correcto = 1;
    } else {
        $correcto = 0;
    }

    // Ver que la imagen no exista ya en el directorio de subidas
    if (file_exists($foto)) {
        echo "<p style='color:red'>* La foto ya existe en la base de datos.<p/>";
        $correcto = 0;
    }

    // Comprobar tamaño de la imagen, si sobrepasa los 5000000 bytes no la dejamos subir
    if ($_FILES["foto"]["size"] > 5000000) { // en bytes
        echo "<p style='color:red'>La foto es muy grande.<p/>";
        $correcto = 0;
    }

    // Comprobar la extensión del fichero
    if($tipo != "jpg" && $tipo != "png" && $tipo != "jpeg" && $tipo != "gif" ) {
        echo "<p style='color:red'>Solo fotos de tipo JPG, JPEG, PNG y GIF se permiten.<p/>";
        $correcto = 0;
    }

    if($correcto) { // Si todo está OK, copia la imagen del directorio temporal a $fichero que es la ruta absoluta donde lo queremos
        if (copy ($_FILES["foto"]["tmp_name"], $foto)) {
            $correcto=1;
        } else {
            echo "<p style='color:red'>Lo sentimos, ha habido un error subiendo la foto.<p/>";
        }
    }

} /// isset foto

///// Añadir los datos a la bd

$referencia=$_POST['referencia'];
$fecha=$_POST["fecha"];
$artista=$_POST["artista"];
$titulo=$_POST["titulo"];
$precio=$_POST["precio"];
$stock=$_POST["stock"];
$sello=$_POST["sello"];

$sql = "INSERT INTO discos VALUES ($referencia, '$fecha', '$artista', '$titulo', '$precio', '$stock', '$sello', ";

// Dependiendo de si se ha subido foto hacemos una cosa u otra
if (isset($foto)) {
    $sql.="'$foto')";
} else {
    $sql.="'')";
}

if ($resultado = mysqli_query($conexion, $sql)) {
    header ("Location: discos_usu.php");
} else {
    echo "<br/><p style='color:red'>Error al añadir disco.</p>";
}

} ////////// isset de añadir

// Formulario con los datos a añadir
echo "
<h2>Añadir disco</h2>

<form method='post' action='añadir_disco.php' enctype='multipart/form-data'>
<strong>Num. Referencia</strong> <input type='text' name='referencia' id='referencia' size='5' style='text-align: center;' onchange='comprueba_ref();comprueba_formulario()' onfocus='comprueba_formulario()'/><br/><span id='span_referencia'></span><br/>

<strong>Fecha Lanz.</strong> <input type='date' name='fecha' id='fecha' size='17' style='text-align: center;' onchange='comprueba_ref();comprueba_fecha();comprueba_formulario()' onfocus='comprueba_fecha();comprueba_formulario()' onblur='comprueba_fecha();comprueba_formulario()'/> <br/><br/>

<strong>Artista</strong> <input type='text' name='artista' id='artista' size='17' style='text-align: center;' onchange='comprueba_artista();comprueba_formulario()' onblur='comprueba_artista()' onfocus='comprueba_formulario()'/> <br/><span id='span_artista'></span><br/>

<strong>Título</strong> <input type='text' name='titulo' id='titulo' size='15' style='text-align: center;' onchange='comprueba_titulo();comprueba_formulario()' onblur='comprueba_titulo()' onfocus='comprueba_formulario()'/> <br/><span id='span_titulo'></span><br/>

<strong>Precio</strong> <input type='text' name='precio' id='precio' size='3' style='text-align: center;' onchange='comprueba_ref();comprueba_precio();comprueba_formulario()' onfocus='comprueba_formulario()'/> <br/><span id='span_precio'></span><br/>

<strong>Stock</strong> <input type='text' name='stock' id='stock' size='3' style='text-align: center;' onchange='comprueba_ref();comprueba_precio();comprueba_stock();comprueba_formulario()' onfocus='comprueba_formulario()'/><br/><span id='span_stock'></span><br/>";

/// Menú desplegable con el nombre de los sellos discográficos de la tabla sellos_discograficos
$sql = "SELECT nombre, cif from sellos_discograficos order by nombre asc";

$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

    echo '<strong>Sello</strong>
         <select name="sello" id="sello" onchange="comprueba_sellos();comprueba_formulario()" onblur="comprueba_sellos()" onfocus="comprueba_formulario()">
         <option>- Elija sello -</option>';

    while($fila = mysqli_fetch_assoc($resultado)) {
        $valor=$fila['cif'];
        $sello_nombre=$fila['nombre'];
        echo "<option value='$valor'>$sello_nombre</option>";
    }

    echo "</select><br/>";

    echo "<span id='span_sello'></span>";

    echo "<br/>";
}

 echo "<strong>Foto</strong> <input type='file' name='foto' id='foto'><br/><br/>

<br/><input type='submit' value='Añadir' name='añadir' id='anadir' style='width:85px; height:35px; font-weight: bold;' disabled='disabled'/>

</form>

<br/>

";

mysqli_close($conexion);

?>

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