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
       
        case 1://dibuja el listado del direccionamiento mipres
                        
            $empresa_id=1;
            $tabla="mipres_direccionamiento";
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
            $colsSubQuery=",(SELECT t2.estado_direccionamiento FROM mipres_estados_direccionamiento t2 WHERE t2.ID=t1.EstDireccionamiento LIMIT 1) as nombre_estado ";
           
            $sql="SELECT $colsQuery  $colsSubQuery              
                  FROM $tabla t1 $Condicion ";
            $statement=$sql;
            
            $limit_condition=" ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            
            $sql.=$limit_condition;
            
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("MiPres Direccionamiento", "verde");
            
            
            $statement= base64_encode(urlencode($statement));
            $colsQuery= base64_encode(urlencode($colsQuery.",nombre_estado"));
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    $CondicionURL= urlencode($Condicion);
                    $link='../../general/procesadores/ExportarExcel.process.php?Accion=1&empresa_id='.$empresa_id.'&tb='.$tabla.'&st='.$statement.'&cols='.$colsQuery;
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
                            print("<strong>Consultar Direccionamiento MiPres</strong>");
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
                                //print("<th>Anular</th>");                               
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
                                $direccionamiento_id=$RegistrosTabla["IDDireccionamiento"];
                                print('<tr>');
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstDireccionamiento"]==1){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-success fa fa-share" title="Direccionar Autorización" onclick="confirma_programar_mipres(`'.$idItem.'`)" target="_blank" ></button>');

                                print("</td>");
                                
                                /*
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstDireccionamiento"]==2){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-danger  fa fa-remove" title="Anular Direccionamiento" onclick="confirma_anular_direccionamiento_mipres(`'.$direccionamiento_id.'`)" target="_blank" ></button>');

                                print("</td>");
                                        
                                 * 
                                 */                           

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
                                    
                                    print("<strong>".$RegistrosTabla["nombre_estado"]."</strong>");
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
            $tabla="mipres_direccionamiento";
            
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
        
        case 3://dibuja el listado de la programacion mipres
                        
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
                $Condicion.=" AND t1.FecProgramacion>='$FechaInicialRangos 00:00:00'";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND t1.FecProgramacion<='$FechaFinalRangos 23:59:59'";
            }
            if($cmb_estado_mipres<>''){
                $Condicion.=" AND t1.EstProgramacion='$cmb_estado_mipres'";
            }
                        
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items 
                   FROM $tabla t1  $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
               
            $colsQuery="ID,IDProgramacion,NoPrescripcion,TipoTec,ConTec,TipoIDPaciente,NoIDPaciente,NoEntrega,FecMaxEnt,TipoIDSedeProv,NoIDSedeProv,CodSedeProv,CodSerTecAEntregar,CantTotAEntregar,FecProgramacion,EstProgramacion,FecAnulacion,user_id,created ";
            $colsSubQuery=",(SELECT t2.estado_programacion FROM mipres_estados_programacion t2 WHERE t2.ID=t1.EstProgramacion LIMIT 1) as nombre_estado ";
           
            $sql="SELECT $colsQuery  $colsSubQuery              
                  FROM $tabla t1 $Condicion ";
            $statement=$sql;
            
            $limit_condition=" ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            
            $sql.=$limit_condition;
            
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("MiPres Programación", "naranja");
            
            
            $statement= base64_encode(urlencode($statement));
            $colsQuery= base64_encode(urlencode($colsQuery.",nombre_estado"));
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    $CondicionURL= urlencode($Condicion);
                    $link='../../general/procesadores/ExportarExcel.process.php?Accion=1&empresa_id='.$empresa_id.'&tb='.$tabla.'&st='.$statement.'&cols='.$colsQuery;
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
                            print("<strong>Consultar Programación MiPres</strong>");
                            $css->CrearBotonEvento("btn_obtener_direccionamiento_mipres", "Obtener", 1, "onclick", "iniciar_consulta_programacion_mipres()", "naranja");
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
                                print('<span class="input-group-addon" onclick=CambiePagina(`12`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                                }
                                $FuncionJS="onchange=CambiePagina(`12`);";
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
                                print('<span class="input-group-addon" onclick=CambiePagina(`12`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
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
                                print("<th>Entregar</th>");                                
                                print("<th>Anular Programación</th>");                               
                                print("<th>ID</th>");
                                print("<th>IDProgramacion</th>");
                                print("<th>NoPrescripcion</th>");                                
                                print("<th>FecProgramacion</th>");
                                print("<th>EstProgramacion</th>");                                
                                print("<th>TipoTec</th>");
                                print("<th>ConTec</th>");
                                print("<th>TipoIDPaciente</th>");
                                print("<th>NoIDPaciente</th>");                                
                                print("<th>NoEntrega</th>");
                                                        
                                print("<th>FecMaxEnt</th>");  
                                print("<th>TipoIDSedeProv</th>");                                
                                print("<th>NoIDSedeProv</th>");
                                print("<th>CodSedeProv</th>");
                                
                                print("<th>CodSerTecAEntregar</th>");
                                print("<th>CantTotAEntregar</th>");
                                
                                print("<th>FecAnulacion</th>");
                                print("<th>IDUsuario</th>");
                                    
                            print('</tr>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                $programacion_id=$RegistrosTabla["IDProgramacion"];
                                print('<tr>');
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstProgramacion"]==1){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-primary fa fa-send" title="Entregar" onclick="frm_entregar_mipres(`'.$idItem.'`)" target="_blank" ></button>');

                                print("</td>");
                                
                                
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstProgramacion"]==1){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-danger  fa fa-remove" title="Anular Programacion" onclick="confirma_anular_programacion_mipres(`'.$programacion_id.'`)" target="_blank" ></button>');

                                print("</td>");
                                        
                                                          

                                print("<td class='mailbox-name'>");
                                    print($RegistrosTabla["ID"]);
                                print("</td>");
                                print("<td class='mailbox-subject text-primary'>");
                                    print("<strong>".$RegistrosTabla["IDProgramacion"]."</strong>");
                                print("</td>");
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".$RegistrosTabla["NoPrescripcion"]."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-success'>");
                                    print("<strong>".($RegistrosTabla["FecProgramacion"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-primary'>");
                                    
                                    print("<strong>".$RegistrosTabla["nombre_estado"]."</strong>");
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
                                    print("<strong>".$RegistrosTabla["FecMaxEnt"]."</strong>");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["TipoIDSedeProv"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoIDSedeProv"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CodSedeProv"]."");
                                print("</td>");   
                                                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CodSerTecAEntregar"]."");
                                print("</td>"); 
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".number_format($RegistrosTabla["CantTotAEntregar"])."</strong>");
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
        break;//fin caso 3
        
        case 4://dibuja el listado de las entregas mipres
                        
            $empresa_id=1;
            $tabla="mipres_entrega";
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
                $Condicion.=" AND t1.FecEntrega>='$FechaInicialRangos 00:00:00'";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND t1.FecEntrega<='$FechaFinalRangos 23:59:59'";
            }
            if($cmb_estado_mipres<>''){
                $Condicion.=" AND t1.EstEntrega='$cmb_estado_mipres'";
            }
                        
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items 
                   FROM $tabla t1  $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
               
            $colsQuery="ID,IDEntrega,NoPrescripcion,TipoTec,ConTec,TipoIDPaciente,NoIDPaciente,NoEntrega,CodSerTecEntregado,CantTotEntregada,EntTotal,CausaNoEntrega,FecEntrega,NoLote,TipoIDRecibe,NoIDRecibe,EstEntrega,FecAnulacion,CodigosEntrega,user_id,created ";
            $colsSubQuery=",(SELECT t2.estado_entrega FROM mipres_estados_entrega t2 WHERE t2.ID=t1.EstEntrega LIMIT 1) as nombre_estado ";
            $colsSubQuery.=",(SELECT t3.causa FROM mipres_causas_no_entrega t3 WHERE t3.ID=t1.CausaNoEntrega LIMIT 1) as nombre_causa_no_entrega ";
           
            $sql="SELECT $colsQuery  $colsSubQuery              
                  FROM $tabla t1 $Condicion ";
            $statement=$sql;
            
            $limit_condition=" ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            
            $sql.=$limit_condition;
            
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("MiPres Entrega", "rojo");
            
            
            $statement= base64_encode(urlencode($statement));
            $colsQuery= base64_encode(urlencode($colsQuery.",nombre_estado"));
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    $CondicionURL= urlencode($Condicion);
                    $link='../../general/procesadores/ExportarExcel.process.php?Accion=1&empresa_id='.$empresa_id.'&tb='.$tabla.'&st='.$statement.'&cols='.$colsQuery;
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
                            print("<strong>Consultar Entrega MiPres</strong>");
                            $css->CrearBotonEvento("btn_obtener_direccionamiento_mipres", "Obtener", 1, "onclick", "iniciar_consulta_entrega_mipres()", "naranja");
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
                                print('<span class="input-group-addon" onclick=CambiePagina(`13`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                                }
                                $FuncionJS="onchange=CambiePagina(`13`);";
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
                                print('<span class="input-group-addon" onclick=CambiePagina(`13`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
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
                                print("<th>Reportar Entrega</th>");                  
                                print("<th>Anular Entrega</th>");                               
                                print("<th>ID</th>");
                                print("<th>IDEntrega</th>");
                                print("<th>NoPrescripcion</th>");                                
                                print("<th>FecEntrega</th>");
                                print("<th>EstEntrega</th>"); 
                                print("<th>TipoIDPaciente</th>");       
                                print("<th>NoIDPaciente</th>");   
                                print("<th>CodSerTecEntregado</th>");
                                print("<th>CantTotEntregada</th>");
                                print("<th>EntTotal</th>");
                                                             
                                print("<th>NoEntrega</th>");
                                print("<th>CausaNoEntrega</th>");  
                                print("<th>NoLote</th>");                                
                                print("<th>TipoIDRecibe</th>");
                                print("<th>NoIDRecibe</th>");
                                print("<th>CodigosEntrega</th>");
                                
                                print("<th>FecAnulacion</th>");
                                print("<th>IDUsuario</th>");
                                    
                            print('</tr>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                $entrega_id=$RegistrosTabla["IDEntrega"];
                                print('<tr>');
                                
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstEntrega"]==1){
                                        $disabled="";
                                    }
                                    print('<button '.$disabled.'  class="btn btn-primary fa fa-send" title="Reportar Entrega" onclick="frm_reporte_entrega_mipres(`'.$idItem.'`)" target="_blank" ></button>');

                                print("</td>");
                                
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstEntrega"]==1){
                                        $disabled="";
                                    }
                                     print('<button '.$disabled.'  class="btn btn-danger fa fa-times-circle" title="Anular Entrega" onclick="confirma_anular_entrega_mipres(`'.$entrega_id.'`)" target="_blank" ></button>');
                                print("</td>");
                                
                                
                                
                                print("<td class='mailbox-name'>");
                                    print($RegistrosTabla["ID"]);
                                print("</td>");
                                print("<td class='mailbox-subject text-primary'>");
                                    print("<strong>".$RegistrosTabla["IDEntrega"]."</strong>");
                                print("</td>");
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".$RegistrosTabla["NoPrescripcion"]."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-success'>");
                                    print("<strong>".($RegistrosTabla["FecEntrega"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-primary'>");
                                    
                                    print("<strong>".$RegistrosTabla["nombre_estado"]."</strong>");
                                print("</td>");
                                print("<td class='mailbox-subject'>");
                                    print("<strong>".$RegistrosTabla["TipoIDPaciente"]."</strong>");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoIDPaciente"]."");
                                print("</td>");  
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CodSerTecEntregado"]."");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CantTotEntregada"]."");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["EntTotal"]."");
                                print("</td>");   
                                
                                
                                print("<td class='mailbox-subject'>");
                                    print("<strong>".$RegistrosTabla["NoEntrega"]."</strong>");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["nombre_causa_no_entrega"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoLote"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["TipoIDRecibe"]."");
                                print("</td>");   
                                                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoIDRecibe"]."");
                                print("</td>"); 
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".($RegistrosTabla["CodigosEntrega"])."</strong>");
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
        break;//fin caso 4
        
        case 5://dibuja los filtros de los estados del mipres 
                        
            $empresa_id=1;
            if($_REQUEST["tabla_id"]==1){
                $tabla="mipres_estados_direccionamiento";
                $nombre_columna_mostrar="estado_direccionamiento";
            }
            if($_REQUEST["tabla_id"]==2){
                $tabla="mipres_estados_programacion";
                $nombre_columna_mostrar="estado_programacion";
            }
            if($_REQUEST["tabla_id"]==3){
                $tabla="mipres_estados_entrega";
                $nombre_columna_mostrar="estado_entrega";
            }
            if($_REQUEST["tabla_id"]==4){
                $tabla="mipres_estados_reporte_entrega";
                $nombre_columna_mostrar="estado_reporte_entrega";
                if($_REQUEST["defecto"]==2){
                    $_REQUEST["defecto"]=1;
                }
            }
            
            $sql="SELECT * FROM $tabla";
            
            $Consulta=$obCon->Query($sql);
            
            $css->select("cmb_estado_mipres", "form-control", "cmb_estado_mipres", "Estado:", "", 'onchange="MostrarListadoSegunID()" ', "");
            
                $css->option("", "", "", "", "", "");                    
                    print("Todos");
                $css->Coption();
                
            
            while($datos_consulta=$obCon->FetchAssoc($Consulta)){
                $sel=0;
                if($_REQUEST["defecto"]==$datos_consulta["ID"]){
                    $sel=1;
                }
                
                $css->option("", "", "", $datos_consulta["ID"], "", "",$sel);                    
                    print($datos_consulta[$nombre_columna_mostrar]);
                $css->Coption();
            }
            $css->Cselect();
            
        break;//fin caso 5
        
        case 6://dibuja el listado de los reporte de entrega mipres
                        
            $empresa_id=1;
            $tabla="mipres_reporte_entrega";
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
                $Condicion.=" AND t1.FecRepEntrega>='$FechaInicialRangos 00:00:00'";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND t1.FecRepEntrega<='$FechaFinalRangos 23:59:59'";
            }
            if($cmb_estado_mipres<>''){
                $Condicion.=" AND t1.EstRepEntrega='$cmb_estado_mipres'";
            }
                        
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items 
                   FROM $tabla t1  $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
               
            $colsQuery="ID,IDReporteEntrega,NoPrescripcion,TipoTec,ConTec,TipoIDPaciente,NoIDPaciente,NoEntrega,EstadoEntrega,CausaNoEntrega,ValorEntregado,CodTecEntregado,CantTotEntregada,NoLote,FecEntrega,FecRepEntrega,EstRepEntrega,FecAnulacion,user_id,created ";
            $colsSubQuery=",(SELECT t2.estado_reporte_entrega FROM mipres_estados_reporte_entrega t2 WHERE t2.ID=t1.EstRepEntrega LIMIT 1) as nombre_estado ";
            $colsSubQuery.=",(SELECT t3.causa FROM mipres_causas_no_entrega t3 WHERE t3.ID=t1.CausaNoEntrega LIMIT 1) as nombre_causa_no_entrega ";
           
            $sql="SELECT $colsQuery  $colsSubQuery              
                  FROM $tabla t1 $Condicion ";
            $statement=$sql;
            
            $limit_condition=" ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            
            $sql.=$limit_condition;
            
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("MiPres Reporte Entrega", "azul");
            
            
            $statement= base64_encode(urlencode($statement));
            $colsQuery= base64_encode(urlencode($colsQuery.",nombre_estado,nombre_causa_no_entrega"));
                    
            $css->div("", "box-body no-padding", "", "", "", "", "");
                $css->div("", "mailbox-controls", "", "", "", "", "");
                    $CondicionURL= urlencode($Condicion);
                    $link='../../general/procesadores/ExportarExcel.process.php?Accion=1&empresa_id='.$empresa_id.'&tb='.$tabla.'&st='.$statement.'&cols='.$colsQuery;
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
                            print("<strong>Consultar Reporte de entregas</strong>");
                            $css->CrearBotonEvento("btn_obtener_direccionamiento_mipres", "Obtener", 1, "onclick", "iniciar_consulta_reporte_entrega_mipres()", "naranja");
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
                                print('<span class="input-group-addon" onclick=CambiePagina(`14`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-left"></i></span>');
                                }
                                $FuncionJS="onchange=CambiePagina(`14`);";
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
                                print('<span class="input-group-addon" onclick=CambiePagina(`14`,`'.$NumPage1.'`) style=cursor:pointer><i class="fa fa-chevron-right" ></i></span>');
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
                                print("<th>Ver Reporte De Entrega</th>");                           
                                print("<th>Anular Reporte Entrega</th>");                               
                                print("<th>ID</th>");
                                print("<th>IDReporteEntrega</th>");
                                print("<th>NoPrescripcion</th>");                                
                                print("<th>FecRepEntrega</th>");
                                print("<th>EstRepEntrega</th>"); 
                                print("<th>TipoIDPaciente</th>");       
                                print("<th>NoIDPaciente</th>");   
                                                                                           
                                print("<th>NoEntrega</th>");
                                print("<th>CausaNoEntrega</th>"); 
                                print("<th>ValorEntregado</th>");     
                                print("<th>CodTecEntregado</th>");     
                                print("<th>CantTotEntregada</th>");
                                
                                print("<th>NoLote</th>");                                
                                print("<th>FecEntrega</th>");
                                
                                print("<th>EstadoEntrega</th>");                                
                                print("<th>FecAnulacion</th>");
                                print("<th>IDUsuario</th>");
                                    
                            print('</tr>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];
                                $reporte_entrega_id=$RegistrosTabla["IDReporteEntrega"];
                                print('<tr>');
                                
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    $disabled="";
                                    if($RegistrosTabla["EstRepEntrega"]<>0){
                                       $disabled="";
                                    }
                                    $link="procesadores/reportes_excel.process.php?Accion=1&empresa_id=$empresa_id&mipres_id=$idItem";
                                     print('<a href="'.$link.'" '.$disabled.'  class="btn btn-success fa fa-file-excel-o" title="Generar Reporte Entrega" target="_blank" ></a>');
                                print("</td>");
                                
                                print("<td style='text-align:center'>");
                                    $disabled="disabled=1";
                                    if($RegistrosTabla["EstRepEntrega"]<>0){
                                        $disabled="";
                                    }
                                     print('<button '.$disabled.'  class="btn btn-danger fa fa-times-circle" title="Anular Reporte Entrega" onclick="confirma_anular_reporte_entrega_mipres(`'.$reporte_entrega_id.'`)" target="_blank" ></button>');
                                print("</td>");
                                        
                                                          

                                print("<td class='mailbox-name'>");
                                    print($RegistrosTabla["ID"]);
                                print("</td>");
                                print("<td class='mailbox-subject text-primary'>");
                                    print("<strong>".$RegistrosTabla["IDReporteEntrega"]."</strong>");
                                print("</td>");
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".$RegistrosTabla["NoPrescripcion"]."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-success'>");
                                    print("<strong>".($RegistrosTabla["FecRepEntrega"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-primary'>");
                                    
                                    print("<strong>".$RegistrosTabla["nombre_estado"]."</strong>");
                                print("</td>");
                                print("<td class='mailbox-subject'>");
                                    print("<strong>".$RegistrosTabla["TipoIDPaciente"]."</strong>");
                                print("</td>");   
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoIDPaciente"]."");
                                print("</td>");  
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["NoEntrega"]."");
                                print("</td>");   
                                                                                             
                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["nombre_causa_no_entrega"]."");
                                print("</td>");   
                                
                                print("<td class='mailbox-subject'>");
                                    print("".number_format($RegistrosTabla["ValorEntregado"])."");
                                print("</td>");  
                                
                                                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CodTecEntregado"]."");
                                print("</td>");   
                                                                
                                print("<td class='mailbox-subject'>");
                                    print("".$RegistrosTabla["CantTotEntregada"]."");
                                print("</td>"); 
                                print("<td class='mailbox-subject text-flickr'>");
                                    print("<strong>".($RegistrosTabla["NoLote"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject'>");
                                    print("<strong>".($RegistrosTabla["FecEntrega"])."</strong>");
                                print("</td>");
                                print("<td class='mailbox-subject'>");
                                    print("<strong>".($RegistrosTabla["EstadoEntrega"])."</strong>");
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
        break;//fin caso 6
        
        case 7: //dibuja el formulario para el reporte de entrega un mipres
            $empresa_id=1;
            $tabla="mipres_direccionamiento";
            
            $mipres_id=$obCon->normalizar($_REQUEST["mipres_id"]);
            $datos_mipres=$obCon->DevuelveValores($tabla, "ID", $mipres_id);
            $datos_factura=$obCon->DevuelveValores("vista_facturas_basante", "idTraza", $mipres_id);
                        
            $tipo_doc_paciente=$datos_mipres["TipoIDPaciente"];
            $identificacion_paciente=$datos_mipres["NoIDPaciente"];
            $sql="SELECT ID,CONCAT(PrimerNombre,' ',SegundoNombre,' ',PrimerApellido,' ',SegundoApellido) as nombre_completo FROM prefactura_paciente WHERE TipoDocumento='$tipo_doc_paciente' AND NumeroDocumento='$identificacion_paciente' LIMIT 1";
            $datos_paciente=$obCon->FetchArray($obCon->Query($sql));
            
            $css->CrearTitulo("<strong>Reportar Entrega MiPres $mipres_id</strong>", "azul");
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
                    
                    $css->ColTabla("Valor Facturado:", 3,"R");
                    print("<td>");
                        $css->input("text", "mipres_valor_facturado", "form-control", "mipres_valor_facturado", "Valor Facturado", "", "Valor Facturado", "off", "", "");
                    print("</td>");
                $css->CierraFilaTabla();
                
                $css->FilaTabla(16);
                    $css->ColTabla(" ", 3);
                    
                    print("<td>");
                        $css->CrearBotonEvento("btn_entregar_mipres", "Reportar", 1, "onclick", "confirma_reporte_entrega_mipres(`$mipres_id`)", "rojo");
                    print("</td>");
                $css->CierraFilaTabla();
            $css->CerrarTabla();
        break;//fin caso 7
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>