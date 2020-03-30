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
    $idFactura=$obGlosas->normalizar($_REQUEST['idEPS']);
    $DatosFactura=$obGlosas->DevuelveValores("salud_archivo_facturacion_mov_generados", "id_fac_mov_generados", $idFactura);
    
        $css->CrearTabla();
        $css->FilaTabla(12);
            $css->ColTabla("<strong>Factura</strong>", 1);
            $css->ColTabla("<strong>FechaFactura</strong>", 1);
            $css->ColTabla("<strong>Total</strong>", 1);
            $css->ColTabla("<strong>ValorPagado</strong>", 1);
            $css->ColTabla("<strong>DiferenciaPago</strong>", 1);
            $css->ColTabla("<strong>FechaRadicado</strong>", 1);
            $css->ColTabla("<strong>FechaPago</strong>", 1);
            $css->ColTabla("<strong>Dias</strong>", 1);
            $css->ColTabla("<strong>Devolucion</strong>", 1);
            $css->ColTabla("<strong>Glosa</strong>", 1);
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
                    
                    $Page="Consultas/SaludDatosFacturasDevoluciones.php?idFactura=$DatosFacturas[id_factura_generada]";
                    $css->CrearBotonEvento("BtnMostrar", "+", 1, "onClick", "EnvieObjetoConsulta(`$Page`,`idEps`,`DivDatosFactura`,``);return false;", "rojo", "");
                print("</td>");
                print("<td style='text-align:center'>");
                    $Page="Consultas/SaludDatosFacturasGlosas.php?idFactura=$DatosFacturas[id_factura_generada]";
                    $css->CrearBotonEvento("BtnMostrar", "+", 1, "onClick", "EnvieObjetoConsulta(`$Page`,`idEps`,`DivDatosFactura`,``);return false;", "naranja", "");
                print("</td>");
            $css->CierraFilaTabla();
        }
        $css->CerrarTabla();
    
    
}
?>