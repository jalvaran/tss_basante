<?php
if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
class MiPres extends conexion{
    
    
    public function callAPI($method, $url,$Token, $data) {
        
               
        $curl = curl_init();

        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($data)));
              curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
              //if ($data)
                 //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'Authorization: Bearer '.$Token,
           'Content-Type: application/json',
           'Accept: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
    }
    
    //insertar los datos a la tabla de direccionamiento
    
    public function guardar_direccionamiento_mipres($db,$datos_programacion,$user_id) {
        
        $Tabla="$db.mipres_direccionamiento";
        
        $sql="REPLACE INTO "
                . "$Tabla (`ID`,`IDDireccionamiento`,`NoPrescripcion`,`TipoTec`,`ConTec`,`TipoIDPaciente`,"
                . "`NoIDPaciente`,`NoEntrega`,`NoSubEntrega`,`FecMaxEnt`,`TipoIDProv`,`NoIDProv`,"
                . "`CodMunEnt`,`CantTotAEntregar`,"
                . "`DirPaciente`,`CodSerTecAEntregar`,`NoIDEPS`,`CodEPS`,`FecDireccionamiento`,`EstDireccionamiento`,"
                . "`user_id`) VALUES ";
        
        foreach ($datos_programacion as $key => $array_programacion) {
            $sql.="(";
            $sql.="'".$array_programacion["ID"]."',";
            $sql.="'".$array_programacion["IDDireccionamiento"]."',";
            $sql.="'".$array_programacion["NoPrescripcion"]."',";
            $sql.="'".$array_programacion["TipoTec"]."',";
            $sql.="'".$array_programacion["ConTec"]."',";
            $sql.="'".$array_programacion["TipoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoEntrega"]."',";
            $sql.="'".$array_programacion["NoSubEntrega"]."',";
            $sql.="'".$array_programacion["FecMaxEnt"]."',";
            $sql.="'".$array_programacion["TipoIDProv"]."',";
            $sql.="'".$array_programacion["NoIDProv"]."',";
            $sql.="'".$array_programacion["CodMunEnt"]."',";            
            $sql.="'".$array_programacion["CantTotAEntregar"]."',";
            $sql.="'".$array_programacion["DirPaciente"]."',";
            $sql.="'".$array_programacion["CodSerTecAEntregar"]."',";            
            $sql.="'".$array_programacion["NoIDEPS"]."',";
            $sql.="'".$array_programacion["CodEPS"]."',";
            $sql.="'".$array_programacion["FecDireccionamiento"]."',";
            $sql.="'".$array_programacion["EstDireccionamiento"]."',";            
            $sql.="'".$user_id."'";
            
            $sql.="),";
            
        }
        
        $sql=substr($sql, 0, -1);
        $this->Query($sql);
        
    }
    
    public function obtener_dias_diferencia_rango_fecha($fecha_inicial,$fecha_final) {
        $date1 = new DateTime($fecha_inicial);
        $date2 = new DateTime($fecha_final);
        $diff = $date1->diff($date2);
        return($diff->days);
    }
    
    public function sumar_dias_fecha($fecha,$dias) {
        return date("Y-m-d",strtotime($fecha."+ $dias days")); 
        
    }
    
    public function programar_mipres_x_id($datos_empresa,$ID,$token_consultas,$idUser) {
        $db=$datos_empresa["db"];
        
        $datos_mipres=$this->DevuelveValores("$db.mipres_direccionamiento", "ID", $ID);
        
        $datos_servidor=$this->DevuelveValores("servidores", "ID", 2002);//aqui se encuentra la url para programar
        $method="PUT";
        $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas;
        $Token=$datos_empresa["TokenAPIMipres"];
        $data=$this->json_programar_mipres_x_id($ID, $datos_mipres["FecMaxEnt"], 'NI', $datos_empresa["NIT"],$datos_empresa["CodSedeProv"] , $datos_mipres["CodSerTecAEntregar"], $datos_mipres["CantTotAEntregar"]);
        $respuesta=$this->callAPI($method, $url, $Token, $data);
        $datos_obtenidos= json_decode($respuesta,1);
        
        $fecha=date("Y-m-d H:i:s");
        if(isset($datos_obtenidos[0]["Id"])){
            $idProgramacion=$datos_obtenidos[0]["IdProgramacion"];
            $Datos["mipres_id"]=$ID;
            $Datos["IdProgramacion"]=$idProgramacion;
            $Datos["user_id"]=$idUser;
            $sql=$this->getSQLInsert("$db.mipres_registro_programacion", $Datos);            
            $this->Query($sql);
            
            $retorno["OK"]=1;
            $retorno["IdProgramacion"]=$idProgramacion;
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        
        if(isset($datos_obtenidos["Id"])){
            $idProgramacion=$datos_obtenidos["IdProgramacion"];
            $Datos["mipres_id"]=$ID;
            $Datos["IdProgramacion"]=$idProgramacion;
            $Datos["user_id"]=$idUser;
            $sql=$this->getSQLInsert("$db.mipres_registro_programacion", $Datos);            
            $this->Query($sql);
            
            $retorno["OK"]=1;
            $retorno["IdProgramacion"]=$idProgramacion;
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        
        return($datos_obtenidos);
        
    }
    
    public function json_programar_mipres_x_id($ID,$FechaMaxEnt,$TipoIDSedeProv,$NoIDSedeProv,$CodSedeProv,$CodSerTecAEntregar,$CantTotAEntregar) {
        
        $json='{
            "ID": '.$ID.',
            "FecMaxEnt": "'.$FechaMaxEnt.'",
            "TipoIDSedeProv": "'.$TipoIDSedeProv.'",
            "NoIDSedeProv": "'.$NoIDSedeProv.'",
            "CodSedeProv": "'.$CodSedeProv.'",
            "CodSerTecAEntregar": "'.$CodSerTecAEntregar.'",
            "CantTotAEntregar": "'.$CantTotAEntregar.'"
          }'; 
        return $json;
        
    }
    
    public function entregar_mipres_x_id($datos_empresa,$ID,$token_consultas,$mipres_cantidad_entregada,$mipres_identificacion_recibe,$mipres_fecha_real_entrega,$mipres_tipo_documento_recibe,$mipres_causas_no_entrega,$idUser) {
        $db=$datos_empresa["db"];
        
        $datos_mipres=$this->DevuelveValores("$db.mipres_direccionamiento", "ID", $ID);
        if($mipres_cantidad_entregada>$datos_mipres["CantTotAEntregar"]){
            exit("E1;La cantidad entregada no puede ser mayor a la cantidad por entregar");
        }
        $flag_total_entrega=1;
        if($mipres_cantidad_entregada<$datos_mipres["CantTotAEntregar"]){
            $flag_total_entrega=0;
        }
        if($mipres_causas_no_entrega<>''){
            $flag_total_entrega=0;
            $mipres_cantidad_entregada=0;
            $mipres_fecha_real_entrega='0000-00-00';
            $mipres_tipo_documento_recibe="";
            $mipres_identificacion_recibe="";
        }
        if($mipres_causas_no_entrega==''){
            $mipres_causas_no_entrega=0;
        }
        $datos_servidor=$this->DevuelveValores("servidores", "ID", 2003);//aqui se encuentra la url para entregar
        $method="PUT";
        $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas;
        $Token=$datos_empresa["TokenAPIMipres"];
        $data=$this->json_entregar_mipres_x_id($ID, $datos_mipres["CodSerTecAEntregar"], $mipres_cantidad_entregada , $flag_total_entrega, $mipres_causas_no_entrega, $mipres_fecha_real_entrega, "", $mipres_tipo_documento_recibe, $mipres_identificacion_recibe);
        //exit($url."<br>".$data);
        $respuesta=$this->callAPI($method, $url, $Token, $data);
        $datos_obtenidos= json_decode($respuesta,1);
        
        $fecha=date("Y-m-d H:i:s");
        if(isset($datos_obtenidos[0]["Id"])){
            $idEntrega=$datos_obtenidos[0]["IdEntrega"];
            $Datos["mipres_id"]=$ID;
            $Datos["IdEntrega"]=$idEntrega;
            $Datos["user_id"]=$idUser;
            $sql=$this->getSQLInsert("$db.mipres_registro_entrega", $Datos);            
            $this->Query($sql);            
            $retorno["OK"]=1;
            $retorno["IdEntrega"]=$idEntrega;
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        
        if(isset($datos_obtenidos["Id"])){
            $idEntrega=$datos_obtenidos["IdEntrega"];
            $Datos["mipres_id"]=$ID;
            $Datos["IdEntrega"]=$idEntrega;
            $Datos["user_id"]=$idUser;
            $sql=$this->getSQLInsert("$db.mipres_registro_entrega", $Datos);            
            $this->Query($sql);            
            $retorno["OK"]=1;
            $retorno["IdEntrega"]=$idEntrega;
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        
        return($datos_obtenidos);
        
    }
    
    public function json_entregar_mipres_x_id($ID,$CodSerTecEntregado,$CantTotEntregada,$EntTotal,$CausaNoEntrega,$FecEntrega,$NoLote,$TipoIDRecibe,$NoIDRecibe) {
        if($CausaNoEntrega==0){
            $json='{
                "ID": '.$ID.',
                "CodSerTecEntregado": "'.$CodSerTecEntregado.'",
                "CantTotEntregada": "'.$CantTotEntregada.'",
                "EntTotal": '.$EntTotal.',
                "CausaNoEntrega": '.$CausaNoEntrega.',
                "FecEntrega": "'.$FecEntrega.'",                
                "TipoIDRecibe": "'.$TipoIDRecibe.'",
                "NoIDRecibe": "'.$NoIDRecibe.'"
              }'; 
        }else{
            $json='{
                "ID": '.$ID.',
                "CausaNoEntrega": '.$CausaNoEntrega.',
                "NoLote": "'.$NoLote.'",
                "TipoIDRecibe": "'.$TipoIDRecibe.'",
                "NoIDRecibe": "'.$NoIDRecibe.'"
              }'; 
        }
        return $json;
        
    }
    
    
    public function anular_programacion_mipres($datos_empresa,$programacion_id,$token_consultas,$idUser) {
        $db=$datos_empresa["db"];        
        $datos_servidor=$this->DevuelveValores("servidores", "ID", 2004);//aqui se encuentra la url para anular una programacion
        $method="PUT";
        $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$programacion_id;
        $Token=$datos_empresa["TokenAPIMipres"];
        $data="";
        $respuesta=$this->callAPI($method, $url, $Token, $data);
        $datos_obtenidos= json_decode($respuesta,1);
        $fecha=date("Y-m-d H:i:s");
        $fecha=date("Y-m-d H:i:s");
        if(isset($datos_obtenidos["Message"])){
            $sql="UPDATE mipres_registro_programacion SET user_id_anulacion='$idUser', fecha_anulacion='$fecha' WHERE IdProgramacion='$programacion_id'";
            $this->Query($sql);
            $datos_mipres=$this->DevuelveValores("$db.mipres_programacion", "IDProgramacion", $programacion_id);
            $retorno["OK"]=1;
            $retorno["Mensaje"]=$datos_obtenidos["Message"];
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        if(isset($datos_obtenidos[0]["Mensaje"])){
            $sql="UPDATE mipres_registro_programacion SET user_id_anulacion='$idUser', fecha_anulacion='$fecha' WHERE IdProgramacion='$programacion_id'";
            $this->Query($sql);
            $datos_mipres=$this->DevuelveValores("$db.mipres_programacion", "IDProgramacion", $programacion_id);
            $retorno["OK"]=1;
            $retorno["Mensaje"]=$datos_obtenidos[0]["Mensaje"];
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
                
    }
    
    public function anular_entrega_mipres($datos_empresa,$entrega_id,$token_consultas,$idUser) {
        $db=$datos_empresa["db"];
        $datos_servidor=$this->DevuelveValores("servidores", "ID", 2005);//aqui se encuentra la url para anular una entrega
        $datos_mipres=$this->DevuelveValores("$db.mipres_entrega", "IDEntrega", $entrega_id);
        $method="PUT";
        $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$entrega_id;
        $url= str_replace(" ", "", $url);
        
        $Token=$datos_empresa["TokenAPIMipres"];
        
        $data="";
        $respuesta=$this->callAPI($method, $url, $Token, $data);
        
        $datos_obtenidos= json_decode($respuesta,1);
        
        //print_r($datos_obtenidos);
        
        $fecha=date("Y-m-d H:i:s");
        if(isset($datos_obtenidos["Message"])){
            //print("Entra");
            $sql="UPDATE mipres_registro_entrega SET user_id_anulacion='$idUser', fecha_anulacion='$fecha' WHERE IdEntrega='$entrega_id'";
            $this->Query($sql);
            
            $retorno["OK"]=1;
            $retorno["Mensaje"]=$datos_obtenidos["Message"];
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        if(isset($datos_obtenidos[0]["Mensaje"])){
            //print("Entra");
            $sql="UPDATE mipres_registro_entrega SET user_id_anulacion='$idUser', fecha_anulacion='$fecha' WHERE IdEntrega='$entrega_id'";
            $this->Query($sql);
            
            $retorno["OK"]=1;
            $retorno["Mensaje"]=$datos_obtenidos[0]["Mensaje"];
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
                
    }
    
    //insertar los datos a la tabla de entrega
    
    public function guardar_entrega_mipres($db,$datos_programacion,$user_id) {
        
        $Tabla="$db.mipres_entrega";
        
        $sql="REPLACE INTO "
                . "$Tabla (`ID`,`IDEntrega`,`NoPrescripcion`,`TipoTec`,`ConTec`,`TipoIDPaciente`,"
                . "`NoIDPaciente`,`NoEntrega`,`CodSerTecEntregado`,`CantTotEntregada`,`EntTotal`,`CausaNoEntrega`,"
                . "`FecEntrega`,`NoLote`,"
                . "`TipoIDRecibe`,`NoIDRecibe`,`EstEntrega`,`FecAnulacion`,`CodigosEntrega`,`user_id`)"
                . " VALUES ";
        
        foreach ($datos_programacion as $key => $array_programacion) {
            $sql.="(";
            $sql.="'".$array_programacion["ID"]."',";
            $sql.="'".$array_programacion["IDEntrega"]."',";
            $sql.="'".$array_programacion["NoPrescripcion"]."',";
            $sql.="'".$array_programacion["TipoTec"]."',";
            $sql.="'".$array_programacion["ConTec"]."',";
            $sql.="'".$array_programacion["TipoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoEntrega"]."',";
            $sql.="'".$array_programacion["CodSerTecEntregado"]."',";
            $sql.="'".$array_programacion["CantTotEntregada"]."',";
            $sql.="'".$array_programacion["EntTotal"]."',";
            $sql.="'".$array_programacion["CausaNoEntrega"]."',";
            $sql.="'".$array_programacion["FecEntrega"]."',";            
            $sql.="'".$array_programacion["NoLote"]."',";
            $sql.="'".$array_programacion["TipoIDRecibe"]."',";
            $sql.="'".$array_programacion["NoIDRecibe"]."',";            
            $sql.="'".$array_programacion["EstEntrega"]."',";
            $sql.="'".$array_programacion["FecAnulacion"]."',";
            $sql.="'".$array_programacion["CodigosEntrega"]."',";                      
            $sql.="'".$user_id."'";
            
            $sql.="),";
            
        }
        
        $sql=substr($sql, 0, -1);
        $this->Query($sql);
        
    }
    
    //insertar los datos a la tabla de entrega
    
    public function guardar_programacion_mipres($db,$datos_programacion,$user_id) {
        
        $Tabla="$db.mipres_programacion";
        
        $sql="REPLACE INTO "
                . "$Tabla (`ID`,`IDProgramacion`,`NoPrescripcion`,`TipoTec`,`ConTec`,`TipoIDPaciente`,"
                . "`NoIDPaciente`,`NoEntrega`,`FecMaxEnt`,`TipoIDSedeProv`,`NoIDSedeProv`,`CodSedeProv`,"
                . "`CodSerTecAEntregar`,`CantTotAEntregar`,"
                . "`FecProgramacion`,`EstProgramacion`,`FecAnulacion`,`user_id`)"
                . " VALUES ";
        
        foreach ($datos_programacion as $key => $array_programacion) {
            $sql.="(";
            $sql.="'".$array_programacion["ID"]."',";
            $sql.="'".$array_programacion["IDProgramacion"]."',";
            $sql.="'".$array_programacion["NoPrescripcion"]."',";
            $sql.="'".$array_programacion["TipoTec"]."',";
            $sql.="'".$array_programacion["ConTec"]."',";
            $sql.="'".$array_programacion["TipoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoEntrega"]."',";
            $sql.="'".$array_programacion["FecMaxEnt"]."',";
            $sql.="'".$array_programacion["TipoIDSedeProv"]."',";
            $sql.="'".$array_programacion["NoIDSedeProv"]."',";
            $sql.="'".$array_programacion["CodSedeProv"]."',";
            $sql.="'".$array_programacion["CodSerTecAEntregar"]."',";            
            $sql.="'".$array_programacion["CantTotAEntregar"]."',";
            $sql.="'".$array_programacion["FecProgramacion"]."',";
            $sql.="'".$array_programacion["EstProgramacion"]."',";            
            $sql.="'".$array_programacion["FecAnulacion"]."',";
                                
            $sql.="'".$user_id."'";
            
            $sql.="),";
            
        }
        
        $sql=substr($sql, 0, -1);
        $this->Query($sql);
        
    }
    
    public function reporte_entrega_mipres_x_id($datos_empresa,$ID,$token_consultas,$valor_facturado,$idUser) {
        $db=$datos_empresa["db"];
        
        $datos_mipres=$this->DevuelveValores("$db.mipres_entrega", "ID", $ID);
        
        $datos_servidor=$this->DevuelveValores("servidores", "ID", 2011);//aqui se encuentra la url para realizar el reporte de entrega
        $method="PUT";
        $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas;
        $Token=$datos_empresa["TokenAPIMipres"];
        $EstadoEntrega=1;
        if($datos_mipres["CausaNoEntrega"]>0){
            $EstadoEntrega=0;
            $valor_facturado=0;
        }
        $data=$this->json_reporte_entrega_mipres_x_id($ID, $EstadoEntrega, $datos_mipres["CausaNoEntrega"], $valor_facturado);
        //exit($url."<br>".$data);
        $respuesta=$this->callAPI($method, $url, $Token, $data);
        $datos_obtenidos= json_decode($respuesta,1);
        
        $fecha=date("Y-m-d H:i:s");
        if(isset($datos_obtenidos[0]["Id"])){
            $idReporteEntrega=$datos_obtenidos[0]["IdReporteEntrega"];
            $Datos["mipres_id"]=$ID;
            $Datos["IdReporteEntrega"]=$idReporteEntrega;
            $Datos["user_id"]=$idUser;
            $sql=$this->getSQLInsert("$db.mipres_registro_reporte_entrega", $Datos);            
            $this->Query($sql);            
            $retorno["OK"]=1;
            $retorno["IdReporteEntrega"]=$idReporteEntrega;
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        
        if(isset($datos_obtenidos["Id"])){
            $idReporteEntrega=$datos_obtenidos["IdReporteEntrega"];
            $Datos["mipres_id"]=$ID;
            $Datos["IdReporteEntrega"]=$idReporteEntrega;
            $Datos["user_id"]=$idUser;
            $sql=$this->getSQLInsert("$db.mipres_registro_reporte_entrega", $Datos);            
            $this->Query($sql);            
            $retorno["OK"]=1;
            $retorno["IdReporteEntrega"]=$idReporteEntrega;
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        
        return($datos_obtenidos);
        
    }
    
    public function json_reporte_entrega_mipres_x_id($ID,$EstadoEntrega,$CausaNoEntrega,$ValorEntregado) {
        if($CausaNoEntrega==0){
            $json='{
                "ID": '.$ID.',
                "EstadoEntrega": "'.$EstadoEntrega.'",
                "CausaNoEntrega": "'.$CausaNoEntrega.'",
                "ValorEntregado": "'.$ValorEntregado.'"

              }'; 
        }else{
            $json='{
                "ID": '.$ID.',                
                "CausaNoEntrega": "'.$CausaNoEntrega.'" 
              }'; 
        }
        return $json;
        
    }
    
    public function anular_reporte_entrega_mipres($datos_empresa,$reporte_entrega_id,$token_consultas,$idUser) {
        $db=$datos_empresa["db"];
        $datos_servidor=$this->DevuelveValores("servidores", "ID", 2012);//aqui se encuentra la url para anular un reporte de entrega
        $datos_mipres=$this->DevuelveValores("$db.mipres_reporte_entrega", "IDReporteEntrega", $reporte_entrega_id);
        $method="PUT";
        $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$reporte_entrega_id;
        $url= str_replace(" ", "", $url);
        
        $Token=$datos_empresa["TokenAPIMipres"];
        
        $data="";
        $respuesta=$this->callAPI($method, $url, $Token, $data);
        
        $datos_obtenidos= json_decode($respuesta,1);
        
        //print_r($datos_obtenidos);
        
        $fecha=date("Y-m-d H:i:s");
        if(isset($datos_obtenidos["Mensaje"])){
            //print("Entra");
            $sql="UPDATE mipres_registro_reporte_entrega SET user_id_anulacion='$idUser', fecha_anulacion='$fecha' WHERE IdReporteEntrega='$reporte_entrega_id'";
            $this->Query($sql);
            
            $retorno["OK"]=1;
            $retorno["Mensaje"]=$datos_obtenidos["Mensaje"];
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        if(isset($datos_obtenidos["Message"])){
            //print("Entra");
            $sql="UPDATE mipres_registro_reporte_entrega SET user_id_anulacion='$idUser', fecha_anulacion='$fecha' WHERE IdReporteEntrega='$reporte_entrega_id'";
            $this->Query($sql);
            
            $retorno["OK"]=1;
            $retorno["Mensaje"]=$datos_obtenidos["Message"];
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
        if(isset($datos_obtenidos[0]["Mensaje"])){
            //print("Entra");
            $sql="UPDATE mipres_registro_reporte_entrega SET user_id_anulacion='$idUser', fecha_anulacion='$fecha' WHERE IdReporteEntrega='$reporte_entrega_id'";
            $this->Query($sql);
            
            $retorno["OK"]=1;
            $retorno["Mensaje"]=$datos_obtenidos[0]["Mensaje"];
            $retorno["NoPrescripcion"]=$datos_mipres["NoPrescripcion"];
            return($retorno);
        }
                
    }
    
    
    //insertar los datos a la tabla de entrega
    
    public function guardar_reporte_entrega_mipres($db,$datos_programacion,$user_id) {
        
        $Tabla="$db.mipres_reporte_entrega";
        
        $sql="REPLACE INTO "
                . "$Tabla (`ID`,`IDReporteEntrega`,`NoPrescripcion`,`TipoTec`,`ConTec`,`TipoIDPaciente`,"
                . "`NoIDPaciente`,`NoEntrega`,`EstadoEntrega`,`CausaNoEntrega`,`ValorEntregado`,"
                . "`CodTecEntregado`,`CantTotEntregada`,`NoLote`,`FecEntrega`,`FecRepEntrega`,`EstRepEntrega`,"
                . "`FecAnulacion`,`user_id`)"
                . " VALUES ";
        
        foreach ($datos_programacion as $key => $array_programacion) {
            $sql.="(";
            $sql.="'".$array_programacion["ID"]."',";
            $sql.="'".$array_programacion["IDReporteEntrega"]."',";
            $sql.="'".$array_programacion["NoPrescripcion"]."',";
            $sql.="'".$array_programacion["TipoTec"]."',";
            $sql.="'".$array_programacion["ConTec"]."',";
            $sql.="'".$array_programacion["TipoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoIDPaciente"]."',";
            $sql.="'".$array_programacion["NoEntrega"]."',";
            $sql.="'".$array_programacion["EstadoEntrega"]."',";
            $sql.="'".$array_programacion["CausaNoEntrega"]."',";
            $sql.="'".$array_programacion["ValorEntregado"]."',";
            $sql.="'".$array_programacion["CodTecEntregado"]."',";
            $sql.="'".$array_programacion["CantTotEntregada"]."',";            
            $sql.="'".$array_programacion["NoLote"]."',";
            $sql.="'".$array_programacion["FecEntrega"]."',";
            $sql.="'".$array_programacion["FecRepEntrega"]."',";            
            
            $sql.="'".$array_programacion["EstRepEntrega"]."',";
            $sql.="'".$array_programacion["FecAnulacion"]."',";                      
            $sql.="'".$user_id."'";
            
            $sql.="),";
            
        }
        
        $sql=substr($sql, 0, -1);
        $this->Query($sql);
        
    }
    
    /**
     * Fin Clase
     */
}
