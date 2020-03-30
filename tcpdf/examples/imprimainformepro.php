<?php

require_once('tcpdf_include.php');
include("inc/jpgraph.php");
include("inc/jpgraph_bar.php");
include("inc/jpgraph_pie.php");
require_once ('inc/jpgraph_pie3d.php');
include("conexion.php");
include('inc/jpgraph_radar.php');
////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////
session_start();
if(!isset($_SESSION["username"]))
   header("Location: index.html");
   
   
////////////////////////////////////////////
/////////////Obtengo el rango de fechas
////////////////////////////////////////////

$fecha=date("Y-m-d");
$FechaIni = substr("$_POST[TxtFechaIniPro]", 6, 7)."-".substr("$_POST[TxtFechaIniPro]", 3, 2)."-".substr("$_POST[TxtFechaIniPro]", 0, 2);
$FechaFinal = substr("$_POST[TxtFechaFinalPro]", 6, 7)."-".substr("$_POST[TxtFechaFinalPro]", 3, 2)."-".substr("$_POST[TxtFechaFinalPro]", 0, 2);

   
////////////////////////////////////////////
/////////////Me conecto a la db
////////////////////////////////////////////

$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");


////////////////////////////////////////////
/////////////Obtengo valores de egresos si es un reporte General
////////////////////////////////////////////  
   
   
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Julian Alvaran');
$pdf->SetTitle('Informe de Producciónn');
$pdf->SetSubject('Informe');
$pdf->SetKeywords('Servi Torno, Techno');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'INFORME MENSUAL DE PRODUCCION '.$fecha, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

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
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

////////////////////////////////////////////////////////
///////////////Leyenda y produccion total///////////////
////////////////////////////////////////////////////////



$html = '<span style="text-align:justify;">
En el presente informe usted encontrará la información de la producción del Taller Industrial Servitorno correspondiente al periodo comprendido entre el '.$FechaIni.' y '.$FechaFinal.'</span><br>

<h3>PRODUCCION POR TRABAJOS</h3><br>';

// set core font
$pdf->SetFont('helvetica', '', 12);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

///////////////////////////////////////////////////////////////////////////////////////
//////////////Obtengo datos de los trabajos///////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

$sel1=mysql_query("SELECT PuntoEquilibrio FROM empresapro",$con) or die("problemas con la consulta a Registro de trabajos");
$TotalTrabajos=mysql_fetch_array($sel1);
$PuntoEquilibrio=$TotalTrabajos["PuntoEquilibrio"];


$sel1=mysql_query("SELECT SUM(Valor) as Valor FROM trabajos WHERE Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' ",$con) or die("problemas con la consulta a Registro de trabajos");
$TotalTrabajos=mysql_fetch_array($sel1);
$TotalTrabajos=$TotalTrabajos["Valor"];

$datetime1 = date_create($FechaIni);
$datetime2 = date_create($FechaFinal);
$interval = date_diff($datetime1, $datetime2);
$interval = $interval->format('%R%a');

$FechaIni2 = date_create($FechaIni);
$FechaFinal2 =  date_create($FechaFinal);


date_sub($FechaIni2, date_interval_create_from_date_string(($interval+1).' days'));
$FechaIni2= date_format($FechaIni2, 'Y-m-d');

date_sub($FechaFinal2, date_interval_create_from_date_string(($interval+1).' days'));
$FechaFinal2= date_format($FechaFinal2, 'Y-m-d');

$sel1=mysql_query("SELECT SUM(Valor) as Valor FROM trabajos WHERE Fecha >= '$FechaIni2' AND Fecha <= '$FechaFinal2' ",$con) or die("problemas con la consulta a Registro de trabajos");
$TotalTrabajos2=mysql_fetch_array($sel1);
$TotalTrabajos2=$TotalTrabajos2["Valor"];

$DiferenciaTra=$TotalTrabajos-$TotalTrabajos2;
$DifEqui=$TotalTrabajos-$PuntoEquilibrio;
$DifEqui2=$TotalTrabajos2-$PuntoEquilibrio;

////////////////////////////////////////////////////////
///////////////Graficas en barras de trabajos comparacion////
////////////////////////////////////////////////////////
$Punto=($PuntoEquilibrio/30)*$interval;

$datay1=array($Punto);
$datay2=array($TotalTrabajos2);
$datay3=array($TotalTrabajos);
 
