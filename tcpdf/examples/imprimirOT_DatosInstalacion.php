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
        <td colspan="2"><strong>Dirección del Servicio:</strong></td>
        <td><strong>Ciudad:</strong></td>
        <td><strong>Tipo de Orden:</strong></td>
    </tr>
    <tr>
        <td colspan="2">$DatosOT[DireccionServicio]</td>
        <td>$DatosCliente[Ciudad]</td>
        <td>$DatosTipoOrden[Tipo]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Fecha: </strong></td>
        <td colspan="2">$Fecha</td>
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
    	<td colspan="3" height="36">$DatosOT[Descripcion]</td>
        
    </tr>
    <tr>
        <td colspan="3"><strong>Datos del Solicitante:</strong> $DatosOT[NombreSolicitante] $DatosOT[Telefono]</td>
        
    </tr>
    
    
</table>       
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
//$pdf->writeHTML("<br><br>", true, false, false, false, '');
?>