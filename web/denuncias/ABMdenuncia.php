<?php
session_start();
if(!isset($_SESSION['codigo_usuario']))
header("Location:http://localhost/denunciasintn/login/acceso.html");
$catego=  $_SESSION["categoria_usuario"];

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>INTN- Denuncias</title>
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
             document.addEventListener("DOMContentLoaded", inicio, false);
		function confirmar(codigo){
			$('tr').click(function() {
			indi = $(this).index();
                       	var nombre=document.getElementById("dataTables-example").rows[indi+1].cells[2].innerText;
			var empresa=document.getElementById("dataTables-example").rows[indi+1].cells[4].innerText;
                        var observacion=document.getElementById("dataTables-example").rows[indi+1].cells[3].innerText;
                        //var estado=document.getElementById("dataTables-example").rows[indi+1].cells[5].innerText;
                        document.getElementById("txtCodigo").value = codigo;
                        document.getElementById("txtNombreM").value = nombre;
			document.getElementById("txtDescripcionM").value = empresa;
                        document.getElementById("txtObservacionM").value = observacion;
			
			});
		};
		function eliminar(codigo){
			document.getElementById("txtCodigoE").value = codigo;
		};
                function verDatos(){
			$('tr').click(function() {
			indi = $(this).index();
                        
                       	var nombre=document.getElementById("dataTables-example").rows[indi+1].cells[8].innerText;
                        var cedula=document.getElementById("dataTables-example").rows[indi+1].cells[9].innerText;
                        var telefono=document.getElementById("dataTables-example").rows[indi+1].cells[10].innerText;
			
                        document.getElementById("txtNombre").value = nombre;
                        document.getElementById("txtCedula").value = cedula;
                        document.getElementById("txtTelefono").value = telefono;
                        
                  
			
			});
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
                      <h1 class="page-header">Denuncias - <small>INTN</small></h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Denuncias
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th style='display:none'>Codigo</th>
                                            <th style='display:none'>Imagen Denuncia</th>
                                            <th>Motivo</th>
                                            <th>Observacion</th>
                                            <th>Empresa</th>
                                            <th>Direccion</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th style='display:none'>Nombre</th>
                                            <th style='display:none'>Cedula</th>
                                            <th style='display:none'>Telefono</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select den.den_cod,den.den_activo,den.den_obs,den.den_empresa,den.den_imagen,den.den_motivo,den.den_direccion,to_char(den_fecha,'DD/MM/YYYY')as den_fecha,den.den_nombre,den.den_ci,den.den_telef,den.den_activo from denuncias den where den_activo='t' and den_confirm='f';";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        $estado=$row1["den_activo"];
                        if($estado=='t'){$estado='Activo';}else{$confirmado='Inactivo';}
                        echo "<tr><td style='display:none'>".$row1["den_cod"]."</td>";
                        echo "<td style='display:none'>".$row1["den_imagen"]."</td>";
                        echo "<td>".$row1["den_motivo"]."</td>";
                        echo "<td>".$row1["den_obs"]."</td>";
                        echo "<td>".$row1["den_empresa"]."</td>";
                        echo "<td>".$row1["den_direccion"]."</td>";
                        echo "<td>".$row1["den_fecha"]."</td>";
                        echo "<td>".$estado."</td>";
                        echo "<td style='display:none'>".$row1["den_nombre"]."</td>";
                        echo "<td style='display:none'>".$row1["den_ci"]."</td>";
                        echo "<td style='display:none'>".$row1["den_telef"]."</td>";
                        echo "<td>";?>
                        <a onclick='verImagen(<?php echo $row1["den_cod"];?>)' href="<?php echo $row1["den_imagen"];?>" class="btn btn-default btn-xs active" data-toggle="modal" role="button">Ver Imagen</a>
                        <a onclick='verDatos(<?php echo $row1["den_cod"];?>)' class="btn btn-success btn-xs active" data-toggle="modal" data-target="#modaldatos" role="button">Denunciante</a>
                        <a onclick='confirmar(<?php echo $row1["den_cod"];?>)' class="btn btn-primary btn-xs active" data-toggle="modal" data-target="#modalmod" role="button">Atender!</a>
                        <a onclick='eliminar(<?php echo $row1["den_cod"];?>)' class="btn btn-danger btn-xs active" data-toggle="modal" data-target="#modalbor" role="button">Borrar</a>
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
   <!-- /#MODAL VER IMAGEN -->
	<div class="modal fade" id="modaldatos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-pencil"></i>Datos del Denunciante</h3>
				</div>
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form  autocomplete="off" class="form-horizontal" name="verimagen" role="form">
                                    
                                        <div class="form-group">
                                            <input type="numeric" name="codigo1" class="hide" id="input000" />
                                            <label  class="col-sm-2 control-label" for="input01">Denunciante</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtNombre" class="form-control" id="txtNombre" readonly="true"/>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <input type="numeric" name="codigo1" class="hide" id="input000" />
                                            <label  class="col-sm-2 control-label" for="input01">Cedula</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtCedula" class="form-control" id="txtCedula" readonly="true"/>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <input type="numeric" name="codigo1" class="hide" id="input000" />
                                            <label  class="col-sm-2 control-label" for="input01">Telefono</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtTelefono" class="form-control" id="txtTelefono"  readonly="true"/>
                                            </div>
					</div>
                                    </form>
				</div>
				
				<!-- Modal Footer -->
				
			</div>
		</div>
	</div>
	
	<!-- /#MODAL MODIFICACIONES -->
	<div class="modal fade" id="modalmod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-pencil"></i> Confirmar Denuncia!</h3>
				</div>
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form  autocomplete="off" class="form-horizontal" name="modificarform" action="../class/ClsDenuncias.php"  method="post" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                            <input type="hidden" name="txtCodigo" class="form-control" id="txtCodigo"  />
                                            </div>
					</div>
                                        <div class="form-group">
                                            <input type="numeric" name="codigo1" class="hide" id="input000" />
                                            <label  class="col-sm-2 control-label" for="input01">Motivo</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtNombreM" class="form-control" id="txtNombreM" placeholder="ingrese nombre" readonly="true"/>
                                            </div>
					</div>
                                        <div class="form-group">
                                            <input type="numeric" name="codigo1" class="hide" id="input000" />
                                            <label  class="col-sm-2 control-label" for="input01">Observacion</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtObservacionM" class="form-control" id="txtObservacionM" placeholder="ingrese observacion" readonly="true"/>
                                            </div>
					</div>
					<div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Empresa</label>
                                            <div class="col-sm-10">
                                            <input type="text" name="txtDescripcionM" class="form-control" id="txtDescripcionM" placeholder="ingrese una descripcion" readonly="true" />
                                            </div>
					</div>
                                        <div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input03">Desea Confirmar?</label>
                                            <div class="col-sm-10">
                                            <div class="radio">
                                            <label><input type="radio" name="txtEstadoM" value="1" checked /> SI</label>
                                            <label><input type="radio" name="txtEstadoM" value="0" /> NO</label>
                                            </div>
                                            </div>
					</div>
                                        <div class="modal-footer">
					<button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" name="modificar" class="btn btn-primary">Guardar</button>
                                        </div>
                                        </form>
				</div>
				
				<!-- Modal Footer -->
				
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
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-trash"></i> Eliminar Denuncia! :(</h3>
				</div>
            
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form class="form-horizontal" name="borrarform" action="../class/ClsDenuncias.php" onsubmit="return submitForm();" method="post" role="form">
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