function NoPermitirTeclas(e,idElement){
    
    console.log(e);
    var key = window.Event ? e.which : e.keyCode
    console.log(key)
    if(key){
        document.getElementById(idElement).value= '';
    }
        return ((key >= 48 && key <= 50))
        
    //onkeyup="return false";
}

function DeshabilitarTeclado(e,idElement){
    
    document.getElementById(idElement).onkeypress= NoPermitirTeclas(e,idElement);
    
}

function ValidaFechaDates(idElement){
    var FechaInicialPermitida =$('#'+idElement).attr("min");
    console.log(FechaInicialPermitida);
    
}

var idComprobanteC=0;

function EnviaFormSC() {

	document.FormMesa.submit();
		
}

function EnviaForm(idForm) {
	
	document.getElementById(idForm).submit();
		
}

function EnviaFormVentasRapidas() {
	var total;
	var paga;
	var devuelta;
	
	total =  parseInt(document.getElementById("TxtGranTotalH").value);
	efectivo =  parseInt(document.getElementById("TxtPaga").value);
        tarjeta =  parseInt(document.getElementById("TxtPagaTarjeta").value);
        cheque =  parseInt(document.getElementById("TxtPagaCheque").value);
        otros =  parseInt(document.getElementById("TxtPagaOtros").value);
        Anticipo =  parseInt(document.getElementById("TxtAnticipo").value);
        if(document.getElementById("TxtPaga").length <= 0 ){
            efectivo=0;
        }
        if(document.getElementById("TxtPagaTarjeta").length <= 0){
            tarjeta=0;
        }
        if(document.getElementById("TxtPagaCheque").length <= 0){
            cheque=0;
        }
        if(document.getElementById("TxtPagaOtros").length <= 0){
            otros=0;
        }
	TotalPago=efectivo+tarjeta+cheque+otros+Anticipo;
        if(TotalPago >= total){
            
            document.getElementById('FrmGuarda').submit();
        }else{
            alert("El dinero pagado no es superior al saldo, por favor digite un dato valido");
        }
		
}



function EnviaFormDepar() {

	document.FormDepar.submit();
		
}

function EnviaFormOrden() {

	document.FormOrden.submit();
		
}

function incrementa(id) {

	document.getElementById(id).value++;
	

}

function decrementa(id) {

if(document.getElementById(id).value > 1)
	document.getElementById(id).value--;

}
function cargar(){

$("#DivConsultas").load("consultas.php?Tipo=Cronometro");

}

function refresca(seg) {
    
    setInterval("cargar()",1000);
}


function cargarMesas(){

$("#contenidoMesas").load("contMesas.php");

}

function refrescaMesas(seg) {
	setTimeout("cargarMesas()",seg);
}

function posiciona(id){ 
   
   document.getElementById(id).focus();
   
}

function CalculeDevuelta() {

	var total;
	var paga;
	var devuelta;
	
	total =  parseInt(document.getElementById("TxtGranTotalH").value);
	efectivo =  parseInt(document.getElementById("TxtPaga").value);
        tarjeta =  parseInt(document.getElementById("TxtPagaTarjeta").value);
        cheque =  parseInt(document.getElementById("TxtPagaCheque").value);
        otros =  parseInt(document.getElementById("TxtPagaOtros").value);
        anticipos =  parseInt(document.getElementById("TxtAnticipo").value);
        
        if(document.getElementById("TxtPaga").length <= 0 ){
            efectivo=0;
        }
        if(document.getElementById("TxtPagaTarjeta").length <= 0){
            tarjeta=0;
        }
        if(document.getElementById("TxtPagaCheque").length <= 0){
            cheque=0;
        }
        if(document.getElementById("TxtPagaOtros").length <= 0){
            otros=0;
        }
	TotalPago=efectivo+tarjeta+cheque+otros+anticipos;
       
	devuelta = TotalPago - total;
	
	document.getElementById("TxtDevuelta").value=devuelta;

}

function atajos()
{


shortcut("Ctrl+Q",function()
{
//document.getElementById("TxtPaga").focus();
document.getElementById("TxtPaga").select();
});
shortcut("Ctrl+E",function()
{
document.getElementById("TxtCodigoBarras").focus();
});


shortcut("Ctrl+S",function()
{
document.getElementById("BtnGuardar").click();
});

shortcut("Ctrl+A",function()
{
document.getElementById("TxtCantidadBascula").focus();
});

}

