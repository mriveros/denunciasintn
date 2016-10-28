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
			var descripcion=document.getElementById("dataTables-example").rows[indi+1].cells[4].innerText;
                        //var estado=document.getElementById("dataTables-example").rows[indi+1].cells[5].innerText;
                        document.getElementById("txtCodigo").value = codigo;
                        document.getElementById("txtNombreM").value = nombre;
			document.getElementById("txtDescripcionM").value = descripcion;
			
			});
		};
		function atenderDenuncia(codigo){
			document.getElementById("txtCodigo").value = codigo;
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
                function imprimirDenuncia(codigo){
                   
                    document.getElementById("txtCodigoImprimir").value = codigo;
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
                            Listado de Denuncias Atendidas
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th>Codigo</th>
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
                    $query = "select den.den_activo,den.den_cod,den.den_motivo,den.den_obs,den.den_empresa,den.den_direccion,to_char(den.den_fecha,'DD/MM/YYYY')as den_fecha,den.den_nombre,den.den_ci,den.den_telef from denuncias den where den_activo='t' and den_confirm='t';";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        $estado=$row1["den_activo"];
                        if($estado=='t'){$estado='Activo';}else{$confirmado='Inactivo';}
                        echo "<tr><td>".$row1["den_cod"]."</td>";
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
                         <a onclick='imprimirDenuncia(<?php echo $row1["den_cod"];?>)' class="btn btn-info  btn-xs active" data-toggle="modal" data-target="#modalimprimir" role="button">Imprimir</a>
                        <a onclick='atenderDenuncia(<?php echo $row1["den_cod"];?>)' class="btn btn-danger btn-xs active" data-toggle="modal" data-target="#modalmod" role="button">Terminar</a>
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
	<!-- /#MODAL AGREGAR OBSERVACIONES Y TERMINAR -->
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
                                    <form  id="upload" enctype="multipart/form-data"  autocomplete="off" class="form-horizontal" name="modificarform" action="../class/ClsDenuncias.php"  method="post" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                            <input type="hidden" name="txtCodigo" class="form-control" id="txtCodigo"  />
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
                                            <input type="numeric" name="codigo1" class="hide" id="input000" />
                                            <label  class="col-sm-2 control-label" for="input01">Observacion</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="txtObservaciones" class="form-control" id="txtObservaciones" placeholder="ingrese observacion" required />
                                            </div>
					</div>
					<div class="form-group">
                                            <label  class="col-sm-2 control-label" for="input01">Imagen</label>
                                            <div class="col-sm-10">
                                           <input type="file" name="file" class="form-control" id="file" placeholder="ingrese una imagen" required/>
                                            </div>
					</div>
                                        <div class="modal-footer">
					<button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" id="submit" value="Subir Imagen"  name="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                        </form>
				</div>
				
				<!-- Modal Footer -->
				
			</div>
		</div>
	</div>
        <div class="modal fade" id="modalimprimir" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header"><button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h3 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-pencil"></i>Imprimir Denuncia..</h3>
				</div>
				<!-- Modal Body -->
				<div class="modal-body">
                                    <form  id="upload" enctype="multipart/form-data"  autocomplete="off" class="form-horizontal" name="modificarform" action="../informes/Imp_Denuncia.php"  method="post" role="form">
                                        <div class="form-group">
                                            <div class="col-sm-10">
                                            <input type="hidden" name="txtCodigoImprimir" class="form-control" id="txtCodigoImprimir"  />
                                            </div>
					</div>
                                        <p>Se imprimirá el Registro de Denuncia..</p>
                                        <div class="modal-footer">
					<button type="reset" onclick="location.reload();" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
					<button type="submit" id="submit" value="Subir Imagen"  name="submit" class="btn btn-primary">Imprimir</button>
                                        </div>
                                        </form>
				</div>
				
				<!-- Modal Footer -->
				
			</div>
		</div>
	</div>
    
</html>
