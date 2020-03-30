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
/////////////Obtengo el ID de la cotizacion a que se imprimirá 
////////////////////////////////////////////

$IDCoti = $_POST["ImgPrintCoti"];

////////////////////////////////////////////
/////////////Me conecto a la db
////////////////////////////////////////////

$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");
////////////////////////////////////////////
/////////////Obtengo valores de la cotizacion
////////////////////////////////////////////
			
		  $sel1=mysql_query("SELECT SUM(Subtotal) as Subtotalcoti, SUM(IVA) as IVACoti, SUM(Total) as TotalCoti FROM cotizaciones WHERE NumCotizacion=$IDCoti",$con) or die("problemas con la consulta");
		  $costos=mysql_fetch_array($sel1);	
		  $SubTotal=number_format($costos["Subtotalcoti"]);
		  $IVACoti=number_format(round($costos["IVACoti"]));
		  $Total=number_format(round($costos["TotalCoti"]));
		  $Total1=round($costos["Subtotalcoti"]);
		  
		$NumMayor=mysql_query("select min(idCotizaciones) as minnp from cotizaciones WHERE NumCotizacion=$IDCoti");
		  $linea=mysql_fetch_array($NumMayor); 
		  $minID=$linea["minnp"];
		  	
		 $NumMayor=mysql_query("select max(idCotizaciones) as maxnp from cotizaciones WHERE NumCotizacion=$IDCoti");
		  $linea=mysql_fetch_array($NumMayor); 
		  $id_arc=$linea["maxnp"];
		  		
		 $sel1=mysql_query("SELECT * FROM cotizaciones WHERE idCotizaciones=$id_arc",$con) or die("problemas con la consulta a cotizaciones");
		  $registros2=mysql_fetch_array($sel1);
		  $NumSolicitud=$registros2["NumSolicitud"];
		  $fecha=date("d/m/y");
		  $CuentaCobro=$IDCoti;
		  $Clientes_idClientes=$registros2["Clientes_idClientes"];
		  $Usuarios_idUsuarios=$registros2["Usuarios_idUsuarios"];
		  
////////////////////////////////////////////
/////////////Obtengo datos del cliente
////////////////////////////////////////////

		  		  
		  $sel1=mysql_query("SELECT * FROM clientes WHERE idClientes=$Clientes_idClientes",$con) or die("problemas con la consulta a clientes");
		  $registros2=mysql_fetch_array($sel1);
		  $nombre=$registros2["RazonSocial"];
		  $direccion=$registros2["Direccion"];
		  $telefono=$registros2["Telefono"];
		  $email=$registros2["Email"];
		  $ciudad=$registros2["Ciudad"];
		  $contacto=$registros2["Contacto"];
		  $TelContacto=$registros2["TelContacto"];
		  $nit=$registros2["Num_Identificacion"];
		  
	////////////////////////////////////////////
/////////////Obtengo datos del Usuario creador
////////////////////////////////////////////

		  		  
		  $sel1=mysql_query("SELECT * FROM usuarios WHERE idUsuarios=$Usuarios_idUsuarios",$con) or die("problemas con la consulta a Usuarios");
		  $registros2=mysql_fetch_array($sel1);
		  $nombreUsuario=$registros2["Nombre"];
		  $ApellidoUsuario=$registros2["Apellido"];
		    
		 
		  
		  $nombre_file=$fecha."_".$nombre;
		  
		  
		  


//////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////		 
		   
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
		//$img_file = K_PATH_IMAGES.'tsfondo.jpg';
		//$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
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

//$pdf->SetFont('helvetica', '', 6);

///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////


$tbl = <<<EOD


<hr id="Line1" style="margin:0;padding:0;position:absolute;left:0px;top:44px;width:625px;height:2px;z-index:1;">
<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">$ciudad $fecha</span></div>
<div id="wb_Text1" style="position:absolute;left:380px;top:72px;width:150px;height:16px;text-align:rigth;z-index:2;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:13px;"><strong><em>CUENTA DE COBRO
</em></strong></span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>Señores:</em></strong></span></div>
<div id="wb_Text2" style="position:absolute;left:37px;top:106px;width:150px;height:16px;z-index:4;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>$nombre</em></strong></span></div>
<div id="wb_Text4" style="position:absolute;left:41px;top:110px;width:368px;height:18px;z-index:6;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Atn. $contacto</span></div>
<div id="wb_Text3" style="position:absolute;left:326px;top:109px;width:150px;height:16px;z-index:5;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">$TelContacto</span></div>


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
  

 <tr nobr="true">
  <th style= "border: 2px solid #000099;" ><h3>Cantidad</h3></th><th style= "border: 2px solid #000099;" colspan="3"><h3>Descripción</h3></th>
  <th  style= "border: 2px solid #000099;"><h3>Valor Unitario</h3></th><th style= "border: 2px solid #000099;"><h3>SubTotal</h3></th>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

////////////////////////////////////////////////////////
 
		  
		  for ($i = $minID ; $i <= $id_arc; $i++) {
	
			$sel1=mysql_query("SELECT * FROM cotizaciones WHERE idCotizaciones=$i",$con) or die("problemas con la consulta");
		  	$registros2=mysql_fetch_array($sel1);
			$registros2["Total"]=number_format($registros2["Total"]);
			$registros2["Subtotal"]=number_format(round($registros2["Subtotal"]));	
			$registros2["ValorUnitario"]=number_format(round($registros2["ValorUnitario"]));	
$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 3px solid #000099;
		border-right: 3px solid #000099;
		border-top: 3px solid #000099;
		border-bottom: 3px solid #000099;">
 <tr nobr="true">
  <td style= "border: 2px solid #000099;" >$registros2[Cantidad]</td><td colspan="3" style= "border: 2px solid #000099;">$registros2[Descripcion]</td>
  <td style= "border: 2px solid #000099;">$$registros2[ValorUnitario]</td><td style= "border: 2px solid #000099;">$$registros2[Subtotal]</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
		  
}

		
		
$tbl = <<<EOD


 </br>
 <div id="wb_Text6" style="position:absolute;left:35px;top:150px;width:242px;height:18px;z-index:8;text-align:rigth;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:13px;"><strong><em>TOTAL: </em></strong></span>
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;"><strong><em>$$SubTotal</em></strong></span></br>
 

EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');		

//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>