$graph = new Graph(450,300,'auto');    
$graph->SetScale("textlin");

$graph->SetShadow();
$graph->img->SetMargin(80,30,40,40);

//$graph->xaxis->title->Set("Periodo de tiempo");
//$graph->yaxis->title->Set("Valor");
 
$graph->title->Set('Group bar plot');
$graph->title->SetFont(FF_FONT1,FS_BOLD);
 
$bplot1 = new BarPlot($datay1);

$bplot2 = new BarPlot($datay2);
$bplot3 = new BarPlot($datay3);

 
$bplot1->SetFillColor("orange");
$bplot1->SetFillGradient("lightsteelblue","navy",GRAD_VER);
$bplot1->SetLegend('de '.$FechaIni2.' a '.$FechaFinal2);
$bplot2->SetFillColor("orange");
$bplot2->SetFillGradient("lightsteelblue","navy",GRAD_VER);
$bplot2->SetLegend('de '.$FechaIni2.' a '.$FechaFinal2);
$bplot3->SetFillColor("brown");
$bplot3->SetFillGradient("brown","pink",GRAD_VER);
$bplot3->SetLegend('de '.$FechaIni.' a '.$FechaFinal);
//$bplot3->SetFillColor("darkgreen");
 
$bplot1->SetShadow();
$bplot2->SetShadow();
$bplot3->SetShadow();
 
$bplot1->SetShadow();
$bplot2->SetShadow();
$bplot3->SetShadow();
 
$gbarplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot2));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);
 
$graph->title->Set("Produccion por trabajos");
 
$contentType = 'image/png';
$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
 
// @see http://stackoverflow.com/a/9084110/126431
ob_start();                        // start buffering
$graph->img->Stream();             // print data to buffer
$graphData = ob_get_contents();   // retrieve buffer contents
ob_end_clean();                    // stop buffer
 
// outputting this in a HTML tag would work like this:
// $graphBase64 = "data:$contentType;base64," . base64_encode($graphData);
// echo sprintf('<img src="%s" alt="Graph">', $graphBase64);
 
$pdf->Image('@'.$graphData);

$TotalPro=$TotalTrabajos;
$TotalPro2=$TotalTrabajos2;
$TotalTrabajos=number_format($TotalTrabajos);
$TotalTrabajos2=number_format($TotalTrabajos2);
$DiferenciaTra=number_format($DiferenciaTra);
$PuntoEq=$PuntoEquilibrio;
$PuntoEquilibrio=number_format($PuntoEquilibrio);
$DifEqui2=number_format($DifEqui2);
$DifEqui=number_format($DifEqui);

$html = '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><span style="text-align:justify;">
El total de los trabajos en el periodo comprendido '.$FechaIni.' y '.$FechaFinal.' fue de $'.$TotalTrabajos.' donde se muestra una diferencia de $'.$DiferenciaTra.' 
en comparacion al periodo entre el '.$FechaIni2.' y '.$FechaFinal2.' donde el valor fue de $'.$TotalTrabajos2.'</span><br><br><br>
<span style="text-align:justify;">
La diferencia con relacion al punto de equilibrio de la empresa que está en: $'.$PuntoEquilibrio.'. en el periodo comprendido entre el '.$FechaIni.' y '.$FechaFinal.' fue de $'.$DifEqui.' 
Y en el periodo comprendido entre el '.$FechaIni.' y '.$FechaFinal.' fue de $'.$DifEqui2.'</span><br>
';


// set core font
$pdf->SetFont('helvetica', '', 12);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);


$pdf->AddPage();


////////////////////////////////////////////////////////
///////////////Leyenda y produccion por Maquinas///////////////
////////////////////////////////////////////////////////



$html = '<span style="text-align:justify;">

<h3>PRODUCCION POR MAQUINAS</h3><br>';

// set core font
$pdf->SetFont('helvetica', '', 12);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);


///////////////////////////////////////////////////////////////////////////////////////
//////////////Obtengo datos de los maquinas y escribo tabla de comparacion//////////////
//////////////////////////////////////////////////////////////////////////////////////


$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="80"><h3>Maquina</h3></td><td width="150"><h3>Produccion de $FechaIni2 a $FechaFinal2</h3></td><td width="80"><h3>%</h3></td>
  <td  width="150"><h3>Produccion de $FechaIni a $FechaFinal</h3></td><td  width="80"><h3>%</h3></td><td  width="100"><h3>Diferencia</h3></td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$idTabla="idMaquinas";
