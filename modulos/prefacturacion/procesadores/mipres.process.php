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
                $obCon->guardar_direccionamiento_mipres($db, $datos_obtenidos, $idUser);
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
                $obCon->guardar_direccionamiento_mipres($db, $datos_obtenidos, $idUser);  
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
                $NoPrescripcion=$respuesta["NoPrescripcion"];
                exit("OK;Registro Programado con el id: $idProgramacion;$idProgramacion;$NoPrescripcion");
            }else{
                print_r($respuesta);
            }
            
        break;//Fin caso 5
        
        case 6: //Entregar Mipres
            
            //$empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            $mipres_id=$obCon->normalizar($_REQUEST["mipres_id"]);
            
            $mipres_cantidad_entregada=$obCon->normalizar($_REQUEST["mipres_cantidad_entregada"]);
            $mipres_fecha_real_entrega=$obCon->normalizar($_REQUEST["mipres_fecha_real_entrega"]);
            $mipres_tipo_documento_recibe=$obCon->normalizar($_REQUEST["mipres_tipo_documento_recibe"]);
            $mipres_identificacion_recibe=$obCon->normalizar($_REQUEST["mipres_identificacion_recibe"]);
            $mipres_parentesco=$obCon->normalizar($_REQUEST["mipres_parentesco"]);
            $mipres_nombre_recibe=$obCon->normalizar($_REQUEST["mipres_nombre_recibe"]);
            $mipres_causas_no_entrega=$obCon->normalizar($_REQUEST["mipres_causas_no_entrega"]);
            
            if(!is_numeric($mipres_cantidad_entregada) or $mipres_cantidad_entregada<1){
                exit("E1;El campo cantidad entregada debe ser un numero mayor a cero;mipres_cantidad_entregada");
            }
            if(!is_numeric($mipres_identificacion_recibe) or $mipres_identificacion_recibe<1){
                exit("E1;El campo cantidad identificacion de quien recibe debe ser un numero mayor a cero;mipres_identificacion_recibe");
            }
            
            if($mipres_fecha_real_entrega==''){
                exit("E1;El campo fecha de entrega no puede estar vacío;mipres_fecha_real_entrega");
            }
            
            if($mipres_tipo_documento_recibe==''){
                exit("E1;El campo tipo de documento de quien entrega no puede estar vacío;mipres_tipo_documento_recibe");
            }
            
            if($mipres_nombre_recibe==''){
                exit("E1;El campo nombre de quien recibe no puede estar vacío;mipres_nombre_recibe");
            }
            
            $respuesta=$obCon->entregar_mipres_x_id($datos_empresa, $mipres_id, $token_consultas,$mipres_cantidad_entregada,$mipres_identificacion_recibe,$mipres_fecha_real_entrega,$mipres_tipo_documento_recibe,$mipres_causas_no_entrega,$idUser);
            
            if(isset($respuesta["OK"])){
                $idEntrega=$respuesta["IdEntrega"];
                $NoPrescripcion=$respuesta["NoPrescripcion"];
                $sql="UPDATE $db.prefactura_paciente SET reponsable_tipo_documento='$mipres_tipo_documento_recibe',reponsable_identificacion='$mipres_identificacion_recibe',responsable_parentesco='$mipres_parentesco',responsable_nombre='$mipres_nombre_recibe'";
                $obCon->Query($sql);
                exit("OK;Registro Entregado con el id: $idEntrega;$idEntrega;$NoPrescripcion");
            }else{
                print_r($respuesta);
            }
            
        break;//Fin caso 6
        
        case 7: //Anular programacion Mipres
            
            //$empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            $programacion_id=$obCon->normalizar($_REQUEST["programacion_id"]);
            
            $respuesta=$obCon->anular_programacion_mipres($datos_empresa, $programacion_id, $token_consultas,$idUser);
            
            if(isset($respuesta["OK"])){
                $mensaje=$respuesta["Mensaje"];
                $NoPrescripcion=$respuesta["NoPrescripcion"];
                exit("OK;$mensaje;$NoPrescripcion");
            }else{
                print_r($respuesta);
            }
            
        break;//Fin caso 7
        
        case 8: //Anular entrega Mipres
            
            //$empresa_id=$obCon->normalizar($_REQUEST["empresa_id"]);
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            $entrega_id=$obCon->normalizar($_REQUEST["entrega_id"]);
            
            $respuesta=$obCon->anular_entrega_mipres($datos_empresa, $entrega_id, $token_consultas,$idUser);
            
            if(isset($respuesta["OK"])){
                $mensaje=$respuesta["Mensaje"];
                $NoPrescripcion=$respuesta["NoPrescripcion"];
                exit("OK;$mensaje;$NoPrescripcion");
            }else{
                print_r($respuesta);
            }
            
        break;//Fin caso 8
        
        case 9: //recibe el rango de fechas y lo valida para consultar el direccionamiento mipres
            
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
            
        break;//Fin caso 9 
        
        case 10://obtenga las entregas mi pres de acuerdo a un rango de fechas
                                
            
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2006);//aqui se encuentra la url para obtener las entregas por fecha
            
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
                $obCon->guardar_entrega_mipres($db, $datos_obtenidos, $idUser);  
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
            
        break;//Fin caso 10   
        
        case 11: //recibe el rango de fechas y lo valida para consultar la programacion mipres
            
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
            
        break;//Fin caso 11
        
        case 12://obtenga la programacion mipres de acuerdo a un rango de fechas
                                
            
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2007);//aqui se encuentra la url para obtener las entregas por fecha
            
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
            
        break;//Fin caso 12
        
        case 13://obtenga el direccionamiento mi pres de acuerdo por NoPrescripcion
                                
            
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2008);//aqui se encuentra la url para obtener las entregas por fecha
            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            $NoPrescripcion=$obCon->normalizar($_REQUEST["NoPrescripcion"]);
            
            $method="GET";
            $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$NoPrescripcion;
            $Token=$datos_empresa["TokenAPIMipres"];
            $data="";
            
            $respuesta=$obCon->callAPI($method, $url, $Token, $data);
            $datos_obtenidos= json_decode($respuesta,1);
            if(isset($datos_obtenidos[0]["ID"])){
                $obCon->guardar_direccionamiento_mipres($db, $datos_obtenidos, $idUser);  
            }
            
            exit("OK;Direccionamiento X Prescipcion $NoPrescripcion Guardado;$NoPrescripcion");
            
        break;//Fin caso 13
        
        case 14://obtenga la programacion mi pres de acuerdo al No. Prescripcion
                                
            
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2009);//aqui se encuentra la url para obtener la programacion por numero de prescipcion
            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            $NoPrescripcion=$obCon->normalizar($_REQUEST["NoPrescripcion"]);
            
            $method="GET";
            $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$NoPrescripcion;
            $Token=$datos_empresa["TokenAPIMipres"];
            $data="";
            
            $respuesta=$obCon->callAPI($method, $url, $Token, $data);
            $datos_obtenidos= json_decode($respuesta,1);
            if(isset($datos_obtenidos[0]["ID"])){
                $obCon->guardar_programacion_mipres($db, $datos_obtenidos, $idUser);  
            }
            
            exit("OK;Programacion X Prescipcion $NoPrescripcion Guardada;$NoPrescripcion");
            
        break;//Fin caso 14
        
        case 15://obtenga las entregas mi pres de acuerdo al No. Prescripcion
                                
            
            $empresa_id=1;
            $datos_empresa=$obCon->DevuelveValores("empresapro", "ID", $empresa_id);
            $db=$datos_empresa["db"];
            $datos_servidor=$obCon->DevuelveValores("servidores", "ID", 2010);//aqui se encuentra la url para obtener la programacion por numero de prescipcion
            
            $token_consultas=$obCon->normalizar($_REQUEST["token_consultas"]);
            $NoPrescripcion=$obCon->normalizar($_REQUEST["NoPrescripcion"]);
            
            $method="GET";
            $url=$datos_servidor["IP"].$datos_empresa["NIT"]."/".$token_consultas."/".$NoPrescripcion;
            $Token=$datos_empresa["TokenAPIMipres"];
            $data="";
            
            $respuesta=$obCon->callAPI($method, $url, $Token, $data);
            $datos_obtenidos= json_decode($respuesta,1);
            if(isset($datos_obtenidos[0]["ID"])){
                $obCon->guardar_entrega_mipres($db, $datos_obtenidos, $idUser);  
            }
            
            exit("OK;Entrega X Prescipcion $NoPrescripcion Guardada;$NoPrescripcion");
            
        break;//Fin caso 15
        
    }
    
    
          
}else{
    print("No se enviaron parametros");
}
?>