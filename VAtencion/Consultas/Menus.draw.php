<?php
session_start();
if (!isset($_SESSION['username'])){
  exit("<a href='../index.php' ><img src='../images/401.png'>Iniciar Sesion </a>");
  
}
$idUser=$_SESSION['idUser'];

include_once("../../modelo/php_conexion.php");
include_once("../css_construct.php");


if( !empty($_REQUEST["idTipo"]) ){
    $css =  new CssIni("id",0);
    $obCon = new conexion($idUser);
    $idTipo=$obCon->normalizar($_REQUEST["idTipo"]);
    $DatosTipoUser=$obCon->DevuelveValores("usuarios_tipo", "ID", $idTipo);
    $MostrarSubmenus=0;
    if(isset($_REQUEST["idMenu"])){
        $idMenuRecibido=$obCon->normalizar($_REQUEST["idMenu"]);
        if($idMenuRecibido==0){
            $Menu="Menu.php";
            $NombreMenu="";
        }else{
            $DatosMenu=$obCon->DevuelveValores("menu", "ID", $idMenuRecibido);
            $Menu=$DatosMenu["Pagina"];
            $NombreMenu=$DatosMenu["Nombre"];
        }


        $sql="SELECT Pagina,Habilitado FROM paginas_bloques WHERE Pagina='$Menu' AND TipoUsuario='$DatosTipoUser[Tipo]' LIMIT 1";
        $ConsultaTipo=$obCon->Query($sql);
        $Resultados=$obCon->FetchAssoc($ConsultaTipo);
        $EstadoMenu=0;
        if($Resultados["Habilitado"]=='SI'){
            $EstadoMenu=0;
            $obCon->update("paginas_bloques", "Habilitado", "NO", "WHERE Pagina='$Menu' AND TipoUsuario='$DatosTipoUser[Tipo]'");
        }
        if($Resultados["Habilitado"]<>'SI'){
            $EstadoMenu=1;
            $obCon->update("paginas_bloques", "Habilitado", "SI", "WHERE Pagina='$Menu' AND TipoUsuario='$DatosTipoUser[Tipo]'");
        }
        if($Resultados["Habilitado"]==''){
            $DatosInsert["TipoUsuario"]=$DatosTipoUser["Tipo"];
            $DatosInsert["Pagina"]=$Menu;
            $DatosInsert["Habilitado"]="SI";
            $sql=$obCon->getSQLInsert("paginas_bloques", $DatosInsert);
            $obCon->Query($sql);
            $EstadoMenu=1;
        }
        $MostrarSubmenus=1;
    }
    
    if(isset($_REQUEST["idSubMenu"])){
        $idMenuRecibido=$obCon->normalizar($_REQUEST["idMenuRecibido"]);
        $idSubmenuRecibido=$obCon->normalizar($_REQUEST["idSubMenu"]);
        if($idMenuRecibido==0){
            $Menu="Menu.php";
            $NombreMenu="Menú";
        }else{
            $DatosMenu=$obCon->DevuelveValores("menu", "ID", $idMenuRecibido);
            $Menu=$DatosMenu["Pagina"];
            $NombreMenu=$DatosMenu["Nombre"];
        }

        $DatosSubMenu=$obCon->DevuelveValores("menu_submenus", "ID", $idSubmenuRecibido);
        $SubMenu=$DatosSubMenu["Pagina"];
        $NombreSubMenu=$DatosSubMenu["Nombre"];
        
        $sql="SELECT Pagina,Habilitado FROM paginas_bloques WHERE Pagina='$SubMenu' AND TipoUsuario='$DatosTipoUser[Tipo]' LIMIT 1";
        $ConsultaTipo=$obCon->Query($sql);
        $Resultados=$obCon->FetchAssoc($ConsultaTipo);
        $EstadoMenu=0;
        if($Resultados["Habilitado"]=='SI'){
            $EstadoMenu=1;
            $obCon->update("paginas_bloques", "Habilitado", "NO", "WHERE Pagina='$SubMenu' AND TipoUsuario='$DatosTipoUser[Tipo]'");
        }
        if($Resultados["Habilitado"]<>'SI'){
            $EstadoMenu=1;
            $obCon->update("paginas_bloques", "Habilitado", "SI", "WHERE Pagina='$SubMenu' AND TipoUsuario='$DatosTipoUser[Tipo]'");
        }
        if($Resultados["Habilitado"]==''){
            $DatosInsert["TipoUsuario"]=$DatosTipoUser["Tipo"];
            $DatosInsert["Pagina"]=$SubMenu;
            $DatosInsert["Habilitado"]="SI";
            $sql=$obCon->getSQLInsert("paginas_bloques", $DatosInsert);
            
            $obCon->Query($sql);
            $EstadoMenu=1;
        }
        $MostrarSubmenus=1;
    }
    
    print('<div id="DivMenus" style="position:absolute; left: 20%; top: 20%; width=45%">');
    //$css->CrearDiv("DivMenus", "", "left", 1, 1);
    //$css->DivGrid("DivMenus", "", "left", 1, 1, 1, 120, 40,5,"transparent");
    
    
    $Consulta=$obCon->ConsultarTabla("menu", "WHERE Estado=1");
    if($obCon->NumRows($Consulta)){
        $css->CrearNotificacionVerde("Menús del sistema", 16);
        $css->CrearTabla();
            $css->FilaTabla(16);
                $css->ColTabla("<strong>Menu</strong>", 1);
                $css->ColTabla("<strong>Habilitar</strong>", 1);
            $css->CierraFilaTabla();
            
            $Pagina="Menu.php";
            $sql="SELECT Pagina,Habilitado FROM paginas_bloques WHERE Pagina='$Pagina' AND TipoUsuario='$DatosTipoUser[Tipo]' LIMIT 1";
            $ConsultaTipo=$obCon->Query($sql);
            $Resultados=$obCon->FetchAssoc($ConsultaTipo);
            $Estado=0;
            if($Resultados["Habilitado"]=='SI'){
                $Estado=1;
            }
            $css->FilaTabla(16);
                $css->ColTabla(("Menú"), 1);
                print("<td>");
                    $Page="Consultas/Menus.draw.php?idMenu=0&idTipo=";
                    
                    $FuncionJS="onClick=EnvieObjetoConsulta(`$Page`,`CmbTipoUser`,`DivMenusConfig`,`2`); return false;";                    
                    $css->CheckOnOff("ChkMnu_0", $FuncionJS, $Estado, "");
                print("</td>");
            $css->CierraFilaTabla();
                
            while($Menus=$obCon->FetchAssoc($Consulta)){
                $Pagina=$Menus["Pagina"];
                $sql="SELECT Pagina,Habilitado FROM paginas_bloques WHERE Pagina='$Pagina' AND TipoUsuario='$DatosTipoUser[Tipo]' LIMIT 1";
                $ConsultaTipo=$obCon->Query($sql);
                $Resultados=$obCon->FetchAssoc($ConsultaTipo);
                $Estado=0;
                if($Resultados["Habilitado"]=='SI'){
                    $Estado=1;
                }
                $css->FilaTabla(16);
                    $css->ColTabla(utf8_encode($Menus["Nombre"]), 1);
                    print("<td>");
                        $idMenu=$Menus["ID"];
                        $Page="Consultas/Menus.draw.php?idMenu=$idMenu&idTipo=";
                        $JavaScript="onClick=EnvieObjetoConsulta(`$Page`,`CmbTipoUser`,`DivMenusConfig`,`2`);return false;";
                    
                        $css->CheckOnOff("ChkMnu_".$Menus["ID"], $JavaScript, $Estado, "");
                    print("</td>");
                $css->CierraFilaTabla();
            }
        $css->CerrarTabla();
    }
    
    $css->CerrarDiv();
    print('<div id="DivSubmenus" style="position:absolute; left: 50%; top: 130px; width=100%">');
    //$css->DivGrid("DivSubmenus", "", "center", 1, 1, 3, 90, 50,5,"transparent");
        if($MostrarSubmenus==1){
            
            $css->CrearNotificacionAzul("Submenus asociados al Menú ". utf8_encode($NombreMenu), 16);
            if($EstadoMenu==1){
                $sql="SELECT s.Nombre,s.ID,s.Pagina FROM menu_submenus s INNER JOIN menu_pestanas p ON "
                        . "p.ID=s.idPestana WHERE p.idMenu='$idMenuRecibido'";
                $Consulta=$obCon->Query($sql);
                if($obCon->NumRows($Consulta)){
                
                    $css->CrearTabla();
                        $css->FilaTabla(14);
                            $css->ColTabla("<strong>Nombre del Submenu</strong>", 1);
                            $css->ColTabla("<strong>Habilitar</strong>", 1);
                        $css->CierraFilaTabla();
                    
                    while ($DatosSubmenu=$obCon->FetchAssoc($Consulta)){
                        $Pagina=$DatosSubmenu["Pagina"];
                        $sql="SELECT Pagina,Habilitado FROM paginas_bloques WHERE Pagina='$Pagina' AND TipoUsuario='$DatosTipoUser[Tipo]' LIMIT 1";
                        $ConsultaTipo=$obCon->Query($sql);
                        $Resultados=$obCon->FetchAssoc($ConsultaTipo);
                        $Estado=0;
                        if($Resultados["Habilitado"]=='SI'){
                            $Estado=1;
                        }
                        $css->FilaTabla(14);
                            $css->ColTabla($DatosSubmenu["Nombre"], 1);
                            print("<td>");
                                $idSubMenu=$DatosSubmenu["ID"];
                                $Page="Consultas/Menus.draw.php?idMenuRecibido=$idMenuRecibido&idSubMenu=$idSubMenu&idTipo=";
                                $JavaScript="onClick=EnvieObjetoConsulta(`$Page`,`CmbTipoUser`,`DivMenusConfig`,`2`);return false;";

                                $css->CheckOnOff("ChkSubMnu_".$Menus["ID"], $JavaScript, $Estado, "");
                            print("</td>");
                        $css->CierraFilaTabla();
                    }
                    $css->CerrarTabla();
                }
            }
            
        }
    $css->CerrarDiv();
    
}

?>