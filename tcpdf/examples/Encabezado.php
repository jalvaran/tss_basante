<?php
require_once('tcpdf_include.php');
//require_once('../../librerias/numerosletras.php');

////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////
session_start();
if(!isset($_SESSION["username"]))
   header("Location: ../../index.php");
$obVenta=new conexion(1);

$DatosFormato=$obVenta->DevuelveValores("formatos_calidad","ID",$idFormatoCalidad);
$TituloFormato=$DatosFormato["Nombre"];
$VersionFormato=$DatosFormato["Version"];
$CodigoFormato=$DatosFormato["Codigo"];
$FechaFormato="";
$PiePagina=$DatosFormato["NotasPiePagina"];
if(isset($DatosCentroCostos["EmpresaPro"])){
    $idEmpresaPro=$DatosCentroCostos["EmpresaPro"];
}else{
    $idEmpresaPro=1;
}		  
$DatosEmpresaPro=$obVenta->DevuelveValores("empresapro","idEmpresaPro",$idEmpresaPro);
$RazonSocialEP=$DatosEmpresaPro["RazonSocial"];
$DireccionEP=$DatosEmpresaPro["Direccion"];
$TelefonoEP=$DatosEmpresaPro["Celular"];
$CiudadEP=$DatosEmpresaPro["Ciudad"];
$NITEP=$DatosEmpresaPro["NIT"];


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Techno Soluciones');
$pdf->SetTitle($TituloFormato);
$pdf->SetSubject($TituloFormato);
$pdf->SetKeywords('Techno Soluciones, PDF, '.$TituloFormato.' , CCTV, Alarmas, Computadores, Software');
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, 60, PDF_HEADER_TITLE.'', "");

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(10);
$pdf->GetY();
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
	require_once(dirname(__FILE__).'/lang/spa.php');
	$pdf->setLanguageArray($l);
}
// ---------------------------------------------------------
// set font
//$pdf->SetFont('helvetica', 'B', 6);
// add a page
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8);

$RutaLogo="../../$DatosEmpresaPro[RutaImagen]";
///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////
//////
//////
$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr border="1">
        <td rowspan="3" border="1" style="text-align: center;"><img src="$RutaLogo" style="width:110px;height:60px;"></td>
        
        <td rowspan="3" width="270px" style="text-align: center; vertical-align: center;"><h2><br>$TituloFormato</h2></td>
        <td width="70px" style="text-align: center;">Versión<br></td>
        <td width="130px"> $VersionFormato</td>
    </tr>
    <tr>
    	
    	<td style="text-align: center;" >Código<br></td>
        <td> $CodigoFormato</td>
        
    </tr>
    <tr>
       <td style="text-align: center;" >Fecha<br></td>
       <td> $FechaFormato</td> 
    </tr>

</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');


$pdf->SetFillColor(255, 255, 255);

$txt="<h3>".$DatosEmpresaPro["RazonSocial"]."<br>NIT ".$DatosEmpresaPro["NIT"]."</h3>";
$pdf->MultiCell(60, 5, $txt, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
$txt=$DatosEmpresaPro["Direccion"]."<br>".$DatosEmpresaPro["Telefono"]."<br>".$DatosEmpresaPro["Ciudad"]."<br>".$DatosEmpresaPro["WEB"];
$pdf->MultiCell(60, 5, $txt, 0, 'C', 1, 0, '', '', true,0, true, true, 10, 'M');

$Documento.="<br><h5>Impreso por SOFTCONTECH, Techno Soluciones SAS <BR>NIT 900.833.180 3177740609</h5><br>";
$pdf->MultiCell(60, 5, $Documento, 0, 'R', 1, 0, '', '', true,0, true ,true, 10, 'M');
$pdf->writeHTML("<br>", true, false, false, false, '');

?>