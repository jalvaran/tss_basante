/*
document.getElementById("Password").addEventListener("keypress", SoloNumeroYLetras); 
document.getElementById("Login").addEventListener("keypress", SoloNumeroYLetras); 
function SoloNumeroYLetras(e){
      
    var key = window.Event ? e.which : e.keyCode
    console.log(key) 
    return ((key >= 48 && key <= 57) || (key >= 0 && key <= 31) || key == 127)
   
   

}

*/
function EnviaForm(idForm) {
	
	document.getElementById(idForm).submit();
		
}

function incrementa(id) {

	document.getElementById(id).value++;
	

}

function decrementa(id) {

if(document.getElementById(id).value > 1)
	document.getElementById(id).value--;

}

function posiciona(id){ 
   
   document.getElementById(id).focus();
   
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

function ConfirmarLink(id){
	
    if (confirm('Desea continuar?')){ 
     
      document.location.href= document.getElementById(id).value;
    } 
}

// esta funcion permite mostrar u ocultar un elemento
function MuestraOculta(id){
    
    estado=document.getElementById(id).style.display;
    if(estado=="none" || estado==""){
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


// esta funcion permite mostrar u ocultar un elemento
function ObtengaValor(id){
    
    valor=document.getElementById(id).value;
    return valor;
}

function MostrarDialogo() {

    document.getElementById('ShowItemsBusqueda').click();
		
}

function MostrarDialogoID(id) {
    
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

function SeleccioneID(id){
    document.getElementById(id).select();
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
       
        if(BorrarId==1){
            document.getElementById(idElement).value='';
        }
        if(BorrarId==2){
            
            setTimeout("posiciona('TxtCantidad')",500);
            
        }
        
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
        //informes con rangos de fechas
        if(BorrarId==4){
            FechaInicial =document.getElementById('TxtFechaIni').value;
            FechaFinal =document.getElementById('TxtFechaFin').value;                        
            ValorElement="?TxtFechaIni="+FechaInicial+"&TxtFechaFin="+FechaFinal;            
            //document.getElementById(idTarget).innerHTML ='<br><img src="../images/cargando.gif" alt="Cargando" height="100" width="100">';
            
        }
        //Cambio del boton switch ON
        if(BorrarId==5){
            CambiarImagenOnOff(idElement);
        }
        
                
        if(BorrarId==98){
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