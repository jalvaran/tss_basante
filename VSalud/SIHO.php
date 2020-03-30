<?php 
$myPage="SIHO.php";
include_once("../sesiones/php_control.php");
include_once("css_construct.php");

print("<html>");
print("<head>");
$css =  new CssIni("Generar Circular SIHO");

print("</head>");
print("<body>");
    
    
    
    $css->CabeceraIni("Generar Circular SIHO"); //Inicia la cabecera de la pagina
      
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    

    $css->CrearDiv("principal", "container", "center",1,1);
   // include_once 'procesadores/salud_generar_circular_030.process.php';
            
    $css->CrearNotificacionAzul("GENERAR CIRCULAR SIHO", 16);
    $css->CrearForm2("FrmSIHO", $myPage, "post", "_self");
    $css->CrearTabla();
        $css->FilaTabla(16);
            
            $css->ColTabla("<strong>FECHA DE CORTE</strong>", 1);
            $css->ColTabla("<strong>GENERAR</strong>", 1);
            
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            
            
            print("<td>");
                
                $css->CrearInputText("TxtFechaCorte", "date", "", date("Y-m-d"), "Fecha", "", "", "", 200, 30, 0, 1);
            
            print("</td>");
            
            print("<td>");
                //$Page="Consultas/SIHO.process.php?TxtFechaCorte=";
                //$css->CrearBotonEvento("BtnCrear", "Generar", 1, "onClick", "EnvieObjetoConsulta(`$Page`,`TxtFechaCorte`,`DivMensajesSIHO`,`5`);return false;", "naranja", "");
                $css->CrearBoton("BtnEnviar", "Generar Informe");
            print("</td>");
            
        $css->CierraFilaTabla();
        
        
    $css->CerrarTabla();
    $css->CrearDiv("DivMensajesSIHO", "", "center", 1, 1);
    $css->CerrarDiv();//Cerramos contenedor de notificaciones
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
    print('<script type="text/javascript" src="jsPages/SIHO.js"></script>');
    $css->AgregaSubir();
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>