$Tabla="maquinas";
$NumMayor=mysql_query("SELECT MAX($idTabla) as maxnp FROM $Tabla") or die ("Error al consultar la tabla de maquinas");
		  $linea=mysql_fetch_array($NumMayor); 
		  $maxID=$linea["maxnp"];
		  
		  for ($i=0; $i<=$maxID; $i++) {

$sel1=mysql_query("SELECT SUM(Valor) as Valor FROM reltrabajosmaquinas WHERE Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' AND Maquinas_idMaquinas='$i'",$con) or die("problemas con la consulta a trabajos maquinas");
$TotalTrabajos=mysql_fetch_array($sel1);
$TotalMaquinas[$i]=$TotalTrabajos["Valor"];
if($TotalPro>=1)
	$PorcentajeMaq[$i]=round(((100/$TotalPro)*$TotalMaquinas[$i]),2);
else
	$PorcentajeMaq[$i]=0;
$sel1=mysql_query("SELECT SUM(Valor) as Valor FROM reltrabajosmaquinas WHERE Fecha >= '$FechaIni2' AND Fecha <= '$FechaFinal2' AND Maquinas_idMaquinas='$i'",$con) or die("problemas con la consulta a trabajos maquinas");
$TotalTrabajos2=mysql_fetch_array($sel1);
$TotalMaquinas2[$i]=$TotalTrabajos2["Valor"];
if($TotalPro2>=1)
$PorcentajeMaq2[$i]=round(((100/$TotalPro2)*$TotalMaquinas2[$i]),2);
else
$PorcentajeMaq2[$i]=0;
$DiferenciaTraMaq[$i]=$TotalMaquinas[$i]-$TotalMaquinas2[$i];

$sel1=mysql_query("SELECT Nombre as Nombre FROM maquinas WHERE idMaquinas='$i'",$con) or die("problemas con la consulta a trabajos maquinas");
$TotalTrabajos2=mysql_fetch_array($sel1);
$NombresMaq[$i]=$TotalTrabajos2["Nombre"];

$TotalM=number_format($TotalMaquinas[$i]);
$TotalM2=number_format($TotalMaquinas2[$i]);
$Dif=number_format(($TotalMaquinas[$i]-$TotalMaquinas2[$i]));

$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="80">$NombresMaq[$i]</td><td width="150">$TotalM2</td><td width="80">$PorcentajeMaq2[$i]</td>
  <td  width="150">$TotalM</td><td  width="80">$PorcentajeMaq[$i]</td><td  width="100">$Dif</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

}

$TotalTrabajos=number_format($TotalPro);
$TotalTrabajos2=number_format($TotalPro2);

$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="80" ><h3>TOTAL</h3></td><td width="150">$TotalTrabajos2</td>
  <td  width="80"><h3>TOTAL</h3></td><td  width="150">$TotalTrabajos</td>
 </tr>
 </table>
 <br><br><br>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


////////////////////////////////////////////////////////
///////////////Graficas en barras de maquinas///////////
////////////////////////////////////////////////////////

$TotalMaquinas2[0]=($PuntoEq/30)*$interval;
$NombresMaq[0]="Equ";
//$TotalMaquinas[0]=$PuntoEq;
$datay1=$TotalMaquinas2;
$datay2=$TotalMaquinas;
 
$graph = new Graph(600,300,'auto');    
$graph->SetScale("textlin");

$graph->SetShadow();
$graph->img->SetMargin(80,30,40,40);
$graph->xaxis->SetTickLabels($NombresMaq);
//$graph->xaxis->title->Set("Periodo de tiempo");
//$graph->yaxis->title->Set("Valor");
 
$graph->title->Set('Group bar plot');
$graph->title->SetFont(FF_FONT1,FS_BOLD);
 
$bplot1 = new BarPlot($datay1);

$bplot2 = new BarPlot($datay2);

 
$bplot1->SetFillColor("orange");
$bplot1->SetFillGradient("lightsteelblue","navy",GRAD_VER);
$bplot1->SetLegend('de '.$FechaIni2.' a '.$FechaFinal2);
$bplot2->SetFillColor("brown");
$bplot2->SetFillGradient("brown","pink",GRAD_VER);
$bplot2->SetLegend('de '.$FechaIni.' a '.$FechaFinal);
//$bplot3->SetFillColor("darkgreen");
 
