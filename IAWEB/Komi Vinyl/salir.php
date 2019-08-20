<?php
// Cerrar la sesión de usuario
session_start();
session_destroy();
header("Location: index.php");
?>