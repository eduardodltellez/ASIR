/// Comprobación del formulario Añadir disco ///

window.onload=function(){ //Cuando cargue la pagina

 document.getElementById("referencia").focus(); //Colocar el foco en el primer elemento

}

var referencia=false; var fecha=false; var artista=false; var titulo=false; 
var precio=false; var stock=false; var sellos=false;
 

// Número de referencia
function comprueba_ref() {
	
  var elemento_referencia = document.getElementById("referencia");
  var valor_referencia = document.getElementById("referencia").value;
  var span_referencia = document.getElementById("span_referencia");

  if( /^\d{5}$/.test(valor_referencia) ) {
    elemento_referencia.style.backgroundColor="#c3ffc5";
	span_referencia.innerHTML="";
	referencia=true;
   }  
   
  else {
    span_referencia.style.color="red";
    elemento_referencia.style.backgroundColor="#fbb";
    span_referencia.innerHTML="Referencia incorrecta (Ej: 15975)";
	referencia=false;
    }
}

// Que la fecha no esté vacía
function comprueba_fecha() {
  var elemento_fecha = document.getElementById("fecha");
  var valor_fecha = document.getElementById("fecha").value;

  if( valor_fecha != null ) {
    elemento_fecha.style.backgroundColor="#c3ffc5";
	fecha=true;
   }  
   
  else {
    span_fecha.style.color="red";
    elemento_fecha.style.backgroundColor="#fbb";
	fecha=false;
    }
}

// Artista
function comprueba_artista() {
	
  var elemento_artista = document.getElementById("artista");
  var valor_artista = document.getElementById("artista").value;
  var span_artista = document.getElementById("span_artista");

  if( /^[A-Z][a-z]+(\s([a-z]{2,3}\s)*[A-Z][a-z]+)*$/.test(valor_artista) ) {
    elemento_artista.style.backgroundColor="#c3ffc5";
	span_artista.innerHTML="";
	artista=true;
   }  
   
  else {
    span_artista.style.color="red";
    elemento_artista.style.backgroundColor="#fbb";
    span_artista.innerHTML="Nombre de artista no correcto";
	artista=false;
    }
}

// Titulo
function comprueba_titulo() {
	
  var elemento_titulo = document.getElementById("titulo");
  var valor_titulo = document.getElementById("titulo").value;
  var span_titulo = document.getElementById("span_titulo");

  if( /^[A-Z][a-z]+(\s([a-z]{2,3}\s)*[A-Z][a-z]+)*$/.test(valor_titulo) ) {
    elemento_titulo.style.backgroundColor="#c3ffc5";
	span_titulo.innerHTML="";
	titulo=true;
   }  
   
  else {
    span_titulo.style.color="red";
    elemento_titulo.style.backgroundColor="#fbb";
    span_titulo.innerHTML="Título no correcto";
	titulo=false;
    }
}

// Precio
function comprueba_precio() {
	
  var elemento_precio = document.getElementById("precio");
  var valor_precio = document.getElementById("precio").value;
  var span_precio = document.getElementById("span_precio");

  if( /^\d\.\d\d$/.test(valor_precio) ) {
    elemento_precio.style.backgroundColor="#c3ffc5";
	span_precio.innerHTML="";
	precio=true;
   }  
   
  else {
    span_precio.style.color="red";
    elemento_precio.style.backgroundColor="#fbb";
    span_precio.innerHTML="Precio incorrecto (Ej: 8.50)";
	precio=false;
    }
}

// Stock
function comprueba_stock() {
	
  var elemento_stock = document.getElementById("stock");
  var valor_stock = document.getElementById("stock").value;
  var span_stock = document.getElementById("span_stock");

  if( /^[0-9]\d?\d?$/.test(valor_stock) ) {
    elemento_stock.style.backgroundColor="#c3ffc5";
	span_stock.innerHTML="";
	stock=true;
   }  
   
  else {
    span_stock.style.color="red";
    elemento_stock.style.backgroundColor="#fbb";
    span_stock.innerHTML="Stock incorrecto (0-999)";
	stock=false;
    }
}

/// Comprobar que se ha seleccionado alguna opción de los sellos
function comprueba_sellos() {
  var lista = document.getElementById("sello"); // Obtener la referencia a la lista
  var indiceSeleccionado = lista.selectedIndex; // Obtener el índice de la opción que se ha seleccionado
  var span_sello = document.getElementById("span_sello"); //Span

	 if (indiceSeleccionado==0) { // Si el índice es 0 es que no se ha elegido ninguno 
      span_sello.style.color="red";
      span_sello.innerHTML="Debe seleccionar un sello discográfico";
      sellos=false;
      }	  
   else {
      span_sello.innerHTML="";
	    sellos=true;
      }
}

/////////////////////////////////////////////////////////////////////

// Activar botón de Añadir
function comprueba_formulario() {
  
  //Comprobamos que referencia sea true
  if ( referencia && fecha && artista && titulo && precio && stock && sellos ) {
   document.getElementById("anadir").disabled=false;
   }
   else {
   document.getElementById("anadir").disabled=true; //Sino desactivamos
   }
}