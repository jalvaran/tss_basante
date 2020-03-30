<?php

$DatosTercero=$obVenta->DevuelveValores("proveedores","Num_Identificacion",$Tercero);
if($DatosTercero["Num_Identificacion"]==''){
    $DatosTercero=$obVenta->DevuelveValores("clientes","Num_Identificacion",$Tercero);
}
$DatosUsuario=$obVenta->DevuelveValores("usuarios","idUsuarios",$Usuarios_idUsuarios);   
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
        <td colspan="3"><strong>Concepto:</strong></td>
        
        
    </tr>
    <tr>
    	<td colspan="3" height="36">$Concepto </td>
        
    </tr>
    <tr>
        <td colspan="3"><strong>Creado Por:</strong> $DatosUsuario[Nombre] $DatosUsuario[Apellido] </td>
        
    </tr>
    
    
</table>       
EOD;

$pdf->MultiCell(90, 25, $tbl, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
$pdf->writeHTML("<br>", true, false, false, false, '');
?>