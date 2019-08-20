/////////////////Comprobación del formularo Curriculums///////////////////

window.onload=function(){ //Cuando cargue la pagina

 document.getElementById("nombre").focus(); //Colocar el foco en el primer elemento

// Desactivar el botón de Registrarme

 function desactiva(){
   document.getElementById("envio").disabled=true;
   }
}

function envio_curriculum() {
	alert ("¡Gracias!");
}

//Variables para comprobar despues los distintos campos del formulario
var nombre=false; var apellidos=false; var dni=false; var telefono=false; var puestos=false; 
var sexo=false; var estudios=false; var experiencia=false; var texto=false;

// Comprobar Nombre
function compruebaNombre(){  

  var elemento_nombre = document.getElementById("nombre");
  var valor_nombre = document.getElementById("nombre").value;
  var span_nombre = document.getElementById("span_nombre");

  if( /^[A-Z][a-z]+(\s([a-z]{2,3}\s)*[A-Z][a-z]+)*$/.test(valor_nombre) ) {
    elemento_nombre.style.backgroundColor="#c3ffc5";
	span_nombre.innerHTML="";
	nombre=true;
   }  
  else {
    span_nombre.style.color="red";
    elemento_nombre.style.backgroundColor="#fbb";
    span_nombre.innerHTML="Introduzca su nombre correctamente ";
	nombre=false;
    }	
}

// Comprobar Apellidos
function compruebaApellidos() {
	
  var elemento_apellidos = document.getElementById("apellidos");
  var valor_apellidos = document.getElementById("apellidos").value;
  var span_apellidos = document.getElementById("span_apellidos");

  if( /^[A-Z][a-z]+(\s([a-z]{2,3}\s)*[A-Z][a-z]+)*$/.test(valor_apellidos) ) {
    elemento_apellidos.style.backgroundColor="#c3ffc5";
	span_apellidos.innerHTML="";
	apellidos=true;
   }  
   
  else {
    span_apellidos.style.color="red";
    elemento_apellidos.style.backgroundColor="#fbb";
    span_apellidos.innerHTML="Introduzca sus apellidos correctamente  ";
	apellidos=false;
    }
}

// Comprobar DNI
function compruebaDNI() {
	var elemento_dni = document.getElementById("dni");
    var valor_dni = document.getElementById("dni").value;
	valor_dni = valor_dni.toUpperCase();
    var letras_dni = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E', 'T'];
    var span_dni= document.getElementById("span_dni");
	
	if ( elemento_dni == null || elemento_dni.length == 0 || !(/^\d{8}[A-Z]$/.test(valor_dni)) || valor_dni.charAt(8) != letras_dni[(valor_dni.substring(0, 8))%23]) {
			elemento_dni.style.backgroundColor="#fbb";
			span_dni.innerHTML="Introduzca su DNI correctamente";
			span_dni.style.color="red";
			dni=false;
		}
		
	else {
		elemento_dni.style.backgroundColor="#c3ffc5";
		span_dni.innerHTML="";
		dni=true;
		}
}

// Comprobar Teléfono
function compruebaTelefono() {
	
  var elemento_telefono = document.getElementById("telefono");
  var valor_telefono = document.getElementById("telefono").value;
  var span_telefono = document.getElementById("span_telefono");

  if( /^(\+34|0034|34)?[6|7|9][0-9]{8}$/.test(valor_telefono) ) {
    elemento_telefono.style.backgroundColor="#c3ffc5";
	span_telefono.innerHTML="";
	telefono=true;
   }  
   
  else {
    span_telefono.style.color="red";
    elemento_telefono.style.backgroundColor="#fbb";
    span_telefono.innerHTML="Teléfono incorrecto ";
	telefono=false;
    }
}

// Checkbox puesto de trabajo buscado excluyentes
function excluyentes(seleccionado) {
    programador_web.checked = false;           
    logistica.checked = false;
    peon.checked = false;
   
    seleccionado.checked = true;   
}

//Comprobar que se ha seleccionado al menos un puesto de trabajo
function comprueba_puestos() {
var cuantos = 0;
var elementos = document.getElementsByName("puesto"); // Es un array con los checkbox de nombre puesto
var span_puestos= document.getElementById("span_puestos");

	for(i in elementos) {
      if (elementos[i].checked)  cuantos++;
	  }

	if (!cuantos) {   // Es lo mismo que cuantos==0
      span_puestos.style.color="red";
      span_puestos.innerHTML=" Debe seleccionar un puesto de trabajo";
      puestos=false;
      }
	  
    else {
      span_puestos.innerHTML="";
	  puestos=true;
      }
}