function CreaRazonSocial() {

    campo1=document.getElementById('TxtPA').value;
    campo2=document.getElementById('TxtSA').value;
    campo3=document.getElementById('TxtPN').value;
    campo4=document.getElementById('TxtON').value;
	

    Razon=campo3+" "+campo4+" "+campo1+" "+campo2;

    document.getElementById('TxtRazonSocial').value=Razon;


}

function calculetotaldias() {
	
	var Subtotal=document.getElementById("TxtSubtotalH").value;
	var IVA=document.getElementById("TxtIVAH").value;
	var Total=document.getElementById("TxtTotalH").value;
	var Dias=document.getElementById("TxtDias").value;
	var Anticipo=document.getElementById("TxtAnticipo").value;
	
	Saldo=Total*Dias-Anticipo;
	document.getElementById("TxtSubtotal").value=Subtotal*Dias;
	document.getElementById("TxtIVA").value=IVA*Dias;
	document.getElementById("TxtTotal").value=Total*Dias;
	document.getElementById("TxtSaldo").value=Saldo;

}

// esta funcion no permite enviar un formulario con el enter
function DeshabilitaEnter(){
    
    if(event.keyCode == 13) event.returnValue = false;
}

// esta funcion permite confirmar el envio de un formulario
function Confirmar(){
	
    if (confirm('Desea continuar?')){ 
      this.form.submit();
    } 
}

// esta funcion permite confirmar el envio de un formulario
function ConfirmarFormPass(){
    alert("Desea continuar");
   
}

// esta funcion permite confirmar el envio de un formulario
function ConfirmarFormNegativo(id){
    valor=parseInt(document.getElementById(id).value);
    
    if(valor<0){
       alert("Esta accion requiere autorizacion");
        
        return false;
    }else{
        this.form.submit();
    }
    
}

function ConfirmarLink(id){
	
    if (confirm('¿Estas seguro que deseas realizar esta accion?')){ 
     
      document.location.href= document.getElementById(id).value;
    } 
}

// esta funcion permite mostrar u ocultar un elemento
function MuestraOculta(id){
    
    estado=document.getElementById(id).style.display;
    if(estado=="none" | estado==""){
        document.getElementById(id).style.display="block";
    }
    if(estado=="block"){
        document.getElementById(id).style.display="none";
    }
    
}

// esta funcion permite mostrar u ocultar un elemento
function Muestra(id){
        
    document.getElementById(id).style.display="block";
       
}

// esta funcion permite mostrar u ocultar un elemento
function Oculta(id){
        
    document.getElementById(id).style.display="none";
       
}


// esta funcion permite deshabilitar o habilitar un elemento
function Habilita(id,estado){
    
    document.getElementById(id).disabled=estado;
       
}

// esta funcion permite deshabilitar o habilitar un elemento
function HabilitaPrecio(id){
    
    pass = prompt("Para cambiar el precio introduzca su contraseña");
    
    if(pass=="1234"){
        document.getElementById(id).disabled=!document.getElementById(id).disabled;
    }
       
}

function CalculeTotal() {

	var Subtotal;
	var IVA;
	var Total;
	
	Subtotal = parseFloat(document.getElementById("TxtSubtotal").value);
	IVA = parseFloat(document.getElementById("TxtIVA").value);
	Total= parseFloat(Subtotal) + parseFloat(IVA);
	
	document.getElementById("TxtTotal").value=Total;

}

function CalculeTotalImpuestos() {

	var TxtSancion;
	var TxtIntereses;
	var TxtImpuesto;
	var Total;
	
	TxtSancion = parseInt(document.getElementById("TxtSancion").value);
	TxtIntereses = parseInt(document.getElementById("TxtIntereses").value);
	TxtImpuesto = parseInt(document.getElementById("TxtImpuesto").value);
	Total= parseInt(TxtSancion) + parseInt(TxtIntereses) + parseInt(TxtImpuesto);
	
	document.getElementById("TxtTotal").value=Total;

}

//Calcula el total en los egresos desde ventas rapidas

function CalculeTotalEgresosVR() {

	var Subtotal;
	var IVA;
	var Total;
	
	Subtotal = parseInt(document.getElementById("TxtSubtotalEgreso").value);
	IVA = parseInt(document.getElementById("TxtIVAEgreso").value);
	Total= parseInt(Subtotal) + parseInt(IVA);
	
	document.getElementById("TxtValorEgreso").value=Total;

}

// esta funcion permite cambiar un link
function CambiaLinkKit(idProducto,idLink,idCantidad,idkit,page){
    
    
    Cantidad=document.getElementById(idCantidad).value;
    
    Kit=document.getElementsByName(idkit)[0].value;
    
    link="procesadores/ProcesadorAgregaKits.php?Tabla=productosventa&IDProducto="+idProducto+"&TxtCantidad="+Cantidad+"&idKit="+Kit+"&Page="+page;
    
    document.getElementById(idLink).href=link;
    
}

