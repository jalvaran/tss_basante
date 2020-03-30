<?php

require_once('tcpdf_include.php');
include("conexion.php");
////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////
session_start();
if(!isset($_SESSION["username"]))
   header("Location: index.php");

////////////////////////////////////////////
/////////////Obtengo el ID de la cotizacion a que se imprimirá 
////////////////////////////////////////////

$NumOrden = $_REQUEST["TxtNumOrden"];

////////////////////////////////////////////
/////////////Me conecto a la db
////////////////////////////////////////////

$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");
////////////////////////////////////////////
/////////////Obtengo valores de la cotizacion
////////////////////////////////////////////
			
		
		 $sel1=mysql_query("SELECT * FROM act_ordenes WHERE NumOrden='$NumOrden'",$con) or die("problemas con la consulta a act_ordenes ".mysql_error());
		 $DatosOrden=mysql_fetch_array($sel1);
		  
		  
////////////////////////////////////////////
/////////////Obtengo datos del cliente
////////////////////////////////////////////

		  		  
		 
		  
		  $nombre_file="$NumOrden OESA";
		   
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
$pdf->SetTitle('Orden Salida o Entrada Activos TS');
$pdf->SetSubject('Activos');
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
//$pdf->SetFont('helvetica', 'B', 6);

// add a page
$pdf->AddPage();



$pdf->SetFont('helvetica', '', 8);

///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD

<hr id="Line1" style="margin:0;padding:0;position:absolute;left:0px;top:44px;width:625px;height:2px;z-index:1;">
<div id="wb_Text1" style="position:absolute;left:380px;top:72px;width:150px;height:10px;text-align:center;z-index:2;">

</em></strong></span><br><span style="color:#00008B;font-family:'Bookman Old Style';font-size:14px;"><em>ORDEN DE INGRESO O SALIDA DE ACTIVOS
</em><br></span>

</div>

<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Fecha: $DatosOrden[Fecha]</span></div>
<div id="wb_Text1" style="position:absolute;left:380px;top:72px;width:150px;height:16px;text-align:rigth;z-index:2;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Numero de Orden: $DatosOrden[NumOrden]
</em></strong></span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Entrega: </em></strong>$DatosOrden[Entrega]</span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Recibe:</em></strong> $DatosOrden[Recibe]</span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Origen:</em></strong> $DatosOrden[Origen]</span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Destino:</em></strong> $DatosOrden[Destino]</span></div>

<div id="container">
<div id="wb_Text12" style="position:absolute;left:34px;top:184px;width:579px;height:16px;z-index:0;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>La persona que recibe se hece totalmente responsable de los siguientes elementos:</em></strong></span></div>

<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">
</div>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


// NON-BREAKING ROWS (nobr="true")

$tbl = <<<EOD


<table border="1" cellpadding="2" cellspacing="2" align="center">
  

 <tr nobr="true">
  <th><h3>Ref</h3></th>
  <th><h3>Descripción</h3></th>
  <th ><h3>Marca</h3></th>
  <th><h3>Serie</h3></th>
  <th><h3>Estado</h3></th>
  <th><h3>Motivo</h3></th>
  <th><h3>Observacion</h3></th>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

////////////////////////////////////////////////////////
 
		  
		
	
			$sel1=mysql_query("SELECT * FROM act_movimientos am INNER JOIN activos act ON am.idActivo=act.idActivos WHERE am.NumOrden='$NumOrden'",$con)
			or die("problemas con la consulta a activos movi  ".mysql_error());
			while($DatosMovimiento=mysql_fetch_array($sel1)){
		  	
		    		
$tbl = <<<EOD

<table border="1" cellpadding="2" cellspacing="2" align="center">
 <tr nobr="true">
  <td>$DatosMovimiento[Referencia]</td>
  <td>$DatosMovimiento[NombreAct]</td>
  <td>$DatosMovimiento[Marca]</td>
  <td>$DatosMovimiento[Serie]</td>
  <td>$DatosMovimiento[Estado]</td>
  <td>$DatosMovimiento[MotivoMovimiento]</td>
  <td>$DatosMovimiento[Observaciones]</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
			
	  
}

		
$tbl = <<<EOD
<br>
 <br>
 <br>
<div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:10px;"><strong><em>Recibe: _________________________________ <br><br><br>
Entrega: ________________________________   
</em></strong></span></div>

EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');		

//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>