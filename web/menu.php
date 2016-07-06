<?php
  
    $conectate=pg_connect("host=localhost port=5432 dbname=denunciasintn user=postgres password=postgres")or die ('Error al conectar a la base de datos');
    $consulta1= pg_exec($conectate,"select count(den_cod) as cantidad from denuncias where den_fecha < now()");
    $consulta2= pg_exec($conectate,"select count(den_cod) as cantidad from denuncias where den_activo='t'   and den_fecha < now()");
    $consulta3= pg_exec($conectate,"select count(den_cod) as cantidad from denuncias where den_activo='f' and den_fecha < now()");
    $consulta4= pg_exec($conectate,"select count(den_cod) as cantidad from denuncias where den_activo='t'  and den_fecha < now()");

    $cantidadReservas=pg_result($consulta1,0,'cantidad');
    $ReservasNoCOnfirmadas=pg_result($consulta2,0,'cantidad');
    $ReservasRechazadas=pg_result($consulta3,0,'cantidad');
    $ReservasConfirmadas=pg_result($consulta4,0,'cantidad');  
    $ruta="dev.appwebpy.com";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap Core CSS -->
    <link href="../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
     <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
    <title>INTN</title>
</head>

<body>
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
           
            <div class="navbar-header">
                  
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    
                    <span class="sr-only">Toggle navigation</span>
                    
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img src="http://<?php echo $ruta;?>/denunciasintn/img/head.png" width=500 height=80 alt="Obra de K. Haring"> 
            </div>
            <center><a class="navbar-brand" href="#"><h2>Sistema Servidor de Denuncias - INTN</h2></a></center>
            <!-- /.navbar-header -->
            <br><br>
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Denuncias Recibidas</strong>
                                        <span class="pull-right text-muted"><?php echo $ReservasConfirmadas;?> Denuncias</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $ReservasConfirmadas;?>" aria-valuemin="0" aria-valuemax="<?php echo $cantidadReservas;?>" style="width: <?php echo $cantidadReservas;?>%">
                                            <span class="sr-only"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Denuncias Atendidas</strong>
                                        <span class="pull-right text-muted"><?php echo $ReservasNoCOnfirmadas;?> Denuncias</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $ReservasNoCOnfirmadas;?>" aria-valuemin="0" aria-valuemax="<?php echo $cantidadReservas;?>" style="width: <?php echo $cantidadReservas;?>%">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                         <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Denuncias Resueltas</strong>
                                        <span class="pull-right text-muted"><?php echo $ReservasRechazadas;?> Denuncias</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $ReservasRechazadas;?>" aria-valuemin="0" aria-valuemax="<?php echo $cantidadReservas;?>" style="width: <?php echo $cantidadReservas;?>%">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Cerrar</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <?php echo "USUARIO"//$_SESSION['usernom']." ".$_SESSION['userape']; ?> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> Cambiar Contraseña</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Configuracion</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="http://<?php echo $ruta;?>/denunciasintn/web/logout.php"><i class="fa fa-sign-out fa-fw"></i> Cerrar Sesion</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
          

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="http://<?php echo $ruta;?>/denunciasintn/web/menu.php" value="Load new document" onclick="location.reload();"><i class="fa  fa-tasks"></i> Menu Principal</a>
                        </li>
			<li>
                            <a href="#"><i class="fa fa-user"></i> USUARIOS<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="http://<?php echo $ruta;?>/denunciasintn/web/usuarios/ABMusuario.php">Registros de Usuarios</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
			<li>
                            <a href="#"><i class="fa  fa-users"></i> DENUNCIAS<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="http://<?php echo $ruta;?>/denunciasintn/web/denuncias/ABMdenuncia.php"> Denuncias Recibidas</a>
                                     <a href="http://<?php echo $ruta;?>/denunciasintn/web/denuncias/denunciasAtendidas.php">Denuncias Atendidas</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                       
                         
                         <li>
                            <a href="#"><i class="fa  fa-flickr "></i> RESPUESTAS<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="http://<?php echo $ruta;?>/denunciasintn/web/respuestas/ABMrespuesta.php">Registros de Respuestas</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                         
                        <li>
                            <a href="#"><i class="fa  fa-file-text "></i> INFORMES<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="http://<?php echo $ruta;?>/denunciasintn/web/informes/frmResumenDenuncias.php">Resumen Denuncias</a>
                                    <a href="http://<?php echo $ruta;?>/denunciasintn/web/informes/frmResumenDenunciasRechazadas.php">Denuncias Rechazadas</a>
                                    <a href="http://<?php echo $ruta;?>/denunciasintn/web/informes/frmResumenDenunciasAtendidas.php">Denuncias Atendidas</a>
                                    <a href="http://<?php echo $ruta;?>/denunciasintn/web/informes/frmResumenRespuestas.php">Respuestas Emitidas</a>
                                </li>
                                
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <li>
                            <a href="#"><i class=""></i> Help <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                
                                    <a href="">Contacte con el Programador: mriveros@intn.gov.py</a>
                              
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
       
</body>
</html>
