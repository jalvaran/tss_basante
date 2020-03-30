<?php
/**
 * Pagina para Actualizar los numeros de las facturas desde la interfaz o un archivo de excel
 * 2019-06-10, Julian Alvaran Techno Soluciones SAS
 * 
 * es recomendable No usar los siguientes ID para ningún objeto:
 * FrmModal, ModalAcciones,DivFormularios,BtnModalGuardar,DivOpcionesTablas,
 * DivControlCampos,DivOpciones1,DivOpciones2,DivOpciones3,DivParametrosTablas
 * TxtTabla, TxtCondicion,TxtOrdenNombreColumna,TxtOrdenTabla,TxtLimit,TxtPage,tabla
 * 
 */
$myPage="ActualizarFacturas.php";
$myTitulo="Actualizacion de Facturas";
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
    $css->Modal("ModalAcciones", "TS5", "", 1);
        $css->div("DivFrmModalAcciones", "", "", "", "", "", "");
        $css->Cdiv();        
    $css->CModal("BntModalAcciones", "onclick=SeleccioneAccionFormularios()", "button", "Guardar");
    
   
   $css->section("", "content", "", "");
    print("<h2>Actualización de Número de Facturas</h3>");
    //print("<br><br>");
    $css->CrearDiv("DivProgress", "col-md-12", "center", 1, 1);
        
        $css->ProgressBar("PgProgresoUp", "LyProgresoUP", "", 0, 0, 100, 0, "0%", "", "");
    $css->CerrarDiv();
    $css->CrearDiv("DivProcess", "col-md-12", "center", 1, 1);
    
    $css->CerrarDiv();
    
    $css->CrearDiv("", "col-md-8", "center", 1, 1);
        $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Factura a Editar</strong>", 1);
                    $css->ColTabla("<strong>Factura Nueva</strong>", 1);
                    $css->ColTabla("<strong>Observaciones</strong>", 1);
                    $css->ColTabla("<strong>Acciones</strong>", 1);
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    print("<td>");
                        $css->input("text", "TxtNumeroFacturaEdit", "form-control", "TxtNumeroFacturaEdit", "", "", "Factura a Editar", "off", "", "");
                    print("</td>");
                    
                    print("<td>");
                        $css->input("text", "TxtFacturaNueva", "form-control", "TxtFacturaNueva", "", "", "Factura Nueva", "off", "", "");
                    print("</td>");
                    
                    print("<td>");
                        $css->textarea("TxtObservacionesEdicioFactura", "form-control", "TxtObservacionesEdicioFactura", "", "Observaciones", "", "");
                        $css->Ctextarea();
                    print("</td>");
                    
                    print("<td>");
                        $css->CrearBotonEvento("BtnEjecutar", "Actualizar", 1, "onclick", "EnviarFacturaEditar()", "rojo", "");
             
                    print("</td>");
                $css->CierraFilaTabla();
            $css->CerrarTabla();
    $css->CerrarDiv();
    $css->CrearDiv("", "col-md-4", "center", 1, 1);
        print("<strong>Subir Actualizaciones de Facturas Masivas</strong>");
        print('<div class="input-group">');
            $css->input("file", "UpActualizaciones", "form-control", "UpActualizaciones", "Actualización de Facturas Por Excel", "Subir Actualizaciónes masivas", "Subir Actualizaciónes masivas", "off", "", "");
        print('<span id="BtnSubirActualizacionesMasivas" class="input-group-addon" style="cursor:pointer;background-color:orange" onclick="ConfirmarCarga()"><i class="fa fa-fw fa-upload" style=color:white></i></span>
            </div>');
    $css->CerrarDiv();
    
    $css->CrearDiv("DivMensajes", "col-md-12", "center", 1, 1);
    
    $css->CerrarDiv();
    
    $css->Csection();
$css->PageFin();


print('<script src="jsPages/ActualizarFacturas.js"></script>');  //script propio de la pagina

$css->Cbody();
$css->Chtml();

?>