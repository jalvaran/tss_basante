<?php
/*
 * Archivo con la informacion de una factura generada desde remisiones
 * 
 */

$pdf->writeHTML("<br>", true, false, false, false, '');
$tbl = <<<EOD
<table cellspacing="1" cellpadding="2" border="0">
    <tr>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Ref</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>CodBar</strong></td>
        <td align="center" colspan="2" style="border-bottom: 2px solid #ddd;"><strong>Producto</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Costo</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Cant</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Total</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Dpto</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Sub1</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Sub2</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Sub3</strong></td>
        
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>PUC</strong></td>
        <td align="center" style="border-bottom: 2px solid #ddd;"><strong>Ver</strong></td>
    </tr>
    
         
EOD;

$sql="SELECT * FROM traslados_items WHERE idTraslado='$idTraslado'";
$Consulta=$obVenta->Query($sql);
 $h=1;  
 $SubtotalCosto=0;
 
while($DatosItemTraslado=$obVenta->FetchArray($Consulta)){
    $SubtotalCosto=$SubtotalCosto+($DatosItemTraslado["CostoUnitario"]*$DatosItemTraslado["Cantidad"]);
    
    $CostoUnitario=  number_format($DatosItemTraslado["CostoUnitario"]);
    $SubTotalItem=  number_format($DatosItemTraslado["CostoUnitario"]*$DatosItemTraslado["Cantidad"]);
    
    if($h==0){
        $Back="#f2f2f2";
        $h=1;
    }else{
        $Back="white";
        $h=0;
    }
    $tbl .= <<<EOD
    
    <tr>
        <td align="left" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[Referencia]</td>
        <td align="left"  style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[CodigoBarras]</td>
        <td align="right" colspan="2" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[Nombre]</td>
        <td align="center" style="border-bottom: 1px solid #ddd;background-color: $Back;">$CostoUnitario</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[Cantidad]</td>
        <td align="center" style="border-bottom: 1px solid #ddd;background-color: $Back;">$SubTotalItem</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[Departamento]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[Sub1]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[Sub2]</td>
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[Sub3]</td>
        
        <td align="right" style="border-bottom: 1px solid #ddd;background-color: $Back;">$DatosItemTraslado[CuentaPUC]</td>
            <td align="center" style="border-bottom: 1px solid #ddd;background-color: $Back;">[__]</td>
    </tr>
    
     
    
        
EOD;
    
}

$tbl .= <<<EOD
        </table>
EOD;

$pdf->MultiCell(180, 170, $tbl, 1, 'C', 1, 0, '', '', true,1, true, true, 10, 'M');
$pdf->writeHTML("<br><br>", true, false, false, false, '');


////Totales de la cotizacion
////
////

$Subtotal=number_format($SubtotalCosto);

//$TotalLetras=numtoletras($TotalFactura, "PESOS COLOMBIANOS");


$tbl = <<<EOD
        
<table  cellpadding="2" border="0">
    <tr>
        <td height="25" colspan="4" style="border-bottom: 1px solid #ddd;background-color: white;"></td> 
        
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>TOTAL COSTO:</h3></td>
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3>$ $Subtotal</h3></td>
    </tr>
    <tr>
        <td colspan="4" height="25" border="1" style="border-bottom: 1px solid #ddd;background-color: white;">Observaciones:</td> 
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3></h3></td>
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3></h3></td>
    </tr>
    <tr>
        <td colspan="2" height="50" align="center" style="border-bottom: 1px solid #ddd;background-color: white;"><br/><br/><br/><br/><br/>Firma Responsable: ________________</td> 
        <td colspan="2" height="50" align="center" style="border-bottom: 1px solid #ddd;background-color: white;"><br/><br/><br/><br/><br/>Firma Verificado:  ________________</td> 
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3></h3></td>
        <td align="rigth" style="border-bottom: 1px solid #ddd;background-color: white;"><h3></h3></td>
    </tr>
     
</table>

        
EOD;

$pdf->MultiCell(180, 30, $tbl, 1, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');

?>