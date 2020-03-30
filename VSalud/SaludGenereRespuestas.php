<?php 
$myPage="SaludGenereRespuestas.php";
include_once("../sesiones/php_control.php");
//include_once("clases/Glosas.class.php");
include_once("css_construct.php");
$obGlosas = new conexion($idUser);
//////Si recibo un cliente
$NumFactura="";

print("<html>");
print("<head>");
$css =  new CssIni("Glosas");

print("</head>");
print("<body>");
  
    
    $css->CabeceraIni("Respuestas"); //Inicia la cabecera de la pagina    
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("DivButtons", "", "", 0, 0);
    
        $css->CreaBotonDesplegable("ModalDescargar", "Abrir","BtnModalDescargas");
                
    $css->CerrarDiv();
    $css->CrearDiv("DivPrincipal", "container", "center", 1, 1);
    
    $css->CrearModal("ModalDescargar", "Descargar", "");
        $css->CrearDiv("DivDescargas", "", "center", 1, 1);
        $css->CerrarDiv();
    $css->CerrarModal();
    $css->CrearNotificacionAzul("Seleccione el tipo de Respuesta que desea", 16);
    $css->ProgressBar("PgProgresoUp", "LyProgresoCMG", "", 0, 0, 100, 0, "0%", "", "");
    $css->CrearDiv("DivProcess", "container", "center", 1, 1);
    $css->CerrarDiv();
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Adjuntar Soportes para los reportes?</strong>", 1);
                    print("<td style='text-align:center'>");
                        
                        $css->CrearSelect("CmbSoportes", "",80);
                            $css->CrearOptionSelect("0", "NO", 1);
                            $css->CrearOptionSelect("1", "SI", 0);
                        $css->CerrarSelect();
                    print("</td>");
                $css->CierraFilaTabla();
            $css->CerrarTabla();    
                $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Generar reporte por cuentas</strong>", 1);
                    
                    $css->ColTabla("<strong>Acción</strong>", 1);                    
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td>");
                        print('<select name="Cuentas[]" id="Cuentas" multiple="multiple" style=width:900px;>');
                            print('<option value=""></option>');
                        print('</select>');
                        //$css->CrearMultiSelectTable("Cuentas", "vista_salud_cuentas_rips", "", "CuentaRIPS", "CuentaRIPS", "nom_enti_administradora", "", "", "", 1,900);
                    print("</td>");
                    
                    print("<td>");
                        $css->CrearBotonEvento("BtnRespuestaXCuenta", "Generar", 1, "onClick", "EnviarCuentas()", "naranja", "");
                    print("</td>");
                    
                $css->CierraFilaTabla();
            $css->CerrarTabla();
       
        
       
            $css->CrearTabla();
                
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Generar reporte por facturas</strong>", 1);
                    $css->ColTabla("<strong>Acción</strong>", 1);
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td>");
                        print('<select name="CmbFacturas[]" id="CmbFacturas" multiple="multiple" style=width:900px;>');
                            print('<option value=""></option>');
                        print('</select>');
                        //$css->CrearMultiSelectTable("Cuentas", "vista_salud_cuentas_rips", "", "CuentaRIPS", "CuentaRIPS", "nom_enti_administradora", "", "", "", 1,900);
                    print("</td>");
                    
                    print("<td>");
                        $css->CrearBotonEvento("BtnRespuestaXFacturas", "Generar", 1, "onClick", "EnviarFacturas()", "naranja", "");
                    print("</td>");
                    
                $css->CierraFilaTabla();
            $css->CerrarTabla();
        
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>EPS</strong>", 1);
                    
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td style='text-align:center'>");
                        $css->CrearTableChosen("idEPS", "salud_eps", "", "cod_pagador_min", "nombre_completo", "nit", "cod_pagador_min", 400, 1, "EPS", "");
                    
                    print("</td>");
                    
            $css->CierraFilaTabla();  
            $css->CerrarTabla();
        
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Generar por rango de fecha de Facturas</strong>", 2);
                    $css->ColTabla("<strong>Acción</strong>", 1);
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td>");
                        $css->CrearInputText("FechaFacturaInicial", "date", "<strong>Fecha Inicial: </strong>", date("Y-m-d"), "", "", "onChange", "CambiarFechaFinalRangofacturas()", 150, 30, 0, 0,"Seleccione una Fecha Inicial",date("Y-m-d"));
                    print("</td>");   
                    print("<td>");
                        $css->CrearInputText("FechaFacturaFinal", "date", "<strong>Fecha Final: </strong>", date("Y-m-d"), "", "", "onChange", "CambiarFechaInicialRangofacturas()", 150, 30, 0, 0,"Seleccione una Fecha Inicial",date("Y-m-d"));
                    print("</td>");   
                    print("<td>");
                        $css->CrearBotonEvento("BtnRespuestaXRangoFechasFacturas", "Generar", 1, "onClick", "EnviarFacturasRangoFecha()", "verde", "");
                    
                    print("</td>");   
                $css->CierraFilaTabla();
            $css->CerrarTabla();
        
        
        
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Generar por rango de fecha de Radicado</strong>", 2);
                    $css->ColTabla("<strong>Acción</strong>", 1);
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td>");
                        $css->CrearInputText("FechaRadicadoInicial", "date", "<strong>Fecha Inicial: </strong>", date("Y-m-d"), "", "", "onChange", "CambiarFechaFinalRangoRadicado()", 150, 30, 0, 0,"Seleccione una Fecha Inicial",date("Y-m-d"));
                    print("</td>");   
                    print("<td>");
                        $css->CrearInputText("FechaRadicadoFinal", "date", "<strong>Fecha Final: </strong>", date("Y-m-d"), "", "", "onChange", "CambiarFechaInicialRangoRadicado()", 150, 30, 0, 0,"Seleccione una Fecha Inicial",date("Y-m-d"));
                    print("</td>");   
                    print("<td>");
                        $css->CrearBotonEvento("BtnRespuestaXRangoFechasRadicados", "Generar", 1, "onClick", "EnviarFacturasRangoFechaRadicado()", "azul", "");
                    
                    print("</td>");   
                $css->CierraFilaTabla();
            $css->CerrarTabla();
     print('<div id="DivLinkDescargas" style="display:scroll; position:fixed; top:50%; right:10px;"></div>');
		   
    $css->CrearDiv("DivConsultas", "container", "center", 1, 1);
    $css->CerrarDiv();    
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaCssJSSelect2(); //Agregamos CSS y JS de Select2
    
    print('<script type="text/javascript" src="jsPages/SaludGenereRespuestas.js"></script>');
    $css->AgregaSubir();
    		
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>