$bplot1->SetShadow();
$bplot2->SetShadow();
//$bplot3->SetShadow();
 
$bplot1->SetShadow();
$bplot2->SetShadow();
//$bplot3->SetShadow();
 
$gbarplot = new GroupBarPlot(array($bplot1,$bplot2));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);
 
$graph->title->Set("Produccion por Maquinas");
 
$contentType = 'image/png';
$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
 
// @see http://stackoverflow.com/a/9084110/126431
ob_start();                        // start buffering
$graph->img->Stream();             // print data to buffer
$graphData = ob_get_contents();   // retrieve buffer contents
ob_end_clean();                    // stop buffer
 
// outputting this in a HTML tag would work like this:
// $graphBase64 = "data:$contentType;base64," . base64_encode($graphData);
// echo sprintf('<img src="%s" alt="Graph">', $graphBase64);
 
$pdf->Image('@'.$graphData);


$pdf->AddPage();

////////////////////////////////////////////////////////
///////////////Leyenda y produccion por Colaborador///////////////
////////////////////////////////////////////////////////



$html = '<span style="text-align:justify;">

<h3>PRODUCCION POR COLABORADOR</h3><br>';

// set core font
$pdf->SetFont('helvetica', '', 12);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);


///////////////////////////////////////////////////////////////////////////////////////
//////////////Obtengo datos de los colaboradores y escribo tabla de comparacion//////////////
//////////////////////////////////////////////////////////////////////////////////////


$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="20"><h3>ID</h3></td><td width="80"><h3>Nombre</h3></td><td width="150"><h3>Produccion de $FechaIni2 a $FechaFinal2</h3></td><td width="80"><h3>%</h3></td>
  <td  width="150"><h3>Produccion de $FechaIni a $FechaFinal</h3></td><td  width="80"><h3>%</h3></td><td  width="100"><h3>Diferencia</h3></td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

$idTabla="idColaboradores";
$Tabla="colaboradoresact";
$NumMayor=mysql_query("SELECT MAX($idTabla) as maxnp FROM $Tabla") or die ("Error al consultar la tabla de colaboradores");
		  $linea=mysql_fetch_array($NumMayor); 
		  $maxID=$linea["maxnp"];
		  
		  for ($i=0; $i<=$maxID; $i++) {

$sel1=mysql_query("SELECT SUM(Valor) as Valor FROM reltrabajos WHERE Fecha >= '$FechaIni' AND Fecha <= '$FechaFinal' AND Colaboradoresact_idColaboradoresact='$i'",$con) or die("problemas con la consulta a trabajos colaboradores");
$TotalTrabajos=mysql_fetch_array($sel1);
$TotalCol[$i]=$TotalTrabajos["Valor"];
if($TotalPro>=1)
$PorcentajeCol[$i]=round(((100/$TotalPro)*$TotalCol[$i]),2);
else
$PorcentajeCol[$i]=0;
$sel1=mysql_query("SELECT SUM(Valor) as Valor FROM reltrabajos WHERE Fecha >= '$FechaIni2' AND Fecha <= '$FechaFinal2' AND Colaboradoresact_idColaboradoresact='$i'",$con) or die("problemas con la consulta a trabajos maquinas");
$TotalTrabajos2=mysql_fetch_array($sel1);
$TotalCol2[$i]=$TotalTrabajos2["Valor"];
if($TotalPro2>=1)
$PorcentajeCol2[$i]=round(((100/$TotalPro2)*$TotalCol2[$i]),2);
else
$PorcentajeCol2[$i]=0;
$DiferenciaTraCol[$i]=$PorcentajeCol[$i]-$PorcentajeCol2[$i];

$sel1=mysql_query("SELECT Nombre, Apellido FROM colaboradoresact WHERE $idTabla='$i'",$con) or die("problemas con la consulta a colaboradores");
$TotalTrabajos2=mysql_fetch_array($sel1);
$NombresCol[$i]=$TotalTrabajos2["Nombre"]." ".$TotalTrabajos2["Apellido"] ;
$NombresLabels[$i]=$i;

$TotalM=number_format($TotalCol[$i]);
$TotalM2=number_format($TotalCol2[$i]);
$Dif=number_format(($TotalCol[$i]-$TotalCol2[$i]));

$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="20">$i</td><td width="80">$NombresCol[$i]</td><td width="150">$TotalM2</td><td width="80">$PorcentajeCol2[$i]</td>
  <td  width="150">$TotalM</td><td  width="80">$PorcentajeCol[$i]</td><td  width="100">$Dif</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');

}

