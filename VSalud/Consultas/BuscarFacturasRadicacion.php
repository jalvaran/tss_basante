<?php

session_start();
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
//$myPage="titulos_comisiones.php";
include_once("../../modelo/php_conexion.php");

include_once("../css_construct.php");
$css =  new CssIni("id");
$MostarPreRadicadas=0; //Para saber si debo mostrar las facturas a radicar
if(isset($_REQUEST["DelItem"])){ //Si recibo la peticion de eliminar una factura a radicar
    $obRadicar = new conexion($idUser);
    $idFacturaDel=$obRadicar->normalizar($_REQUEST["DelItem"]);
    $obRadicar->BorraReg("salud_facturas_radicacion_numero", "ID", $idFacturaDel);
    $MostarPreRadicadas=1;
}
if(isset($_REQUEST["AgregarFactura"])){ //Si recibo la peticion de agregar una factura a radicar
    $MostarPreRadicadas=1;
    $obRadicar = new conexion($idUser);
    $idFacturaRadicar=$obRadicar->normalizar($_REQUEST["idFacturaRadicar"]);
    $css->CrearNotificacionAzul("Facturas Agregadas", 14);
    //Verificamos si ya fue agregada
    $DatosFactura=$obRadicar->DevuelveValores("salud_facturas_radicacion_numero", "idFactura", $idFacturaRadicar);
    if($DatosFactura["idFactura"]=='' AND $idFacturaRadicar<>'NA'){
        //Agrego la factura que se desea radicar
        $tab="salud_facturas_radicacion_numero";
        $NumRegistros=2;

        $Columnas[0]="idFactura";		$Valores[0]=$idFacturaRadicar;
        $Columnas[1]="idUser";                  $Valores[1]=$idUser; 

        $obRadicar->InsertarRegistro($tab,$NumRegistros,$Columnas,$Valores);
    }
    
    
}
//Mostramos las facturas a Radicar
if($MostarPreRadicadas==1){
    $css->CrearTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>FACTURA</strong>", 1);
            $css->ColTabla("<strong>TOTAL</strong>", 1);
            $css->ColTabla("<strong>ELIMINAR</strong>", 1);
        $css->CierraFilaTabla();
        $sql="SELECT ID,id_fac_mov_generados,num_factura,valor_neto_pagar FROM salud_facturas_radicacion_numero fr "
                . "INNER JOIN salud_archivo_facturacion_mov_generados fg ON fr.idFactura=fg.id_fac_mov_generados"
                . " WHERE fr.idUser='$idUser' ORDER BY fr.ID DESC";
        $consulta=$obRadicar->Query($sql);
        $TotalFacturas=0;
        while($DatosFactura=$obRadicar->FetchArray($consulta)){
            $TotalFacturas=$TotalFacturas+$DatosFactura["valor_neto_pagar"];
            $css->FilaTabla(12);
                
                $css->ColTabla($DatosFactura["num_factura"], 1);
                $css->ColTabla(number_format($DatosFactura["valor_neto_pagar"]),1);
                //boton para eliminar por javascript y ajax
                print("<td style='text-align:center'>");
                    
                    $Page="Consultas/BuscarFacturasRadicacion.php?DelItem=$DatosFactura[ID]&Carry=";
                    $css->CrearBotonEvento("BtnMostrar", "-", 1, "onClick", "EnvieObjetoConsulta(`$Page`,`idEps`,`DivFacturasAgregadas`,``);return false;", "rojo", "");
                print("</td>");
            $css->CierraFilaTabla();
        }
        $css->FilaTabla(12);
                
            $css->ColTabla("<strong>TOTAL<strong>", 1);
            $css->ColTabla(number_format($TotalFacturas),2);
            
        $css->CierraFilaTabla();
    $css->CerrarTabla();
}

