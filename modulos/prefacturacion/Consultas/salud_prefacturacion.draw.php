<?php

session_start();
if (!isset($_SESSION['username'])){// valida que el usuario tenga alguna sesion iniciada 
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];// se crea una variable iduser para saber almacenar que usuario esta tabajando

include_once("../clases/salud_prefacturacion.class.php");// se debe incluir la clase del modulo 
include_once("../../../constructores/paginas_constructor.php");// siempre debera de ir ya que utilizara html que esta en el constructor

if(!empty($_REQUEST["Accion"]) ){// se verifica si el indice accion es diferente a vacio 
    
    $css =  new PageConstruct("", "", 1, "", 1, 0);// se instancia para poder utilizar el html
    $obCon = new Prefacturacion($idUser);// se instancia para poder conectarse con la base de datos 
    
    switch($_REQUEST["Accion"]) {
       
        case 1://dibuja el listado de los pacientes
                        
            $Limit=20;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            
            $Condicion=" WHERE ID>0 ";
            
            if($Busquedas<>''){
                $Condicion.=" AND (t1.ID = '$Busquedas' or t1.NumeroDocumento = '%$Busquedas%' or t1.CodEPS like '%$Busquedas%' or t1.PrimerApellido like '%$Busquedas%' or t1.SegundoApellido like '%$Busquedas%' or t1.Telefono like '%$Busquedas%')";
            }
            
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM prefactura_paciente t1 $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.*,
                  (SELECT t2.NombreRegimen FROM prefactura_regimen_paciente t2 WHERE t2.ID=t1.idRegimenPaciente ) as NombreRegimenPaciente,
                  (SELECT t3.NombreUnidad FROM prefactura_unidades_medida_edad t3 WHERE t3.ID=t1.UnidadMedidaEdad ) as NombreUnidadMedidaEdad,
                  (SELECT t4.nombre_completo FROM salud_eps t4 WHERE t4.cod_pagador_min=t1.CodEPS LIMIT 1) as NombreEPS,
                  (SELECT CONCAT(t5.Nombre,' ',t5.Departamento) FROM catalogo_municipios t5 WHERE t5.CodigoDANE=t1.CodigoDANE LIMIT 1) as Municipio
                  
                  FROM prefactura_paciente t1 $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("Lista de Pacientes", "verde");
            
            
            
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                /*
                    $css->div("", "col-md-6", "", "", "", "", "");
                        print("<strong>Registros:</strong> <h4 style=color:green>". number_format($ResultadosTotales)."</h4>");
                    $css->Cdiv();
                 * 
                 */
                    print('<button type="button" class="btn btn-success btn-lg" onclick=FormularioCrearEditarPaciente()><i class="fa fa-user-plus"></i>
                    </button>');
                    $css->div("", "pull-right", "", "", "", "", "");
                        if($ResultadosTotales>$Limit){
                            $TotalPaginas= ceil($ResultadosTotales/$Limit);                               
                            print('<div class="input-group" style=width:150px>');
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                            print('<span class="input-group-addon" onclick=CambiePagina(`1`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                            }
                            $FuncionJS="onchange=CambiePagina(`1`);";
                            $css->select("CmbPage", "form-control", "CmbPage", "", "", $FuncionJS, "");

                                for($p=1;$p<=$TotalPaginas;$p++){
                                    if($p==$NumPage){
                                        $sel=1;
                                    }else{
                                        $sel=0;
                                    }

                                    $css->option("", "", "", $p, "", "",$sel);
                                        print($p);
                                    $css->Coption();

                                }

                            $css->Cselect();
                            if($ResultadosTotales>($PuntoInicio+$Limit)){
                                $NumPage1=$NumPage+1;
                            print('<span class="input-group-addon" onclick=CambiePagina(`1`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
                            }
                            print("</div>");
                        }    
                    $css->Cdiv();
                $css->Cdiv();
                   
                $css->CrearDiv("", "table-responsive mailbox-messages", "", 1, 1);
                    print('<table class="table table-hover table-striped">');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                $NombreCompleto= utf8_encode($RegistrosTabla["PrimerNombre"]." ".$RegistrosTabla["SegundoNombre"]." ".$RegistrosTabla["PrimerApellido"]." ".$RegistrosTabla["SegundoApellido"]);
                                print('<tr>');
                                    print("<td>");
                                        print('<button type="button" class="btn btn-warning btn-sm" onclick=FormularioCrearEditarPaciente(`2`,`'.$idItem.'`)><i class="fa fa-edit"></i></button>');
                                    print("</td>");
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["ID"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".$NombreCompleto."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["TipoDocumento"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["NumeroDocumento"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Edad"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode($RegistrosTabla["NombreUnidadMedidaEdad"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Sexo"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode($RegistrosTabla["CodEPS"]." ".$RegistrosTabla["NombreEPS"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode($RegistrosTabla["NombreRegimenPaciente"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode($RegistrosTabla["Direccion"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode($RegistrosTabla["Municipio"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Telefono"]));
                                    print("</td>");
                                    
                                    print("<td class='mailbox-date'>");
                                        print(($RegistrosTabla["Correo"]));
                                    print("</td>");
                                    /*
                                    print("<td class='mailbox-date'>");
                                        print(($RegistrosTabla["Updated"]));
                                    print("</td>");
                                    */
                                    
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
        break;//fin caso 1
        
        case 2:// formulario para crear o editar un paciente
            $css->CrearTitulo("Crear o Editar Usuario", "naranja");
            $css->CrearDiv("", "box box-default", "", 1, 1);
                $css->CrearDiv("", "box-body", "", 1, 1);
                    $css->CrearDiv("", "row", "", 1, 1);
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Tipo de Documento</label>');
                                $css->select("TipoDocumento", "form-control", "TipoDocumento", "", "", "", "onchange=ValidaDocumentoPaciente()");
                                    $css->option("", "", "", '', "", "");
                                        print("Seleccione una opción");
                                    $css->Coption();
                                    $css->option("", "", "", 'CC', "", "");
                                        print("CC");
                                    $css->Coption();
                                    $css->option("", "", "", 'CE', "", "");
                                        print("CE");
                                    $css->Coption();
                                    $css->option("", "", "", 'PA', "", "");
                                        print("PA");
                                    $css->Coption();
                                    $css->option("", "", "", 'RC', "", "");
                                        print("RC");
                                    $css->Coption();
                                    $css->option("", "", "", 'TI', "", "");
                                        print("TI");
                                    $css->Coption();
                                    $css->option("", "", "", 'AS', "", "");
                                        print("AS");
                                    $css->Coption();
                                    $css->option("", "", "", 'MS', "", "");
                                        print("MS");
                                    $css->Coption();
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Número del Documento</label>');                                
                                $css->input("text", "NumeroDocumento", "form-control", "NumeroDocumento", "Numero Documento", "", "Numero Documento", "off", "", "onchange=ValidaDocumentoPaciente()");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv(); 
                        
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>EPS</label>');
                                $css->select("CodEPS", "form-control", "CodEPS", "", "", "", "");
                                    $css->option("", "", "", '', "", "");
                                        print("Seleccione una EPS");
                                    $css->Coption();
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Regimen Paciente</label>');
                                $css->select("idRegimenPaciente", "form-control", "idRegimenPaciente", "", "", "", "");
                                    $css->option("", "", "", '', "", "");
                                        print("Seleccione una opción");
                                    $css->Coption();
                                    $sql="SELECT * FROM prefactura_regimen_paciente";
                                    $Consulta=$obCon->Query($sql);
                                    
                                    while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                                        $css->option("", "", "", $DatosConsulta["ID"], "", "");
                                            print($DatosConsulta["NombreRegimen"]);
                                        $css->Coption();
                                    }
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                    $css->CerrarDiv();
                    
                    $css->CrearDiv("", "row", "", 1, 1);
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Primer Nombre</label>');
                                $css->input("text", "PrimerNombre", "form-control", "PrimerNombre", "Primer Nombre", "", "Primer Nombre", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Segundo Nombre</label>');
                                $css->input("text", "SegundoNombre", "form-control", "SegundoNombre", "Segundo Nombre", "", "Segundo Nombre", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Primer Apellido</label>');
                                $css->input("text", "PrimerApellido", "form-control", "PrimerApellido", "Primer Apellido", "", "Primer Apellido", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Segundo Apellido</label>');
                                $css->input("text", "SegundoApellido", "form-control", "SegundoApellido", "Segundo Apellido", "", "Segundo Apellido", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        
                    $css->CerrarDiv(); 
                    //Fin Fila 2
                    //Inicio Fila 3
                    $css->CrearDiv("", "row", "", 1, 1);
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Fecha de Nacimiento</label>');
                                $css->input("date", "FechaNacimiento", "form-control", "FechaNacimiento", "Fecha de Nacimiento", "", "Fecha de Nacimiento", "off", "", "","style='line-height: 15px;'");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Edad</label>');
                                $css->input("number", "Edad", "form-control", "Edad", "Edad", "", "Edad", "off", "", "","disabled");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Unidad de Medida Edad</label>');
                                $css->select("UnidadMedidaEdad", "form-control", "UnidadMedidaEdad", "", "", "", "disabled");
                                    $css->option("", "", "", '', "", "");
                                        print("Unidad Edad");
                                    $css->Coption();
                                    $sql="SELECT * FROM prefactura_unidades_medida_edad";
                                    $Consulta=$obCon->Query($sql);
                                    
                                    while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                                        $css->option("", "", "", $DatosConsulta["ID"], "", "");
                                            print(utf8_encode($DatosConsulta["NombreUnidad"]));
                                        $css->Coption();
                                    }
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Sexo</label>');
                                $css->select("Sexo", "form-control", "Sexo", "", "", "", "");
                                    $css->option("", "", "", '', "", "");
                                        print("Seleccione una opción");
                                    $css->Coption();
                                    $css->option("", "", "", 'M', "", "");
                                        print("Masculino");
                                    $css->Coption();
                                    $css->option("", "", "", 'F', "", "");
                                        print("Femenino");
                                    $css->Coption();
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        
                    $css->CerrarDiv(); 
                    //Fin Fila 3
                    
                    //Inicio Fila 4
                    $css->CrearDiv("", "row", "", 1, 1);
                    
                        $css->CrearDiv("", "col-md-6", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Municipio</label>');
                                $css->select("CodigoDANE", "form-control", "CodigoDANE", "", "", "", "");
                                    $css->option("", "", "", '', "", "");
                                        print("Seleccione un Municipio");
                                    $css->Coption();
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-6", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Dirección</label>');
                                $css->input("text", "Direccion", "form-control", "Direccion", "Direccion", "", "Direccion", "off", "", "","");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        
                    
                    $css->CerrarDiv(); 
                    //Fin Fila4
                    //Inicio Fila 5
                    $css->CrearDiv("", "row", "", 1, 1);
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Zona Residencial</label>');
                                $css->select("ZonaResidencial", "form-control", "ZonaResidencial", "", "", "", "");
                                    $css->option("", "", "", '', "", "");
                                        print("Seleccione una Zona Residencial");
                                    $css->Coption();
                                    
                                    $css->option("", "", "", 'U', "", "");
                                        print("URBANO");
                                    $css->Coption();
                                    
                                    $css->option("", "", "", 'R', "", "");
                                        print("RURAL");
                                    $css->Coption();
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Teléfono</label>');
                                $css->input("text", "Telefono", "form-control", "Telefono", "Telefono", "", "Telefono", "off", "", "","");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Correo</label>');
                                $css->input("text", "Correo", "form-control", "Correo", "Correo", "", "Correo", "off", "", "","");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Guardar</label>');
                                $css->CrearBotonEvento("btnGuardarPaciente", "Guardar", 1, "onclick", "ConfirmaGuadarEditarPaciente()", "rojo");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                    $css->CerrarDiv(); 
                    //Fin Fila 5
                $css->CerrarDiv();
                
            $css->CerrarDiv();
            /*
            print('               
          
                
       
              <!-- /.form-group -->
              <div class="form-group">
                <label>Disabled</label>
                <select class="form-control select2 select2-hidden-accessible" disabled="" style="width: 100%;" tabindex="-1" aria-hidden="true">
                  <option selected="selected">Alabama</option>
                  <option>Alaska</option>
                  <option>California</option>
                  <option>Delaware</option>
                  <option>Tennessee</option>
                  <option>Texas</option>
                  <option>Washington</option>
                </select><span class="select2 select2-container select2-container--default select2-container--disabled" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-labelledby="select2-qqdj-container"><span class="select2-selection__rendered" id="select2-qqdj-container" title="Alabama">Alabama</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Multiple</label>
                <select class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select a State" style="width: 100%;" tabindex="-1" aria-hidden="true">
                  <option>Alabama</option>
                  <option>Alaska</option>
                  <option>California</option>
                  <option>Delaware</option>
                  <option>Tennessee</option>
                  <option>Texas</option>
                  <option>Washington</option>
                </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="Select a State" style="width: 517.5px;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
              </div>
              <!-- /.form-group -->
              <div class="form-group">
                <label>Disabled Result</label>
                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
                  <option selected="selected">Alabama</option>
                  <option>Alaska</option>
                  <option disabled="disabled">California (disabled)</option>
                  <option>Delaware</option>
                  <option>Tennessee</option>
                  <option>Texas</option>
                  <option>Washington</option>
                </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-cez6-container"><span class="select2-selection__rendered" id="select2-cez6-container" title="Alabama">Alabama</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          Visit <a href="https://select2.github.io/">Select2 documentation</a> for more examples and information about
          the plugin.
        </div>
      </div>');
           */ 
        break;// fin caso 2  
        
        case 3://Formulario para subir los pagos
            
            $idPago=$obCon->normalizar($_REQUEST["idPago"]);
            
            $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);
            
            $css->input("hidden", "idPago", "", "idPago", "", $idPago, "", "", "", "");
            
            $css->ProgressBar("PgProgresoUp", "LyProgresoCMG", "", 0, 0, 100, 0, "0%", "", "");
    
            $Mensaje="LEGALIZAR EL PAGO No. $idPago, de la EPS: <strong> ".$DatosPago["nom_enti_administradora"]." </strong>";
            $css->CrearTitulo($Mensaje, "verde");
            
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>FECHA DEL PAGO</strong>", 1);
                    $css->ColTabla("<strong>VALOR DEL PAGO:</strong>", 1);
                    $css->ColTabla("<strong>VALOR X LEGALIZAR:</strong>", 1);
                    
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    $css->ColTabla($DatosPago["fecha_transaccion"], 1);
                    $css->ColTabla(number_format($DatosPago["valor_transaccion"]), 1);
                    $css->ColTabla(number_format($DatosPago["valor_legalizar"]), 1);
                    
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                
                    print("<td>");
                        print("<strong>Separador de Archivo</strong><br>");
                        $css->select("CmbSeparador", "form-control", "CmbSeparador", "", "", "", "");
                            $css->option("", "", "", "", "", "");
                                print("Selecciones el Separador de los archivos");
                            $css->Coption();
                            $css->option("", "", "", "1", "", "");
                                print("Punto y Coma (;)");
                            $css->Coption();
                            $css->option("", "", "", "2", "", "",1);
                                print("Coma (,)");
                            $css->Coption();

                        $css->Cselect();
                    print("</td>");
                    print("<td colspan=2>");
                        print("<strong>Tipo de Giro</strong><br>");

                        $css->select("CmbTipoGiro", "form-control", "CmbTipoGiro", "", "", "", "");
                            $css->option("", "", "", "", "", "");
                                print("Tipo de Giro");
                            $css->Coption();
                            $css->option("", "", "", "1", "", "");
                                print("Giro Directo Subsidiado");
                            $css->Coption();
                            $css->option("", "", "", "2", "", "",1);
                                print("Cuenta Maestra (Tesoreria)");
                            $css->Coption();
                            $css->option("", "", "", "3", "", "");
                                print("Giro Directo Contributivo");
                            $css->Coption();

                        $css->Cselect();
                        
                    print("</td>");
                $css->CierraFilaTabla();

                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Pagos (AR)</strong>", 1);
                    $css->ColTabla("<strong>Fecha de Giro</strong>", 1);
                    $css->ColTabla("<strong>Soporte de Pago</strong>", 1);
                    
                $css->CierraFilaTabla();


                $css->FilaTabla(16);

                    print("<td>");
                        
                        $css->input("file", "UpPago", "form-control", "UpPago", "Soporte", "", "Soporte", "off", "", "style=width:100%");
                    print("</td>");
                    print("<td colspan=1>");
                        $css->input("date", "TxtFechaGira", "form-control", "TxtFechaGira", "Fecha", date("Y-m-d"), "Fecha", "off", "", "","style='line-height: 15px;'");
                        
                    print("</td>");
                    print("<td>");
                        
                        $css->input("file", "UpSoporte", "form-control", "UpSoporte", "Soporte", "", "Soporte", "off", "", "style=width:100%");
                    print("</td>");
                    $css->FilaTabla(16);
                        print("<td colspan=3>");
                            $css->textarea("TxtObservaciones", "form-control", "", "", "Observaciones", "", "");
                                
                            $css->Ctextarea();
                        print("<td>");
                    $css->CierraFilaTabla();
                    $css->FilaTabla(16);
                    print("<td colspan=3>");
                        $css->CrearBotonEvento("BtnEnviar", "Subir", 1, "onClick", "CargarAR('$idPago')", "rojo", "");
                       
                    print("</td>");
                    $css->CierraFilaTabla();
                $css->CierraFilaTabla();

            $css->CerrarTabla();
        break;//Fin caso 3    
        
    
        case 4:// formulario para editar pago
            
            $idPago=$obCon->normalizar($_REQUEST["idPago"]);
            $DatosPago=$obCon->DevuelveValores("salud_tesoreria", "ID", $idPago);
            $css->CrearTitulo("<strong>Editar el Pago No. $idPago</strong>", "naranja");
            $css->CrearTabla();
            
                $css->FilaTabla(16);
                    $css->ColTabla("<strong>Fecha:</strong>", 1,"C");
                    $css->ColTabla("<strong>EPS:</strong>", 1,"C");
                    $css->ColTabla("<strong>Banco:</strong>", 1,"C");
                    $css->ColTabla("<strong>Número Transacción:</strong>", 1,"C");
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    print("<td>");
                        $css->input("date", "Fecha", "form-control", "Fecha", "Fecha", date("Y-m-d"), "Fecha", "off", $DatosPago["fecha_transaccion"], "","style='line-height: 15px;'");
                        
                    print("</td>");   
                    print("<td>");
                        $DatosEPS=$obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosPago["cod_enti_administradora"]);
                        $css->select("CmbEps", "form-control", "CmbEps", "", "", "", "style=width:400px");
                            $css->option("", "", "", $DatosPago["cod_enti_administradora"], "", "");
                                print($DatosPago["nom_enti_administradora"]);
                            $css->Coption();    
                        $css->Cselect();
                    print("</td>");
                    
                    print("<td>");
                        $sql="SELECT * FROM salud_bancos";
                        $Consulta=$obCon->Query($sql);
                        
                        $css->select("CmbBanco", "form-control", "CmbBanco", "", "", "", "");
                            $css->option("", "", "", "", "", "");
                                print("Seleccione un Banco");
                            $css->Coption(); 
                            while($DatosBanco=$obCon->FetchAssoc($Consulta)){
                                    $Seleccionado=0;
                                if($DatosBanco["banco_transaccion"]==$DatosPago["banco_transaccion"]){
                                    $Seleccionado=1;
                                }
                                $css->option("", "", "", $DatosBanco["ID"], "", "",$Seleccionado);
                                    print($DatosBanco["banco_transaccion"]." || ".$DatosBanco["num_cuenta_banco"]." || ".$DatosBanco["tipo_cuenta"]);
                                $css->Coption(); 
                            }
                        $css->Cselect();
                        
                    print("</td>");
                    
                    print("<td>");
                        $css->input("text", "NumeroTransaccion", "form-control", "NumeroTransaccion", $DatosPago["num_transaccion"], $DatosPago["num_transaccion"], "Numero de transaccion", "off", "", "");
                    print("</td>");
                    
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    print("<td>");
                        $sql="SELECT * FROM salud_tesoreria_tipos_pago";
                            $Consulta=$obCon->Query($sql);

                            $css->select("CmbTipoPago", "form-control", "CmbTipoPago", "", "", "", "");
                                $css->option("", "", "", "", "", "");
                                    print("Seleccione un tipo de Pago");
                                $css->Coption(); 
                                while($DatosTipoPago=$obCon->FetchAssoc($Consulta)){
                                    $Seleccionado=0;
                                    if($DatosTipoPago["ID"]=$DatosPago["TipoPago"]){
                                        $Seleccionado=1;
                                    }
                                    $css->option("", "", "", $DatosTipoPago["ID"], "", "",$Seleccionado);
                                        print($DatosTipoPago["TipoPago"]);
                                    $css->Coption(); 
                                }
                            $css->Cselect();
                        print("</td>");
                        
                        
                        print("<td>");
                            $css->input("number", "ValorTransaccion", "form-control", "ValorTransaccion", "", $DatosPago["valor_transaccion"], "Valor de transaccion", "off", "", "");
                        print("</td>");
                        print("<td>");
                            $css->textarea("Observaciones", "form-control", "Observaciones", "", "Observaciones", "", "");
                                print($DatosPago["observacion"]);
                            $css->Ctextarea();
                        print("</td>");
                        
                        
                $css->CierraFilaTabla();
            
                $css->CerrarTabla();
            
            $css->CrearBotonEvento("BtnGuardar", "Editar", 1, "onclick", "ConfirmaEditarPago('$idPago')", "rojo");
            
        break;// fin caso 4
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>