<?php 
	session_start(); 
	session_destroy();
    $ruta=$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/web";
	header("Location:http://$ruta/denunciasintn/login/acceso.html");
?>
