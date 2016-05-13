<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de Compras y Pagos DiscoA 2.0
 */
session_start();
$codusuario=  $_SESSION["codigo_usuario"];
$ruta=$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
    include '../funciones.php';
    conexionlocal();
    
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombreM'])){$nombreM='';}else{ $nombreM = $_POST['txtNombreM'];}
    if  (empty($_POST['txtDescripcionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    if  (empty($_POST['txtObservaciones'])){$observaciones='';}else{ $observaciones= $_POST['txtObservaciones'];}
    if  (empty($_POST['txtResolucion'])){$archivoResolucion='';}else{ $archivoResolucion= $_POST['txtResolucion'];}
    if  (empty($_POST['txtVeredicto'])){$veredicto='';}else{ $veredicto= $_POST['txtVeredicto'];} 
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
        //si es Modificar    
        if(isset($_POST['modificar'])){
            pg_query("update denuncias set den_confirm='$estadoM' where den_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://dev.appwebpy.com/denunciasintn/web/denuncias/ABMdenuncia.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update denuncias set den_activo='f',den_confirm='f' WHERE den_cod=$codigoElim");
            header("Refresh:0; url=http://dev.appwebpy.com/denunciasintn/web/denuncias/ABMdenuncia.php");
	}
         if(isset($_POST['submit'])){
            
            subirImagen($codigoModif, $observaciones,$archivoResolucion,$codigoModif,$veredicto);
            header("Refresh:0; url=http://dev.appwebpy.com/denunciasintn/web/denuncias/denunciasAtendidas.php");
	}
        
        function subirImagen($codigoModif,$observaciones,$archivoResolucion,$codigoModif,$veredicto){ 

        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

        $uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'respuestas/'; 

        $uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'denunciasAtendidas.php'; 

        $uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMrespuesta.php'; 

        $fieldname = 'file';
        $errors = array(1 => 'php.ini tamaño de archivo excedido', 
                        2 => 'tamaño excedido en la imagen', 
                        3 => 'solo se subio la imagen parcialmente', 
                        4 => 'ningun archivo fue subido'); 

        isset($_POST['submit']) 
            or error('el formulario es necesario', $uploadForm); 

        ($_FILES[$fieldname]['error'] == 0) 
            or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 

        @is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
            or error('no es una subida http', $uploadForm); 

        @getimagesize($_FILES[$fieldname]['tmp_name']) 
            or error('solo esta permitido subir imagenes', $uploadForm); 

        $now = time();
        $nombreimagen='http://dev.appwebpy.com/denunciasintn/web/class/respuestas/'.$now.$_FILES[$fieldname]['name'];
        while(file_exists($uploadFilename = $uploadsDirectory.$now.$_FILES[$fieldname]['name'])) 
        { 
            $now++; 
        } 

        @move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
            or error('sin permisos en el directorio', $uploadForm); 
         
        
        pg_query("insert into respuestas(den_cod,res_fecha,res_obs,res_url,res_activo,res_veredicto) values($codigoModif,'now()','$observaciones','$nombreimagen','t','$veredicto')");
        pg_query("update denuncias set den_activo='f' WHERE den_cod=$codigoModif");
        //ejecucion del query
        header("Refresh:0; url=http://dev.appwebpy.com/denunciasintn/web/galerias/ABMgaleria.php");
        
       // header('Location: ' . $uploadSuccess); 

}

function error($error, $location, $seconds = 5) 
        { 
            header("Refresh: $seconds; URL=http://dev.appwebpy.com/denunciasintn/web/respuestas/ABMrespuesta.php"); 
            echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"'.
            '"http://www.w3.org/TR/html4/strict.dtd">'.
            '<html lang="es">'.
            '    <head>'.
            '        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">'.
            '        <link rel="stylesheet" type="text/css" href="stylesheet.css">' .
            '    <title>Error al subir</title>'.
            '    </head>'.
            '    <body>'.
            '    <div id="Upload">'.
            '        <h1>Codigo de error.</h1>'.
            '        <p>Un error a ocurrido: '.
            '        <span class="red">' . $error . '...</span>'.
            '         el formulario esta recargandose.</p>' .
            '     </div>'.
            '</html>'; 
            exit; 
        }

