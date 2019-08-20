// Desactivar el botón de enviar al cargar la página
window.onload=function(){

function desactiva() {
   document.getElementById("crear_regla").disabled=true;
  }

}

// Mostrar cuadro de texto para introducir la IP si marcamos Equipo específico
function mostrar(numero) {
  if(numero==0) {
    document.getElementById('div_equipo').style.display = 'none';
  }
  else {
    document.getElementById('div_equipo').style.display = '';
  }
}

// Inicialización de las variables para las comprobaciones
var IP=true;
var URL=true;
var mensaje=false;

// Comprobar que la IP introducida es correcta
function compruebaIP() {  

  var elemento_IP = document.getElementById("IP_equipo");
  var valor_IP = document.getElementById("IP_equipo").value;

//  if( /^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(valor_IP) ) {

    if( /^[1][9][2]\.[1][6][8]\.[3][0]\.[1-9][0-9]?[0-9]?$/.test(valor_IP) ) {
    elemento_IP.style.backgroundColor="#c3ffc5";
    IP=true;
   }  
  else {
    elemento_IP.style.backgroundColor="#fbb";
    IP=false;
    }   
}

// Comprobar que la URL introducida es correcta
function compruebaURL(){  

  var elemento_URL = document.getElementById("URL");
  var valor_URL = document.getElementById("URL").value;

  if( /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(valor_URL) ) {
    elemento_URL.style.backgroundColor="#c3ffc5";
    URL=true;
   }  
  else {
    elemento_URL.style.backgroundColor="#fbb";
    URL=false;
    }   
}

// Comprobar que se introduce un mensaje para mostrar
function compruebaMensaje(){  

  var elemento_mensaje = document.getElementById("mensaje");
  var length_mensaje = document.getElementById("mensaje").value.length;

  if( length_mensaje >= 5 ) {
    elemento_mensaje.style.backgroundColor="#c3ffc5";
    mensaje=true;
   }  
  else {
    elemento_mensaje.style.backgroundColor="#fbb";
    mensaje=false;
    }   
}

// Activar botón de Añadir Alerta si el formulario está OK
function comprueba_formulario() {

  var valor_URL=document.getElementById('URL').value;

    if ( !IP || !URL || !mensaje || valor_URL === "" ) {
     document.getElementById("crear_regla").disabled=true; // Desactivamos
   }
   else {
     document.getElementById("crear_regla").disabled=false; // Sino activamos
   }
}