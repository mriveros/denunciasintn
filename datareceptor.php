<?php
 //Recibimos el parametro des de Android
	include './funciones.php';
	
    conexionlocal();
if (empty($_REQUEST['nombre'])){$nombre=' ';}else{ $nombre=utf8_decode($_REQUEST['nombre']);}
if (empty($_REQUEST['cedula'])){$cedula=0;}else{$cedula = $_REQUEST['cedula'];}
if (empty($_REQUEST['telefono'])){$telefono=' ';}else{$telefono = $_REQUEST['telefono'];}
$empresa = utf8_decode($_REQUEST['empresa']);
$direccion = utf8_decode($_REQUEST['direccion']);
$motivo = utf8_decode($_REQUEST['motivo']);
$observacion = utf8_decode($_REQUEST['observacion']);
$imagen = $_REQUEST['imagen'];

$imagenUrl='http://dev.appwebpy.com/denunciasintn/web/class/denuncias/'.$imagen.'.jpg';
$query = "INSERT INTO denuncias(den_motivo,den_obs,den_empresa,den_direccion,den_fecha,den_posx,den_posy,den_imagen,den_activo,den_confirm,den_nombre,den_ci,den_telef)VALUES ('$motivo','$observacion','$empresa','$direccion',now(),0,0,'$imagenUrl','t','f','$nombre','$cedula','$telefono');";
//ejecucion del query
$ejecucion = pg_query($query)or die('Error al realizar la carga');
 
	echo ("SERVER: ok, Datos Cargados Exitosamente..!");
enviarMail();
function enviarMail() 
        { 
            require 'phpmailer/PHPMailerAutoload.php';
            $correo = new PHPMailer();

            $correo->IsSMTP();

            $correo->SMTPAuth = true;

            $correo->SMTPSecure = 'tls';

            $correo->Host = "smtp.gmail.com";

            $correo->Port = 587;

            $correo->Username   = "sigappy.soporte@gmail.com";

            $correo->Password   = "Riveros200587!";

            $correo->SetFrom("sigappy.soporte@gmail.com", "Soporte Denuncias Ciudadanas");

            //$correo->AddReplyTo("mriveros.py@gmail.com","PHP SENDER");

            $correo->AddAddress("mriveros@intn.gov.py", "Marcos");

            $correo->Subject = "INTN Denuncias Ciudadanas";

            $correo->MsgHTML("Has recibido un nuevo registro de Denuncia <strong>INTN Denuncias Ciudadanas.</strong><a HREF='http://dev.appwebpy.com/denunciasintn/login/acceso.html'>Clic Aqui para Iniciar Sesion</a>");

            //$correo->AddAttachment("images/phpmailer.gif");

            if(!$correo->Send()) {
              //echo "Hubo un error: " . $correo->ErrorInfo;
            } else {
             //header("Refresh:0; url=http://dev.appwebpy.com/denunciasintn/web/galerias/ABMgaleria.php");
            }
        }
 
?>