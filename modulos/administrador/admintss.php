<?php
/**
 * Pagina base para la plataforma TSS
 * 2018-11-27
 * para cambiar el skin base debe realizarse desde la ultima parte en el archivo dist/js/admintss.js
 */
$myPage="admintss.php";
$myTitulo="Plataforma TSS";
include_once("../../sesiones/php_control_usuarios.php");
include_once("../../constructores/paginas_constructor.php");
$css =  new PageConstruct($myTitulo, "");

$css->PageInit($myTitulo);
        $JSBoton="onsubmit='GuardarRegistro(event);'";
        $css->form("FrmModal", "", "", "", "", "", "", $JSBoton);
        //$css->form("FrmModal", "", "", "", "", "", "", "");
            $css->Modal("ModalAcciones", "TSS", "", 0, 0, 1);

                    $css->CrearDiv("DivFormularios", "", "", 1, 1);
                    $css->CerrarDiv();

            $JSBoton="";
            
            $css->CModal("BtnModalGuardar", $JSBoton, "submit", "Guardar");
        $css->Cform();   
        //Codigo del desarrollador para su pagina
        
        $css->CrearDiv("DivOpcionesTablas", "", "left", 1, 1);
        
            $css->CrearDiv("DivControlCampos", "col-sm-3", "left", 1, 1); //Control de campos
            $css->CerrarDiv();
            
            $css->CrearDiv("DivOpciones1", "col-sm-3", "left", 1, 1); //Busquedas
            $css->CerrarDiv();
            
            $css->CrearDiv("DivOpciones2", "col-sm-3", "left", 1, 1); //Acciones
            $css->CerrarDiv();
             
            $css->CrearDiv("DivOpciones3", "col-sm-3", "left", 1, 1); //Agregar
            $css->CerrarDiv();
            
        $css->CerrarDiv();
        
        $css->CrearDiv("DivParametrosTablas", "", "", 0, 0);
            $css->CrearInputText("TxtTabla", "text", "", "", "", "", "", "", 300, 30, 0, 0,"1em");
            $css->CrearInputText("TxtCondicion", "text", "", "", "", "", "", "", 300, 30, 0, 0,"1em");
            $css->CrearInputText("TxtOrdenNombreColumna", "text", "", "", "", "", "", "", 300, 30, 0, 0,"1em");
            $css->CrearInputText("TxtOrdenTabla", "text", "", "DESC", "", "", "", "", 300, 30, 0, 0,"1em");
            $css->CrearInputText("TxtLimit", "text", "", "10", "", "", "", "", 300, 30, 0, 0,"1em");
            $css->CrearInputText("TxtPage", "text", "", "1", "", "", "", "", 300, 30, 0, 0,"1em");
        $css->CerrarDiv();    
        
        $css->CrearDiv("tabla", "", "", 1, 1); //Se dibujan las tablas realizando peticiones por ajax
        
        $css->CerrarDiv();
        
$css->PageFin();
print('<script src="jsPages/administrador.js"></script>'); 

$css->Cbody();
$css->Chtml();

?>