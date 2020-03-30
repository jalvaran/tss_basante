<?php

$DatosCliente=$obVenta->DevuelveValores("clientes","idClientes",$Clientes_idClientes);
    
$tbl = <<<EOD
      
<table cellpadding="1" border="1">
    <tr>
        <td><strong>Cliente:</strong></td>
        <td colspan="3">$DatosCliente[RazonSocial]</td>
        
    </tr>
    <tr>
    	<td><strong>NIT:</strong></td>
        <td colspan="3">$DatosCliente[Num_Identificacion] - $DatosCliente[DV]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Dirección:</strong></td>
        <td><strong>Ciudad:</strong></td>
        <td><strong>Teléfono:</strong></td>
    </tr>
    <tr>
        <td colspan="2">$DatosCliente[Direccion]</td>
        <td>$DatosCliente[Ciudad]</td>
        <td>$DatosCliente[Telefono]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Fecha: </strong></td>
        <td colspan="2">$fecha</td>
    </tr>
    
</table>       
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');

$tbl = <<<EOD
      
<table cellpadding="1" border="1">
    <tr>
        <td colspan="3"><strong>Observaciones:</strong></td>
        
        
    </tr>
    <tr>
    	<td colspan="3" height="36">$observaciones</td>
        
    </tr>
    <tr>
        <td colspan="3"><strong>Vendedor:</strong> $Vendedor</td>
        
    </tr>
    
    
</table>       
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
//$pdf->writeHTML("<br><br>", true, false, false, false, '');
?>