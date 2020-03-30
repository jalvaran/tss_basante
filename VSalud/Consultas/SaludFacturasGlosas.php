<?php

session_start();
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
//$myPage="titulos_comisiones.php";
include_once("../../modelo/php_conexion.php");

include_once("../css_construct.php");

if(!empty($_REQUEST["idFactura"])){
    $css =  new CssIni("id");
    $obGlosas = new conexion($idUser);
    $idFactura=$obGlosas->normalizar($_REQUEST['idFactura']);
    //print($_REQUEST['idFactura']." $idFactura ".$_REQUEST['t']);
    $DatosFactura=$obGlosas->DevuelveValores("vista_salud_facturas_diferencias", "id_factura_generada", $idFactura);
    $NumFactura=$DatosFactura["num_factura"];
    
    $FechaPago="$DatosFactura[fecha_pago_factura]";
    $FechaRadicado="$DatosFactura[fecha_radicado]";
    $Dias=$obGlosas->CalculeDiferenciaFechas($FechaPago,$FechaRadicado , "");
    $idEps=$DatosFactura["cod_enti_administradora"];
    if($Dias["Dias"]>20){
        $css->CrearNotificacionRoja("! Esta factura tiene mas de 20 dias de radicacion, por lo tanto No aplica para Glosa !", 16);
    }
    /*
        $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>Factura</strong>", 1);
            $css->ColTabla("<strong>FechaFactura</strong>", 1);
            $css->ColTabla("<strong>Total</strong>", 1);
            $css->ColTabla("<strong>ValorPagado</strong>", 1);
            $css->ColTabla("<strong>DiferenciaPago</strong>", 1);
            $css->ColTabla("<strong>FechaRadicado</strong>", 1);
            $css->ColTabla("<strong>FechaPago</strong>", 1);
            $css->ColTabla("<strong>Dias</strong>", 1);
                
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            $css->ColTabla($DatosFactura["num_factura"], 1);
            $css->ColTabla($DatosFactura["fecha_factura"], 1);
            $css->ColTabla($DatosFactura["valor_neto_pagar"], 1);
            $css->ColTabla($DatosFactura["valor_pagado"], 1);
            $css->ColTabla($DatosFactura["DiferenciaEnPago"], 1);
            $css->ColTabla($DatosFactura["fecha_radicado"], 1);
            $css->ColTabla($DatosFactura["fecha_pago_factura"], 1);
            $css->ColTabla($Dias["Dias"], 1);                      
        $css->CierraFilaTabla();
        
        $css->CerrarTabla();
        
     * 
     */
        $css->CrearNotificacionAzul("Items de la Factura ".$NumFactura, 14);
        
        //$css->DivGrid("DivItemsFactura", "container", "left", 1, 1, 3, 90, 100,5,"transparent");
        $css->CrearDiv("DivItemsFactura", "", "center", 1, 1);
            //$css->CerrarTabla();
                $i=0;
                $Datos=$obGlosas->ConsultarTabla("salud_archivo_consultas", " WHERE num_factura='$NumFactura'");                
                while ($DatosArchivo=$obGlosas->FetchArray($Datos)) {
                    $consulta=$obGlosas->ConsultarTabla("salud_registro_glosas", "WHERE num_factura='$NumFactura' AND TablaOrigen='salud_archivo_consultas' AND idArchivo='$DatosArchivo[id_consultas]' ORDER BY ID DESC LIMIT 1");
                    $DatosGlosas=$obGlosas->FetchArray($consulta);
                    
                    $i++;
                    $css->CrearForm2("FrmGlosas".$i, "SaludGlosasDevoluciones.php", "post", "_self");
                        $css->CrearInputText("TxtIndicador", "hidden", "", $i, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("TxtTabla", "hidden", "", "salud_archivo_consultas", "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idArchivo", "hidden", "", $DatosArchivo["id_consultas"], "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("NumFactura", "hidden", "", $NumFactura, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idEps", "hidden", "", $idEps, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idFactura", "hidden", "", $idFactura, "", "", "", "", "", "", 0, 0);
                        
                        $css->CrearTabla();
                            $css->FilaTabla(14);
                            $css->ColTabla("<strong>ITEM $i de la Factura $NumFactura, Tipo: Consultas</strong>", 5);
                            $css->CierraFilaTabla();
                            $css->FilaTabla(14);
                                $css->ColTabla("<strong>Archivo</strong>", 1);
                                $css->ColTabla("<strong>Codigo de la Consulta</strong>", 3);
                                $css->ColTabla("<strong>Valor</strong>", 1);
                            $css->CierraFilaTabla();   
                            $css->FilaTabla(14);
                                $css->ColTabla($DatosArchivo["nom_cargue"], 1);
                                $DatosCups=$obGlosas->DevuelveValores("salud_cups", "codigo_sistema", $DatosArchivo["cod_consulta"]);
                                $css->ColTabla($DatosArchivo["cod_consulta"]." ".$DatosCups["descripcion_cups"], 3);
                                $css->ColTabla(number_format($DatosArchivo["valor_neto_pagar_consulta"]), 1);
                            $css->CierraFilaTabla();   
                            $css->FilaTabla(14);    
                                $css->ColTabla("<strong>Tipo y Cod Glosa</strong>", 1);
                                $css->ColTabla("<strong>Fecha Reporte</strong>", 1);
                                $css->ColTabla("<strong>Soporte</strong>", 1);
                                $css->ColTabla("<strong>Glosas</strong>", 1);
                                $css->ColTabla("<strong>Observaciones</strong>", 1);
                            $css->CierraFilaTabla();
                
                            $css->FilaTabla(12);
                    
                                  print("<td>");
                                  $sel=0;
                                    $css->CrearSelect("CmbTipoGlosa", "");
                                        
                                        $css->CrearOptionSelect("", "Seleccione", $sel);
                                        if($DatosGlosas["TipoGlosa"]==1){
                                            $sel=1;
                                        }
                                        
                                        $css->CrearOptionSelect(1, "Glosa Inicial", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==2){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(2, "Glosa Levantada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==3){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(3, "Glosa Aceptada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==4){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(4, "Glosa X Conciliar", $sel);
                                        $sel=0;
                                    $css->CerrarSelect();   
                                    print("<br>");
                                    $NombreCajaText="CodigoGlosa".$i;
                                    $funcion="EscribaValor('CajaAsigna','$NombreCajaText');ClickElement('ImgBuscar');";
                                    $css->CrearInputText($NombreCajaText, "text", "Click para buscar el codigo:<br>", $DatosGlosas["CodigoGlosa"], $DatosGlosas["CodigoGlosa"], "black", "onclick", "$funcion", 150, 30, 0, 1);
                            
                                print("</td>");
                        
                                print("<td>");
                                    $FechaReporte=date("Y-m-d");
                                    if($DatosGlosas["FechaReporte"]<>''){
                                        $FechaReporte=$DatosGlosas["FechaReporte"];
                                    }
                                    $css->CrearInputText("TxtFechaReporte", "date", "", $FechaReporte, "", "", "", "", 150, 30, 0, 1);
                                print("</td>");
                                print("<td>");
                                    $css->CrearUpload("Soporte");
                                    if($DatosGlosas["Soporte"]<>''){
                                        $Link=$DatosGlosas["Soporte"];
                                        print("<br><br><a href='../$Link' target='_blank'>! Ver Soporte !</a>");
                                    }
                                print("</td>");
                                print("<td>");
                                    $css->CrearInputNumber("TxtValorGlosaEPS", "number", "Glosa EPS:<br>", $DatosGlosas["GlosaEPS"], "Valor EPS", "", "", "", 100, 30, 0, 1, 1, "", "any");
                                    $css->CrearInputNumber("TxtValorGlosaAceptada", "number", "<br>Glosa Aceptada:<br>", $DatosGlosas["GlosaAceptada"], "Valor Aceptado", "", "", "", 100, 30, 0, 0, 0, "", "any");

                                print("</td>");

                                print("<td>");
                                    $css->CrearTextArea("TxtObservaciones", "", $DatosGlosas["Observaciones"], "Observaciones", "", "", "", 200, 100, 0, 1);
                                    print("<br>");
                                    //$funcion="";
                                    //$css->CrearBotonEvento("BtnEnviar", "Registrar", 1, "onClick", $funcion, "rojo", "");
                                    $css->CrearBotonConfirmado("BtnEnviar", "Registrar");
                                    $css->CerrarForm();
                                print("</td>");

                            $css->CierraFilaTabla();
                    
                        $css->CerrarTabla();
                        $css->CerrarForm();
                }
                
                //Archivo de medicamentos
                
                $Datos=$obGlosas->ConsultarTabla("salud_archivo_medicamentos", " WHERE num_factura='$NumFactura'");                
                while ($DatosArchivo=$obGlosas->FetchArray($Datos)) {
                    $consulta=$obGlosas->ConsultarTabla("salud_registro_glosas", "WHERE num_factura='$NumFactura' AND TablaOrigen='salud_archivo_medicamentos' AND idArchivo='$DatosArchivo[id_medicamentos]' ORDER BY ID DESC LIMIT 1");
                    $DatosGlosas=$obGlosas->FetchArray($consulta);
                    $i++;
                    
                    $css->CrearForm2("FrmGlosas".$i, "SaludGlosasDevoluciones.php", "post", "_self");
                        $css->CrearInputText("TxtIndicador", "hidden", "", $i, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("TxtTabla", "hidden", "", "salud_archivo_medicamentos", "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idArchivo", "hidden", "", $DatosArchivo["id_medicamentos"], "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("NumFactura", "hidden", "", $NumFactura, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idEps", "hidden", "", $idEps, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idFactura", "hidden", "", $idFactura, "", "", "", "", "", "", 0, 0);
                        $css->CrearTabla();
                            $css->FilaTabla(14);
                            $css->ColTabla("<strong>ITEM $i de la Factura $NumFactura, Tipo: Medicamentos</strong>", 5);
                            $css->CierraFilaTabla();
                        $css->FilaTabla(14);
                            $css->ColTabla("<strong>Archivo</strong>", 1);
                            $css->ColTabla("<strong>Concepto</strong>", 3);
                            $css->ColTabla("<strong>Valor</strong>", 1);
                        $css->CierraFilaTabla(); 
                        
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosArchivo["nom_cargue"], 1);
                            $css->ColTabla($DatosArchivo["cod_medicamento"]." ".$DatosArchivo["nom_medicamento"], 3);
                            $css->ColTabla(number_format($DatosArchivo["valor_total_medic"]), 1);
                        $css->CierraFilaTabla(); 
                        $css->FilaTabla(14);    
                            $css->ColTabla("<strong>Tipo y Cod Glosa</strong>", 1);
                            $css->ColTabla("<strong>Fecha Reporte</strong>", 1);
                            $css->ColTabla("<strong>Soporte</strong>", 1);
                            $css->ColTabla("<strong>Glosas</strong>", 1);
                            $css->ColTabla("<strong>Observaciones</strong>", 1);
                        $css->CierraFilaTabla();
                        $css->FilaTabla(12);    
                            print("<td>");
                                $sel=0;
                                    $css->CrearSelect("CmbTipoGlosa", "");
                                        
                                        $css->CrearOptionSelect("", "Seleccione", $sel);
                                        if($DatosGlosas["TipoGlosa"]==1){
                                            $sel=1;
                                        }
                                        
                                        $css->CrearOptionSelect(1, "Glosa Inicial", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==2){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(2, "Glosa Levantada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==3){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(3, "Glosa Aceptada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==4){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(4, "Glosa X Conciliar", $sel);
                                        $sel=0;
                                    $css->CerrarSelect();      
                                print("<br>");
                                $NombreCajaText="CodigoGlosa".$i;
                                $funcion="EscribaValor('CajaAsigna','$NombreCajaText');ClickElement('ImgBuscar');";
                                $css->CrearInputText($NombreCajaText, "text", "Click para buscar el codigo:<br>", $DatosGlosas["CodigoGlosa"], $DatosGlosas["CodigoGlosa"], "black", "onclick", "$funcion", 150, 30, 0, 1);
                            
                            print("</td>");
                        
                            print("<td>");
                                    $FechaReporte=date("Y-m-d");
                                    if($DatosGlosas["FechaReporte"]<>''){
                                        $FechaReporte=$DatosGlosas["FechaReporte"];
                                    }
                                    $css->CrearInputText("TxtFechaReporte", "date", "", $FechaReporte, "", "", "", "", 150, 30, 0, 1);
                                print("</td>");
                            print("<td>");
                                    $css->CrearUpload("Soporte");
                                    if($DatosGlosas["Soporte"]<>''){
                                        $Link=$DatosGlosas["Soporte"];
                                        print("<br><br><a href='../$Link' target='_blank'>! Ver Soporte !</a>");
                                    }
                                print("</td>");
                                print("<td>");
                                    $css->CrearInputNumber("TxtValorGlosaEPS", "number", "Glosa EPS:<br>", $DatosGlosas["GlosaEPS"], "Valor EPS", "", "", "", 100, 30, 0, 1, 1, "", "any");
                                    $css->CrearInputNumber("TxtValorGlosaAceptada", "number", "<br>Glosa Aceptada:<br>", $DatosGlosas["GlosaAceptada"], "Valor Aceptado", "", "", "", 100, 30, 0, 0, 0, "", "any");

                                print("</td>");

                                print("<td>");
                                    $css->CrearTextArea("TxtObservaciones", "", $DatosGlosas["Observaciones"], "Observaciones", "", "", "", 200, 100, 0, 1);
                                    print("<br>");
                                //$funcion="";
                                //$css->CrearBotonEvento("BtnEnviar", "Registrar", 1, "onClick", $funcion, "rojo", "");
                                $css->CrearBotonConfirmado("BtnEnviar", "Registrar");
                                $css->CerrarForm();
                            print("</td>");
                        
                    $css->CierraFilaTabla();
                $css->CerrarTabla();
            $css->CerrarForm();
                        
                    
            }
            
            //Archivo de procedimientos
                
                $Datos=$obGlosas->ConsultarTabla("salud_archivo_procedimientos", " WHERE num_factura='$NumFactura'");                
                while ($DatosArchivo=$obGlosas->FetchArray($Datos)) {
                    $consulta=$obGlosas->ConsultarTabla("salud_registro_glosas", "WHERE num_factura='$NumFactura' AND TablaOrigen='salud_archivo_procedimientos' AND idArchivo='$DatosArchivo[id_procedimiento]' ORDER BY ID DESC LIMIT 1");
                    $DatosGlosas=$obGlosas->FetchArray($consulta);
                    
                    $i++;
                    $css->CrearForm2("FrmGlosas".$i, "SaludGlosasDevoluciones.php", "post", "_self");
                        $css->CrearInputText("TxtIndicador", "hidden", "", $i, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("TxtTabla", "hidden", "", "salud_archivo_procedimientos", "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idArchivo", "hidden", "", $DatosArchivo["id_procedimiento"], "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("NumFactura", "hidden", "", $NumFactura, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idEps", "hidden", "", $idEps, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idFactura", "hidden", "", $idFactura, "", "", "", "", "", "", 0, 0);
                        $css->CrearTabla();
                            $css->FilaTabla(14);
                            $css->ColTabla("<strong>ITEM $i de la Factura $NumFactura, Tipo: Procedimientos</strong>", 5);
                            $css->CierraFilaTabla();
                        $css->FilaTabla(14);
                            $css->ColTabla("<strong>Archivo</strong>", 1);
                            $css->ColTabla("<strong>Codigo Procedimiento</strong>", 3);
                            $css->ColTabla("<strong>Valor</strong>", 1);
                        $css->CierraFilaTabla(); 
                        
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosArchivo["nom_cargue"], 1);
                            $DatosCups=$obGlosas->DevuelveValores("salud_cups", "codigo_sistema", $DatosArchivo["cod_procedimiento"]);
                            $css->ColTabla($DatosArchivo["cod_procedimiento"]." ".$DatosCups["descripcion_cups"], 3);
                            $css->ColTabla(number_format($DatosArchivo["valor_procedimiento"]), 1);
                        $css->CierraFilaTabla(); 
                        $css->FilaTabla(14);    
                            $css->ColTabla("<strong>Tipo y Cod Glosa</strong>", 1);
                            $css->ColTabla("<strong>Fecha Reporte</strong>", 1);
                            $css->ColTabla("<strong>Soporte</strong>", 1);
                            $css->ColTabla("<strong>Glosas</strong>", 1);
                            $css->ColTabla("<strong>Observaciones</strong>", 1);
                        $css->CierraFilaTabla();
                        $css->FilaTabla(12);    
                            print("<td>");
                                $sel=0;
                                    $css->CrearSelect("CmbTipoGlosa", "");
                                        
                                        $css->CrearOptionSelect("", "Seleccione", $sel);
                                        if($DatosGlosas["TipoGlosa"]==1){
                                            $sel=1;
                                        }
                                        
                                        $css->CrearOptionSelect(1, "Glosa Inicial", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==2){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(2, "Glosa Levantada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==3){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(3, "Glosa Aceptada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==4){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(4, "Glosa X Conciliar", $sel);
                                        $sel=0;
                                    $css->CerrarSelect();      
                                print("<br>");
                                $NombreCajaText="CodigoGlosa".$i;
                                $funcion="EscribaValor('CajaAsigna','$NombreCajaText');ClickElement('ImgBuscar');";
                                $css->CrearInputText($NombreCajaText, "text", "Click para buscar el codigo:<br>", $DatosGlosas["CodigoGlosa"], $DatosGlosas["CodigoGlosa"], "black", "onclick", "$funcion", 150, 30, 0, 1);
                            
                            print("</td>");
                        
                            print("<td>");
                                    $FechaReporte=date("Y-m-d");
                                    if($DatosGlosas["FechaReporte"]<>''){
                                        $FechaReporte=$DatosGlosas["FechaReporte"];
                                    }
                                    $css->CrearInputText("TxtFechaReporte", "date", "", $FechaReporte, "", "", "", "", 150, 30, 0, 1);
                                print("</td>");
                            print("<td>");
                                    $css->CrearUpload("Soporte");
                                    if($DatosGlosas["Soporte"]<>''){
                                        $Link=$DatosGlosas["Soporte"];
                                        print("<br><br><a href='../$Link' target='_blank'>! Ver Soporte !</a>");
                                    }
                                print("</td>");
                                print("<td>");
                                    $css->CrearInputNumber("TxtValorGlosaEPS", "number", "Glosa EPS:<br>", $DatosGlosas["GlosaEPS"], "Valor EPS", "", "", "", 100, 30, 0, 1, 1, "", "any");
                                    $css->CrearInputNumber("TxtValorGlosaAceptada", "number", "<br>Glosa Aceptada:<br>", $DatosGlosas["GlosaAceptada"], "Valor Aceptado", "", "", "", 100, 30, 0, 0, 0, "", "any");

                                print("</td>");

                                print("<td>");
                                    $css->CrearTextArea("TxtObservaciones", "", $DatosGlosas["Observaciones"], "Observaciones", "", "", "", 200, 100, 0, 1);
                                    print("<br>");
                                //$funcion="";
                                //$css->CrearBotonEvento("BtnEnviar", "Registrar", 1, "onClick", $funcion, "rojo", "");
                                $css->CrearBotonConfirmado("BtnEnviar", "Registrar");
                                $css->CerrarForm();
                            print("</td>");
                        
                    $css->CierraFilaTabla();
                $css->CerrarTabla();
            $css->CerrarForm();
            
            }
            //Archivo de Otros Servicios
                
                $Datos=$obGlosas->ConsultarTabla("salud_archivo_otros_servicios", " WHERE num_factura='$NumFactura'");                
                while ($DatosArchivo=$obGlosas->FetchArray($Datos)) {
                    $consulta=$obGlosas->ConsultarTabla("salud_registro_glosas", "WHERE num_factura='$NumFactura' AND TablaOrigen='salud_archivo_otros_servicios' AND idArchivo='$DatosArchivo[id_otro_servicios]' ORDER BY ID DESC LIMIT 1");
                    $DatosGlosas=$obGlosas->FetchArray($consulta);
                    
                    $i++;
                    $css->CrearForm2("FrmGlosas".$i, "SaludGlosasDevoluciones.php", "post", "_self");
                    
                        $css->CrearInputText("TxtIndicador", "hidden", "", $i, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("TxtTabla", "hidden", "", "salud_archivo_otros_servicios", "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idArchivo", "hidden", "", $DatosArchivo["id_otro_servicios"], "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("NumFactura", "hidden", "", $NumFactura, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idEps", "hidden", "", $idEps, "", "", "", "", "", "", 0, 0);
                        $css->CrearInputText("idFactura", "hidden", "", $idFactura, "", "", "", "", "", "", 0, 0);
                        $css->CrearTabla();
                            $css->FilaTabla(14);
                            $css->ColTabla("<strong>ITEM $i de la Factura $NumFactura, Tipo: Otros Servicios</strong>", 5);
                            $css->CierraFilaTabla();
                        $css->FilaTabla(14);
                            $css->ColTabla("<strong>Archivo</strong>", 1);
                            $css->ColTabla("<strong>Descripcion</strong>", 3);
                            $css->ColTabla("<strong>Valor</strong>", 1);
                        $css->CierraFilaTabla(); 
                        
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosArchivo["nom_cargue"], 1);
                            $css->ColTabla($DatosArchivo["cod_servicio"]." ".$DatosArchivo["nom_servicio"], 3);
                            $css->ColTabla(number_format($DatosArchivo["valor_total_material"]), 1);
                        $css->CierraFilaTabla(); 
                        $css->FilaTabla(14);    
                            $css->ColTabla("<strong>Tipo y Cod Glosa</strong>", 1);
                            $css->ColTabla("<strong>Fecha Reporte</strong>", 1);
                            $css->ColTabla("<strong>Soporte</strong>", 1);
                            $css->ColTabla("<strong>Glosas</strong>", 1);
                            $css->ColTabla("<strong>Observaciones</strong>", 1);
                        $css->CierraFilaTabla();
                        $css->FilaTabla(12);    
                            print("<td>");
                                $sel=0;
                                    $css->CrearSelect("CmbTipoGlosa", "");
                                        
                                        $css->CrearOptionSelect("", "Seleccione", $sel);
                                        if($DatosGlosas["TipoGlosa"]==1){
                                            $sel=1;
                                        }
                                        
                                        $css->CrearOptionSelect(1, "Glosa Inicial", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==2){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(2, "Glosa Levantada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==3){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(3, "Glosa Aceptada", $sel);
                                        $sel=0;
                                        if($DatosGlosas["TipoGlosa"]==4){
                                            $sel=1;
                                        }
                                        $css->CrearOptionSelect(4, "Glosa X Conciliar", $sel);
                                        $sel=0;
                                    $css->CerrarSelect();        
                                print("<br>");
                                $NombreCajaText="CodigoGlosa".$i;
                                $funcion="EscribaValor('CajaAsigna','$NombreCajaText');ClickElement('ImgBuscar');";
                                $css->CrearInputText($NombreCajaText, "text", "Click para buscar el codigo:<br>", $DatosGlosas["CodigoGlosa"], $DatosGlosas["CodigoGlosa"], "black", "onclick", "$funcion", 150, 30, 0, 1);
                            
                            print("</td>");
                        
                            print("<td>");
                                    $FechaReporte=date("Y-m-d");
                                    if($DatosGlosas["FechaReporte"]<>''){
                                        $FechaReporte=$DatosGlosas["FechaReporte"];
                                    }
                                    $css->CrearInputText("TxtFechaReporte", "date", "", $FechaReporte, "", "", "", "", 150, 30, 0, 1);
                                print("</td>");
                            print("<td>");
                                    $css->CrearUpload("Soporte");
                                    if($DatosGlosas["Soporte"]<>''){
                                        $Link=$DatosGlosas["Soporte"];
                                        print("<br><br><a href='../$Link' target='_blank'>! Ver Soporte !</a>");
                                    }
                                print("</td>");
                                print("<td>");
                                    $css->CrearInputNumber("TxtValorGlosaEPS", "number", "Glosa EPS:<br>", $DatosGlosas["GlosaEPS"], "Valor EPS", "", "", "", 100, 30, 0, 1, 1, "", "any");
                                    $css->CrearInputNumber("TxtValorGlosaAceptada", "number", "<br>Glosa Aceptada:<br>", $DatosGlosas["GlosaAceptada"], "Valor Aceptado", "", "", "", 100, 30, 0, 0, 0, "", "any");

                                print("</td>");

                                print("<td>");
                                    $css->CrearTextArea("TxtObservaciones", "", $DatosGlosas["Observaciones"], "Observaciones", "", "", "", 200, 100, 0, 1);
                                    print("<br>");
                                //$funcion="";
                                //$css->CrearBotonEvento("BtnEnviar", "Registrar", 1, "onClick", $funcion, "rojo", "");
                                $css->CrearBotonConfirmado("BtnEnviar", "Registrar");
                                $css->CerrarForm();
                            print("</td>");
                        
                    $css->CierraFilaTabla();
                $css->CerrarTabla();
            $css->CerrarForm();
                    
            }
        $css->CerrarDiv();
        
        
}
$css->AgregaJS(); //Agregamos javascripts
?>