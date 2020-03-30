<?php
/**
 * Pagina para Registrar los descuentos BDUA
 * 2019-07-30, Julian Alvaran Techno Soluciones SAS
 * 
 * es recomendable No usar los siguientes ID para ningún objeto:
 * FrmModal, ModalAcciones,DivFormularios,BtnModalGuardar,DivOpcionesTablas,
 * DivControlCampos,DivOpciones1,DivOpciones2,DivOpciones3,DivParametrosTablas
 * TxtTabla, TxtCondicion,TxtOrdenNombreColumna,TxtOrdenTabla,TxtLimit,TxtPage,tabla
 * 
 */
$myPage="RegistrarDescuentosBDUA.php";
$myTitulo="Registrar Descuentos BDUA";
include_once("../../sesiones/php_control_usuarios.php");
include_once("../../constructores/paginas_constructor.php");

$css =  new PageConstruct($myTitulo, ""); //objeto con las funciones del html

$obCon = new conexion($idUser); //Conexion a la base de datos
$NombreUser=$_SESSION['nombre'];

$sql="SELECT TipoUser,Role FROM usuarios WHERE idUsuarios='$idUser'";
$DatosUsuario=$obCon->Query($sql);
$DatosUsuario=$obCon->FetchAssoc($DatosUsuario);
$TipoUser=$DatosUsuario["TipoUser"];

$css->PageInit($myTitulo);
    $css->Modal("ModalAcciones", "TSS", "", 1);
        $css->div("DivFrmModalAcciones", "", "", "", "", "", "");
        $css->Cdiv();        
    $css->CModal("BntModalAcciones", "onclick=SeleccioneAccionFormularios()", "button", "Guardar");
    
   
   $css->section("", "content", "", "");
    print("<h2>Registro de Descuentos BDUA</h3>");
    //print("<br><br>");
    $css->CrearDiv("DivProgress", "col-md-12", "center", 1, 1);
        
        $css->ProgressBar("PgProgresoUp", "LyProgresoUP", "", 0, 0, 100, 0, "0%", "", "");
    $css->CerrarDiv();
    $css->CrearDiv("DivProcess", "col-md-12", "center", 1, 1);
    
    $css->CerrarDiv();
    
    $css->CrearDiv("", "col-md-12", "center", 1, 1);
        $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Fecha del Descuento</strong>", 1);
                    $css->ColTabla("<strong>Factura</strong>", 1);
                    $css->ColTabla("<strong>Número del Radicado</strong>", 1);
                    $css->ColTabla("<strong>Afiliados IMA</strong>", 1);
                    $css->ColTabla("<strong>Valor del Descuento</strong>", 1);
                    
                    $css->ColTabla("<strong>Acciones</strong>", 1);
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td>");
                        
                        $css->input("date", "FechaDescuento", "form-control", "FechaDescuento", "", date("Y-m-d"), "Fecha Descuento", "", "", "style='line-height: 15px;'"."max=".date("Y-m-d"));
        
                    print("</td>");
                    print("<td>");
                        $css->input("text", "NumeroFactura", "form-control", "NumeroFactura", "", "", "Factura", "off", "", "");
                    print("</td>");
                    print("<td>");
                        $css->input("text", "Radicado", "form-control", "Radicado", "", "", "Radicado", "off", "", "");
                    print("</td>");
                    
                    print("<td>");
                        $css->input("number", "AfiliadosIMA", "form-control", "AfiliadosIMA", "", "", "AfiliadosIMA", "off", "", "");
                    print("</td>");
                    print("<td>");
                        $css->input("number", "ValorDescuento", "form-control", "ValorDescuento", "", "", "ValorDescuento", "off", "", "");
                    print("</td>");
                                        
                    print("<td>");
                        $css->CrearBotonEvento("BtnEjecutar", "Ejecutar", 1, "onclick", "ConfirmarEnvio()", "rojo", "");
             
                    print("</td>");
                $css->CierraFilaTabla();
            $css->CerrarTabla();
    $css->CerrarDiv();
    /*
    $css->CrearDiv("", "col-md-4", "center", 1, 1);
        print("<strong>Subir Actualizaciones de Facturas Masivas</strong>");
        print('<div class="input-group">');
            $css->input("file", "UpActualizaciones", "form-control", "UpActualizaciones", "Actualización de Facturas Por Excel", "Subir Actualizaciónes masivas", "Subir Actualizaciónes masivas", "off", "", "");
        print('<span id="BtnSubirActualizacionesMasivas" class="input-group-addon" style="cursor:pointer;background-color:orange" onclick="ConfirmarCarga()"><i class="fa fa-fw fa-upload" style=color:white></i></span>
            </div>');
    $css->CerrarDiv();
    */
    $css->CrearDiv("DivMensajes", "col-md-12", "center", 1, 1);
    
    $css->CerrarDiv();
    
    $css->Csection();
$css->PageFin();


print('<script src="jsPages/RegistrarDescuentosBDUA.js"></script>');  //script propio de la pagina

$css->Cbody();
$css->Chtml();

?>