function CambiaLinkAbono(idfecha,idLibro,idLink,idCantidad,idCuenta,page,procesador,TablaAbono){
    
    
        Cantidad=document.getElementById(idCantidad).value;
        Fecha2=document.getElementById(idfecha).value;
        Cuenta=document.getElementsByName(idCuenta)[0].value;

        link=procesador+"?TablaAbono="+TablaAbono+"&IDLibro="+idLibro+"&TxtCantidad="+Cantidad+"&idCuenta="+Cuenta+"&Page="+page+"&TxtFecha="+Fecha2;

        document.getElementById(idLink).href=link;
    
    
}

function CalculeTotalPagoIngreso() {

	var Retefuente;
	var ReteIVA;
	var ReteICA;
        var Otros;
        var Anticipos;
	var Total;
	var TotalPago;
        
	Retefuente = document.getElementById("TxtRetefuente").value;
	ReteIVA = document.getElementById("TxtReteIVA").value;
	ReteICA = document.getElementById("TxtReteICA").value;
        Otros = document.getElementById("TxtOtrosDescuentos").value;
        Total = document.getElementById("TxtPagoH").value;
        Anticipos = document.getElementById("TxtAnticipos").value;
	TotalPago= Total - Retefuente - ReteIVA - ReteICA - Otros - Anticipos;
	
	document.getElementById("TxtPago").value = TotalPago;

}

// esta funcion permite mostrar u ocultar un elemento
function MuestreDesdeCombo(idCombo,idElement,idCarga){
    
    estado=document.getElementsByName(idCombo)[0].value;
    idComprobanteC=estado;
    if(estado==""){
        document.getElementById(idElement).style.display="none";
    }else{
        document.getElementById(idElement).style.display="block";
    }
    
}

// esta funcion permite mostrar u ocultar un elemento
function CargueIdEgreso(){
    
    document.getElementById('TxtIdCC').value=idComprobanteC;
    
}

// esta funcion permite mostrar u ocultar un elemento
function ObtengaValor(id){
    
    valor=document.getElementById(id).value;
    return valor;
}

// esta funcion permite calcular el valor y costo de un servicio, aplica solo para servitorno
function Servitorno_CalculePrecioVenta(Costos){
    
    var Cantidad;
    var TiempoMaquina;
    var Margen;
    var Maquinas;
    var CostosTotales;
    var ValorTotal;
    var CostoTotal;
    var TotalMateriales;
        
    Cantidad=document.getElementById('TxtCantidadPiezas').value;
    TotalMateriales=document.getElementById('TxtValorMateriales').value;
    if(Cantidad<=0){
        Cantidad=1;
    }
    TiempoMaquina=document.getElementById('TxtTiempoMaquinas').value;
    Margen=document.getElementById('TxtMargen').value;
    Maquinas=document.getElementById('TxtNumMaquinas').value;
    CostosTotales=Costos/Maquinas;
    
    ValorTotal=Math.round(((CostosTotales*TiempoMaquina)/Margen)/Cantidad);
    ValorTotal=parseInt(ValorTotal)+parseInt(TotalMateriales);
    CostoTotal=Math.round(((CostosTotales*TiempoMaquina))/Cantidad);
    document.getElementById("TxtPrecioVenta").value = ValorTotal;
    document.getElementById("TxtCostoUnitario").value = CostoTotal;
    
}

function MostrarDialogo() {

    document.getElementById('ShowItemsBusqueda').click();
		
}

function MostrarDialogoID(id) {
    //alert("entra "+id);
    document.getElementById(id).click();
		
}

function ClickElement(id) {

    document.getElementById(id).click();
		
}

function beep() {
   // alert("Entra");
  (new
	Audio(
	"data:audio/wav;base64,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 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////VEFHAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAU291bmRib3kuZGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMjAwNGh0dHA6Ly93d3cuc291bmRib3kuZGUAAAAAAAAAACU="
	)).play();
}

// esta funcion permite cambiar el max y min de una caja de texto
function CambiarMaxMin(idCaja,Min,Max){
    
    document.getElementById(idCaja).min=Min;
    document.getElementById(idCaja).max=Max;
    
}

