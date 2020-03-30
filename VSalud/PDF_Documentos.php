<?php 
if(isset($_REQUEST["idDocumento"])){
    $myPage="PDF_Documentos.php";
    include_once("../sesiones/php_control.php");
    include_once("clases/ClasesPDFDocumentos.php");  //Clase que genera los pdf
    
    $obCon = new conexion($idUser);
    
    $obDoc = new Documento($db);
    $idDocumento=$obCon->normalizar($_REQUEST["idDocumento"]);
    
    
    switch ($idDocumento){ //Si se recibe 1, es para generar un cobro prejuridico
        case 1: //Se va a generar un cobro prejuridico juridico
            $idCobro=$obCon->normalizar($_REQUEST["idCobroPrejuridico"]);
            $obDoc->PDF_CobroPrejuridico($idCobro);
            
        break;
        case 2: //Se generan los reportes de glosas
            $TipoReporte=$obCon->normalizar($_REQUEST["TipoReporte"]);
            //$st= base64_decode($_REQUEST["st"]);
            //if($TipoReporte==2){
                $st= urldecode($_REQUEST["st"]);
            //}
            $query="";
            
            if(isset($_REQUEST["q"])){
                $query=urldecode($_REQUEST["q"]);
            }
            $obDoc->Reportes_PDF($TipoReporte,$query,$st,$idUser,"");
        break;
        
    }
}else{
    print("No se recibió parametro de documento");
}

?>