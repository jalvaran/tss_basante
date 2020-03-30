<?php
    
$tbl = <<<EOD
      
<table cellpadding="1" border="1">
    <tr>
        <td><strong>ORIGEN:</strong></td>
        <td colspan="2">$DatosSucursalOrigen[Nombre]</td>
        
    </tr>
    <tr>
    	<td><strong>DESTINO:</strong></td>
        <td colspan="2">$DatosSucursalDestino[Nombre]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Dirección:</strong></td>
        <td><strong>Ciudad:</strong></td>
        
    </tr>
    <tr>
        <td colspan="2">$DatosSucursalDestino[Direccion]</td>
        <td>$DatosSucursalDestino[Ciudad]</td>
        
    </tr>
    <tr>
        <td colspan="2"><strong>Fecha: </strong></td>
        <td colspan="1">$fecha</td>
    </tr>
    
</table>       
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');

$tbl = <<<EOD
      
<table cellpadding="1" border="1">
    <tr>
        <td colspan="3"><strong>Descripcion:</strong></td>
        
        
    </tr>
    <tr>
    	<td colspan="3" height="36">$observaciones</td>
        
    </tr>
    <tr>
        <td colspan="3"><strong>Usuario:</strong> $Usuario</td>
        
    </tr>
    
    
</table>       
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
//$pdf->writeHTML("<br><br>", true, false, false, false, '');
?>