//Funcion calcule valor con respecto a una caja de texto
function CalculeValorDependencia(idDependencia,idCambiar,Operacion,idValor) {
    	
	       
	ValorDependencia = document.getElementById(idDependencia).value;
        Valor=document.getElementById(idValor).value;
        if(Operacion=='P'){
            Resultado=(ValorDependencia*Valor)/100;
        }
        
        if(Operacion=='M'){
            Resultado=(ValorDependencia*Valor);
        }
	
	document.getElementById(idCambiar).value = Resultado;

}

//Funcion calcule la sumatoria de los montos y escribalo en una caja de texto
function CalculeSumatoria(idCambiar) {
    	
	var Total;
        var idMonto;
        Total=0;
        document.getElementById(idCambiar).value = 0;
        //Total=parseInt(document.getElementById('Monto2').value)+parseInt(document.getElementById('Monto3').value);
        
        for(i=1;i<=10000; i++){
            if(document.getElementById('Monto'+i)){
                //Total=Total+document.getElementById('Monto'+i).value;
                //alert(parseInt(document.getElementById('Monto'+i).value));
                Total=Total+parseFloat(document.getElementById('Monto'+i).value);
            }
        }
        //alert(valor);
	document.getElementById(idCambiar).value = Total;

}

function SeleccioneID(id){
    document.getElementById(id).select();
}

//Verifique que la fecha no esté cerrada

function CompareFechaCierre(FechaCierre,idFecha){
    FechaActual=new Date();
    Year=FechaActual.getFullYear();
    Mes=FechaActual.getMonth()+1;
    Dia=FechaActual.getDate();
    if(Mes<10){
        Mes="0"+Mes;
    }
    if(Dia<10){
        Dia="0"+Dia;
    }
    Fecha=Year+"-"+Mes+"-"+Dia;
    var FechaIn = document.getElementById(idFecha).value;
    valuesStart=FechaCierre.split("-");
    valuesEnd=FechaIn.split("-");
    var dateStart=new Date(valuesStart[0],(valuesStart[1]-1),valuesStart[2]);
    var dateEnd=new Date(valuesEnd[0],(valuesEnd[1]-1),valuesEnd[2]);
    if(dateStart>=dateEnd){
        alert("Esta fecha ya fue cerrada");
        document.getElementById(idFecha).value=Fecha;
    }else{
        //alert("La Fecha Introducida es mayor a la fecha de cierre");
    }
    
}

//Validar si el formato de fecha es real
function ValidarFecha(FechaCierre,idFecha){
 var fecha = document.getElementById(idFecha).value;
 var fechaArr = fecha.split('-');
 var aho = fechaArr[0];
 var mes = fechaArr[1];
 var dia = fechaArr[2];
 
 var plantilla = new Date(aho, mes - 1, dia);//mes empieza de cero Enero = 0

 if(!plantilla || plantilla.getFullYear() == aho && plantilla.getMonth() == mes -1 && plantilla.getDate() == dia){
    CompareFechaCierre(FechaCierre,idFecha);
 }else{
    alert('Formato de fecha Incorrecto');
    FechaActual=new Date();
    Year=FechaActual.getFullYear();
    Mes=FechaActual.getMonth()+1;
    Dia=FechaActual.getDate();
    if(Mes<10){
        Mes="0"+Mes;
    }
    if(Dia<10){
        Dia="0"+Dia;
    }
    document.getElementById(idFecha).value=Year+"-"+Mes+"-"+Dia;
 }
}

function Muestre(){
    alert("Entra2");
}

//Funcion para enviar el contenido de un texto a editar
function EditeRegistro(Tab,Columna,idTabla,idEdit,idElement){
    ValorEdit=document.getElementById(idElement).value;
    
    if (confirm('¿Estas seguro que deseas editar '+Columna+' de la tabla '+Tab+'?')){
        
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                httpEdicion = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                httpEdicion = new ActiveXObject("Microsoft.XMLHTTP");
            }
            httpEdicion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("DivRespuestasJS").innerHTML = this.responseText;
                    MuestraOculta('ventana-flotante');
                }
            };
        
        httpEdicion.open("GET","ProcesadoresJS/ProcesaUpdateJS.php?BtnEditarRegistro=1&TxtTabla="+Tab+"&TxtIDEdit="+idEdit+"&TxtIdTabla="+idTabla+"&TxtValorEdit="+ValorEdit+"&TxtColumna="+Columna,true);
        httpEdicion.send();
    }
}

