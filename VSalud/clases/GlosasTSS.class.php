<?php
/* 
 * Clase donde se realizaran procesos de para Devolver o Glosar Facturas 
 * Julian Andres Alvaran
 * Techno Soluciones SAS en asociacion con SITIS SAS
 * 2018-07-19
 */

class Glosas extends conexion{
   /**
    * Clase para devolver una factura
    * @param type $idFactura
    * @param type $FechaDevolucion
    * @param type $Observaciones
    * @param type $CodigoGlosa
    * @param type $idUser
    * @param type $Soporte
    * @param type $Vector
    * Creada: 2018-07-19 Julian Alvaran
    */
    public function DevolverFactura($idFactura,$ValorFactura,$FechaDevolucion,$FechaAuditoria,$Observaciones,$CodigoGlosa,$idUser,$Soporte,$Vector) {
        //////Hago el registro en la tabla             
        $tab="salud_registro_devoluciones_facturas";
        $NumRegistros=9;

        $Columnas[0]="FechaDevolucion";         $Valores[0]=$FechaDevolucion;
        $Columnas[1]="num_factura";             $Valores[1]=$idFactura;
        $Columnas[2]="Observaciones";           $Valores[2]=$Observaciones;
        $Columnas[3]="CodGlosa";                $Valores[3]=$CodigoGlosa;
        $Columnas[4]="idUser";                  $Valores[4]=$idUser;
        $Columnas[5]="Soporte";                 $Valores[5]=$Soporte;
        $Columnas[6]="FechaReciboAuditoria";    $Valores[6]=$FechaAuditoria;
        $Columnas[7]="FechaRegistro";           $Valores[7]=date("Y-m-d");
        $Columnas[8]="ValorFactura";            $Valores[8]=$ValorFactura;
        
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
        
        $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", 9, "num_factura", $idFactura);
        $this->ActualizaRegistro("salud_archivo_consultas", "EstadoGlosa", 9, "num_factura", $idFactura);
        $this->ActualizaRegistro("salud_archivo_procedimientos", "EstadoGlosa", 9, "num_factura", $idFactura);
        $this->ActualizaRegistro("salud_archivo_otros_servicios", "EstadoGlosa", 9, "num_factura", $idFactura);
        $this->ActualizaRegistro("salud_archivo_medicamentos", "EstadoGlosa", 9, "num_factura", $idFactura);
        $this->ActualizaRegistro("salud_archivo_control_glosas_respuestas", "EstadoGlosa", 9, "num_factura", $idFactura);
        
    }
    /**
     * Registra una glosa inicial temporal para ir agregando hasta que el auditor termine
     * @param type $idFactura
     * @param type $idActividad
     * @param type $FechaIPS
     * @param type $FechaAuditoria
     * 
     * @param type $CodigoGlosa
     * @param type $ValorEPS
     * @param type $ValorAceptado
     * @param type $ValorConciliar
     * 
     * @param type $Vector
     */
    public function RegistrarGlosaInicialTemporal($TipoArchivo,$idFactura, $idActividad,$TotalActividad, $FechaIPS, $FechaAuditoria, $CodigoGlosa, $ValorEPS, $ValorAceptado, $ValorConciliar,$Observaciones,$destino,$idUser,$Vector) {
        $TotalGlosasExistentes=$this->Sume("salud_glosas_iniciales", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad'");
        $TotalGlosasTemporal=$this->Sume("salud_glosas_iniciales_temp", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad'");
        $TotalGlosasExistentes=$TotalGlosasExistentes+$TotalGlosasTemporal;
        if($TipoArchivo=='AC'){
            $CodigoActivida=$this->ValorActual("salud_archivo_consultas", "cod_consulta as Codigo", " id_consultas='$idActividad'");
            $Codigo=$CodigoActivida["Codigo"];
            $DatosDescripcion=$this->ValorActual("salud_cups", "descripcion_cups as Descripcion", " codigo_sistema='$Codigo'");
            $Descripcion=$DatosDescripcion["Descripcion"];
        }
        if($TipoArchivo=='AP'){
            $CodigoActivida=$this->ValorActual("salud_archivo_procedimientos", "cod_procedimiento as Codigo", " id_procedimiento='$idActividad'");
            $Codigo=$CodigoActivida["Codigo"];
            $DatosDescripcion=$this->ValorActual("salud_cups", "descripcion_cups as Descripcion", " codigo_sistema='$Codigo'");
            $Descripcion=$DatosDescripcion["Descripcion"];
            
        }
        if($TipoArchivo=='AT'){
            $CodigoActivida=$this->ValorActual("salud_archivo_otros_servicios", "cod_servicio  as Codigo,nom_servicio as Descripcion", " id_otro_servicios='$idActividad'");
            $Codigo=$CodigoActivida["Codigo"];
            $Descripcion=$CodigoActivida["Descripcion"];
            
        }
        if($TipoArchivo=='AM'){
            $CodigoActivida=$this->ValorActual("salud_archivo_medicamentos", "cod_medicamento as Codigo,nom_medicamento as Descripcion", " id_medicamentos='$idActividad'");
            $Codigo=$CodigoActivida["Codigo"];
            $Descripcion=$CodigoActivida["Descripcion"];
            
        }
        if(($TotalGlosasExistentes+$ValorEPS)>$TotalActividad){
            exit("El valor Glosado Excede el total de la actividad.");
        }
        $sql="SELECT ID FROM salud_glosas_iniciales WHERE num_factura='$idFactura' AND CodigoActividad='$Codigo' AND CodigoGlosa='$CodigoGlosa' AND EstadoGlosa<>12";
        $consulta=$this->Query($sql);
        $DatosGlosasIniciales= $this->FetchArray($consulta);
        if($DatosGlosasIniciales["ID"]<>''){
            $Mensaje["msg"]="El codigo de Glosa $CodigoGlosa ya fue registrado a la factura $idFactura y codigo de actividad $Codigo";
            $Mensaje["Error"]=1;
            exit(json_encode($Mensaje));
        }
        $sql="SELECT ID FROM salud_glosas_iniciales_temp WHERE num_factura='$idFactura' AND CodigoActividad='$Codigo' AND CodigoGlosa='$CodigoGlosa'";
        $consulta=$this->Query($sql);
        $DatosGlosasIniciales= $this->FetchArray($consulta);
        if($DatosGlosasIniciales["ID"]<>''){
            $Mensaje["msg"]="El codigo de Glosa $CodigoGlosa ya fue registrado a la factura $idFactura y codigo de actividad $Codigo";
            $Mensaje["Error"]=1;
            exit(json_encode($Mensaje));
        }
        $FechaRegistro=date("Y-m-d");
        $tab="salud_glosas_iniciales_temp";
        $NumRegistros=19;

        $Columnas[0]="FechaIPS";                $Valores[0]=$FechaIPS;
        $Columnas[1]="FechaAuditoria";          $Valores[1]=$FechaAuditoria;
        $Columnas[2]="FechaRegistro";           $Valores[2]=$FechaRegistro;
        $Columnas[3]="CodigoGlosa";             $Valores[3]=$CodigoGlosa;
        $Columnas[4]="num_factura";             $Valores[4]=$idFactura;
        $Columnas[5]="CodigoActividad";         $Valores[5]=$Codigo;
        $Columnas[6]="EstadoGlosa";             $Valores[6]=1;
        $Columnas[7]="ValorGlosado";            $Valores[7]=$ValorEPS;
        $Columnas[8]="ValorLevantado";          $Valores[8]=0;
        $Columnas[9]="ValorAceptado";           $Valores[9]=$ValorAceptado;
        $Columnas[10]="ValorXConciliar";        $Valores[10]=$ValorEPS;
        $Columnas[11]="ValorConciliado";        $Valores[11]=0;
        $Columnas[12]="ValorActividad";         $Valores[12]=$TotalActividad;
        $Columnas[13]="TipoArchivo";            $Valores[13]=$TipoArchivo;
        $Columnas[14]="Observaciones";          $Valores[14]=$Observaciones;
        $Columnas[15]="Soporte";                $Valores[15]=$destino;
        $Columnas[16]="idArchivo";              $Valores[16]=$idActividad;
        $Columnas[17]="NombreActividad";        $Valores[17]=$Descripcion;
        $Columnas[18]="idUser";                 $Valores[18]= $idUser;
        
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
        $idGlosa=$this->ObtenerMAX($tab, "ID", 1, "");
        $Mensaje["msg"]="Glosa Agregada";
        return($Mensaje);
        
    }
    /**
     * Registre una glosa inicial
     * @param type $idFactura
     * @param type $idActividad
     * @param type $ValorActividad
     * @param type $FechaIPS
     * @param type $FechaAuditoria
     * @param type $CodigoGlosa
     * @param type $ValorEPS
     * @param type $ValorAceptado
     * @param type $ValorConciliar
     * @param type $Vector
     * @return type
     */
    public function RegistrarGlosaInicial($idFactura,$idActividad,$ValorActividad,$FechaIPS,$FechaAuditoria,$CodigoGlosa,$ValorEPS,$ValorAceptado,$ValorConciliar,$Vector,$ValorEdicion=0) {
        $TotalGlosasExistentes=$this->Sume("salud_glosas_iniciales", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad' AND EstadoGlosa<9");
        
        if(($TotalGlosasExistentes+$ValorEPS)>$ValorActividad){
            exit("El valor Glosado Excede el total de la actividad.");
        }
        $FechaRegistro=date("Y-m-d");
        $tab="salud_glosas_iniciales";
        $NumRegistros=13;

        $Columnas[0]="FechaIPS";                $Valores[0]=$FechaIPS;
        $Columnas[1]="FechaAuditoria";          $Valores[1]=$FechaAuditoria;
        $Columnas[2]="FechaRegistro";           $Valores[2]=$FechaRegistro;
        $Columnas[3]="CodigoGlosa";             $Valores[3]=$CodigoGlosa;
        $Columnas[4]="num_factura";             $Valores[4]=$idFactura;
        $Columnas[5]="CodigoActividad";         $Valores[5]=$idActividad;
        $Columnas[6]="EstadoGlosa";             $Valores[6]=1;
        $Columnas[7]="ValorGlosado";            $Valores[7]=$ValorEPS;
        $Columnas[8]="ValorLevantado";          $Valores[8]=0;
        $Columnas[9]="ValorAceptado";           $Valores[9]=$ValorAceptado;
        $Columnas[10]="ValorXConciliar";        $Valores[10]=$ValorEPS;
        $Columnas[11]="ValorConciliado";        $Valores[11]=0;
        $Columnas[12]="ValorActividad";         $Valores[12]=$ValorActividad;
        
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
        $idGlosa=$this->ObtenerMAX($tab, "ID", 1, "");
        
        return($idGlosa);
        
    }
    
    /**
     * Registra los datos para realizar una glosa, contraglosa o respuesta
     * 
     * @param type $TipoArchivo
     * @param type $idGlosa
     * @param type $idFactura
     * @param type $idActividad
     * @param type $TotalActividad
     * @param type $EstadoGlosa
     * @param type $FechaIPS
     * @param type $FechaAuditoria
     * @param type $Observaciones
     * @param type $CodigoGlosa
     * @param type $ValorEPS
     * @param type $ValorAceptado
     * @param type $ValorLevantado
     * @param type $ValorConciliar
     * @param type $destino
     * @param type $idUser
     * @param type $Vector
     */
    public function RegistraGlosaRespuesta($TipoArchivo,$idGlosa,$idFactura,$idActividad,$NombreActividad,$TotalActividad,$EstadoGlosa,$FechaIPS,$FechaAuditoria,$Observaciones,$CodigoGlosa,$ValorEPS,$ValorAceptado,$ValorLevantado,$ValorConciliar,$destino,$idUser,$Vector) {
        $DatosFactura= $this->ValorActual("salud_archivo_facturacion_mov_generados", " CuentaGlobal,CuentaRIPS ", " num_factura='$idFactura'");
        $DatosGlosaInicial= $this->ValorActual("salud_glosas_iniciales", " ValorXConciliar ", " ID='$idGlosa'");
        
        $FechaRegistro=date("Y-m-d");
        $tab="salud_archivo_control_glosas_respuestas";
        $NumRegistros=21;

        $Columnas[0]="num_factura";             $Valores[0]=$idFactura;
        $Columnas[1]="idGlosa";                 $Valores[1]=$idGlosa;
        $Columnas[2]="CuentaGlobal";            $Valores[2]=$DatosFactura["CuentaGlobal"];
        $Columnas[3]="CuentaRIPS";              $Valores[3]=$DatosFactura["CuentaRIPS"];
        $Columnas[4]="cod_glosa_general";       $Valores[4]=substr($CodigoGlosa,0,1);
        $Columnas[5]="cod_glosa_especifico";    $Valores[5]=substr($CodigoGlosa,1,2);
        $Columnas[6]="id_cod_glosa";            $Valores[6]=$CodigoGlosa;
        $Columnas[7]="CodigoActividad";         $Valores[7]=$idActividad;
        $Columnas[8]="EstadoGlosa";             $Valores[8]=$EstadoGlosa;
        $Columnas[9]="FechaIPS";                $Valores[9]=$FechaIPS;
        $Columnas[10]="FechaAuditoria";         $Valores[10]=$FechaAuditoria;
        $Columnas[11]="valor_actividad";        $Valores[11]=$TotalActividad;
        
        $Columnas[12]="valor_glosado_eps";      $Valores[12]=$ValorEPS;
        $Columnas[13]="valor_levantado_eps";    $Valores[13]=$ValorLevantado;
        $Columnas[14]="valor_aceptado_ips";     $Valores[14]=$ValorAceptado;
        $Columnas[15]="observacion_auditor";    $Valores[15]=$Observaciones;
        $Columnas[16]="Soporte";                $Valores[16]=$destino;
        $Columnas[17]="fecha_registo";          $Valores[17]=$FechaRegistro;
        $Columnas[18]="TipoArchivo";            $Valores[18]=$TipoArchivo;
        $Columnas[19]="idUser";                 $Valores[19]=$idUser;
        $Columnas[20]="DescripcionActividad";   $Valores[20]= utf8_decode($NombreActividad);
        
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
    }
    /**
     * Elimina una glosa temporal
     * @param type $idGlosa
     */
    public function EliminarGlosaTemporal($idGlosa) {
        $this->BorraReg("salud_glosas_iniciales_temp", "ID", $idGlosa);
    }
    /**
     * Guarda las glosas temporales en la tabla real, en la tabla de registros de respuesta y elimina de la tabla temporal
     * @param type $Vector
     */
    public function GuardaGlosasTemporalesAIniciales($idUser,$Vector) {
        $consulta=$this->ConsultarTabla("salud_glosas_iniciales_temp", " WHERE idUser='$idUser'");
        $Estado=12;
        while($DatosGlosaTemporal=$this->FetchArray($consulta)){
            $NumFactura=$DatosGlosaTemporal["num_factura"];
            $CodActividad=$DatosGlosaTemporal["CodigoActividad"];
            $idGlosa=$this->RegistrarGlosaInicial($DatosGlosaTemporal["num_factura"], $DatosGlosaTemporal["CodigoActividad"], $DatosGlosaTemporal["ValorActividad"], $DatosGlosaTemporal["FechaIPS"], $DatosGlosaTemporal["FechaAuditoria"], $DatosGlosaTemporal["CodigoGlosa"], $DatosGlosaTemporal["ValorGlosado"], $DatosGlosaTemporal["ValorAceptado"], $DatosGlosaTemporal["ValorXConciliar"], "");
            $this->RegistraGlosaRespuesta($DatosGlosaTemporal["TipoArchivo"], $idGlosa, $DatosGlosaTemporal["num_factura"], $DatosGlosaTemporal["CodigoActividad"],$DatosGlosaTemporal["NombreActividad"], $DatosGlosaTemporal["ValorActividad"], 1, $DatosGlosaTemporal["FechaIPS"], $DatosGlosaTemporal["FechaAuditoria"], $DatosGlosaTemporal["Observaciones"], $DatosGlosaTemporal["CodigoGlosa"], $DatosGlosaTemporal["ValorGlosado"], $DatosGlosaTemporal["ValorAceptado"], 0, $DatosGlosaTemporal["ValorXConciliar"], $DatosGlosaTemporal["Soporte"], $idUser, "");
            $this->BorraReg("salud_glosas_iniciales_temp", "ID", $DatosGlosaTemporal["ID"]);
            if($DatosGlosaTemporal["TipoArchivo"]=="AC"){
                
                $TablaArchivo="salud_archivo_consultas";
                $ColCodigo="cod_consulta";
                $this->update($TablaArchivo, "EstadoGlosa", 1, "WHERE num_factura='$NumFactura' AND $ColCodigo='$CodActividad'");
                $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", 1, "num_factura", $NumFactura);
      
            }
            
            if($DatosGlosaTemporal["TipoArchivo"]=="AT"){
                
                $TablaArchivo="salud_archivo_otros_servicios";
                $ColCodigo="cod_servicio";
                $this->update($TablaArchivo, "EstadoGlosa", 1, "WHERE num_factura='$NumFactura' AND $ColCodigo='$CodActividad'");
                $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", 1, "num_factura", $NumFactura);
      
            }
            
            if($DatosGlosaTemporal["TipoArchivo"]=="AP"){
                
                $TablaArchivo="salud_archivo_procedimientos";
                $ColCodigo="cod_procedimiento";
                $this->update($TablaArchivo, "EstadoGlosa", 1, "WHERE num_factura='$NumFactura' AND $ColCodigo='$CodActividad'");
                $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", 1, "num_factura", $NumFactura);
      
            }
            
            if($DatosGlosaTemporal["TipoArchivo"]=="AM"){
                
                $TablaArchivo="salud_archivo_medicamentos";
                $ColCodigo="cod_medicamento";
                $this->update($TablaArchivo, "EstadoGlosa", 1, "WHERE num_factura='$NumFactura' AND $ColCodigo='$CodActividad'");
                $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", 1, "num_factura", $NumFactura);
      
            }
            
       
        }
        //$this->VaciarTabla("salud_glosas_iniciales_temp");
        
    }
    /**
     * Registra respuestas en tabla temporal
     * @param type $TipoArchivo
     * @param type $idGlosa
     * @param type $idFactura
     * @param type $idActividad
     * @param type $NombreActividad
     * @param type $TotalActividad
     * @param type $EstadoGlosa
     * @param type $FechaIPS
     * @param type $FechaAuditoria
     * @param type $Observaciones
     * @param type $CodigoGlosa
     * @param type $ValorEPS
     * @param type $ValorAceptado
     * @param type $ValorLevantado
     * @param type $ValorConciliar
     * @param type $destino
     * @param type $idUser
     * @param type $Vector
     */
    public function RegistraGlosaRespuestaTemporal($idReplace,$TipoArchivo,$idGlosa,$idFactura,$idActividad,$NombreActividad,$TotalActividad,$EstadoGlosa,$FechaIPS,$FechaAuditoria,$Observaciones,$CodigoGlosa,$ValorEPS,$ValorAceptado,$ValorLevantado,$ValorConciliar,$destino,$idUser,$Vector) {
        $DatosFactura= $this->ValorActual("salud_archivo_facturacion_mov_generados", " CuentaGlobal,CuentaRIPS ", " num_factura='$idFactura'");
        //$DatosGlosaInicial= $this->ValorActual("salud_glosas_iniciales", " ValorXConciliar ", " ID='$idGlosa'");
        
        $FechaRegistro=date("Y-m-d");
        $tab="salud_archivo_control_glosas_respuestas_temp";
        $Datos["ID"]=$idReplace;               
        $Datos["num_factura"]=$idFactura;
        $Datos["idGlosa"]=$idGlosa;
        $Datos["CuentaGlobal"]=$DatosFactura["CuentaGlobal"];
        $Datos["CuentaRIPS"]=$DatosFactura["CuentaRIPS"];
        $Datos["cod_glosa_general"]=substr($CodigoGlosa,0,1);
        $Datos["cod_glosa_especifico"]=substr($CodigoGlosa,1,2);
        $Datos["id_cod_glosa"]=$CodigoGlosa;
        $Datos["CodigoActividad"]=$idActividad;
        $Datos["EstadoGlosa"]=$EstadoGlosa;
        $Datos["FechaIPS"]=$FechaIPS;
        $Datos["FechaAuditoria"]=$FechaAuditoria;
        $Datos["valor_actividad"]=$TotalActividad;
        $Datos["valor_glosado_eps"]=$ValorEPS;
        $Datos["valor_levantado_eps"]=$ValorLevantado;
        $Datos["valor_aceptado_ips"]=$ValorAceptado;
        $Datos["observacion_auditor"]=$Observaciones;
        $Datos["Soporte"]=$destino;
        $Datos["fecha_registo"]=$FechaRegistro;
        $Datos["TipoArchivo"]=$TipoArchivo;
        $Datos["idUser"]=$idUser;
        $Datos["DescripcionActividad"]=$NombreActividad;
        if($idReplace=''){
            $sql=$this->getSQLInsert($tab, $Datos);
        }else{
            $sql=$this->getSQLReeplace($tab, $Datos);
        }
        
        $this->Query($sql);
               
    }
    /**
     * Edite una Glosa Temporal
     * @param type $idGlosaTemp
     * @param type $TipoArchivo
     * @param type $idArchivo
     * @param type $idFactura
     * @param type $idActividad
     * @param type $NombreActividad
     * @param type $TotalActividad
     * @param type $EstadoGlosa
     * @param type $FechaIPS
     * @param type $FechaAuditoria
     * @param type $Observaciones
     * @param type $CodigoGlosa
     * @param type $ValorEPS
     * @param type $ValorAceptado
     * @param type $ValorLevantado
     * @param type $ValorConciliar
     * @param type $destino
     * @param type $idUser
     * @param type $Vector
     */
    public function EditaGlosaRespuestaTemporal($idGlosaTemp,$TipoArchivo,$idArchivo,$idFactura,$idActividad,$NombreActividad,$TotalActividad,$EstadoGlosa,$FechaIPS,$FechaAuditoria,$Observaciones,$CodigoGlosa,$ValorEPS,$ValorAceptado,$ValorLevantado,$ValorConciliar,$destino,$idUser,$Vector) {
        $DatosFactura= $this->ValorActual("salud_archivo_facturacion_mov_generados", " CuentaGlobal,CuentaRIPS ", " num_factura='$idFactura'");
        //$DatosGlosaInicial= $this->ValorActual("salud_glosas_iniciales", " ValorXConciliar ", " ID='$idGlosa'");
        
        $FechaRegistro=date("Y-m-d");
        $tab="salud_glosas_iniciales_temp";
        $Datos["ID"]=$idGlosaTemp;    //            
        $Datos["num_factura"]=$idFactura;//
        //$Datos["idGlosa"]=$idGlosa;//
        $Datos["ValorConciliado"]=$ValorConciliar;
        
        
        $Datos["CodigoGlosa"]=$CodigoGlosa;//
        $Datos["CodigoActividad"]=$idActividad;//
        $Datos["EstadoGlosa"]=$EstadoGlosa;//
        $Datos["FechaIPS"]=$FechaIPS;//
        $Datos["FechaAuditoria"]=$FechaAuditoria;//
        $Datos["ValorActividad"]=$TotalActividad;//
        $Datos["ValorGlosado"]=$ValorEPS;//
        $Datos["ValorLevantado"]=$ValorLevantado;//
        $Datos["ValorAceptado"]=$ValorAceptado;//
        $Datos["ValorXConciliar"]=$ValorEPS-$ValorAceptado-$ValorLevantado;//
        $Datos["NombreActividad"]=$NombreActividad;
        $Datos["FechaRegistro"]=$FechaRegistro;//
        $Datos["TipoArchivo"]=$TipoArchivo;//
        $Datos["idArchivo"]=$idArchivo;//
        $Datos["Observaciones"]=$Observaciones;//
        $Datos["Soporte"]=$destino;//
        $Datos["idUser"]=$idUser;//
               
        $sql=$this->getSQLReeplace($tab, $Datos);
        
        
        $this->Query($sql);
               
    }
    /**
     * Edita una glosa inicial
     * @param type $idGlosaInicial
     * @param type $idFactura
     * @param type $CodActividad
     * @param type $ValorActividad
     * @param type $EstadoGlosa
     * @param type $FechaIPS
     * @param type $FechaAuditoria
     * @param type $CodigoGlosa
     * @param type $ValorEPS
     * @param type $ValorAceptado
     * @param type $ValorLevantado
     * @param type $ValorConciliar
     * @param type $destino
     * @param type $idUser
     * @param type $Vector
     */
    public function EditaGlosaInicial($idGlosaInicial,$idFactura,$CodActividad,$ValorActividad,$EstadoGlosa,$FechaIPS,$FechaAuditoria,$CodigoGlosa,$ValorEPS,$ValorAceptado,$ValorLevantado,$ValorConciliar,$destino,$idUser,$Vector) {
        $DatosFactura= $this->ValorActual("salud_archivo_facturacion_mov_generados", " CuentaGlobal,CuentaRIPS ", " num_factura='$idFactura'");
        $DatosGlosaInicial= $this->DevuelveValores("salud_glosas_iniciales", "ID", $idGlosaInicial);
        
        $FechaRegistro=date("Y-m-d");
        $tab="salud_glosas_iniciales";
        $Datos["ID"]=$idGlosaInicial;   
        $Datos["FechaIPS"]=$FechaIPS;////   
        $Datos["FechaAuditoria"]=$FechaAuditoria;//
        $Datos["FechaRegistro"]=$FechaRegistro;//
        $Datos["CodigoGlosa"]=$CodigoGlosa;//        
        $Datos["num_factura"]=$idFactura;//
        $Datos["CodigoActividad"]=$CodActividad;//
        $Datos["ValorGlosado"]=$ValorEPS;//
        $Datos["ValorLevantado"]=$ValorLevantado;//
        $Datos["ValorAceptado"]=$ValorAceptado;//
        $Datos["ValorXConciliar"]=$ValorEPS-$ValorAceptado-$ValorLevantado;//
        $Datos["ValorConciliado"]=0;
        $Datos["EstadoGlosa"]=$EstadoGlosa;//
        $Datos["ValorActividad"]=$ValorActividad;//
        //$Datos["Soporte"]=$destino;//
               
        $sql=$this->getSQLReeplace($tab, $Datos);
        $this->Query($sql);
        $this->RegistreEdicionesGlosasIniciales($DatosGlosaInicial,$Datos,"ID = $idGlosaInicial",$idUser);
          
        
    }
    /**
     * Registra las ediciones realizadas sobre las glosas 
     * @param type $DatosIniciales
     * @param type $DatosNuevos
     */
    public function RegistreEdicionesGlosasIniciales($DatosIniciales,$DatosNuevos,$Consulta,$idUser) {
        if($DatosIniciales["FechaIPS"]<>$DatosNuevos["FechaIPS"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "FechaIPS", $DatosIniciales["FechaIPS"], $DatosNuevos["FechaIPS"], $Consulta, "");
        }
        
        if($DatosIniciales["FechaAuditoria"]<>$DatosNuevos["FechaAuditoria"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "FechaAuditoria", $DatosIniciales["FechaAuditoria"], $DatosNuevos["FechaAuditoria"], $Consulta, "");
        }
        
        if($DatosIniciales["FechaRegistro"]<>$DatosNuevos["FechaRegistro"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "FechaRegistro", $DatosIniciales["FechaRegistro"], $DatosNuevos["FechaRegistro"], $Consulta, "");
        }
        
        if($DatosIniciales["ValorGlosado"]<>$DatosNuevos["ValorGlosado"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "ValorGlosado", $DatosIniciales["ValorGlosado"], $DatosNuevos["ValorGlosado"], $Consulta, "");
        }
        
        if($DatosIniciales["ValorLevantado"]<>$DatosNuevos["ValorLevantado"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "ValorLevantado", $DatosIniciales["ValorLevantado"], $DatosNuevos["ValorLevantado"], $Consulta, "");
        }
        
        if($DatosIniciales["ValorAceptado"]<>$DatosNuevos["ValorAceptado"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "ValorAceptado", $DatosIniciales["ValorAceptado"], $DatosNuevos["ValorAceptado"], $Consulta, "");
        }
        
        if($DatosIniciales["ValorXConciliar"]<>$DatosNuevos["ValorXConciliar"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "ValorXConciliar", $DatosIniciales["ValorXConciliar"], $DatosNuevos["ValorXConciliar"], $Consulta, "");
        }
        
        if($DatosIniciales["ValorConciliado"]<>$DatosNuevos["ValorConciliado"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "ValorConciliado", $DatosIniciales["ValorConciliado"], $DatosNuevos["ValorConciliado"], $Consulta, "");
        }
        
        if($DatosIniciales["EstadoGlosa"]<>$DatosNuevos["EstadoGlosa"]){
            $this->RegistraEdiciones("salud_glosas_iniciales", "EstadoGlosa", $DatosIniciales["EstadoGlosa"], $DatosNuevos["EstadoGlosa"], $Consulta, "");
        }
    }
    
    /**
     * Registra ediciones en las tablas
     * @param type $param
     */
    public function RegistraEdiciones($tabla,$campo,$ValorAnterior,$ValorNuevo,$Consulta,$Vector) {
        $tab="registra_ediciones";
        $NumRegistros=8;

        $Columnas[0]="Fecha";               $Valores[0]=date("Y-m-d");
        $Columnas[1]="Hora";                $Valores[1]=date("H:i:s");
        $Columnas[2]="Tabla";               $Valores[2]=$tabla;
        $Columnas[3]="Campo";               $Valores[3]=$campo;
        $Columnas[4]="ValorAnterior";       $Valores[4]=$ValorAnterior;
        $Columnas[5]="ValorNuevo";	    $Valores[5]=$ValorNuevo;
        $Columnas[6]="ConsultaRealizada";   $Valores[6]=$Consulta;
        $Columnas[7]="idUsuario";	    $Valores[7]=$_SESSION["idUser"];

        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
    }
    
    /**
     * Guarda las respuestas de las glosas que estan en la temporal a la real
     * @param type $idUser
     * @param type $Vector
     */
    public function GuardaRespuestasGlosasTemporalAReal($idUser,$Vector){
        
        //Copio las respuestas cuyo valor Glosado sea diferente al aceptado
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=2 AND valor_glosado_eps <> valor_aceptado_ips" ;
        
        $this->Query($sql);
        //Copio las respuestas en estado 2 (respondida) cuyo valor Glosado sea igual al aceptado pero con la columna tratado =1
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser,Tratado) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,'2',FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser,'1' "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=2 AND valor_glosado_eps=valor_aceptado_ips" ;
        
        $this->Query($sql);
        
        //Copio las respuestas en estado 7 (Aceptada) cuyo valor Glosado sea igual al aceptado pero con la columna tratado =1
      
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,'7',FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=2 AND valor_glosado_eps=valor_aceptado_ips" ;
        
        $this->Query($sql);
        
        $sql="SELECT *,valor_aceptado_ips AS ValorIPS FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=2 ";
        $consulta= $this->Query($sql);
        while($DatosTemp=$this->FetchArray($consulta)){
            $ValorAceptadoIPS=$DatosTemp["ValorIPS"];
            $NumFactura=$DatosTemp["num_factura"];
            $CodigoActividad=$DatosTemp["CodigoActividad"];
            $ValorGlosado=$DatosTemp["valor_glosado_eps"];
            //Actualizo la columna tratado de las respuestas para saber que ya se trató ese registro
            $idGlosa=$DatosTemp["idGlosa"];            
            $sql="UPDATE salud_archivo_control_glosas_respuestas SET Tratado=1 WHERE idGlosa='$idGlosa' AND EstadoGlosa=1";
            $this->Query($sql);
            
            $Estado= $this->CalcularEstadoActividad($NumFactura, $CodigoActividad, "");
            
            
            $Estado=2;
            if($ValorAceptadoIPS==$ValorGlosado){
                $Estado=7;
            }
             
                      
            
            //Actualizo los datos de las glosas iniciales
            $sql="UPDATE salud_glosas_iniciales SET EstadoGlosa='$Estado',ValorAceptado='$ValorAceptadoIPS',ValorXConciliar=ValorGlosado-ValorAceptado WHERE ID='$idGlosa'";
            $this->Query($sql);
            
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            $this->ActualiceEstados($NumFactura, $TipoArchivo, $CodigoActividad, "");
            
            /*
            //Actualizo el estado de las facturas
            $sql="SELECT MIN(EstadoGlosa) as MinEstado FROM salud_archivo_control_glosas_respuestas WHERE num_factura='$NumFactura' AND Tratado=0";
            $Datos=$this->Query($sql);
            $Datos= $this->FetchArray($Datos);
            $EstadoGlosaFactura=$Datos["MinEstado"];
            
            $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", $EstadoGlosaFactura, "num_factura", $NumFactura);
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            //Actualizo el estado de las actividades
            if($TipoArchivo=="AC"){
                
                $this->update("salud_archivo_consultas", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_consulta='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AP"){
               
                $this->update("salud_archivo_procedimientos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_procedimiento='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AT"){
               
                $this->update("salud_archivo_otros_servicios", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_servicio='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AM"){
               
                $this->update("salud_archivo_medicamentos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_medicamento='$CodigoActividad'");
                
            }
             * 
             */
        }
        
        //$this->VaciarTabla("salud_archivo_control_glosas_respuestas_temp");
        
       }
       
       /**
        * Calcula el estado de una actividad
        */
       public function CalcularEstadoActividad($NumFactura,$CodigoActividad,$Vector) {
           $sql="SELECT MIN(EstadoGlosa) as MinEstado FROM salud_archivo_control_glosas_respuestas WHERE num_factura='$NumFactura' AND CodigoActividad='$CodigoActividad' AND Tratado=0";
           $consulta=$this->Query($sql);
           $Datos= $this->FetchArray($consulta);
           $Estado=$Datos["MinEstado"];
           if($Estado==12){
               $Estado=8;
           }
           return($Estado);
       }   
     /**
     * Guarda las  contra glosas que estan en la temporal a la real
     * @param type $idUser
     * @param type $Vector
     */
    public function GuardaContraGlosasTemporalAReal($idUser,$Vector){
        
        //Copio las contra Glosas cuando el valor a conciliar es diferente a cero
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=3 AND valor_levantado_eps <> valor_glosado_eps-valor_aceptado_ips" ;
        
        $this->Query($sql);
        //Copio las contra Glosas en estado 3 (ContraGlosado) cuando el valor x conciliar sea cero, pero con la columna tratado =1
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser,Tratado) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser,'1' "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=3 AND valor_levantado_eps=valor_glosado_eps-valor_aceptado_ips" ;
        
        $this->Query($sql);
        
        //Copio las respuestas en estado 7 (Aceptado) cuyo valor Glosado sea igual al aceptado pero con la columna tratado =1
      
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,'7',FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=3 AND valor_levantado_eps=valor_glosado_eps-valor_aceptado_ips" ;
        
        $this->Query($sql);
        
        $sql="SELECT *,valor_aceptado_ips AS ValorIPS FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=3 ";
        $consulta= $this->Query($sql);
        while($DatosTemp=$this->FetchArray($consulta)){
            $NumFactura=$DatosTemp["num_factura"];
            $CodigoActividad=$DatosTemp["CodigoActividad"];
            $ValorAceptadoIPS=$DatosTemp["ValorIPS"];
            $ValorGlosado=$DatosTemp["valor_glosado_eps"];
            $ValorLevantado=$DatosTemp["valor_levantado_eps"];
            $idGlosa=$DatosTemp["idGlosa"];
            //Actualizo la columna tratado de las respuestas para saber que ya se trató ese registro
            $sql="UPDATE salud_archivo_control_glosas_respuestas SET Tratado=1 WHERE idGlosa='$idGlosa' AND EstadoGlosa=2";
            $this->Query($sql);
           
            
            $Estado=3;
            if($ValorLevantado == $ValorGlosado-$ValorAceptadoIPS){
                $Estado=7;
            }
            
            $ValorXConciliar=$ValorGlosado-$ValorAceptadoIPS-$ValorLevantado;
            
            
            
             //Actualizo los datos de las glosas iniciales
            $sql="UPDATE salud_glosas_iniciales SET ValorLevantado='$ValorLevantado',EstadoGlosa='$Estado',ValorAceptado='$ValorAceptadoIPS',ValorXConciliar='$ValorXConciliar' WHERE ID='$idGlosa'";
            $this->Query($sql);
            
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            $this->ActualiceEstados($NumFactura, $TipoArchivo, $CodigoActividad, "");
            
            /*
            
            //Actualizo el estado de las facturas
            $sql="SELECT MIN(EstadoGlosa) as MinEstado FROM salud_archivo_control_glosas_respuestas WHERE num_factura='$NumFactura' AND Tratado=0";
            $Datos=$this->Query($sql);
            $Datos= $this->FetchArray($Datos);
            $EstadoGlosaFactura=$Datos["MinEstado"];
            
            $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", $EstadoGlosaFactura, "num_factura", $NumFactura);
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            $Estado= $this->CalcularEstadoActividad($NumFactura, $CodigoActividad, "");
            //Actualizo el estado de las actividades
            if($TipoArchivo=="AC"){
                
                $this->update("salud_archivo_consultas", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_consulta='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AP"){
               
                $this->update("salud_archivo_procedimientos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_procedimiento='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AT"){
               
                $this->update("salud_archivo_otros_servicios", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_servicio='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AM"){
               
                $this->update("salud_archivo_medicamentos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_medicamento='$CodigoActividad'");
                
            }
             * 
             */
        }
        
        
        
       }
       /**
        * Guarda las respuestas a las Contra Glosas de la temporal a la real
        * @param type $idUser
        * @param type $Vector
        */
       public function GuardaRespuestaContraGlosasTemporalAReal($idUser,$Vector){
        
        //Copio las contra Glosas cuando el valor a conciliar es diferente a cero
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=4 AND valor_levantado_eps <> valor_glosado_eps-valor_aceptado_ips" ;
        
        $this->Query($sql);
        //Copio las contra Glosas en estado 4 (ReesputaContraGlosa) cuando el valor x conciliar sea cero, pero con la columna tratado =1
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser,Tratado) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser,'1' "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=4 AND valor_levantado_eps=valor_glosado_eps-valor_aceptado_ips" ;
        
        $this->Query($sql);
        
        //Copio las respuestas en estado 5 (Conciliada) cuyo valor Glosado sea igual al aceptado pero con la columna tratado =0
      
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,'7',FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=4 AND valor_levantado_eps=valor_glosado_eps-valor_aceptado_ips" ;
        
        $this->Query($sql);
        
        $sql="SELECT *,valor_aceptado_ips AS ValorIPS FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=4 ";
        $consulta= $this->Query($sql);
        while($DatosTemp=$this->FetchArray($consulta)){
            $NumFactura=$DatosTemp["num_factura"];
            $CodigoActividad=$DatosTemp["CodigoActividad"];
            $ValorAceptadoIPS=$DatosTemp["ValorIPS"];
            $ValorGlosado=$DatosTemp["valor_glosado_eps"];
            $ValorLevantado=$DatosTemp["valor_levantado_eps"];
            
            $idGlosa=$DatosTemp["idGlosa"];
            //Actualizo la columna tratado de las respuestas para saber que ya se trató ese registro
            
            $sql="UPDATE salud_archivo_control_glosas_respuestas SET Tratado=1 WHERE idGlosa='$idGlosa' AND EstadoGlosa=3";
            $this->Query($sql);
           
             
            $Estado=4;
            if($ValorLevantado == $ValorGlosado-$ValorAceptadoIPS){
                $Estado=7;
            }
           
            $ValorXConciliar=$ValorGlosado-$ValorAceptadoIPS-$ValorLevantado;
            
            
            //Actualizo los datos de las glosas iniciales
            $sql="UPDATE salud_glosas_iniciales SET ValorLevantado='$ValorLevantado',EstadoGlosa='$Estado',ValorAceptado='$ValorAceptadoIPS',ValorXConciliar='$ValorXConciliar' WHERE ID='$idGlosa'";
            $this->Query($sql);
            
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            $this->ActualiceEstados($NumFactura, $TipoArchivo, $CodigoActividad, "");
            
            /*
            //Actualizo el estado de las facturas
            $sql="SELECT MIN(EstadoGlosa) as MinEstado FROM salud_archivo_control_glosas_respuestas WHERE num_factura='$NumFactura' AND Tratado=0";
            $Datos=$this->Query($sql);
            $Datos= $this->FetchArray($Datos);
            $EstadoGlosaFactura=$Datos["MinEstado"];
            
            $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", $EstadoGlosaFactura, "num_factura", $NumFactura);
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            $Estado= $this->CalcularEstadoActividad($NumFactura, $CodigoActividad, "");
            //Actualizo el estado de las actividades
            if($TipoArchivo=="AC"){
                
                $this->update("salud_archivo_consultas", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_consulta='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AP"){
               
                $this->update("salud_archivo_procedimientos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_procedimiento='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AT"){
               
                $this->update("salud_archivo_otros_servicios", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_servicio='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AM"){
               
                $this->update("salud_archivo_medicamentos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_medicamento='$CodigoActividad'");
                
            }
             * 
             */
        }
        
        
        
       }
    /**
     * Guarda las conciliaciones
     * @param type $idUser
     * @param type $Vector
     */
    public function GuardaConciliacionesTemporalAReal($idUser,$Vector) {
        //Copio las conciliaciones de la tabla temporal a la real
        $sql="INSERT INTO salud_archivo_control_glosas_respuestas (num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser) "
                . "SELECT "
                . "num_factura, idGlosa,CuentaGlobal,CuentaRIPS,cod_glosa_general,"
                . "cod_glosa_especifico,id_cod_glosa,CodigoActividad,DescripcionActividad,EstadoGlosa,FechaIPS,FechaAuditoria,valor_actividad,"
                . "valor_glosado_eps,valor_levantado_eps,valor_aceptado_ips,observacion_auditor,Soporte,fecha_registo,TipoArchivo,idUser "
                . "FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=5 AND valor_glosado_eps = valor_levantado_eps+valor_aceptado_ips" ;
        
        $this->Query($sql);
                
        $sql="SELECT *,valor_aceptado_ips AS ValorIPS FROM salud_archivo_control_glosas_respuestas_temp WHERE idUser='$idUser' AND EstadoGlosa=5 AND valor_glosado_eps = valor_levantado_eps+valor_aceptado_ips";
        $consulta= $this->Query($sql);
        while($DatosTemp=$this->FetchArray($consulta)){
            $ValorAceptadoIPS=$DatosTemp["ValorIPS"];
            $ValorGlosado=$DatosTemp["valor_glosado_eps"];
            $ValorLevantado=$DatosTemp["valor_levantado_eps"];
            $NumFactura=$DatosTemp["num_factura"];
            $CodigoActividad=$DatosTemp["CodigoActividad"];
            $idGlosa=$DatosTemp["idGlosa"];
            //Actualizo los datos de las glosas iniciales
            $ValorXConciliar=0;
            $sql="UPDATE salud_glosas_iniciales SET ValorLevantado='$ValorLevantado',EstadoGlosa='5',ValorAceptado='$ValorAceptadoIPS',ValorXConciliar='$ValorXConciliar' WHERE ID='$idGlosa'";
            $this->Query($sql);
            
            //Actualizo la columna tratado de las respuestas para saber que ya se trató ese registro
            $sql="UPDATE salud_archivo_control_glosas_respuestas SET Tratado=1 WHERE idGlosa='$idGlosa' AND EstadoGlosa=4";
            $this->Query($sql);
            
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            $this->ActualiceEstados($NumFactura, $TipoArchivo, $CodigoActividad, "");
            
            /*
            //Actualizo el estado de las facturas
            $sql="SELECT MIN(EstadoGlosa) as MinEstado FROM salud_archivo_control_glosas_respuestas WHERE num_factura='$NumFactura' AND Tratado=0";
            $Datos=$this->Query($sql);
            $Datos= $this->FetchArray($Datos);
            $EstadoGlosaFactura=$Datos["MinEstado"];
            
            $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", $EstadoGlosaFactura, "num_factura", $NumFactura);
            $TipoArchivo=$DatosTemp["TipoArchivo"];
            $Estado=$this->CalcularEstadoActividad($NumFactura, $CodigoActividad, "");
            //Actualizo el estado de las actividades
            if($TipoArchivo=="AC"){
                
                $this->update("salud_archivo_consultas", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_consulta='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AP"){
               
                $this->update("salud_archivo_procedimientos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_procedimiento='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AT"){
               
                $this->update("salud_archivo_otros_servicios", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_servicio='$CodigoActividad'");
                
            }
            if($TipoArchivo=="AM"){
               
                $this->update("salud_archivo_medicamentos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_medicamento='$CodigoActividad'");
                
            }
             * 
             */
        }
        
    }   
    /**
     * Funcion para anular una glosa
     * @param type $idGlosa
     * @param type $Vector
     */
    public function AnularGlosa($idGlosa,$Observaciones,$idUser,$SoloRespuesta,$Vector) {
        $Fecha=date("Y-m-d H:i:s");
        $ObservacionAnulacion="Glosa Anulada $Fecha por el usuario: $idUser, Motivo: $Observaciones - ";
        if($SoloRespuesta==0){//Si se desea anular la glosa inicial tambien
            $sql="UPDATE salud_glosas_iniciales SET EstadoGlosa='12' WHERE ID='$idGlosa'";
            $this->Query($sql);
            $sql="UPDATE salud_archivo_control_glosas_respuestas SET EstadoGlosa='12',observacion_auditor=CONCAT('$ObservacionAnulacion',observacion_auditor) WHERE idGlosa='$idGlosa'";
            $this->Query($sql);
        }
        if($SoloRespuesta==1){
            $sql="UPDATE salud_archivo_control_glosas_respuestas SET EstadoGlosa='12',observacion_auditor=CONCAT('$ObservacionAnulacion',observacion_auditor) WHERE ID='$idGlosa'";
            $this->Query($sql);
        }
        
        
    }
    /**
     * Actualiza el estado de una factura
     * @param type $idFactura
     * @param type $Vector
     */
    public function ActualiceEstados($NumFactura,$TipoArchivo,$CodigoActividad,$Vector) {
                
        
        
        //Actualizo el estado de las actividades
        $Estado= $this->CalcularEstadoActividad($NumFactura, $CodigoActividad, "");
        //$sql="SELECT MIN(EstadoGlosa) as MinEstado FROM salud_glosas_iniciales WHERE num_factura='$NumFactura' AND CodigoActividad='$CodigoActividad'";
        //$Datos=$this->Query($sql);
        //$Datos= $this->FetchArray($Datos);
        //$Estado=$Datos["MinEstado"];
        //if($Estado=="" or $Estado==12){
          //  $Estado=8;
        //}
        if($TipoArchivo=="AC"){

            $this->update("salud_archivo_consultas", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_consulta='$CodigoActividad'");

        }
        if($TipoArchivo=="AP"){

            $this->update("salud_archivo_procedimientos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_procedimiento='$CodigoActividad'");

        }
        if($TipoArchivo=="AT"){

            $this->update("salud_archivo_otros_servicios", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_servicio='$CodigoActividad'");

        }
        if($TipoArchivo=="AM"){

            $this->update("salud_archivo_medicamentos", "EstadoGlosa", $Estado, " WHERE num_factura='$NumFactura' AND cod_medicamento='$CodigoActividad'");

        }
        
        
        $sql="SELECT MIN(EstadoGlosa) as MinEstado FROM salud_glosas_iniciales WHERE num_factura='$NumFactura' ";
        $Datos=$this->Query($sql);
        $Datos= $this->FetchArray($Datos);
        $EstadoGlosaFactura=$Datos["MinEstado"];
        if($EstadoGlosaFactura=="" or $EstadoGlosaFactura==12){
            $EstadoGlosaFactura=8;
        }
        $this->ActualizaRegistro("salud_archivo_facturacion_mov_generados", "EstadoGlosa", $EstadoGlosaFactura, "num_factura", $NumFactura);
        
    }
    
    
    public function EditaTablaControlRespuestasGlosas($ID,$TipoArchivo,$idGlosa,$idFactura,$CodActividad,$NombreActividad,$TotalActividad,$EstadoGlosa,$FechaIPS,$FechaAuditoria,$Observaciones,$CodigoGlosa,$ValorEPS,$ValorAceptado,$ValorLevantado,$ValorConciliar,$destino,$idUser,$Vector) {
        $DatosFactura= $this->ValorActual("salud_archivo_facturacion_mov_generados", " CuentaGlobal,CuentaRIPS ", " num_factura='$idFactura'");
        $DatosGlosaControlRespuesta= $this->DevuelveValores("salud_archivo_control_glosas_respuestas", "ID", $ID);
        
        $FechaRegistro=date("Y-m-d");
        $tab="salud_archivo_control_glosas_respuestas";
        $Datos["ID"]=$ID;               
        $Datos["num_factura"]=$idFactura;
        $Datos["idGlosa"]=$idGlosa;
        $Datos["CuentaGlobal"]=$DatosFactura["CuentaGlobal"];
        $Datos["CuentaRIPS"]=$DatosFactura["CuentaRIPS"];
        $Datos["cod_glosa_general"]=substr($CodigoGlosa,0,1);
        $Datos["cod_glosa_especifico"]=substr($CodigoGlosa,1,2);
        $Datos["id_cod_glosa"]=$CodigoGlosa;
        $Datos["CodigoActividad"]=$CodActividad;
        $Datos["EstadoGlosa"]=$EstadoGlosa;
        $Datos["FechaIPS"]=$FechaIPS;
        $Datos["FechaAuditoria"]=$FechaAuditoria;
        $Datos["valor_actividad"]=$TotalActividad;
        $Datos["valor_glosado_eps"]=$ValorEPS;
        $Datos["valor_levantado_eps"]=$ValorLevantado;
        $Datos["valor_aceptado_ips"]=$ValorAceptado;
        $Datos["observacion_auditor"]=$Observaciones;
        $Datos["Soporte"]=$destino;
        $Datos["fecha_registo"]=$FechaRegistro;
        $Datos["TipoArchivo"]=$TipoArchivo;
        $Datos["idUser"]=$idUser;
        $Datos["DescripcionActividad"]=$NombreActividad;
        
        $sql=$this->getSQLReeplace($tab, $Datos);
                
        $this->Query($sql);
        $this->RegistreEdicionesGlosasControlRespuestas($DatosGlosaControlRespuesta, $Datos, "ID = $ID", $idUser);  
    }
    
    /**
     * registra las ediciones en el control de respuestas
     * @param type $DatosIniciales
     * @param type $DatosNuevos
     * @param type $Consulta
     * @param type $idUser
     */
    public function RegistreEdicionesGlosasControlRespuestas($DatosIniciales,$DatosNuevos,$Consulta,$idUser) {
        if($DatosIniciales["id_cod_glosa"]<>$DatosNuevos["id_cod_glosa"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "id_cod_glosa", $DatosIniciales["id_cod_glosa"], $DatosNuevos["id_cod_glosa"], $Consulta, "");
        }
        
        if($DatosIniciales["EstadoGlosa"]<>$DatosNuevos["EstadoGlosa"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "EstadoGlosa", $DatosIniciales["EstadoGlosa"], $DatosNuevos["EstadoGlosa"], $Consulta, "");
        }
        
        if($DatosIniciales["FechaIPS"]<>$DatosNuevos["FechaIPS"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "FechaIPS", $DatosIniciales["FechaIPS"], $DatosNuevos["FechaIPS"], $Consulta, "");
        }
        
        if($DatosIniciales["FechaAuditoria"]<>$DatosNuevos["FechaAuditoria"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "FechaAuditoria", $DatosIniciales["FechaAuditoria"], $DatosNuevos["FechaAuditoria"], $Consulta, "");
        }
        
        if($DatosIniciales["valor_glosado_eps"]<>$DatosNuevos["valor_glosado_eps"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "valor_glosado_eps", $DatosIniciales["valor_glosado_eps"], $DatosNuevos["valor_glosado_eps"], $Consulta, "");
        }
        
        if($DatosIniciales["valor_levantado_eps"]<>$DatosNuevos["valor_levantado_eps"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "valor_levantado_eps", $DatosIniciales["valor_levantado_eps"], $DatosNuevos["valor_levantado_eps"], $Consulta, "");
        }
        
        if($DatosIniciales["valor_aceptado_ips"]<>$DatosNuevos["valor_aceptado_ips"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "valor_aceptado_ips", $DatosIniciales["valor_aceptado_ips"], $DatosNuevos["valor_aceptado_ips"], $Consulta, "");
        }
        
        if($DatosIniciales["observacion_auditor"]<>$DatosNuevos["observacion_auditor"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "observacion_auditor", $DatosIniciales["observacion_auditor"], $DatosNuevos["observacion_auditor"], $Consulta, "");
        }
        
        if($DatosIniciales["Soporte"]<>$DatosNuevos["Soporte"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "Soporte", $DatosIniciales["Soporte"], $DatosNuevos["Soporte"], $Consulta, "");
        }
        
        if($DatosIniciales["fecha_registo"]<>$DatosNuevos["fecha_registo"]){
            $this->RegistraEdiciones("salud_archivo_control_glosas_respuestas", "fecha_registo", $DatosIniciales["fecha_registo"], $DatosNuevos["fecha_registo"], $Consulta, "");
        }
        
    }
    /**
     * Registra Glosa inicial Conciliada
     * @param type $idFactura
     * @param type $idActividad
     * @param type $ValorActividad
     * @param type $FechaIPS
     * @param type $FechaAuditoria
     * @param type $CodigoGlosa
     * @param type $ValorEPS
     * @param type $ValorAceptado
     * @param type $ValorConciliar
     * @param type $Vector
     * @param type $ValorEdicion
     * @return type
     */
    public function RegistrarGlosaInicialConciliada($EstadoGlosa,$idFactura,$idActividad,$ValorActividad,$FechaIPS,$FechaAuditoria,$CodigoGlosa,$ValorEPS,$ValorAceptado,$ValorXConciliar,$ValorLevantado,$Vector) {
        $TotalGlosasExistentes=$this->Sume("salud_glosas_iniciales", "ValorGlosado", " WHERE num_factura='$idFactura' AND CodigoActividad='$idActividad' AND EstadoGlosa<>13 ");
        
        if(($TotalGlosasExistentes+$ValorEPS)>$ValorActividad){
            exit("El valor Glosado Excede el total de la actividad.");
        }
        $FechaRegistro=date("Y-m-d");
        $tab="salud_glosas_iniciales";
        $NumRegistros=13;

        $Columnas[0]="FechaIPS";                $Valores[0]=$FechaIPS;
        $Columnas[1]="FechaAuditoria";          $Valores[1]=$FechaAuditoria;
        $Columnas[2]="FechaRegistro";           $Valores[2]=$FechaRegistro;
        $Columnas[3]="CodigoGlosa";             $Valores[3]=$CodigoGlosa;
        $Columnas[4]="num_factura";             $Valores[4]=$idFactura;
        $Columnas[5]="CodigoActividad";         $Valores[5]=$idActividad;
        $Columnas[6]="EstadoGlosa";             $Valores[6]=$EstadoGlosa;
        $Columnas[7]="ValorGlosado";            $Valores[7]=$ValorEPS;
        $Columnas[8]="ValorLevantado";          $Valores[8]=$ValorLevantado;
        $Columnas[9]="ValorAceptado";           $Valores[9]=$ValorAceptado;
        $Columnas[10]="ValorXConciliar";        $Valores[10]=$ValorXConciliar;
        $Columnas[11]="ValorConciliado";        $Valores[11]=$ValorEPS-$ValorLevantado;
        $Columnas[12]="ValorActividad";         $Valores[12]=$ValorActividad;
        
        $this->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
        $idGlosa=$this->ObtenerMAX($tab, "ID", 1, "");
        
        return($idGlosa);
        
    }
    
    public function ConstruirXMLGlosas($DatosGlosa) {
        $idUser=$_SESSION["idUser"];
        $DatosRuta=$this->DevuelveValores("configuracion_general", "ID", 14);
        $Ruta=$DatosRuta["Valor"];
        $NumeroFactura=$DatosGlosa["num_factura"];
        $TipoDocumento="GLI";
        $nombre_archivo = $TipoDocumento.$NumeroFactura.".xml";
        $RutaArchivo=$Ruta.$nombre_archivo;
        $DatosFactura=$this->DevuelveValores("salud_archivo_facturacion_mov_generados", "num_factura", $NumeroFactura);
        $DatosEPS=$this->DevuelveValores("salud_eps", "cod_pagador_min", $DatosFactura["cod_enti_administradora"]);
        $DatosIPS=$this->DevuelveValores("empresapro", "CodigoPrestadora", $DatosFactura["cod_prest_servicio"]);
        
        $idFactura=$DatosFactura["id_fac_mov_generados"];
        $FechaFacturaXml = date("d/m/Y",strtotime($DatosFactura["fecha_factura"]));
        $AnioFactura = date("Y",strtotime($DatosFactura["fecha_factura"]));
        $MesGlosa = date("m",strtotime($DatosGlosa["FechaRegistro"]));
        $NitIPSXml= number_format($DatosIPS["NIT"],0,",",".");
        $NitIPSXml.="-".$DatosIPS["DV"];
        $RegimenEPS=$DatosEPS["tipo_regimen"];
        $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaInicial='D' LIMIT 1";
        $CuentaPUCDebito=$this->FetchAssoc($this->Query($sql));
        
        $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaInicial='C' LIMIT 1";
        $CuentaPUCCredito=$this->FetchAssoc($this->Query($sql));
        
        $ValorGlosaInicial=round($DatosGlosa["ValorGlosado"],1);
        $ValorGlosaAceptada=round($DatosGlosa["ValorAceptado"],1);
        $ValorGlosaLevantada=round($DatosGlosa["ValorLevantado"],1);
        
        if($ValorGlosaInicial>0){
            if($DatosGlosa["Xml_Glosa_Inicial"]==0){
                $xmlGlosaInicial=$this->ConstruirXMLGlosa($idFactura,$TipoDocumento, $FechaFacturaXml, $NitIPSXml, $DatosIPS["CentroCostos"], $NumeroFactura, $ValorGlosaInicial, $MesGlosa, $CuentaPUCDebito, $CuentaPUCCredito, $DatosEPS["TipoEntidad"], $DatosEPS["nit"], $DatosEPS["nombre_completo"]);
                $this->GuardeArchivoXMLEnLocal($RutaArchivo, $NumeroFactura, $xmlGlosaInicial);
            }
            $idRegistroXML=$this->AgregueRegistroXMLFTP($NumeroFactura, $nombre_archivo, $RutaArchivo, $idUser);
            if($ValorGlosaAceptada>0){
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaAceptada='D' LIMIT 1";
                $CuentaPUCDebito=$this->FetchAssoc($this->Query($sql));
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaAceptada='C' LIMIT 1";
                $CuentaPUCCredito=$this->FetchAssoc($this->Query($sql));
                $DatosTipoDocumento=$this->DevuelveValores("glosas_parametros_interfaz_aqua", "ID", 2);
                $TipoDocumento=$DatosTipoDocumento["PrefijoDocumento"];
                $nombre_archivo = $TipoDocumento.$NumeroFactura.".xml";
                $RutaArchivo=$Ruta.$nombre_archivo;
                $xmlGlosaAceptada=$this->ConstruirXMLGlosa($idFactura,$TipoDocumento, $FechaFacturaXml, $NitIPSXml, $DatosIPS["CentroCostos"], $NumeroFactura, $ValorGlosaAceptada, $MesGlosa, $CuentaPUCDebito, $CuentaPUCCredito, $DatosEPS["TipoEntidad"], $DatosEPS["nit"], $DatosEPS["nombre_completo"]);
                $this->GuardeArchivoXMLEnLocal($RutaArchivo, $NumeroFactura, $xmlGlosaAceptada);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Xml_Glosa_Aceptada", 1, "num_factura", $NumeroFactura);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "NombreArchivoXMLGlosaAceptada", $nombre_archivo, "ID", $idRegistroXML);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Ruta_Xml_GlosaAceptada", $RutaArchivo, "ID", $idRegistroXML);
                
            }
            
            if($ValorGlosaLevantada>0){
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaLevantada='D' LIMIT 1";
                $CuentaPUCDebito=$this->FetchAssoc($this->Query($sql));
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaLevantada='C' LIMIT 1";
                $CuentaPUCCredito=$this->FetchAssoc($this->Query($sql));
                $DatosTipoDocumento=$this->DevuelveValores("glosas_parametros_interfaz_aqua", "ID", 3);
                $TipoDocumento=$DatosTipoDocumento["PrefijoDocumento"];
                $nombre_archivo = $TipoDocumento.$NumeroFactura.".xml";
                $RutaArchivo=$Ruta.$nombre_archivo;
                $xmlGlosaLevantada=$this->ConstruirXMLGlosa($idFactura,$TipoDocumento, $FechaFacturaXml, $NitIPSXml, $DatosIPS["CentroCostos"], $NumeroFactura, $ValorGlosaLevantada, $MesGlosa, $CuentaPUCDebito, $CuentaPUCCredito, $DatosEPS["TipoEntidad"], $DatosEPS["nit"], $DatosEPS["nombre_completo"]);
                $this->GuardeArchivoXMLEnLocal($RutaArchivo, $NumeroFactura, $xmlGlosaLevantada);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Xml_Glosa_Levantada", 1, "num_factura", $NumeroFactura);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "NombreArchivoXMLGlosaLevantada", $nombre_archivo, "ID", $idRegistroXML);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Ruta_Xml_GlosaLevantada", $RutaArchivo, "ID", $idRegistroXML);
                
            }
            
        }
        
        
        
    }
    
