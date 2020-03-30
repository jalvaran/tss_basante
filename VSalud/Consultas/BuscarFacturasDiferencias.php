<?php

session_start();
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
//$myPage="titulos_comisiones.php";
include_once("../../modelo/php_conexion.php");

include_once("../css_construct.php");


if(!empty($_REQUEST["idEPS"]) or !empty($_REQUEST["idFactura"]) or !empty($_REQUEST["Page"])){
    $css =  new CssIni("id");
    $obGlosas = new conexion($idUser);
    
    // Consultas enviadas a traves de la URL
    $statement="";
    if(isset($_REQUEST['st'])){

        $statement= base64_decode($_REQUEST['st']);
        //print($statement);
    }
    
    //Paginacion
    if(isset($_REQUEST['Page'])){
        $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
    }else{
        $NumPage=1;
    }
    
    //////////////////

    if(isset($_REQUEST["idEPS"])){
        $idEPS=$obGlosas->normalizar($_REQUEST['idEPS']);
        $statement=" vista_salud_facturas_diferencias WHERE cod_enti_administradora='$idEPS' ORDER BY DiferenciaEnPago DESC";
    }
    if(isset($_REQUEST["idFactura"])){
        $NumFactura=$obGlosas->normalizar($_REQUEST['idFactura']);
        $css->CrearNotificacionAzul("Resultados de la Busqueda ".$NumFactura, 16);
        $statement=" vista_salud_facturas_diferencias WHERE num_factura='$NumFactura' ORDER BY DiferenciaEnPago DESC";
    }
    //Paginacion
    $limit = 20;
    $startpoint = ($NumPage * $limit) - $limit;
    $VectorST = explode("LIMIT", $statement);
    $statement = $VectorST[0]; 
    $query = "SELECT COUNT(*) as `num` FROM {$statement}";
    $row = $obGlosas->FetchArray($obGlosas->Query($query));
    $ResultadosTotales = $row['num'];
        
    $statement.=" LIMIT $startpoint,$limit";
    
    //print("st:$statement");
    $consulta=$obGlosas->Query("SELECT * FROM $statement");
    if($obGlosas->NumRows($consulta)){
        $Resultados=$obGlosas->NumRows($consulta);
        $css->CrearTabla();
        //Paginacion
        if($Resultados){
            $st= base64_encode($statement);
            if($ResultadosTotales>$limit){
                
                $css->FilaTabla(16);
                print("<td colspan='3' style=text-align:center>");
                if($NumPage>1){
                    $NumPage1=$NumPage-1;
                    $Page="Consultas/BuscarFacturasDiferencias.php?st=$st&Page=$NumPage1&Carry=";
                    $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEps`,`DivFacturasDif`,`5`);return false ;";
                    
                    $css->CrearBotonEvento("BtnMas", "Page $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");
                    
                }
                print("</td>");
                $TotalPaginas= ceil($ResultadosTotales/$limit);
                print("<td colspan=3 style=text-align:center>");
                print("<strong>Pagina: </strong>");
                                
                $Page="Consultas/BuscarFacturasDiferencias.php?st=$st&Page=";
                $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivFacturasDif`,`5`);return false ;";
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
                    $Page="Consultas/BuscarFacturasDiferencias.php?st=$st&Page=$NumPage1&Carry=";
                    $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEps`,`DivFacturasDif`,`5`);return false ;";
                    $css->CrearBotonEvento("BtnMas", "Page $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                }
                print("</td>");
               $css->CierraFilaTabla(); 
            }
        }   
        $css->FilaTabla(12);
            $css->ColTabla("<strong>Factura</strong>", 1);
            $css->ColTabla("<strong>FechaFactura</strong>", 1);
            $css->ColTabla("<strong>Total</strong>", 1);
            $css->ColTabla("<strong>ValorPagado</strong>", 1);
            $css->ColTabla("<strong>DiferenciaPago</strong>", 1);
            $css->ColTabla("<strong>FechaRadicado</strong>", 1);
            $css->ColTabla("<strong>FechaPago</strong>", 1);
            $css->ColTabla("<strong>Dias</strong>", 1);
            $css->ColTabla("<strong>Seleccionar</strong>", 1);
            
        $css->CierraFilaTabla();
        
        while($DatosFacturas=$obGlosas->FetchArray($consulta)){
            $FechaPago="$DatosFacturas[fecha_pago_factura]";
            $FechaRadicado="$DatosFacturas[fecha_radicado]";
            $Dias=$obGlosas->CalculeDiferenciaFechas($FechaPago,$FechaRadicado , "");
            $css->FilaTabla(12);
                $css->ColTabla($DatosFacturas["num_factura"], 1);
                $css->ColTabla($DatosFacturas["fecha_factura"], 1);
                $css->ColTabla(number_format($DatosFacturas["valor_neto_pagar"]), 1);
                $css->ColTabla(number_format($DatosFacturas["valor_pagado"]), 1);
                $css->ColTabla(number_format($DatosFacturas["DiferenciaEnPago"]), 1);
                $css->ColTabla($FechaRadicado, 1);
                $css->ColTabla($FechaPago, 1);
                $css->ColTabla($Dias["Dias"], 1);
                
                print("<td style='text-align:center'>");
                    $Page="Consultas/SaludFacturasGlosas.php?idFactura=$DatosFacturas[id_factura_generada]";
                    $css->CrearBotonEvento("BtnMostrar", "+", 1, "onClick", "EnvieObjetoConsulta(`$Page`,`idEps`,`DivDatosFactura`,``);return false;", "naranja", "");
                print("</td>");
            $css->CierraFilaTabla();
        }
        $css->CerrarTabla();
    }else{
        $css->CrearNotificacionRoja("No se encontraron datos", 16);
    }
    
}
?>