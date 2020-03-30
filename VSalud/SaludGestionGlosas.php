<?php 
$myPage="SaludGestionGlosas.php";
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
  
    
    $css->CabeceraIni("Glosas"); //Inicia la cabecera de la pagina
    
    $css->CabeceraFin(); 
    ///////////////Creamos el contenedor
    /////
    /////
    $css->CrearDiv("DivButtons", "", "", 0, 0);
    
        $css->CreaBotonDesplegable("DialFacturasDetalle", "Abrir","BtnModalFacturas");
        $css->CreaBotonDesplegable("ModalGlosar", "Abrir","BtnModalGlosar");
        
        
    $css->CerrarDiv();
    $css->CrearInputText("TxtCuentaActiva", "hidden", "", "", "", "", "", "", "", "", 0, 0);
    $css->CrearInputText("TxtFacturaActiva", "hidden", "", "", "", "", "", "", "", "", 0, 0);
    //$css->CrearModalAmplio("DialFacturasDetalle", "", "");
    
      //  $css->CrearDiv("DivHistoricoGlosas1", "container", "center", 1, 1);
        //$css->CerrarDiv();
           
   // $css->CerrarModal();
    
    $css->CrearModalAmplio("ModalGlosar", "", "");
        $css->CrearDiv("DivGlosar", "", "center", 1, 1);
        $css->CerrarDiv();
        $css->CrearDiv("DivHistorialGlosas", "", "center", 1, 1);
        $css->CerrarDiv();
    $css->CerrarModal();
    
    $css->CrearDiv("principal", "container", "center",1,1);
    
    $css->CrearTabla();
    $css->FilaTabla(16);
        $css->ColTabla("<strong>Opciones de Busqueda</strong>", 1);
        print("<td colspan=3 style='text-align:center'>");
            $css->CrearLink("SaludGenereRespuestas.php", "_blank", "<strong>Generar Respuestas</strong>");
            print(" || ");
            $css->ImageOcultarMostrar("ImgOcultaMasivos", "Cargar Glosas: ", "DivMasivos", 30, 30, "");
            print(" || ");
            $css->ImageOcultarMostrar("ImgOcultaMasivosConciliaciones", "Cargar Conciliaciones: ", "DivMasivosConciliaciones", 30, 30, "");
            $css->CrearDiv("DivMasivos", "", "center", 0, 1);
                print("<br><strong>Carga Masiva de Glosas: </strong>");
                print("<br><strong>Archivo: </strong>");$css->CrearUpload("UpCargaMasivaGlosas");print("<br>");
                //print("<strong>Soporte: </strong>");$css->CrearUpload("UpSoporteGlosasMasivas");print("<br>");            
                $css->CrearBotonEvento("BtnEnviarCargaMasiva", "Cargar Glosas", 1, "onClick", "CargarArchivoGlosasMasivas()", "rojo", "");
                $css->ProgressBar("PgProgresoCMG", "LyProgresoCMG", "", 0, 0, 100, 0, "0%", "", "");
                print("<div id='EstadoProgresoGlosasMasivas'></div>");
               
            $css->CerrarDiv();
            $css->CrearDiv("DivMasivosConciliaciones", "", "center", 0, 1);
                print("<br><strong>Carga Masiva de Conciliaciones: </strong><br>");
                print("<strong>Archivo: </strong>");$css->CrearUpload("UpCargaMasivaConciliaciones");
                           
                print("<br>");$css->CrearBotonEvento("BtnEnviarCargaMasivaConciliaciones", "Cargar Conciliaciones", 1, "onClick", "CargarArchivoConciliaciones()", "naranja", "");
                
                $css->ProgressBar("PgProgresoConciliaciones", "LyProgresoConciliaciones", "", 0, 0, 100, 0, "0%", "", "");
                print("<div id='EstadoProgresoConciliaciones'></div>");
            $css->CerrarDiv();
        print("</td>");
    $css->CierraFilaTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>EPS</strong>", 1);
            $css->ColTabla("<strong>Factura</strong>", 1);
            $css->ColTabla("<strong>X Cuentas</strong>", 1);
            $css->ColTabla("<strong>Estado de Glosas en Cuentas</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            print("<td>");
                $css->CrearTableChosen("idEPS", "salud_eps", " ORDER BY nombre_completo", "cod_pagador_min", "nombre_completo", "cod_pagador_min", "cod_pagador_min", 300, 1, "EPS", "");
                $css->CrearBotonEvento("BtnMostrar", "Buscar", 1, "onClick", "BuscarCuentaXCriterio(5)", "naranja", "");
            print("</td>"); 
            print("<td>");
            
                $css->CrearInputText("TxtBuscarFact","text","","","Factura","black","onChange","BuscarCuentaXCriterio(1)",200,30,0,0);
        
            print("</td>");
            print("<td>");
            
                $css->CrearInputText("TxtBuscarCuentaRIPS","text","","","Cuenta RIPS","black","onChange","BuscarCuentaXCriterio(2)",150,30,0,0);
                $css->CrearInputText("TxtBuscarCuentaGlobal","text","","","Cuenta Global","black","onChange","BuscarCuentaXCriterio(3)",150,30,0,0);
        
            print("</td>");
            print("<td>");
            
                $css->CrearSelectTable("CmdEstadoGlosa", "salud_estado_glosas", "", "ID", "ID", "Estado_glosa", "onChange", "BuscarCuentaXCriterio(4)", "", 0);
                
            print("</td>");
            
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
        
        print("<td colspan=4 style='text-align:center'>");
        
            $css->CrearNotificacionAzul("Generar XML y Cargar al FTP", 16);
            $css->CrearBotonEvento("BtnCrearXML", "Crear XML de Glosas y Enviar al FTP", 1, "onclick", "ConfirmarGeneracionXMLGlosas()", "naranja", "");
            $css->CrearDiv("DivProcessGeneracionXML", "", "center", 1, 1);
            $css->CerrarDiv();            
            $css->CrearDiv("DivGeneracionXML", "", "left", 1, 1);
            $css->CerrarDiv();
            $css->CrearDiv("DivProcessSubirFTP", "", "center", 1, 1);
            $css->CerrarDiv();            
            $css->CrearDiv("DivSubirFTP", "", "left", 1, 1);
            $css->CerrarDiv();
        print("</td>");
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
        
        $css->FilaTabla(16);
        
        print("<td colspan=4>");
            $css->CrearNotificacionAzul("Cuentas", 16);
            $css->CrearDiv("DivCuentas", "", "center", 1, 1);
            $css->CerrarDiv();
        print("</td>");
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
        print("<td colspan=4>");
            $css->CrearNotificacionVerde("Facturas", 16);
            print('<a name="AnclaFacturas"></a>');
            $css->CrearDiv("DivFacturas", "", "center", 1, 1);
            
            $css->CerrarDiv();
        print("</td>");
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
        print("<td colspan=4>");
            $css->CrearNotificacionNaranja("Detalle de Facturas", 16);
            print('<a name="AnclaDetalleFacturas"></a>');
            $css->CrearDiv("DivActividades", "", "center", 1, 1);
                $css->CrearDiv("DivDetallesUsuario", "container", "center", 1, 1);
                $css->CerrarDiv();
                $css->CrearDiv("DivActividadesFacturas", "container", "center", 1, 1);
                $css->CerrarDiv();
            $css->CerrarDiv();
        print("</td>");
       $css->CierraFilaTabla();
       $css->FilaTabla(16);
        print("<td colspan=4>");
            print('<a name="AnclaDetalleActividades"></a>');
            $css->CrearNotificacionRoja("Detalle de Glosas", 16);
            $css->CrearDiv("DivDetalleGlosas", "", "center", 1, 1);
                $css->CrearDiv("DivHistoricoGlosas", "container", "center", 1, 1);
                $css->CerrarDiv();
                print('<a name="AnclaFormularioRespuestas"></a>');
                $css->CrearDiv("DivFormRespuestasGlosas", "container", "center", 1, 1);
                $css->CerrarDiv();
                $css->CrearDiv("DivRespuestasGlosasTemporal", "container", "center", 1, 1);
                $css->CerrarDiv();
            $css->CerrarDiv();
        print("</td>");
       $css->CierraFilaTabla();
    $css->CerrarTabla();
              
    $css->CerrarDiv();//Cerramos contenedor Principal
    $css->AgregaJS(); //Agregamos javascripts
   
    print('<script type="text/javascript" src="jsPages/Glosas.js"></script>');
    print('<script type="text/javascript" src="jsPages/GlosasMasivas.js"></script>');
    print('<script type="text/javascript" src="jsPages/ConciliacionesMasivas.js"></script>');
    print('<script type="text/javascript" src="jsPages/GlosasDecor.js"></script>');
    $css->AgregaSubir();
    print('<a style="display:scroll; position:fixed; bottom:345px; right:10px;height:70px;width:60px" href="#" title="Cuentas"><img src="../images/salud_cuentas.png" /></a>');
    print('<a style="display:scroll; position:fixed; bottom:270px; right:10px;height:70px;width:60px" href="#AnclaFacturas" title="Facturas"><img src="../images/salud_facturas.png" /></a>');
    print('<a style="display:scroll; position:fixed; bottom:190px; right:10px;height:70px;width:60px" href="#AnclaDetalleFacturas" title="Facturas"><img src="../images/salud_archivos.png" /></a>');
    print('<a style="display:scroll; position:fixed; bottom:120px; right:10px;height:70px;width:60px" href="#AnclaDetalleActividades" title="Facturas"><img src="../images/salud_detalles.png" /></a>');
   		
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>