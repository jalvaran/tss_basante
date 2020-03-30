<?php

require_once('tcpdf_include.php');
include("conexion.php");
////////////////////////////////////////////
/////////////Verifico que haya una sesion activa
////////////////////////////////////////////
session_start();
if(!isset($_SESSION["username"]))
   header("Location: index.html");
   
$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");
   
function horamilitar($HoraNor){
	
	$ampm=substr($HoraNor, 20);
	$horamil=substr($HoraNor, 11, 2);
	
	if ($ampm=="p.m." and $horamil<12)
		$horamil=$horamil+12;
		
	if ($ampm=="a.m." and $horamil==12)
		$horamil="00";
	$dec=(substr($HoraNor, 14, 2))/60;
	$dec=substr($dec,1,3);
	$horamil=$horamil.$dec;
	return($horamil);
	
}

function formatofecha($forma){
	
	$forma=substr($forma, 6,4)."-".substr($forma, 3,2)."-".substr($forma, 0,2);
	return($forma);
	
}

/////////////////////////////////////////////////////////////////////////
////Funcion para calcular las extras////////////////////////////////////
////////////////////////////////////////////////////////////////////////

function calculaextras($horaOut, $horaIn, $fechae, $z, $identificacion, $festivo){
	
	include("conexion.php");
	$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");
	
	$sel1=mysql_query("SELECT * FROM colaboradoresact WHERE Identificacion='$identificacion'",$con) or die("problemas con la consulta a colaboradores activos");
    $DatosEmp=mysql_fetch_array($sel1);
	$Nombre=$DatosEmp["Nombre"];
	$Apellido=$DatosEmp["Apellido"];
	$Salario=$DatosEmp["Salario"];
	
	
	$Tolerancia=0.2;
	$HoraIngreso=7.5 - $Tolerancia;       //hora de entrada normal   7:18 (Sale de multiplicar el decimal por 60 minutos 0.3 * 60 = 18)
	$HoraMedioDia=12 + $Tolerancia;    // hora de salida almuerzo normal   12:12
	$HoraInMedioDia=13.5 - $Tolerancia;   //hora de entrada almuerzo normal  13:18
	$HoraSalida=18 + $Tolerancia;		//hora de salida normal  18:12
	$HoraIngresoSab= 8 - $Tolerancia; 
	$HoraSalidaSab=11 + $Tolerancia; 
	$LimSupNoct=22;
	$LimInfNoct=6;
	$extrasnocturnas=0;
	$extrasdiurnas=0;
	$extrasnocturnasfest=0;
	$extrasdiurnafest=0;
	
//<br>
	
	$factorfestivo=1;
	$diasem=date('l', strtotime($fechae));
	//echo " dia de la semana: ".$diasem." ";
	
	if ($diasem=="Sunday")
		$factorfestivo=1.75;
	//echo " $factorfestivo ";	

if ($diasem <> "Sunday" && $diasem <> "Saturday" && $festivo<>"X"){
///////primera posibilidad que entre antes de la hora normal

	if ($horaIn < $HoraIngreso){
		if($horaIn<$LimInfNoct){   ///si entra antes de las 6 am
			$extrasnocturnas=$LimInfNoct-$horaIn;
			$extrasdiurnas=($HoraIngreso + $Tolerancia)-$LimInfNoct;
		}else{
			$extrasdiurnas=($HoraIngreso + $Tolerancia) - $horaIn;
		}
		
		
	}
	
	/////// 2da posibilidad que salga despues de medio dia pero antes de la hora de entrada normal al medio dia
	
	
	if (($horaOut > $HoraMedioDia) && ($horaOut<=$HoraInMedioDia)){
		$extrasdiurnas=$extrasdiurnas + ($horaOut-($HoraMedioDia-$Tolerancia));
	}
	
	/////// 3da posibilidad que entre en el rango de medio dia y hora de entrada medio dia
	
	if (($horaIn >= $HoraMedioDia) && ($horaIn<$HoraInMedioDia)){
		$extrasdiurnas=$extrasdiurnas + (($HoraInMedioDia+$Tolerancia)-$horaIn);
	}	
	
	/////// 4ta posibilidad que salga despues de las 9 horas diarias y que su hora de entrada haya sido antes o igual a la hora de ingreso
	
	if (($horaOut > ($HoraIngreso+9)) && ($horaIn<=($HoraIngreso+$Tolerancia))){
		$extrasdiurnas=$extrasdiurnas + (($HoraInMedioDia+$Tolerancia)-$horaIn);
	}
	
	/////// 5ta posibilidad que salga despues de la hora de salida y haya entrado despues de la hora del medio dia
	
	if (($horaOut > ($HoraSalida)) && ($horaIn>=($HoraMedioDia-$Tolerancia)) ){
		if($horaOut>$LimSupNoct){
			$extrasnocturnas=$horaOut-$LimSupNoct;
			$extrasdiurnas=($LimSupNoct - ($HoraSalida-$Tolerancia));
		}else{
			$extrasdiurnas=$horaOut-($HoraSalida-$Tolerancia);
		}
	}	
	
	/////// si es hora de almuerzo
	
	if (($horaIn >= ($HoraMedioDia-$Tolerancia-$Tolerancia)) && ($horaOut<=($HoraInMedioDia+$Tolerancia+$Tolerancia)) ){
		$extrasdiurnas=0;
	}	
	
	/*  Si se desea transformar en horas normales
	$dec=(substr($horaIn, 2));
	//$dec="0".$dec;
	$dec=round($dec*60);
	$horaIn=(substr($horaIn, 0, 2)).":".$dec;
	
	$dec=(substr($horaOut, 2));
	//$dec="0".$dec;
	$dec=round($dec*60);
	$horaOut=(substr($horaOut, 0, 2)).":".$dec;
	*/
	mysql_query("INSERT INTO registroextras (idEmpleado, Fecha, ExtrasDiurnas, ExtrasNocturnas, ExtrasDiurnasFest, ExtrasNocturnasFest, Contador, HoraIn, HoraOut, DiaSem, Festivo) VALUES ('$identificacion','$fechae','$extrasdiurnas','$extrasnocturnas','$extrasdiurnafest','$extrasnocturnasfest','$z','$horaIn','$horaOut','$diasem', '$festivo')") or die("No se pudo acceder a la tabla Registro de Extras");
	
	//echo "datos ingresados: $identificacion, '$fechae', $extrasdiurnas, $extrasnocturnas , $z, $horaIn, $horaOut <br>";
}


////////////////////////////////////////////////////
///////////Si es Sabado/////////////////////////////
////////////////////////////////////////////////////

if ($diasem == "Saturday" and $festivo<>"X"){
///////primera posibilidad que entre antes de la hora normal

	if ($horaIn < $HoraIngresoSab){
		if($horaIn<$LimInfNoct){   ///si entra antes de las 6 am
			$extrasnocturnas=$LimInfNoct-$horaIn;
			$extrasdiurnas=($HoraIngresoSab + $Tolerancia)-$LimInfNoct;
		}else{
			$extrasdiurnas=($HoraIngresoSab + $Tolerancia) - $horaIn;
		}
		
		
	}
	
	
	
	
	/////// 5ta posibilidad que salga despues de la hora de salida y haya entrado despues de la hora del medio dia
	
	if (($horaOut > $HoraSalidaSab) ){
		if($horaOut>$LimSupNoct){
			$extrasnocturnas=$horaOut-$LimSupNoct;
			$extrasdiurnas=($LimSupNoct - ($HoraSalidaSab-$Tolerancia));
		}else{
			$extrasdiurnas=$horaOut-($HoraSalidaSab-$Tolerancia);
		}
	}	
	
	/////// 4ta posibilidad que salga despues de las 3 horas del sabado y que su hora de entrada haya sido antes o igual a la hora de ingreso
	
	if (($horaOut < ($HoraSalidaSab-$Tolerancia)) ){
		$extrasdiurnas=$extrasdiurnas-(($HoraSalidaSab-$Tolerancia)-$horaOut);
	}
	
	mysql_query("INSERT INTO registroextras (idEmpleado, Fecha, ExtrasDiurnas, ExtrasNocturnas, ExtrasDiurnasFest, ExtrasNocturnasFest, Contador, HoraIn, HoraOut, DiaSem, Festivo) VALUES ('$identificacion','$fechae','$extrasdiurnas','$extrasnocturnas','$extrasdiurnafest','$extrasnocturnasfest','$z','$horaIn','$horaOut','$diasem', '$festivo')") or die("No se pudo acceder a la tabla Registro de Extras");
	
	//echo "datos ingresados: $identificacion, '$fechae', $extrasdiurnas, $extrasnocturnas , $z, $horaIn, $horaOut <br>";
}



////////////////////////////////////////////////////
///////////Si es Domingo o festivo/////////////////////////////
////////////////////////////////////////////////////

if ($diasem == "Sunday" or $festivo=="X"){
///////primera posibilidad que entre antes de la hora normal

	
		if($horaIn<$LimInfNoct){   ///si entra antes de las 6 am
			$extrasnocturnasfest=$LimInfNoct-$horaIn;
			$extrasdiurnas=$horaOut-$LimInfNoct;
		}else{
			$extrasdiurnas=$horaOut - $horaIn;
		}
		
		if($extrasdiurnas>8){
			$extrasdiurnafest=$extrasdiurnas-8;
			$extrasdiurnas=8;
		}
			
	
	/////// 5ta posibilidad que salga despues de la hora de salida y haya entrado despues de la hora del medio dia
	
	
		if($horaOut>$LimSupNoct)
			$extrasnocturnasfest=$extrasnocturnasfest + ($horaOut-$LimSupNoct);
			
	mysql_query("INSERT INTO registroextras (idEmpleado, Fecha, ExtrasDiurnas, ExtrasNocturnas, ExtrasDiurnasFest, ExtrasNocturnasFest, Contador, HoraIn, HoraOut, DiaSem, Festivo) VALUES ('$identificacion','$fechae','$extrasdiurnas','$extrasnocturnas','$extrasdiurnafest','$extrasnocturnasfest','$z','$horaIn','$horaOut','$diasem', '$festivo')") or die("No se pudo acceder a la tabla Registro de Extras");
	
	//echo "datos ingresados: $identificacion, '$fechae', $extrasdiurnas, $extrasnocturnas , $z, $horaIn, $horaOut <br>";
}




	
}