//Funcion para enviar el contenido de un texto a editar sin confirmar
function EditeRegistroSinConfirmar(Tab,Columna,idTabla,idEdit,idElement){
    ValorEdit=document.getElementById(idElement).value;
    
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                httpEdicion = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                httpEdicion = new ActiveXObject("Microsoft.XMLHTTP");
            }
            httpEdicion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("DivRespuestasJS").innerHTML = this.responseText;
                    //MuestraOculta('ventana-flotante');
                }
            };
        
        httpEdicion.open("GET","ProcesadoresJS/ProcesaUpdateJS.php?BtnEditarRegistro=1&NoConfirma=1&TxtTabla="+Tab+"&TxtIDEdit="+idEdit+"&TxtIdTabla="+idTabla+"&TxtValorEdit="+ValorEdit+"&TxtColumna="+Columna,true);
        httpEdicion.send();
    
}

//Funcion para enviar el contenido de una caja de texto a una pagina y dibujarlo en un div
function EnvieObjetoConsulta(Page,idElement,idTarget,BorrarId=1){
    
    //alert("entra");
    ValorElement=document.getElementById(idElement).value;  
    
        verifique=Page.substr(14,10);
        if(verifique=="/PrintCodi"){
            document.getElementById(idTarget).innerHTML ='Conectando...<br><img src="../images/process.gif" alt="Cargando" height="100" width="100">';
        }
        if(BorrarId==5){
            document.getElementById(idTarget).innerHTML ='<br><img src="../images/cargando.gif" alt="Cargando" height="100" width="100">';
        }
        if(BorrarId==6){
            ValorElement = ValorElement.substring(1, 10);
        }
        if(BorrarId==7){
            idMesa=document.getElementById('idMesa').value;
            ValorElement = ValorElement+"&idMesa="+idMesa;
        }
        if(BorrarId==8){
            idMesa=document.getElementById('idMesa').value;
            Cantidad=document.getElementById('TxtCantidadItem').value;
            Observaciones=document.getElementById('TxtObservacionesItem').value;
            ValorElement = ValorElement+"&idMesa="+idMesa+"&TxtCantidad="+Cantidad+"&TxtObservaciones="+Observaciones;
            document.getElementById('TxtCantidadItem').value=1;
            document.getElementById('TxtObservacionesItem').value=""; 
            document.getElementById('ImgBuscar').click(); 
            
        }
        if(BorrarId==9){
            var Observaciones = prompt("Por favor ingrese la Razon por la que se descarta", "");
            if (Observaciones.length < 3) {
                alert("Debe Digitar una razon para descartar el Pedido");
                return;
            }
            Observaciones="Descartado por "+Observaciones;
            
            ValorElement = ValorElement+"&TxtObservaciones="+Observaciones;
        }
        if(BorrarId==10){
            var Observaciones = prompt("Por favor ingrese la Razon por la que se elimina el item", "");
            if (Observaciones.length < 3) {
                alert("Debe Digitar una razon para eliminar el item");
                return;
            }
               
            ValorElement = ValorElement+"&TxtCausal="+Observaciones;
        }
        
        if(BorrarId==11){
            
            TxtGranTotalH =document.getElementById('TxtGranTotalH').value;
            TxtidColaborador =document.getElementById('TxtidColaborador').value;
            TxtPaga =document.getElementById('TxtPaga').value;
            TxtPagaCheque  =document.getElementById('TxtPagaCheque').value;
            TxtPagaTarjeta  =document.getElementById('TxtPagaTarjeta').value;
            CmbIdTarjeta  =document.getElementById('CmbIdTarjeta').value;
            TxtPagaOtros  =document.getElementById('TxtPagaOtros').value;
            TxtDevuelta  =document.getElementById('TxtDevuelta').value;
            if(TxtDevuelta<0){
                alert("el dinero recibido debe ser igual o mayor al total");
                return;
            }
                
            TxtCuentaDestino  =document.getElementById('TxtCuentaDestino').value;
            TxtTipoPago  =document.getElementById('TxtTipoPago').value;
            TxtAnticipo  =document.getElementById('TxtAnticipo').value;
            CmbAnticipo  =document.getElementById('CmbAnticipo').value;
            CmbPrint  =document.getElementById('CmbPrint').value;
            TxtObservacionesFactura   =document.getElementById('TxtObservacionesFactura').value;
            VC="&TxtGranTotalH="+TxtGranTotalH+"&TxtidColaborador="+TxtidColaborador;
            VC=VC+"&TxtPaga="+TxtPaga+"&TxtPagaCheque="+TxtPagaCheque+"&TxtPagaTarjeta="+TxtPagaTarjeta;
            VC=VC+"&CmbIdTarjeta="+CmbIdTarjeta+"&TxtPagaOtros="+TxtPagaOtros+"&TxtDevuelta="+TxtDevuelta;
            VC=VC+"&TxtCuentaDestino="+TxtCuentaDestino+"&TxtTipoPago="+TxtTipoPago+"&TxtAnticipo="+TxtAnticipo;
            VC=VC+"&CmbAnticipo="+CmbAnticipo+"&TxtObservacionesFactura="+TxtObservacionesFactura+"&CmbPrint="+CmbPrint;
            ValorElement = ValorElement+VC;
            document.getElementById(idTarget).innerHTML ='<br><img src="../images/processing.gif" alt="Procesando" height="100" width="100">';           
        }
        
        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                httpEdicion = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                httpEdicion = new ActiveXObject("Microsoft.XMLHTTP");
            }
            httpEdicion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(idTarget).innerHTML = this.responseText;
                    
                }
            };
        
        httpEdicion.open("GET",Page+ValorElement,true);
        httpEdicion.send();
        //document.getElementById(idElement).value='Limpiando';
        if(BorrarId==1){
            document.getElementById(idElement).value='';
        }
        if(BorrarId==2){
            //alert('entra');
            setTimeout("posiciona('TxtCantidad')",500);
            //document.getElementById('TxtCantidadBascula').select();
        }
        if(BorrarId==11){
            //alert('entra');
            posiciona('TxtCodigoBarras');
            //document.getElementById('TxtCantidadBascula').select();
        }
        //alert("Sale");
}

