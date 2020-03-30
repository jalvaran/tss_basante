<?php

/* 
 * Este archivo procesa la anulacion de un abono a un titulo
 */


if(!empty($_REQUEST["BtnEnviar"])){
    $obGlosas=new Glosas($idUser);       
    $Indicador=$obGlosas->normalizar($_REQUEST["TxtIndicador"]);
    $TipoGlosa=$obGlosas->normalizar($_REQUEST["CmbTipoGlosa"]);
    $CodigoGlosa=$obGlosas->normalizar($_REQUEST["CodigoGlosa".$Indicador]);
    $FechaReporte=$obGlosas->normalizar($_REQUEST["TxtFechaReporte"]);
    
    $ValorEPS=$obGlosas->normalizar($_REQUEST["TxtValorGlosaEPS"]);
    $ValorAceptado=$obGlosas->normalizar($_REQUEST["TxtValorGlosaAceptada"]);
    $Observaciones=$obGlosas->normalizar($_REQUEST["TxtObservaciones"]);
    $TablaOrigen=$obGlosas->normalizar($_REQUEST["TxtTabla"]);
    $idArchivo=$obGlosas->normalizar($_REQUEST["idArchivo"]);
    $NumFactura=$obGlosas->normalizar($_REQUEST["NumFactura"]);
    $idFactura=$obGlosas->normalizar($_REQUEST["idFactura"]);
    $idEps=$obGlosas->normalizar($_REQUEST["idEps"]);
    
    $obGlosas->RegistreGlosa($TipoGlosa,$CodigoGlosa,$FechaReporte,$ValorEPS,$ValorAceptado,$Observaciones,$TablaOrigen,$idArchivo,$NumFactura,$idEps,$idUser,"");
        
    header("location:$myPage?idFactura=$idFactura");
   

}
?>