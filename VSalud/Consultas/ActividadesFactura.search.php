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
    // Consultas enviadas a traves de la URL
    $statement="";
    
    
    //Paginacion
    if(isset($_REQUEST['Page'])){
        $NumPage=$obGlosas->normalizar($_REQUEST['Page']);
    }else{
        $NumPage=1;
    }
    
    $idFactura=$obGlosas->normalizar($_REQUEST["idFactura"]);
    //$css->CrearNotificacionAzul("Factura No. $idFactura", 14);
    $css->CrearInputText("TxtFacturaActiva", "hidden", "", $idFactura, "", "", "", "", 150, 30, 0, 0);
    $css->CrearInputText("TxtActividadActiva", "hidden", "", "", "", "", "", "", 150, 30, 0, 0);
    
            
            $sql1="SELECT 'AC' as Archivo,id_consultas  as idArchivo,cod_consulta as Codigo,"
                    . "(SELECT descripcion_cups FROM salud_cups WHERE salud_cups.codigo_sistema=salud_archivo_consultas.cod_consulta) as Descripcion,"
                    . "`valor_consulta` as ValorUnitario, "
                    . "'1' as Cantidad, `valor_consulta` as Total,EstadoGlosa, "
                    . "(SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_estado_glosas.ID=salud_archivo_consultas.EstadoGlosa) as Estado "
                    . "FROM `salud_archivo_consultas` WHERE `num_factura`='$idFactura'";
            
            $sql2="SELECT 'AP' as Archivo, id_procedimiento as idArchivo,cod_procedimiento as Codigo,"
                    . "(SELECT descripcion_cups FROM salud_cups WHERE salud_cups.codigo_sistema=salud_archivo_procedimientos.cod_procedimiento) as Descripcion,"
                    . " SUM(`valor_procedimiento`)/COUNT(id_procedimiento) as ValorUnitario, "
                    . " COUNT(id_procedimiento) as Cantidad, SUM(`valor_procedimiento`)  as Total,EstadoGlosa, "
                    . "(SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_estado_glosas.ID=salud_archivo_procedimientos.EstadoGlosa) as Estado "
                    . "FROM `salud_archivo_procedimientos` WHERE `num_factura`='$idFactura' GROUP BY cod_procedimiento";
            
            $sql3="SELECT 'AT' as Archivo,id_otro_servicios as idArchivo,cod_servicio as Codigo,"
                    . "nom_servicio as Descripcion,"
                    . "`valor_unit_material` as ValorUnitario, "
                    . " SUM(cantidad)  as Cantidad, SUM(`valor_total_material`) as Total,EstadoGlosa, "
                    . "(SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_estado_glosas.ID=salud_archivo_otros_servicios.EstadoGlosa) as Estado "
                    . "FROM `salud_archivo_otros_servicios` WHERE `num_factura`='$idFactura' GROUP BY cod_servicio";
            
            $sql4="SELECT 'AM' as Archivo,id_medicamentos as idArchivo, `cod_medicamento` as Codigo,`nom_medicamento` as Descripcion,`valor_unit_medic` as ValorUnitario, "
                    . "SUM(`num_und_medic`) as Cantidad, SUM(`valor_total_medic`) as Total,EstadoGlosa, "
                    . "(SELECT Estado_glosa FROM salud_estado_glosas WHERE salud_estado_glosas.ID=salud_archivo_medicamentos.EstadoGlosa) as Estado "
                    . "FROM `salud_archivo_medicamentos` WHERE `num_factura`='$idFactura' GROUP BY cod_medicamento";
            
            $QueryVista=$sql1." UNION ".$sql2." UNION ".$sql3." UNION ".$sql4;
            
            $vista="DROP VIEW IF EXISTS `vista_temporal_actividades_af`";
            $obGlosas->Query($vista);  
            
            $vista="CREATE VIEW vista_temporal_actividades_af AS $QueryVista";
            $obGlosas->Query($vista);
            
            
            
            if(isset($_REQUEST['st'])){

                $statement= base64_decode($_REQUEST['st']);
                //print($statement);
            }
               
            $statement=" vista_temporal_actividades_af ";
            //Paginacion
            $limit = 10;
            $startpoint = ($NumPage * $limit) - $limit;
            $VectorST = explode("LIMIT", $statement);
            $statement = $VectorST[0]; 
            $query = "SELECT COUNT(EstadoGlosa) as num FROM ".$statement;
            
            $row = $obGlosas->Query($query);
            $row = $obGlosas->FetchAssoc($row);
            $ResultadosTotales = $row['num'];

            $statement.=" LIMIT $startpoint,$limit";
            
            $Consulta=$obGlosas->Query("SELECT * FROM ".$statement); 
            
            if($obGlosas->NumRows($Consulta)){
                
                $css->CrearTabla();
                
                $st= base64_encode($statement);
                    $css->CrearDiv("DivActualizarCuentas", "", "center", 0, 1);
                        $Page="Consultas/ActividadesFactura.search.php?st=$st&idFactura=$idFactura&Page=$NumPage&Carry=";
                        $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivActividadesFacturas`,`5`);return false ;";
                        $css->CrearBotonEvento("BtnActualizarActividades", "Actualizar Actividades", 1, "onclick", $FuncionJS, "rojo", "");
                    $css->CerrarDiv();
                    if($ResultadosTotales>$limit){

                        $css->FilaTabla(16);
                        print("<td colspan='4' style=text-align:center>");
                        if($NumPage>1){
                            $NumPage1=$NumPage-1;
                            $Page="Consultas/ActividadesFactura.search.php?st=$st&idFactura=$idFactura&Page=$NumPage1&Carry=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivActividadesFacturas`,`5`);return false ;";

                            $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "rojo", "");

                        }
                        print("</td>");
                        $TotalPaginas= ceil($ResultadosTotales/$limit);
                        print("<td colspan=9 style=text-align:center>");
                        print("<strong>Página: </strong>");

                        $Page="Consultas/ActividadesFactura.search.php?st=$st&idFactura=$idFactura&Page=";
                        $FuncionJS="EnvieObjetoConsulta(`$Page`,`CmbPageActv`,`DivActividadesFacturas`,`5`);return false ;";
                        $css->CrearSelect("CmbPageActv", $FuncionJS,70);
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
                            $Page="Consultas/ActividadesFactura.search.php?st=$st&idFactura=$idFactura&Page=$NumPage1&Carry=";
                            $FuncionJS="EnvieObjetoConsulta(`$Page`,`idEPS`,`DivActividadesFacturas`,`5`);return false ;";
                            $css->CrearBotonEvento("BtnMas", "Página $NumPage1", 1, "onclick", $FuncionJS, "verde", "");
                        }
                        print("</td>");
                       $css->CierraFilaTabla(); 
                    }
                
                    $css->FilaTabla(16);
                        $css->ColTabla("<strong>Actividades</strong>", 16);
                    $css->CierraFilaTabla();
                    $css->FilaTabla(12);
                        $css->ColTabla("Archivo", 1);
                        $css->ColTabla("Código", 1);
                        $css->ColTabla("Descripción", 1);
                        $css->ColTabla("Valor Unitario", 1);
                        $css->ColTabla("Cantidad", 1);
                        $css->ColTabla("Valor Contratado", 1);
                        $css->ColTabla("Valor Total", 1);
                        $css->ColTabla("Número de Glosas", 1);
                        $css->ColTabla("Valor Glosado", 1);
                        $css->ColTabla("Valor Levantado", 1);
                        $css->ColTabla("Valor Aceptado", 1);
                        $css->ColTabla("Valor X Conciliar", 1);
                        $css->ColTabla("Valor A Pagar", 1);
                        $css->ColTabla("Estado", 1);
                        $css->ColTabla("Glosa", 1);
                        $css->ColTabla("Responder", 1);


                    $css->CierraFilaTabla();
                
            while($DatosFactura=$obGlosas->FetchArray($Consulta)){
                $CodActividad=$DatosFactura["Codigo"];
                $sqlGlosas="SELECT COUNT(ID) AS NumGlosas,ValorActividad,SUM(ValorGlosado) AS ValorGlosado,SUM(ValorLevantado) as ValorLevantado,SUM(ValorAceptado) as ValorAceptado ,SUM(ValorXConciliar) as ValorXConciliar,"
                        . "(SELECT ValorActividad-ValorAceptado) AS ValorAPagarXEPS "
                        . "FROM salud_glosas_iniciales WHERE num_factura='$idFactura' and CodigoActividad='$CodActividad' AND EstadoGlosa<>12 AND EstadoGlosa<>13";
                $Datos=$obGlosas->Query($sqlGlosas);
                $DatosValoresGlosas=$obGlosas->FetchArray($Datos);
                $css->FilaTabla(12);
                    $TipoArchivo=$DatosFactura["Archivo"];
                    $idArchivo=$DatosFactura["idArchivo"];
                    $css->ColTabla($DatosFactura["Archivo"], 1);
                    $css->ColTabla($DatosFactura["Codigo"], 1);
                    $css->ColTabla(utf8_encode($DatosFactura["Descripcion"]), 1);
                    $css->ColTabla(number_format($DatosFactura["ValorUnitario"]), 1);
                    $css->ColTabla($DatosFactura["Cantidad"], 1);
                    $css->ColTabla("", 1);//pendiente despues de contratos
                    $css->ColTabla(number_format($DatosFactura["Total"]), 1);
                    $css->ColTabla(number_format($DatosValoresGlosas["NumGlosas"]), 1);
                    $css->ColTabla(number_format($DatosValoresGlosas["ValorGlosado"]), 1);
                    $css->ColTabla(number_format($DatosValoresGlosas["ValorLevantado"]), 1);
                    $css->ColTabla(number_format($DatosValoresGlosas["ValorAceptado"]), 1);
                    $css->ColTabla(number_format($DatosValoresGlosas["ValorXConciliar"]), 1);
                    if($DatosValoresGlosas["ValorXConciliar"]==0){
                        $ValorAPagarXEPS=number_format($DatosValoresGlosas["ValorAPagarXEPS"]);
                    }else{
                        $ValorAPagarXEPS="En trámite";
                    }
                    $css->ColTabla($ValorAPagarXEPS, 1);
                    $css->ColTabla($DatosFactura["Estado"], 1);
                    print("<td>");
                        $Enable=1;
                        if(($DatosFactura["EstadoGlosa"]>=5 and $DatosFactura["EstadoGlosa"]<=7) or ($DatosFactura["EstadoGlosa"]>8 and $DatosFactura["EstadoGlosa"]<12) ){
                            $Enable=0;
                        }
                        $DatosFechaFactura=$obGlosas->ValorActual("salud_archivo_facturacion_mov_generados", "fecha_radicado", "num_factura='$idFactura'");
                        $DiasRadicado=$obGlosas->CalculeDiferenciaFechas($DatosFechaFactura["fecha_radicado"], date("Y-m-d"), "");
                        $Parametros=$obGlosas->DevuelveValores("salud_parametros_generales", "ID", 1); //Verifico cuantos dias hay parametrizados para poder registrar glosas o devolver una factura
                        if($DiasRadicado["Dias"]>$Parametros["Valor"]){
                            $Enable=0;
                            print("<strong>Glosa fuera de tiempo<br><strong>");
                        }
                        $sql="SELECT ID FROM registro_glosas_xml_ftp WHERE num_factura='$idFactura' AND Xml_Glosa_Inicial";
                        $DatosXML=$obGlosas->FetchAssoc($obGlosas->Query($sql));
                        if($DatosXML["ID"]>0){
                            $css->CrearBotonEvento("BtnGlosarActividad", "&nbsp Glosa &nbsp", 0, "onClick", "GlosarActividad('$TipoArchivo','$idArchivo','$idFactura','$CodActividad')", "naranja", "");
                        }else{
                            $css->CrearBotonEvento("BtnGlosarActividad", "&nbsp Glosa &nbsp", $Enable, "onClick", "GlosarActividad('$TipoArchivo','$idArchivo','$idFactura','$CodActividad')", "naranja", "");
                        }
                        print("<br><br>");
                        $Enable=0;
                        if($DatosValoresGlosas["NumGlosas"]>0){
                            $Enable=1;
                        }
                        if($DatosFactura["EstadoGlosa"]>=5){
                            $Enable=0;
                        }
                        $css->CrearBotonEvento("BtnResponderActividad_$idArchivo", "Detalles ", $Enable, "onClick", "VerDetallesActividad('$CodActividad','$idFactura');CambiarColorBtnDetalles('BtnResponderActividad_$idArchivo');", "verde", "");
                       
                        print("</td>");
                    print("<td>");
                        $Enable=0;
                        if($DatosValoresGlosas["NumGlosas"]>0){
                            $Enable=1;
                        }
                        
                        $css->CrearBotonEvento("BtnHistorial_$idArchivo", "Historial ", $Enable, "onClick", "VerHistoricoGlosas('$idFactura','$CodActividad');CambiarColorBtnDetalles('BtnResponderActividad_$idArchivo');", "rojo", "");
                       
                        print("<br><br>");
                        $Enable=1;
                        if($DatosFactura["EstadoGlosa"]>=5){
                            $Enable=0;
                        }
                        $css->CrearBotonEvento("BtnConciliarActividad", "Conciliar", $Enable, "onClick", "DibujeFormularioConciliarActividad('$TipoArchivo','$idArchivo','$idFactura','$CodActividad')", "azul", "");
                    
                    print("</td>");
                    
                $css->CierraFilaTabla();
            }
       
        
        $css->CerrarTabla();
        
        }
          
}else{
    print("No se enviaron parametros");
}
?>