<?php

@session_start();
if (!isset($_SESSION['username'])){// valida que el usuario tenga alguna sesion iniciada 
  exit("<a href='../../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];// se crea una variable iduser para saber almacenar que usuario esta tabajando

include_once("../clases/mipres.class.php");// se debe incluir la clase del modulo 
include_once("../../../constructores/paginas_constructor.php");// siempre debera de ir ya que utilizara html que esta en el constructor

if(!empty($_REQUEST["Accion"]) ){// se verifica si el indice accion es diferente a vacio 
    
    $css =  new PageConstruct("", "", 1, "", 1, 0);// se instancia para poder utilizar el html
    $obCon = new MiPres($idUser);// se instancia para poder conectarse con la base de datos 
    
    switch($_REQUEST["Accion"]) {
       
        case 1://dibuja el listado de la programacion mipres
                        
            $empresa_id=1;
            $tabla="mipres_programacion";
            $Limit=20;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
            $cmb_estado_mipres=$obCon->normalizar($_REQUEST["cmb_estado_mipres"]);
                        
            $FechaInicialRangos=$obCon->normalizar($_REQUEST["FechaInicialRangos"]);
            $FechaFinalRangos=$obCon->normalizar($_REQUEST["FechaFinalRangos"]);
            
           
            $Condicion=" WHERE ID>0 ";
            
            if($Busquedas<>''){
                $Condicion.=" AND ( t1.ID = '$Busquedas' or t1.NoPrescripcion = '$Busquedas' or t1.NoIDPaciente = '$Busquedas' )";
            }
            if($FechaInicialRangos<>''){
                $Condicion.=" AND t1.FecDireccionamiento>='$FechaInicialRangos 00:00:00'";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND t1.FecDireccionamiento<='$FechaFinalRangos 23:59:59'";
            }
            if($cmb_estado_mipres<>''){
                $Condicion.=" AND t1.EstDireccionamiento='$cmb_estado_mipres'";
            }
                        
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items 
                   FROM $tabla t1  $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
               
            $colsQuery="ID,IDDireccionamiento,NoPrescripcion,TipoTec,ConTec,TipoIDPaciente,NoIDPaciente,NoEntrega,NoSubEntrega,FecMaxEnt,TipoIDProv,NoIDProv,CodMunEnt,CantTotAEntregar,DirPaciente,CodSerTecAEntregar,NoIDEPS,CodEPS,FecDireccionamiento,EstDireccionamiento,FecAnulacion,user_id ";
            $colsSubQuery=",(SELECT IdProgramacion FROM mipres_registro_programacion t2 WHERE t2.mipres_id=t1.ID and user_id_anulacion='' ORDER BY ID DESC LIMIT 1) as idProgramacion ";
            $colsSubQuery.=",(SELECT IdEntrega FROM mipres_registro_entrega t3 WHERE t3.mipres_id=t1.ID and user_id_anulacion='' ORDER BY ID DESC LIMIT 1) as idEntrega ";
            $sql="SELECT $colsQuery  $colsSubQuery              
                  FROM $tabla t1 $Condicion ";
            $statement=$sql;
            
            $limit_condition=" ORDER BY EstDireccionamiento ASC,ID DESC LIMIT $PuntoInicio,$Limit;";
            
            $sql.=$limit_condition;
            
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("MiPres", "verde");
            
            
            $statement= base64_encode(urlencode($statement));
            $colsQuery= base64_encode(urlencode($colsQuery.",IdProgramacion,idEntrega"));
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    $CondicionURL= urlencode($Condicion);
                    $link='../../general/procesadores/GeneradorCSV.process.php?Opcion=3&empresa_id='.$empresa_id.'&tb='.$tabla.'&st='.$statement.'&colsQuery='.$colsQuery;
                    $css->div("", "row", "", "", "", "", "");
                        $css->CrearDiv("", "col-md-2", "left", 1, 1); 
                            $css->div("", "pull-left", "", "", "", "", "");
                                print('<a class="btn btn-app" style="background-color:#12a900;color:white;" href="'.$link.'" target="_blank">
                                    <span class="badge bg-blue" style="font-size:14px">'.$ResultadosTotales.'</span>
                                    <i class="fa fa-file-excel-o"></i> Exportar 
                                  </a>');
                            $css->Cdiv();
                        $css->Cdiv();
                        $css->CrearDiv("", "col-md-2", "left", 1, 1); 
                            //$css->div("", "pull-left", "", "", "", "style=text-align:center", "");
                                $FechaInicialMiPres=date("Y-m-d");
                                if(isset($_REQUEST["fecha_inicial_mipres"])){
                                    $FechaInicialMiPres=$obCon->normalizar($_REQUEST["fecha_inicial_mipres"]);
                                }
                                
                                
                                print("<strong>Fecha Inicial</strong>");
                                $css->input("date", "FechaInicialMiPres", "form-control", "FechaInicialMiPres", "Fecha", $FechaInicialMiPres, "Fecha Inicial", "off", "", "","style='line-height: 15px;'");
                                
                            //$css->Cdiv();
                        $css->Cdiv();
                        $css->CrearDiv("", "col-md-2", "left", 1, 1); 
                            //$css->div("", "pull-left", "", "", "", "style=text-align:center", "");
                                
                                
                                $FechaFinallMiPres=date("Y-m-d");
                                if(isset($_REQUEST["fecha_final_mipres"])){
                                    $FechaFinallMiPres=$obCon->normalizar($_REQUEST["fecha_final_mipres"]);
                                }
                                
                                print("<strong>Fecha Final</strong>");
                                $css->input("date", "FechaFinalMiPres", "form-control", "FechaFinalMiPres", "Fecha", $FechaFinallMiPres, "Fecha Final", "off", "", "","style='line-height: 15px;'");
                                
                            //$css->Cdiv();
                        $css->Cdiv();
                        $css->CrearDiv("", "col-md-2", "left", 1, 1); 
                            print("<strong>Consultar MiPres</strong>");
                            $css->CrearBotonEvento("btn_obtener_direccionamiento_mipres", "Obtener", 1, "onclick", "iniciar_consulta_mipres()", "naranja");
                        $css->Cdiv();
                        $css->CrearDiv("", "col-md-2", "left", 1, 1); 
                        $porcentaje=0;
                        $leyenda_barra="";
                        if(isset($_REQUEST["porcentaje_barra_mipres"])){
                            $porcentaje=$_REQUEST["porcentaje_barra_mipres"];
                            $leyenda_barra="Consulta completada";
                        }
                        print("<span id='sp_msg_mipres'>$leyenda_barra</span>");
                        print('<div class="progress">
                                <div id="PgProgresoUp" name="PgProgresoUp" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:'.$porcentaje.'%">
                                  <div id="LyProgresoCMG" name="LyProgresoCMG" "="">'.$porcentaje.'%</div>
                                </div>
                              </div>');
                        $css->Cdiv();
                    $css->Cdiv();
                    $css->div("", "row", "", "", "", "", "");
                        $css->div("", "pull-right", "", "", "", "", "");
                            if($ResultadosTotales>$Limit){
                                $TotalPaginas= ceil($ResultadosTotales/$Limit);                               
                                print('<div class="input-group" style=width:150px>');
                                if($NumPage>1){
                                    $NumPage1=$NumPage-1;
                                print('<span class="input-group-addon" onclick=CambiePagina(`11`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                                }
                                $FuncionJS="onchange=CambiePagina(`11`);";
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
                                print('<span class="input-group-addon" onclick=CambiePagina(`11`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
                                }
                                print("</div>");
                            }    
                        $css->Cdiv();
                    
                    $css->Cdiv();
                $css->Cdiv();
                   
                $css->CrearDiv("", "table-responsive mailbox-messages", "", 1, 1);
                    print('<table class="table table-hover table-striped">');
                        print('<tbody>');
                            print('<tr>');
                                print("<th>Programar</th>");
                                print("<th>Entregar</th>");
                                print("<th>Anular Programación</th>");
                                print("<th>Anular Entrega</th>");
                                print("<th>ID</th>");
                                print("<th>IDDireccionamiento</th>");
                                print("<th>NoPrescripcion</th>");                                
                                print("<th>FecDireccionamiento</th>");
                                print("<th>EstDireccionamiento</th>");                                
                                print("<th>TipoTec</th>");
                                print("<th>ConTec</th>");
                                print("<th>TipoIDPaciente</th>");
                                print("<th>NoIDPaciente</th>");                                
                                print("<th>NoEntrega</th>");
                                print("<th>NoSubEntrega</th>");                                
                                print("<th>FecMaxEnt</th>");  
                                print("<th>TipoIDProv</th>");                                
                                print("<th>NoIDProv</th>");
                                print("<th>DirPaciente</th>");
                                print("<th>CodMunEnt</th>");
                                print("<th>CodSerTecAEntregar</th>");
                                print("<th>CantTotAEntregar</th>");
                                print("<th>NoIDEPS</th>");
                                print("<th>CodEPS</th>");
                                print("<th>FecAnulacion</th>");
                                print("<th>IDUsuario</th>");
                                    
                            print('</tr>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                $programacion_id=$RegistrosTabla["idProgramacion"];
                                $entrega_id=$RegistrosTabla["idEntrega"];
                                print('<tr>');
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstDireccionamiento"]==1){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-success fa fa-share" title="Direccionar Autorización" onclick="confirma_programar_mipres(`'.$idItem.'`)" target="_blank" ></button>');

                                print("</td>");
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstDireccionamiento"]==2){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-primary fa fa-send" title="Entregar" onclick="frm_entregar_mipres(`'.$idItem.'`)" target="_blank" ></button>');

                                print("</td>");
                                
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["idProgramacion"]>0){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-warning fa fa-times-circle" title="Anular Programacion" onclick="confirma_anular_programacion_mipres(`'.$programacion_id.'`)" target="_blank" ></button>');

                                print("</td>");
                                
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["idEntrega"]>0){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-danger  fa fa-remove" title="Anular Entrega" onclick="confirma_anular_entrega_mipres(`'.$entrega_id.'`)" target="_blank" ></button>');

                                print("</td>");
                                                                   

                                print("<td class='mailbox-name'>");
                                    print($RegistrosTabla["ID"]);
                                print("</td>");
                                print("<td class='mailbox-subject text-primary'>");
                                    print("<strong>".$RegistrosTabla["IDDireccionamiento"]."</strong>");
                                print("</td>");
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".$RegistrosTabla["NoPrescripcion"]."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-success'>");
                                    print("<strong>".($RegistrosTabla["FecDireccionamiento"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-primary'>");
                                    if($RegistrosTabla["EstDireccionamiento"]==0){
                                        $NombreEstado="ANULADO";
                                    }
                                    if($RegistrosTabla["EstDireccionamiento"]==1){
                                        $NombreEstado="ACTIVO";
                                    }
                                    if($RegistrosTabla["EstDireccionamiento"]==2){
                                        $NombreEstado="PROCESADO";
                                    }
                                    print("<strong>$NombreEstado</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["TipoTec"]."");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["ConTec"]."");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["TipoIDPaciente"]."");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("<strong>".$RegistrosTabla["NoIDPaciente"]."</strong>");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoEntrega"]."");
                                print("</td>");  
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoSubEntrega"]."");
                                print("</td>");  
                                print("<td class='mailbox-subject'>");
                                    print("<strong>".$RegistrosTabla["FecMaxEnt"]."</strong>");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["TipoIDProv"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoIDProv"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["DirPaciente"]."");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CodMunEnt"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CodSerTecAEntregar"]."");
                                print("</td>"); 
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".number_format($RegistrosTabla["CantTotAEntregar"])."</strong>");
                                print("</td>");
                                
                                
                                print("<td class='mailbox-subject text-success'>");
                                    print("<strong>".($RegistrosTabla["NoIDEPS"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-primary'>");
                                    print("<strong>".($RegistrosTabla["CodEPS"])."</strong>");
                                print("</td>");
                                
                                
                                
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".($RegistrosTabla["FecAnulacion"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("".($RegistrosTabla["user_id"])."");
                                print("</td>");
                                
                                
                            print('</tr>');

                            }

                        print('</tbody>');
                    print('</table>');
                $css->Cdiv();
            $css->Cdiv();
        break;//fin caso 1
        
        case 2: //dibuja el formulario para entregar un mipres
            $empresa_id=1;
            $tabla="mipres_programacion";
            
            $mipres_id=$obCon->normalizar($_REQUEST["mipres_id"]);
            $datos_mipres=$obCon->DevuelveValores($tabla, "ID", $mipres_id);
            $datos_factura=$obCon->DevuelveValores("vista_facturas_basante", "idTraza", $mipres_id);
            $fecha_entrega="";
            $servicios_entregados="";
            if($datos_factura["Fecha"]<>''){
                $fecha_entrega=$datos_factura["Fecha"];
            }
            if($datos_factura["CitasFacturadas"]<>''){
                $servicios_entregados=$datos_factura["CitasFacturadas"];
            }
            $tipo_doc_paciente=$datos_mipres["TipoIDPaciente"];
            $identificacion_paciente=$datos_mipres["NoIDPaciente"];
            $sql="SELECT ID,CONCAT(PrimerNombre,' ',SegundoNombre,' ',PrimerApellido,' ',SegundoApellido) as nombre_completo FROM prefactura_paciente WHERE TipoDocumento='$tipo_doc_paciente' AND NumeroDocumento='$identificacion_paciente' LIMIT 1";
            $datos_paciente=$obCon->FetchArray($obCon->Query($sql));
            
            $css->CrearTitulo("<strong>Entregar MiPres $mipres_id</strong>", "azul");
            $css->CrearTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("No. Prescripcion:", 1);
                    $css->ColTabla("<strong>".$datos_mipres["NoPrescripcion"]."</strong>", 1,"R");
                    $css->ColTabla("ID. Paciente:", 1);
                    $css->ColTabla("<strong>".$datos_mipres["TipoIDPaciente"].$datos_mipres["NoIDPaciente"]."</strong>", 1,"R");
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    if($datos_paciente["ID"]<>''){
                        $nombre_paciente=$datos_paciente["nombre_completo"];
                    }else{
                        $nombre_paciente="El paciente no existe en la base de datos";
                    }
                    $css->ColTabla($nombre_paciente, 4,"C");
                    
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    $css->ColTabla("Cantidad Programada:", 1);
                    $css->ColTabla("<strong>".$datos_mipres["CantTotAEntregar"]."</strong>", 1,"R");
                    $css->ColTabla("Cantidad Total Entregada:", 1);
                    print("<td>");
                        $css->input("text", "mipres_cantidad_entregada", "form-control", "mipres_cantidad_entregada", "Cantidad total entregada", $servicios_entregados, "Cantidad total entregada", "off", "", "");
                    print("</td>");
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("Fecha Máxima de Entrega:", 1);
                    $css->ColTabla("<strong>".$datos_mipres["FecMaxEnt"]."</strong>", 1,"R");
                    $css->ColTabla("Fecha Real de Entrega:", 1);
                    print("<td>");
                        $css->input("date", "mipres_fecha_real_entrega", "form-control", "mipres_fecha_real_entrega", "Fecha Real de Entrega", $fecha_entrega, "Fecha real de entrega", "off", "", "style='line-height: 15px;'");
                    print("</td>");
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    $css->ColTabla("Tipo de ID de quien recibe:", 1);
                    print("<td>");
                        $sql="SELECT * FROM prefactura_paciente_tipo_documento";
                        $consulta=$obCon->Query($sql);
                        
                        $css->select("mipres_tipo_documento_recibe", "form-control", "mipres_tipo_documento_recibe", "", "", "", "");
                            while($datos_consulta=$obCon->FetchAssoc($consulta)){
                                $sel=0;
                                if( $datos_consulta["tipo_documento"]==$datos_mipres["TipoIDPaciente"]){
                                    $sel=1;
                                }
                                $css->option("", "", "", $datos_consulta["tipo_documento"], "", "",$sel);
                                    print($datos_consulta["tipo_documento"]);
                                $css->Coption();
                            }
                        $css->Cselect();
                    print("</td>");
                    $css->ColTabla("Identificación de quien recibe:", 1);
                    print("<td>");
                        $css->input("text", "mipres_identificacion_recibe", "form-control", "mipres_identificacion_recibe", "Cantidad total entregada", $datos_mipres["NoIDPaciente"], "Cantidad total entregada", "off", "", "");
                    print("</td>");
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    $css->ColTabla("Parentesco: ", 1);
                    print("<td>");
                        $sql="SELECT * FROM prefactura_paciente_parentesco";
                        $consulta=$obCon->Query($sql);
                        
                        $css->select("mipres_parentesco", "form-control", "mipres_parentesco", "", "", "", "");
                            while($datos_consulta=$obCon->FetchAssoc($consulta)){
                                
                                $css->option("", "", "", $datos_consulta["ID"], "", "",$sel);
                                    print($datos_consulta["parentesco"]);
                                $css->Coption();
                            }
                        $css->Cselect();
                    print("</td>");
                    $css->ColTabla("Nombre de quien recibe:", 1);
                    print("<td>");
                        $css->input("text", "mipres_nombre_recibe", "form-control", "mipres_nombre_recibe", "Nombre de Quien recibe", $datos_paciente["nombre_completo"], "Cantidad total entregada", "off", "", "");
                    print("</td>");
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    $css->ColTabla("Causa de No Entrega: ", 1);
                    print("<td>");
                        $sql="SELECT * FROM mipres_causas_no_entrega";
                        $consulta=$obCon->Query($sql);
                        
                        $css->select("mipres_causas_no_entrega", "form-control", "mipres_causas_no_entrega", "", "", "", "");
                            $css->option("", "", "", "", "", "",$sel);
                                print("Seleccione");
                            $css->Coption();
                            while($datos_consulta=$obCon->FetchAssoc($consulta)){
                                
                                $css->option("", "", "", $datos_consulta["ID"], "", "",$sel);
                                    print($datos_consulta["causa"]);
                                $css->Coption();
                            }
                        $css->Cselect();
                    print("</td>");
                    $css->ColTabla("Código del servicio a Entregar:", 1);
                    $css->ColTabla("<strong>".$datos_mipres["CodSerTecAEntregar"]."</strong>", 1,"R");
                $css->CierraFilaTabla();
                $css->FilaTabla(16);
                    $css->ColTabla(" ", 3);
                    
                    print("<td>");
                        $css->CrearBotonEvento("btn_entregar_mipres", "Entregar", 1, "onclick", "confirma_entregar_mipres(`$mipres_id`)", "rojo");
                    print("</td>");
                $css->CierraFilaTabla();
            $css->CerrarTabla();
        break;//fin caso 2
        
        
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>