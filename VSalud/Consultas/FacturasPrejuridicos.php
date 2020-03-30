<?php

session_start();
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
//$myPage="titulos_comisiones.php";
include_once("../../modelo/php_conexion.php");

include_once("../css_construct.php");


if(!empty($_REQUEST["idEPS"]) or !empty($_REQUEST["idFactura"]) or !empty($_REQUEST["TipoCobro"]) or !empty($_REQUEST["Page"])){
    $css =  new CssIni("id");
    $obRips = new conexion($idUser);
    
    // Consultas enviadas a traves de la URL
    $statement="";
    if(isset($_REQUEST['st'])){

        $statement= base64_decode($_REQUEST['st']);
        //print($statement);
    }
    
    //Paginacion
    if(isset($_REQUEST['Page'])){
        $NumPage=$obRips->normalizar($_REQUEST['Page']);
    }else{
        $NumPage=1;
    }
    
    //////////////////
    if(isset($_REQUEST["TipoCobro"])){
        $TipoCobro=$obRips->normalizar($_REQUEST['TipoCobro']);
        
        if($TipoCobro==1){
            $statement=" vista_salud_facturas_no_pagas WHERE DiasMora>='1' AND EstadoCobro='' ";
    
        }else{
            $statement=" vista_salud_facturas_no_pagas WHERE DiasMora>='30' AND EstadoCobro='PREJURIDICO1' ";
    
        }
        $css->CrearNotificacionAzul("Facturas para cobro Prejuridico $TipoCobro", 16);
    }
    
    if(isset($_REQUEST["idEPS"]) and !empty($_REQUEST["idEPS"])){
        $idEPS=$obRips->normalizar($_REQUEST['idEPS']);
        $statement.=" AND cod_enti_administradora='$idEPS' ";
        $MsnEPS="para la EPS $idEPS";
    }
    $statement.=" ORDER BY valor_neto_pagar DESC ";
    //print("$statement");
    if(isset($_REQUEST["idFactura"])){
        $NumFactura=$obRips->normalizar($_REQUEST['idFactura']);
        $css->CrearNotificacionAzul("Resultados de la Busqueda ".$NumFactura, 16);
        $statement=" vista_salud_facturas_no_pagas WHERE num_factura='$NumFactura' ORDER BY valor_neto_pagar DESC";
    }
    
    //Paginacion
    $limit = 20;
    $startpoint = ($NumPage * $limit) - $limit;
    $VectorST = explode("LIMIT", $statement);
    $statement = $VectorST[0]; 
    $query = "SELECT COUNT(*) as `num` FROM {$statement}";
    $row = $obRips->FetchArray($obRips->Query($query));
    $ResultadosTotales = $row['num'];
        
    $statement.=" LIMIT $startpoint,$limit";
    
    //print("st:$statement");
    $consulta=$obRips->Query("SELECT * FROM $statement");
    if($obRips->NumRows($consulta)){
        $Resultados=$obRips->NumRows($consulta);
        $css->CrearTabla();
        //Paginacion
        if($Resultados){
            $st= base64_encode($statement);
            if($ResultadosTotales>$limit){
                
                $css->FilaTabla(16);
                print("<td colspan='2' style=text-align:center>");
                if($NumPage>1){
                    $NumPage1=$NumPage-1;
                    $Page="Consultas/FacturasPrejuridicos.php?st=$st&Page=$NumPage1&Carry=";
                    $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEps`,`DivFacturasDif`,`5`);return false ;";
                    
                    $css->CrearBotonEvento("BtnMas", "Page $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");
                    
                }
                print("</td>");
                $TotalPaginas= ceil($ResultadosTotales/$limit);
                print("<td colspan=2 style=text-align:center>");
                print("<strong>Pagina: </strong>");
                                
                $Page="Consultas/FacturasPrejuridicos.php?st=$st&Page=";
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
                print("<td colspan='2' style=text-align:center>");
                if($ResultadosTotales>($startpoint+$limit)){
                    $NumPage1=$NumPage+1;
                    $Page="Consultas/FacturasPrejuridicos.php?st=$st&Page=$NumPage1&Carry=";
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
            $css->ColTabla("<strong>DiasMora</strong>", 1);
            $css->ColTabla("<strong>FechaRadicado</strong>", 1);
            $css->ColTabla("<strong>Soporte</strong>", 1);          
        $css->CierraFilaTabla();
        
        while($DatosFacturas=$obRips->FetchArray($consulta)){
            
            $FechaRadicado="$DatosFacturas[fecha_radicado]";
            
            $css->FilaTabla(12);
                $css->ColTabla($DatosFacturas["num_factura"], 1);
                $css->ColTabla($DatosFacturas["fecha_factura"], 1);
                $css->ColTabla(number_format($DatosFacturas["valor_neto_pagar"]), 1);
                $css->ColTabla(number_format($DatosFacturas["DiasMora"]), 1);
                $css->ColTabla($FechaRadicado, 1);
                
                $css->ColTabla($DatosFacturas["numero_radicado"], 1); //Soporte
                
                
            $css->CierraFilaTabla();
        }
        $css->CerrarTabla();
    }else{
        $css->CrearNotificacionRoja("No se encontraron datos", 16);
    }
    
}
?>