<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");
include_once("../clases/Glosas.class.php");
if( !empty($_REQUEST["TipoReporte"]) ){
    $css =  new CssIni("id",0);
    $obGlosas = new conexion($idUser);
    $TipoReporte=$obGlosas->normalizar($_REQUEST["TipoReporte"]);
    switch ($_REQUEST["TipoReporte"]) {
        case 1: //Pagos totales
            
            $TipoReporte=$obGlosas->normalizar($_REQUEST["TipoReporte"]);
            $idEPS="";
            if(isset($_REQUEST["idEPS"])){
                $idEPS=$obGlosas->normalizar($_REQUEST["idEPS"]);
            }
            $CuentaGlobal='';
            if(isset($_REQUEST["CuentaGlobal"])){
                $CuentaGlobal=$obGlosas->normalizar($_REQUEST["CuentaGlobal"]);
            }
            $FechaInicial="";
            if(isset($_REQUEST["FechaInicial"])){
                $FechaInicial=$obGlosas->normalizar($_REQUEST["FechaInicial"]);
            }
            $FechaFinal="";
            if(isset($_REQUEST["FechaFinal"])){
                $FechaFinal=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
            }
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            $CuentaRIPS="000000";
            if(isset($_REQUEST["CuentaRIPS"])){
                $CuentaRIPS=str_pad($obGlosas->normalizar($_REQUEST["CuentaRIPS"]),6,'0',STR_PAD_LEFT);
            }
            
            //Paginacion
            if(isset($_REQUEST['Page'])){
                $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
            }else{
                $NumPage=1;
            }
            $Condicional=" WHERE (`cod_prest_servicio`<> '') ";
            $Condicional2="";
            if($idEPS<>''){
                $Condicional2=" AND cod_enti_administradora='$idEPS'";
            }
            if($FechaInicial<>''){
                $Condicional2.=$Condicional2." AND fecha_pago_factura >= '$FechaInicial' ";
            }
            if($FechaFinal<>''){
                $Condicional2.=$Condicional2." AND fecha_pago_factura <= '$FechaFinal' ";
            }
            if($CuentaRIPS<>'000000'){
                $Condicional2.=$Condicional2." AND CuentaRIPS = '$CuentaRIPS' ";
            }
            if($CuentaGlobal<>''){
                $Condicional2.=$Condicional2." AND CuentaGlobal = '$CuentaGlobal' ";
            }
            
            $statement=" `vista_salud_facturas_pagas` $Condicional $Condicional2";
            
            if(isset($_REQUEST['st'])){

                $statement= urldecode($_REQUEST['st']);
                //print($statement);
            }
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(*) as `num`,SUM(valor_pagado) AS TotalPagado FROM {$statement}";
            $row = $obGlosas->FetchArray($obGlosas->Query($query));
            $ResultadosTotales = $row['num'];
            $TotalPagado=$row['TotalPagado'];
            $st_reporte=$statement;
            $Limit=" ORDER BY fecha_pago_factura,fecha_factura LIMIT $startpoint,$limit";
            
            $query="SELECT CuentaRIPS,CuentaGlobal,razon_social,num_factura,fecha_factura,fecha_pago_factura,tipo_negociacion,"
                    . "cod_enti_administradora,nom_enti_administradora,ROUND(valor_pagado) AS valor_pagado,CuentaContable ";
            $consulta=$obGlosas->Query("$query FROM $statement $Limit");
            //print("$query FROM $statement");
            if($obGlosas->NumRows($consulta)){
                
                $Resultados=$obGlosas->NumRows($consulta);
        
                $css->CrearTabla();
                $css->FilaTabla(14);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:green>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Total Pagos:</strong> <h4 style=color:green>". number_format($TotalPagado)."</h4>");
                    print("</td>");
                    print("<td colspan='5'>");
                        $css->CrearNotificacionVerde("Facturas Pagadas", 16);
                    print("</td>");
                    print("<td colspan='2' style='text-align:center'>");
                        $st1= urlencode($st_reporte);
                        $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=1&sp=$Separador&st=$st1", "../images/csv.png", "_blank", 50, 50);

                    print("</td>");
                $css->CierraFilaTabla();
                //Paginacion
                if($Resultados){

                    $st= urlencode($st_reporte);
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(14);
                            print("<td colspan='2' style=text-align:center>");
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";

                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                            }
                            print("</td>");
                            $TotalPaginas= ceil($ResultadosTotales/$limit);
                            print("<td colspan=5 style=text-align:center>");
                            print("<strong>Página: </strong>");

                            $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&TipoReporte=$TipoReporte&Page=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivConsultas`,`5`);return false ;";
                            $css->CrearSelect("CmbPage", $FuncionJS,70);
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    $css->CrearOptionSelect($p, "$p", $sel);
                                }

                            $css->CerrarSelect();
                            print("</td>");
                            
                            print("<td colspan='4' style=text-align:center>");
                            if($ResultadosTotales>($startpoint+$limit)){
                                $NumPage1=$NumPage+1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";
                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                            }
                            print("</td>");
                           $css->CierraFilaTabla(); 
                        }
                    }   
                    
                    $css->FilaTabla(14);
                        $css->ColTabla("<strong>CUENTA RIPS</strong>", 1);
                        $css->ColTabla("<strong>CUENTA GLOBAL</strong>", 1);
                        $css->ColTabla("<strong>CÓDIGO EPS</strong>", 1);
                        $css->ColTabla("<strong>EPS</strong>", 1);
                        $css->ColTabla("<strong>FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA PAGO</strong>", 1);
                        $css->ColTabla("<strong>VALOR PAGADO</strong>", 1);
                        $css->ColTabla("<strong>NEGOCIACIÓN</strong>", 1);
                        $css->ColTabla("<strong>CUENTA CONTABLE</strong>", 1);                        
                    $css->CierraFilaTabla();
                    
                    while($DatosConsulta=$obGlosas->FetchAssoc($consulta)){
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosConsulta["CuentaRIPS"], 1);
                            $css->ColTabla($DatosConsulta["CuentaGlobal"], 1);
                            $css->ColTabla($DatosConsulta["cod_enti_administradora"], 1);
                            $css->ColTabla(utf8_encode($DatosConsulta["nom_enti_administradora"]), 1);
                            $css->ColTabla($DatosConsulta["num_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_pago_factura"], 1);
                            $css->ColTabla($DatosConsulta["valor_pagado"], 1);
                            $css->ColTabla($DatosConsulta["tipo_negociacion"], 1);    
                            $css->ColTabla($DatosConsulta["CuentaContable"], 1);      
                        $css->CierraFilaTabla();
                    }
                    
                    $css->CerrarTabla();
                    
                }else{
                    $css->CrearNotificacionAzul("No hay resultados", 14);
                }
        break;
    
        case 2: // Facturas no pagas
            
            
            $idEPS="";
            if(isset($_REQUEST["idEPS"])){
                $idEPS=$obGlosas->normalizar($_REQUEST["idEPS"]);
            }
            $CuentaGlobal='';
            if(isset($_REQUEST["CuentaGlobal"])){
                $CuentaGlobal=$obGlosas->normalizar($_REQUEST["CuentaGlobal"]);
            }
            $FechaInicial="";
            if(isset($_REQUEST["FechaInicial"])){
                $FechaInicial=$obGlosas->normalizar($_REQUEST["FechaInicial"]);
            }
            $FechaFinal="";
            if(isset($_REQUEST["FechaFinal"])){
                $FechaFinal=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
            }
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            
            $CuentaRIPS="000000";
            if(isset($_REQUEST["CuentaRIPS"])){
                $CuentaRIPS=str_pad($obGlosas->normalizar($_REQUEST["CuentaRIPS"]),6,'0',STR_PAD_LEFT);
            }
            
            //Paginacion
            if(isset($_REQUEST['Page'])){
                $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
            }else{
                $NumPage=1;
            }
            $Condicional=" WHERE (`cod_prest_servicio`<> '') ";
            $Condicional2="";
            if($idEPS<>''){
                $Condicional2=" AND cod_enti_administradora='$idEPS'";
            }
            if($FechaInicial<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado >= '$FechaInicial' ";
            }
            if($FechaFinal<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado <= '$FechaFinal' ";
            }
            if($CuentaRIPS<>'000000'){
                $Condicional2.=$Condicional2." AND CuentaRIPS = '$CuentaRIPS' ";
            }
            if($CuentaGlobal<>''){
                $Condicional2.=$Condicional2." AND CuentaGlobal = '$CuentaGlobal' ";
            }
            
            $statement=" `vista_salud_facturas_no_pagas` $Condicional $Condicional2";
            
            if(isset($_REQUEST['st'])){

                $statement= urldecode($_REQUEST['st']);
                //print($statement);
            }
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(*) as `num`,SUM(valor_neto_pagar) AS TotalXPagar FROM {$statement}";
            $row = $obGlosas->FetchArray($obGlosas->Query($query));
            $ResultadosTotales = $row['num'];
            $TotalXPagar=$row['TotalXPagar'];
            $st_reporte=$statement;
            $Limit=" ORDER BY fecha_factura LIMIT $startpoint,$limit";
            
            $query="SELECT CuentaRIPS,CuentaGlobal,razon_social,num_factura,fecha_factura,fecha_radicado,DiasMora ,tipo_negociacion,"
                    . "cod_enti_administradora,nom_enti_administradora,ROUND(valor_neto_pagar) AS valor_neto_pagar,"
                    . "CuentaContable,ValorGlosaInicial,ValorGlosaLevantada,ValorGlosaAceptada,ValorGlosaXConciliar,TotalPagos,SaldoFinalFactura ";
            $consulta=$obGlosas->Query("$query FROM $statement $Limit");
            //print("$query FROM $statement");
            if($obGlosas->NumRows($consulta)){
                
                $Resultados=$obGlosas->NumRows($consulta);
        
                $css->CrearTabla();
                $css->FilaTabla(14);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:red>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Facturas Sin pagar:</strong> <h4 style=color:red>". number_format($TotalXPagar)."</h4>");
                    print("</td>");
                    print("<td colspan='6'>");
                        $css->CrearNotificacionRoja("Facturas No Pagadas", 16);
                    print("</td>");
                    print("<td colspan='2' style='text-align:center'>");
                        $st1= urlencode($st_reporte);
                        $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=$TipoReporte&sp=$Separador&st=$st1", "../images/csv.png", "_blank", 50, 50);

                    print("</td>");
                $css->CierraFilaTabla();
                //Paginacion
                if($Resultados){

                    $st= urlencode($st_reporte);
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(14);
                            print("<td colspan='2' style=text-align:center>");
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";

                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                            }
                            print("</td>");
                            $TotalPaginas= ceil($ResultadosTotales/$limit);
                            print("<td colspan=6 style=text-align:center>");
                            print("<strong>Página: </strong>");

                            $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&TipoReporte=$TipoReporte&Page=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivConsultas`,`5`);return false ;";
                            $css->CrearSelect("CmbPage", $FuncionJS,70);
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    $css->CrearOptionSelect($p, "$p", $sel);
                                }

                            $css->CerrarSelect();
                            print("</td>");
                            
                            print("<td colspan='3' style=text-align:center>");
                            if($ResultadosTotales>($startpoint+$limit)){
                                $NumPage1=$NumPage+1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";
                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                            }
                            print("</td>");
                           $css->CierraFilaTabla(); 
                        }
                    }   
                    
                    $css->FilaTabla(14);
                        $css->ColTabla("<strong>CUENTA RIPS</strong>", 1);
                        $css->ColTabla("<strong>CUENTA GLOBAL</strong>", 1);
                        $css->ColTabla("<strong>CÓDIGO EPS</strong>", 1);
                        $css->ColTabla("<strong>EPS</strong>", 1);
                        $css->ColTabla("<strong>FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA RADICADO</strong>", 1);
                        $css->ColTabla("<strong>DIAS EN MORA</strong>", 1);
                        $css->ColTabla("<strong>VALOR NETO A PAGAR</strong>", 1);
                        $css->ColTabla("<strong>NEGOCIACIÓN</strong>", 1);         
                        $css->ColTabla("<strong>CUENTA CONTABLE</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA INICIAL</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA LEVANTADA</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA ACEPTADA</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA X CONCILIAR</strong>", 1);  
                        $css->ColTabla("<strong>SALDO FINAL</strong>", 1);  
                    $css->CierraFilaTabla();
                    
                    while($DatosConsulta=$obGlosas->FetchAssoc($consulta)){
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosConsulta["CuentaRIPS"], 1);
                            $css->ColTabla($DatosConsulta["CuentaGlobal"], 1);
                            $css->ColTabla($DatosConsulta["cod_enti_administradora"], 1);
                            $css->ColTabla(utf8_encode($DatosConsulta["nom_enti_administradora"]), 1);
                            $css->ColTabla($DatosConsulta["num_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_radicado"], 1);
                            $css->ColTabla($DatosConsulta["DiasMora"], 1);
                            $css->ColTabla(number_format($DatosConsulta["valor_neto_pagar"]), 1);
                            $css->ColTabla($DatosConsulta["tipo_negociacion"], 1); 
                            $css->ColTabla($DatosConsulta["CuentaContable"], 1);  
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaInicial"]), 1);   
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaLevantada"]), 1);   
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaAceptada"]), 1);   
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaXConciliar"]), 1);   
                            $css->ColTabla(number_format($DatosConsulta["SaldoFinalFactura"]), 1);   
                        $css->CierraFilaTabla();
                    }
                    
                    $css->CerrarTabla();
                    
                }else{
                    $css->CrearNotificacionAzul("No hay resultados", 14);
                }
        break;
       
        case 3: // Facturas pagas con diferencias
            
            
            $idEPS="";
            if(isset($_REQUEST["idEPS"])){
                $idEPS=$obGlosas->normalizar($_REQUEST["idEPS"]);
            }
            $CuentaGlobal='';
            if(isset($_REQUEST["CuentaGlobal"])){
                $CuentaGlobal=$obGlosas->normalizar($_REQUEST["CuentaGlobal"]);
            }
            $FechaInicial="";
            if(isset($_REQUEST["FechaInicial"])){
                $FechaInicial=$obGlosas->normalizar($_REQUEST["FechaInicial"]);
            }
            $FechaFinal="";
            if(isset($_REQUEST["FechaFinal"])){
                $FechaFinal=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
            }
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            
            $CuentaRIPS="000000";
            if(isset($_REQUEST["CuentaRIPS"])){
                $CuentaRIPS=str_pad($obGlosas->normalizar($_REQUEST["CuentaRIPS"]),6,'0',STR_PAD_LEFT);
            }
            
            //Paginacion
            if(isset($_REQUEST['Page'])){
                $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
            }else{
                $NumPage=1;
            }
            $Condicional=" WHERE (`cod_prest_servicio`<> '') ";
            $Condicional2="";
            if($idEPS<>''){
                $Condicional2=" AND cod_enti_administradora='$idEPS'";
            }
            if($FechaInicial<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado >= '$FechaInicial' ";
            }
            if($FechaFinal<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado <= '$FechaFinal' ";
            }
            if($CuentaRIPS<>'000000'){
                $Condicional2.=$Condicional2." AND CuentaRIPS = '$CuentaRIPS' ";
            }
            if($CuentaGlobal<>''){
                $Condicional2.=$Condicional2." AND CuentaGlobal = '$CuentaGlobal' ";
            }
            
            $statement=" `vista_salud_facturas_diferencias` $Condicional $Condicional2";
            
            if(isset($_REQUEST['st'])){

                $statement= urldecode($_REQUEST['st']);
                //print($statement);
            }
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(*) as `num`,SUM(valor_pagado) AS TotalXPagar FROM {$statement}";
            $row = $obGlosas->FetchArray($obGlosas->Query($query));
            $ResultadosTotales = $row['num'];
            $TotalXPagar=$row['TotalXPagar'];
            $st_reporte=$statement;
            $Limit=" ORDER BY fecha_factura LIMIT $startpoint,$limit";
            
            $query="SELECT CuentaRIPS,CuentaGlobal,razon_social,num_factura,fecha_factura,fecha_pago_factura,ROUND(valor_pagado) as valor_pagado,ROUND(DiferenciaEnPago) as DiferenciaEnPago,"
                    . "cod_enti_administradora,nom_enti_administradora,ROUND(valor_neto_pagar) AS valor_neto_pagar, "
                    . "CuentaContable,ValorGlosaInicial,ValorGlosaLevantada,ValorGlosaAceptada,ValorGlosaXConciliar ";
            $consulta=$obGlosas->Query("$query FROM $statement $Limit");
            //print("$query FROM $statement");
            if($obGlosas->NumRows($consulta)){
                
                $Resultados=$obGlosas->NumRows($consulta);
        
                $css->CrearTabla();
                $css->FilaTabla(14);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:orange>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Total Pagos:</strong> <h4 style=color:orange>". number_format($TotalXPagar)."</h4>");
                    print("</td>");
                    print("<td colspan='6'>");
                        $css->CrearNotificacionNaranja("Facturas Pagas con diferencias", 16);
                    print("</td>");
                    print("<td colspan='2' style='text-align:center'>");
                        $st1= urlencode($st_reporte);
                        $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=$TipoReporte&sp=$Separador&st=$st1", "../images/csv.png", "_blank", 50, 50);

                    print("</td>");
                $css->CierraFilaTabla();
                //Paginacion
                if($Resultados){

                    $st= urlencode($st_reporte);
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(14);
                            print("<td colspan='2' style=text-align:center>");
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";

                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                            }
                            print("</td>");
                            $TotalPaginas= ceil($ResultadosTotales/$limit);
                            print("<td colspan=5 style=text-align:center>");
                            print("<strong>Página: </strong>");

                            $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&TipoReporte=$TipoReporte&Page=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivConsultas`,`5`);return false ;";
                            $css->CrearSelect("CmbPage", $FuncionJS,70);
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    $css->CrearOptionSelect($p, "$p", $sel);
                                }

                            $css->CerrarSelect();
                            print("</td>");
                            
                            print("<td colspan='3' style=text-align:center>");
                            if($ResultadosTotales>($startpoint+$limit)){
                                $NumPage1=$NumPage+1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";
                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                            }
                            print("</td>");
                           $css->CierraFilaTabla(); 
                        }
                    }   
                    
                    $css->FilaTabla(14);
                        $css->ColTabla("<strong>CUENTA RIPS</strong>", 1);
                        $css->ColTabla("<strong>CUENTA GLOBAL</strong>", 1);
                        $css->ColTabla("<strong>CÓDIGO EPS</strong>", 1);
                        $css->ColTabla("<strong>EPS</strong>", 1);
                        $css->ColTabla("<strong>FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA DE PAGO</strong>", 1);
                        $css->ColTabla("<strong>VALOR NETO A PAGAR</strong>", 1);
                        $css->ColTabla("<strong>VALOR PAGADO</strong>", 1);
                        $css->ColTabla("<strong>DIFERENCIA</strong>", 1);
                        $css->ColTabla("<strong>CUENTA CONTABLE</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA INICIAL</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA LEVANTADA</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA ACEPTADA</strong>", 1);  
                        $css->ColTabla("<strong>GLOSA X CONCILIAR</strong>", 1); 
                    $css->CierraFilaTabla();
                    
                    while($DatosConsulta=$obGlosas->FetchAssoc($consulta)){
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosConsulta["CuentaRIPS"], 1);
                            $css->ColTabla($DatosConsulta["CuentaGlobal"], 1);
                            $css->ColTabla($DatosConsulta["cod_enti_administradora"], 1);
                            $css->ColTabla(utf8_encode($DatosConsulta["nom_enti_administradora"]), 1);
                            $css->ColTabla($DatosConsulta["num_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_pago_factura"], 1);
                            $css->ColTabla(number_format($DatosConsulta["valor_neto_pagar"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["valor_pagado"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["DiferenciaEnPago"]), 1); 
                            $css->ColTabla($DatosConsulta["CuentaContable"], 1);
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaInicial"]), 1);  
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaLevantada"]), 1);  
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaAceptada"]), 1);  
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaXConciliar"]), 1);  
                        $css->CierraFilaTabla();
                    }
                    
                    $css->CerrarTabla();
                    
                }else{
                    $css->CrearNotificacionAzul("No hay resultados", 14);
                }
        break;
        case 4: // Facturas pagadas de posibles vigencias anteriores
            
            
            $idEPS="";
            if(isset($_REQUEST["idEPS"])){
                $idEPS=$obGlosas->normalizar($_REQUEST["idEPS"]);
            }
            
            $FechaInicial="";
            if(isset($_REQUEST["FechaInicial"])){
                $FechaInicial=$obGlosas->normalizar($_REQUEST["FechaInicial"]);
            }
            $FechaFinal="";
            if(isset($_REQUEST["FechaFinal"])){
                $FechaFinal=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
            }
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            
           
            
            //Paginacion
            if(isset($_REQUEST['Page'])){
                $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
            }else{
                $NumPage=1;
            }
            $Condicional=" WHERE (`id_pagados`<> '') ";
            $Condicional2="";
            if($idEPS<>''){
                $Condicional2=" AND idEPS='$idEPS'";
            }
            if($FechaInicial<>''){
                $Condicional2.=$Condicional2." AND fecha_pago_factura >= '$FechaInicial' ";
            }
            if($FechaFinal<>''){
                $Condicional2.=$Condicional2." AND fecha_pago_factura <= '$FechaFinal' ";
            }
            
            
            $statement=" `vista_salud_pagas_no_generadas` $Condicional $Condicional2";
            
            if(isset($_REQUEST['st'])){

                $statement= urldecode($_REQUEST['st']);
                //print($statement);
            }
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(*) as `num`,SUM(valor_pagado) AS TotalXPagar FROM {$statement}";
            $row = $obGlosas->FetchArray($obGlosas->Query($query));
            $ResultadosTotales = $row['num'];
            $TotalXPagar=$row['TotalXPagar'];
            $st_reporte=$statement;
            $Limit=" ORDER BY fecha_pago_factura LIMIT $startpoint,$limit";
            
            $query="SELECT num_factura,fecha_pago_factura,ROUND(valor_pagado) as valor_pagado,tipo_negociacion,"
                    . "idEPS,nom_enti_administradora ";
            $consulta=$obGlosas->Query("$query FROM $statement $Limit");
            //print("$query FROM $statement");
            if($obGlosas->NumRows($consulta)){
                
                $Resultados=$obGlosas->NumRows($consulta);
        
                $css->CrearTabla();
                $css->FilaTabla(14);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:blue>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Total Pagos:</strong> <h4 style=color:blue>". number_format($TotalXPagar)."</h4>");
                    print("</td>");
                    print("<td colspan='3'>");
                        $css->CrearNotificacionAzul("Facturas Pagas de posibles vigencias anteriores", 16);
                    print("</td>");
                    print("<td colspan='1' style='text-align:center'>");
                        $st1= urlencode($st_reporte);
                        $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=$TipoReporte&sp=$Separador&st=$st1", "../images/csv.png", "_blank", 50, 50);

                    print("</td>");
                $css->CierraFilaTabla();
                //Paginacion
                if($Resultados){

                    $st= urlencode($st_reporte);
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(14);
                            print("<td colspan='1' style=text-align:center>");
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";

                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                            }
                            print("</td>");
                            $TotalPaginas= ceil($ResultadosTotales/$limit);
                            print("<td colspan=4 style=text-align:center>");
                            print("<strong>Página: </strong>");

                            $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&TipoReporte=$TipoReporte&Page=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivConsultas`,`5`);return false ;";
                            $css->CrearSelect("CmbPage", $FuncionJS,70);
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    $css->CrearOptionSelect($p, "$p", $sel);
                                }

                            $css->CerrarSelect();
                            print("</td>");
                            
                            print("<td colspan='1' style=text-align:center>");
                            if($ResultadosTotales>($startpoint+$limit)){
                                $NumPage1=$NumPage+1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";
                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                            }
                            print("</td>");
                           $css->CierraFilaTabla(); 
                        }
                    }   
                    
                    $css->FilaTabla(14);
                        $css->ColTabla("<strong>FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA DE PAGO</strong>", 1);
                        $css->ColTabla("<strong>CÓDIGO EPS</strong>", 1);
                        $css->ColTabla("<strong>EPS</strong>", 1);
                        $css->ColTabla("<strong>VALOR PAGADO</strong>", 1);
                        $css->ColTabla("<strong>NEGOCIACION</strong>", 1);
                                                
                    $css->CierraFilaTabla();
                    
                    while($DatosConsulta=$obGlosas->FetchAssoc($consulta)){
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosConsulta["num_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_pago_factura"], 1);
                            $css->ColTabla($DatosConsulta["idEPS"], 1);
                            $css->ColTabla(utf8_encode($DatosConsulta["nom_enti_administradora"]), 1);
                            
                            $css->ColTabla(number_format($DatosConsulta["valor_pagado"]), 1);
                            $css->ColTabla(($DatosConsulta["tipo_negociacion"]), 1);
                                                
                        $css->CierraFilaTabla();
                    }
                    
                    $css->CerrarTabla();
                    
                }else{
                    $css->CrearNotificacionAzul("No hay resultados", 14);
                }
        break;
        
        case 5: // Cartera X Edades
            
            $obGlosas = new Glosas($idUser);
            
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            $CondicionFechaCorte="";
            
            if(isset($_REQUEST["FechaFinal"])){
                $FechaCorte=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
                if($FechaCorte==''){
                    $FechaCorte=date("Y-m-d");
                }
                $CondicionFechaCorte=" AND t1.fecha_radicado<='$FechaCorte' ";
            }
            $sql="DROP VIEW IF EXISTS `vista_salud_carteraxdias_v2`;";
            $obGlosas->Query($sql);
            $sql="CREATE VIEW vista_salud_carteraxdias_v2 AS 
                SELECT t1.`id_fac_mov_generados` as id_factura_generada, 
                (SELECT DATEDIFF('$FechaCorte',t1.`fecha_radicado` ) - (SELECT dias_convenio FROM salud_eps t2 WHERE t2.cod_pagador_min=t1.cod_enti_administradora LIMIT 1)) as DiasMora ,t1.`cod_prest_servicio`,t1.`razon_social`,t1.`num_factura`,
                t1.`fecha_factura`, t1.`fecha_radicado`,t1.`numero_radicado`,t1.`cod_enti_administradora`,t1.`nom_enti_administradora`,t1.`valor_neto_pagar` ,t1.`tipo_negociacion`, 
                t1.`dias_pactados`,t1.`Soporte`,t1.`EstadoCobro`,
                (SELECT tipo_regimen FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) as Regimen,
                (SELECT nit FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) as NIT_EPS,
                (SELECT nombre_completo FROM salud_eps WHERE salud_eps.cod_pagador_min=t1.cod_enti_administradora LIMIT 1) as RazonSocialEPS
                FROM salud_archivo_facturacion_mov_generados t1 WHERE  t1.tipo_negociacion='evento' OR t1.tipo_negociacion='capita' $CondicionFechaCorte AND (t1.estado='RADICADO' OR t1.estado='' OR t1.estado='DIFERENCIA'); ";
            $obGlosas->Query($sql);
            
           $css->CrearTabla();
           $css->FilaTabla(16);
           print("<td colspan=14 style=text-align:center>");
            $css->CrearNotificacionVerde("Cartera X Edades", 16);
           print("</td>");
            print("<td colspan='4' style='text-align:center'>");
                
                $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=$TipoReporte&sp=$Separador", "../images/csv.png", "_blank", 50, 50);

            print("</td>");
            $css->CierraFilaTabla();        
        $css->FilaTabla(16);
            $css->ColTabla("<strong>Cartera X Edades</strong>", 1);
            
            $css->ColTabla("<strong>de 1 a 30</strong>", 2);
            $css->ColTabla("<strong>de 31 a 60</strong>", 2);
            $css->ColTabla("<strong>de 61 a 90</strong>", 2);
            $css->ColTabla("<strong>de 91 a 120</strong>", 2);
            $css->ColTabla("<strong>de 121 a 180</strong>", 2);
            $css->ColTabla("<strong>de 181 a 360</strong>", 2);
            $css->ColTabla("<strong>+361 Dias</strong>", 2);
            $css->ColTabla("<strong>Total</strong>", 2);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong> EPS </strong>", 1);
            
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
            $css->ColTabla("<strong>C</strong>", 1);
            $css->ColTabla("<strong>V</strong>", 1);
        $css->CierraFilaTabla();
        $sql="SELECT cod_enti_administradora, nom_enti_administradora, Regimen,NIT_EPS,RazonSocialEPS FROM vista_salud_carteraxdias_v2 WHERE DiasMora>0 GROUP BY cod_enti_administradora";
        $ConsultaCartera=$obGlosas->Query($sql);
        $CatidadFacturas=0;
        $TotalValor=0;
        $obGlosas->VaciarTabla("salud_cartera_x_edades_temp");
        while($DatosEPS=$obGlosas->FetchArray($ConsultaCartera)){
            unset($Datos);
            $idEPS=$DatosEPS["cod_enti_administradora"];            
            $Datos["idEPS"]=$idEPS;
            $Datos["RazonSocialEPS"]=$DatosEPS["RazonSocialEPS"];
            $Datos["RegimenEPS"]=$DatosEPS["Regimen"];
            $Datos["NIT_EPS"]=$DatosEPS["NIT_EPS"];
            $DiasPactadosEPS=$obGlosas->ValorActual("salud_eps", "dias_convenio", " cod_pagador_min='$idEPS'");
            $DiasPactados=$DiasPactadosEPS["dias_convenio"];
            if($DiasPactados==''){
                $DiasPactados=30;
            }
            $css->FilaTabla(14);
                $css->ColTabla("$DatosEPS[nom_enti_administradora]", 1);
                
                //De 1 a 30 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(1+$DiasPactados) AND DiasMora<=(30+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $Datos["Cantidad_1_30"]=$DatosCartera["NumFacturas"];
                $Datos["Valor_1_30"]=$DatosCartera["Total"];
                $CatidadFacturas=$DatosCartera["NumFacturas"];
                $TotalValor=$DatosCartera["Total"];
                //De 31 a 60 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(31+$DiasPactados) AND DiasMora<=(60+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $Datos["Cantidad_31_60"]=$DatosCartera["NumFacturas"];
                $Datos["Valor_31_60"]=$DatosCartera["Total"];
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 61 a 90 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(61+$DiasPactados) AND DiasMora<=(90+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $Datos["Cantidad_61_90"]=$DatosCartera["NumFacturas"];
                $Datos["Valor_61_90"]=$DatosCartera["Total"];
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 91 a 120 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(91+$DiasPactados) AND DiasMora<=(120+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $Datos["Cantidad_91_120"]=$DatosCartera["NumFacturas"];
                $Datos["Valor_91_120"]=$DatosCartera["Total"];
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 91 a 120 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(121+$DiasPactados) AND DiasMora<=(180+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $Datos["Cantidad_121_180"]=$DatosCartera["NumFacturas"];
                $Datos["Valor_121_180"]=$DatosCartera["Total"];
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // de 181 a 360 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(181+$DiasPactados) AND DiasMora<=(360+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $Datos["Cantidad_181_360"]=$DatosCartera["NumFacturas"];
                $Datos["Valor_181_360"]=$DatosCartera["Total"];
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // >360 dias
                $Condicion=" WHERE cod_enti_administradora='$idEPS' AND (DiasMora>=(361+$DiasPactados)) ";
                $DatosCartera=$obGlosas->CarteraSegunDias($idEPS, $Condicion, "");
                $css->ColTabla(number_format($DatosCartera["NumFacturas"]), 1);
                $css->ColTabla(number_format($DatosCartera["Total"]), 1);
                $Datos["Cantidad_360"]=$DatosCartera["NumFacturas"];
                $Datos["Valor_360"]=$DatosCartera["Total"];
                $CatidadFacturas=$CatidadFacturas+$DatosCartera["NumFacturas"];
                $TotalValor=$TotalValor+$DatosCartera["Total"];
                // Totales
                $css->ColTabla(number_format($CatidadFacturas), 1);
                $css->ColTabla(number_format($TotalValor), 1);
                $Datos["TotalFacturas"]=$DatosCartera["NumFacturas"];
                $Datos["Total"]=$TotalValor;
                $sql_cartera=$obGlosas->getSQLInsert("salud_cartera_x_edades_temp", $Datos);
                $obGlosas->Query($sql_cartera);
            $css->CierraFilaTabla();
        }
            $css->CerrarTabla();
        break;
        
        case 6: // Circular 07
            
            
            $idEPS="";
            if(isset($_REQUEST["idEPS"])){
                $idEPS=$obGlosas->normalizar($_REQUEST["idEPS"]);
            }
            $CuentaGlobal='';
            if(isset($_REQUEST["CuentaGlobal"])){
                $CuentaGlobal=$obGlosas->normalizar($_REQUEST["CuentaGlobal"]);
            }
            $FechaInicial="";
            if(isset($_REQUEST["FechaInicial"])){
                $FechaInicial=$obGlosas->normalizar($_REQUEST["FechaInicial"]);
            }
            $FechaFinal="";
            if(isset($_REQUEST["FechaFinal"])){
                $FechaFinal=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
            }
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            
            $CuentaRIPS="000000";
            if(isset($_REQUEST["CuentaRIPS"])){
                $CuentaRIPS=str_pad($obGlosas->normalizar($_REQUEST["CuentaRIPS"]),6,'0',STR_PAD_LEFT);
            }
            
            //Paginacion
            if(isset($_REQUEST['Page'])){
                $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
            }else{
                $NumPage=1;
            }
            //$Condicional=" WHERE (`SaldoFinalFactura` <> 0) AND Genera07='S' ";
            $Condicional=" WHERE (`SaldoFinalFactura` <> 0)";
            $Condicional2="";
            if($idEPS<>''){
                $Condicional2=" AND cod_enti_administradora='$idEPS'";
            }
            if($FechaInicial<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado >= '$FechaInicial' ";
            }
            if($FechaFinal<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado <= '$FechaFinal' ";
            }
            if($CuentaRIPS<>'000000'){
                $Condicional2.=$Condicional2." AND CuentaRIPS = '$CuentaRIPS' ";
            }
            if($CuentaGlobal<>''){
                $Condicional2.=$Condicional2." AND CuentaGlobal = '$CuentaGlobal' ";
            }
            
            $statement=" `vista_circular_07` $Condicional $Condicional2";
            
            if(isset($_REQUEST['st'])){

                $statement= urldecode($_REQUEST['st']);
                //print($statement);
            }
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(*) as `num`,SUM(SaldoFinalFactura) AS TotalXPagar FROM {$statement}";
            $row = $obGlosas->FetchArray($obGlosas->Query($query));
            $ResultadosTotales = $row['num'];
            $TotalXPagar=$row['TotalXPagar'];
            $st_reporte=$statement;
            $Limit=" ORDER BY fecha_factura LIMIT $startpoint,$limit";
            
            $query="SELECT CuentaRIPS,CuentaGlobal,RegimenEPS,razon_social,num_factura,fecha_factura,FechaVencimiento,fecha_radicado,DiasMora,tipo_negociacion,"
                    . "cod_enti_administradora,nom_enti_administradora,ROUND(valor_neto_pagar) AS valor_neto_pagar,"
                    . "round(SaldoFinalFactura) as SaldoFinalFactura,ROUND(TotalPagos) AS TotalPagos,ValorGlosaAceptada,ValorGlosaLevantada,ValorGlosaInicial,TotalDevolucion ";
            $consulta=$obGlosas->Query("$query FROM $statement $Limit");
            //print("$query FROM $statement");
            if($obGlosas->NumRows($consulta)){
                
                $Resultados=$obGlosas->NumRows($consulta);
        
                $css->CrearTabla();
                $css->FilaTabla(12);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:red>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Saldo Total:</strong> <h4 style=color:red>". number_format($TotalXPagar)."</h4>");
                    print("</td>");
                    print("<td colspan='11'>");
                        $css->CrearNotificacionRoja("Circular 07", 16);
                    print("</td>");
                    print("<td colspan='3' style='text-align:center'>");
                        $st1= urlencode($st_reporte);
                        $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=$TipoReporte&sp=$Separador&st=$st1", "../images/csv.png", "_blank", 50, 50);

                    print("</td>");
                $css->CierraFilaTabla();
                //Paginacion
                if($Resultados){

                    $st= urlencode($st_reporte);
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(12);
                            print("<td colspan='2' style=text-align:center>");
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";

                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                            }
                            print("</td>");
                            $TotalPaginas= ceil($ResultadosTotales/$limit);
                            print("<td colspan=11 style=text-align:center>");
                            print("<strong>Página: </strong>");

                            $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&TipoReporte=$TipoReporte&Page=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivConsultas`,`5`);return false ;";
                            $css->CrearSelect("CmbPage", $FuncionJS,70);
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    $css->CrearOptionSelect($p, "$p", $sel);
                                }

                            $css->CerrarSelect();
                            print("</td>");
                            
                            print("<td colspan='3' style=text-align:center>");
                            if($ResultadosTotales>($startpoint+$limit)){
                                $NumPage1=$NumPage+1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";
                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                            }
                            print("</td>");
                           $css->CierraFilaTabla(); 
                        }
                    }   
                    
                    $css->FilaTabla(12);
                        $css->ColTabla("<strong>CUENTA RIPS</strong>", 1);
                        $css->ColTabla("<strong>CUENTA GLOBAL</strong>", 1);
                        $css->ColTabla("<strong>CÓDIGO EPS</strong>", 1);
                        $css->ColTabla("<strong>EPS</strong>", 1);
                        $css->ColTabla("<strong>RÉGIMEN</strong>", 1);
                        $css->ColTabla("<strong>FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA RADICADO</strong>", 1);
                        $css->ColTabla("<strong>FECHA VENCIMIENTO</strong>", 1);
                        $css->ColTabla("<strong>DIAS EN MORA</strong>", 1);
                        $css->ColTabla("<strong>VALOR NETO A PAGAR</strong>", 1);
                        $css->ColTabla("<strong>GLOSA INICIAL</strong>", 1);
                        $css->ColTabla("<strong>GLOSA LEVANTADA</strong>", 1);
                        $css->ColTabla("<strong>GLOSA ACEPTADA</strong>", 1);
                        $css->ColTabla("<strong>TOTAL PAGOS</strong>", 1);
                        $css->ColTabla("<strong>TOTAL DEVOLUCION</strong>", 1);
                        $css->ColTabla("<strong>SALDO</strong>", 1);
                        $css->ColTabla("<strong>NEGOCIACIÓN</strong>", 1);                    
                    $css->CierraFilaTabla();
                    
                    while($DatosConsulta=$obGlosas->FetchAssoc($consulta)){
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosConsulta["CuentaRIPS"], 1);
                            $css->ColTabla($DatosConsulta["CuentaGlobal"], 1);
                            $css->ColTabla($DatosConsulta["cod_enti_administradora"], 1);
                            $css->ColTabla(utf8_encode($DatosConsulta["nom_enti_administradora"]), 1);
                            $css->ColTabla($DatosConsulta["RegimenEPS"], 1);
                            $css->ColTabla($DatosConsulta["num_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_radicado"], 1);
                            $css->ColTabla($DatosConsulta["FechaVencimiento"], 1);
                            $css->ColTabla($DatosConsulta["DiasMora"], 1);
                            $css->ColTabla(number_format($DatosConsulta["valor_neto_pagar"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaInicial"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaLevantada"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaAceptada"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalPagos"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalDevolucion"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["SaldoFinalFactura"]), 1);
                            $css->ColTabla($DatosConsulta["tipo_negociacion"], 1);
                        $css->CierraFilaTabla();
                    }
                    
                    $css->CerrarTabla();
                    
                }else{
                    $css->CrearNotificacionAzul("No hay resultados", 14);
                }
        break; //Fin caso 6
        
        
         case 7: // Saldos por EPS
            
            
            $idEPS="";
            if(isset($_REQUEST["idEPS"])){
                $idEPS=$obGlosas->normalizar($_REQUEST["idEPS"]);
            }
            $CuentaGlobal='';
            if(isset($_REQUEST["CuentaGlobal"])){
                $CuentaGlobal=$obGlosas->normalizar($_REQUEST["CuentaGlobal"]);
            }
            $FechaInicial="";
            if(isset($_REQUEST["FechaInicial"])){
                $FechaInicial=$obGlosas->normalizar($_REQUEST["FechaInicial"]);
            }
            $FechaFinal="";
            if(isset($_REQUEST["FechaFinal"])){
                $FechaFinal=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
            }
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            
            $CuentaRIPS="000000";
            if(isset($_REQUEST["CuentaRIPS"])){
                $CuentaRIPS=str_pad($obGlosas->normalizar($_REQUEST["CuentaRIPS"]),6,'0',STR_PAD_LEFT);
            }
            
            //Paginacion
            if(isset($_REQUEST['Page'])){
                $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
            }else{
                $NumPage=1;
            }
            //$Condicional=" WHERE (`SaldoFinalFactura` <> 0) AND Genera07='S' ";
            $Condicional=" WHERE (`SaldoEPS` > 0)";
            $Condicional2="";
            if($idEPS<>''){
                $Condicional2=" AND cod_enti_administradora='$idEPS' ";
            }
            if($FechaInicial<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado >= '$FechaInicial' ";
            }
            if($FechaFinal<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado <= '$FechaFinal' ";
            }
            if($CuentaRIPS<>'000000'){
                $Condicional2.=$Condicional2." AND CuentaRIPS = '$CuentaRIPS' ";
            }
            if($CuentaGlobal<>''){
                $Condicional2.=$Condicional2." AND CuentaGlobal = '$CuentaGlobal' ";
            }
            
            $statement=" `vista_saldos_x_eps` $Condicional $Condicional2";
            
            if(isset($_REQUEST['st'])){

                $statement= urldecode($_REQUEST['st']);
                //print($statement);
            }
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(*) as `num`,SUM(SaldoEPS) AS TotalXPagar FROM {$statement}";
            $row = $obGlosas->FetchArray($obGlosas->Query($query));
            $ResultadosTotales = $row['num'];
            $TotalXPagar=$row['TotalXPagar'];
            $st_reporte=$statement;
            $Limit=" ORDER BY SaldoEPS DESC LIMIT $startpoint,$limit";
            
            $query="SELECT * ";
            $consulta=$obGlosas->Query("$query FROM $statement $Limit");
            //print("$query FROM $statement");
            if($obGlosas->NumRows($consulta)){
                
                $Resultados=$obGlosas->NumRows($consulta);
        
                $css->CrearTabla();
                $css->FilaTabla(12);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:red>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Saldo Total:</strong> <h4 style=color:red>". number_format($TotalXPagar)."</h4>");
                    print("</td>");
                    print("<td colspan='11'>");
                        $css->CrearNotificacionRoja("Saldos por EPS", 16);
                    print("</td>");
                    print("<td colspan='3' style='text-align:center'>");
                        $st1= urlencode($st_reporte);
                        $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=$TipoReporte&sp=$Separador&st=$st1", "../images/csv.png", "_blank", 50, 50);

                    print("</td>");
                $css->CierraFilaTabla();
                //Paginacion
                if($Resultados){

                    $st= urlencode($st_reporte);
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(12);
                            print("<td colspan='2' style=text-align:center>");
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";

                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                            }
                            print("</td>");
                            $TotalPaginas= ceil($ResultadosTotales/$limit);
                            print("<td colspan=11 style=text-align:center>");
                            print("<strong>Página: </strong>");

                            $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&TipoReporte=$TipoReporte&Page=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivConsultas`,`5`);return false ;";
                            $css->CrearSelect("CmbPage", $FuncionJS,70);
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    $css->CrearOptionSelect($p, "$p", $sel);
                                }

                            $css->CerrarSelect();
                            print("</td>");
                            
                            print("<td colspan='3' style=text-align:center>");
                            if($ResultadosTotales>($startpoint+$limit)){
                                $NumPage1=$NumPage+1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";
                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                            }
                            print("</td>");
                           $css->CierraFilaTabla(); 
                        }
                    }   
                    
                    $css->FilaTabla(12);
                        $css->ColTabla("<strong>NIT EPS</strong>", 1); 
                        $css->ColTabla("<strong>CÓDIGO EPS</strong>", 1);
                        $css->ColTabla("<strong>EPS</strong>", 1);
                        $css->ColTabla("<strong>RÉGIMEN</strong>", 1);                        
                        $css->ColTabla("<strong>TOTAL FACTURADO</strong>", 1);
                        $css->ColTabla("<strong>GLOSA INICIAL</strong>", 1);
                        $css->ColTabla("<strong>GLOSA LEVANTADA</strong>", 1);
                        $css->ColTabla("<strong>GLOSA ACEPTADA</strong>", 1);
                        $css->ColTabla("<strong>GLOSA X CONCILIAR</strong>", 1);
                        $css->ColTabla("<strong>TOTAL PAGOS</strong>", 1);
                        $css->ColTabla("<strong>TOTAL DEVOLUCION</strong>", 1);
                        $css->ColTabla("<strong>SALDO</strong>", 1);
                                   
                    $css->CierraFilaTabla();
                    
                    while($DatosConsulta=$obGlosas->FetchAssoc($consulta)){
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosConsulta["NitEPS"], 1);
                            $css->ColTabla($DatosConsulta["cod_enti_administradora"], 1);
                            $css->ColTabla(utf8_encode($DatosConsulta["nom_enti_administradora"]), 1);
                            $css->ColTabla($DatosConsulta["RegimenEPS"], 1);
                            
                            $css->ColTabla(number_format($DatosConsulta["TotalFacturado"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalGlosaInicial"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalGlosaLevantada"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalGlosaAceptada"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalGlosaXConciliar"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalPagos"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalDevolucion"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["SaldoEPS"]), 1);
                            
                        $css->CierraFilaTabla();
                    }
                    
                    $css->CerrarTabla();
                    
                }else{
                    $css->CrearNotificacionAzul("No hay resultados", 14);
                }
        break;//Fin caso 7
        
        case 8: // consolidado de facturacion
            
            
            $idEPS="";
            if(isset($_REQUEST["idEPS"])){
                $idEPS=$obGlosas->normalizar($_REQUEST["idEPS"]);
            }
            $CuentaGlobal='';
            if(isset($_REQUEST["CuentaGlobal"])){
                $CuentaGlobal=$obGlosas->normalizar($_REQUEST["CuentaGlobal"]);
            }
            $FechaInicial="";
            if(isset($_REQUEST["FechaInicial"])){
                $FechaInicial=$obGlosas->normalizar($_REQUEST["FechaInicial"]);
            }
            $FechaFinal="";
            if(isset($_REQUEST["FechaFinal"])){
                $FechaFinal=$obGlosas->normalizar($_REQUEST["FechaFinal"]);
            }
            $Separador="";
            if(isset($_REQUEST["Separador"])){
                $Separador=$obGlosas->normalizar($_REQUEST["Separador"]);
            }
            if(isset($_REQUEST["sp"])){
                $Separador=$obGlosas->normalizar($_REQUEST["sp"]);
            }
            
            $CuentaRIPS="000000";
            if(isset($_REQUEST["CuentaRIPS"])){
                $CuentaRIPS=str_pad($obGlosas->normalizar($_REQUEST["CuentaRIPS"]),6,'0',STR_PAD_LEFT);
            }
            
            //Paginacion
            if(isset($_REQUEST['Page'])){
                $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
            }else{
                $NumPage=1;
            }
            //$Condicional=" WHERE (`SaldoFinalFactura` <> 0) AND Genera07='S' ";
            $Condicional=" WHERE (`CuentaRIPS` >= 0)";
            $Condicional2="";
            if($idEPS<>''){
                $Condicional2=" AND cod_enti_administradora='$idEPS'";
            }
            if($FechaInicial<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado >= '$FechaInicial' ";
            }
            if($FechaFinal<>''){
                $Condicional2.=$Condicional2." AND fecha_radicado <= '$FechaFinal' ";
            }
            if($CuentaRIPS<>'000000'){
                $Condicional2.=$Condicional2." AND CuentaRIPS = '$CuentaRIPS' ";
            }
            if($CuentaGlobal<>''){
                $Condicional2.=$Condicional2." AND CuentaGlobal = '$CuentaGlobal' ";
            }
            
            $statement=" `vista_consolidado_facturacion` $Condicional $Condicional2";
            
            if(isset($_REQUEST['st'])){

                $statement= urldecode($_REQUEST['st']);
                //print($statement);
            }
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(*) as `num`,SUM(SaldoFinalFactura) AS TotalXPagar FROM {$statement}";
            $row = $obGlosas->FetchArray($obGlosas->Query($query));
            $ResultadosTotales = $row['num'];
            $TotalXPagar=$row['TotalXPagar'];
            $st_reporte=$statement;
            $Limit=" ORDER BY SaldoFinalFactura DESC LIMIT $startpoint,$limit";
            
            $query="SELECT * ";
            $consulta=$obGlosas->Query("$query FROM $statement $Limit");
            //print("$query FROM $statement");
            if($obGlosas->NumRows($consulta)){
                
                $Resultados=$obGlosas->NumRows($consulta);
        
                $css->CrearTabla();
                $css->FilaTabla(12);
                    print("<td style='text-align:center'>");
                        print("<strong>Registros:</strong> <h4 style=color:red>". number_format($ResultadosTotales)."</h4>");
                    print("</td>");
                    print("<td style='text-align:center'>");
                        print("<strong>Saldo Total:</strong> <h4 style=color:red>". number_format($TotalXPagar)."</h4>");
                    print("</td>");
                    print("<td colspan='11'>");
                        $css->CrearNotificacionRoja("Circular 07", 16);
                    print("</td>");
                    print("<td colspan='3' style='text-align:center'>");
                        $st1= urlencode($st_reporte);
                        $css->CrearImageLink("ProcesadoresJS/GeneradorCSVReportesCartera.php?Opcion=$TipoReporte&sp=$Separador&st=$st1", "../images/csv.png", "_blank", 50, 50);

                    print("</td>");
                $css->CierraFilaTabla();
                //Paginacion
                if($Resultados){

                    $st= urlencode($st_reporte);
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(12);
                            print("<td colspan='2' style=text-align:center>");
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";

                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                            }
                            print("</td>");
                            $TotalPaginas= ceil($ResultadosTotales/$limit);
                            print("<td colspan=11 style=text-align:center>");
                            print("<strong>Página: </strong>");

                            $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&TipoReporte=$TipoReporte&Page=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivConsultas`,`5`);return false ;";
                            $css->CrearSelect("CmbPage", $FuncionJS,70);
                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }
                                    $css->CrearOptionSelect($p, "$p", $sel);
                                }

                            $css->CerrarSelect();
                            print("</td>");
                            
                            print("<td colspan='3' style=text-align:center>");
                            if($ResultadosTotales>($startpoint+$limit)){
                                $NumPage1=$NumPage+1;
                                $Page="Consultas/SaludReportesCartera.draw.php?st=$st&sp=$Separador&Page=$NumPage1&TipoReporte=$TipoReporte&Carry=";
                                $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivConsultas`,`5`);return false ;";
                                $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                            }
                            print("</td>");
                           $css->CierraFilaTabla(); 
                        }
                    }   
                    
                    $css->FilaTabla(12);
                        $css->ColTabla("<strong>CUENTA RIPS</strong>", 1);
                        $css->ColTabla("<strong>CUENTA GLOBAL</strong>", 1);
                        $css->ColTabla("<strong>CUENTA CONTABLE</strong>", 1);
                        $css->ColTabla("<strong>NIT EPS</strong>", 1);
                        $css->ColTabla("<strong>CÓDIGO EPS</strong>", 1);
                        $css->ColTabla("<strong>EPS</strong>", 1);
                        $css->ColTabla("<strong>RÉGIMEN</strong>", 1);
                        $css->ColTabla("<strong>FACTURA</strong>", 1);
                        $css->ColTabla("<strong>FECHA RADICADO</strong>", 1);
                        $css->ColTabla("<strong>NUMERO RADICADO</strong>", 1);
                        $css->ColTabla("<strong>FECHA VENCIMIENTO</strong>", 1);
                        $css->ColTabla("<strong>DIAS EN MORA</strong>", 1);
                        $css->ColTabla("<strong>VALOR NETO A PAGAR</strong>", 1);
                        $css->ColTabla("<strong>GLOSA INICIAL</strong>", 1);
                        $css->ColTabla("<strong>GLOSA LEVANTADA</strong>", 1);
                        $css->ColTabla("<strong>GLOSA ACEPTADA</strong>", 1);
                        $css->ColTabla("<strong>TOTAL PAGOS</strong>", 1);
                        $css->ColTabla("<strong>TOTAL DEVOLUCION</strong>", 1);
                        $css->ColTabla("<strong>SALDO</strong>", 1);
                        $css->ColTabla("<strong>NEGOCIACIÓN</strong>", 1);                    
                    $css->CierraFilaTabla();
                    
                    while($DatosConsulta=$obGlosas->FetchAssoc($consulta)){
                        $css->FilaTabla(12);
                            $css->ColTabla($DatosConsulta["CuentaRIPS"], 1);
                            $css->ColTabla($DatosConsulta["CuentaGlobal"], 1);
                            $css->ColTabla($DatosConsulta["CuentaContable"], 1);
                            $css->ColTabla($DatosConsulta["NitEPS"], 1);
                            $css->ColTabla($DatosConsulta["cod_enti_administradora"], 1);
                            $css->ColTabla(utf8_encode($DatosConsulta["nom_enti_administradora"]), 1);
                            $css->ColTabla($DatosConsulta["RegimenEPS"], 1);
                            $css->ColTabla($DatosConsulta["num_factura"], 1);
                            $css->ColTabla($DatosConsulta["fecha_radicado"], 1);
                            $css->ColTabla($DatosConsulta["numero_radicado"], 1);
                            $css->ColTabla($DatosConsulta["FechaVencimiento"], 1);
                            $css->ColTabla($DatosConsulta["DiasMora"], 1);
                            $css->ColTabla(number_format($DatosConsulta["valor_neto_pagar"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaInicial"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaLevantada"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["ValorGlosaAceptada"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalPagos"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["TotalDevolucion"]), 1);
                            $css->ColTabla(number_format($DatosConsulta["SaldoFinalFactura"]), 1);
                            $css->ColTabla($DatosConsulta["tipo_negociacion"], 1);
                        $css->CierraFilaTabla();
                    }
                    
                    $css->CerrarTabla();
                    
                }else{
                    $css->CrearNotificacionAzul("No hay resultados", 14);
                }
        break;//Fin caso 8
    }
          
}else{
    print("No se enviaron parametros");
}
?>