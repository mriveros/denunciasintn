<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2016
 * Sistema Servidor Disco
 */
session_start();
    $codusuario=  $_SESSION["codigo_usuario"];   
    include '../funciones.php';
    $ruta=$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
    conexionlocal();
    
    //Datos del Form Agregar
    if  (empty($_POST['txtEventoA'])){$eventoA='';}else{ $eventoA = $_POST['txtEventoA'];}
    if  (empty($_POST['file'])){$imagenA='';}else{ $imagenA= $_POST['file'];}
    if  (empty($_POST['txtDescripcionA'])){$descripcionA='';}else{ $descripcionA= $_POST['txtDescripcionA'];}
    if  (empty($_POST['txtVeredicto'])){$veredicto='';}else{ $veredicto= $_POST['txtVeredicto'];}  
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtEventoM'])){$eventoM='';}else{ $eventoM = $_POST['txtEventoM'];}
    if  (empty($_POST['txtImagenM'])){$imagenM='';}else{ $imagenM= $_POST['txtImagenM'];}
    if  (empty($_POST['txtObservacionM'])){$descripcionM='';}else{ $descripcionM= $_POST['txtObservacionM'];}
    if  (empty($_POST['txtEstadoM'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
    
    
    
        if(isset($_POST['modificar'])){
            
            modificarImagen($descripcionM,$estadoM,$codigoModif,$veredicto);
            $query = '';
            header("Refresh:0; url=http://$ruta/web/denunciasintn/web/respuestas/ABMrespuesta.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update respuestas set res_activo='f' WHERE res_cod=$codigoElim");
            header("Refresh:0; url=http://$ruta/web/denunciasintn/web/respuestas/ABMrespuesta.php");
	}
        
        
        
        //Clase para alzar imagenes
function modificarImagen($descripcion,$estado,$codigoModif,$veredicto){ 
    $ruta=$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];

        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

        $uploadsDirectory = $_SERVER['DOCUMENT_ROOT'] . $directory_self . 'respuestas/'; 

        $uploadForm = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMrespuesta.php'; 

        $uploadSuccess = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ABMrespuesta.php'; 

        $fieldname = 'file';
        $errors = array(1 => 'php.ini tamaño de archivo excedido', 
                        2 => 'tamaño excedido en la imagen', 
                        3 => 'solo se subio la imagen parcialmente', 
                        4 => 'ningun archivo fue subido'); 

        isset($_POST['modificar']) 
            or error('el formulario es necesario', $uploadForm); 

        ($_FILES[$fieldname]['error'] == 0) 
            or error($errors[$_FILES[$fieldname]['error']], $uploadForm); 

        @is_uploaded_file($_FILES[$fieldname]['tmp_name']) 
            or error('no es una subida http', $uploadForm); 

       // @getimagesize($_FILES[$fieldname]['tmp_name']) 
       //     or error('solo esta permitido subir imagenes', $uploadForm); 

        $now = time();
        $nombreimagen=''.$ruta.'web/denunciasintn/web/class/respuestas/'.$now.$_FILES[$fieldname]['name'];
        while(file_exists($uploadFilename = $uploadsDirectory.$now.$_FILES[$fieldname]['name'])) 
        { 
            $now++; 
        } 

        @move_uploaded_file($_FILES[$fieldname]['tmp_name'], $uploadFilename) 
            or error('sin permisos en el directorio', $uploadForm);
         $query =("update respuestas set res_url='$nombreimagen',"
                    . "res_obs= '$descripcion',res_veredicto='$veredicto',"
                    . "res_activo='$estado'"
                    . "WHERE res_cod=$codigoModif");
        //ejecucion del query
        $ejecucion = pg_query($query)or die('Error al realizar la carga');
        $query = '';
        echo"cargo";
        //header("Refresh:0; url=http://localhost/denunciasintn/web/respuestas/ABMrespuesta.php");
        
       // header('Location: ' . $uploadSuccess); 

}
function error($error, $location, $seconds = 5) 
        { 
    $ruta=$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
            header("Refresh: $seconds; URL=http://$ruta/web/denunciasintn/web/respuestas/ABMrespuesta.php"); 
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
