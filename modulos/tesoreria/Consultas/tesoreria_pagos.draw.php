<?php

session_start();
if (!isset($_SESSION['username'])){// valida que el usuario tenga alguna sesion iniciada 
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];// se crea una variable iduser para saber almacenar que usuario esta tabajando

include_once("../clases/tesoreria.class.php");// se debe incluir la clase del modulo 
include_once("../../../constructores/paginas_constructor.php");// siempre debera de ir ya que utilizara html que esta en el constructor

if(!empty($_REQUEST["Accion"]) ){// se verifica si el indice accion es diferente a vacio 
    
    $css =  new PageConstruct("", "", 1, "", 1, 0);// se instancia para poder utilizar el html
    $obCon = new Tesoreria($idUser);// se instancia para poder conectarse con la base de datos 
    
    switch($_REQUEST["Accion"]) {
       
        case 1://dibuja el listado de los pagos
            
            
            $Limit=20;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $FechaInicialRangos=$obCon->normalizar($_REQUEST["FechaInicialRangos"]);
            $FechaFinalRangos=$obCon->normalizar($_REQUEST["FechaFinalRangos"]);
            $FiltroLegalizados=$obCon->normalizar($_REQUEST["cmbFiltros"]);
            
            $Condicion=" WHERE ID>0 ";
            
            if($Busquedas<>''){
                $Condicion.=" AND (t1.ID = '$Busquedas' or t1.cod_enti_administradora like '%$Busquedas%' or t1.nom_enti_administradora like '%$Busquedas%' or t1.observacion like '%$Busquedas%')";
            }
            if($FechaInicialRangos<>''){
                $Condicion.=" AND (t1.fecha_transaccion >= '$FechaInicialRangos')";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND (t1.fecha_transaccion <= '$FechaFinalRangos')";
            }
            if($FiltroLegalizados=='2'){
                $Condicion.=" AND (t1.legalizado = 'SI')";
            }
            if($FiltroLegalizados=='1'){
                $Condicion.=" AND (t1.legalizado <> 'SI')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items,SUM(valor_transaccion) as TotalPagos,SUM(valor_transaccion) as TotalPagos, SUM(valor_legalizado) as TotalLegalizado,SUM(valor_legalizar) as TotalXLegalizar
                   FROM salud_tesoreria t1 $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
            $TotalPagos=$totales["TotalPagos"];
            $TotalLegalizado=$totales["TotalLegalizado"];
            $TotalXLegalizar=$totales["TotalXLegalizar"];
            
            $sql="SELECT t1.*,
                  (SELECT t2.TipoPago FROM salud_tesoreria_tipos_pago t2 WHERE t2.ID=t1.TipoPago ) as NombreTipoPago  
                  FROM salud_tesoreria t1 $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("Lista de pagos ingresados", "verde");
            
            $css->CrearTabla();
                
            
            $css->FilaTabla(16);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:green>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    
                    print("<td style='text-align:center'>");
                        print("<strong>Total Pagos:</strong><br>");
                        print("".number_format($TotalPagos));
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Total Legalizado:</strong><br>");
                        print("".number_format($TotalLegalizado));
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Total X Legalizar:</strong><br>");
                        print("".number_format($TotalXLegalizar));
                    print("</td>");
                
                    if($ResultadosTotales>$Limit){

                        //$css->FilaTabla(14);
                            
                            $TotalPaginas= ceil($ResultadosTotales/$Limit);
                            print("<td  style=text-align:center>");
                            //print("<strong>Página: </strong>");
                            
                            print('<div class="input-group" style=width:150px>');
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                            print('<span class="input-group-addon" onclick=CambiePagina('.$NumPage1.') style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                            }
                            $FuncionJS="onchange=CambiePagina();";
                            $css->select("CmbPage", "form-control", "CmbPage", "", "", $FuncionJS, "");
                            
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    
                                    $css->option("", "", "", $p, "", "",$sel);
                                        print($p);
                                    $css->Coption();
                                    
                                }

                            $css->Cselect();
                            if($ResultadosTotales>($PuntoInicio+$Limit)){
                                $NumPage1=$NumPage+1;
                            print('<span class="input-group-addon" onclick=CambiePagina('.$NumPage1.') style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
                            }
                            print("</div>");
                            print("</td>");
                            
                            
                          
                        }
            
            $css->FilaTabla(16);
                
                $css->ColTabla("<strong>Editar</strong>", 1, "C"); 
                $css->ColTabla("<strong>Legalizar</strong>", 1, "C");    
                $css->ColTabla("<strong>ID</strong>", 1, "C");
                $css->ColTabla("<strong>Tipo de pago</strong>", 1, "C");
                $css->ColTabla("<strong>Fecha</strong>", 1, "C");
                $css->ColTabla("<strong>Codigo EPS</strong>", 1, "C");
                $css->ColTabla("<strong>Nombre EPS</strong>", 1, "C"); 
                $css->ColTabla("<strong>Número de Transacción</strong>", 1, "C"); 
                $css->ColTabla("<strong>Banco</strong>", 1, "C");
                $css->ColTabla("<strong>Cuenta Banco</strong>", 1, "C");
                $css->ColTabla("<strong>Valor de la Transacción</strong>", 1, "C");
                $css->ColTabla("<strong>Valor Legalizado</strong>", 1, "C");
                $css->ColTabla("<strong>Valor por Legalizar</strong>", 1, "C");
                $css->ColTabla("<strong>Soporte</strong>", 1, "C");
                $css->ColTabla("<strong>Observaciones Tesoreria</strong>", 1, "C");
                $css->ColTabla("<strong>Observaciones Cartera</strong>", 1, "C");
                $css->ColTabla("<strong>Legalizado?</strong>", 1, "C");
                $css->ColTabla("<strong>Usuario</strong>", 1, "C");
            $css->CierraFilaTabla();

            
                while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                    $Soporte=$RegistrosTabla["Soporte"];
                    $Soporte= str_replace("../", "", $Soporte);
                    $Soporte="../../".$Soporte;
                    $idPago=$RegistrosTabla["ID"];
                    $css->FilaTabla(16);
                        print("<td style='text-align:center;font-size:30px;color:blue;cursor:pointer'>");
                            print('<i class="fa fa-fw fa-edit" onclick=AbreFormularioEditarPago(`'.$idPago.'`)></i>');
                        print("</td>");
                        print("<td style='text-align:center;font-size:30px;color:green;cursor:pointer'>");
                            print('<i class="fa fa-fw fa-money" onclick=AbreFormularioLegalizarPago(`'.$idPago.'`)></i>');
                        print("</td>");
                        $css->ColTabla($RegistrosTabla["ID"], 1, "L");
                        $css->ColTabla($RegistrosTabla["NombreTipoPago"], 1, "L");
                        $css->ColTabla($RegistrosTabla["fecha_transaccion"], 1, "L");
                        $css->ColTabla($RegistrosTabla["cod_enti_administradora"], 1, "L");
                        $css->ColTabla(utf8_encode($RegistrosTabla["nom_enti_administradora"]), 1, "L");         
                        $css->ColTabla($RegistrosTabla["num_transaccion"], 1, "L");
                        $css->ColTabla($RegistrosTabla["banco_transaccion"], 1, "L");
                        $css->ColTabla($RegistrosTabla["num_cuenta_banco"], 1, "L");
                        $css->ColTabla(number_format($RegistrosTabla["valor_transaccion"]), 1, "L");
                        $css->ColTabla(number_format($RegistrosTabla["valor_legalizado"]), 1, "L");
                        $css->ColTabla(number_format($RegistrosTabla["valor_legalizar"]), 1, "L");
                        print("<td>");
                            print("<a href='".$Soporte."' target='_blank'>Ver Soporte</a>");
                        print("</td>");
                        //$css->ColTabla($RegistrosTabla["Soporte"], 1, "L");
                        $css->ColTabla(utf8_encode($RegistrosTabla["observacion"]), 1, "L");
                        $css->ColTabla(utf8_encode($RegistrosTabla["observaciones_cartera"]), 1, "L");
                        $css->ColTabla($RegistrosTabla["legalizado"], 1, "L");
                        $css->ColTabla($RegistrosTabla["idUser"], 1, "L");
                    $css->CierraFilaTabla();
                    
                }
            
            $css->CerrarTabla();
            
        break;//fin caso 1
        
        case 2:// formulario para nuevo pago
            
            $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Fecha:</strong>", 1,"C");
                    $css->ColTabla("<strong>EPS:</strong>", 1,"C");
                    $css->ColTabla("<strong>Banco:</strong>", 1,"C");
                    $css->ColTabla("<strong>Número Transacción:</strong>", 1,"C");
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    print("<td>");
                        $css->input("date", "Fecha", "form-control", "Fecha", "Fecha", date("Y-m-d"), "Fecha", "off", "", "","style='line-height: 15px;'");
                        
                    print("</td>");   
                    print("<td>");
                        
                        $css->select("CmbEps", "form-control", "CmbEps", "", "", "", "style=width:400px");
                            $css->option("", "", "", "", "", "");
                                print("Seleccione una EPS");
                            $css->Coption();    
                        $css->Cselect();
                    print("</td>");
                    
                    print("<td>");
                        $sql="SELECT * FROM salud_bancos";
                        $Consulta=$obCon->Query($sql);
                        
                        $css->select("CmbBanco", "form-control", "CmbBanco", "", "", "", "");
                            $css->option("", "", "", "", "", "");
                                print("Seleccione un Banco");
                            $css->Coption(); 
                            while($DatosBanco=$obCon->FetchAssoc($Consulta)){
                                $css->option("", "", "", $DatosBanco["ID"], "", "");
                                    print($DatosBanco["banco_transaccion"]." || ".$DatosBanco["num_cuenta_banco"]." || ".$DatosBanco["tipo_cuenta"]);
                                $css->Coption(); 
                            }
                        $css->Cselect();
                        
                    print("</td>");
                    
                    print("<td>");
                        $css->input("text", "NumeroTransaccion", "form-control", "NumeroTransaccion", "", "", "Numero de transaccion", "off", "", "");
                    print("</td>");
                    
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    print("<td>");
                        $sql="SELECT * FROM salud_tesoreria_tipos_pago";
                            $Consulta=$obCon->Query($sql);

                            $css->select("CmbTipoPago", "form-control", "CmbTipoPago", "", "", "", "");
                                $css->option("", "", "", "", "", "");
                                    print("Seleccione un tipo de Pago");
                                $css->Coption(); 
                                while($DatosTipoPago=$obCon->FetchAssoc($Consulta)){
                                    $css->option("", "", "", $DatosTipoPago["ID"], "", "");
                                        print($DatosTipoPago["TipoPago"]);
                                    $css->Coption(); 
                                }
                            $css->Cselect();
                        print("</td>");
                        
                        
                        print("<td>");
                            $css->input("number", "ValorTransaccion", "form-control", "ValorTransaccion", "", "", "Valor de transaccion", "off", "", "");
                        print("</td>");
                        print("<td>");
                            $css->textarea("Observaciones", "form-control", "Observaciones", "", "Observaciones", "", "");
                            
                            $css->Ctextarea();
                        print("</td>");
                        
                        print("<td>");
                             $css->input("file", "UpSoporte", "", "UpSoporte", "Soporte", "", "Soporte", "off", "", "style=width:100%");
                        print("</td>");
                $css->CierraFilaTabla();
            
                $css->CerrarTabla();
            
            $css->CrearBotonEvento("BtnGuardar", "Guardar", 1, "onclick", "ConfirmaCrearPago()", "rojo");
            
        break;// fin caso 2  
        
        case 3://Formulario para subir los pagos
            
            $idPago=$obCon->normalizar($_REQUEST["idPago"]);
            
            $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);
            
            $css->input("hidden", "idPago", "", "idPago", "", $idPago, "", "", "", "");
            
            $css->ProgressBar("PgProgresoUp", "LyProgresoCMG", "", 0, 0, 100, 0, "0%", "", "");
    
            $Mensaje="LEGALIZAR EL PAGO No. $idPago, de la EPS: <strong> ".$DatosPago["nom_enti_administradora"]." </strong>";
            $css->CrearTitulo($Mensaje, "verde");
            
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>FECHA DEL PAGO</strong>", 1);
                    $css->ColTabla("<strong>VALOR DEL PAGO:</strong>", 1);
                    $css->ColTabla("<strong>VALOR X LEGALIZAR:</strong>", 1);
                    
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    $css->ColTabla($DatosPago["fecha_transaccion"], 1);
                    $css->ColTabla(number_format($DatosPago["valor_transaccion"]), 1);
                    $css->ColTabla(number_format($DatosPago["valor_legalizar"]), 1);
                    
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                
                    print("<td>");
                        print("<strong>Separador de Archivo</strong><br>");
                        $css->select("CmbSeparador", "form-control", "CmbSeparador", "", "", "", "");
                            $css->option("", "", "", "", "", "");
                                print("Selecciones el Separador de los archivos");
                            $css->Coption();
                            $css->option("", "", "", "1", "", "");
                                print("Punto y Coma (;)");
                            $css->Coption();
                            $css->option("", "", "", "2", "", "",1);
                                print("Coma (,)");
                            $css->Coption();

                        $css->Cselect();
                    print("</td>");
                    print("<td colspan=2>");
                        print("<strong>Tipo de Giro</strong><br>");

                        $css->select("CmbTipoGiro", "form-control", "CmbTipoGiro", "", "", "", "");
                            $css->option("", "", "", "", "", "");
                                print("Tipo de Giro");
                            $css->Coption();
                            $css->option("", "", "", "1", "", "");
                                print("Giro Directo Subsidiado");
                            $css->Coption();
                            $css->option("", "", "", "2", "", "",1);
                                print("Cuenta Maestra (Tesoreria)");
                            $css->Coption();
                            $css->option("", "", "", "3", "", "");
                                print("Giro Directo Contributivo");
                            $css->Coption();

                        $css->Cselect();
                        
                    print("</td>");
                $css->CierraFilaTabla();

                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Pagos (AR)</strong>", 1);
                    $css->ColTabla("<strong>Fecha de Giro</strong>", 1);
                    $css->ColTabla("<strong>Soporte de Pago</strong>", 1);
                    
                $css->CierraFilaTabla();


                $css->FilaTabla(16);

                    print("<td>");
                        
                        $css->input("file", "UpPago", "form-control", "UpPago", "Soporte", "", "Soporte", "off", "", "style=width:100%");
                    print("</td>");
                    print("<td colspan=1>");
                        $css->input("date", "TxtFechaGira", "form-control", "TxtFechaGira", "Fecha", date("Y-m-d"), "Fecha", "off", "", "","style='line-height: 15px;'");
                        
                    print("</td>");
                    print("<td>");
                        
                        $css->input("file", "UpSoporte", "form-control", "UpSoporte", "Soporte", "", "Soporte", "off", "", "style=width:100%");
                    print("</td>");
                    $css->FilaTabla(16);
                        print("<td colspan=3>");
                            $css->textarea("TxtObservaciones", "form-control", "", "", "Observaciones", "", "");
                                
                            $css->Ctextarea();
                        print("<td>");
                    $css->CierraFilaTabla();
                    $css->FilaTabla(16);
                    print("<td colspan=3>");
                        $css->CrearBotonEvento("BtnEnviar", "Subir", 1, "onClick", "CargarAR('$idPago')", "rojo", "");
                       
                    print("</td>");
                    $css->CierraFilaTabla();
                $css->CierraFilaTabla();

            $css->CerrarTabla();
        break;//Fin caso 3    
        
    
        case 4:// formulario para editar pago
            
            $idPago=$obCon->normalizar($_REQUEST["idPago"]);
            $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);
            $css->CrearTitulo("<strong>Editar el Pago No. $idPago</strong>", "naranja");
            $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Fecha:</strong>", 1,"C");
                    $css->ColTabla("<strong>EPS:</strong>", 1,"C");
                    $css->ColTabla("<strong>Banco:</strong>", 1,"C");
                    $css->ColTabla("<strong>Número Transacción:</strong>", 1,"C");
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    print("<td>");
                        $css->input("date", "Fecha", "form-control", "Fecha", "Fecha", date("Y-m-d"), "Fecha", "off", $DatosPago["fecha_transaccion"], "","style='line-height: 15px;'");
                        
                    print("</td>");   
                    print("<td>");
                        $DatosEPS=$obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosPago["cod_enti_administradora"]);
                        $css->select("CmbEps", "form-control", "CmbEps", "", "", "", "style=width:400px");
                            $css->option("", "", "", $DatosPago["cod_enti_administradora"], "", "");
                                print($DatosPago["nom_enti_administradora"]);
                            $css->Coption();    
                        $css->Cselect();
                    print("</td>");
                    
                    print("<td>");
                        $sql="SELECT * FROM salud_bancos";
                        $Consulta=$obCon->Query($sql);
                        
                        $css->select("CmbBanco", "form-control", "CmbBanco", "", "", "", "");
                            $css->option("", "", "", "", "", "");
                                print("Seleccione un Banco");
                            $css->Coption(); 
                            while($DatosBanco=$obCon->FetchAssoc($Consulta)){
                                    $Seleccionado=0;
                                if($DatosBanco["banco_transaccion"]==$DatosPago["banco_transaccion"]){
                                    $Seleccionado=1;
                                }
                                $css->option("", "", "", $DatosBanco["ID"], "", "",$Seleccionado);
                                    print($DatosBanco["banco_transaccion"]." || ".$DatosBanco["num_cuenta_banco"]." || ".$DatosBanco["tipo_cuenta"]);
                                $css->Coption(); 
                            }
                        $css->Cselect();
                        
                    print("</td>");
                    
                    print("<td>");
                        $css->input("text", "NumeroTransaccion", "form-control", "NumeroTransaccion", $DatosPago["num_transaccion"], $DatosPago["num_transaccion"], "Numero de transaccion", "off", "", "");
                    print("</td>");
                    
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    print("<td>");
                        $sql="SELECT * FROM salud_tesoreria_tipos_pago";
                            $Consulta=$obCon->Query($sql);

                            $css->select("CmbTipoPago", "form-control", "CmbTipoPago", "", "", "", "");
                                $css->option("", "", "", "", "", "");
                                    print("Seleccione un tipo de Pago");
                                $css->Coption(); 
                                while($DatosTipoPago=$obCon->FetchAssoc($Consulta)){
                                    $Seleccionado=0;
                                    if($DatosTipoPago["ID"]=$DatosPago["TipoPago"]){
                                        $Seleccionado=1;
                                    }
                                    $css->option("", "", "", $DatosTipoPago["ID"], "", "",$Seleccionado);
                                        print($DatosTipoPago["TipoPago"]);
                                    $css->Coption(); 
                                }
                            $css->Cselect();
                        print("</td>");
                        
                        
                        print("<td>");
                            $css->input("number", "ValorTransaccion", "form-control", "ValorTransaccion", "", $DatosPago["valor_transaccion"], "Valor de transaccion", "off", "", "");
                        print("</td>");
                        print("<td>");
                            $css->textarea("Observaciones", "form-control", "Observaciones", "", "Observaciones", "", "");
                                print($DatosPago["observacion"]);
                            $css->Ctextarea();
                        print("</td>");
                        
                        
                $css->CierraFilaTabla();
            
                $css->CerrarTabla();
            
            $css->CrearBotonEvento("BtnGuardar", "Editar", 1, "onclick", "ConfirmaEditarPago('$idPago')", "rojo");
            
        break;// fin caso 4
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>