////////////////////////////////////////////
/////////////Calcule el valor de las extras
////////////////////////////////////////////
   
function calculevalorextras($idCol, $Salario){
	
$factorED=1.25;
$factorEN=1.75;
$factorODF=1.75;
$factorEDFD=2;
$factorEDFN=2.5;

	
	include("conexion.php");
	$con=mysql_connect($host,$user,$pw) or die("problemas con el servidor");
mysql_select_db($db,$con) or die("la base de datos no abre");
	
	$sel1=mysql_query("SELECT SUM(ExtrasDiurnas) as SumDiur, SUM(ExtrasNocturnas) as SumNoct FROM registroextras WHERE idEmpleado='$idCol' AND DiaSem<> 'Sunday' AND Festivo <> 'X' ",$con) or die("problemas con la consulta a Registro de Extras 1");
	$DatosExtras=mysql_fetch_array($sel1);
	$ExtrasDiurnas=round($DatosExtras["SumDiur"],2);
	$ExtrasNocturnas=round($DatosExtras["SumNoct"],2);
	
	$sel1=mysql_query("SELECT SUM(ExtrasDiurnas) as SumDiur, SUM(ExtrasNocturnas) as SumNoct FROM registroextras WHERE idEmpleado='$idCol' AND (DiaSem = 'Sunday' OR Festivo = 'X') ",$con) or die("problemas con la consulta a Registro de Extras 2");
	$DatosExtras=mysql_fetch_array($sel1);
	
	$ExtrasOrdinariaF=round($DatosExtras["SumDiur"],2);
	
	$sel1=mysql_query("SELECT SUM(ExtrasDiurnasFest) as SumDiur, SUM(ExtrasNocturnasFest) as SumNoct FROM registroextras WHERE idEmpleado='$idCol'",$con) or die("problemas con la consulta a Registro de Extras 3");
	$DatosExtras=mysql_fetch_array($sel1);
	
	$ExtrasDominicalD=round($DatosExtras["SumDiur"],2);
    $ExtrasDominicalN=round($DatosExtras["SumNoct"],2);
	
	
	
	$TotalExtrasDiurnas=$ExtrasDiurnas * (($Salario/30/8)* $factorED);
	$TotalExtrasNocturnas=$ExtrasNocturnas * (($Salario/30/8)* $factorEN);
	$TotalOrdinariasFest=$ExtrasOrdinariaF * (($Salario/30/8)* $factorODF);
	$TotalDiurnasFest=$ExtrasDominicalD * (($Salario/30/8)* $factorEDFD);
	$TotalNocturnasFest=$ExtrasDominicalN * (($Salario/30/8)* $factorEDFN);
	
	$GranTotal=	$TotalExtrasDiurnas+$TotalExtrasNocturnas+$TotalOrdinariasFest+$TotalDiurnasFest+$TotalNocturnasFest;
	
	$TotalExtrasDiurnas=number_format($TotalExtrasDiurnas);
	$TotalExtrasNocturnas=number_format($TotalExtrasNocturnas);
	$TotalOrdinariasFest=number_format($TotalOrdinariasFest);
	$TotalDiurnasFest=number_format($TotalDiurnasFest);
	$TotalNocturnasFest=number_format($TotalNocturnasFest);
	$GranTotal=number_format($GranTotal);
			
	return array($ExtrasDiurnas, $ExtrasNocturnas, $ExtrasOrdinariaF, $ExtrasDominicalD, $ExtrasDominicalN, $TotalExtrasDiurnas, $TotalExtrasNocturnas,$TotalDiurnasFest, $TotalNocturnasFest, $TotalOrdinariasFest, $GranTotal);
	
}
   
   
   $Tabla="reporteextras";
   $fecha=date("Y-m-d");
   
