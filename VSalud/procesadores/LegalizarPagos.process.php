<?php 
$obCon=new conexion($idUser);
// si se requiere guardar y cerrar
if(isset($_REQUEST["BtnLegalizar"])){
    
        $idPago=$obCon->normalizar($_REQUEST["idPago"]);
        $Observaciones=$obCon->normalizar($_REQUEST["TxtObservaciones"]);
        $ValorLegalizado=$obCon->normalizar($_REQUEST["TxtValorLegalizado"]);
        $ValorTransaccion=$obCon->normalizar($_REQUEST["ValorTransaccion"]);
        
        $Legalizado="SI";
        $ValorXLegalizar=$ValorTransaccion-$ValorLegalizado;
        if($ValorXLegalizar>0){
            $Legalizado="NO";
        }
        $sql="UPDATE salud_tesoreria SET valor_legalizado='$ValorLegalizado',"
                . "valor_legalizar='$ValorXLegalizar',legalizado='$Legalizado',observaciones_cartera='$Observaciones' "
                . " WHERE ID='$idPago'";
        $obCon->Query($sql);
   header("location:$myPage?ID=$idPago");
    
}
///////////////fin
?>