    public function ConstruirXMLGlosaInicial($DatosGlosa,$ValorGlosado) {
        $idUser=$_SESSION["idUser"];
        $DatosRuta=$this->DevuelveValores("configuracion_general", "ID", 14);
        $Ruta=$DatosRuta["Valor"];
        $NumeroFactura=$DatosGlosa["num_factura"];
        $DatosTipoDocumento=$this->DevuelveValores("glosas_parametros_interfaz_aqua", "ID", 1);
        $TipoDocumento=$DatosTipoDocumento["PrefijoDocumento"];
        $nombre_archivo = $TipoDocumento.$NumeroFactura.".xml";
        $RutaArchivo=$Ruta.$nombre_archivo;
        $DatosFactura=$this->DevuelveValores("salud_archivo_facturacion_mov_generados", "num_factura", $NumeroFactura);
        $DatosEPS=$this->DevuelveValores("salud_eps", "cod_pagador_min", $DatosFactura["cod_enti_administradora"]);
        $DatosIPS=$this->DevuelveValores("empresapro", "CodigoPrestadora", $DatosFactura["cod_prest_servicio"]);
        
        $idFactura=$DatosFactura["id_fac_mov_generados"];
        $FechaFacturaXml = date("d/m/Y",strtotime($DatosFactura["fecha_factura"]));
        $AnioFactura = date("Y",strtotime($DatosFactura["fecha_factura"]));
        $MesGlosa = date("m",strtotime($DatosGlosa["FechaRegistro"]));
        $NitIPSXml= number_format($DatosIPS["NIT"],0,",",".");
        $NitIPSXml.="-".$DatosIPS["DV"];
        $RegimenEPS=$DatosEPS["tipo_regimen"];
        $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaInicial='D' LIMIT 1";
        $CuentaPUCDebito=$this->FetchAssoc($this->Query($sql));
        
        $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaInicial='C' LIMIT 1";
        $CuentaPUCCredito=$this->FetchAssoc($this->Query($sql));
        
        $ValorGlosaInicial=round($ValorGlosado,1);
               
        if($ValorGlosaInicial>0){
            $xmlGlosaInicial=$this->ConstruirXMLGlosa($idFactura,$TipoDocumento, $FechaFacturaXml, $NitIPSXml, $DatosIPS["CentroCostos"], $NumeroFactura, $ValorGlosaInicial, $MesGlosa, $CuentaPUCDebito, $CuentaPUCCredito, $DatosEPS["TipoEntidad"], $DatosEPS["nit"], $DatosEPS["nombre_completo"]);
            $this->GuardeArchivoXMLEnLocal($RutaArchivo, $NumeroFactura, $xmlGlosaInicial);
            $idRegistroXML=$this->AgregueRegistroXMLFTP($NumeroFactura, $nombre_archivo, $RutaArchivo, $idUser);
                       
        }
        
        
        
    }
    
