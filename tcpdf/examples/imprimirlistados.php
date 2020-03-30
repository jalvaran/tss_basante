<?php

require_once('tcpdf_include.php');
include("conexion.php");
////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////
session_start();
if(!isset($_SESSION["username"]))
   header("Location: index.html");
   
   $Tabla=$_SESSION["Listado"];


////////////////////////////////////////////
/////////////Me conecto a la db
////////////////////////////////////////////

$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");
////////////////////////////////////////////
/////////////Obtengo datos de la tabla
////////////////////////////////////////////
		 
		  $nombre_file="Listado ".$Tabla;
		   
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	//Page header
	public function Header() {
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// set bacground image
		$img_file = K_PATH_IMAGES.'tsfondo.jpg';
		$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Julian Andres Alvaran Valencia');
$pdf->SetTitle('Cotizacion TS');
$pdf->SetSubject('Cotizacion');
$pdf->SetKeywords('Techno Soluciones, PDF, cotizacion, CCTV, Alarmas, Computadores');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$pdf->SetFont('helvetica', 'B', 16);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Taller industrial Servi Torno tiene el agrado de cotizarle los siguientes servicios:', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 6);

///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD



<div id="wb_Text20" style="position:absolute;left:380px;top:72px;width:150px;height:16px;text-align:center;z-index:2;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:10px;"><strong><em>CENTRO DE MECANIZADO, TORNO, FRESADORA, RECTIFICADORA DE SUPERFICIES PLANAS, RECTIFICADORA DE EJES, CEPILLO, PRENSA HIDRÁULICA, 
SOLDADURAS: ELÉCTRICA Y AUTÓGENA; REPARACIÓN Y FABRICACIÓN DE PIEZAS <br> AGRO-INDRUSTRIALES
</em></strong></span></div>

<hr id="Line1" style="margin:0;padding:0;position:absolute;left:0px;top:44px;width:625px;height:2px;z-index:1;">
<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Listado de $Tabla</span></div>


<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">
</div>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


// NON-BREAKING ROWS (nobr="true")

$tbl = <<<EOD


<table border="1"  cellpadding="1" cellspacing="1" align="center" style="border-left: 3px solid #000099;
		border-right: 3px solid #000099;
		border-top: 3px solid #000099;
		border-bottom: 3px solid #000099;">
  

 <tr>
  <th><h3>Identificador</h3></th><th><h3>Razón Social</h3></th>
  <th><h3>NIT</h3></th><th><h3>Dirección</h3></th><th><h3>Teléfono</h3></th><th><h3>Ciudad</h3></th><th><h3>Contacto</h3></th><th><h3>Teléfono del Contacto</h3></th><th><h3>Email</h3></th>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

////////////////////////////////////////////////////////

			if($Tabla=="clientes")
		  	$idTabla="idClientes";
		  if($Tabla=="proveedores")
		  	$idTabla="idProveedor";	
			
 $NumMayor=mysql_query("select min($idTabla) as minnp from $Tabla");
		  $linea=mysql_fetch_array($NumMayor); 
		  $minID=$linea["minnp"];
		  	
		 $NumMayor=mysql_query("select max($idTabla) as maxnp from $Tabla");
		  $linea=mysql_fetch_array($NumMayor); 
		  $maxID=$linea["maxnp"];
		  
		  for ($i = $minID ; $i <= $maxID; $i++) {
	
			$sel1=mysql_query("SELECT * FROM $Tabla WHERE $idTabla=$i",$con) or die("problemas con la consulta a $Tabla");
		  	$registros2=mysql_fetch_array($sel1);
			
				
$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 3px solid #000099;
		border-right: 3px solid #000099;
		border-top: 3px solid #000099;
		border-bottom: 3px solid #000099;">
 <tr>
  <td>$registros2[0]</td><td>$registros2[1]</td><td>$registros2[2]</td><td>$registros2[3]</td><td>$registros2[4]</td><td>$registros2[5]</td>
  <td>$registros2[6]</td><td>$registros2[7]</td><td>$registros2[8]</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
		  
}


//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>