//Funcion para enviar el contenido de una caja de texto a una pagina y dibujarlo en un div
function EnvieObjetoConsulta2(Page,idElement,idTarget,BorrarId=1){
    
    ValorElement=document.getElementById(idElement).value;  
    
        if(BorrarId==2){
            TxtFechaIniBC =document.getElementById('TxtFechaIniBC').value;
            TxtFechaFinalBC =document.getElementById('TxtFechaFinalBC').value;
            TxtFechaCorteBC =document.getElementById('TxtFechaCorteBC').value;
            CmbTipoReporteBC  =document.getElementById('CmbTipoReporteBC').value;
            CmbEmpresaProBC  =document.getElementById('CmbEmpresaProBC').value;
            CmbCentroCostosBC  =document.getElementById('CmbCentroCostosBC').value;
            
            VC="&TxtFechaIniBC="+TxtFechaIniBC+"&TxtFechaFinalBC="+TxtFechaFinalBC+"&TxtFechaCorteBC="+TxtFechaCorteBC;
            VC=VC+"&CmbTipoReporteBC="+CmbTipoReporteBC+"&CmbEmpresaProBC="+CmbEmpresaProBC+"&CmbCentroCostosBC="+CmbCentroCostosBC;
           
            ValorElement = ValorElement+VC;
            document.getElementById(idTarget).innerHTML ='<br><img src="../images/processing.gif" alt="Cargando" height="100" width="100">';
            
        }
        //Para cobros prejuridicos salud
        if(BorrarId==3){
            TipoCobro =document.getElementById('CmbCobro').value;
            idEps =document.getElementById('idEps').value;                        
            ValorElement="?idEPS="+idEps+"&TipoCobro="+TipoCobro;            
            document.getElementById(idTarget).innerHTML ='<br><img src="../images/cargando.gif" alt="Cargando" height="100" width="100">';
            
        }
        //Para generar circular 030
        if(BorrarId==4){
            FechaInicial =document.getElementById('TxtFechaInicial').value;
            FechaFinal =document.getElementById('TxtFechaFinal').value;
            TipoInforme =document.getElementById('CmbAdicional').value;                        
            ValorElement="?BtnCrear=1&TxtFechaInicial="+FechaInicial+"&TxtFechaFinal="+FechaFinal+"&CmbAdicional="+TipoInforme;            
            document.getElementById(idTarget).innerHTML ='<br><img src="../images/cargando.gif" alt="Cargando" height="100" width="100">';
            
        }
        //Cambio del boton switch ON
        if(BorrarId==5){
            CambiarImagenOnOff(idElement);
        }
        
        //Para generar circular 014
        if(BorrarId==7){
            var Mes =document.getElementById('CmbMes').value;
            var Anio =document.getElementById('CmbAnio').value;
                                  
            ValorElement="?BtnCrear=1&CmbMes="+Mes+"&CmbAnio="+Anio;            
            document.getElementById(idTarget).innerHTML ='<br><img src="../images/cargando.gif" alt="Cargando" height="100" width="100">';
            
        }
        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                httpEdicion = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                httpEdicion = new ActiveXObject("Microsoft.XMLHTTP");
            }
            httpEdicion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(idTarget).innerHTML = this.responseText;
                    
                }
            };
        
        httpEdicion.open("GET",Page+ValorElement,true);
        httpEdicion.send();
        //document.getElementById(idElement).value='Limpiando';
        if(BorrarId==1){
            document.getElementById(idElement).value='';
        }
        
        //alert("Sale");
}
//Calcule la retefuente en una compra segun porcentaje


