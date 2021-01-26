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
            
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2000);//aqui se encuentra la url para obtener el token
            $method="GET";
            $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$datos_empresa["TokenAPIMipres"];
            $Token=$datos_empresa["TokenAPIMipres"];
            $data="";
            $respuesta=$obCon->callAPI($method, $url, $Token, $data);
            print("OK;Token obtenido;$respuesta");
            
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
        
        case 3: //recibe el rango de fechas y lo valida para consultar el direccionamiento mipres
            
            $FechaInicialMiPres=$obCon->normalizar($_REQUEST["FechaInicialMiPres"]);
            $FechaFinalMiPres=$obCon->normalizar($_REQUEST["FechaFinalMiPres"]);
            
            if($FechaInicialMiPres==''){
                exit("E1;La fecha inicial no puede estar vacía;FechaInicialMiPres");
            }
            
            if($FechaFinalMiPres==''){
                exit("E1;La fecha final no puede estar vacía;FechaFinalMiPres");
            }
            
            
            $ValidarFechaInicialMiPres= strtotime($FechaInicialMiPres);
            $ValidarFechaFinalMiPres= strtotime($FechaFinalMiPres);
            
            if($ValidarFechaInicialMiPres>$ValidarFechaFinalMiPres){
                exit("E1;La fecha inicial no puede ser mayor a la final;FechaFinalMiPres");
            }
            
            $total_dias = $obCon->obtener_dias_diferencia_rango_fecha($FechaInicialMiPres, $FechaFinalMiPres);
            $total_dias=$total_dias+1;
            print("OK;Fechas Validadas;$FechaInicialMiPres;$FechaFinalMiPres;$FechaInicialMiPres;$total_dias");
            
        break;//Fin caso 3  
        
        case 4://obtenga el direccionamiento mi pres de acuerdo a un rango de fechas
                                
            
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2001);//aqui se encuentra la url para obtener el token
            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            
            $fecha_inicial=$obCon->normalizar($_REQUEST["fecha_inicial"]);
            $fecha_final=$obCon->normalizar($_REQUEST["fecha_final"]);
            $fecha_consulta=$obCon->normalizar($_REQUEST["fecha_consulta"]);
            $total_dias=$obCon->normalizar($_REQUEST["total_dias"]);
            if($fecha_consulta=='undefined' or $fecha_consulta==''){
                exit("E1;La fecha de consulta solicitada está vacía");
            }
            $method="GET";
            $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$fecha_consulta;
            $Token=$datos_empresa["TokenAPIMipres"];
            $data="";
            
            $respuesta=$obCon->callAPI($method, $url, $Token, $data);
            $datos_obtenidos= json_decode($respuesta,1);
            if(isset($datos_obtenidos[0]["ID"])){
                $obCon->guardar_programacion_mipres($db, $datos_obtenidos, $idUser);  
            }
            
            $proxima_consulta=$obCon->sumar_dias_fecha($fecha_consulta, 1);
            //print("Proxima consulta $proxima_consulta");
            $ValidarFechaInicialMiPres= strtotime($proxima_consulta);
            $ValidarFechaFinalMiPres= strtotime($fecha_final);
            if($ValidarFechaInicialMiPres>$ValidarFechaFinalMiPres){
                exit("FIN;Fueron Obtenidas todas las fechas solicitadas");
            }
            
            $difencia_proxima_consulta=$obCon->obtener_dias_diferencia_rango_fecha($proxima_consulta, $fecha_final);
            $difencia_proxima_consulta=$difencia_proxima_consulta+1;
            //print("<br>diferencia: ".$difencia_proxima_consulta);
            $porcentaje=round((100/$total_dias)*$difencia_proxima_consulta);
            $porcentaje=100-$porcentaje;
            //print("<br>porcentaje: ".$porcentaje);
            exit("OK;Datos de la fecha $fecha_consulta Guardados;$proxima_consulta;$porcentaje;$fecha_consulta consultado");
            
        break;//Fin caso 4    
        
        case 5: //Programar Mipres
            
            //$empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            $mipres_id=$obCon->normalizar($_REQUEST["mipres_id"]);
            
            $respuesta=$obCon->programar_mipres_x_id($datos_empresa, $mipres_id, $token_consultas, $idUser);
            
            if(isset($respuesta["OK"])){
                $idProgramacion=$respuesta["IdProgramacion"];
                exit("OK;Registro Programado con el id: $idProgramacion;$idProgramacion");
            }else{
                print_r($respuesta);
            }
            
        break;//Fin caso 5
        
           
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>