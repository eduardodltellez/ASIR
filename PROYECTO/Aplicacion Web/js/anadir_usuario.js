// Desactivar el botón de Crear usuario al cargar la página
window.onload=function(){

function desactiva() {
   document.getElementById("crear_usuario").disabled=true;
  }

}

// Inicialización de las variables para las comprobaciones
var login=false;
var clave=false;
var nombre=false;

// Comprobar que se ha escrito un login
function compruebaLogin(){  

  var elemento_login = document.getElementById("login");
  var length_login = document.getElementById("login").value.length;

  if( length_login >= 3 ) {
    elemento_login.style.backgroundColor="#c3ffc5";
    login=true;
   }  
  else {
    elemento_login.style.backgroundColor="#fbb";
    login=false;
    }   
}

///// Comprobar que se ha escrito una contraseña con la complejidad adecuada por seguridad
/*
La contraseña debe tener entre 8 y 16 caracteres, al menos un dígito, al menos una minúscula y al menos una mayúscula.
NO puede tener otros símbolos.
Ejemplo: w3Unpocodet0d0
*/
function compruebaClave(){  

  var elemento_clave = document.getElementById("clave");
  var valor_clave = document.getElementById("clave").value;

  if( /^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/.test(valor_clave) ) {
    elemento_clave.style.backgroundColor="#c3ffc5";
    clave=true;
   }  
  else {
    elemento_clave.style.backgroundColor="#fbb";
    clave=false;
    }   
}

// Comprobar que se ha escrito el nombre del usuario
function compruebaNombre(){  

  var elemento_nombre = document.getElementById("nombre");
  var length_nombre = document.getElementById("nombre").value.length;

  if( length_nombre >= 3 ) {
    elemento_nombre.style.backgroundColor="#c3ffc5";
    nombre=true;
   }  
  else {
    elemento_nombre.style.backgroundColor="#fbb";
    nombre=false;
    }   
}

// Activar botón de Crear Usuario si el formulario está OK
function comprueba_formulario() {
    if ( !login || !clave || !nombre ) {
     document.getElementById("crear_usuario").disabled=true; // Desactivamos
   }
   else {
     document.getElementById("crear_usuario").disabled=false; // Sino activamos
   }
}