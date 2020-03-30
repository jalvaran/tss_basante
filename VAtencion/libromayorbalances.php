<?php
$myPage="libromayorbalances.php";
include_once("../sesiones/php_control.php");
include_once("clases/ClasesInformesFinancieros.php");
////////// Paginacion
$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);

    	$limit = 10;
    	$startpoint = ($page * $limit) - $limit;
		
/////////


        
        
include_once ('funciones/function.php');  //En esta funcion está la paginacion

include_once("Configuraciones/libromayorbalances.ini.php");  //Clases de donde se escribirán las tablas
$obTabla = new Tabla($db);
$obCon=new conexion($idUser);

$statement = $obTabla->CreeFiltro($Vector);
//print($statement);
$Vector["statement"]=$statement;   //Filtro necesario para la paginacion


$obTabla->VerifiqueExport($Vector);

include_once("css_construct.php");
print("<html>");
print("<head>");

$css =  new CssIni($myTitulo);
print("</head>");
print("<body>");
//Cabecera
$css->CabeceraIni($myTitulo); //Inicia la cabecera de la pagina
$css->CabeceraFin(); 

///////////////Creamos el contenedor
    /////
    /////
$css->CrearDiv("principal", "container", "center",1,1);
if(isset($_REQUEST["BtnEjecutar"])){
    $FechaIni=$obCon->normalizar($_REQUEST["TxtFechaIni"]);
    $FechaFin=$obCon->normalizar($_REQUEST["TxtFechaFin"]);
    $sql="SELECT SUBSTRING(`CuentaPUC`,1,4) as Cuenta FROM `librodiario` WHERE Fecha<='$FechaFin' "
                . "GROUP BY SUBSTRING(`CuentaPUC`,1,4);";
    $consulta=$obCon->Query($sql);
    $sqlMayor="";
    $sqlMayor="INSERT INTO `libromayorbalances` (`FechaInicial`,`FechaFinal`,`CuentaPUC`,`NombreCuenta`,`SaldoAnterior`,`Debito`,`Credito`,`NuevoSaldo`)"
            . "  VALUES ";
    while ($DatosCuentaMayor=$obCon->FetchArray($consulta)){
        
        $Cuenta=$DatosCuentaMayor["Cuenta"];
        $DatosCuenta=$obCon->DevuelveValores("cuentas", "idPUC", $Cuenta);
        $sql="SELECT SUM(Debito) as Debitos, Sum(Credito) as Creditos FROM librodiario "
                . "WHERE CuentaPUC LIKE '$Cuenta%' AND Fecha>='$FechaIni' AND Fecha<='$FechaFin'";
        $Datos=$obCon->Query($sql);
        $Sumas=$obCon->FetchArray($Datos);
        $NombreCuenta=$DatosCuenta["Nombre"];
        $Debitos=$Sumas["Debitos"];
        $Creditos=$Sumas["Creditos"];
        $Diferencia=$Debitos-$Creditos;
        $SaldoAnterior=$obCon->Sume("librodiario", "Neto", "WHERE CuentaPUC LIKE '$Cuenta%' AND Fecha<'$FechaIni'");
        $NuevoSaldo=$Diferencia-$SaldoAnterior;
        $sqlMayor=$sqlMayor." ('$FechaIni','$FechaFin','$Cuenta','$NombreCuenta','$SaldoAnterior','$Debitos','$Creditos','$NuevoSaldo'),";
        
        
    }
    $sqlMayor=substr($sqlMayor, 0, -1);
    $obCon->VaciarTabla("libromayorbalances");
    $obCon->Query($sqlMayor);
    $sqlMayor="";
}
$css->CrearForm2("FrmMayor", $myPage, "post", "_self");
    $css->CrearTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>Armar Libro Mayor y Balances</strong>", 3);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            $css->ColTabla("<strong>Fecha Inicial</strong>", 1);
            $css->ColTabla("<strong>Fecha Final</strong>", 1);
            $css->ColTabla("<strong>Ejecutar</strong>", 1);
        $css->CierraFilaTabla();
        $css->FilaTabla(16);
            print("<td>");
                $css->CrearInputFecha("", "TxtFechaIni", date("Y-m-d"), 150, 30, "");
            print("</td>");
            print("<td>");
                $css->CrearInputFecha("", "TxtFechaFin", date("Y-m-d"), 150, 30, "");
            print("</td>");
            print("<td>");
                $css->CrearBotonNaranja("BtnEjecutar", "Ejecutar");
            print("</td>");
        $css->CierraFilaTabla();
    $css->CerrarTabla();
$css->CerrarForm();

///////////////Creamos la imagen representativa de la pagina
    /////
    /////	
$css->CrearImageLink("../VMenu/Menu.php", "../images/libromayor.png", "_self",200,200);


////Paginacion
////
$Ruta="";
print("<div style='height: 50px;'>");   //Dentro de un DIV para no hacerlo tan grande
print(pagination($Ruta,$statement,$limit,$page));
print("</div>");
////
///Dibujo la tabla
////
///
/*
 * Verifico que haya balance
 */

$obTabla->DibujeTabla($Vector);
$css->CerrarDiv();//Cerramos contenedor Principal
$css->Footer();
$css->AgregaJS(); //Agregamos javascripts
//$css->AgregaSubir();    
////Fin HTML  
print("</body></html>");


?>