////////////////////////////////////////////
/////////////Me conecto a la db
////////////////////////////////////////////



$deleterecords = "TRUNCATE TABLE registroextras"; //empty the table of its current records

mysql_query($deleterecords);


////////////////////////////////////////////
/////////////Obtengo datos de la tabla
////////////////////////////////////////////
		 
		 
		 $idTabla="idReporteExtras";	
			
			//echo "$Tabla $idTabla";
		  	
		 $NumMayor=mysql_query("SELECT MAX($idTabla) as maxnp FROM $Tabla") or die ("Error al consultar la tabla de Reporte de Extras");
		  $linea=mysql_fetch_array($NumMayor); 
		  $maxID=$linea["maxnp"];
		  //echo "el max: $maxID";
		  $z=0;
		  $h=1;
		  for ($i=1; $i<=$maxID; $i++) {
			
			$sel1=mysql_query("SELECT * FROM $Tabla WHERE $idTabla=$i",$con) or die("problemas con la consulta a $Tabla");
		  	$DatosExtras=mysql_fetch_array($sel1);
			$FechaExtra[$i]=substr($DatosExtras["FechaHora"], 0, 10);
			$parHora=$DatosExtras["idReporteExtras"] % 2;
			$FechaExtra[$i]=formatofecha($FechaExtra[$i]);
			$horamil[$i]=horamilitar($DatosExtras["FechaHora"]);
			
			if ($parHora==0){
				if($FechaExtra[$i-1]==$FechaExtra[$i]){
					$difhora=calculaextras($horamil[$i], $horamil[$i-1], $FechaExtra[$i], $z, $DatosExtras["Identificacion"], $DatosExtras["Festivo"]);
				}else{
					$z=$z+1;
				}
			}
			}
		 
		   
