<?php

/* 
 * Este archivo procesa la anulacion de un abono a un titulo
 */


if(!empty($_REQUEST["BtnGenerar"])){
    $css =  new CssIni("id");
    $obLegal=new Legal($idUser); 
    $Fecha=date("Y-m-d");
    $TipoCobro=$obLegal->normalizar($_REQUEST["CmbCobro"]);
    $NumFact=$obLegal->normalizar($_REQUEST["TxtBuscarFact"]);
    $Observaciones=$obLegal->normalizar($_REQUEST["TxtObservaciones"]);
    $idEPS=$obLegal->normalizar($_REQUEST["idEps"]);
      
    $idCobro=$obLegal->CrearCobroPrejuridico($Fecha,$TipoCobro,$NumFact,$idEPS,$Observaciones,$idUser,"");
    if($idCobro>0){
        $css->CrearNotificacionNaranja("Cobro Prejuridico $TipoCobro creado con ID $idCobro", 16);  
    }else{
        $css->CrearNotificacionRoja("No se encontraron facturas para realizar cobro", 16);  
    }
    
}
?>