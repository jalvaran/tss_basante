<?php

@session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../../index.php' ><img src='../../../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../clases/mipres.class.php");

if( !empty($_REQUEST["Accion"]) ){
    
    $obCon=new MiPres($idUser);
    
    switch ($_REQUEST["Accion"]) {
        
        case 1: //Obtener token para consultas
            
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2000);//aqui se encuentra la url para obtener el token
            $method="GET";
            $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$datos_empresa["TokenAPIMipres"];
            $Token=$datos_empresa["TokenAPIMipres"];
            $data="";
            $respuesta=$obCon->callAPI($method, $url, $Token, $data);
            print("OK;$respuesta");
            
        break;//Fin caso 1
    
        case 2: //Obtener direccionamiento por fecha
            
            $empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2001);//aqui se encuentra la url para obtener el token
            $fecha=$obCon->normalizar($_REQUEST["fecha"]);
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            //$token_consultas="8iNlAS7T2lk80Sf5T57Kj7U5WlVocHdh1ixwp7fQEVc=";
            $method="GET";
            $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$fecha;
            $Token=$datos_empresa["TokenAPIMipres"];
            $data="";
            $respuesta=$obCon->callAPI($method, $url, $Token, $data);
            $datos_obtenidos= json_decode($respuesta,1);
            if(isset($datos_obtenidos[0]["ID"])){
                $obCon->guardar_programacion_mipres($db, $datos_obtenidos, $idUser);
                print("OK;Datos Guardados");
            }else{
                print("OK;No hay datos en la Fecha $fecha");
            }
            
            
            
        break;//Fin caso 2
    
           
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>