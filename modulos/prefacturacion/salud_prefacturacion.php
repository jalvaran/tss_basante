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
    print('<input type="hidden" id="empresa_id" value=1>');
    $css->section("", "content", "", "");
        $css->CrearDiv("", "row", "left", 1, 1);
            $css->CrearDiv("div_spinner", "", "left", 1, 1);

            $css->CerrarDiv();
         $css->CrearDiv("", "col-md-2", "left", 1, 1);
         /*
            $css->CrearBotonEvento("BtnPacientes", "Pacientes", 1, "onclick", "ListarPacientes();idListado=1;", "verde");
            print("<br><br>");
            $css->CrearBotonEvento("BtnReservas", "Reservas", 1, "onclick", "ListarReservas();idListado=2;", "azul");
            print("<br>");
            $css->CrearBotonEvento("BtnCitas", "Citas", 1, "onclick", "ListarCitas();idListado=3;", "naranja");
            print("<br><br>");
            $css->CrearDiv("DivFiltrosReservas", "box box-solid", "left", 1, 1);
             $css->CrearDiv("", "box-header with-border", "left", 1, 1);
                
          * 
          */
            $css->CrearDiv("", "box box-solid", "left", 1, 1);
    $css->CrearDiv("", "box-header with-border", "left", 1, 1);
    print('<h3 class="box-title">Carpetas</h3>');
    $css->CrearDiv("", "box-tools", "left", 1, 1);    
    print('  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding" style="">
              <ul class="nav nav-pills nav-stacked">
                <li>
                    <a href="#" onclick="idListado=1;MostrarListadoSegunID();">
                        <i class="fa fa-user-md"></i>Pacientes</a>
                </li>
                <li>
                    <a href="#" onclick="idListado=2;MostrarListadoSegunID();">
                        <i class="fa fa-ambulance"></i>Reservas</a>
                </li>
                <li>
                    <a href="#" onclick="idListado=3;MostrarListadoSegunID();">
                        <i class="fa fa-medkit"></i>Citas</a>
                </li>
                <li>
                    <a href="#" onclick="idListado=4;MostrarListadoSegunID();">
                        <i class="fa fa-list-alt"></i>Pendiente por Facturar</a>
                </li>
                <li>
                    <a href="#" onclick="idListado=5;MostrarListadoSegunID();">
                        <i class="fa fa-list"></i>Historial de Facturas</a>
                </li>
                <li>
                    <a href="#" onclick="idListado=8;MostrarListadoSegunID();">
                        <i class="fa fa-server"></i>Facturación Electrónica</a>
                </li>
                <li>
                    <a href="#" onclick="idListado=6;MostrarListadoSegunID();">
                        <i class="fa fa-dashboard"></i>RIPS Generados</a>
                </li>
                
                <li>
                    <a href="#" onclick="idListado=7;MostrarListadoSegunID();">
                        <i class="fa fa-users"></i>Liquidacion Colaboradores</a>
                </li>
                
                <li>
                    <a href="#" onclick="idListado=11;MostrarListadoSegunID();">
                        <i class="fa fa-calendar-check-o"></i> MiPres</a>
                </li>
               
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">');
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
                
                $css->CrearDiv("DivFiltrosFacturas", "box box-solid", "left", 1, 1);
             $css->CrearDiv("", "box-header with-border", "left", 1, 1);
                
               print('<h3 class="box-title">Filtros Facturas</h3>');
               
               $css->CrearDiv("", "box-tools", "left", 1, 1);    
                  print('<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                      </div>
                  </div>');
        
                   $css->div("", "box-body no-padding", "", "", "", "", "");
                                
                                //Creamos el Selector que contiene las bases de datos
                         $css->select("idTipoFactura", "form-control", "idTipoFactura", "", "", "onchange=ListarFacturas(`1`)", '');
                        $css->option("", "", "", "", "", "");
                            print("Seleccione el tipo de factura");
                        $css->Coption();
                        $sql="SELECT * FROM facturas_tipo";
                        $Consulta=$obCon->Query($sql);
                        while($DatosTipoFactura=$obCon->FetchAssoc($Consulta)){
                            $css->option("", "", "", $DatosTipoFactura["ID"], "", "");
                                print(utf8_encode($DatosTipoFactura["TipoFactura"]));
                            $css->Coption();
                        }
                    $css->Cselect();
                    
                    $css->select("idRegimenFactura", "form-control", "idRegimenFactura", "", "", "onchange=ListarFacturas(`1`)", '');
                        $css->option("", "", "", "", "", "");
                            print("Regimen de factura");
                        $css->Coption();
                        $sql="SELECT * FROM facturas_regimen";
                        $Consulta=$obCon->Query($sql);
                        while($DatosTipoFactura=$obCon->FetchAssoc($Consulta)){
                            $css->option("", "", "", $DatosTipoFactura["ID"], "", "");
                                print(utf8_encode($DatosTipoFactura["RegimenFactura"]));
                            $css->Coption();
                        }
                    $css->Cselect();
            
                    $css->Cdiv();
                $css->Cdiv();
                    
                          
                
                
            $css->Cdiv();
        
            
            $css->CrearDiv("", "col-md-10", "left", 1, 1);
                      
            $css->CrearDiv("DivMensajes", "col-md-2", "left", 1, 1);
            
            $css->CerrarDiv();
            $css->CrearDiv("div_filtro_mipres", "col-md-2", "left", 1, 1);
                $css->select("cmb_estado_mipres", "form-control", "cmb_estado_mipres", "Estado Mipres", "", "onchange=MostrarListadoSegunID()", "");
                    $css->option("", "", "", "", "", "");
                        print("Todos");
                    $css->Coption();
                    $css->option("", "", "", "0", "", "");
                        print("Anulados");
                    $css->Coption();
                    $css->option("", "", "", "1", "", "");
                        print("Activos");
                    $css->Coption();
                    $css->option("", "", "", "2", "", "");
                        print("Procesados");
                    $css->Coption();
                $css->Cselect();    
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
//print('<script src="jsPages/facturador.js"></script>');  //script propio de la pagina
print('<script src="jsPages/salud_prefacturacion.js"></script>');  //script propio de la pagina
print('<script src="jsPages/mipres.js"></script>');  //script propio de la pagina

$css->Cbody();
$css->Chtml();

?>