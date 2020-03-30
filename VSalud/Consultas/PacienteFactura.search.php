<?php

session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];
$TipoUser=$_SESSION['tipouser'];
//$myPage="titulos_comisiones.php";
include_once("../../modelo/php_conexion.php");

include_once("../css_construct.php");


if( !empty($_REQUEST["idFactura"]) ){
    $css =  new CssIni("id",0);
    $obGlosas = new conexion($idUser);
    
    $idFactura=$obGlosas->normalizar($_REQUEST["idFactura"]);
    //$css->CrearNotificacionAzul("Factura No. $idFactura", 14);
    $sql="SELECT t2.num_ident_usuario,t2.tipo_ident_usuario,t2.primer_ape_usuario,t2.segundo_ape_usuario,t2.primer_nom_usuario,"
            . " t2.segundo_nom_usuario,t2.edad,t2.unidad_medida_edad,t2.sexo "
            . "FROM salud_archivo_consultas t1 "
            . "INNER JOIN salud_archivo_usuarios t2 ON t1.num_ident_usuario=t2.num_ident_usuario WHERE t1.num_factura ='$idFactura'";
    $Consulta=$obGlosas->Query($sql);
    $DatosUsuario=$obGlosas->FetchArray($Consulta);
    
    if(empty($DatosUsuario["num_ident_usuario"])){
        $sql="SELECT t2.num_ident_usuario,t2.tipo_ident_usuario,t2.primer_ape_usuario,t2.segundo_ape_usuario,t2.primer_nom_usuario,"
            . " t2.segundo_nom_usuario,t2.edad,t2.unidad_medida_edad,t2.sexo "
            . "FROM salud_archivo_medicamentos t1 "
            . "INNER JOIN salud_archivo_usuarios t2 ON t1.num_ident_usuario=t2.num_ident_usuario WHERE t1.num_factura ='$idFactura'";
        $Consulta=$obGlosas->Query($sql);
        $DatosUsuario=$obGlosas->FetchArray($Consulta);
        
        if(empty($DatosUsuario["num_ident_usuario"])){
            $sql="SELECT t2.num_ident_usuario,t2.tipo_ident_usuario,t2.primer_ape_usuario,t2.segundo_ape_usuario,t2.primer_nom_usuario,"
            . " t2.segundo_nom_usuario,t2.edad,t2.unidad_medida_edad,t2.sexo "
            . "FROM salud_archivo_procedimientos t1 "
            . "INNER JOIN salud_archivo_usuarios t2 ON t1.num_ident_usuario=t2.num_ident_usuario WHERE t1.num_factura ='$idFactura'";
            $Consulta=$obGlosas->Query($sql);
            $DatosUsuario=$obGlosas->FetchArray($Consulta);
            if(empty($DatosUsuario["num_ident_usuario"])){
                $sql="SELECT t2.num_ident_usuario,t2.tipo_ident_usuario,t2.primer_ape_usuario,t2.segundo_ape_usuario,t2.primer_nom_usuario,"
                . " t2.segundo_nom_usuario,t2.edad,t2.unidad_medida_edad,t2.sexo "
                . "FROM salud_archivo_otros_servicios t1 "
                . "INNER JOIN salud_archivo_usuarios t2 ON t1.num_ident_usuario=t2.num_ident_usuario WHERE t1.num_factura ='$idFactura'";
                $Consulta=$obGlosas->Query($sql);
                $DatosUsuario=$obGlosas->FetchArray($Consulta);

            }
        }
    }
    
    $NombreUsuario=$DatosUsuario["primer_nom_usuario"]." ".$DatosUsuario["segundo_nom_usuario"]." ".$DatosUsuario["primer_ape_usuario"]." ".$DatosUsuario["segundo_ape_usuario"];
    
    $MedidaEdad="";
    if($DatosUsuario["unidad_medida_edad"]==1){
        $MedidaEdad="AÑOS";
    }
    if($DatosUsuario["unidad_medida_edad"]==2){
        $MedidaEdad="MESES";
    }
    if($DatosUsuario["unidad_medida_edad"]==3){
        $MedidaEdad="DIAS";
    }
    $css->CrearTabla();
            $css->FilaTabla(16);
                $css->ColTabla("<strong>Datos del Usuario</strong>", 6);
            $css->CierraFilaTabla();
            $css->FilaTabla(12);
                $css->ColTabla("Nombre", 1);
                $css->ColTabla("Tipo de Identificación", 1);
                $css->ColTabla("No. Identificación", 1);
                $css->ColTabla("Edad", 1);
                $css->ColTabla("Medida Edad", 1);
                $css->ColTabla("Sexo", 1);
            $css->CierraFilaTabla();
            $css->FilaTabla(12);
                $css->ColTabla(utf8_encode($NombreUsuario), 1);
                $css->ColTabla($DatosUsuario["tipo_ident_usuario"], 1);
                $css->ColTabla($DatosUsuario["num_ident_usuario"], 1);
                $css->ColTabla($DatosUsuario["edad"], 1);
                $css->ColTabla($MedidaEdad, 1);
                $css->ColTabla($DatosUsuario["sexo"], 1);
            $css->CierraFilaTabla();
        $css->CerrarTabla();
        
        $sql="SELECT fecha_factura,cod_enti_administradora,nom_enti_administradora,num_contrato,"
                . "  valor_neto_pagar,valor_total_pago,fecha_radicado,numero_radicado,CuentaRIPS,CuentaGlobal,EstadoGlosa "
                . "FROM salud_archivo_facturacion_mov_generados WHERE num_factura='$idFactura'";
        $Consulta=$obGlosas->Query($sql);
        $DatosFactura=$obGlosas->FetchArray($Consulta);
        $css->CrearTabla();
            $css->FilaTabla(16);
                $css->ColTabla("<strong>Datos de la factura $idFactura</strong>", 6);
            $css->CierraFilaTabla();
            $css->FilaTabla(12);
                $css->ColTabla("EPS", 1);
                $css->ColTabla("No. del Contrato", 1);
                $css->ColTabla("Fecha del Radicado", 1);
                $css->ColTabla("No. del Radicado", 1);
                $css->ColTabla("Cuenta RIPS", 1);
                $css->ColTabla("Cuenta Global", 1);
                $css->ColTabla("Fecha Factura", 1);
                $css->ColTabla("Total EPS", 1);
                $css->ColTabla("Total Usuario", 1);
                $css->ColTabla("Valor Total", 1);
                $css->ColTabla("Devolución", 1);
                
            $css->CierraFilaTabla();
            $css->FilaTabla(12);
                $css->ColTabla($DatosFactura["cod_enti_administradora"]." ".$DatosFactura["nom_enti_administradora"], 1);
                $css->ColTabla($DatosFactura["num_contrato"], 1);
                $css->ColTabla($DatosFactura["fecha_radicado"], 1);
                $css->ColTabla($DatosFactura["numero_radicado"], 1);
                $css->ColTabla($DatosFactura["CuentaRIPS"], 1);
                $css->ColTabla($DatosFactura["CuentaGlobal"], 1);
                $css->ColTabla($DatosFactura["fecha_factura"], 1);
                $css->ColTabla(number_format($DatosFactura["valor_neto_pagar"]), 1);
                $css->ColTabla(number_format($DatosFactura["valor_total_pago"]), 1);                
                $css->ColTabla(number_format($DatosFactura["valor_neto_pagar"]+$DatosFactura["valor_total_pago"]), 1);
                print("<td>");
                    $Enable=0;
                    if($DatosFactura["EstadoGlosa"]==8){
                        $Enable=1;
                    }
                    $DiasRadicado=$obGlosas->CalculeDiferenciaFechas($DatosFactura["fecha_radicado"], date("Y-m-d"), "");
                    $Parametros=$obGlosas->DevuelveValores("salud_parametros_generales", "ID", 1); //Verifico cuantos dias hay parametrizados para poder registrar glosas o devolver una factura
                    if($DiasRadicado["Dias"]>$Parametros["Valor"]){
                        $Enable=0;
                        print("<strong>Ya no es posible devolver esta Factura por tiempo<br><strong>");
                    }
                    $css->CrearBotonEvento("BtnDevolverFactura", "Devolución", $Enable, "onClick", "DibujeFormulario(1,`$idFactura`)", "rojo", "");
                print("</td>");
                $css->CrearDiv("Div", "", "", 1, 1);
                $css->CerrarDiv();
            $css->CierraFilaTabla();
        $css->CerrarTabla();
        
}else{
    print("No se enviaron parametros");
}
?>