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
<script type="text/javascript" src="js/curriculum.js"></script>

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
        <li><a href="discos.php"> Discos</a></li>
        <li><a href="curriculums.php" style="background-color: #0067d5"> Curriculums</a></li>
        <li><a href="login.php"> Login <img alt='Entrar' src='img/login.png' width='14'/></a></li>
    </ul>

<!-- Cuerpo variable -->

<div class="cuerpo">

<h2>Usted puede formar parte de Komi Vinyl</h2>
<h3>Rellene este formulario con sus datos personales</h3>

<form action="curriculum.php" method="post" enctype="multipart/form-data" onsubmit="envio_curriculum()">
    <fieldset>
    <legend><strong>Datos personales</strong></legend>
	<label for="nombre" id="nombre_label">Nombre: </label>
	<input type="text" name="nombre" id="nombre" value="" size="15" onchange="compruebaNombre();comprueba_formulario()" onfocus="comprueba_formulario()" />&nbsp;<span id="span_nombre"></span>
	<label for="apellidos" id="apellidos_label">Apellidos: </label>
	<input type="text" name="apellidos" id="apellidos" size="25" onchange="compruebaApellidos();comprueba_formulario()" onfocus="comprueba_formulario()"/>&nbsp;<span id="span_apellidos"></span>
	<label for="dni" id="dni_label">DNI: </label>
	<input type="text" name="dni" id="dni" size="8" onchange="compruebaDNI();comprueba_formulario()" onblur="comprueba_formulario()" />&nbsp;<span id="span_dni"></span>
	<label for="telefono_label" id="telefono_label">Teléfono: </label>
	<input type="text" name="telefono" id="telefono" size="8" onchange="compruebaTelefono();comprueba_formulario()" onblur="comprueba_formulario()" />&nbsp;<span id="span_telefono"></span>
	<p>Puesto de trabajo buscado</p> <span id="span_puestos"></span>	
	<input name="puesto" type="checkbox" id="programador_web" value="Programador Web" onchange="comprueba_puestos();comprueba_formulario()" onfocus="comprueba_formulario()" onclick="excluyentes(this);" /> Programador Web &nbsp;
	<input name="puesto" type="checkbox" id="logistica" value="Logistica" onchange="comprueba_puestos();comprueba_formulario()" onfocus="comprueba_formulario()" onclick="excluyentes(this);" /> Logística &nbsp;
	<input name="puesto" type="checkbox" id="peon" value="Peon" onchange="comprueba_puestos();comprueba_formulario()" onfocus="comprueba_formulario()" onclick="excluyentes(this);" /> Peón
	<p>Sexo</p> <span id="span_sexo"></span>
	<input type="radio" name="sexo" value="Hombre" onchange="comprueba_puestos();compruebaSexo();comprueba_formulario()" onfocus="comprueba_formulario()" /> Hombre
	<input type="radio" name="sexo" value="Mujer" onchange="comprueba_puestos();compruebaSexo();comprueba_formulario()" onfocus="comprueba_formulario()" /> Mujer
	</fieldset>
	<fieldset>
	<legend><strong>Formación</strong></legend>
	<label for="estudios">Estudios</label><span id="span_estudios"></span><br/>
	<select id="estudios" name="estudios" onfocus="compruebaNombre();compruebaApellidos();compruebaDNI();compruebaTelefono();comprueba_puestos();compruebaSexo();comprueba_formulario()" onchange="comprueba_estudios();comprueba_formulario()" >
	    <option value="" selected="selected">- selecciona -</option>
	    <option value="Primaria">Primaria</option>
	    <option value="ESO">ESO</option>
	    <option value="Grado Medio">Grado Medio</option>
	    <option value="Grado Superior">Grado Superior</option>
	    <option value="Licenciatura">Licenciatura</option>
	</select><br/><br/>
	<label for="experiencia">Años de experiencia</label><span id="span_experiencia"></span><br/>
	<select id="experiencia" name="experiencia" onfocus="compruebaNombre();compruebaApellidos();compruebaDNI();compruebaTelefono();comprueba_puestos();compruebaSexo();comprueba_estudios();comprueba_formulario()" onchange="comprueba_experiencia();comprueba_formulario()">
	    <option value="" selected="selected">- selecciona -</option>
	    <option value="1-5">1-5</option>
	    <option value="5-10">5-10</option>
	    <option value="10-20">10-20</option>
	    <option value="20+">Más de 20</option>
	</select>
	</fieldset>
	<fieldset>
	<legend><strong>Adjuntar datos</strong></legend>
	 Enviar foto... (opcional)<br/><br/>* El nombre de la foto debe contener su nombre y apellidos<br/><br/>
	<input type="file" name="foto"  onfocus="compruebaNombre();compruebaApellidos();compruebaDNI();compruebaTelefono();comprueba_puestos();compruebaSexo();comprueba_estudios();comprueba_experiencia();comprueba_formulario()" /><br/><br/>
	 Enviar Currículum... (opcional)<br/><br/>* El nombre del documento debe contener su nombre y apellidos<br/><br/>
	<input type="file" name="curriculum" onfocus="compruebaNombre();compruebaApellidos();compruebaDNI();compruebaTelefono();comprueba_puestos();compruebaSexo();comprueba_estudios();comprueba_experiencia();comprueba_formulario()" />
	</fieldset>
	<fieldset>
	<legend><strong>Descripción</strong></legend>
	<p>Breve descripción personal (obligatorio)</p>
	<textarea name="descripcion" id="texto_area" cols="40" rows="5" onkeypress="return limita(event, 100);" onkeyup="actualizaInfo(100);compruebaTexto()" onfocus="compruebaNombre();compruebaApellidos();compruebaDNI();compruebaTelefono();comprueba_puestos();compruebaSexo();comprueba_estudios();comprueba_experiencia();comprueba_formulario()" onchange="comprueba_formulario()" ></textarea>
	<br><br/>
	<span id="texto_span"></span>
	<span id="info_span">Máximo 100 caracteres</span>
	</fieldset>
	<fieldset>
	    <input type="checkbox" name="terminos" value="terminos" id="terminos_usu" onchange="comprueba_formulario()" onclick="comprueba_formulario()" onfocus="comprueba_formulario()"/> Acepto los términos de privacidad
	</fieldset>
	<fieldset>
	<br/>
	<input type="submit" name="enviar" value="Enviar" id="envio" style="width:130px; height:45px; font-weight: bold;" disabled="disabled" />	
	</fieldset>
</form>

</div>

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

</body>

</html>