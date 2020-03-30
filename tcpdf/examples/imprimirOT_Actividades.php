<?php
/*
 * Archivo con la informacion de las actividades de una orden de trabajo
 * 
 */

$pdf->writeHTML("<br>", true, false, false, false, '');
$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="0">
    <tr>
        <td align="center" colspan="3" style="border-bottom: 2px solid #ddd;"><strong>Actividad</strong></td>
        <td align="center"  style="border-bottom: 2px solid #ddd;"><strong>Fecha Inicio</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Fecha Fin</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Horas</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Colaborador</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Observacion</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Realizado</strong></td>
    </tr>
    
         
EOD;

$sql="SELECT * FROM ordenesdetrabajo_items WHERE idOT='$idOT'";
$Consulta=$obVenta->Query($sql);
 $h=1;  
 
while($DatosItemOT=$obVenta->FetchArray($Consulta)){
    
    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    $tbl .= <<<EOD
    
    <tr>
        <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemOT[Actividad]</td>
        <td align="left"  style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemOT[FechaInicio]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemOT[FechaFin]</td>
        <td align="center" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemOT[TiempoEstimadoHoras]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemOT[idColaborador]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemOT[Observaciones]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;"></td>
    </tr>
    
     
    
        
EOD;
    
}

$tbl .= <<<EOD
        </table>
EOD;

$pdf->MultiCell(180, 140, $tbl, 1, 'C', 1, 0, '', '', true,1, true, true, 10, 'M');
$pdf->writeHTML("<br><br>", true, false, false, false, '');

$tbl="";

$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="0">
    <tr>
        
        <td align="center" colspan="4" style="border-bottom: 2px solid #ddd;"><strong>SERVICIOS ADICIONALES SOLICITADOS POR EL CLIENTE AL MOMENTO DE PRESTAR EL SERVICIO</strong></td>
        
    </tr>
    
         
EOD;

$h=1;  
 
for($i=0;$i<=5;$i++){
    
    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    $tbl .= <<<EOD
    
    <tr>
        
        <td align="right" colspan="4" style="border-bottom: 1px solid #ddd;background-color: $Back;"></td>
    </tr>
    
     
    
        
EOD;
    
}
$Back="white";
$Back2="#f2f2f2";
$tbl .= <<<EOD
        <tr>
           <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>Autoriza:</strong></td>
           <td align="left" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>No. Orden Adicional:</strong></td>
        </tr>
        <tr>
           <td align="left" colspan="4" style="border-bottom: 1px solid #ddd;background-color: $Back2;"></td>  
        </tr>
        <tr>
           <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>Servicio Generado por Garantía:</strong></td>
           
           <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>(SI) (NO)</strong></td>
        </tr>
        
        <tr>
           <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back2;"><strong>Servicio generado con cobro al cliente:</strong></td>
           
           <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back2;"><strong>(SI) (NO)</strong></td>
        </tr>
        
        <tr>
        
            <td align="center" colspan="4" style="border-bottom: 2px solid #ddd;"><strong>RECEPCION DEL TRABAJO</strong></td>
        
        </tr>
        <tr>
           <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back2;"><strong>Plena satisfacción del trabajo realizado:</strong></td>
           
           <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back2;"><strong>(SI) (NO)</strong></td>
        </tr>
        <tr>
           <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>Cumple con lo solicitado:</strong></td>
           
           <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>(SI) (NO)</strong></td>
        </tr>
        <tr>
        
            <td align="center" colspan="4" style="border-bottom: 2px solid #ddd;"></td>
        
        </tr>
        <tr>
           <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back2;"><strong>Nombre:</strong></td>
           
           <td align="left" style="border-bottom: 1px solid #ddd;background-color: $Back2;"><strong>Firma:</strong></td>
        </tr>
        <tr>
           <td align="left" colspan="3" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>Cedula:</strong></td>
           
           <td align="left" style="border-bottom: 1px solid #ddd;background-color: $Back;"><strong>Telefono:</strong></td>
        </tr>
        </table>
EOD;

$pdf->MultiCell(180, 70, $tbl, 1, 'C', 1, 0, '', '', true,1, true, true, 10, 'M');
$pdf->writeHTML("<br><br>", true, false, false, false, '');
?>