function CalculeReteFuenteCompra(Subtotal,Servicios=0){
    if(Servicios==0){
        Porcentaje=parseFloat(document.getElementById('TxtPorReteFuente').value);
    }else{
        Porcentaje=parseFloat(document.getElementById('TxtPorReteFuenteServicios').value);
    }
    Porcentaje=(Porcentaje/100);
    Retefuente=(Subtotal*Porcentaje).toFixed(0);
    if(Servicios==0){
        document.getElementById('TxtReteFuenteProductos').value=Retefuente;
    }else{
        document.getElementById('TxtReteFuenteServicios').value=Retefuente;
    }
}

//Calcule la retefuente en una compra segun porcentaje


function CalculePorcentajeReteFuenteCompra(Subtotal,Servicios=0){
    if(Servicios==0){
        Retefuente=parseFloat(document.getElementById('TxtReteFuenteProductos').value);
    }else{
        Retefuente=parseFloat(document.getElementById('TxtReteFuenteServicios').value);
    }
    Porcentaje=((100/Subtotal)*Retefuente).toFixed(2);
    if(Servicios==0){
        document.getElementById('TxtPorReteFuente').value=Porcentaje;
    }else{
        document.getElementById('TxtPorReteFuenteServicios').value=Porcentaje;
    }
    
}

//Calcule la reteiva en una compra segun porcentaje


function CalculeReteIVACompra(IVA,Servicios=0){
    if(Servicios==0){
        Porcentaje=parseFloat(document.getElementById('TxtPorReteIVA').value);
    }else{
        Porcentaje=parseFloat(document.getElementById('TxtPorReteIVAServicios').value);
    }
    Porcentaje=(Porcentaje/100);
    Retefuente=(IVA*Porcentaje).toFixed(0);
    if(Servicios==0){
        document.getElementById('TxtReteIVA').value=Retefuente;
    }else{
        document.getElementById('TxtReteIVAServicios').value=Retefuente;
    }    
}

//Calcule la reteiva en una compra segun porcentaje


function CalculePorcentajeIVACompra(IVA,Servicios=0){
    if(Servicios==0){
        Retefuente=parseFloat(document.getElementById('TxtReteIVA').value);
    }else{
        Retefuente=parseFloat(document.getElementById('TxtReteIVAServicios').value);
    }    
    Porcentaje=((100/IVA)*Retefuente).toFixed(2);
    if(Servicios==0){
        document.getElementById('TxtPorReteIVA').value=Porcentaje;
    }else{
        document.getElementById('TxtPorReteIVAServicios').value=Porcentaje;    
    }
}

//Calcule la reteiva en una compra segun porcentaje


function CalculeReteICACompra(Subtotal,Servicios=0){
    if(Servicios==0){
        Porcentaje=parseFloat(document.getElementById('TxtPorReteICA').value);
    }else{
        Porcentaje=parseFloat(document.getElementById('TxtPorReteICAServicios').value);
    }    
    Porcentaje=(Porcentaje/100);
    Retefuente=(Subtotal*Porcentaje).toFixed(0);
    if(Servicios==0){
        document.getElementById('TxtReteICA').value=Retefuente;
    }else{
        document.getElementById('TxtReteICAServicios').value=Retefuente;
    }    
}

//Calcule la reteiva en una compra segun porcentaje


function CalculePorcentajeICACompra(Subtotal,Servicios=0){
    if(Servicios==0){
        Retefuente=parseFloat(document.getElementById('TxtReteICA').value);
    }else{
        Retefuente=parseFloat(document.getElementById('TxtReteICAServicios').value);
    }
    Porcentaje=((100/Subtotal)*Retefuente).toFixed(2);
    if(Servicios==0){
        document.getElementById('TxtPorReteICA').value=Porcentaje;
    }else{
        document.getElementById('TxtPorReteICAServicios').value=Porcentaje;
    }    
}


