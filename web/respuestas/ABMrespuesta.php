<?php
session_start();
if(!isset($_SESSION['codigo_usuario']))
header("Location:http://localhost/denunciasintn/login/acceso.html");
$catego=  $_SESSION["categoria_usuario"];
?>
<?php 

$directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']); 

$uploadHandler = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'ClsGalerias.php'; 

$max_file_size = 100000000; 

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>INTN- Respuestas</title>
    <!-- Bootstrap Core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
	<!-- DataTables CSS -->
    <link href="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="../../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../bower_components/metisMenu/dist/metisMenu.min.js"></script>
	
    <!-- DataTables JavaScript -->
    <script src="../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>
	    
	<!-- Page-Level Demo Scripts - Tables - Use for reference -->
   <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
			responsive: true
        });
    });
    </script>
	<script type="text/javascript">
		function modificar(codigo){
			$('tr').click(function() {
			indi = $(this).index();
			var descripcion=document.getElementById("dataTables-example").rows[indi+1].cells[4].innerText;
                       
                        //var estado=document.getElementById("dataTables-example").rows[indi+1].cells[5].innerText;
                        document.getElementById("txtCodigo").value = codigo;
			document.getElementById("txtObservacionM").value = descripcion;
			
			});
		};
		function eliminar(codigo){
			document.getElementById("txtCodigoE").value = codigo;
		};
	</script>
</head>

<body>

    <div id="wrapper">

        <?php 
        include("../funciones.php");
        if ($catego==1){
             include("../menu.php");
        }elseif($catego==2){
             include("../menu_usuario.php");
        }elseif($catego==3){
             include("../menu_supervisor.php");
        }
       
        conexionlocal();
        ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                      <h1 class="page-header">Respuestas - <small>INTN</small></h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Imagenes
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th style='display:none'>CodigoImagen</th>
                                            <th style='display:none'>CodigoEvento</th>
                                            <th>Fecha Respuesta</th>
                                            <th>Motivo Denuncia</th>
                                            <th>Observaciones</th>
					    <th>A Favor de</th>
                                            <th>Estado</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select to_char(res_fecha,'DD/MM/YYYY')as res_fecha,res.res_veredicto, res.res_cod,den.den_cod,den.den_motivo,res.res_obs,res.res_activo from respuestas res,denuncias den where res_activo='t' and den.den_cod=res.den_cod";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        $estado=$row1["res_activo"];
                        if($estado=='t'){$estado='Activo';}else{$estado='Inactivo';}
                        echo "<tr><td style='display:none'>".$row1["res_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["den_cod"]."</td>";
                         echo "<td>".$row1["res_fecha"]."</td>";
                        echo "<td>".$row1["den_motivo"]."</td>";
                        echo "<td>".$row1["res_obs"]."</td>";
			echo "<td>".$row1["res_veredicto"]."</td>";
                        echo "<td>".$estado."</td>";
                        echo "<td>";?>
                        
                        <a onclick='modificar(<?php echo $row1["res_cod"];?>)' class="btn btn-success btn-xs active" data-toggle="modal" data-target="#modalmod" role="button">Modificar</a>
                        <a onclick='eliminar(<?php echo $row1["res_cod"];?>)' class="btn btn-danger btn-xs active" data-toggle="modal" data-target="#modalbor" role="button">Borrar</a>
                        <?php
                        echo "</td></tr>";
                    }
                    pg_free_result($result);
                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                        
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
	
	<!-- /#MODAL MODIFICACIONES -->
	<div class="modal fade" id="modalmod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-pencil"></i> Modificar Registro</h3>
				</div>
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form id="upload" enctype="multipart/form-data" autocomplete="off" class="form-horizontal" name="modificarform" action="../class/ClsRespuestas.php" method="post" role="form">
					 <div class="col-sm-10">
                                            <input type="hidden" name="txtCodigo" class="form-control" id="txtCodigo"/>
                                            </div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Imagen</label>
                                            <div class="col-sm-10">
                                                  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>" />
                                                <input type="file" name="file" class="form-control" id="file" placeholder="ingrese una imagen" />
                                            </div>
					</div>
					
					<div class="form-group">
                                                <label class="col-sm-2 control-label" for="input01">A Favor de:</label>
                                                <div class="col-sm-10">
                                                <select id="txtVeredicto" name="txtVeredicto" class="form-control" required/>
                                                         <option value="Ninguno"></option>
                                                         <option value="Cliente">Cliente</option>
                                                         <option value="Empresa">Empresa</option>
                                                </select>
                                                </div>
                                        </div>




	
					<div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Observacion</label>
                                            <div class="col-sm-10">
                                            <input type="text" name="txtObservacionM" class="form-control" id="txtObservacionM" placeholder="ingrese una observacion" />
                                            </div>
					</div>
                                         <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input03">Estado</label>
                                            <div class="col-sm-10">
                                            <div class="radio">
                                            <label><input type="radio" name="txtEstadoM" value="1" checked /> Activo</label>
                                            <label><input type="radio" name="txtEstadoM" value="0" /> Inactivo</label>
                                            </div>
                                            </div>
					</div>
				
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="modificar" id="submit" value="Subir Imagen"   class="btn btn-primary">Guardar</button>
					
				</div>
                                </form>
			</div>
		</div>
	</div>
    </div>
	
	<!-- /#MODAL ELIMINACIONES -->
	<div class="modal fade" id="modalbor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-trash"></i> Borrar Registro</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form class="form-horizontal" name="borrarform" action="../class/ClsRespuestas.php" onsubmit="return submitForm();" method="post" role="form">
						<div class="form-group">
							<input type="numeric" name="txtCodigoE" class="hide" id="txtCodigoE" />
							<div class="alert alert-danger alert-dismissable col-sm-10 col-sm-offset-1">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								¡¡¡ATENCION!!! ...Se borrara el siguiente registro..
							</div>
						</div>
				</div>
				
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="borrar" class="btn btn-danger">Borrar</button>
					</form>
				</div>
			</div>
		</div>
	</div>
    
</html>