if(!empty($_REQUEST["idEPS"]) or !empty($_REQUEST["idFactura"]) or !empty($_REQUEST["Page"])){
    
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
        $statement=" salud_archivo_facturacion_mov_generados WHERE cod_enti_administradora='$idEPS' AND numero_radicado='' ORDER BY num_factura DESC";
    }
    if(isset($_REQUEST["idFactura"])){
        $NumFactura=$obGlosas->normalizar($_REQUEST['idFactura']);
        $css->CrearNotificacionAzul("Resultados de la Busqueda ".$NumFactura, 16);
        $statement=" salud_archivo_facturacion_mov_generados WHERE num_factura like '%$NumFactura%' AND numero_radicado='' ORDER BY num_factura DESC";
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
                print("<td  style=text-align:center>");
                if($NumPage>1){
                    $NumPage1=$NumPage-1;
                    $Page="Consultas/BuscarFacturasRadicacion.php?st=$st&Page=$NumPage1&Carry=";
                    $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEps`,`DivFacturasBusqueda`,`5`);return false ;";
                    
                    $css->CrearBotonEvento("BtnMas", "Page $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");
                    
                }
                print("</td>");
                $TotalPaginas= ceil($ResultadosTotales/$limit);
                print("<td colspan=2 style=text-align:center>");
                print("<strong>Pagina: </strong>");
                                
                $Page="Consultas/BuscarFacturasRadicacion.php?st=$st&Page=";
                $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPage`,`DivFacturasBusqueda`,`5`);return false ;";
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
                print("<td  style=text-align:center>");
                if($ResultadosTotales>($startpoint+$limit)){
                    $NumPage1=$NumPage+1;
                    $Page="Consultas/BuscarFacturasRadicacion.php?st=$st&Page=$NumPage1&Carry=";
                    $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEps`,`DivFacturasBusqueda`,`5`);return false ;";
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
            //$css->ColTabla("<strong>ValorPagado</strong>", 1);
            //$css->ColTabla("<strong>DiferenciaPago</strong>", 1);
            //$css->ColTabla("<strong>FechaRadicado</strong>", 1);
            //$css->ColTabla("<strong>FechaPago</strong>", 1);
            //$css->ColTabla("<strong>Dias</strong>", 1);
            $css->ColTabla("<strong>Seleccionar</strong>", 1);
            
        $css->CierraFilaTabla();
        
        while($DatosFacturas=$obGlosas->FetchArray($consulta)){
            //$FechaPago="$DatosFacturas[fecha_pago_factura]";
            //$FechaRadicado="$DatosFacturas[fecha_radicado]";
            //$Dias=$obGlosas->CalculeDiferenciaFechas($FechaPago,$FechaRadicado , "");
            $css->FilaTabla(12);
                $css->ColTabla($DatosFacturas["num_factura"], 1);
                $css->ColTabla($DatosFacturas["fecha_factura"], 1);
                $css->ColTabla(number_format($DatosFacturas["valor_neto_pagar"]), 1);
                //$css->ColTabla(number_format($DatosFacturas["valor_pagado"]), 1);
                //$css->ColTabla(number_format($DatosFacturas["DiferenciaEnPago"]), 1);
                //$css->ColTabla($FechaRadicado, 1);
                //$css->ColTabla($FechaPago, 1);
                //$css->ColTabla($Dias["Dias"], 1);
                
                print("<td style='text-align:center'>");
                    //pagina a la que se enviará el requerimiento para agregar una factura a la radicacion por ajax
                    $Page="Consultas/BuscarFacturasRadicacion.php?AgregarFactura=1&idFacturaRadicar=$DatosFacturas[id_fac_mov_generados]&Carry=";
                    $css->CrearBotonEvento("BtnMostrar", "+", 1, "onClick", "EnvieObjetoConsulta(`$Page`,`idEps`,`DivFacturasAgregadas`,``);return false;", "naranja", "");
                print("</td>");
            $css->CierraFilaTabla();
        }
        $css->CerrarTabla();
    }else{
        $css->CrearNotificacionRoja("No se encontraron datos", 16);
    }
    
}
?>