////////////////////////////////////////////////////////////////
////////////////////////Impresion de Extras//////////////////////
/////////////////////////////////////////////////////////////////


		 
		 
		  $nombre_file=$fecha."Reporte de extras";
		   
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

$pdf->SetFont('helvetica', '', 8);

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
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Liquidacion de Horas Extras $fecha</span></div>


<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">
</div>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');




////////////////////////////////////////////////////////
//////////////////imprime los datos de los empleados y calcula el valor de las extras
///////////////////////////////////////////////////////
	
	
	
		$NumMayor=mysql_query("SELECT MAX(idColaboradores) as maxnp FROM colaboradoresact") or die ("Error al consultar la tabla de colaboradores activos 1");
		  $linea=mysql_fetch_array($NumMayor); 
		  $maxID=$linea["maxnp"];
		 
		  for ($i=1; $i<=$maxID; $i++) {
			
		  $Datos=mysql_query("SELECT * FROM colaboradoresact WHERE idColaboradores=$i") or die ("Error al consultar la tabla de colaboradores activos 2");
		  $registros2=mysql_fetch_array($Datos); 
		  $idCol=$registros2["Identificacion"];
		  $Salario=$registros2["Salario"];
		  
list($ExtraDiurna, $ExtraNoct, $ExtrasOrdFest, $ExtrasFestDiurna, $ExtrasFestNoct, $TotalED, $TotalEN, $TotalEDF, $TotalENF, $TotalOF, $GranTotal)=calculevalorextras($idCol, $Salario);
		  


$tbl = <<<EOD
<br><br><br><br>
<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">

<div id="wb_Text20" style="position:absolute;left:380px;top:72px;width:150px;height:16px;text-align:center;z-index:2;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:10px;"><strong><em>Nombre: $registros2[Nombre] $registros2[Apellido] $registros2[Identificacion]
</em></strong></span></div>


<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Total Extras Diurnas : $ExtraDiurna para $$TotalED</span></div>

<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Total Extras Nocturnas : $ExtraNoct para $$TotalEN</span></div>

<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Total Ordinarias festivas : $ExtrasOrdFest para $$TotalOF</span></div>

<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Total Extras Diurnas Festivas: $ExtrasFestDiurna para $$TotalEDF</span></div>

<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Total Extras Nocturnas Festivas: $ExtrasFestNoct para $$TotalENF</span></div>

<div id="wb_Text5" style="position:absolute;left:334px;top:127px;width:335px;height:18px;z-index:7;text-align:left;">
<span style="color:#000000;font-family:'Bookman Old Style';font-size:13px;">Pago Total: $$GranTotal</span></div>

<hr id="Line3" style="margin:0;padding:0;position:absolute;left:0px;top:219px;width:625px;height:2px;z-index:9;">

<div id="wb_Text20" style="position:absolute;left:380px;top:72px;width:150px;height:16px;text-align:center;z-index:2;">
<span style="color:#00008B;font-family:'Bookman Old Style';font-size:10px;"><strong><em>Detalles
</em></strong></span></div>

</div>


EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');		  
		  
		  
		  $sql=" SELECT * FROM registroextras WHERE IdEmpleado = '$idCol'";
		 $r = mysql_query($sql,$con) or die("La consulta a nuestra base de datos es erronea.".mysql_error());
                       
                        if(mysql_num_rows($r)){//Si existen resultados
						
$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
  <td  width="20">ID</td><td width="80">Identificacion</td><td width="80">Fecha</td><td  width="30">E.D.</td><td  width="30">E.N.</td><td  width="30">E.D.F</td><td  width="30">E.N.F</td><td width="20">Err</td>
  <td width="50">Hora In</td><td width="50">HoraSal</td><td width="80">Dia Semana</td><td width="30">Fest</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');		  
                                   while(list($ID, $Identificacion, $Fecha, $ExtrasDiurnas, $ExtrasNocturnas, $ExtrasDiurnasFest, $ExtrasNocturnasFest, $Errores, $HoraIn, $HoraOut, $DiaSem, $Festivo) = mysql_fetch_array($r)){
				
$tbl = <<<EOD

<table border="1" cellpadding="1" cellspacing="1" align="center" style="border-left: 1px solid #000099;
		border-right: 1px solid #000099;
		border-top: 1px solid #000099;
		border-bottom: 1px solid #000099;">
 <tr>
  <td width="20">$ID</td><td width="80">$Identificacion</td><td width="80">$Fecha</td><td  width="30">$ExtrasDiurnas</td><td  width="30">$ExtrasNocturnas</td><td  width="30">$ExtrasDiurnasFest</td><td  width="30">$ExtrasNocturnasFest</td><td width="20">$Errores</td>
  <td  width="50">$HoraIn</td><td  width="50">$HoraOut</td><td width="80">$DiaSem</td><td  width="30">$Festivo</td>
 </tr>
 </table>
EOD;

$pdf->writeHTML($tbl, false, false, false, false, '');



}

$pdf->AddPage();

						}else{
							$pdf->writeHTML(" <br> No se encontraron registros <br>", false, false, false, false, '');
							$pdf->AddPage();
						}

}



//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');


//============================================================+
// END OF FILE
//============================================================+
?>