<?php
/**
 * Pagina para registrar la prefacturacion de basante
 * 2020-03-30, Julian Alvaran Techno Soluciones SAS
 * 
 * es recomendable No usar los siguientes ID para ningún objeto:
 * FrmModal, ModalAcciones,DivFormularios,BtnModalGuardar,DivOpcionesTablas,
 * DivControlCampos,DivOpciones1,DivOpciones2,DivOpciones3,DivParametrosTablas
 * TxtTabla, TxtCondicion,TxtOrdenNombreColumna,TxtOrdenTabla,TxtLimit,TxtPage,tabla
 * 
 */

$myPage="salud_prefacturacion.php";  // identifica la pagina para poder controlar el acceso
$myTitulo="Pre Facturacion";  //Titulo en la pestaña del navegador
include_once("../../sesiones/php_control_usuarios.php"); //Controla los permisos de los usuarios
include_once("../../constructores/paginas_constructor.php"); //Construye la pagina, estan las herramientas para construir los objetos de la pagina

$css =  new PageConstruct($myTitulo, ""); //instancia para el objeto con las funciones del html

$obCon = new conexion($idUser); //instancia para Conexion a la base de datos

$css->PageInit($myTitulo);
    /*
     * Inicio de la maqueta propia de cada programador
     */
     $css->Modal("ModalAcciones", "TSS", "", 1);
        $css->div("DivFrmModalAcciones", "", "", "", "", "", "");
        $css->Cdiv();        
    $css->CModal("BntModalAcciones", "onclick=SeleccioneAccionFormularios()", "button", "Guardar");
    
    
    $css->section("", "content-header", "", "");
        print("<h1>Pre-Facturacion</h1>");
         
    $css->Csection();
    //print("<br>");
    $css->section("", "content", "", "");
        $css->CrearDiv("", "row", "left", 1, 1);
         $css->CrearDiv("", "col-md-2", "left", 1, 1);
            $css->CrearBotonEvento("BtnPacientes", "Pacientes", 1, "onclick", "ListarPacientes();idListado=1;", "verde");
            print("<br><br>");
            $css->CrearBotonEvento("BtnReservas", "Reservas", 1, "onclick", "ListarReservas();idListado=2;", "azul");
            print("<br>");
            $css->CrearBotonEvento("BtnCitas", "Citas", 1, "onclick", "ListarCitas();idListado=3;", "naranja");
            print("<br><br>");
            $css->CrearDiv("DivFiltrosReservas", "box box-solid", "left", 1, 1);
             $css->CrearDiv("", "box-header with-border", "left", 1, 1);
                
               print('<h3 class="box-title">Filtros Reservas</h3>');
               
               $css->CrearDiv("", "box-tools", "left", 1, 1);    
                  print('<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                  </div>');
        
                   $css->div("", "box-body no-padding", "", "", "", "", "");
                         $css->select("cmbFiltrosReservas", "form-control", "cmbFiltrosReservas", "", "", "onchange=ListarReservas()"/*funcion js para listar las tablas de  una base de datos*/, "");
                            $css->option("", "", "", "", "", "");
                                    print("Todas");
                            $css->Coption();
                            
                            $sql="SELECT * FROM prefactura_reservas_estados";
                            $Consulta=$obCon->Query($sql);
                            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                                $css->option("", "", "", $DatosConsulta["ID"], "", "");
                                    print($DatosConsulta["EstadoReserva"]);
                                $css->Coption();
                            }
                      $css->Cselect();
            
                    $css->Cdiv();
                $css->Cdiv();
                
                $css->CrearDiv("DivFiltrosCitas", "box box-solid", "left", 1, 1);
             $css->CrearDiv("", "box-header with-border", "left", 1, 1);
                
               print('<h3 class="box-title">Filtros Citas</h3>');
               
               $css->CrearDiv("", "box-tools", "left", 1, 1);    
                  print('<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                  </div>');
        
                   $css->div("", "box-body no-padding", "", "", "", "", "");
                                
                                //Creamos el Selector que contiene las bases de datos
                         $css->select("cmbFiltrosCitas", "form-control", "cmbFiltrosCitas", "", "", "onchange=ListarCitas()"/*funcion js para listar las tablas de  una base de datos*/, "");
                            $css->option("", "", "", "", "", "");
                                    print("Todas");
                            $css->Coption();
                            $sql="SELECT * FROM prefactura_reservas_citas_estados";
                            $Consulta=$obCon->Query($sql);
                            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                                $css->option("", "", "", $DatosConsulta["ID"], "", "");
                                    print($DatosConsulta["EstadoCita"]);
                                $css->Coption();
                            }
                      $css->Cselect();
            
                    $css->Cdiv();
                $css->Cdiv();
                    
                
                
            $css->Cdiv();
        
            
            $css->CrearDiv("", "col-md-10", "left", 1, 1);
                      
            $css->CrearDiv("DivMensajes", "col-md-4", "left", 1, 1);
            
            $css->CerrarDiv();
            $css->CrearDiv("", "col-md-2", "right", 1, 1); 
                
                $css->input("date", "FechaInicialRangos", "form-control", "FechaInicialRangos", "Fecha", "", "Fecha Inicial", "off", "", "onchange=MostrarListadoSegunID()","style='line-height: 15px;'");
                
            $css->CerrarDiv();
            $css->CrearDiv("", "col-md-2", "right", 1, 1); 
                $css->input("date", "FechaFinalRangos", "form-control", "FechaFinalRangos", "Fecha", "", "Fecha Final", "off", "", "onchange=MostrarListadoSegunID()","style='line-height: 15px;'");
                
            $css->CerrarDiv();
            $css->CrearDiv("", "box-tools pull-right", "left", 1, 1);      
                        
                    print('<div class="input-group">'); 
                        
                        $css->input("text", "TxtBusquedas", "form-control", "TxtBusquedas", "", "", "Buscar", "", "", "onchange=MostrarListadoSegunID()");

                    print('<span class="input-group-addon"><i class="fa fa-fw fa-search"></i></span>
                              </div>');
                $css->CerrarDiv(); 
            $css->CerrarDiv();   
            
            $css->CrearDiv("", "col-md-10", "left", 1, 1);
                            
                $css->CrearDiv("", "box box-primary", "left", 1, 1);
                    $css->CrearDiv("DivGeneralDraw", "box-header with-border", "left", 1, 1);
                        
                    $css->CerrarDiv();
                $css->CerrarDiv();    
            $css->CerrarDiv();
       
        print('</div>
                
              </section>');
     
        /*
         * Fin de la maqueta del programador
         */
        
$css->PageFin();

print('<script src="jsPages/salud_prefacturacion.js"></script>');  //script propio de la pagina

$css->Cbody();
$css->Chtml();

?>