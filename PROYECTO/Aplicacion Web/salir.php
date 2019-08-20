<?php

// Cerrar y destruir la sesión de usuario
session_start();
session_destroy();

// se le envia al index
header("Location: index.php");

?>