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
            $Limit=5;
            $Page=$obCon->normalizar($_REQUEST["Page"]);
            $NumPage=$obCon->normalizar($_REQUEST["Page"]);
            if($Page==''){
                $Page=1;
                $NumPage=1;
            }
            $Busquedas=$obCon->normalizar($_REQUEST["Busquedas"]);
                        
            $FechaInicialRangos=$obCon->normalizar($_REQUEST["FechaInicialRangos"]);
            $FechaFinalRangos=$obCon->normalizar($_REQUEST["FechaFinalRangos"]);
            
           
            $Condicion=" WHERE ID>0 ";
            
            if($Busquedas<>''){
                $Condicion.=" AND ( t1.NoPrescripcion = '$Busquedas' or t1.NoIDPaciente = '$Busquedas' )";
            }
            if($FechaInicialRangos<>''){
                $Condicion.=" AND t1.FecProgramacion>='$FechaInicialRangos 00:00:00'";
            }
            if($FechaFinalRangos<>''){
                $Condicion.=" AND t1.FecProgramacion<='$FechaFinalRangos 23:59:59'";
            }
                        
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(*) as Items 
                   FROM $tabla t1  $Condicion;";
            
            $Consulta=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta);
            $ResultadosTotales = $totales['Items'];
               
            $colsQuery="ID,IDDireccionamiento,NoPrescripcion,TipoTec,ConTec,TipoIDPaciente,NoIDPaciente,NoEntrega,NoSubEntrega,FecMaxEnt,TipoIDProv,NoIDProv,CodMunEnt,CantTotAEntregar,DirPaciente,CodSerTecAEntregar,NoIDEPS,CodEPS,FecDireccionamiento,EstDireccionamiento,FecAnulacion,user_id ";
            
            $sql="SELECT $colsQuery                  
                  FROM $tabla t1 $Condicion ";
            $statement=$sql;
            
            $limit_condition=" ORDER BY EstDireccionamiento ASC,ID DESC LIMIT $PuntoInicio,$Limit;";
            
            $sql.=$limit_condition;
            
            $Consulta=$obCon->Query($sql);
            
            
            $css->CrearTitulo("MiPres", "verde");
            
            
            $statement= base64_encode(urlencode($statement));
            $colsQuery= base64_encode(urlencode($colsQuery));
                    
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
                                if(isset($_REQUEST["FechaInicialMiPres"])){
                                    $FechaInicialMiPres=$obCon->normalizar($_REQUEST["FechaInicialMiPres"]);
                                }
                                
                                
                                print("<strong>Fecha Inicial</strong>");
                                $css->input("date", "FechaInicialMiPres", "form-control", "FechaInicialMiPres", "Fecha", $FechaInicialMiPres, "Fecha Inicial", "off", "", "","style='line-height: 15px;'");
                                
                            //$css->Cdiv();
                        $css->Cdiv();
                        $css->CrearDiv("", "col-md-2", "left", 1, 1); 
                            //$css->div("", "pull-left", "", "", "", "style=text-align:center", "");
                                
                                
                                $FechaFinallMiPres=date("Y-m-d");
                                if(isset($_REQUEST["FechaFinallMiPres"])){
                                    $FechaFinallMiPres=$obCon->normalizar($_REQUEST["FechaFinallMiPres"]);
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
                        print("<span id='sp_msg_mipres'><strong></strong></span>");
                        print('<div class="progress">
                                <div id="PgProgresoUp" name="PgProgresoUp" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                  <div id="LyProgresoCMG" name="LyProgresoCMG" "="">0%</div>
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
                                print("<th>Accion 1</th>");
                                print("<th>Accion 2</th>");
                                print("<th>ID</th>");
                                print("<th>IDDireccionamiento</th>");
                                print("<th>NoPrescripcion</th>");
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
                                
                                print("<th>FecDireccionamiento</th>");
                                print("<th>EstDireccionamiento</th>");
                                print("<th>FecAnulacion</th>");
                                print("<th>IDUsuario</th>");
                                    
                            print('</tr>');
                            while($RegistrosTabla=$obCon->FetchAssoc($Consulta)){
                                
                                $idItem=$RegistrosTabla["ID"];

                                print('<tr>');
                                print("<td style='text-align:center'>");
                                    $link="procesadores/facturador.process.php?Accion=8&empresa_id=$empresa_id&documento_electronico_id=$idItem";
                                    print('<a style="font-size:25px;text-align:center" title="Ver PDF" href="'.$link.'" target="_blank")" ><i class="fa fa-file-pdf-o text-danger"></i></a>');

                                print("</td>");
                                print("<td style='text-align:center'>");
                                    $link="procesadores/facturador.process.php?Accion=9&empresa_id=$empresa_id&documento_electronico_id=$idItem";
                                    print('<a style="font-size:25px;text-align:center" title="Ver ZIP" href="'.$link.'" target="_blank" ><i class="fa fa-file-archive-o text-primary"></i></a>');

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
                                
                                print("<td class='mailbox-subject text-success'>");
                                    print("<strong>".($RegistrosTabla["FecDireccionamiento"])."</strong>");
                                print("</td>");
                                
                                print("<td class='mailbox-subject text-primary'>");
                                    print("<strong>".($RegistrosTabla["EstDireccionamiento"])."</strong>");
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
        
        
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>