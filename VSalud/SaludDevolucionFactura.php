<?php 
$myPage="SaludDevolucionFactura.php";
include_once("../sesiones/php_control.php");
//include_once("clases/Glosas.class.php");
include_once("css_construct.php");
$obGlosas = new conexion($idUser);
//////Si recibo un cliente
$NumFactura="";

print("<html>");
print("<head>");
$css =  new CssIni("Devolver una Factura");

print("</head>");
print("<body>");
  
    
    $css->CabeceraIni("Devolver una Factura"); //Inicia la cabecera de la pagina
    
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
        $css->ColTabla("<strong>Devolución de Facturas</strong>", 3,'C');
        
    $css->CierraFilaTabla();
        $css->FilaTabla(16);
            //$css->ColTabla("<strong>EPS</strong>", 1);
            $css->ColTabla("<strong>Factura</strong>", 1);
            //$css->ColTabla("<strong>X Cuentas</strong>", 1);
            //$css->ColTabla("<strong>Estado de Glosas en Cuentas</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            
            print("<td>");
            
                $css->CrearInputText("TxtBuscarFact","text","","","Factura","black","onChange","BuscarCuentaXCriterio(1)",200,30,0,0);
        
            print("</td>");
            
            
            
        $css->CierraFilaTabla();
        //$css->FilaTabla(16);
        /*
        print("<td colspan=4>");
            $css->CrearNotificacionAzul("Cuentas", 16);
            $css->CrearDiv("DivCuentas", "", "center", 1, 1);
            $css->CerrarDiv();
        print("</td>");
         * 
         */
        //$css->CierraFilaTabla();
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
            //$css->CrearNotificacionRoja("Detalle de Glosas", 16);
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
   
    print('<script type="text/javascript" src="jsPages/DevolucionFacturas.js"></script>');
    
    print('<script type="text/javascript" src="jsPages/GlosasDecor.js"></script>');
    $css->AgregaSubir();
    		
    $css->Footer();
    
    print("</body></html>");
    ob_end_flush();
?>