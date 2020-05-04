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
                $Condicion.=" AND ( t1.NumeroDocumento like '%$Busquedas%' or t1.CodEPS like '%$Busquedas%' or t1.PrimerNombre like '%$Busquedas%' or t1.SegundoNombre like '%$Busquedas%' or t1.PrimerApellido like '%$Busquedas%' or t1.SegundoApellido like '%$Busquedas%' or t1.Telefono like '%$Busquedas%')";
            }
            
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM prefactura_paciente t1 $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT t1.*,
                  (SELECT t2.NombreRegimen FROM prefactura_regimen_paciente t2 WHERE t2.ID=t1.idRegimenPaciente ) as NombreRegimenPaciente,
                  
                  (SELECT t4.nombre_completo FROM salud_eps t4 WHERE t4.cod_pagador_min=t1.CodEPS LIMIT 1) as NombreEPS,
                  (SELECT CONCAT(t5.Nombre,' ',t5.Departamento) FROM catalogo_municipios t5 WHERE t5.CodigoDANE=t1.CodigoDANE LIMIT 1) as Municipio
                  
                  FROM prefactura_paciente t1 $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("Lista de Pacientes", "verde");
            
            
            
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                
                    print('<a class="btn btn-app" style="background-color:#12a900;color:white;" onclick=FormularioCrearEditarPaciente()>
                        <span class="badge bg-blue" style="font-size:14px">'.$ResultadosTotales.'</span>
                        <i class="fa fa-user-plus"></i> Agregar 
                      </a>');
                   
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
                                $DatosEdad=$obCon->CalcularEdad($RegistrosTabla["FechaNacimiento"]);
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
                                        print($RegistrosTabla["FechaNacimiento"]." <strong>".$DatosEdad["Edad"]." ".$DatosEdad["NombreUnidad"]."</strong>");
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
            $TipoFormulario=$obCon->normalizar($_REQUEST["TipoFormulario"]);
            $idEditar=$obCon->normalizar($_REQUEST["idEditar"]);
            $DatosPaciente=$obCon->DevuelveValores("prefactura_paciente", "ID", $idEditar);
            $css->CrearTitulo("Crear o Editar Usuario", "naranja");
            $css->CrearDiv("", "box box-default", "", 1, 1);
                $css->CrearDiv("", "box-body", "", 1, 1);
                    $css->CrearDiv("", "row", "", 1, 1);
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Tipo de Documento</label>');
                                $css->select("TipoDocumento", "form-control", "TipoDocumento", "", "", "", "onchange=ValidaDocumentoPaciente()");
                                    $sel=0;
                                    $css->option("", "", "", '', "", "",$sel);
                                        print("Seleccione una opción");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["TipoDocumento"]=="CC"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'CC', "", "",$sel);
                                        print("CC");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["TipoDocumento"]=="CE"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'CE', "", "",$sel);
                                        print("CE");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["TipoDocumento"]=="PA"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'PA', "", "",$sel);
                                        print("PA");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["TipoDocumento"]=="RC"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'RC', "", "",$sel);
                                        print("RC");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["TipoDocumento"]=="TI"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'TI', "", "",$sel);
                                        print("TI");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["TipoDocumento"]=="AS"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'AS', "", "",$sel);
                                        print("AS");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["TipoDocumento"]=="MS"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'MS', "", "",$sel);
                                        print("MS");
                                    $css->Coption();
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Número del Documento</label>');                                
                                $css->input("text", "NumeroDocumento", "form-control", "NumeroDocumento", "Numero Documento", $DatosPaciente["NumeroDocumento"], "Numero Documento", "off", "", "onchange=ValidaDocumentoPaciente()");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv(); 
                        
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>EPS</label>');
                                $css->select("CodEPS", "form-control", "CodEPS", "", "", "", "");
                                    $css->option("", "", "", '', "", "");
                                        print("Seleccione una EPS");
                                    $css->Coption();
                                    if($DatosPaciente["CodEPS"]<>''){
                                        $DatosEps=$obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosPaciente["CodEPS"]);
                                        $css->option("", "", "", $DatosPaciente["CodEPS"], "", "",1);
                                            print(utf8_encode($DatosPaciente["CodEPS"]." ".$DatosEps["nombre_completo"]));
                                        $css->Coption();
                                    }
                                    
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
                                        $sel=0;
                                        if($DatosPaciente["idRegimenPaciente"]==$DatosConsulta["ID"]){
                                            $sel=1;
                                        }
                                        $css->option("", "", "", $DatosConsulta["ID"], "", "",$sel);
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
                                $css->input("text", "PrimerNombre", "form-control", "PrimerNombre", "Primer Nombre", $DatosPaciente["PrimerNombre"], "Primer Nombre", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Segundo Nombre</label>');
                                $css->input("text", "SegundoNombre", "form-control", "SegundoNombre", "Segundo Nombre", $DatosPaciente["SegundoNombre"], "Segundo Nombre", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Primer Apellido</label>');
                                $css->input("text", "PrimerApellido", "form-control", "PrimerApellido", "Primer Apellido", $DatosPaciente["PrimerApellido"], "Primer Apellido", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Segundo Apellido</label>');
                                $css->input("text", "SegundoApellido", "form-control", "SegundoApellido", "Segundo Apellido", $DatosPaciente["SegundoApellido"], "Segundo Apellido", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        
                    $css->CerrarDiv(); 
                    //Fin Fila 2
                    //Inicio Fila 3
                    $css->CrearDiv("", "row", "", 1, 1);
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Fecha de Nacimiento</label>');
                                $css->input("date", "FechaNacimiento", "form-control", "FechaNacimiento", "Fecha de Nacimiento", $DatosPaciente["FechaNacimiento"], "Fecha de Nacimiento", "off", "", "onchange=CalculeEdad();","style='line-height: 15px;' max='".date("Y-m-d")."'");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                $Edad="";
                                $Unidad="";
                                if($DatosPaciente["FechaNacimiento"]<>''){
                                    $DatosEdad=$obCon->CalcularEdad($DatosPaciente["FechaNacimiento"]);
                                    $Edad=$DatosEdad["Edad"];
                                    $Unidad=$DatosEdad["Unidad"];
                                }
                                print('<label>Edad</label>');
                                $css->input("number", "Edad", "form-control", "Edad", "Edad", $Edad, "Edad", "off", "", "","disabled");
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
                                        $sel=0;
                                        if($Unidad==$DatosConsulta["ID"]){
                                            $sel=1;
                                        }
                                        $css->option("", "", "", $DatosConsulta["ID"], "", "",$sel);
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
                                    $sel=0;
                                    if($DatosPaciente["Sexo"]=="M"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'M', "", "",$sel);
                                        print("Masculino");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["Sexo"]=="F"){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'F', "", "",$sel);
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
                                    
                                    if($DatosPaciente["CodigoDANE"]<>''){
                                        $DatosMunicipio=$obCon->DevuelveValores("catalogo_municipios", "CodigoDANE", $DatosPaciente["CodigoDANE"]);
                                        $css->option("", "", "", $DatosMunicipio["CodigoDANE"], "", "",1);
                                            print(utf8_encode($DatosMunicipio["Nombre"]." || ".$DatosMunicipio["Departamento"]." || ".$DatosMunicipio["CodigoDANE"]));
                                        $css->Coption();
                                    }
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-6", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Dirección</label>');
                                $css->input("text", "Direccion", "form-control", "Direccion", "Direccion", $DatosPaciente["Direccion"], "Direccion", "off", "", "","");
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
                                    $sel=0;
                                    if($DatosPaciente["ZonaResidencial"]=='U'){
                                        $sel=1;
                                    }
                                    
                                    $css->option("", "", "", 'U', "", "",$sel);
                                        print("URBANO");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosPaciente["ZonaResidencial"]=='R'){
                                        $sel=1;
                                    }
                                    $css->option("", "", "", 'R', "", "",$sel);
                                        print("RURAL");
                                    $css->Coption();
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Teléfono</label>');
                                $css->input("text", "Telefono", "form-control", "Telefono", "Telefono", $DatosPaciente["Telefono"], "Telefono", "off", "", "","");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Correo</label>');
                                $css->input("text", "Correo", "form-control", "Correo", "Correo", $DatosPaciente["Correo"], "Correo", "off", "", "","");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Guardar</label>');
                                $css->CrearBotonEvento("btnGuardarPaciente", "Guardar", 1, "onclick", "ConfirmaGuardarEditarPaciente(`$TipoFormulario`,`$idEditar`)", "rojo");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                    $css->CerrarDiv(); 
                    //Fin Fila 5
                $css->CerrarDiv();
                
            $css->CerrarDiv();
            
        break;// fin caso 2  
        
        case 5://dibuja el listado de las reservas
                        
            $Limit=20;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $Estado=$obCon->normalizar($_REQUEST["Estado"]);
            $Condicion=" WHERE ID>0 ";
            
            if($Busquedas<>''){
                $Condicion.=" AND ( NumeroAutorizacion like '%$Busquedas%' or NumeroDocumento like '%$Busquedas%' or NombreCompleto like '%$Busquedas%' or Telefono like '%$Busquedas%')";
            }
            
            if($Estado<>''){
                $Condicion.=" AND Estado='$Estado'";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM vista_prefactura_reservas t1 $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT *
                  FROM vista_prefactura_reservas $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("Lista de Reservas", "azul");
            
            
            
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    print('<a class="btn btn-app" style="background-color:blue;color:white;" onclick=FormularioCrearEditarReserva()>
                        <span class="badge bg-green" style="font-size:14px">'.$ResultadosTotales.'</span>
                        <i class="fa fa-ambulance"></i> Reservar 
                      </a>');
                    $css->div("", "pull-right", "", "", "", "", "");
                        if($ResultadosTotales>$Limit){
                            $TotalPaginas= ceil($ResultadosTotales/$Limit);                               
                            print('<div class="input-group" style=width:150px>');
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                            print('<span class="input-group-addon" onclick=CambiePagina(`2`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                            }
                            $FuncionJS="onchange=CambiePagina(`2`);";
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
                            print('<span class="input-group-addon" onclick=CambiePagina(`2`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
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
                                
                                $NombreCompleto= utf8_encode($RegistrosTabla["NombreCompleto"]);
                                print('<tr>');
                                    print("<td>");
                                        print('<button type="button" class="btn btn-warning btn-sm" title="Editar" onclick=FormularioCrearEditarReserva(`2`,`'.$idItem.'`)><i class="fa fa-edit"></i></button>');
                                    print("</td>");
                                    print("<td>");
                                        print('<button type="button" class="btn btn-success btn-sm" title="Ver Reserva" onclick=VerReserva(`'.$idItem.'`)><i class="fa fa-eye"></i></button>');
                                    print("</td>");
                                    print("<td>");
                                        print('<button type="button" class="btn btn-primary btn-sm" title="Validar Reserva" onclick=FormularioValidarReserva(`'.$idItem.'`)><i class="fa fa-check"></i></button>');
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
                                        print(($RegistrosTabla["Sexo"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".$RegistrosTabla["NumeroAutorizacion"]."</strong>");
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode($RegistrosTabla["Direccion"]." <br>(".$RegistrosTabla["Municipio"]." ".$RegistrosTabla["Departamento"].")"));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Telefono"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        if($RegistrosTabla["Estado"]==3){
                                            print('<a href="'.substr($RegistrosTabla["RutaSoporte"], 3).'" target="blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> '.$RegistrosTabla["NombreArchivo"].'</a>');
                                        }else{
                                            print("");
                                        }
                                        
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode("<strong>".$RegistrosTabla["NombreEstado"]."</strong>"));
                                    print("</td>");
                                                                        
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
        break;//fin caso 5
        
        case 6:// formulario para crear o editar un paciente
            $TipoFormulario=$obCon->normalizar($_REQUEST["TipoFormulario"]);
            $idEditar=$obCon->normalizar($_REQUEST["idEditar"]);
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idEditar);
            $DatosPaciente=$obCon->DevuelveValores("prefactura_paciente", "ID", $DatosReserva["idPaciente"]);
            $css->CrearTitulo("Crear o Editar una Reserva", "verde");
            $css->CrearDiv("", "box box-default", "", 1, 1);
                $css->CrearDiv("", "box-body", "", 1, 1);
                    $css->CrearDiv("", "row", "", 1, 1);
                        $css->CrearDiv("", "col-md-12", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Seleccione un Paciente</label>');
                                $css->select("idPaciente", "form-control", "idPaciente", "", "", "", "");
                                    $sel=0;
                                    $css->option("", "", "", '', "", "",$sel);
                                        print("Seleccione un paciente");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosReserva["idPaciente"]<>''){
                                        $sel=1;
                                        $NombreCompleto=$DatosPaciente["PrimerNombre"]." ".$DatosPaciente["PrimerNombre"]." ".$DatosPaciente["PrimerApellido"]." ".$DatosPaciente["SegundoApellido"];
                                        $css->option("", "", "", $DatosPaciente["ID"], "", "",$sel);
                                            print($DatosPaciente["ID"]." || ".$NombreCompleto." || ".$DatosPaciente["TipoDocumento"]." || ".$DatosPaciente["NumeroDocumento"]." || ".utf8_encode($DatosPaciente["Direccion"])." || ".$DatosPaciente["Telefono"]);
                                        $css->Coption();
                                    }
                                    
                                $css->Cselect();
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                    $css->CerrarDiv();
                    //Fin Fila 1
                    //Inicia Fila 2
                    $css->CrearDiv("", "row", "", 1, 1);
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Número de Autorización</label>');
                                $css->input("text", "NumeroAutorizacion", "form-control", "NumeroAutorizacion", "Número de Autorización", $DatosReserva["NumeroAutorizacion"], "Numero de autorizacion", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv(); 
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Cantidad de Servicios</label>');
                                $css->input("text", "CantidadServicios", "form-control", "CantidadServicios", "Cantidad de Servicios", $DatosReserva["CantidadServicios"], "Cantidad de Servicios", "off", "", "");
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        $css->CrearDiv("", "col-md-6", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Diagnóstico</label>');
                                $css->select("Cie10", "form-control", "Cie10", "", "", "", "");
                                    $sel=0;
                                    $css->option("", "", "", '', "", "",$sel);
                                        print("Seleccione un diagnostico");
                                    $css->Coption();
                                    $sel=0;
                                    if($DatosReserva["Cie10"]<>''){
                                        $sel=1;
                                        $DatosCIE=$obCon->DevuelveValores("salud_cie10", "codigo_sistema", $DatosReserva["Cie10"]);
                                        
                                        $css->option("", "", "", $DatosCIE["codigo_sistema"], "", "",$sel);
                                            print(utf8_encode($DatosCIE["codigo_sistema"]." || ".$DatosCIE["descripcion_cups"]));
                                        $css->Coption();
                                    }
                                    
                                $css->Cselect();
                            $css->CerrarDiv(); 
                            
                        $css->CerrarDiv();  
                        
                    $css->CerrarDiv(); 
                    //Fin Fila 2
                    
                    //Inicio Fila 3
                    $css->CrearDiv("", "row", "", 1, 1);
                        
                        $css->CrearDiv("", "col-md-9", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Observaciones</label>');
                                $css->textarea("Observaciones", "form-control", "Observaciones", "", "Observaciones", "", "");
                                    print(utf8_encode($DatosReserva["Observaciones"]));
                                $css->Ctextarea();
                                
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                        $css->CrearDiv("", "col-md-3", "", 1, 1);
                            $css->CrearDiv("", "form-group", "", 1, 1);
                                print('<label>Guardar</label>');
                                $css->CrearBotonEvento("btnGuardarReserva", "Guardar", 1, "onclick", "ConfirmaGuardarEditarReserva(`$TipoFormulario`,`$idEditar`)", "rojo");
                            $css->CerrarDiv();                             
                        $css->CerrarDiv();
                        
                    $css->CerrarDiv(); 
                    //Fin Fila 3
                $css->CerrarDiv();
                
            $css->CerrarDiv();
            
        break;// fin caso 6
        
        case 7://Dibuja una Reserva con sus citas y formulario para agregar citas
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            if($idReserva==''){
                exit("E1;No se recibió el id de la reserva");
            }
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            $DatosPaciente=$obCon->DevuelveValores("prefactura_paciente", "ID", $DatosReserva["idPaciente"]);
            $DatosMunicipio=$obCon->DevuelveValores("catalogo_municipios", "CodigoDANE", $DatosPaciente["CodigoDANE"]);
            $DatosEps=$obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosPaciente["CodEPS"]);
            $NombreCompleto= utf8_encode($DatosPaciente["PrimerNombre"]." ".$DatosPaciente["SegundoNombre"]." ".$DatosPaciente["PrimerApellido"]." ".$DatosPaciente["SegundoApellido"]);
            $sql="SELECT COUNT(ID) as TotalCitas FROM prefactura_reservas_citas WHERE idReserva='$idReserva' AND Estado<10";
            $Validacion=$obCon->FetchAssoc($obCon->Query($sql));
            $TotalCitasAgregadas=$Validacion["TotalCitas"];
            $css->CrearTitulo("<strong>Reserva $idReserva</strong>", "azul");
            $css->CrearDiv("", "col-md-4", "center", 1, 1);
                $css->CrearDiv("", "box box-widget widget-user-2", "left", 1, 1);
                    $css->CrearDiv("", "widget-user-header bg-aqua-active", "left", 1, 1);
                        $css->CrearDiv("", "widget-user-image", "left", 1, 1);
                            print('<img class="img-circle" src="../../images/usuariostipo.png" alt="User Avatar"></img>');
                        $css->CerrarDiv();
                        print('<h4 class="widget-user-username">');
                            print("<strong>".$NombreCompleto."</strong>");
                        print('</h4>');
                    $css->CerrarDiv();   
                    
                    $css->CrearDiv("", "box-footer no-padding", "left", 1, 1);
                        print('<ul class="nav nav-stacked" style="font-size:16px;">');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spAutorizacionReserva`);">Autorización <span id="spAutorizacionReserva" class="pull-right" style="font-size:16px;"><strong>'.$DatosReserva["NumeroAutorizacion"].'</strong></span></a></li>');
                            print('<li><a>No. Servicios Autorizados<span  class="pull-right" style="font-size:16px;"><strong>'.$DatosReserva["CantidadServicios"].'</strong></span></a></li>');
                            print('<li><a>No. Servicios Disponibles<span id="spServiciosDisponibles" class="pull-right badge bg-blue" style="font-size:16px;"><strong>'.($DatosReserva["CantidadServicios"]-$TotalCitasAgregadas).'</strong></span></a></li>');
                            print('<li><a>Sexo <span  class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["Sexo"].'</strong></span></a></li>');
                            print('<li><a>Tipo de Documento <span class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["TipoDocumento"].'</strong></span></a></li>');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spIdentificacionPaciente`);">Numero de Documento <span id="spIdentificacionPaciente" class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["NumeroDocumento"].'</strong></span></a></li>');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spTelefonoPaciente`);">Teléfono <span id="spTelefonoPaciente" class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["Telefono"].'</strong></span></a></li>');
                            print('<li><a disabled onclick="CopiarAlPortapapelesID(`#spDireccionPaciente`);">Dirección <span  id="spDireccionPaciente" class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosPaciente["Direccion"]).'</strong></span></a></li>');
                            print('<li><a disabled >Ciudad <span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosMunicipio["Nombre"]).'</strong></span></a></li>');
                            print('<li><a disabled >Departamento <span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosMunicipio["Departamento"]).'</strong></span></a></li>');
                            print('<li><a disabled >EPS<span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosEps["nombre_completo"]).'</strong></span></a></li>');
                           
                        print('</ul>');
                    $css->CerrarDiv();
                $css->CerrarDiv();    
                
            $css->CerrarDiv();
            $css->CrearDiv("", "col-md-8", "center", 1, 1);
                $css->CrearDiv("DivFormularioCitas", "", "center", 1, 1);
                    $css->CrearTitulo("<strong>Agregar una Cita</strong>","verde");
                    $css->CrearDiv("", "box box-default", "", 1, 1);
                        $css->CrearDiv("", "box-body", "", 1, 1);
                            $css->CrearDiv("", "row", "", 1, 1);
                                $css->CrearDiv("", "col-md-12", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Seleccione un Hospital</label>');
                                        $css->select("idHospital", "form-control", "idHospital", "", "", "", "");                                            
                                            $css->option("", "", "", '', "", "");
                                                print("Seleccione un Hospital");
                                            $css->Coption();                                            
                                        $css->Cselect();
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();

                            $css->CerrarDiv();
                            //Fila 2
                            $css->CrearDiv("", "row", "", 1, 1);
                                $css->CrearDiv("", "col-md-6", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Fecha</label>');
                                        $css->input("date", "Fecha", "form-control", "Fecha", "Fecha", "", "Fecha", "off", "", "","style='line-height: 15px;'");
                        
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();
                                
                                $css->CrearDiv("", "col-md-6", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        
                                                        
                                        print('<div class="bootstrap-timepicker">
                                            <div class="form-group">
                                              <label>Hora:</label>

                                              <div class="input-group">
                                                <input id="Hora" type="text" class="form-control timepicker">

                                                <div class="input-group-addon">
                                                  <i class="fa fa-clock-o"></i>
                                                </div>
                                              </div>
                                              <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                          </div>');
                                          
                                       // print('<label>Hora</label>');
                                       // $css->input("text", "Hora", "form-control", "Hora", "Hora", "", "Hora", "off", "", "","style='line-height: 15px;'");
                        
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();
                                
                                

                            $css->CerrarDiv();
                            //Fin fila 2
                            //Fila 3
                            $css->CrearDiv("", "row", "", 1, 1);
                                
                                $css->CrearDiv("", "col-md-8", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Observaciones</label>');
                                        $css->textarea("Observaciones", "form-control", "Observaciones", "", "Observaciones", "", "");
                                            
                                        $css->Ctextarea();
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();
                                $css->CrearDiv("", "col-md-4", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Agregar</label>');
                                        $css->CrearBotonEvento("btnCrearCita", "Agregar", 1, "onclick", "AgregarCitaAReserva(`$idReserva`)", "naranja");
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();
                            $css->CerrarDiv();                            
                            //Fin fila 3
                            
                            
                        $css->CerrarDiv();    

                    $css->CerrarDiv();
                    
                $css->CerrarDiv();
                
                $css->CrearDiv("DivCitasReserva", "", "center", 1, 1);
                    
                $css->CerrarDiv();
            $css->CerrarDiv();
        break;//Fin caso 7   
        
        case 8://Listar las citas de una reserva
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            if($idReserva==''){
                exit("E;No se recibió el id de la reserva");
            }
            $css->CrearTitulo("<strong>Citas agregadas a la Reserva $idReserva</strong>", "naranja");
            $sql="SELECT t1.*,
                    (SELECT t2.Nombre FROM ips t2 WHERE t2.ID=t1.idHospital) as NombreHospital,
                    (SELECT t2.NIT FROM ips t2 WHERE t2.ID=t1.idHospital) as NITHospital,
                    (SELECT t2.Direccion FROM ips t2 WHERE t2.ID=t1.idHospital) as DireccionHospital,
                    (SELECT t2.Municipio FROM ips t2 WHERE t2.ID=t1.idHospital) as MunicipioHospital,
                    (SELECT t2.Departamento FROM ips t2 WHERE t2.ID=t1.idHospital) as DepartamentoHospital,
                    (SELECT t3.EstadoCita FROM prefactura_reservas_citas_estados t3 WHERE t3.ID=t1.Estado) as NombreEstadoCita
                    FROM prefactura_reservas_citas t1 WHERE t1.idReserva='$idReserva' AND Estado<10";
            $Consulta=$obCon->Query($sql);
            
            $css->CrearDiv("", "table-responsive mailbox-messages", "", 1, 1);
                    print('<table class="table table-hover table-striped">');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                $NombreCompleto= utf8_encode($RegistrosTabla["NombreHospital"]);
                                print('<tr>');
                                    print("<td>");
                                        $disabled="";
                                        if($RegistrosTabla["Estado"]>1){
                                            $disabled="disabled";
                                        }
                                        print('<button '.$disabled.' type="button" class="btn btn-success btn-sm" onclick=ConfirmarCita(`'.$idReserva.'`,`'.$idItem.'`)><i class="fa fa-hand-o-up"></i></button>');
                                    print("</td>");
                                    print("<td>");
                                        print('<button type="button" class="btn btn-primary btn-sm" onclick=FormularioAdjuntarDocumentosCita(`'.$idItem.'`)><i class="fa fa-paperclip"></i></button>');
                                    print("</td>");
                                    print("<td>");
                                        $Ruta="../../general/Consultas/PDF_Documentos.draw.php?idDocumento=2000&ID=".$idItem;
                                        print('<a class="btn btn-danger btn-sm" href="'.$Ruta.'" target="_blank"><i class="fa fa-file-pdf-o"></i></a>');
                                    print("</td>");
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["ID"]);
                                    print("</td>");
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["Fecha"]." ".$RegistrosTabla["Hora"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".$NombreCompleto."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Observaciones"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["NombreEstadoCita"]);
                                    print("</td>");
                                    print("<td style='font-size:16px;text-align:center;color:red' title='Borrar'>");                             
                                        $css->li("", "fa  fa-remove", "", "onclick=EliminarCita(`$idReserva`,`1`,`$idItem`) style=font-size:16px;cursor:pointer;text-align:center;color:red");
                                        $css->Cli();
                                    print("</td>");                                                                       
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            
        break;//Fin caso 8   
        
        case 9://Dibuja formulario para validar una reserva
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            if($idReserva==''){
                exit("E1;No se recibió el id de la reserva");
            }
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            $DatosPaciente=$obCon->DevuelveValores("prefactura_paciente", "ID", $DatosReserva["idPaciente"]);
            $DatosMunicipio=$obCon->DevuelveValores("catalogo_municipios", "CodigoDANE", $DatosPaciente["CodigoDANE"]);
            $DatosEps=$obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosPaciente["CodEPS"]);
            $NombreCompleto= utf8_encode($DatosPaciente["PrimerNombre"]." ".$DatosPaciente["SegundoNombre"]." ".$DatosPaciente["PrimerApellido"]." ".$DatosPaciente["SegundoApellido"]);
            
            $css->CrearTitulo("<strong>Validar la Reserva $idReserva</strong>", "azul");
            $css->CrearDiv("", "col-md-4", "center", 1, 1);
                $css->CrearDiv("", "box box-widget widget-user-2", "left", 1, 1);
                    $css->CrearDiv("", "widget-user-header bg-aqua-active", "left", 1, 1);
                        $css->CrearDiv("", "widget-user-image", "left", 1, 1);
                            print('<img class="img-circle" src="../../images/usuariostipo.png" alt="User Avatar"></img>');
                        $css->CerrarDiv();
                        print('<h4 class="widget-user-username">');
                            print("<strong>".$NombreCompleto."</strong>");
                        print('</h4>');
                    $css->CerrarDiv();   
                    
                    $css->CrearDiv("", "box-footer no-padding", "left", 1, 1);
                        print('<ul class="nav nav-stacked" style="font-size:16px;">');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spAutorizacionReserva`);">Autorización <span id="spAutorizacionReserva" class="pull-right" style="font-size:16px;"><strong>'.$DatosReserva["NumeroAutorizacion"].'</strong></span></a></li>');
                            print('<li><a>No. Servicios Autorizados<span  class="pull-right" style="font-size:16px;"><strong>'.$DatosReserva["CantidadServicios"].'</strong></span></a></li>');
                            //print('<li><a>No. Servicios Disponibles<span id="spServiciosDisponibles" class="pull-right badge bg-blue" style="font-size:16px;"><strong>'.($DatosReserva["CantidadServicios"]-$TotalCitasAgregadas).'</strong></span></a></li>');
                            print('<li><a>Sexo <span  class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["Sexo"].'</strong></span></a></li>');
                            print('<li><a>Tipo de Documento <span class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["TipoDocumento"].'</strong></span></a></li>');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spIdentificacionPaciente`);">Numero de Documento <span id="spIdentificacionPaciente" class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["NumeroDocumento"].'</strong></span></a></li>');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spTelefonoPaciente`);">Teléfono <span id="spTelefonoPaciente" class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["Telefono"].'</strong></span></a></li>');
                            print('<li><a disabled onclick="CopiarAlPortapapelesID(`#spDireccionPaciente`);">Dirección <span  id="spDireccionPaciente" class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosPaciente["Direccion"]).'</strong></span></a></li>');
                            print('<li><a disabled >Ciudad <span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosMunicipio["Nombre"]).'</strong></span></a></li>');
                            print('<li><a disabled >Departamento <span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosMunicipio["Departamento"]).'</strong></span></a></li>');
                            print('<li><a disabled >EPS<span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosEps["nombre_completo"]).'</strong></span></a></li>');
                           
                        print('</ul>');
                    $css->CerrarDiv();
                $css->CerrarDiv();    
                
            $css->CerrarDiv();
            $css->CrearDiv("", "col-md-8", "center", 1, 1);
                $css->CrearDiv("DivFormularioValidacion", "", "center", 1, 1);
                    $css->CrearTitulo("<strong>Validar Autorización</strong>","verde");
                    $css->CrearDiv("", "box box-default", "", 1, 1);
                        $css->CrearDiv("", "box-body", "", 1, 1);
                            
                            //Fila 1
                            $css->CrearDiv("", "row", "", 1, 1);
                                $css->CrearDiv("", "col-md-3", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Fecha</label>');
                                        $css->input("date", "Fecha", "form-control", "Fecha", "Fecha", "", "Fecha", "off", "", "","style='line-height: 15px;'");
                        
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();
                                
                                $css->CrearDiv("", "col-md-4", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Observaciones</label>');
                                        $css->textarea("Observaciones", "form-control", "Observaciones", "", "Observaciones", "", "");
                                            
                                        $css->Ctextarea();
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();
                                
                                $css->CrearDiv("", "col-md-5", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Soporte</label>');
                                        $css->input("file", "SoporteValidacionReserva", "form-control", "SoporteValidacionReserva", "", "Subir Soporte", "Subir Soporte", "off", "", "");
                                        print("<br>");
                                        $css->CrearBotonEvento("btnValidarReserva", "Validar", 1, "onclick", "ValidarReserva(`$idReserva`)", "rojo");
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();

                            $css->CerrarDiv();
                            //Fin fila 1
                        $css->CerrarDiv();    

                    $css->CerrarDiv();
                    
                $css->CerrarDiv();
                
                $css->CrearDiv("DivValidacionesReservas", "", "center", 1, 1);
                    
                $css->CerrarDiv();
            $css->CerrarDiv();
            print("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
        break;//Fin caso 9
        
        case 10;//Dibujar la validacion de las reservas
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            if($idReserva==''){
                exit("E;No se recibió el id de la reserva");
            }
            $css->CrearTitulo("<strong>Datos de la Validacion de la Reserva $idReserva</strong>", "naranja");
            $sql="SELECT t1.*,
                    (SELECT CONCAT(t2.Nombre,t2.Apellido) FROM usuarios t2 WHERE t2.idUsuarios=t1.idUser) as NombreUsuario
                    
                    FROM prefactura_reservas_validacion t1 WHERE t1.idReserva='$idReserva'";
            $Consulta=$obCon->Query($sql);
            
            $css->CrearDiv("", "table-responsive mailbox-messages", "", 1, 1);
                    print('<table class="table table-hover table-striped">');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                $NombreCompleto= utf8_encode($RegistrosTabla["NombreUsuario"]);
                                print('<tr>');
                                    
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["Fecha"]);
                                    print("</td>");                                    
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Observaciones"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print('<a href="'.substr($RegistrosTabla["Ruta"], 3).'" target="blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> '.$RegistrosTabla["NombreArchivo"].'</a>');
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".$NombreCompleto."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Created"]);
                                    print("</td>");
                                    print("<td style='font-size:16px;text-align:center;color:red' title='Borrar'>");                             
                                        $css->li("", "fa  fa-remove", "", "onclick=EliminarValidacion(`$idReserva`,`1`,`$idItem`) style=font-size:16px;cursor:pointer;text-align:center;color:red");
                                        $css->Cli();
                                    print("</td>");                                                                       
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            
            
        break;//fin caso 10    
        
        case 11://Dibuja formulario para agregar adjuntos a una cita
            $idCita=$obCon->normalizar($_REQUEST["idCita"]);
            $ListaAActualizar=$obCon->normalizar($_REQUEST["ListaAActualizar"]);
            if($idCita==''){
                exit("E1;No se recibió el id de la cita");
            }
            $DatosCita=$obCon->DevuelveValores("prefactura_reservas_citas", "ID", $idCita);
            $idReserva=$DatosCita["idReserva"];
            $DatosHospital=$obCon->DevuelveValores("ips", "ID", $DatosCita["idHospital"]);
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $DatosCita["idReserva"]);
            $DatosPaciente=$obCon->DevuelveValores("prefactura_paciente", "ID", $DatosReserva["idPaciente"]);
            $DatosMunicipio=$obCon->DevuelveValores("catalogo_municipios", "CodigoDANE", $DatosPaciente["CodigoDANE"]);
            $DatosEps=$obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosPaciente["CodEPS"]);
            $NombreCompleto= utf8_encode($DatosPaciente["PrimerNombre"]." ".$DatosPaciente["SegundoNombre"]." ".$DatosPaciente["PrimerApellido"]." ".$DatosPaciente["SegundoApellido"]);
            
            $css->CrearTitulo("<strong>Adjuntar los Documentos de la Cita $idCita</strong>", "azul");
            $css->CrearDiv("", "col-md-4", "center", 1, 1);
                $css->CrearDiv("", "box box-widget widget-user-2", "left", 1, 1);
                    $css->CrearDiv("", "widget-user-header bg-aqua-active", "left", 1, 1);
                        $css->CrearDiv("", "widget-user-image", "left", 1, 1);
                            print('<img class="img-circle" src="../../images/usuariostipo.png" alt="User Avatar"></img>');
                        $css->CerrarDiv();
                        print('<h4 class="widget-user-username">');
                            print("<strong>".$NombreCompleto."</strong>");
                        print('</h4>');
                    $css->CerrarDiv();   
                    
                    $css->CrearDiv("", "box-footer no-padding", "left", 1, 1);
                        print('<ul class="nav nav-stacked" style="font-size:16px;">');
                            print('<li><a>Fecha y Hora <span class="pull-right" style="font-size:16px;"><strong>'.$DatosCita["Fecha"].' '.$DatosCita["Hora"].' </strong></span></a></li>');
                            print('<li><a>Hospital <span  class="pull-right" style="font-size:16px;"><strong>'.$DatosHospital["Nombre"].'</strong></span></a></li>');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spAutorizacionReserva`);">Autorización <span id="spAutorizacionReserva" class="pull-right" style="font-size:16px;"><strong>'.$DatosReserva["NumeroAutorizacion"].'</strong></span></a></li>');
                            print('<li><a>No. Servicios Autorizados<span  class="pull-right" style="font-size:16px;"><strong>'.$DatosReserva["CantidadServicios"].'</strong></span></a></li>');
                            //print('<li><a>No. Servicios Disponibles<span id="spServiciosDisponibles" class="pull-right badge bg-blue" style="font-size:16px;"><strong>'.($DatosReserva["CantidadServicios"]-$TotalCitasAgregadas).'</strong></span></a></li>');
                            print('<li><a>Sexo <span  class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["Sexo"].'</strong></span></a></li>');
                            print('<li><a>Tipo de Documento <span class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["TipoDocumento"].'</strong></span></a></li>');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spIdentificacionPaciente`);">Numero de Documento <span id="spIdentificacionPaciente" class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["NumeroDocumento"].'</strong></span></a></li>');
                            print('<li><a onclick="CopiarAlPortapapelesID(`#spTelefonoPaciente`);">Teléfono <span id="spTelefonoPaciente" class="pull-right" style="font-size:16px;"><strong>'.$DatosPaciente["Telefono"].'</strong></span></a></li>');
                            print('<li><a disabled onclick="CopiarAlPortapapelesID(`#spDireccionPaciente`);">Dirección <span  id="spDireccionPaciente" class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosPaciente["Direccion"]).'</strong></span></a></li>');
                            print('<li><a disabled >Ciudad <span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosMunicipio["Nombre"]).'</strong></span></a></li>');
                            print('<li><a disabled >Departamento <span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosMunicipio["Departamento"]).'</strong></span></a></li>');
                            print('<li><a disabled >EPS<span class="pull-right" style="font-size:16px;"><strong>'.utf8_encode($DatosEps["nombre_completo"]).'</strong></span></a></li>');
                           
                        print('</ul>');
                    $css->CerrarDiv();
                $css->CerrarDiv();    
                
            $css->CerrarDiv();
            $css->CrearDiv("", "col-md-8", "center", 1, 1);
                $css->CrearDiv("DivFormularioValidacion", "", "center", 1, 1);
                    $css->CrearTitulo("<strong>Adjuntar Documento</strong>","verde");
                    $css->CrearDiv("", "box box-default", "", 1, 1);
                        $css->CrearDiv("", "box-body", "", 1, 1);
                            
                            //Fila 1
                            $css->CrearDiv("", "row", "", 1, 1);
                                
                                $css->CrearDiv("", "col-md-6", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Soporte</label>');
                                        $css->input("file", "upSoporte", "form-control", "upSoporte", "", "Subir Soporte", "Subir Soporte", "off", "", "");
                                        
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();
                                $css->CrearDiv("", "col-md-6", "", 1, 1);
                                    $css->CrearDiv("", "form-group", "", 1, 1);
                                        print('<label>Adjuntar</label>');
                                        
                                        $css->CrearBotonEvento("btnAdjuntar", "Adjuntar", 1, "onclick", "AdjuntarDocumentoCita(`$idCita`,`$idReserva`,`$ListaAActualizar`)", "verde");
                                    $css->CerrarDiv();                             
                                $css->CerrarDiv();

                            $css->CerrarDiv();
                            //Fin fila 1
                        $css->CerrarDiv();    

                    $css->CerrarDiv();
                    
                $css->CerrarDiv();
                
                $css->CrearDiv("DivAdjuntosCita", "", "center", 1, 1);
                    
                $css->CerrarDiv();
            $css->CerrarDiv();
            print("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
        break;//Fin caso 11
        
        case 12;//Dibujar los adjuntos de una cita
            $idCita=$obCon->normalizar($_REQUEST["idCita"]);
            $ListadoAActualizar=$obCon->normalizar($_REQUEST["ListadoAActualizar"]);
            if($idCita==''){
                exit("E;No se recibió el id de la cita");
            }
            $DatosCita=$obCon->DevuelveValores("prefactura_reservas_citas", "ID", $idCita);
            $idReserva=$DatosCita["idReserva"];
            $css->CrearTitulo("<strong>Documentos adjuntados a la cita $idCita</strong>", "naranja");
            $sql="SELECT t1.*,
                    (SELECT CONCAT(t2.Nombre,t2.Apellido) FROM usuarios t2 WHERE t2.idUsuarios=t1.idUser) as NombreUsuario
                    
                    FROM prefactura_reservas_citas_adjuntos t1 WHERE t1.idCita='$idCita'";
            $Consulta=$obCon->Query($sql);
            
            $css->CrearDiv("", "table-responsive mailbox-messages", "", 1, 1);
                    print('<table class="table table-hover table-striped">');
                        print('<tbody>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                
                                $NombreCompleto= utf8_encode($RegistrosTabla["NombreUsuario"]);
                                print('<tr>');
                                    
                                    print("<td class='mailbox-subject'>");
                                        print('<a href="'.substr($RegistrosTabla["Ruta"], 3).'" target="blank" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> '.$RegistrosTabla["NombreArchivo"].'</a>');
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".$NombreCompleto."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["Created"]);
                                    print("</td>");
                                    print("<td style='font-size:16px;text-align:center;color:red' title='Borrar'>");                             
                                        $css->li("", "fa  fa-remove", "", "onclick=EliminarAdjuntoCita(`$idReserva`,`$idCita`,`1`,`$idItem`,`$ListadoAActualizar`) style=font-size:16px;cursor:pointer;text-align:center;color:red");
                                        $css->Cli();
                                    print("</td>");                                                                       
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            
            
        break;//fin caso 12
        
        case 13://dibuja el listado de las citas
                        
            $Limit=20;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $Estado=$obCon->normalizar($_REQUEST["Estado"]);
            $FechaInicialRangos=$obCon->normalizar($_REQUEST["FechaInicialRangos"]);
            $FechaFinalRangos=$obCon->normalizar($_REQUEST["FechaFinalRangos"]);
            $Condicion=" WHERE ID>0 ";
            
            if($Busquedas<>''){
                $Condicion.=" AND ( NumeroAutorizacion like '%$Busquedas%' or NumeroDocumento like '%$Busquedas%' or NombrePaciente like '%$Busquedas%')";
            }
            
            if($Estado<>''){
                $Condicion.=" AND Estado='$Estado'";
            }
            
            if($FechaInicialRangos<>''){
                $Condicion.=" AND Fecha>='$FechaInicialRangos'";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND Fecha<='$FechaFinalRangos'";
            }
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM vista_prefactura_reservas_citas t1 $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT *
                  FROM vista_prefactura_reservas_citas $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("Lista de Citas", "naranja");
            
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    print('<span class="badge bg-orange" style="font-size:14px">'.$ResultadosTotales.'</span>');
                    $css->div("", "pull-right", "", "", "", "", "");
                        if($ResultadosTotales>$Limit){
                            $TotalPaginas= ceil($ResultadosTotales/$Limit);                               
                            print('<div class="input-group" style=width:150px>');
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                            print('<span class="input-group-addon" onclick=CambiePagina(`3`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                            }
                            $FuncionJS="onchange=CambiePagina(`3`);";
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
                            print('<span class="input-group-addon" onclick=CambiePagina(`3`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
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
                                $idReserva=$RegistrosTabla["idReserva"];
                                $NombreCompleto= utf8_encode($RegistrosTabla["NombrePaciente"]);
                                print('<tr>');
                                    print("<td>");
                                        $disabled="";
                                        if($RegistrosTabla["Estado"]>1){
                                            $disabled="disabled";
                                        }
                                        print('<button '.$disabled.' type="button" class="btn btn-success btn-sm" onclick=ConfirmarCita(`'.$idReserva.'`,`'.$idItem.'`)><i class="fa fa-hand-o-up"></i></button>');
                                    print("</td>");
                                    print("<td>");
                                        print('<button type="button" class="btn btn-primary btn-sm" onclick=FormularioAdjuntarDocumentosCita(`'.$idItem.'`,`2`)><i class="fa fa-paperclip"></i></button>');
                                    print("</td>");
                                    
                                    print("<td>");
                                        $Ruta="../../general/Consultas/PDF_Documentos.draw.php?idDocumento=2000&ID=".$idItem;
                                        print('<a class="btn btn-danger btn-sm" href="'.$Ruta.'" target="_blank"><i class="fa fa-file-pdf-o"></i></a>');
                                    print("</td>");
                                    
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["ID"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Fecha"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Hora"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["NombreHospital"]));
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
                                        print(($RegistrosTabla["Telefono"]));
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print(utf8_encode("<strong>".$RegistrosTabla["NombreEstado"]."</strong>"));
                                    print("</td>");
                                                                        
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
        break;//fin caso 13
        
        case 14://dibuja el listado de pendientes por facturar
                        
            $Limit=20;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);            
            $FechaInicialRangos=$obCon->normalizar($_REQUEST["FechaInicialRangos"]);
            $FechaFinalRangos=$obCon->normalizar($_REQUEST["FechaFinalRangos"]);
            $obCon->CrearVistaPendientesPorFacturar($FechaInicialRangos, $FechaFinalRangos);
            $Condicion=" WHERE ID>0 ";
            
            if($Busquedas<>''){
                $Condicion.=" AND ( NumeroAutorizacion like '%$Busquedas%' or NumeroDocumento like '%$Busquedas%' or NombrePaciente like '%$Busquedas%')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items 
                   FROM vista_pendiente_por_facturar t1 $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT *
                  FROM vista_pendiente_por_facturar $Condicion ORDER BY ID ASC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("Pendientes por Facturar", "azul");
            
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    print('<span class="badge bg-blue" style="font-size:14px">'.$ResultadosTotales.'</span>');
                    $css->div("", "pull-right", "", "", "", "", "");
                        if($ResultadosTotales>$Limit){
                            $TotalPaginas= ceil($ResultadosTotales/$Limit);                               
                            print('<div class="input-group" style=width:150px>');
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                            print('<span class="input-group-addon" onclick=CambiePagina(`4`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                            }
                            $FuncionJS="onchange=CambiePagina(`4`);";
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
                            print('<span class="input-group-addon" onclick=CambiePagina(`4`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
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
                                $NombreCompleto= utf8_encode($RegistrosTabla["NombrePaciente"]);
                                print('<tr>');
                                    
                                    print("<td>");
                                        print('<button type="button" class="btn btn-success btn-sm" onclick=FormularioCrearFactura(`'.$idItem.'`)><i class="fa fa-credit-card"></i></button>');
                                    print("</td>");
                                                                  
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["ID"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["FechaReserva"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".($RegistrosTabla["NumeroAutorizacion"])."</strong>");
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
                                        print(($RegistrosTabla["Telefono"]));
                                    print("</td>");
                                                                                                            
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
        break;//fin caso 14
        
        case 15://Dibujo el formulario para hacer la factura
            $idReserva=$obCon->normalizar($_REQUEST["idReserva"]);
            $Fecha=$obCon->normalizar($_REQUEST["Fecha"]); 
            if($Fecha==''){
                $css->CrearTitulo("<strong>SELECCIONE UNA FECHA DE CORTE</strong>", "rojo");
                exit();
            }
            
            $DatosReserva=$obCon->DevuelveValores("prefactura_reservas", "ID", $idReserva);
            $DatosPaciente=$obCon->DevuelveValores("prefactura_paciente", "ID", $DatosReserva["idPaciente"]);
            $DatosRegimenPaciente=$obCon->DevuelveValores("prefactura_regimen_paciente", "ID", $DatosPaciente["idRegimenPaciente"]);
            $DatosEPS=$obCon->DevuelveValores("salud_eps", "cod_pagador_min", $DatosPaciente["CodEPS"]);
            $DatosEdad=$obCon->CalcularEdad($DatosPaciente["FechaNacimiento"]);
            $Edad=$DatosEdad["Edad"]." ".$DatosEdad["NombreUnidad"];            
            $NombreCompleto= utf8_encode($DatosPaciente["PrimerNombre"]." ".$DatosPaciente["SegundoNombre"]." ".$DatosPaciente["PrimerApellido"]." ".$DatosPaciente["SegundoApellido"]);
            $sql="SELECT Fecha FROM prefactura_reservas_citas WHERE Estado=3 AND idReserva='$idReserva' AND Fecha<='$Fecha' ORDER BY Fecha DESC LIMIT 1";
            $DatosFechaVencimiento=$obCon->FetchAssoc($obCon->Query($sql));
            $FechaVencimiento=$DatosFechaVencimiento["Fecha"];
            $css->form("frmFactura", "form-class", "frmTrasladarCuentas", "post", "procesadores/salud_prefacturacion.php", "#", "", "onsubmit=ConfirmaGuardarFactura();return false;");
                $css->input("hidden", "idReserva", "idReserva", "idReserva", "", $idReserva, "", "", "", "");
                $css->CrearTabla();
                    $back="#e6e5e3";
                    $css->FilaTabla(16);
                        print("<td colspan='6' style='background-color:$back;text-align:center'>");
                            print("<strong>FACTURA PARA AUTORIZACIÓN ".$DatosReserva["NumeroAutorizacion"]."</strong>");
                        print("</td>");
                        //$css->ColTabla("<strong>FACTURA PARA AUTORIZACIÓN ".$DatosReserva["NumeroAutorizacion"]."</strong>", 6, "C");
                    $css->CierraFilaTabla();
                $css->CerrarTabla();
                $css->CrearTabla();    
                    $back="#f1efee";
                    $css->FilaTabla(16);

                        print("<td style='background-color:$back;text-align:center'>");
                            print("<strong>RESOLUCIÓN:</strong>");
                        print("</td>");
                        print("<td style='background-color:$back;text-align:center'>");
                            print("<strong>TIPO DE FACTURA:</strong>");
                        print("</td>");
                        print("<td style='background-color:$back;text-align:center'>");
                            print("<strong>RESOLUCIÓN DE LA FACTURA:</strong>");
                        print("</td>");

                    $css->CierraFilaTabla();
                    $back="#faf9f8";
                    $css->FilaTabla(16);    
                        print("<td style='background-color:$back;text-align:center'>");
                            $sql="SELECT ID,NombreInterno FROM empresapro_resoluciones_facturacion WHERE Completada='NO'";
                            $Consulta=$obCon->Query($sql);
                            $css->select("cmbResolucionDIAN", "form-control", "cmbResolucionDIAN", "", "", "", "");
                            while($DatosResolucion=$obCon->FetchAssoc($Consulta)){
                                $css->option("", "", "", $DatosResolucion["ID"], "", "");
                                    print($DatosResolucion["NombreInterno"]);
                                $css->Coption();
                            }    

                            $css->Cselect();
                        print("</td>");

                        print("<td style='background-color:$back;text-align:center'>");
                            $sql="SELECT * FROM facturas_tipo ";
                            $Consulta=$obCon->Query($sql);
                            $css->select("cmbTipoFactura", "form-control", "cmbTipoFactura", "", "", "onchange=MuestraOcultaReferenciaTutela();", "");
                                $css->option("", "", "", "", "", "");
                                    print("Seleccione el tipo de factura");
                                $css->Coption();
                                while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                                    $css->option("", "", "", $DatosConsulta["ID"], "", "");
                                        print(utf8_encode($DatosConsulta["TipoFactura"]));
                                    $css->Coption();
                                }    

                            $css->Cselect();
                            $css->CrearDiv("divReferenciaTutela", "", "left", 1, 1);
                                $css->input("text", "ReferenciaTutela", "form-control", "ReferenciaTutela", "", "", "Referencia Tutela", "off", "", "", "");
                            $css->CerrarDiv();
                        print("</td>");

                        print("<td style='background-color:$back;text-align:center'>");
                            $sql="SELECT * FROM facturas_regimen ";
                            $Consulta=$obCon->Query($sql);
                            $css->select("cmbRegimenFactura", "form-control", "cmbRegimenFactura", "", "", "", "");
                                $css->option("", "", "", "", "", "");
                                    print("Seleccione el regimen de la factura");
                                $css->Coption();
                                while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                                    $css->option("", "", "", $DatosConsulta["ID"], "", "");
                                        print($DatosConsulta["RegimenFactura"]);
                                    $css->Coption();
                                }    

                            $css->Cselect();
                        print("</td>");
                    $css->CierraFilaTabla();
                $css->CerrarTabla();
                //datos del paciente
                $css->CrearTabla();  
                    $back="#f1efee";
                    $css->FilaTabla(16);
                        print("<td colspan='6' style='background-color:$back;text-align:center'>");
                            print("<strong>DATOS DEL PACIENTE</strong>");
                        print("</td>");                    
                    $css->CierraFilaTabla();

                    $css->FilaTabla(16);
                        $css->ColTabla("Paciente", 1);  
                        $css->ColTabla("<strong>".$NombreCompleto."</strong>", 1); 
                        $css->ColTabla("No. Autorización", 1);  
                        $css->ColTabla("<strong>".$DatosReserva["NumeroAutorizacion"]."</strong>", 1);  
                    $css->CierraFilaTabla();

                    $css->FilaTabla(16);
                        $css->ColTabla("No. Identificación", 1);  
                        $css->ColTabla("<strong>".$DatosPaciente["NumeroDocumento"]."</strong>", 1); 
                        $css->ColTabla("Fecha Vencimiento", 1);  
                        $css->ColTabla("<strong>".$FechaVencimiento."</strong>", 1);  
                    $css->CierraFilaTabla();

                    $css->FilaTabla(16);
                        $css->ColTabla("Fecha de Nacimiento", 1);  
                        $css->ColTabla("<strong>".$DatosPaciente["FechaNacimiento"]." || ".$Edad."</strong>", 1); 
                        $css->ColTabla("Tipo de Afiliado", 1);  
                        $css->ColTabla("<strong>".$DatosRegimenPaciente["NombreRegimen"]."</strong>", 1);  
                    $css->CierraFilaTabla();

                    $css->FilaTabla(16);
                        $css->ColTabla("Dirección", 1);  
                        $css->ColTabla("<strong>".$DatosPaciente["Direccion"]."</strong>", 1); 
                        $css->ColTabla("Teléfono", 1);  
                        $css->ColTabla("<strong>".$DatosPaciente["Telefono"]."</strong>", 1);  
                    $css->CierraFilaTabla();

                    $css->FilaTabla(16);
                        $css->ColTabla("EPS", 1);  
                        $css->ColTabla("<strong>".$DatosEPS["sigla_nombre"]." (".$DatosEPS["cod_pagador_min"].")</strong>", 1); 
                        $css->ColTabla("NIT EPS", 1);  
                        $css->ColTabla("<strong>".$DatosEPS["nit"]."</strong>", 1);  
                    $css->CierraFilaTabla();
                $css->CerrarTabla();


                $css->CrearTabla();  
                    $back="#f1efee";
                    $css->FilaTabla(16);
                        print("<td colspan='6' style='background-color:$back;text-align:center'>");
                            print("<strong>CITAS DISPONIBLES PARA FACTURAR</strong>");
                        print("</td>");                    
                    $css->CierraFilaTabla();
                    $css->FilaTabla(16);
                        $css->ColTabla("<strong>FECHA Y HORA</strong>", 1);
                        $css->ColTabla("<strong>HOSPITAL</strong>", 1);
                        $css->ColTabla("<strong>SERVICIO</strong>", 1);
                        $css->ColTabla("<strong>COLABORADOR</strong>", 1);
                        $css->ColTabla("<strong>RECORRIDO</strong>", 1);
                        $css->ColTabla("<strong>VALOR</strong>", 1);
                    $css->CierraFilaTabla();

                    $sql="SELECT t1.*,
                            (SELECT t2.Nombre FROM ips t2 WHERE t2.ID=t1.idHospital) as NombreHospital,
                            (SELECT t2.Direccion FROM ips t2 WHERE t2.ID=t1.idHospital) as DireccionHospital,
                            (SELECT t2.Municipio FROM ips t2 WHERE t2.ID=t1.idHospital) as MunicipioHospital,
                            (SELECT t2.Departamento FROM ips t2 WHERE t2.ID=t1.idHospital) as DepartamentoHospital

                        FROM prefactura_reservas_citas t1 WHERE Estado=3 AND idReserva='$idReserva';";
                    $Consulta=$obCon->Query($sql);
                    while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                        print("<tr>");
                        $idItem=$DatosConsulta["ID"];
                        
                        $css->ColTabla($DatosConsulta["Fecha"]." ".$DatosConsulta["Hora"], 1);
                        $css->ColTabla($DatosConsulta["NombreHospital"]." || ".$DatosConsulta["MunicipioHospital"]." || ".$DatosConsulta["DepartamentoHospital"], 1);
                        
                        print("<td>");
                            
                            $css->select("cmbServicio_".$idItem, "SelectServicio", "cmbServicio[$idItem]", "", "", "onchange=CalculeTotalItem(`$idItem`)", "style=width:200px;",0);
                                $css->option("", "", "", "", "", "");
                                    print("Servicio");
                                $css->Coption();
                            $css->Cselect();
                        print("</td>");
                        print("<td>");
                            $css->select("cmbColaborador_".$idItem, "SelectColaborador", "cmbColaborador[$idItem]", "", "", "", "style=width:200px;",0);
                                $css->option("", "", "", "", "", "");
                                    print("Colaborador");
                                $css->Coption();
                            $css->Cselect();
                        print("</td>");
                        print("<td>");
                            $css->select("cmbRecorrido_".$idItem, "form-control", "cmbRecorrido[$idItem]", "", "", "onchange=CalculeTotalItem(`$idItem`)", "",0);
                                $css->option("", "", "", "", "", "");
                                    print("Recorrido");
                                $css->Coption();
                                $css->option("", "", "", "1", "", "");
                                    print("IDA");
                                $css->Coption();
                                $css->option("", "", "", "2", "", "");
                                    print("VUELTA");
                                $css->Coption();
                                $css->option("", "", "", "3", "", "");
                                    print("COMPLETO");
                                $css->Coption();
                            $css->Cselect();
                        print("</td>");
                        print("<td>");
                            $css->input("text", "TxtTarifa_".$idItem, "form-control texttarifa", "TxtTarifa[$idItem]", "", "", "", "", "", "onkeyup=calcularTotal()");
                            
                        print("</td>");
                        print("</tr>");
                    }
                    print("<tr>");
                        print("<td colspan=5 style='text-align:right;font-size:25px;'>");
                            print("<strong>TOTAL</strong>");

                        print("</td>");
                        print("<td colspan='1' >");
                            print("<span id='spTotalFactura' style='color:blue;font-size:25px;'>0</span>");
                        print("</td>");
                    print("</tr>");
                    
                $css->CerrarTabla(); 
                
                $css->textarea("ObservacionesFactura", "form-control", "ObservacionesFactura", "", "Observaciones", "", "");
                $css->Ctextarea();
                print("<br>");
                $css->CrearBotonEvento("btnGuardar", "Guardar", 1, "onclick", "confirmaGuardarFactura()", "rojo");
                
            $css->Cform();    
        break;//Fin caso 15    
        
        case 16://dibuja el listado de facturas
                        
            $Limit=20;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);            
            $FechaInicialRangos=$obCon->normalizar($_REQUEST["FechaInicialRangos"]);
            $FechaFinalRangos=$obCon->normalizar($_REQUEST["FechaFinalRangos"]);
            $obCon->CrearVistaFacturas();
            $Condicion=" WHERE ID<>'' ";
            
            if($Busquedas<>''){
                $Condicion.=" AND ( NumeroFactura='$Busquedas'  or NumeroAutorizacion like '%$Busquedas%' or NumeroDocumento like '%$Busquedas%' or NombrePaciente like '%$Busquedas%')";
            }
            if($FechaInicialRangos<>''){
                $Condicion.=" AND Fecha>='$FechaInicialRangos'";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND Fecha<='$FechaFinalRangos'";
            }
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(ID) as Items,SUM(TotalFactura) AS TotalFacturas  
                   FROM vista_facturas_basante t1 $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
            $TotalFacturas=$totales['TotalFacturas'];        
            $sql="SELECT * 
                  FROM vista_facturas_basante $Condicion LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("Lista de Facturas", "azul");
            
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    print('Facturas: <span class="badge bg-blue" style="font-size:14px">'.$ResultadosTotales.'</span>');
                    print(' || Total Facturado: <span class="badge bg-green" style="font-size:14px">'.number_format($TotalFacturas,2).'</span>');
                    $css->div("", "pull-right", "", "", "", "", "");
                        if($ResultadosTotales>$Limit){
                            $TotalPaginas= ceil($ResultadosTotales/$Limit);                               
                            print('<div class="input-group" style=width:150px>');
                            if($NumPage>1){
                                $NumPage1=$NumPage-1;
                            print('<span class="input-group-addon" onclick=CambiePagina(`5`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                            }
                            $FuncionJS="onchange=CambiePagina(`5`);";
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
                            print('<span class="input-group-addon" onclick=CambiePagina(`5`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
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
                                
                                print('<tr>');
                                    
                                    print("<td>");
                                        $Link="../../general/Consultas/PDF_Documentos.draw.php?idDocumento=2001&idFactura=".$idItem;
                                        
                                        print('<a href='.$Link.' target="_blank"><button type="button" class="btn btn-danger btn-sm"><i class="fa fa-file-pdf-o"></i></button></a>');
                                    print("</td>");
                                                                  
                                    print("<td class='mailbox-name'>");
                                        print($RegistrosTabla["Fecha"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["NumeroFactura"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".($RegistrosTabla["NumeroAutorizacion"])."</strong>");
                                    print("</td>");
                                    
                                    print("<td class='mailbox-subject'>");
                                        print(number_format($RegistrosTabla["TotalFactura"]));
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".$RegistrosTabla["NombrePaciente"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print("<strong>".$RegistrosTabla["NumeroDocumento"]."</strong>");
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["NombreTipoFactura"]);
                                    print("</td>");
                                    print("<td class='mailbox-subject'>");
                                        print($RegistrosTabla["NombreResolucionFactura"]);
                                    print("</td>");                                     
                                    print("<td class='mailbox-subject'>");
                                        print(($RegistrosTabla["Observaciones"]));
                                    print("</td>");
                                                                                                            
                                print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
        break;//fin caso 16
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>