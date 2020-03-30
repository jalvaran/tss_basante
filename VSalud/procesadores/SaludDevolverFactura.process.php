<?php

/* 
 * Este archivo procesa la anulacion de un abono a un titulo
 */

if(!empty($_REQUEST["BtnDevolver"])){
    $obGlosas=new Glosas($idUser);       
    
    $TipoGlosa=5;
    $idFactura=$obGlosas->normalizar($_REQUEST["idFactura"]);
    $CodigoGlosa=$obGlosas->normalizar($_REQUEST["CmbGlosas"]);
    $FechaReporte=$obGlosas->normalizar($_REQUEST["TxtFechaDevolucion"]);
    $DatosFactura=$obGlosas->DevuelveValores("salud_archivo_facturacion_mov_generados", "id_fac_mov_generados", $idFactura);
    $ValorEPS=$DatosFactura["valor_neto_pagar"];
    $ValorAceptado=$DatosFactura["valor_neto_pagar"];
    $Observaciones=$obGlosas->normalizar($_REQUEST["TxtObservaciones"]);
    $TablaOrigen="salud_archivo_facturacion_mov_generados";
    $idArchivo=$idFactura;
    $NumFactura=$DatosFactura["num_factura"];
    
    $idEps=$DatosFactura["cod_enti_administradora"];

    $obGlosas->RegistreGlosa($TipoGlosa,$CodigoGlosa,$FechaReporte,$ValorEPS,$ValorAceptado,$Observaciones,$TablaOrigen,$idArchivo,$NumFactura,$idEps,$idUser,"");
    $obGlosas->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "estado", "DEVUELTA", "id_fac_mov_generados", $idFactura);
    header("location:$myPage?TxtidComprobante=1");
        
}
?>