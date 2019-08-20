<?php
require "seguridad1.php";
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

        <?php
        // Dependiendo de si estas logueado el menú de discos te lleva a un sitio u otro
        if ($nivel == 2) {
        	echo "<li><a href='discos_usu.php'> Administrar discos</a></li>";
        }
        else {
        	echo "<li><a href='discos.php'> Discos</a></li>";
    	}
    	?>

    	<li><a href='curriculums_usu.php' style="background-color: #0067d5"> Ver Curriculums</a></li>

    	<li><a href='salir.php'> Salir <img src='img/exit.png' alt='Salir' width='14'/></a></li>

    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

<h2>Listado de Curriculums recibidos</h2><br/>

<?php
// Crear la conexión con la bd
$conexion = mysqli_connect($servidor, $usuario, $clave, $bd);

// Checkear conexion
if (!$conexion) {
    die("Fallo al conectar con la base de datos: " . mysqli_connect_error());
}

// una vez conectado correctamente a la bd
$sql = "SELECT * from curriculums order by apellidos";

$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {  // si hay filas en la consulta

// Generación de la tabla  
    echo "<table class='tablas_discos' border='0' cellspacing='0' width='97%'>";
    echo "<tr id='indicador'> <th><strong>Foto</strong></th> <th><strong>Nombre</strong></th> <th><strong>Apellidos</strong></th> <th><strong>DNI</strong></th> <th><strong>Puesto</strong></th>  <th><strong>Sexo</strong></th> <th><strong>Estudios</strong></th> <th><strong>Experiencia<strong></th> <th><strong>Descripción</strong></th> <th><strong>Documento</strong></th> </tr>";

    while($fila = mysqli_fetch_assoc($resultado)) {
    	  $foto=$fila["foto"];
          $nombre=$fila["nombre"];
          $apellidos=$fila["apellidos"];
          $dni=$fila["dni"];
          $puesto=$fila["puesto"];
          $sexo=$fila["sexo"];
          $estudios=$fila["estudios"];
          $experiencia=$fila["experiencia"];
          $descripcion=$fila["descripcion"];
          $documento=$fila["documento"];

          echo "<tr>";

                	if ($foto != null) {
                    echo "<td><img id='disco' src='$foto' alt='$nombre' width='45'/></td>";
                	} else {
                		echo "<td></td>";
                	}
          			
          			echo "<td>$nombre</td> <td>$apellidos</td> <td>$dni</td> <td>$puesto</td> 
          			<td>$sexo</td> <td>$estudios</td> <td>$experiencia años</td> <td>$descripcion</td>";

          			if ($documento != null) {  
                    echo "<td><a href='$documento'><img id='disco' src='img/documento.png' alt='Documento' width='40'/></td>";
                	} else {
                		echo "<td></td>";
                	}

          echo "</tr>";
    }

    echo "</table>";
} 

// Si no hay resultados
else {
    echo "<p style='color:red'>No hay resultados.</p>";
}

mysqli_close($conexion);

?>

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