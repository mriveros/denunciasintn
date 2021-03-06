<?php 
session_start();
?>
<?php
//Example FPDF script with PostgreSQL
//Ribamar FS - ribafs@dnocs.gov.br

require('fpdf.php');

class PDF extends FPDF{
function Footer()
{
        
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(343,236,15,236);//largor,ubicacion derecha,inicio,ubicacion izquierda
    // Go to 1.5 cm from bottom
        $this->SetY(-15);
    // Select Arial italic 8
        $this->SetFont('Arial','I',8);
    // Print centered page number
	$this->Cell(0,2,utf8_decode('Página: ').$this->PageNo().' de {nb}',0,0,'R');
	$this->text(10,234,'Consulta Generada: '.date('d-M-Y').' '.date('h:i:s'));
}

function Header()
{
   // Select Arial bold 15
        $this->SetFont('Arial','',9);
	$this->Image('img/intn.jpg',15,10,-300,0,'','../../InformeCargos.php');
    // Move to the right
    $this->Cell(80);
    // Framed title
	$this->text(15,32,utf8_decode('Instituto Nacional de Tecnología, Normalización y Metrología'));
	$this->text(315,32,'Sistema Servidor Denuncias Ciudadanas');
        //$this->text(315,37,'Mes: '.utf8_decode(genMonth_Text($mes).' Año: 2016'));
	//$this->Cell(30,10,'noc',0,0,'C');
    // Line break
    $this->Ln(30);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(360 ,33,10,33);//largor,ubicacion derecha,inicio,ubicacion izquierda
//table header        
    
    $this->SetFont('Arial','B',8);
    $this->SetTitle('RESUMEN DE DENUNCIAS RECHAZADAS');
    $this->Cell(300,5,'DENUNCIAS CIUDADANAS',100,100,'C');//Titulo
    $this->Cell(300,10,'DENUNCIAS RECHAZADAS O NO ATENDIDAS',100,100,'C');//Titulo
    $this->SetFillColor(153,192,141);
    $this->SetTextColor(255);
    $this->SetDrawColor(153,192,141);
    $this->SetLineWidth(.3);
    /*$this->Cell(20,10,'SIAPE',1,0,'L',1);
    $this->Cell(50,10,'Nome',1,1,'L',1);*/
    
    $this->Cell(25,10,'Item',1,0,'C',1);
    $this->Cell(100,10,'Motivo',1,0,'C',1);
    $this->Cell(50,10,'Empresa',1,0,'C',1);
    $this->Cell(100,10,'Direccion',1,0,'C',1);
    $this->Cell(20,10,'Fecha',1,0,'C',1);
    $this->Cell(30,10,'Estado',1,1,'C',1);
}
}

$pdf=new PDF();//'P'=vertical o 'L'=horizontal,'mm','A4' o 'Legal'
//obtener el nombre de organismo------------------------------------------------
//QUERY and data cargue y se reciben los datos

if  (empty($_POST['txtDesdeFecha'])){$fechadesde=0;}else{$fechadesde=$_POST['txtDesdeFecha'];}
if  (empty($_POST['txtHastaFecha'])){$fechahasta=0;}else{$fechahasta=$_POST['txtHastaFecha'];}

  $mes=substr($fechadesde, 5, 2);
//------------------------------------------------------------------------------      
$pdf->AddPage('L', 'Legal');
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',10);

$conectate=pg_connect("host=localhost port=5432 dbname=denunciasintn user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
$consulta=pg_exec($conectate,"SELECT * 
                    from denuncias
                    where den_fecha >= '$fechadesde' and den_fecha<='$fechahasta' and den_activo='f'and den_confirm='f' order by den_fecha ");

$numregs=pg_numrows($consulta);
$pdf->SetFont('Arial','',7);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
//Build table
$fill=false;
$i=0;
while($i<$numregs)
{
    
   
    $motivo=pg_result($consulta,$i,'den_motivo');
    $empresa=pg_result($consulta,$i,'den_empresa');
    $direccion=pg_result($consulta,$i,'den_direccion');
    $fecha=pg_result($consulta,$i,'den_fecha');
    $estado=pg_result($consulta,$i,'den_activo');
  
   
   
  /*

   $this->Cell(25,10,'Item',1,0,'C',1);
    $this->Cell(50,10,'Motivo',1,0,'C',1);
    $this->Cell(80,10,'Observacion',1,0,'C',1);
    $this->Cell(25,10,'Empresa',1,0,'C',1);
    $this->Cell(30,10,'Direccion',1,0,'C',1);
    $this->Cell(20,10,'Fecha',1,0,'C',1);
    $this->Cell(30,10,'Nombre',1,0,'C',1);
    $this->Cell(15,10,'Cedula',1,0,'C',1);
    $this->Cell(15,10,'Telefono',1,1,'C',1);   */
    
   
     
    $pdf->Cell(25,5,$i+1,1,0,'C',$fill);
    $pdf->Cell(100,5,$motivo,1,0,'L',$fill);
    $pdf->Cell(50,5,$empresa,1,0,'L',$fill);
    $pdf->Cell(100,5,$direccion,1,0,'L',$fill);
    $pdf->Cell(20,5,$fecha,1,0,'C',$fill);
    if($estado=='f'){$estado='Inactivo';};
    $pdf->Cell(30,5,$estado,1,1,'L',$fill);
   
   
    

   
    $fill=!$fill;
    $i++;
}
//Add a rectangle, a line, a logo and some text
/*
$pdf->Rect(5,5,170,80);
$pdf->Line(5,90,90,90);
//$pdf->Image('mouse.jpg',185,5,10,0,'JPG','http://www.dnocs.gov.br');
$pdf->SetFillColor(224,235);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(5,95);
$pdf->Cell(170,5,'PDF gerado via PHP acessando banco de dados - Por Ribamar FS',1,1,'L',1,'mailto:ribafs@dnocs.gov.br');
*/
ob_end_clean();
$pdf->Output();
$pdf->Close();
// generamos los meses 
function genMonth_Text($m) { 
 switch ($m) { 
  case '01': $month_text = "Enero"; break; 
  case '02': $month_text = "Febrero"; break; 
  case '03': $month_text = "Marzo"; break; 
  case '04': $month_text = "Abril"; break; 
  case '05': $month_text = "Mayo"; break; 
  case '06': $month_text = "Junio"; break; 
  case '07': $month_text = "Julio"; break; 
  case '08': $month_text = "Agosto"; break; 
  case '09': $month_text = "Septiembre"; break; 
  case '10': $month_text = "Octubre"; break; 
  case '11': $month_text = "Noviembre"; break; 
  case '12': $month_text = "Diciembre"; break; 
 } 
 return ($month_text); 
} 
?>
