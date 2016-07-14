<?php 
session_start();
?>
<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de Estaciones ONM INTN
 */
include '../web/funciones.php';
//$ruta="192.168.0.99/web";
$ruta=$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/web";
 conexionlocal();
$usr= $_REQUEST['username'];
$pwd=md5($_REQUEST['clave']);
//$pwd= md5($pwd); esto usaremos despues para comparar carga que se realizara en md5
//session_start();
//print_r($_REQUEST);
//INGRESO DE USUARIO
	$sql= "SELECT * FROM usuarios u
        WHERE u.usu_username = '$usr' AND u.usu_pass =('$pwd') and u.estado='t'" ;
	//echo "$sql";
	//echo $n.' ---'.$sql; 
	$datosusr = pg_query($sql);
        $row = pg_fetch_array($datosusr);
        $n=0;
        $n = count($row['usu_nom']);
	if($n==0)
	{
		echo '<script type="text/javascript">
                         alert("Nombre de Usuario o Password no valido..!");
			 window.location="http://localhost/denunciasintn/login/acceso.html";
                      </script>';
	}
	else
	{
            $_SESSION["username"] = $row['usu_username'];
            $_SESSION["nombre_usuario"] = $row['usu_nom'];
            $_SESSION["codigo_usuario"] = $row['usu_cod'];
            $_SESSION["categoria_usuario"] = $row['cat_cod'];
            if ($row['cat_cod']==1){
            
                header("Location:http://$ruta/denunciasintn/web/menu.php");
                 
            }else if($row['cat_cod']==2){
                header("Location:http://$ruta/denunciasintn/web/menu_usuario.php");
                 
            
            }else if($row['cat_cod']==3){
                 header("Location:http://$ruta/denunciasintn/web/menu_supervisor.php");
            }
        }
