<?php
/* 
 * Clase donde se realizaran procesos de compras u otros modulos.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../php_conexion.php';
class Rips extends conexion{
    //Calculamos el numero de registros
    public function CalculeRegistros($FileName,$Separador) {
        $i=0;
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        
            
            $handle = fopen($FileName, "r");
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                
                $i++;
            }
            
            fclose($handle); 
        
        return $i;
    }
    //Rips de pagos AR
    public function InsertarRipsPagos($NombreArchivo,$Separador,$FechaCargue, $idUser,$destino, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        
            
            $handle = fopen("archivos/".$NombreArchivo, "r");
            $i=0;
            $z=0;
            $tab="salud_archivo_facturacion_mov_pagados_temp";
            $sql="INSERT INTO `$tab` (`id_pagados`, `num_factura`, `fecha_pago_factura`, `num_pago`, `valor_bruto_pagar`, `valor_descuento`, `valor_iva`, `valor_retefuente`, `valor_reteiva`, `valor_reteica`, `valor_otrasretenciones`, `valor_cruces`, `valor_anticipos`, `valor_pagado`, `nom_cargue`, `fecha_cargue`, `idUser`, `Soporte`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                $i++;
                if($data[1]<>""){
                    $FechaArchivo= explode("/", $data[1]);
                    if(count($FechaArchivo)>1){
                        $FechaFactura= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                    }else{
                        $FechaFactura=$data[1];
                    }

                 }else{
                    $FechaFactura="0000-00-00";
                 }
                    
                if($z==1){
                    
                    $sql.="('', '$data[0]', '$FechaFactura', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]','$NombreArchivo','$FechaCargue','$idUser','$destino'),";
                }
                $z=1;
                
                if($i==10000){
                    $sql=substr($sql, 0, -1);
                    $this->Query($sql);
                    $sql="INSERT INTO `$tab` (`id_pagados`, `num_factura`, `fecha_pago_factura`, `num_pago`, `valor_bruto_pagar`, `valor_descuento`, `valor_iva`, `valor_retefuente`, `valor_reteiva`, `valor_reteica`, `valor_otrasretenciones`, `valor_cruces`, `valor_anticipos`, `valor_pagado`, `nom_cargue`, `fecha_cargue`, `idUser`, `Soporte`) VALUES";
                    $i=0;
                }
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->update("salud_upload_control", "Analizado", 1, " WHERE nom_cargue='$NombreArchivo'");
        
        
    }
    
    //Rips de pagos formato adres
    public function InsertarRipsPagosAdres($NombreArchivo,$Separador,$FechaCargue, $idUser,$destino,$FechaGiro,$TipoGiro, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        
            
            $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            $z=0;
            $h=0;
            $tab="salud_pagos_temporal";
            $sql="INSERT INTO `$tab` (`id_temp_rips_generados`, `Proceso`, `CodigoEPS`, `NombreEPS`, `FormaContratacion`, `Departamento`, `Municipio`, `FechaFactura`, `PrefijoFactura`, `NumeroFactura`, `ValorGiro`, `FechaPago`, `NumeroGiro`, `nom_cargue`, `fecha_cargue`, `idUser`, `Soporte`,`numero_factura`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador,'"',"#")) !== FALSE) {
                //////Inserto los datos en la tabla  
                $i++;
                $h++;
                
                if($h>=1 and $data[0]<>''){
                    if($data[7]==''){
                        $NumeroFactura=$data[8];
                    }else{
                        $NumeroFactura=$data[7]."-".$data[8]; //Para pagos con separador
                    }
                    
                    $ValorGiro=str_replace(".","",$data[9]);
                    $Giro=str_replace(".","",$data[10]);
                    $Giro=str_replace(",00","",$Giro);
                    if($data[6]<>""){
                        $FechaArchivo= explode("/", $data[6]);
                        if(count($FechaArchivo)>1){
                            $FechaFactura= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                        }else{
                            $FechaFactura=$data[6];
                        }

                     }else{
                        $FechaFactura="0000-00-00";
                     }

                    if($z==1){

                        $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$FechaFactura', '$data[7]', '$data[8]', '$ValorGiro', '$FechaGiro', '$Giro', '$NombreArchivo','$FechaCargue','$idUser','$destino','$NumeroFactura'),";
                    }
                    $z=1;

                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `$tab` (`id_temp_rips_generados`, `Proceso`, `CodigoEPS`, `NombreEPS`, `FormaContratacion`, `Departamento`, `Municipio`, `FechaFactura`, `PrefijoFactura`, `NumeroFactura`, `ValorGiro`, `FechaPago`, `NumeroGiro`, `nom_cargue`, `fecha_cargue`, `idUser`, `Soporte`,`numero_factura`) VALUES";
                        $i=0;
                    }
                }
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->RegistreUpload($NombreArchivo, $FechaCargue, $idUser, "");
            //$this->update("salud_upload_control", "Analizado", 1, " WHERE nom_cargue='$NombreArchivo'");
               
    }
    
    // insertar Rips de consultas generados a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsConsultas($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            
            $sql="INSERT INTO `salud_archivo_consultas_temp` "
              . "(`id_consultas`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `fecha_consulta`, `num_autorizacion`, `cod_consulta`, `finalidad_consulta`, `causa_externa`, `cod_diagnostico_principal`, `cod_diagnostico_relacionado1`, `cod_diagnostico_relacionado2`, `cod_diagnostico_relacionado3`, `tipo_diagn_principal`, `valor_consulta`, `valor_cuota_moderadora`, `valor_neto_pagar_consulta`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`,`idUser`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                    
                  $i++;  
                    if($data[4]<>""){
                       $FechaArchivo= explode("/", $data[4]);
                       if(count($FechaArchivo)>1){
                           $FechaConsulta= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{
                           $FechaConsulta=$data[4];
                       }
                       
                    }else{
                       $FechaConsulta="0000-00-00";
                    }
                    
                    
                    
                    $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$FechaConsulta', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]', '$data[13]', '$data[14]', '$data[15]', '$data[16]','$TipoNegociacion','$NombreArchivo','$FechaCargue','$idUser'),";
                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_archivo_consultas_temp` "
                            . "(`id_consultas`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `fecha_consulta`, `num_autorizacion`, `cod_consulta`, `finalidad_consulta`, `causa_externa`, `cod_diagnostico_principal`, `cod_diagnostico_relacionado1`, `cod_diagnostico_relacionado2`, `cod_diagnostico_relacionado3`, `tipo_diagn_principal`, `valor_consulta`, `valor_cuota_moderadora`, `valor_neto_pagar_consulta`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`,`idUser`) VALUES";
                        $i=0;
                    }
                
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
                
    }
    
    // insertar Rips de urgencias generados a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsUrgencias($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            
            $sql="INSERT INTO `salud_archivo_urgencias_temp` (`id_urgencias`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `fecha_ingreso`, `hora_ingreso`, `num_autorizacion`, `causa_externa`, `diagnostico_salida`, "
                    . "`diagn_relac1_salida`, `diagn_relac2_salida`, `diagn_relac3_salida`, `destino_usuario`, `estado_salida`, `causa_muerte`, `fecha_salida`, `hora_salida`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES ";
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                    
                  $i++;  
                    if($data[4]<>""){
                       $FechaArchivo= explode("/", $data[4]);
                       if(count($FechaArchivo)>1){
                           $FechaIngreso= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{
                           $FechaIngreso=$data[4];
                       }
                       
                    }else{
                       $FechaIngreso="0000-00-00";
                    }
                    
                    if($data[15]<>""){
                       $FechaArchivo= explode("/", $data[15]);
                       if(count($FechaArchivo)>1){
                           $FechaSalida= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{
                           $FechaSalida=$data[15];
                       }
                       
                    }else{
                       $FechaSalida="0000-00-00";
                    }
                    
                    
                    
                    $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$FechaIngreso', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]', '$data[13]', '$data[14]', '$FechaSalida', '$data[16]','$TipoNegociacion','$NombreArchivo','$FechaCargue','$idUser'),";
                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_archivo_urgencias_temp` (`id_urgencias`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `fecha_ingreso`, `hora_ingreso`, `num_autorizacion`, `causa_externa`, `diagnostico_salida`, "
                                . "`diagn_relac1_salida`, `diagn_relac2_salida`, `diagn_relac3_salida`, `destino_usuario`, `estado_salida`, `causa_muerte`, `fecha_salida`, `hora_salida`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES ";
                         $i=0;
                    }
                
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
                
    }
    
    // insertar Rips de consultas generados a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsHospitalizaciones($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            
            $sql="INSERT INTO `salud_archivo_hospitalizaciones_temp` "
              . "(`id_hospitalizacion`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `via_ingreso`, `fecha_ingreso_hospi`, `hora_ingreso`, `num_autorizacion`, `causa_externa`, `diagn_princ_ingreso`, `diagn_princ_egreso`, `diagn_relac1_egreso`, `diagn_relac2_egreso`, `diagn_relac3_egreso`, `diagn_complicacion`, `estado_salida_hospi`, `diagn_causa_muerte`, `fecha_salida_hospi`, `hora_salida_hospi`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                    $i++;
                    //Convertimos la fecha de ingreso en formato 0000-00-00
                    if($data[5]<>""){
                       $FechaArchivo= explode("/", $data[5]);
                       if(count($FechaArchivo)>1){
                           $FechaIngreso= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{
                           $FechaIngreso=$data[5];
                       }
                       
                    }else{
                       $FechaIngreso="0000-00-00";
                    }
                    
                    if($data[17]<>""){
                       $FechaArchivo= explode("/", $data[17]);
                       if(count($FechaArchivo)>1){ //Si tiene formato dd/mm/año
                           $FechaSalida= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{ //Si no tiene ese formato se espera que tenga el 0000-00-00
                           $FechaSalida=$data[17];
                       }
                       
                    }else{
                       $FechaSalida="0000-00-00";
                    }
                    
                    
                    
                    $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$FechaIngreso', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]', '$data[13]', '$data[14]', '$data[15]', '$data[16]','$FechaSalida', '$data[18]','$TipoNegociacion','$NombreArchivo','$FechaCargue','$idUser'),";
                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_archivo_hospitalizaciones_temp` "
                        . "(`id_hospitalizacion`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `via_ingreso`, `fecha_ingreso_hospi`, `hora_ingreso`, `num_autorizacion`, `causa_externa`, `diagn_princ_ingreso`, `diagn_princ_egreso`, `diagn_relac1_egreso`, `diagn_relac2_egreso`, `diagn_relac3_egreso`, `diagn_complicacion`, `estado_salida_hospi`, `diagn_causa_muerte`, `fecha_salida_hospi`, `hora_salida_hospi`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
                    $i=0;
                    }
                
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            $sql="";
            fclose($handle);
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
            
        
    }
    
       
    // insertar Rips de procedimientos generados a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsProcedimientos($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            
            $sql="INSERT INTO `salud_archivo_procedimientos_temp` "
              . "(`id_procedimiento`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `fecha_procedimiento`, `num_autorizacion`, `cod_procedimiento`, `ambito_reali_procedimiento`, `finalidad_procedimiento`, `personal_atiende`, `cod_diagn_princ_procedimiento`, `cod_diagn_relac_procedimiento`, `complicaciones`, `realizacion_quirurgico`, `valor_procedimiento`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                    
                    
                    if($data[4]<>""){
                       $FechaArchivo= explode("/", $data[4]);
                       if(count($FechaArchivo)>1){
                           $FechaConsulta= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{
                           $FechaConsulta=$data[4];
                       }
                       
                    }else{
                       $FechaConsulta="0000-00-00";
                    }
                    
                    
                    
                    $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$FechaConsulta', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]', '$data[13]','$data[14]','$TipoNegociacion','$NombreArchivo','$FechaCargue','$idUser'),";
                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_archivo_procedimientos_temp` "
                        . "(`id_procedimiento`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `fecha_procedimiento`, `num_autorizacion`, `cod_procedimiento`, `ambito_reali_procedimiento`, `finalidad_procedimiento`, `personal_atiende`, `cod_diagn_princ_procedimiento`, `cod_diagn_relac_procedimiento`, `complicaciones`, `realizacion_quirurgico`, `valor_procedimiento`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
                        $i=0;
                    }
                    $i++;
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
            
        
    }
    
    // insertar Rips de Otros Servicios generados a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsOtrosServicios($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            
            $sql="INSERT INTO `salud_archivo_otros_servicios_temp` "
              . "(`id_otro_servicios`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `num_autorizacion`, `tipo_servicio`, `cod_servicio`, `nom_servicio`, `cantidad`, `valor_unit_material`, `valor_total_material`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                    
                $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]','$TipoNegociacion','$NombreArchivo','$FechaCargue','$idUser'),";
                if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_archivo_otros_servicios_temp` "
                         . "(`id_otro_servicios`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `num_autorizacion`, `tipo_servicio`, `cod_servicio`, `nom_servicio`, `cantidad`, `valor_unit_material`, `valor_total_material`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
                        $i=0;
                    }
                    $i++;
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
        
        
    }
    
    // insertar Rips de usuarios generados a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsUsuarios($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            
            $sql="INSERT INTO `salud_archivo_usuarios_temp` "
              . "(`id_usuarios_salud`, `tipo_ident_usuario`, `num_ident_usuario`, `cod_ident_adm_pb`, `tipo_usuario`, `primer_ape_usuario`, `segundo_ape_usuario`, `primer_nom_usuario`, `segundo_nom_usuario`, `edad`, `unidad_medida_edad`, `sexo`, `cod_depa_residencial`, `cod_muni_residencial`, `zona_residencial`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                    
                $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]','$data[11]','$data[12]','$data[13]','$NombreArchivo','$FechaCargue','$idUser'),";
                if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_archivo_usuarios_temp` "
                            . "(`id_usuarios_salud`, `tipo_ident_usuario`, `num_ident_usuario`, `cod_ident_adm_pb`, `tipo_usuario`, `primer_ape_usuario`, `segundo_ape_usuario`, `primer_nom_usuario`, `segundo_nom_usuario`, `edad`, `unidad_medida_edad`, `sexo`, `cod_depa_residencial`, `cod_muni_residencial`, `zona_residencial`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
                          $i=0;
                    }
                    $i++;
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
        
        
    }
    
    // insertar Rips de facturas generadas a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsFacturacionGenerada($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser,$destino,$FechaRadicado,$NumeroRadicado,$Escenario,$CuentaGlobal,$CuentaRIPS,$idEPS,$CuentaContable, $Vector) {
        // si se recibe el archivo
        $DatosEPS=$this->DevuelveValores("salud_eps", "cod_pagador_min", $idEPS);
        
        $Dias=$DatosEPS["dias_convenio"];
        
        $sql="SELECT CodigoPrestadora FROM empresapro";
        $consulta=$this->Query($sql);
        $i=0;
        while ($DatosIPS=$this->FetchArray($consulta)){
            $CodigosIPS[$i]= $DatosIPS["CodigoPrestadora"];
            $i++;
        }
        
        //$CodigoIPS=$DatosIPS["CodigoPrestadora"];
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        if (!file_exists("../archivos/".$NombreArchivo)) {
           exit('Archivo no exsite'); 
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            $line=0;
            $Mensaje["Errores"]=0;
            $Mensaje["LineasError"]="";
            $Mensaje["PosError"]="";
            $sql="INSERT INTO `salud_rips_facturas_generadas_temp` "
              . "(`id_temp_rips_generados`, `cod_prest_servicio`, `razon_social`, `tipo_ident_prest_servicio`, `num_ident_prest_servicio`, `num_factura`, `fecha_factura`, "
              . "`fecha_inicio`, `fecha_final`, `cod_enti_administradora`, `nom_enti_administradora`, `num_contrato`, `plan_beneficios`, `num_poliza`, `valor_total_pago`, "
                    . "`valor_comision`, `valor_descuentos`, `valor_neto_pagar`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`,"
                    . "`fecha_radicado`,`numero_radicado`,`Soporte`,`Escenario`,`CuentaGlobal`,`CuentaRIPS`,`LineaArchivo`,`dias_pactados`,`CuentaContable`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                $line++;
                $ErrorXIPS=1;
                foreach ($CodigosIPS as $CodigoIPS){
                    if($data[0]==$CodigoIPS){
                        $ErrorXIPS=0;
                    }
                    
                }
                if($ErrorXIPS==1){
                    if($Mensaje["Errores"]<10){
                        print("<h4 style='color:red'>El código de IPS: ".$data[0]." relacionado en la linea $line del AF, No existe en la Base de datos</h4><br>");
                    }
                    $Mensaje["Errores"]++;
                    $Mensaje["LineasError"]=$Mensaje["LineasError"].",".$line;
                    $Mensaje["PosError"]=1;
                    if($Mensaje["Errores"]>50){
                        $Mensaje["LineasError"]="N";

                    }

                }
                
                if($data[8]<>$idEPS){
                    if($Mensaje["Errores"]<10){
                        print("<h4 style='color:red'>El código de la EPS: ".$data[8]." relacionado en la linea $line del AF, No Corresponde a la EPS Seleccionada</h4><br>");
                    }
                    $Mensaje["Errores"]++;
                    $Mensaje["LineasError"]=$Mensaje["LineasError"].",".$line;
                    $Mensaje["PosError"]=9;
                    if($Mensaje["Errores"]>50){
                        $Mensaje["LineasError"]="N";
                        
                    }
                    
                }
                $i++; 
                
                    //Convertimos la fecha de ingreso en formato 0000-00-00
                    if($data[5]<>""){
                       $FechaArchivo= explode("/", $data[5]);
                       if(count($FechaArchivo)>1){
                           $FechaFactura= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{
                           $FechaFactura=$data[5];
                       }
                       
                    }else{
                       $FechaFactura="0000-00-00";
                    }
                    
                    if($data[6]<>""){
                       $FechaArchivo= explode("/", $data[6]);
                       if(count($FechaArchivo)>1){ //Si tiene formato dd/mm/año
                           $FechaInicio= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{ //Si no tiene ese formato se espera que tenga el 0000-00-00
                           $FechaInicio=$data[6];
                       }
                       
                    }else{
                       $FechaInicio="0000-00-00";
                    }
                    
                    if($data[7]<>""){
                       $FechaArchivo= explode("/", $data[7]);
                       if(count($FechaArchivo)>1){ //Si tiene formato dd/mm/año
                           $FechaFinal= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{ //Si no tiene ese formato se espera que tenga el 0000-00-00
                           $FechaFinal=$data[7];
                       }
                       
                    }else{
                       $FechaFinal="0000-00-00";
                    }
                    
                    $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$FechaFactura', "
                            . "'$FechaInicio', '$FechaFinal', '$data[8]', '$data[9]', '$data[10]', '$data[11]', "
                            . "'$data[12]', '$data[13]', '$data[14]', '$data[15]', '$data[16]', '$TipoNegociacion',"
                            . "'$NombreArchivo','$FechaCargue','$idUser','$FechaRadicado','$NumeroRadicado','$destino',"
                            . "'$Escenario','$CuentaGlobal','$CuentaRIPS','$line','$Dias','$CuentaContable'),";     
                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_rips_facturas_generadas_temp` "
                        . "(`id_temp_rips_generados`, `cod_prest_servicio`, `razon_social`, `tipo_ident_prest_servicio`, `num_ident_prest_servicio`, `num_factura`, `fecha_factura`, "
                        . "`fecha_inicio`, `fecha_final`, `cod_enti_administradora`, `nom_enti_administradora`, `num_contrato`, `plan_beneficios`, `num_poliza`, `valor_total_pago`, "
                              . "`valor_comision`, `valor_descuentos`, `valor_neto_pagar`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`,"
                              . "`fecha_radicado`,`numero_radicado`,`Soporte`,`Escenario`,`CuentaGlobal`,`CuentaRIPS`,`LineaArchivo`,`dias_pactados`,`CuentaContable`) VALUES";
                       $i=0;
                    }
                
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            $sql="";
            fclose($handle); 
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
            return($Mensaje);
    }
     
    // insertar Rips de facturas generadas a tabla temporal, despues por medio de un trigger se llevará a la general
    public function InsertarRipsMedicamentos($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        
                       
        $handle = fopen("../archivos/".$NombreArchivo, "r");
        $i=0;

        $sql="INSERT INTO `salud_archivo_medicamentos_temp` "
              . "(`id_medicamentos`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `num_autorizacion`, `cod_medicamento`,  `tipo_medicamento`, `forma_farmaceutica`, `nom_medicamento`,`concentracion_medic`, `um_medicamento`, `num_und_medic`, `valor_unit_medic`, `valor_total_medic`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
            
       while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
            $i++;
            $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]', '$data[13]','$TipoNegociacion','$NombreArchivo','$FechaCargue','$idUser'),";
                
            if($i==10000){
                $sql=substr($sql, 0, -1);
                $this->Query($sql);
                $sql="INSERT INTO `salud_archivo_medicamentos_temp` "
                . "(`id_medicamentos`, `num_factura`, `cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, `num_autorizacion`, `cod_medicamento`,  `tipo_medicamento`, `forma_farmaceutica`, `nom_medicamento`,`concentracion_medic`, `um_medicamento`, `num_und_medic`, `valor_unit_medic`, `valor_total_medic`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES";
                $i=0;
            }

        }
        $sql=substr($sql, 0, -1);
        $this->Query($sql);
        $sql="";
        fclose($handle); 
        $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
        
    }
     // archivo de nacidos
    public function InsertarRipsNacidos($NombreArchivo,$TipoNegociacion,$Separador,$FechaCargue, $idUser, $Vector) {
        // si se recibe el archivo
        
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $handle = fopen("../archivos/".$NombreArchivo, "r");
            $i=0;
            
            $sql="INSERT INTO `salud_archivo_nacidos_temp` (`id_recien_nacido`, `num_factura`, "
                    . "`cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, "
                    . "`fecha_nacimiento_rn`, `hora_nacimiento_rc`, `edad_gestacional`, "
                    . "`control_prenatal`, `sexo_rc`, `peso_rc`, `diagn_rc`, `causa_muerte_rc`, "
                    . "`fecha_muerte_rc`, `hora_muerte_rc`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, "
                    . "`idUser`) VALUES ";
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                $i++;    
                    //Convertimos la fecha de ingreso en formato 0000-00-00
                    if($data[4]<>""){
                       $FechaArchivo= explode("/", $data[4]);
                       if(count($FechaArchivo)>1){
                           $FechaNacimiento= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{
                           $FechaNacimiento=$data[4];
                       }
                       
                    }else{
                       $FechaNacimiento="0000-00-00";
                    }
                    
                    if($data[12]<>""){
                       $FechaArchivo= explode("/", $data[12]);
                       if(count($FechaArchivo)>1){ //Si tiene formato dd/mm/año
                           $FechaMuerte= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                       }else{ //Si no tiene ese formato se espera que tenga el 0000-00-00
                           $FechaMuerte=$data[12];
                       }
                       
                    }else{
                       $FechaMuerte="0000-00-00";
                    }
                    
                                       
                    $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$FechaNacimiento', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$FechaMuerte', '$data[13]',  '$TipoNegociacion','$NombreArchivo','$FechaCargue','$idUser'),";
                    
                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `salud_archivo_nacidos_temp` (`id_recien_nacido`, `num_factura`, "
                            . "`cod_prest_servicio`, `tipo_ident_usuario`, `num_ident_usuario`, "
                            . "`fecha_nacimiento_rn`, `hora_nacimiento_rc`, `edad_gestacional`, "
                            . "`control_prenatal`, `sexo_rc`, `peso_rc`, `diagn_rc`, `causa_muerte_rc`, "
                            . "`fecha_muerte_rc`, `hora_muerte_rc`, `tipo_negociacion`, `nom_cargue`, `fecha_cargue`, "
                            . "`idUser`) VALUES ";
                        $i=0;
                    }
                
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            $sql="";
            fclose($handle); 
            $this->update("salud_upload_control", "CargadoTemp", 1, " WHERE nom_cargue='$NombreArchivo'");
        
    }
    //Actualiza Autoincrementables
    public function AjusteAutoIncrement($tabla,$id,$Vector) {
        $Increment=$this->ObtenerMAX($tabla, $id, 1, "");
        $Increment=$Increment+1;
        $sql="ALTER TABLE $tabla AUTO_INCREMENT=$Increment";
        $this->Query($sql);
    }
    //Actualiza todos los autoincrementables de las tablas que se utilizan (Esto se debe a que en la importacion
    // el autoincremental no corresponde al ultimo registro
    public function ModifiqueAutoIncrementables($Vector) {
        $this->AjusteAutoIncrement("salud_archivo_consultas", "id_consultas", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_hospitalizaciones", "id_hospitalizacion", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_usuarios", "id_usuarios_salud", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_medicamentos", "id_medicamentos", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_procedimientos", "id_procedimiento", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_otros_servicios", "id_otro_servicios", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_facturacion_mov_generados", "id_fac_mov_generados", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_nacidos", "id_recien_nacido", $Vector);
        $this->AjusteAutoIncrement("salud_archivo_urgencias", "id_urgencias", $Vector);
    }
    //Registra los nombres de los archivos subidos
    public function RegistreUpload($NombreArchivo,$Fecha,$idUser,$Vector) {
        $sql="INSERT INTO `salud_upload_control` (`id_upload_control`, `nom_cargue`, `fecha_cargue`, `idUser`) VALUES "
                . "(NULL, '$NombreArchivo', '$Fecha', '$idUser');";
        $this->Query($sql);
    }
    //Copia los registros de la tabla temporal consultas que no existan en la principal y los inserta
    public function AnaliceInsercionConsultas($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_consultas` "
                . "SELECT * FROM `salud_archivo_consultas_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_consultas` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
    }
    //Copia los registros de la tabla temporal consultas que no existan en la principal y los inserta
    public function AnaliceInsercionHospitalizaciones($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_hospitalizaciones` "
                . "SELECT * FROM `salud_archivo_hospitalizaciones_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_hospitalizaciones` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
    }
    
    //Copia los registros de la tabla temporal urgencias que no existan en la principal y los inserta
    public function AnaliceInsercionUrgencias($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_urgencias` "
                . "SELECT * FROM `salud_archivo_urgencias_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_urgencias` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
    }
    //Copia los registros de la tabla temporal Medicamentos que no existan en la principal y los inserta
    public function AnaliceInsercionMedicamentos($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_medicamentos` "
                . "SELECT * FROM `salud_archivo_medicamentos_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_medicamentos` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
    }
    
    //Copia los registros de la tabla temporal Procedimientos que no existan en la principal y los inserta
    public function AnaliceInsercionProcedimientos($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_procedimientos` "
                . "SELECT * FROM `salud_archivo_procedimientos_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_procedimientos` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
    }
    
    //Copia los registros de la tabla temporal Otros Servicios que no existan en la principal y los inserta
    public function AnaliceInsercionOtrosServicios($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_otros_servicios` "
                . "SELECT * FROM `salud_archivo_otros_servicios_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_otros_servicios` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
    }
    
    //Copia los registros de la tabla temporal usuarios que no existan en la principal y los inserta
    public function AnaliceInsercionUsuarios($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_usuarios` "
                . "SELECT * FROM `salud_archivo_usuarios_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_usuarios` as t2 "
                . "WHERE t1.`num_ident_usuario`=t2.`num_ident_usuario` "
                . "AND t1.`primer_ape_usuario`=t2.`primer_ape_usuario` "
                . "AND t1.`segundo_ape_usuario`=t2.`segundo_ape_usuario` "
                . "AND t1.`primer_nom_usuario`=t2.`primer_nom_usuario` "
                . "AND t1.`segundo_nom_usuario`=t2.`segundo_nom_usuario`);";
        
        $this->Query($sql);
    }
    
    //Copia los registros de la tabla temporal facturas que no existan en la principal y los inserta
    public function AnaliceInsercionFacturasGeneradas($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="SELECT * FROM salud_rips_facturas_generadas_temp LIMIT 1";
        $Consulta= $this->Query($sql);
        $DatosRips=$this->FetchAssoc($Consulta);
        
        if($DatosRips["tipo_negociacion"]=="evento"){
            $TablaCopiar="salud_archivo_facturacion_mov_generados";
        }else{
            $TablaCopiar="salud_archivo_af_capita";
        }
        if($TablaCopiar=="salud_archivo_af_capita"){
            if(!isset($Vector["ValorCapita"])){
                return("E1");
            }
            if(!isset($Vector["CuentaGlobal"])){
                return("E2");
            }
            unset($DatosRips["id_temp_rips_generados"]);
            unset($DatosRips["LineaArchivo"]);
            $DatosRips["valor_total_pago"]=$Vector["ValorCapita"];
            $DatosRips["valor_neto_pagar"]=$Vector["ValorCapita"];
            $DatosRips["num_factura"]=$Vector["CuentaGlobal"];
            $sql=$this->getSQLInsert("salud_archivo_facturacion_mov_generados", $DatosRips);
            $this->Query($sql);
        }
        $sql="INSERT INTO `$TablaCopiar` 
            (`cod_prest_servicio`,`razon_social`,`tipo_ident_prest_servicio`,`num_ident_prest_servicio`,
            `num_factura`,`fecha_factura`,`fecha_inicio`,`fecha_final`,`cod_enti_administradora`,
            `nom_enti_administradora`,`num_contrato`,`plan_beneficios`,`num_poliza`,`valor_total_pago`,
            `valor_comision`,`valor_descuentos`,`valor_neto_pagar`,`tipo_negociacion`,`nom_cargue`,`fecha_cargue`,`idUser`,
            `fecha_radicado`,`numero_radicado`,`Soporte`,`Escenario`,`CuentaGlobal`,`CuentaRIPS`,`dias_pactados`,`estado`,`eps_radicacion`,`CuentaContable`)
            SELECT `cod_prest_servicio`,`razon_social`,`tipo_ident_prest_servicio`,`num_ident_prest_servicio`,`num_factura`,
            `fecha_factura`,`fecha_inicio`,`fecha_final`,`cod_enti_administradora`,`nom_enti_administradora`,`num_contrato`,
            `plan_beneficios`,`num_poliza`,`valor_total_pago`,`valor_comision`,`valor_descuentos`,`valor_neto_pagar`,
            `tipo_negociacion`,`nom_cargue`,`fecha_cargue`,`idUser`,
            `fecha_radicado`,`numero_radicado`,`Soporte`,`Escenario`,`CuentaGlobal`,`CuentaRIPS`,`dias_pactados`,'RADICADO',`cod_enti_administradora` AS eps_radicacion,`CuentaContable`
            FROM salud_rips_facturas_generadas_temp as t1 WHERE NOT EXISTS  
            (SELECT 1 FROM `$TablaCopiar` as t2 
            WHERE t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
        
        
        
    }
    //Copia los registros de la tabla temporal Otros Servicios que no existan en la principal y los inserta
    public function AnaliceInsercionFacturasPagadas($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_facturacion_mov_pagados` "
                . "SELECT * FROM `salud_archivo_facturacion_mov_pagados_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_facturacion_mov_pagados` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
        $this->AjusteAutoIncrement("salud_archivo_facturacion_mov_pagados", "id_pagados", $Vector);
    }
    //Copia los registros de la tabla temporal facturas que no existan en la principal y los inserta
    public function AnaliceInsercionFacturasPagadasAdres($Vector,$SubeDesde='Subsidiado') {
        
        $sql="INSERT INTO `salud_archivo_facturacion_mov_pagados` 
            (`num_factura`,`fecha_pago_factura`,`num_pago`,`valor_pagado`,
            `nom_cargue`,`fecha_cargue`,`Soporte`,`idUser`,`idEPS`,`nom_enti_administradora`,`tipo_negociacion`,`Proceso`,`SubeDesde`)
            SELECT `numero_factura`,`FechaPago`,`NumeroGiro`,`ValorGiro`,`nom_cargue`,
            `fecha_cargue`,`Soporte`,`idUser`,`CodigoEPS`,`NombreEPS`,`FormaContratacion`,`Proceso`,'$SubeDesde'
            FROM salud_pagos_temporal as t1 WHERE NOT EXISTS  
            (SELECT 1 FROM `salud_archivo_facturacion_mov_pagados` as t2  
            WHERE t1.`numero_factura`=t2.`num_factura` AND t1.Proceso=t2.Proceso AND t1.FechaPago=t2.fecha_pago_factura); ";
        
        $this->Query($sql);
    }
    //Copia los registros de la tabla temporal nacidos que no existan en la principal y los inserta
    public function AnaliceInsercionNacidos($Vector) {
        //Secuencia SQL que selecciona los usuarios que no estan creados de la tabla temporal y los inserta en la principal
        $sql="INSERT INTO `salud_archivo_nacidos` "
                . "SELECT * FROM `salud_archivo_nacidos_temp` as t1 "
                . "WHERE NOT EXISTS "
                . "(SELECT 1 FROM `salud_archivo_nacidos` as t2 "
                . "WHERE t1.`nom_cargue`=t2.`nom_cargue` AND t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
    }
    //Actualiza el estado de las facturas pagas con el mismo valor
    public function EncuentreFacturasPagadas($Vector) {
        $sql="UPDATE salud_archivo_facturacion_mov_generados t1 "
                . "INNER JOIN salud_archivo_facturacion_mov_pagados t2 "
                . "SET t1.estado='PAGADA' "
                . "WHERE t1.`num_factura`=t2.`num_factura` AND "
                . "(SELECT SUM(`valor_pagado`) FROM salud_archivo_facturacion_mov_pagados "
                . "WHERE `num_factura`=t1.`num_factura`)>=t1.`valor_neto_pagar` ";
        $this->Query($sql);
    }
    //Actualiza el estado de las facturas pagas con diferencia
    public function EncuentreFacturasPagadasConDiferencia($Vector) {
        $sql="UPDATE salud_archivo_facturacion_mov_generados t1 INNER JOIN salud_archivo_facturacion_mov_pagados t2 "
                . "SET t1.estado='DIFERENCIA' "
                . "WHERE t1.`num_factura`=t2.`num_factura` AND t2.`valor_pagado`<t1.`valor_neto_pagar` ";
        $this->Query($sql);
    }
    
    //Verificar archivos subidos por zip
    public function VerificarZip($NombreArchivo,$idUser,$Vector) {
        $zip = new ZipArchive;
        $Fecha=date("Y-m-d H:i:s");
        $Archivos="";
        if ($zip->open($NombreArchivo) === TRUE){
            $zip->extractTo('../archivos/'); //función para extraer el ZIP, le pasamos la ruta donde queremos que nos descomprima
            for($i = 0; $i < $zip->numFiles; $i++){
                //obtenemos ruta que tendrán los documentos cuando los descomprimamos
                $nombresFichZIP['tmp_name'][$i] = '../archivos/'.$zip->getNameIndex($i);
                //obtenemos nombre del fichero
                $nombresFichZIP['name'][$i] = $zip->getNameIndex($i);
                $Archivos[$i]=$nombresFichZIP['name'][$i];
                $DatosArchivos= $this->DevuelveValores("salud_upload_control", "nom_cargue", $nombresFichZIP['name'][$i]);
                
                if($DatosArchivos["nom_cargue"]==''){
                    $this->RegistreUpload($nombresFichZIP['name'][$i], $Fecha, $idUser, "");
                }
               
            }
            

            $zip->close();
	}else{
            exit("No se recibio .zip");
        }
        
    }
    
    //Subir Circular 030 inicial
    public function SubirCircular030Inicial($NombreArchivo,$Separador,$FechaCargue, $idUser, $Vector) {
         // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        
            $this->RegistreUpload($NombreArchivo, $FechaCargue, $idUser, "");
            $handle = fopen("ArchivosTemporales/".$NombreArchivo, "r");
            $CuentaContable=0;
            $i=0;
            $z=0;
            $tab="salud_circular030_inicial";
            $sql="INSERT INTO `$tab` (`ID`, `TipoRegistro`, `Consecutivo`, `tipo_ident_erp`, `num_ident_erp`, `razon_social`, `tipo_ident_ips`, `num_ident_ips`, `tipo_cobro`, `pref_factura`, `num_factura`, `indic_act_fact`, `valor_factura`, `fecha_factura`, `fecha_radicado`, `fecha_devolucion`, `valor_total_pagos`, `valor_glosa_acept`, `glosa_respondida`, `saldo_factura`, `cobro_juridico`, `etapa_proceso`, `numero_factura`,`fecha_cargue`, `idUser`, `CuentaContable`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador)) !== FALSE) {
                //////Inserto los datos en la tabla  
                $i++;
                //$NumFactura=$data[8]."-".$data[9];   
                
                if(isset($data[21])){
                    $CuentaContable=$data[21];
                }
                if($z==1){
                    $NumFactura=$data[9]; 
                    $sql.="('', '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', '$data[6]', '$data[7]', '$data[8]', '$data[9]', '$data[10]', '$data[11]', '$data[12]','$data[13]','$data[14]','$data[15]','$data[16]','$data[17]','$data[18]','$data[19]','$data[20]','$NumFactura','$FechaCargue','$idUser','$CuentaContable'),";
                }
                $z=1;
                
                if($i==10000){
                    $sql=substr($sql, 0, -1);
                    $this->Query($sql);
                    $sql="INSERT INTO `$tab` (`ID`, `TipoRegistro`, `Consecutivo`, `tipo_ident_erp`, `num_ident_erp`, `razon_social`, `tipo_ident_ips`, `num_ident_ips`, `tipo_cobro`, `pref_factura`, `num_factura`, `indic_act_fact`, `valor_factura`, `fecha_factura`, `fecha_radicado`, `fecha_devolucion`, `valor_total_pagos`, `valor_glosa_acept`, `glosa_respondida`, `saldo_factura`, `cobro_juridico`, `etapa_proceso`, `numero_factura`,`fecha_cargue`, `idUser`, `CuentaContable`) VALUES";
                    $i=0;
                }
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            $this->update("salud_upload_control", "Analizado", 1, " WHERE nom_cargue='$NombreArchivo'");
        
            //Actualiza los codigos de las eps porque la 030 inicial no los tiene
            $sql="UPDATE `salud_circular030_inicial` t1 INNER JOIN salud_eps t2 ON t2.nit=t1.`num_ident_erp`  "
                . "SET `Cod_Entidad_Administradora`=t2.cod_pagador_min;";
            $this->Query($sql);
    }
   
    //Copia los registros de la tabla temporal de la circular 030 que no existan en los AF
    public function InserteRegistrosAFDesde030Inicial($Vector) {
        //Secuencia SQL mueve las facturas pagadas desde la circular 030 inicial
        $sql="INSERT INTO `salud_archivo_facturacion_mov_generados` 
            (`cod_prest_servicio`,`razon_social`,`tipo_ident_prest_servicio`,`num_ident_prest_servicio`,
            `num_factura`,`fecha_factura`,`fecha_inicio`,`fecha_final`,`cod_enti_administradora`,
            `nom_enti_administradora`,`num_contrato`,`plan_beneficios`,`num_poliza`,`valor_total_pago`,
            `valor_comision`,`valor_descuentos`,`valor_neto_pagar`,`tipo_negociacion`,`nom_cargue`,`fecha_cargue`,`idUser`,`Arma030Anterior`,`estado`,`fecha_radicado`,`CuentaContable`)
            SELECT (SELECT CodigoPrestadora FROM empresapro WHERE idEmpresaPro=1) as CodigoPrestadora,(SELECT RazonSocial FROM empresapro WHERE idEmpresaPro=1) as RazonSocial,`tipo_ident_erp`,`num_ident_erp`,`numero_factura`,
            `fecha_factura`,`fecha_factura`,`fecha_factura`,Cod_Entidad_Administradora,`razon_social`,'',
            '','',valor_total_pagos,'','',`valor_factura`,
            'Evento','030_Inicial',`fecha_cargue`,`idUser`,'S','PAGADA',fecha_radicado,CuentaContable
            FROM salud_circular030_inicial as t1 WHERE indic_act_fact='A' AND saldo_factura=0 AND NOT EXISTS  
            (SELECT 1 FROM `salud_archivo_facturacion_mov_generados` as t2 
            WHERE t1.`numero_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
        //Secuencia SQL mueve las facturas no pagadas desde la circular 030 inicial
        $sql="INSERT INTO `salud_archivo_facturacion_mov_generados` 
            (`cod_prest_servicio`,`razon_social`,`tipo_ident_prest_servicio`,`num_ident_prest_servicio`,
            `num_factura`,`fecha_factura`,`fecha_inicio`,`fecha_final`,`cod_enti_administradora`,
            `nom_enti_administradora`,`num_contrato`,`plan_beneficios`,`num_poliza`,`valor_total_pago`,
            `valor_comision`,`valor_descuentos`,`valor_neto_pagar`,`tipo_negociacion`,`nom_cargue`,`fecha_cargue`,`idUser`,`Arma030Anterior`,`estado`,`fecha_radicado`,`CuentaContable`)
            SELECT (SELECT CodigoPrestadora FROM empresapro WHERE idEmpresaPro=1) as CodigoPrestadora,(SELECT RazonSocial FROM empresapro WHERE idEmpresaPro=1) as RazonSocial,`tipo_ident_erp`,`num_ident_erp`,`numero_factura`,
            `fecha_factura`,`fecha_factura`,`fecha_factura`,Cod_Entidad_Administradora,`razon_social`,'',
            '','',valor_total_pagos,'','',`valor_factura`,
            'Evento','030_Inicial',`fecha_cargue`,`idUser`,'S','RADICADO' ,fecha_radicado,CuentaContable
            FROM salud_circular030_inicial as t1 WHERE indic_act_fact='A' AND saldo_factura=valor_factura AND NOT EXISTS  
            (SELECT 1 FROM `salud_archivo_facturacion_mov_generados` as t2 
            WHERE t1.`numero_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
        
        //Secuencia SQL mueve las facturas con diferencia pero sin glosa aun
        $sql="INSERT INTO `salud_archivo_facturacion_mov_generados` 
            (`cod_prest_servicio`,`razon_social`,`tipo_ident_prest_servicio`,`num_ident_prest_servicio`,
            `num_factura`,`fecha_factura`,`fecha_inicio`,`fecha_final`,`cod_enti_administradora`,
            `nom_enti_administradora`,`num_contrato`,`plan_beneficios`,`num_poliza`,`valor_total_pago`,
            `valor_comision`,`valor_descuentos`,`valor_neto_pagar`,`tipo_negociacion`,`nom_cargue`,`fecha_cargue`,`idUser`,`Arma030Anterior`,`estado`,`fecha_radicado`,`CuentaContable`)
            SELECT (SELECT CodigoPrestadora FROM empresapro WHERE idEmpresaPro=1) as CodigoPrestadora,(SELECT RazonSocial FROM empresapro WHERE idEmpresaPro=1) as RazonSocial,`tipo_ident_erp`,`num_ident_erp`,`numero_factura`,
            `fecha_factura`,`fecha_factura`,`fecha_factura`,Cod_Entidad_Administradora,`razon_social`,'',
            '','',valor_total_pagos,'','',`valor_factura`,
            'Evento','030_Inicial',`fecha_cargue`,`idUser`,'S','DIFERENCIA' ,fecha_radicado,CuentaContable
            FROM salud_circular030_inicial as t1 WHERE indic_act_fact='A' AND saldo_factura<>valor_factura AND saldo_factura>0 AND valor_glosa_acept=0 AND NOT EXISTS  
            (SELECT 1 FROM `salud_archivo_facturacion_mov_generados` as t2 
            WHERE t1.`numero_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
        
        //Secuencia SQL mueve las facturas con diferencia y glosa
        $sql="INSERT INTO `salud_archivo_facturacion_mov_generados` 
            (`cod_prest_servicio`,`razon_social`,`tipo_ident_prest_servicio`,`num_ident_prest_servicio`,
            `num_factura`,`fecha_factura`,`fecha_inicio`,`fecha_final`,`cod_enti_administradora`,
            `nom_enti_administradora`,`num_contrato`,`plan_beneficios`,`num_poliza`,`valor_total_pago`,
            `valor_comision`,`valor_descuentos`,`valor_neto_pagar`,`tipo_negociacion`,`nom_cargue`,`fecha_cargue`,`idUser`,`Arma030Anterior`,`estado`,`fecha_radicado`,`CuentaContable`)
            SELECT (SELECT CodigoPrestadora FROM empresapro WHERE idEmpresaPro=1) as CodigoPrestadora,(SELECT RazonSocial FROM empresapro WHERE idEmpresaPro=1) as RazonSocial,`tipo_ident_erp`,`num_ident_erp`,`numero_factura`,
            `fecha_factura`,`fecha_factura`,`fecha_factura`,Cod_Entidad_Administradora,`razon_social`,'',
            '','',valor_total_pagos,'','',`valor_factura`,
            'Evento','030_Inicial',`fecha_cargue`,`idUser`,'S','DIFERENCIA' ,fecha_radicado,CuentaContable
            FROM salud_circular030_inicial as t1 WHERE indic_act_fact='A' AND saldo_factura<>valor_factura AND 	valor_glosa_acept>0 AND NOT EXISTS  
            (SELECT 1 FROM `salud_archivo_facturacion_mov_generados` as t2 
            WHERE t1.`numero_factura`=t2.`num_factura`); ";
        
        $this->Query($sql);
        
        
    }
    //Registra las glosas desde la circular 030 inicial
    public function RegistroDeGlosasDesde030Inicial($param) {
        //Secuencia SQL mueve las facturas con diferencia y glosa
        $sql="INSERT INTO `salud_registro_glosas` 
            (`num_factura`,`PrefijoArchivo`,`idArchivo`,`TipoGlosa`,
            `CodigoGlosa`,`FechaReporte`,`GlosaEPS`,`GlosaAceptada`,`Observaciones`,
            `cod_enti_administradora`,`fecha_factura`,`idUser`,`TablaOrigen`)
            SELECT numero_factura,'AF','','3','',
            `fecha_devolucion`,`valor_glosa_acept`,`valor_glosa_acept`,'030_Inicial',`Cod_Entidad_Administradora`,fecha_factura,
            idUser,'salud_circular030_inicial'
            FROM salud_circular030_inicial as t1 WHERE indic_act_fact='A' AND saldo_factura<>valor_factura AND 	valor_glosa_acept>0; ";
        
        $this->Query($sql);
    }
    //Copia los registros de la tabla temporal de la circular 030 que no existan en los AR
    public function InserteARDesde030Inicial($Vector) {
        //Secuencia SQL mueve las facturas pagadas desde la circular 030 inicial
        $sql="INSERT INTO `salud_archivo_facturacion_mov_pagados` 
            (`num_factura`,`fecha_pago_factura`,`num_pago`,`valor_pagado`,
            `nom_cargue`,`fecha_cargue`,`idUser`,`Arma030Anterior`)
            SELECT numero_factura,`fecha_radicado`,'1',`valor_total_pagos`,
            '030_Inicial',`fecha_cargue`,`idUser`,'S'
            FROM salud_circular030_inicial as t1 WHERE indic_act_fact='A' AND valor_total_pagos>0 ; ";
        
        $this->Query($sql);
    }
    //Verifica si hay duplicados en los AF y los retorna
    public function VerifiqueDuplicadosAF($Vector) {
        $Error=0;
        $sql="SELECT ID FROM vista_af_duplicados LIMIT 1";
        $consulta= $this->Query($sql);
        $Datos= $this->FetchArray($consulta);
        if($Datos["ID"]>0){
            $Error=1;
        }
        return $Error;
    }
    
    //Actualiza Facturas duplicadas en AF en estado devuelto
    public function ActualiceAFDevueltas($Vector) {
        // Se copian los archivos a la tabla historica
        $sql="INSERT INTO `salud_rips_facturas_generadas_historico` "
                . "SELECT * FROM `salud_archivo_facturacion_mov_generados` as t1 "
                . "WHERE EXISTS "
                . "(SELECT 1 FROM `vista_af_devueltos` as t2 "
                . "WHERE t1.`num_factura`=t2.`num_factura`); ";
        
        $this->Query($sql); 
        
        // Eliminar Archivos del AC que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_consultas` WHERE EXISTS "
                . "(SELECT num_factura FROM `vista_af_devueltos` "
                . "WHERE salud_archivo_consultas.`num_factura`=vista_af_devueltos.`num_factura`)";
        
        $this->Query($sql);
        
        // Eliminar Archivos del AH que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_hospitalizaciones` WHERE EXISTS "
                . "(SELECT num_factura FROM `vista_af_devueltos` "
                . "WHERE salud_archivo_hospitalizaciones.`num_factura`=vista_af_devueltos.`num_factura`)";
        
        $this->Query($sql);
        
        // Eliminar Archivos del AM que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_medicamentos` WHERE EXISTS "
                . "(SELECT num_factura FROM `vista_af_devueltos` "
                . "WHERE salud_archivo_medicamentos.`num_factura`=vista_af_devueltos.`num_factura`)";
        
        $this->Query($sql);
        
        // Eliminar Archivos del AN que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_nacidos` WHERE EXISTS "
                . "(SELECT num_factura FROM `vista_af_devueltos` "
                . "WHERE salud_archivo_nacidos.`num_factura`=vista_af_devueltos.`num_factura`)";
        
        $this->Query($sql);
        
        // Eliminar Archivos del AT que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_otros_servicios` WHERE EXISTS "
                . "(SELECT num_factura FROM `vista_af_devueltos` "
                . "WHERE salud_archivo_otros_servicios.`num_factura`=vista_af_devueltos.`num_factura`)";
        
        $this->Query($sql);
        
        // Eliminar Archivos del AP que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_procedimientos` WHERE EXISTS "
                . "(SELECT num_factura FROM `vista_af_devueltos` "
                . "WHERE salud_archivo_procedimientos.`num_factura`=vista_af_devueltos.`num_factura`)";
        
        $this->Query($sql);
        
        // Eliminar Archivos del AU que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_urgencias` WHERE EXISTS "
                . "(SELECT num_factura FROM `vista_af_devueltos` "
                . "WHERE salud_archivo_urgencias.`num_factura`=vista_af_devueltos.`num_factura`)";
        
        $this->Query($sql);
        
               
        // Eliminar Archivos del AF que tienen las facturas devueltas
        $sql="DELETE FROM `salud_archivo_facturacion_mov_generados` WHERE EXISTS "
                . "(SELECT num_factura FROM `salud_rips_facturas_generadas_historico` "
                . "WHERE salud_archivo_facturacion_mov_generados.`num_factura`=salud_rips_facturas_generadas_historico.`num_factura`"
                . " AND salud_archivo_facturacion_mov_generados.`nom_cargue`=salud_rips_facturas_generadas_historico.`nom_cargue`)";
        
        $this->Query($sql);
        
    }
    
    public function VaciarArchivosTemporalesCarga() {
        $this->BorraReg("salud_upload_control", "Analizado", 0);
        $this->AjusteAutoIncrement("salud_upload_control", "id_upload_control", "");
        $this->VaciarTabla("salud_archivo_consultas_temp");
        $this->VaciarTabla("salud_archivo_hospitalizaciones_temp");
        $this->VaciarTabla("salud_archivo_medicamentos_temp");
        $this->VaciarTabla("salud_archivo_nacidos_temp");
        $this->VaciarTabla("salud_archivo_otros_servicios_temp");
        $this->VaciarTabla("salud_archivo_procedimientos_temp");
        $this->VaciarTabla("salud_archivo_urgencias_temp");
        $this->VaciarTabla("salud_archivo_usuarios_temp");
        $this->VaciarTabla("salud_rips_facturas_generadas_temp");
        $this->VaciarTabla("salud_upload_control_ct");
        
        
    }
    
    //Rips de pagos formato adres
    public function InsertarRipsPagosAdresContributivoTemporal($NombreArchivo,$Separador,$FechaCargue, $idUser,$destino,$FechaGiro,$TipoGiro, $Vector) {
        // si se recibe el archivo
        if($Separador==1){
           $Separador=";"; 
        }else{
           $Separador=",";  
        }
        $Ruta="../../../VSalud/archivos/";
            if($handle=fopen($Ruta.$NombreArchivo, "r")){
            //$handle = fopen($Ruta.$NombreArchivo, "r");
            $i=0;
            $z=0;
            $h=0;
            $tab="salud_pagos_contributivo_temp";
            $sql="INSERT INTO `$tab` (`ID`, `Proceso`, `NitEPS`, `CodigoEps`, `NombreEPS`, `FechaPago`, `numero_factura`, `PrefijoFactura`, `NumeroFactura`, `FechaFactura`, `FormaContratacion`, `ValorGiro`, `fecha_cargue`, `Soporte`, `Estado`, `idUser`) VALUES";
            
            while (($data = fgetcsv($handle, 1000, $Separador,'"',"#")) !== FALSE) {
                //////Inserto los datos en la tabla  
                $i++;
                $h++;
                
                if($h>=1 and $data[0]<>''){
                    $Prefijo="";
                    $NumeroFactura="";
                    $FacturaCompleta="";
                    if($data[7]<>''){
                        $Prefijo=preg_replace('/[^A-Za-z]+/', '', $data[7]);
                        $NumeroFactura=preg_replace('/[^0-9]+/', '', $data[7]);                    
                        $FacturaCompleta=$data[7];                        
                    }
                    //$DatosFactura= $this->DevuelveValores("salud_archivo_facturacion_mov_generados", "num_factura", $data[7]);
                    $CodigoEps="";
                    $FechaFactura="";
                    $TipoContratacion="";
                    $ValorGiro=str_replace(".","",$data[5]);
                    
                    $ValorGiro=str_replace("$","",$ValorGiro);
                    
                    if($data[6]<>""){
                        $FechaArchivo= explode("/", $data[4]);
                        if(count($FechaArchivo)>1){
                            $FechaPago= $FechaArchivo[2]."-".$FechaArchivo[1]."-".$FechaArchivo[0];
                        }else{
                            $FechaPago=$data[4];
                        }

                     }else{
                        $FechaPago="0000-00-00";
                     }

                    if($z==1){
                        
                        $sql.="('','$data[6]', '$data[2]', '$CodigoEps','$data[3]', '$FechaPago', '$FacturaCompleta', '$Prefijo','$NumeroFactura','$FechaFactura', '$TipoContratacion', '$ValorGiro', '$FechaCargue','$destino' ,'0','$idUser'),";
                    }
                    $z=1;

                    if($i==10000){
                        $sql=substr($sql, 0, -1);
                        $this->Query($sql);
                        $sql="INSERT INTO `$tab` (`ID`, `Proceso`, `NitEPS`, `CodigoEps`, `NombreEPS`, `FechaPago`, `numero_factura`, `PrefijoFactura`, `NumeroFactura`, `FechaFactura`, `FormaContratacion`, `ValorGiro`, `fecha_cargue`, `Soporte`, `Estado`, `idUser`) VALUES";
            
                        $i=0;
                    }
                }
            }
            }else{
                exit("E1;Archivo no encontrado");
            }
            $sql=substr($sql, 0, -1);
            $this->Query($sql);
            fclose($handle); 
            $sql="";
            //$this->RegistreUpload($NombreArchivo, $FechaCargue, $idUser, "");
            //$this->update("salud_upload_control", "Analizado", 1, " WHERE nom_cargue='$NombreArchivo'");
               
    }
    //Fin Clases
}