// Comprobar que se ha marcado el Sexo
function compruebaSexo() {
   var sexo_marcado=false;
   var span_sexo = document.getElementById("span_sexo");

   var elementos_sexo = document.getElementsByName("sexo"); // Es un array
   for(i in elementos_sexo) {
      if (elementos_sexo[i].checked)  sexo_marcado=true;
   }

   if (!sexo_marcado) {
      span_sexo.style.color="red";
      span_sexo.innerHTML=" Debe seleccionar su sexo";
	  sexo=false;
      }
	  
	else {
		span_sexo.innerHTML="";
		sexo=true;
		}
}

//Comprobar que se ha seleccionado alguna opción en Estudios
function comprueba_estudios() {
	var lista = document.getElementById("estudios"); // Obtener la referencia a la lista
	var span_estudios = document.getElementById("span_estudios"); //Span   
    var indiceSeleccionado = lista.selectedIndex; // Obtener el índice de la opción que se ha seleccionado
	
	 if (indiceSeleccionado==0) {     // Si el indice es 0 no ha elegido ninguno 
      span_estudios.style.color="red";
      span_estudios.innerHTML=" Debe seleccionar su nivel de estudios";
      estudios=false;
      }
	  
   else {
      span_estudios.style.color="green";
      span_estudios.innerHTML=" OK";
	  estudios=true;
      }
}

//Comprobar que se ha seleccionado alguna opción en Años de experiencia
function comprueba_experiencia() {
	var lista = document.getElementById("experiencia"); // Obtener la referencia a la lista
	var span_experiencia = document.getElementById("span_experiencia"); //Span   
    var indiceSeleccionado = lista.selectedIndex; // Obtener el índice de la opción que se ha seleccionado
	
	 if (indiceSeleccionado==0) {     // Si el indice es 0 no ha elegido ninguno 
      span_experiencia.style.color="red";
      span_experiencia.innerHTML=" Debe seleccionar sus años de experiencia";
      experiencia=false;
      }
	  
   else {
      span_experiencia.style.color="green";
      span_experiencia.innerHTML=" OK";
	  experiencia=true;
      }
}

//Area de texto comprobaciones de caracteres pulsados
function limita(elEvento, maximoCaracteres) {
	var elemento = document.getElementById("texto_area");
	// Obtener la tecla pulsada
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;
	// Permitir utilizar las teclas con flecha horizontal
	if(codigoCaracter == 37 || codigoCaracter == 39) {
	return true;
	}
	
	if(codigoCaracter == 8 || codigoCaracter == 46) {
	return true;
	}
	
	else if(elemento.value.length >= maximoCaracteres ) {
	return false;
	}
	
	else {
	return true;
	}
}

function actualizaInfo(maximoCaracteres) {
	var elemento = document.getElementById("texto_area");
	var info_span = document.getElementById("info_span");
	if(elemento.value.length >= maximoCaracteres ) {
	info_span.style.color="red";
	info_span.innerHTML = "Ha llegado al límite de caracteres permitidos";
	}
	
	else {
	info_span.style.color="green";
	info_span.innerHTML = "Puede escribir "+(maximoCaracteres-elemento.value.length)+" caracteres más";
	}
}

//Comprobar si se ha escrito algo en el Cuadro de texto
function compruebaTexto() {
	var elemento = document.getElementById("texto_area");
	var span_texto = document.getElementById("texto_span");
	
	if(elemento.value.length == 0 ) {
	span_texto.style.color="red";
	span_texto.innerHTML = " Debe escribir una pequeña descripción personal";
	texto=false;
	}
	
	else {
	span_texto.innerHTML = "";
	texto=true;
	}	
}

//Activar botón de Registro y comprobar si el checkbox está pinchado
function comprueba_formulario() {
	var terminos = document.getElementById("terminos_usu");
	
	//Comprobamos que todos los campos esten en true
	if ( nombre && apellidos && dni && telefono && puestos && sexo && estudios && experiencia && texto && terminos.checked ) {
	 document.getElementById("envio").disabled=false;
   }
   else {
	 document.getElementById("envio").disabled=true; //Sino desactivamos
   }
}