    public function ConstruirXMLGlosaAceptadaLevantada($DatosGlosa) {
        $idUser=$_SESSION["idUser"];
        $DatosRuta=$this->DevuelveValores("configuracion_general", "ID", 14);
        $Ruta=$DatosRuta["Valor"];
        $NumeroFactura=$DatosGlosa["num_factura"];
        
        $DatosFactura=$this->DevuelveValores("salud_archivo_facturacion_mov_generados", "num_factura", $NumeroFactura);
        $DatosEPS=$this->DevuelveValores("salud_eps", "cod_pagador_min", $DatosFactura["cod_enti_administradora"]);
        $DatosIPS=$this->DevuelveValores("empresapro", "CodigoPrestadora", $DatosFactura["cod_prest_servicio"]);
        
        $idFactura=$DatosFactura["id_fac_mov_generados"];
        $FechaFacturaXml = date("d/m/Y",strtotime($DatosFactura["fecha_factura"]));
        $AnioFactura = date("Y",strtotime($DatosFactura["fecha_factura"]));
        $MesGlosa = date("m",strtotime($DatosGlosa["FechaRegistro"]));
        $NitIPSXml= number_format($DatosIPS["NIT"],0,",",".");
        $NitIPSXml.="-".$DatosIPS["DV"];
        $RegimenEPS=$DatosEPS["tipo_regimen"];
        
        $ValorGlosaAceptada=round($DatosGlosa["ValorAceptado"],1);
        $ValorGlosaLevantada=round($DatosGlosa["ValorLevantado"],1);
        
        if($ValorGlosaAceptada>0){
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaAceptada='D' LIMIT 1";
                $CuentaPUCDebito=$this->FetchAssoc($this->Query($sql));
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaAceptada='C' LIMIT 1";
                $CuentaPUCCredito=$this->FetchAssoc($this->Query($sql));
                $DatosTipoDocumento=$this->DevuelveValores("glosas_parametros_interfaz_aqua", "ID", 2);
                $TipoDocumento=$DatosTipoDocumento["PrefijoDocumento"];
                $nombre_archivo = $TipoDocumento.$NumeroFactura.".xml";
                $RutaArchivo=$Ruta.$nombre_archivo;
                $xmlGlosaAceptada=$this->ConstruirXMLGlosa($idFactura,$TipoDocumento, $FechaFacturaXml, $NitIPSXml, $DatosIPS["CentroCostos"], $NumeroFactura, $ValorGlosaAceptada, $MesGlosa, $CuentaPUCDebito, $CuentaPUCCredito, $DatosEPS["TipoEntidad"], $DatosEPS["nit"], $DatosEPS["nombre_completo"]);
                $this->GuardeArchivoXMLEnLocal($RutaArchivo, $NumeroFactura, $xmlGlosaAceptada);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Xml_Glosa_Aceptada", 1, "num_factura", $NumeroFactura);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "NombreArchivoXMLGlosaAceptada", $nombre_archivo, "ID", $idRegistroXML);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Ruta_Xml_GlosaAceptada", $RutaArchivo, "ID", $idRegistroXML);
                
            }
            