$TotalTrabajos=number_format($TotalPro);
$TotalTrabajos2=number_format($TotalPro2);

$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="100" ><h3>TOTAL</h3></td><td width="150">$TotalTrabajos2</td>
  <td  width="80"><h3>TOTAL</h3></td><td  width="150">$TotalTrabajos</td>
 </tr>
 </table>
 <br><br><br>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');


////////////////////////////////////////////////////////
///////////////Graficas en barras de colaboradores///////////
////////////////////////////////////////////////////////

$TotalCol2[0]=($PuntoEq/30)*$interval;
$NombresLabels[0]="Equ";

$datay1=$TotalCol2;
$datay2=$TotalCol;
 
$graph = new Graph(600,300,'auto');    
$graph->SetScale("textlin");

$graph->SetShadow();
$graph->img->SetMargin(80,30,40,40);
$graph->xaxis->SetTickLabels($NombresLabels);
//$graph->xaxis->title->Set("Periodo de tiempo");
//$graph->yaxis->title->Set("Valor");
 
$graph->title->Set('Group bar plot');
$graph->title->SetFont(FF_FONT1,FS_BOLD);
 
$bplot1 = new BarPlot($datay1);

$bplot2 = new BarPlot($datay2);

 
$bplot1->SetFillColor("orange");
$bplot1->SetFillGradient("lightsteelblue","navy",GRAD_VER);
$bplot1->SetLegend('de '.$FechaIni2.' a '.$FechaFinal2);
$bplot2->SetFillColor("brown");
$bplot2->SetFillGradient("brown","pink",GRAD_VER);
$bplot2->SetLegend('de '.$FechaIni.' a '.$FechaFinal);
//$bplot3->SetFillColor("darkgreen");
 
$bplot1->SetShadow();
$bplot2->SetShadow();
//$bplot3->SetShadow();
 
$bplot1->SetShadow();
$bplot2->SetShadow();
//$bplot3->SetShadow();
 
$gbarplot = new GroupBarPlot(array($bplot1,$bplot2));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);
 
$graph->title->Set("Produccion por Colaborador");
 
$contentType = 'image/png';
$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
 
// @see http://stackoverflow.com/a/9084110/126431
ob_start();                        // start buffering
$graph->img->Stream();             // print data to buffer
$graphData = ob_get_contents();   // retrieve buffer contents
ob_end_clean();                    // stop buffer
 
// outputting this in a HTML tag would work like this:
// $graphBase64 = "data:$contentType;base64," . base64_encode($graphData);
// echo sprintf('<img src="%s" alt="Graph">', $graphBase64);
 
$pdf->Image('@'.$graphData);


/////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////


$pdf->AddPage();

////////////////////////////////////////////////////////
///////////////Graficas en torta para maquinas/////////////////////////////////
////////////////////////////////////////////////////////

$data = $TotalMaquinas;
$leyenda = $NombresMaq;

// Create the Pie Graph. 
$graph = new PieGraph(600,700);
//$graph->img->SetMargin(100,50,60,60);
$theme_class= new VividTheme;
$graph->SetTheme($theme_class);

// Set A title for the plot
$graph->title->Set("A Simple 3D Pie Plot");

// Create
$p1 = new PiePlot3D($data);
$p1->SetLegends($leyenda);
$p1->SetLabelMargin(10);
$graph->Add($p1);

$p1->ShowBorder();
$p1->SetColor('black');
$p1->ExplodeSlice(1);
 
$graph->title->Set("Produccion por maquinas de $FechaIni a $FechaFinal");
 
$contentType = 'image/png';
$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
 
// @see http://stackoverflow.com/a/9084110/126431
ob_start();                        // start buffering
$graph->img->Stream();             // print data to buffer
$graphData = ob_get_contents();   // retrieve buffer contents
ob_end_clean();                    // stop buffer
 
// outputting this in a HTML tag would work like this:
// $graphBase64 = "data:$contentType;base64," . base64_encode($graphData);
// echo sprintf('<img src="%s" alt="Graph">', $graphBase64);
 
