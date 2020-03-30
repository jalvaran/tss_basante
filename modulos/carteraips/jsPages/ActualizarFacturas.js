/**
 * Controlador para cartera
 * JULIAN ALVARAN 2019-05-20
 * TECHNO SOLUCIONES SAS 
 * 
 */

/**
 * Cierra una ventana modal
 * @param {type} idModal
 * @returns {undefined}
 */
function CierraModal(idModal) {
    $("#"+idModal).modal('hide');//ocultamos el modal
    $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
    $('.modal-backdrop').remove();//eliminamos el backdrop del modal
}

function AbreModal(idModal){
    $("#"+idModal).modal();
}


function MuestraXID(id){
    
    
    document.getElementById(id).style.display="block";
    
    
}


function OcultaXID(id){
    
    
    document.getElementById(id).style.display="none";
    
    
}

/**
 * Muestra u oculta un elemento por su id
 * @param {type} id
 * @returns {undefined}
 */

function MuestraOcultaXID(id){
    
    var estado=document.getElementById(id).style.display;
    if(estado=="none" | estado==""){
        document.getElementById(id).style.display="block";
    }
    if(estado=="block"){
        document.getElementById(id).style.display="none";
    }
    
}


function EditarFactura(NumeroFactura){
    OcultaXID('BntModalAcciones');
    AbreModal('ModalAcciones');
    document.getElementById("DivFrmModalAcciones").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    var CmbEPS=document.getElementById('CmbEPS').value;
    var CmbIPS=document.getElementById('CmbIPS').value;
    
    var form_data = new FormData();
        form_data.append('Accion', 8);        
        form_data.append('NumeroFactura', NumeroFactura);
        form_data.append('CmbEPS', CmbEPS);
        form_data.append('CmbIPS', CmbIPS);
        $.ajax({
        url: './Consultas/validaciones.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           document.getElementById('DivFrmModalAcciones').innerHTML=data;
           
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            LimpiarDivs();
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function EnviarFacturaEditar(){
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    
    document.getElementById('BtnEjecutar').disabled=true;
    document.getElementById('BtnEjecutar').value="Enviando...";
    var TxtNumeroFacturaEdit=document.getElementById('TxtNumeroFacturaEdit').value;
    var TxtFacturaNueva=document.getElementById('TxtFacturaNueva').value;
    var TxtObservacionesEdicioFactura=document.getElementById('TxtObservacionesEdicioFactura').value;
        
    if($('#TxtNumeroFacturaEdit').val()==null || $('#TxtNumeroFacturaEdit').val()==''){
          alertify.alert("por favor digite la factura que va a editar");   
          document.getElementById('BtnEjecutar').disabled=false;
          document.getElementById('BtnEjecutar').value="Ejecutar";
          document.getElementById('TxtNumeroFacturaEdit').style.backgroundColor="pink";
          return;
    }else{
        document.getElementById('TxtNumeroFacturaEdit').style.backgroundColor="white";
    }
    
    if($('#TxtFacturaNueva').val()==null || $('#TxtFacturaNueva').val()==''){
          alertify.alert("por favor digite la factura que reemplazará la anterior");
          document.getElementById('BtnEjecutar').disabled=false;
          document.getElementById('BtnEjecutar').value="Ejecutar";
          document.getElementById('TxtFacturaNueva').style.backgroundColor="pink";
          return;
    }else{
        document.getElementById('TxtFacturaNueva').style.backgroundColor="white";
    }
    
    if($('#TxtObservacionesEdicioFactura').val()==null || $('#TxtObservacionesEdicioFactura').val()==''){
          alertify.alert("por favor digite las observaciones");
          document.getElementById('BtnEjecutar').disabled=false;
          document.getElementById('BtnEjecutar').value="Ejecutar";
          document.getElementById('TxtObservacionesEdicioFactura').style.backgroundColor="pink";
          return;
    }else{
        document.getElementById('TxtObservacionesEdicioFactura').style.backgroundColor="white";
    }
    
    var form_data = new FormData();
        form_data.append('Accion', 1);
        form_data.append('TxtNumeroFacturaEdit', TxtNumeroFacturaEdit);
        form_data.append('TxtFacturaNueva', TxtFacturaNueva);
        form_data.append('TxtObservacionesEdicioFactura', TxtObservacionesEdicioFactura);
        
       
    $.ajax({
        //async:false,
        url: './procesadores/ActualizarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               
                document.getElementById('BtnEjecutar').disabled=false;
                document.getElementById('BtnEjecutar').value="Actualizar";
                
                alertify.success(respuestas[1]);
                BuscarFacturasPagasConDiferencia();
                BuscarFacturasPagas();
            }else if(respuestas[0]==="E1"){
                
                alertify.alert(respuestas[1]);
                document.getElementById('BtnEjecutar').disabled=false;
                document.getElementById('BtnEjecutar').value="Actualizar";
                return;                
            }else{
                
                alertify.alert(data);
                document.getElementById('BtnEjecutar').disabled=false;
                document.getElementById('BtnEjecutar').value="Actualizar";
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            document.getElementById('BtnEjecutar').disabled=false;
            document.getElementById('BtnEjecutar').value="Ejecutar";
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function ConfirmarCarga(){
    var UpActualizaciones=document.getElementById('UpActualizaciones').value;
    if(UpActualizaciones==""){
        alertify.alert("No se ha subido ningún archivo");
        return;
    }
    alertify.confirm('Está seguro que desea Realizar ésta actualización?',
        function (e) {
            if (e) {

                alertify.success("Subiendo Archivo");                    
                InicieCarga();
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function LimpiarDivs(){
    document.getElementById("DivProcess").innerHTML="";
}

function InicieCarga(){
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Mensajes=document.getElementById("DivMensajes");
    Mensajes.innerHTML="Iniciando la carga";
    
    var idBoton="BtnSubirActualizacionesMasivas";
    
    document.getElementById(idBoton).disabled=true;
    
    
    if($('#UpActualizaciones').val()==null || $('#UpActualizaciones').val()==''){
          alertify.alert("por favor seleccione un archivo");
          document.getElementById(idBoton).disabled=false;
          
          document.getElementById('UpActualizaciones').style.backgroundColor="pink";
          return;
    }else{
        document.getElementById('UpActualizaciones').style.backgroundColor="white";
    }
    
    var form_data = new FormData();
        form_data.append('Accion', 2);
        
        form_data.append('UpActualizaciones', $('#UpActualizaciones').prop('files')[0]);
      
    $.ajax({
        //async:false,
        url: './procesadores/ActualizarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               $('.progress-bar').css('width','20%').attr('aria-valuenow', 20);  
                document.getElementById('LyProgresoUP').innerHTML="20%";
                var RutaArchivo=respuestas[2];
                var Extension=respuestas[3];
                alertify.success(respuestas[1]);
                Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Archivo Cargado";
                LeaArchivo(RutaArchivo,Extension);
            }else if(respuestas[0]==="E1"){
                LimpiarDivs();
                alertify.alert(respuestas[1]);
                document.getElementById(idBoton).disabled=false;
                
                return;                
            }else{
               LimpiarDivs();
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            LimpiarDivs();
            document.getElementById(idBoton).disabled=false;
            
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function LeaArchivo(RutaArchivo,Extension){
   
    var Mensajes=document.getElementById("DivMensajes");
    Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Iniciando la lectura del Archivo";
    
    var idBoton="BtnSubirActualizacionesMasivas";
    
    document.getElementById(idBoton).disabled=true;
    var form_data = new FormData();
        form_data.append('Accion', 3);
        
        
        form_data.append('RutaArchivo', RutaArchivo);
        form_data.append('Extension', Extension);
      
    $.ajax({
        //async:false,
        url: './procesadores/ActualizarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               $('.progress-bar').css('width','30%').attr('aria-valuenow', 30);  
                document.getElementById('LyProgresoUP').innerHTML="30%";
                alertify.success(respuestas[1]);
                Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Archivo Leído";
                ValidarRegistros();
            }else if(respuestas[0]==="E1"){
                
                alertify.alert(respuestas[1]);
                document.getElementById(idBoton).disabled=false;
                LimpiarDivs();
                return;                
            }else{
               LimpiarDivs();
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                LimpiarDivs();
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            LimpiarDivs();
            document.getElementById(idBoton).disabled=false;
            
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function ValidarRegistros(){
    var Mensajes=document.getElementById("DivMensajes");
    Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Iniciando la validación de los registros del Archivo";
    var idBoton="BtnSubirActualizacionesMasivas";
    
    var form_data = new FormData();
        form_data.append('Accion', 4);
        
        
      
    $.ajax({
        //async:false,
        url: './procesadores/ActualizarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               $('.progress-bar').css('width','40%').attr('aria-valuenow', 40);  
                document.getElementById('LyProgresoUP').innerHTML="40%";
                alertify.success(respuestas[1]);
                 Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Validación terminada";
                ActualizarFacturasDesdeTemporal();
            }else if(respuestas[0]==="E1"){
                LimpiarDivs();
                alertify.alert(respuestas[1]);
                document.getElementById(idBoton).disabled=false;
                
                return;                
            }else{
               LimpiarDivs();
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            LimpiarDivs();
            document.getElementById(idBoton).disabled=false;
            
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function ActualizarFacturasDesdeTemporal(){
    var Mensajes=document.getElementById("DivMensajes");
    Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Iniciando la Actualización de las facturas";
    var idBoton="BtnSubirActualizacionesMasivas";
        
    var form_data = new FormData();
        form_data.append('Accion', 5);
        
        
      
    $.ajax({
        //async:false,
        url: './procesadores/ActualizarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               $('.progress-bar').css('width','50%').attr('aria-valuenow', 50);  
                document.getElementById('LyProgresoUP').innerHTML="50%";
                alertify.success(respuestas[1]);
                Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Facturas Actualizadas";
                BuscarFacturasPagasConDiferencia();
            }else if(respuestas[0]==="E1"){
                LimpiarDivs();
                alertify.alert(respuestas[1]);
                document.getElementById(idBoton).disabled=false;
                
                return;                
            }else{
               LimpiarDivs();
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            LimpiarDivs();
            document.getElementById(idBoton).disabled=false;
            
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function BuscarFacturasPagasConDiferencia(){
    var Mensajes=document.getElementById("DivMensajes");
    Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Iniciando la busqueda de facturas pagas con Diferencia";
    var idBoton="BtnSubirActualizacionesMasivas";
        
    var form_data = new FormData();
        form_data.append('Accion', 6);
        
        
      
    $.ajax({
        //async:false,
        url: './procesadores/ActualizarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               $('.progress-bar').css('width','75%').attr('aria-valuenow', 75);  
                document.getElementById('LyProgresoUP').innerHTML="75%";
                Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Busqueda de facturas pagas con Diferencia Realizada";
                alertify.success(respuestas[1]);
                BuscarFacturasPagas();
            }else if(respuestas[0]==="E1"){
                LimpiarDivs();
                alertify.alert(respuestas[1]);
                document.getElementById(idBoton).disabled=false;
                
                return;                
            }else{
               LimpiarDivs();
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            LimpiarDivs();
            document.getElementById(idBoton).disabled=false;
            
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function BuscarFacturasPagas(){
    var Mensajes=document.getElementById("DivMensajes");
    Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Iniciando la busqueda de facturas pagas Completamente";
    var idBoton="BtnSubirActualizacionesMasivas";
        
    var form_data = new FormData();
        form_data.append('Accion', 7);
        
        
      
    $.ajax({
        //async:false,
        url: './procesadores/ActualizarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               $('.progress-bar').css('width','100%').attr('aria-valuenow', 100);  
                document.getElementById('LyProgresoUP').innerHTML="100%";
                alertify.success(respuestas[1]);
                Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Facturas pagas Completamente encontradas";
                Mensajes.innerHTML=Mensajes.innerHTML+"<br>"+"Todos los procesos fueron ejecutados éxitosamente";
                LimpiarDivs();
            }else if(respuestas[0]==="E1"){
                LimpiarDivs();
                alertify.alert(respuestas[1]);
                document.getElementById(idBoton).disabled=false;
                
                return;                
            }else{
               LimpiarDivs();
                alertify.alert(data);
                document.getElementById(idBoton).disabled=false;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            LimpiarDivs();
            document.getElementById(idBoton).disabled=false;
            
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

document.getElementById('BtnMuestraMenuLateral').click();