            if($ValorGlosaLevantada>0){
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaLevantada='D' LIMIT 1";
                $CuentaPUCDebito=$this->FetchAssoc($this->Query($sql));
                $sql="SELECT CuentaPUC,DescripcionCuenta FROM parametros_contables_glosas WHERE Regimen='$RegimenEPS' AND Vigencia='$AnioFactura' AND TipoCuentaGlosaLevantada='C' LIMIT 1";
                $CuentaPUCCredito=$this->FetchAssoc($this->Query($sql));
                $DatosTipoDocumento=$this->DevuelveValores("glosas_parametros_interfaz_aqua", "ID", 3);
                $TipoDocumento=$DatosTipoDocumento["PrefijoDocumento"];
                $nombre_archivo = $TipoDocumento.$NumeroFactura.".xml";
                $RutaArchivo=$Ruta.$nombre_archivo;
                $xmlGlosaLevantada=$this->ConstruirXMLGlosa($idFactura,$TipoDocumento, $FechaFacturaXml, $NitIPSXml, $DatosIPS["CentroCostos"], $NumeroFactura, $ValorGlosaLevantada, $MesGlosa, $CuentaPUCDebito, $CuentaPUCCredito, $DatosEPS["TipoEntidad"], $DatosEPS["nit"], $DatosEPS["nombre_completo"]);
                $this->GuardeArchivoXMLEnLocal($RutaArchivo, $NumeroFactura, $xmlGlosaLevantada);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Xml_Glosa_Levantada", 1, "num_factura", $NumeroFactura);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "NombreArchivoXMLGlosaLevantada", $nombre_archivo, "ID", $idRegistroXML);
                $this->ActualizaRegistro("registro_glosas_xml_ftp", "Ruta_Xml_GlosaLevantada", $RutaArchivo, "ID", $idRegistroXML);
                
            }
        
    }
    
    public function GuardeArchivoXMLEnLocal($RutaArchivo,$NumeroFactura,$xml) {
        if(file_exists($RutaArchivo)){
            unlink($RutaArchivo);
        }
        if($archivo = fopen($RutaArchivo, "w")){
            if(!fwrite($archivo, $xml)){
                exit("E1;No se pudo generar el XML para la factura $NumeroFactura");
            }
            fclose($archivo);
        }
    }
    
    public function AgregueRegistroXMLFTP($num_factura,$NombreArchivoXML,$RutaArchivoXML,$idUser) {
        
        $Datos["num_factura"]=$num_factura;
        $Datos["Xml_Glosa_Inicial"]=1;
        $Datos["FechaRegistroGlosaInicial"]=date("Y-m-d H:i:s");
        $Datos["NombreArchivoXMLGlosaInicial"]=$NombreArchivoXML;
        $Datos["Ruta_Xml_GlosaInicial"]=$RutaArchivoXML;
        $Datos["idUser"]=$idUser;
        $sql= $this->getSQLInsert("registro_glosas_xml_ftp", $Datos);
        $this->Query($sql);
        $ID= $this->ObtenerMAX("registro_glosas_xml_ftp", "ID", 1, "");
        return($ID);
    }
    
    public function ConstruirXMLGlosa($idFactura,$TipoDocumento,$FechaFacturaXml,$NitIPSXml,$CentroCostos,$NumeroFactura,$ValorGlosa,$MesGlosa,$CuentaPUCDebito,$CuentaPUCCredito,$TipoEntidadEPS,$NitEPS,$NombreEps) {
        $xmlGlosa='<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<factura>
    <cabecera_asg>
        <tnro>'.$idFactura.'</tnro>
        <tdocu>'.$TipoDocumento.'</tdocu>
        <usucrea>CADUCEOS</usucrea>
        <fechacrea>'.$FechaFacturaXml.'</fechacrea>
        <tmovempcod>'.$NitIPSXml.'</tmovempcod>
        <ccosto>'.$CentroCostos.'</ccosto>
        <tmovdocnro>'.$NumeroFactura.'</tmovdocnro>
        <tmovval>'.$ValorGlosa.'</tmovval>
        <tmovpernro>'.$MesGlosa.'</tmovpernro>
        <tmovemppaic>169</tmovemppaic>
        <detalles>
            <tnro>'.$idFactura.'</tnro>
            <tmovcuecod>'.$CuentaPUCDebito["CuentaPUC"].'</tmovcuecod>
            <tmovmdecpt>'.$CuentaPUCDebito["DescripcionCuenta"].'</tmovmdecpt>
            <tmovmdemov>D</tmovmdemov>
            <tmovccocod>'.$CentroCostos.'</tmovccocod>
            <tmovscocod>'.$TipoEntidadEPS.'</tmovscocod>
            <tmovtercod>'.$NitEPS.'</tmovtercod>
            <tmovternom>'.$NombreEps.'</tmovternom>
            <tmovmdedoc>'.$NumeroFactura.'</tmovmdedoc>
            <tmovmdeval>'.$ValorGlosa.'</tmovmdeval>
        </detalles>
        <detalles>
            <tnro>'.$idFactura.'</tnro>
            <tmovcuecod>'.$CuentaPUCCredito["CuentaPUC"].'</tmovcuecod>
            <tmovmdecpt>'.$CuentaPUCCredito["DescripcionCuenta"].'</tmovmdecpt>
            <tmovmdemov>C</tmovmdemov>
            <tmovccocod>'.$CentroCostos.'</tmovccocod>
            <tmovscocod>'.$TipoEntidadEPS.'</tmovscocod>
            <tmovtercod>'.$NitEPS.'</tmovtercod>
            <tmovternom>'.$NombreEps.'</tmovternom>
            <tmovmdedoc>'.$NumeroFactura.'</tmovmdedoc>
            <tmovmdeval>'.$ValorGlosa.'</tmovmdeval>
        </detalles>	
    </cabecera_asg>
</factura>';
        
        return($xmlGlosa);
        
    }
   
    public function ReportarGlosasXFTP($DatosGlosa,$ReporteGlosaInicial=0) {
        $DatosServidorFTP= $this->DevuelveValores("servidores", "ID", 200);
        $ftp_host=$DatosServidorFTP["IP"];
	$ftp_port=$DatosServidorFTP["Puerto"];
	$ftp_user=$DatosServidorFTP["Usuario"];
	$ftp_password=$DatosServidorFTP["Password"];
        $DatosConfiguracion= $this->DevuelveValores("configuracion_general", "ID", 15);
	$ruta=$DatosConfiguracion["Valor"];
        
	// Realizamos la conexion con el servidor
	$conn_id=@ftp_connect($ftp_host,$ftp_port);
        
	if($conn_id){
		// Realizamos el login con nuestro usuario y contraseña
		if(@ftp_login($conn_id,$ftp_user,$ftp_password)){
                        ftp_pasv($conn_id, true);
			// Canbiamos al directorio especificado
			if(@ftp_chdir($conn_id,$ruta)){
				# Subimos el fichero
                            //@ftp_put($conn_id,$DatosGlosa["NombreArchivoXMLGlosaInicial"],$DatosGlosa["NombreArchivoXMLGlosaInicial"],FTP_BINARY);
                                if($DatosGlosa["Ruta_Xml_GlosaInicial"]<>'' AND $DatosGlosa["GlosaInicialReportadaPorFTP"]==0){
                                    if (!ftp_put($conn_id, $DatosGlosa["NombreArchivoXMLGlosaInicial"], $DatosGlosa["Ruta_Xml_GlosaInicial"], FTP_BINARY)) {
                                        
                                       exit("E1;Hubo un problema durante la transferencia de $DatosGlosa[Ruta_Xml_GlosaInicial]\n");
                                       
                                    } else {
                                        $this->ActualizaRegistro("registro_glosas_xml_ftp", "GlosaInicialReportadaPorFTP", 1, "ID", $DatosGlosa["idRegistroGlosasXmlFtp"]);
                                    }
                                }
                                if($DatosGlosa["Ruta_Xml_GlosaAceptada"]<>''){
                                    if (!ftp_put($conn_id, $DatosGlosa["NombreArchivoXMLGlosaAceptada"], $DatosGlosa["Ruta_Xml_GlosaAceptada"], FTP_BINARY)) {

                                       exit("E1;Hubo un problema durante la transferencia de $DatosGlosa[Ruta_Xml_GlosaAceptada]\n");
                                    }
                                }
                                if($DatosGlosa["Ruta_Xml_GlosaLevantada"]<>''){
                                    if (!ftp_put($conn_id, $DatosGlosa["NombreArchivoXMLGlosaLevantada"], $DatosGlosa["Ruta_Xml_GlosaLevantada"], FTP_BINARY)) {

                                       exit("E1;Hubo un problema durante la transferencia de $DatosGlosa[Ruta_Xml_GlosaLevantada]\n");
                                    }
                                }
                                if($ReporteGlosaInicial==0){
                                    $this->ActualizaRegistro("registro_glosas_xml_ftp", "ReportadoXFtp", 1, "ID", $DatosGlosa["idRegistroGlosasXmlFtp"]);
                                }
                                

                        }else{
                                exit("No existe el directorio especificado en el ftp, ".$ruta);
                        }
                        
                       
		}else
			echo "El usuario o la contraseña son incorrectos";
		# Cerramos la conexion ftp
		ftp_close($conn_id);
	}else{
            exit("No ha sido posible conectar con el servidor");
        }
        
    }
    
    //Fin Clases
}