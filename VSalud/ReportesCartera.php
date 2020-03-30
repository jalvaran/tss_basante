<?php 
$myPage="ReportesCartera.php";
include_once("../sesiones/php_control.php");
//include_once("clases/Glosas.class.php");
include_once("css_construct.php");
$obGlosas = new conexion($idUser);

print("<html>");
print("<head>");
$css =  new CssIni("Reportes Cartera");

print("</head>");
print("<body>");
  
    
    $css->CabeceraIni("Reportes Cartera"); //Inicia la cabecera de la pagina     
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    
    $css->CrearDiv("DivPrincipal", "container", "center", 1, 1);
    $css->CrearDiv("DivProcess", "", "center", 1, 1);
    $css->CerrarDiv();
    
    $css->CrearNotificacionNaranja("Reportes de Cartera", 16);
    $css->ProgressBar("PgProgresoUp", "LyProgresoCMG", "", 0, 0, 100, 0, "0%", "", "");
    
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Tipo de Reporte</strong>", 1);
                    $css->ColTabla("<strong>Separador</strong>", 1);
                    $css->ColTabla("<strong>EPS</strong>", 2);
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td  style='text-align:center' >");
                        
                        $css->CrearSelect("TipoReporte", "",350);
                            $css->CrearOptionSelect("1", "Facturas Pagadas", 1);
                            $css->CrearOptionSelect("2", "Facturas No Pagadas", 0);
                            $css->CrearOptionSelect("3", "Facturas Pagadas con Diferencias", 0);
                            $css->CrearOptionSelect("4", "Facturas Pagadas de posibles vigencias anteriores", 0);
                            $css->CrearOptionSelect("6", "Circular 07", 0);
                            $css->CrearOptionSelect("5", "Cartera por edades", 0);
                            //$css->CrearOptionSelect("6", "SIHO", 0);
                            $css->CrearOptionSelect("7", "Saldos por EPS", 0);
                            $css->CrearOptionSelect("8", "Consolidado de Facturacion", 0);
                            
                        $css->CerrarSelect();
                        print("</td>");
                        print("<td  style='text-align:center' >");
                        $css->CrearSelect("Separador", "",150);
                            $css->CrearOptionSelect("1", "Punto y Coma (;)", 1);
                            $css->CrearOptionSelect("2", "Coma (,)", 0);
                                                       
                        $css->CerrarSelect();
                    print("</td>");
                    print("<td colspan=2 style='text-align:center' >");
                        $css->CrearDiv("DivEPS", "", "center", 1, "");
                            $css->CrearTableChosen("idEPS", "salud_eps", "", "cod_pagador_min", "nombre_completo", "nit", "cod_pagador_min", 400, 1, "EPS", "");
                        $css->CerrarDiv();
                    print("</td>");
                    
            $css->CierraFilaTabla();  
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Fecha Inicial</strong>", 1);
                    $css->ColTabla("<strong>Fecha Final</strong>", 1);   
                    $css->ColTabla("<strong>Cuentas</strong>", 1);
                    $css->ColTabla("<strong>Generar</strong>", 1);
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td>");
                        $css->CrearDiv("DivFechaInicial", "", "center", 1, 1);
                            $css->CrearInputText("FechaInicial", "date", "", "", "", "", "onChange", "CambiarFechaFinal()", 150, 30, 0, 0,"Seleccione una Fecha Inicial",date("Y-m-d"));
                        $css->CerrarDiv();
                    print("</td>");
                     print("<td>");
                        $css->CrearDiv("DivFechaFinal", "", "center", 1, 1);
                            $css->CrearInputText("FechaFinal", "date", "", "", "", "", "onChange", "CambiarFechaInicial()", 150, 30, 0, 0,"Seleccione una Fecha Final",date("Y-m-d"));
                        $css->CerrarDiv();    
                    print("</td>");
                     print("<td>");
                        $css->CrearDiv("DivCuentaRIPS", "", "center", 1, 1);
                            $css->CrearInputText("CuentaRIPS", "text", "", "", "CuentaRIPS", "", "", "", 150, 30, 0, 0,"Seleccione una Cuenta RIPS");
                            $css->CrearInputText("CuentaGlobal", "text", "", "", "CuentaGlobal", "", "", "", 150, 30, 0, 0,"Seleccione una Cuenta RIPS");
                        
                        $css->CerrarDiv();
                    print("</td>");
                    
                    print("<td>");
                        $css->CrearBotonEvento("BtnReporte", "Generar", 1, "onClick", "DibujeReporte()", "naranja", "");
                    print("</td>");
                    
                $css->CierraFilaTabla();
            $css->CerrarTabla();
       
        
    
    //print("</div>");    
    $css->CrearDiv("DivConsultas", "container", "center", 1, 1);
    $css->CerrarDiv();    
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    
    print('<script type="text/javascript" src="jsPages/SaludGenereReportesCartera.js"></script>');
    $css->AgregaSubir();
    		
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>