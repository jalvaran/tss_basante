<?php

require_once('tcpdf_include.php');
include("conexion.php");
////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////
session_start();
if(!isset($_SESSION["username"]))
   header("Location: index.html");

////////////////////////////////////////////
/////////////Obtengo el ID de la Factura a que se imprimirá 
////////////////////////////////////////////

$idOrden = $_POST["ImgPrintOrden"];

////////////////////////////////////////////
/////////////Me conecto a la db
////////////////////////////////////////////

$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");
////////////////////////////////////////////
/////////////Obtengo datos de la orden de salida
////////////////////////////////////////////
		  $sel1=mysql_query("SELECT * FROM ordenessalida WHERE idOrdenesSalida=$idOrden",$con) or die("problemas con la consulta a ordenes de salida");
		  $DatosOrden=mysql_fetch_array($sel1);	
		  //$IDCoti=$DatosFactura["Cotizaciones_idCotizaciones"];
		  
		  
		  $nombre_file=$DatosOrden[1]."_OrdenSalida_";
		   
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
$pdf->SetTitle('Orden Salida TS');
$pdf->SetSubject('Orden de Salida');
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

//$pdf->SetFont('helvetica', '', 6);

///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<hr id="Line1" style="margin:0;padding:0;position:absolute;left:0px;top:44px;width:625px;height:2px;z-index:1;">
<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Guadalajara de Buga $DatosOrden[1]</span></div>

<div id="wb_Text1" style="position:absolute;left:380px;top:72px;width:150px;height:16px;text-align:rigth;z-index:2;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:13px;"><strong><em>ORDEN DE SALIDA No. $idOrden
</em></strong></span></div>

<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Hora: $DatosOrden[2] </em></strong></span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Articulo: $DatosOrden[3] </em></strong></span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Valor Estimado: $DatosOrden[4] </em></strong></span></div>

<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Observaciones: $DatosOrden[5] </em></strong></span></div>

<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Destino: $DatosOrden[6] </em></strong></span></div>

<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Retirado por: $DatosOrden[7]</em></strong></span></div>

<div id="wb_Text4" style="position:absolute;left:41px;top:110px;width:368px;height:18px;z-index:6;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Se transporta en: $DatosOrden[8]</em></strong></span></div>

<div id="wb_Text6" style="position:absolute;left:35px;top:230px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Aprobado por:</em></strong></span><span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"> &nbsp;&nbsp; &nbsp; &nbsp; _____________________________________ </span></div>

<div id="wb_Text6" style="position:absolute;left:35px;top:250px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Firma de quien retira:</em></strong></span><span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"> _____________________________________</span></div>

<div id="wb_Text6" style="position:absolute;left:35px;top:250px;width:242px;height:18px;z-index:8;text-align:left;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:13px;"><strong><em>No. de Identificacion:</em></strong></span><span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"> _______________</span></div>

<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">
</div>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');



//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>