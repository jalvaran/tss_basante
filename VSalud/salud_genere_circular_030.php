<?php 
$myPage="salud_genere_circular_030.php";
include_once("../sesiones/php_control.php");
include_once("css_construct.php");

print("<html>");
print("<head>");
$css =  new CssIni("Generar Circular 030");

print("</head>");
print("<body>");
    
    
    
    $css->CabeceraIni("Generar Circular 030"); //Inicia la cabecera de la pagina
      
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    

    $css->CrearDiv("principal", "container", "center",1,1);
   // include_once 'procesadores/salud_generar_circular_030.process.php';
            
    $css->CrearNotificacionAzul("GENERAR CIRCULAR 030", 16);
    $css->CrearForm2("FrmCircular030", $myPage, "post", "_self");
    $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>FECHA INICIAL</strong>", 1);
            $css->ColTabla("<strong>FECHA FINAL</strong>", 1);
            $css->ColTabla("<strong>ADICIONAL</strong>", 1);
            $css->ColTabla("<strong>GENERAR</strong>", 1);
            
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            
            print("<td>");
                
                $css->CrearInputText("TxtFechaInicial", "date", "", date("Y-m-d"), "Fecha", "", "", "", 200, 30, 0, 1);
            
            print("</td>");
            print("<td>");
                
                $css->CrearInputText("TxtFechaFinal", "date", "", date("Y-m-d"), "Fecha", "", "", "", 200, 30, 0, 1);
            
            print("</td>");
            print("<td>");
                $css->CrearSelect("CmbAdicional", "",200);                    
                    $css->CrearOptionSelect(1, "Solo periodo seleccionado", 0);
                    $css->CrearOptionSelect(2, "Incluir Datos anteriores", 0);
                $css->CerrarSelect();
            print("</td>");
            print("<td>");
                //$Page="Consultas/salud_generar_circular_030.process.php?idFactura=";
                $css->CrearBotonEvento("BtnGenerar030", "Generar", 1, "onClick", "ConstruyaVista030();", "naranja", "");
                
            print("</td>");
            
        $css->CierraFilaTabla();
        
        
    $css->CerrarTabla();
    $css->ProgressBar("PgProgresoUp", "LyProgresoCMG", "", 0, 0, 100, 0, "0%", "", "");
    $css->CrearDiv("DivProcess", "", "center", 1, 1);
    $css->CerrarDiv();//Cerramos contenedor de notificaciones
    $css->CrearDiv("DivMensajesCircular2", "", "center", 1, 1);
    $css->CerrarDiv();//Cerramos contenedor de notificaciones
    $css->CrearDiv("DivMensajesCircular", "", "center", 1, 1);
    $css->CerrarDiv();//Cerramos contenedor de notificaciones
    
    $css->CrearNotificacionVerde("GENERAR CIRCULAR 014", 16);
    $css->CrearForm2("FrmCircular014", $myPage, "post", "_self");
    $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>MES</strong>", 1);
            $css->ColTabla("<strong>AÑO</strong>", 1);
            
            $css->ColTabla("<strong>GENERAR</strong>", 1);
            
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            
            print("<td>");
                $css->CrearSelect("CmbMes", "");
                    $css->CrearOptionSelect("01", "Enero", 0);
                    $css->CrearOptionSelect("02", "Febrero", 0);
                    $css->CrearOptionSelect("03", "Marzo", 0);
                    $css->CrearOptionSelect("04", "Abril", 0);
                    $css->CrearOptionSelect("05", "Mayo", 0);
                    $css->CrearOptionSelect("06", "Junio", 0);
                    $css->CrearOptionSelect("07", "Julio", 0);
                    $css->CrearOptionSelect("08", "Agosto", 0);
                    $css->CrearOptionSelect("09", "Septiembre", 0);
                    $css->CrearOptionSelect("10", "Octubre", 0);
                    $css->CrearOptionSelect("11", "Noviembre", 0);
                    $css->CrearOptionSelect("12", "Diciembre", 0);
                $css->CerrarSelect();
            print("</td>");
            print("<td>");
                
                $css->CrearSelect("CmbAnio", "");
                    $AnioActual=date("Y");
                    $Desde=$AnioActual-10;
                    for($i=$Desde;$i<=$AnioActual;$i++){
                        $sel=0;
                        if($i==$AnioActual){
                            $sel=1;
                        }
                        $css->CrearOptionSelect($i, $i, $sel);
                    }
                $css->CerrarSelect();
            print("</td>");
            
            print("<td>");
                $Page="Consultas/salud_generar_circular_014.process.php?idFactura=";
                $css->CrearBotonEvento("BtnCrear014", "Generar", 1, "onClick", "EnvieObjetoConsulta2(`$Page`,`CmbAnio`,`DivMensajesCircular014`,`7`);return false;", "verde", "");
                
            print("</td>");
            
        $css->CierraFilaTabla();
        
        
    $css->CerrarTabla();
    $css->CrearDiv("DivMensajesCircular014", "", "center", 1, 1);
    $css->CerrarDiv();//Cerramos contenedor de notificaciones
    
    $css->CerrarDiv();//Cerramos contenedor Principal
    print('<script type="text/javascript" src="jsPages/Salud_Generar030.js"></script>');
    $css->AgregaJS(); //Agregamos javascripts
    $css->AgregaSubir();
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>