function ValidarUploads() {
    //Valida Archivo de Consultas
    flag_envia=1;
    if(document.getElementById("CmbTipoNegociacion").value==''){
        alert("Debe seleccionar un tipo de negociacion");
        document.getElementById("CmbTipoNegociacion").focus();
        return;
    }
    if(document.getElementById("UpAC").value){
        NombreAC = document.getElementById("UpAC").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AC'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAC').value="";
           document.getElementById('DivAC').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Consultas debe empezar por AC</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAC').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>"; 
        }
        
    }else{
        document.getElementById('DivAC').value="";
        document.getElementById('DivAC').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
    
    //Valida Archivo de Hospitalizaciones
    
    if(document.getElementById("UpAH").value){
        NombreAC = document.getElementById("UpAH").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AH'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAH').value="";
           document.getElementById('DivAH').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Hospitalizaciones debe empezar por AH</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAH').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivAH').value="";
        document.getElementById('DivAH').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
    
    //Valida Archivo de Medicamentos
    
    if(document.getElementById("UpAM").value){
        NombreAC = document.getElementById("UpAM").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AM'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAM').value="";
           document.getElementById('DivAM').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Medicamentos debe empezar por AM</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAM').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivAM').value="";
        document.getElementById('DivAM').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
      
    //Valida Archivo de Procedimientos
    
    if(document.getElementById("UpAP").value){
        NombreAC = document.getElementById("UpAP").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AP'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAP').value="";
           document.getElementById('DivAP').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Procedimientos debe empezar por AP</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAP').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivAP').value="";
        document.getElementById('DivAP').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
    
    
    //Valida Archivo de Otros Servicios
    
    if(document.getElementById("UpAT").value){
        NombreAC = document.getElementById("UpAT").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AT'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAT').value="";
           document.getElementById('DivAT').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Otros Procedimientos debe empezar por AT</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAT').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivAT').value="";
        document.getElementById('DivAT').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
    
    
    //Valida Archivo de Usuarios
    
    if(document.getElementById("UpUS").value){
        NombreAC = document.getElementById("UpUS").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='US'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivUS').value="";
           document.getElementById('DivUS').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Usuarios debe empezar por US</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivUS').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivUS').value="";
        document.getElementById('DivUS').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
    
    
    //Valida Archivo de Facturas
    
    if(document.getElementById("UpAF").value){
        NombreAC = document.getElementById("UpAF").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AF'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAF').value="";
           document.getElementById('DivAF').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Facturas debe empezar por AF</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAF').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivAF').value="";
        document.getElementById('DivAF').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
    
    
    
    //Valida Archivo de Errores
    
    if(document.getElementById("UpAD").value){
        NombreAC = document.getElementById("UpAD").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AD'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAD').value="";
           document.getElementById('DivAD').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Errores debe empezar por AD</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAD').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivAD').value="";
        document.getElementById('DivAD').innerHTML = "<FONT COLOR='orange'>Este Archivo No Es Obligatorio!</FONT>";
           
        
    }
    
    if(flag_envia==1){
        EnviaForm('FrmArchivos');
    }else{
        alert("Faltan archivos o algunos no son correctos");
    }
    
   
}


function ValidaRIPSPagos(){
    //Valida Archivo de Pagos
    flag_envia=1;
    if(document.getElementById("UpAR").value){
        NombreAC = document.getElementById("UpAR").files[0].name;
        Inicial=NombreAC.substring(0,2);
        
        if(Inicial!='AR'){
           //alert("El nombre del archivo seleccionado para Consultas debe empezar por AC");
           document.getElementById('DivAR').value="";
           document.getElementById('DivAR').innerHTML = "<FONT COLOR='red'>Archivo incorrecto!, El nombre del archivo seleccionado para Pagos debe empezar por AR</FONT>";
           flag_envia=0;
        }else{
           document.getElementById('DivAR').innerHTML="<FONT COLOR='green'>Nombre de Archivo Correcto!</FONT>";
     
        }
        
    }else{
        document.getElementById('DivAR').value="";
        document.getElementById('DivAR').innerHTML = "<FONT COLOR='red'>Archivo Obligatorio!</FONT>";
           
        flag_envia=0;
    }
    
    if(flag_envia==1){
        EnviaForm('FrmRipsPagos');
        //alert("Enviado");
    }else{
        alert("No se hay ningun archivo o no es valido");
    }
    
}

function EscribaValor(id,Valor){
    document.getElementById(id).value=Valor;
}

function CopiarCodigoGlosa(){
    idCajaGlosa=document.getElementById('CajaAsigna').value;
    Valor=document.getElementById('CmbGlosas').value;
    document.getElementById(idCajaGlosa).value=Valor;
}

function CambiarImagenOnOff(idElement){
    Img=document.getElementById(idElement).src;
    //alert(Img.substr(-5));
    if(Img.substr(-5)=='f.png'){
        document.getElementById(idElement).src = "../images/on.png";
    }else{
        document.getElementById(idElement).src = "../images/off.png";
    }
     
    
}