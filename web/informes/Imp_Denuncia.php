
<?php
session_start();
require('./fpdf.php');
include '../MonedaTexto.php';
class PDF extends FPDF{
    
function Footer()
{
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(230,280,9,280);//largor,ubicacion derecha,inicio,ubicacion izquierda
    // Go to 1.5 cm from bottom
        $this->SetY(-15);
    // Select Arial italic 8
        $this->SetFont('Arial','I',8);
    // Print centered page number
	$this->Cell(0,2,utf8_decode('Página: ').$this->PageNo().' de {nb}',0,0,'R');
	$this->text(10,283,'Datos Generados en: '.date('d-M-Y').' '.date('h:i:s'));
}
function Header()
{
   // Select Arial bold 15
    $this->SetFont('Arial','',16);
    $this->Image('img/intn.jpg',10,14,-300,0,'','../../InformeCargos.php');
    // Move to the right
    $this->Cell(80);
    // Framed title
    $this->text(37,19,utf8_decode('Instituto Nacional de Tecnología, Normalización y Metrología'));
    $this->SetFont('Arial','',8);
    $this->text(37,24,"Avda. Gral. Artigas 3973 c/ Gral Roa- Tel.: (59521)290 160 -Fax: (595921) 290 873 ");
    $this->text(37,29,"Denuncias Ciudadanas");
    $this->text(37,34,"Telefax: (595921) 295 408 e-mail: cmencia@intn.gov.py");
    //-----------------------TRAEMOS LOS DATOS DE CABECERA----------------------
   
    //---------------------------------------------------------
        $this->Ln(30);
        $this->Ln(30);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(230,40,10,40);//largor,ubicacion derecha,inicio,ubicacion izquierda
    //------------------------RECIBIMOS LOS VALORES DE POST-----------
    
    if  (empty($_POST['txtCodigoImprimir'])){$codigo_denuncia='';}else{ $codigo_denuncia = $_POST['txtCodigoImprimir'];}
    
    $conectate=pg_connect("host=localhost port=5432 dbname=denunciasintn user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
    $consulta=pg_exec($conectate,"select * from denuncias where den_cod=$codigo_denuncia");
   
    $fecha=pg_result($consulta,0,'den_fecha');
    $empresa=pg_result($consulta,0,'den_empresa');
    $motivo=pg_result($consulta,0,'den_motivo');
    $direccion=pg_result($consulta,0,'den_direccion');
    $ciudad=pg_result($consulta,0,'den_ciudad');
   
    //table header CABECERA        
    $this->SetFont('Arial','B',10);
    $this->SetTitle('Denuncias Ciudadanas');
    //---------------------Encabezado Izquierda--------------------------------
    $this->text(80,48,'DENUNCIAS CIUDADANAS');
    
    
    $this->text(15,73,'Fecha:');
    $this->text(15,83,'Empresa:');
    $this->text(15,93,'Motivo:');
    $this->text(15,103,'Direccion:');
    $this->text(15,113,'Ciudad:');
    $this->SetFont('Arial','',10);
    $this->text(30,73,$fecha);
    $this->text(35,83,$empresa);
    $this->text(29,93,$motivo);
    $this->text(35,103,$direccion);
    $this->text(35,113,$ciudad);
    //---------------------Encabezado Derecha--------------------------------
   
    
   
   
}
}

$pdf= new PDF();//'P'=vertical o 'L'=horizontal,'mm','A4' o 'Legal'
$pdf->AddPage();
//------------------------RECIBIMOS LOS VALORES DE POST-----------
 if  (empty($_POST['txtCodigoImprimir'])){$codigo_denuncia='';}else{ $codigo_denuncia = $_POST['txtCodigoImprimir'];}
//-------------------------Damos formato al informe-----------------------------    

    
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
   
//----------------------------Build table---------------------------------------
$pdf->SetXY(10,100);


$fill=false;
$i=0;
$pdf->SetFont('Arial','',10);

//------------------------QUERY and data cargue y se reciben los datos-----------
$conectate=pg_connect("host=localhost port=5432 dbname=denunciasintn user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
$consulta=pg_exec($conectate,"");
$numregs=pg_numrows($consulta);
  



    $descripcion=pg_result($consulta,0,'con_nom');
    $monto=pg_result($consulta,0,'pag_monto');
    $observacion=pg_result($consulta,0,'fac_obs');
    $codfactura=pg_result($consulta,0,'fac_cod');
    
 
 
//-------------------Restar las retenciones de la factura---------------
$query = "";
$resultado=pg_query($query);
$row=  pg_fetch_array($resultado);
$retencionMonto=$row[0];//1179156.24

//----------------------------Parte derecha-------------------------------------
$pdf->text(20,150,'Firma: ________________________');
$pdf->text(100,150,'Aclaracion: _______________________________');

//---------------------------Ultima parte---------------------------------------
$pdf->Output("DenunciasCiudadanas_".$codigo_denuncia,"I");
$pdf->Close();
