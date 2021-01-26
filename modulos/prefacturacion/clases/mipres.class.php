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
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
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
    
    //insertar los datos a la tabla de programacion
    
    public function guardar_programacion_mipres($db,$datos_programacion,$user_id) {
        
        $Tabla="$db.mipres_programacion";
        
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
    
    /**
     * Fin Clase
     */
}