$pdf->Image('@'.$graphData);



$pdf->AddPage();

////////////////////////////////////////////////////////
///////////////Graficas en torta para colaboradores/////////////////////////////////
////////////////////////////////////////////////////////

$data = $TotalCol;
$leyenda = $NombresCol;

// Create the Pie Graph. 
$graph = new PieGraph(650,700);
//$graph->img->SetMargin(100,50,60,60);
$theme_class= new VividTheme;
$graph->SetTheme($theme_class);

// Set A title for the plot
$graph->title->Set("A Simple 3D Pie Plot");

// Create
$p1 = new PiePlot3D($data);
$p1->SetLegends($leyenda);
$p1->SetLabelMargin(10);
$graph->Add($p1);

$p1->ShowBorder();
$p1->SetColor('black');
$p1->ExplodeSlice(1);
 
$graph->title->Set("Produccion por colaborador de $FechaIni a $FechaFinal");
 
$contentType = 'image/png';
$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
 
// @see http://stackoverflow.com/a/9084110/126431
ob_start();                        // start buffering
$graph->img->Stream();             // print data to buffer
$graphData = ob_get_contents();   // retrieve buffer contents
ob_end_clean();                    // stop buffer
 
// outputting this in a HTML tag would work like this:
// $graphBase64 = "data:$contentType;base64," . base64_encode($graphData);
// echo sprintf('<img src="%s" alt="Graph">', $graphBase64);
 
$pdf->Image('@'.$graphData);

////////////////////////////////////////////////////////
///////////////IMPRIME DETALLES///////////////////////////
////////////////////////////////////////////////////////



if($_POST["OptCols"]=="si"){




///////////////////////////////////////////////////////////////////////////////////////
//////////////Obtengo datos de los colaboradores y escribo tabla de comparacion//////////////
//////////////////////////////////////////////////////////////////////////////////////

$idTabla="idColaboradores";
$Tabla="colaboradoresact";
$NumMayor=mysql_query("SELECT MAX($idTabla) as maxnp FROM $Tabla") or die ("Error al consultar la tabla de colaboradores");
		  $linea=mysql_fetch_array($NumMayor); 
		  $maxID=$linea["maxnp"];
		  
		  for ($i=1; $i<=$maxID; $i++) {
		  
		  $NumMayor=mysql_query("SELECT Nombre, Apellido FROM colaboradoresact WHERE idColaboradores='$i'") or die ("Error al consultar la tabla de colaboradores");
		  $linea=mysql_fetch_array($NumMayor); 
		  $Nombre=$linea["Nombre"]." ".$linea["Apellido"];
		  
		  $pdf->AddPage();

$tbl = <<<EOD
TRABAJOS REALIZADOS POR: $Nombre ENTRE $FechaIni Y $FechaFinal<Br><Br><Br> 

EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');



		   $sql = " SELECT * FROM trabajos trab INNER JOIN reltrabajos relt ON relt.Trabajos_idTrabajos=trab.idTrabajos 
		   WHERE relt.Fecha >= '$FechaIni' AND relt.Fecha <= '$FechaFinal' AND relt.Colaboradoresact_idColaboradoresact='$i'";
                        	   
			//Ejecutamos el codigo Mysql
			$r = mysql_query($sql,$con) or die("La consulta a nuestra base de datos es erronea.".mysql_error());
						
						
                        
                        if(mysql_num_rows($r)){//Si existen resultados
$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="90" ><h3>IDTrabajo</h3></td><td width="180"><h3>Descripcion</h3></td>
  <td  width="80"><h3>Valor</h3></td><td  width="80"><h3>Fecha</h3></td><td  width="50"><h3>Hora Ini</h3></td><td  width="50"><h3>Hora Fin</h3></td>
  <td  width="90"><h3>IDCliente</h3></td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
                                   while($registros=mysql_fetch_array($r)){
$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
 
  <td width="90" >$registros[0]</td><td width="180">$registros[1]</td>
  <td  width="80">$registros[2]</td><td  width="80">$registros[3]</td><td  width="50">$registros[4]</td><td  width="50">$registros[5]</td><td  width="90">$registros[6]</td>
 </tr>
 </table>
 
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');
//$pdf->AddPage();
}
}

}

}

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output("produccion $fecha.pdf", "I");

//============================================================+
// END OF FILE
//============================================================+
?>