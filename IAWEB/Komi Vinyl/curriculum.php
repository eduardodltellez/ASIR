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
        	echo "<li><a href='curriculums.php' style='background-color: #0067d5'> Curriculums</a></li>";
    	}

        // Si hay un usuario logueado en vez de motrar en el menú la opción de login mostramos la de salir
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

<br/>

<?php

$nombre = $_POST["nombre"];
$apellidos = $_POST["apellidos"];
$dni = $_POST["dni"];
$telefono = $_POST["telefono"];
$puesto = $_POST["puesto"];
$sexo = $_POST["sexo"];
$estudios = $_POST["estudios"];
$experiencia = $_POST["experiencia"];
$descripcion = $_POST["descripcion"];

if (basename($_FILES["foto"]["tmp_name"]) != null) { // Comprobamos si han subido una foto personal

	$correcto = 1;

	$dir = "fotos/";
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
    	echo "<p style='color:red'>* Su foto ya existe en nuestra base de datos.<p/>";
    	$correcto = 0;
	}

	// Comprobar tamaño de la imagen, si sobrepasa los 5000000 bytes no la dejamos subir
	if ($_FILES["foto"]["size"] > 5000000) { // en bytes
    	echo "<p style='color:red'>Lo sentimos la foto es muy grande.<p/>";
    	$correcto = 0;
	}

	// Comprobar la extensión del fichero
	if($tipo != "jpg" && $tipo != "png" && $tipo != "jpeg" && $tipo != "gif" ) {
    	echo "<p style='color:red'>Lo sentimos, solo fotos de tipo JPG, JPEG, PNG y GIF se permiten.<p/>";
    	$correcto = 0;
	}

	if($correcto) { // Si todo está OK, copia la imagen del directorio temporal a $fichero que es la ruta absoluta donde lo queremos
    	if (copy ($_FILES["foto"]["tmp_name"], $foto)) {
    		$correcto=1;
    	} else {
        	echo "<p style='color:red'>Lo sentimos, ha habido un error subiendo la foto.<p/>";
    	}
	}

} // isset foto

if (basename($_FILES["curriculum"]["tmp_name"]) != null) { // Comprobamos si han subido un documento con el curriculum

	$correcto = 1;

	$dir = "curriculums/";
	$nombre_documento = basename($_FILES["curriculum"]["name"]);
	$curriculum = $dir . $nombre_documento; // obtiene el nombre de fichero "curriculum/curriculum.pdf"
	$tipo = strtolower(pathinfo($curriculum,PATHINFO_EXTENSION)); // sacar la extensión, por ejemplo: .pdf

    // Ver que el curriculum no exista ya en el directorio de subidas
	if (file_exists($curriculum)) {
    	echo "<p style='color:red'>* Su documento ya existe en nuestra base de datos.</p>";
    	$correcto = 0;
	}

	// Comprobar tamaño del curriculum, si sobrepasa los 5000000 bytes no lo dejamos subir
	if ($_FILES["curriculum"]["size"] > 5000000) { // en bytes
    	echo "<p style='color:red'>* Lo sentimos el documento pesa mucho.</p>";
    	$correcto = 0;
	}

	// Comprobar la extensión del fichero
	if($tipo != "pdf" && $tipo != "doc" && $tipo != "docx" && $tipo != "txt" ) {
    	echo "<p style='color:red'>* Lo sentimos, solo archivos de tipo PDF, DOC, DOCX y TXT se permiten como documento.</p>";
    	$correcto = 0;
	}

	if($correcto) { // Si todo está OK, copia el curriculum del directorio temporal a $curriculum que es la ruta absoluta donde lo queremos
    	if (copy ($_FILES["curriculum"]["tmp_name"], $curriculum)) {
    		$correcto=1;
    	} else {
        	echo "<p style='color:red'>* Lo sentimos, ha habido un error subiendo el documento.</p>";
    	}
	}

} // isset curriculum

/// Insertar los datos recibidos en la tabla curriculums de la base de datos

// Conexion con la base de datos
require "conexion_bd.php";

// Crear la conexión
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Chequear la conexión
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

// Si se ha conectado correctamente a la bd
$sql = "INSERT INTO curriculums VALUES ('$nombre', '$apellidos', '$dni', '$puesto', '$sexo', '$estudios', '$experiencia', '$descripcion',";

// Si hay curriculum lo concatenamos al insert, sino concatenamos un null
if (isset($curriculum)) {
    $sql.=" '$curriculum',";
} else {
    $sql.=" '',";
}

// Si hay foto la concatenamos al insert, sino concatenamos un null
if (isset($foto)) {
    $sql.=" '$foto')";
} else {
    $sql.=" '')";
}

$exito=0; // Variable para probar el éxito del insert

if ($resultado = mysqli_query($conexion, $sql)) {
	echo "<p style='color:green'><u>Hemos recibido sus datos correctamente</u><br/>";
	$exito=1;
}
else {
	echo "<p style='color:red'>* Usted ya está en nuestra base de datos.</p><br/>";
}

mysqli_close($conexion);

/// Mostrar los datos al usuario si la inseción de datos en la tabla ha sido exitosa

if ($exito) { 

	echo "<h2>Sus datos</h2>";

	if (isset($foto)) {
		echo "<img src='$foto'  alt='Su Foto'  width='70'/> <br/>"; // Sacamos la foto por pantalla
	}

	echo "<p><strong>Nombre y apellidos -> </strong> $nombre $apellidos </p>";
	echo "<p><strong> DNI -> </strong> $dni</p>";
	echo "<p><strong> Teléfono -> </strong> $telefono</p>";
	echo "<p><strong> Solicita un puesto de -> </strong> $puesto</p>";
	echo "<p><strong> Sexo -> </strong> $sexo</p>";
	echo "<p><strong> Estudios -> </strong> $estudios</p>";
	echo "<p><strong> Experiencia laboral -> </strong> $experiencia años</p>";
	echo "<p><strong> Su descripción </strong></p>";
	echo "<p> $descripcion </p>";

	if (isset($curriculum)) {
		echo "<p><strong> Documento -> </strong> $nombre_documento</p><br/>";
	}
}

?>

<input type="button" style="font-weight: bold; width:105px; height:45px;" onclick="location.href='curriculums.php'" value="<- Volver" name="boton"/>

<br/><br/>

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