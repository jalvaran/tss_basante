<?php

$DatosTercero=$obVenta->DevuelveValores("proveedores","idProveedores",$Tercero);
    
$tbl = <<<EOD
      
<table cellpadding="1" border="1">
    <tr>
        <td><strong>Tercero:</strong></td>
        <td colspan="3">$DatosTercero[RazonSocial]</td>
        
    </tr>
    <tr>
    	<td><strong>NIT:</strong></td>
        <td colspan="3">$DatosTercero[Num_Identificacion] - $DatosTercero[DV]</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Dirección:</strong></td>
        <td><strong>Ciudad:</strong></td>
        <td><strong>Telefono:</strong></td>
    </tr>
    <tr>
        <td colspan="2">$DatosTercero[Direccion]</td>
        <td>$DatosTercero[Ciudad]</td>
        <td>$DatosTercero[Telefono]</td>
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
        <td colspan="3"><strong>Descripcion:</strong></td>
        
        
    </tr>
    <tr>
    	<td colspan="3" height="36">$DatosOC[Descripcion]<br><strong>Plazo de Entrega:</strong> $DatosOC[PlazoEntrega]  </td>
        
    </tr>
    <tr>
        <td colspan="3"><strong>No Cotizacion:</strong> $DatosOC[NoCotizacion] </td>
        
    </tr>
    
    
</table>       
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
//$pdf->writeHTML("<br><br>", true, false, false, false, '');
?>