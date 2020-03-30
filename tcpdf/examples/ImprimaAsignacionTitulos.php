<?php
include("../../modelo/php_conexion.php");

////////////////////////////////////////////
/////////////Obtengo el ID del comprobante a que se imprimirá 
////////////////////////////////////////////
$idAsignacion = $_REQUEST["idAsignacion"];

$idFormatoCalidad=18;

$Documento="<strong>ACTA DE ENTREGA No. $idAsignacion</strong>";
require_once('Encabezado.php');
////////////////////////////////////////////
/////////////Obtengo valores del comprobante
////////////////////////////////////////////		
$obVenta=new conexion(1);
$DatosGenerales=$obVenta->DevuelveValores("titulos_asignaciones","ID",$idAsignacion);
//$DatosColaborador=$obVenta->DevuelveValores("colaboradores","Identificacion",$DatosGenerales["idColaborador"]);
$fecha=$DatosGenerales["Fecha"];

$nombre_file=$idAsignacion."_Acta_Entrega_".$fecha;
		   
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="0" align="left" style="border-radius: 10px;">
    <tr>
        <td><strong>Ciudad:</strong> Buga</td>
        <td><strong>Fecha:</strong> $fecha</td>
        <td><strong>No.:</strong> $idAsignacion</td>
    </tr>
    <tr>
        <td colspan="3"><strong>Nombre Colaborador:</strong> $DatosGenerales[NombreColaborador] $DatosGenerales[idColaborador]</td>
        
    </tr>
    <tr>
        <td colspan="3"><strong>Observaciones:</strong> $DatosGenerales[Observaciones]</td>
        
    </tr>
    
</table>
        
<br><br><br>

  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');

////////////////////////////////////////
///Dibujo movientos contables

$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
    <tr align="left">
        <td><strong>Fecha</strong></td>
        <td><strong>Promocion</strong></td>
        <td><strong>Desde</strong></td>
        <td><strong>Hasta</strong></td>
        
    </tr>
    
</table>
       

EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');

$h=0;

    
$Back="#f2f2f2";
        
   
$tbl = <<<EOD
<table border="0" cellpadding="2" cellspacing="2" align="left" style="border-radius: 10px;">
    <tr align="left">
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosGenerales[Fecha]</td>
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosGenerales[Promocion]</td>
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosGenerales[Desde]</td>
        <td style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosGenerales[Hasta]</td>
    </tr>
    
</table>
       
 
      
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');


///////////
///////Espacio para firmas
//
//

$tbl = <<<EOD
         <br><br><br> 
         <br><br><br> 
<table border="1" cellpadding="2" cellspacing="0" align="left">
     
    <tr align="left" >
        <td style="height: 100px;" >Realizado:</td>
        <td style="height: 100px;" >Recibido:</td>
        <td style="height: 100px;" >Aprobado:</td>
        
    </tr>
   
</table>
       
  
EOD;
$pdf->writeHTML($tbl, false, false, false, false, '');

//Close and output PDF document
$pdf->Output($nombre_file.'.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>