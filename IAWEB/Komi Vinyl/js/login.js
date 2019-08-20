// Comprobar Login
function compruebaLogin(){  

  var valor_usuario = document.getElementById("usuario").value;  
  var valor_pass = document.getElementById("pass1").value;  
  
  if( valor_usuario.length == 0 || valor_pass.length == 0 ) {
	document.getElementById("login").disabled=true;
   }    
  else {
    document.getElementById("login").disabled=false;
   }	
}