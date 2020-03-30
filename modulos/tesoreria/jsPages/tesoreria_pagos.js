/**
 * Controlador para administrar los pagos que ingresan al modulo de tesoreria
 * JULIAN ANDRES ALVARAN
 * 2020-02-16
 */

document.getElementById("BtnMuestraMenuLateral").click(); //da click sobre el boton que esconde el menu izquierdo de la pagina principal

/**
 * Funcion que lista las tablas de una base de datos
 * @returns {undefined}
 */

function ListarPagos(Page=1){
    
    document.getElementById("DivDrawTables").innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var Busquedas =document.getElementById("TxtBusquedas").value;
    var FechaInicialRangos =document.getElementById("FechaInicialRangos").value;
    var FechaFinalRangos =document.getElementById("FechaFinalRangos").value;
    var cmbFiltros =document.getElementById("cmbFiltros").value;
    
    var form_data = new FormData();
        form_data.append('Accion', 1);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('Page', Page);
        form_data.append('Busquedas', Busquedas);
        form_data.append('FechaInicialRangos', FechaInicialRangos);
        form_data.append('FechaFinalRangos', FechaFinalRangos);
        form_data.append('cmbFiltros', cmbFiltros);
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/tesoreria_pagos.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById('DivDrawTables').innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            document.getElementById('DivDrawTables').innerHTML="hay un problema!";
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function FormularioNuevoPago(){
    document.getElementById("DivDrawTables").innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var form_data = new FormData();
        form_data.append('Accion', 2);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/tesoreria_pagos.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById('DivDrawTables').innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos    
            $('#CmbEps').select2({
		  
                placeholder: 'Selecciona una EPS',
                ajax: {
                  url: 'buscadores/salud_eps.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
            
            
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            document.getElementById('DivDrawTables').innerHTML="hay un problema!";
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function ConfirmaCrearPago(){
    alertify.confirm('Está seguro que desea Crear este Pago?',
        function (e) {
            if (e) {
                
                CrearPago();
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function CrearPago(){
    var idDivMensajes='DivMensajes';
    var idBoton='BtnGuardar';
    document.getElementById(idBoton).disabled=true;
    var Fecha=document.getElementById("Fecha").value;    
    var CmbEps=document.getElementById("CmbEps").value;    
    var CmbBanco=document.getElementById("CmbBanco").value;    
    var NumeroTransaccion=document.getElementById("NumeroTransaccion").value;  
    var CmbTipoPago=document.getElementById("CmbTipoPago").value; 
    
    var ValorTransaccion=document.getElementById("ValorTransaccion").value;
    var Observaciones=document.getElementById("Observaciones").value;
    
    
    var form_data = new FormData();
        form_data.append('Accion', '1'); 
        form_data.append('Fecha', Fecha);
        form_data.append('CmbEps', CmbEps);
        form_data.append('CmbBanco', CmbBanco);
        form_data.append('NumeroTransaccion', NumeroTransaccion);
        form_data.append('CmbTipoPago', CmbTipoPago);
        form_data.append('ValorTransaccion', ValorTransaccion);
        form_data.append('Observaciones', Observaciones);        
        form_data.append('UpSoporte', $('#UpSoporte').prop('files')[0]);
        
        
        $.ajax({
        url: './procesadores/tesoreria_pagos.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){
                
                alertify.success(respuestas[1]);
                ListarPagos();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
            document.getElementById(idBoton).disabled=false;         
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;   
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
    }
    
function MarqueErrorElemento(idElemento){
    
    if(idElemento==undefined){
       return; 
    }
    document.getElementById(idElemento).style.backgroundColor="pink";
    document.getElementById(idElemento).focus();
}


function AbreFormularioLegalizarPago(idPago){
    document.getElementById("DivDrawTables").innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var form_data = new FormData();
        form_data.append('Accion', 3);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idPago', idPago);
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/tesoreria_pagos.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById('DivDrawTables').innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos    
            $('#CmbEps').select2({
		  
                placeholder: 'Selecciona una EPS',
                ajax: {
                  url: 'buscadores/salud_eps.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
            
            
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            document.getElementById('DivDrawTables').innerHTML="hay un problema!";
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function getInfoForm(){
        
    var form_data = new FormData();
    form_data.append('CmbSeparador', $('#CmbSeparador').val());
    form_data.append('CmbTipoGiro', $('#CmbTipoGiro').val());
    form_data.append('TxtFechaGira', $('#TxtFechaGira').val());
    form_data.append('UpPago', $('#UpPago').prop('files')[0]);
    form_data.append('UpSoporte', $('#UpSoporte').prop('files')[0]);
    return form_data;
}

/**
 * Envia la peticion para que sea cargado el AR
 * @returns {undefined}
 */
function CargarAR(idPago){
    
    if($('#UpPago').val()==''){
        alertify.alert("Debe Subir un archivo de pago");
        document.getElementById('UpPago').style.backgroundColor="pink";
        return;
    }else{
        document.getElementById('UpPago').style.backgroundColor="";
    }
    if($('#UpSoporte').val()==''){
        alertify.alert("Debe subir un Soporte");
        document.getElementById('UpSoporte').style.backgroundColor="pink";
        return;
    }else{
        document.getElementById('UpSoporte').style.backgroundColor="";
    }
    if($('#TxtFechaGira').val()==''){
         alertify.alert("Debe seleccionar una fecha");
         document.getElementById('TxtFechaGira').style.backgroundColor="pink";
         return;
     }else{
         document.getElementById('TxtFechaGira').style.backgroundColor="";
     }
     if($('#TxtObservaciones').val()==''){
         alertify.alert("Debe Escribir las observaciones");
         document.getElementById('TxtObservaciones').style.backgroundColor="pink";
         return;
     }else{
         document.getElementById('TxtObservaciones').style.backgroundColor="";
     }
    var form_data = getInfoForm();
        form_data.append('Accion', 2);
        
        $.ajax({
        //async:false,
        url: './procesadores/tesoreria_pagos.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            if(data=="OK"){
                $('.progress-bar').css('width','20%').attr('aria-valuenow', 20);  
                document.getElementById('LyProgresoCMG').innerHTML="20%";
                
                document.getElementById("DivMensajes").innerHTML="Archivo Subido";
                alertify.success("Archivo subido");
                InsertarRIPSPago(idPago);
            }else{
                alertify.alert("Error "+data);
                document.getElementById("DivMensajes").innerHTML=data;
                
            }
                      
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
        
       
}


function InsertarRIPSPago(idPago){
   
    var TipoGiro = document.getElementById('CmbTipoGiro').value;
    var form_data = new FormData();
        form_data.append('Accion', 3);
        form_data.append('CmbTipoGiro', TipoGiro);
        form_data.append('idPago', idPago);
        //console.log("Tipo Giro"+TipoGiro)
        $.ajax({
        //async:false,
        url: './procesadores/tesoreria_pagos.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';');
            if(respuestas[0]=="OK"){
                var ValorPagos=respuestas[1];
                $('.progress-bar').css('width','40%').attr('aria-valuenow', 40);  
                document.getElementById('LyProgresoCMG').innerHTML="40%";
                document.getElementById("DivMensajes").innerHTML="Registros del archivo insertados en la tabla temporal";
                alertify.success("Registros insertados en la tabla de pagos");
                EncuentreFacturasPagasConDiferencia(idPago,ValorPagos);
            }else{
                alertify.alert("Error "+data);
                document.getElementById("DivMensajes").innerHTML=data;
                
            }
           
                       
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
        
       
}
    
    

function EncuentreFacturasPagasConDiferencia(idPago,ValorPagos){
    var form_data = new FormData();
        form_data.append('Accion', 4);
        
        $.ajax({
        //async:false,
        url: './procesadores/tesoreria_pagos.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            if(data=="OK"){
                $('.progress-bar').css('width','60%').attr('aria-valuenow', 60);  
                document.getElementById('LyProgresoCMG').innerHTML="60%";
                document.getElementById("DivMensajes").innerHTML="Facturas Pagas con diferencia analizadas";
                alertify.success("Facturas Pagas con diferencia analizadas");
                EncuentreFacturasPagas(idPago,ValorPagos);
            }else{
                alertify.alert("Error "+data);
                document.getElementById("DivMensajes").innerHTML=data;
                
            }
           
            
              
            
                //document.getElementById("GifProcess").innerHTML="";
                //document.getElementById('BtnSubirZip').disabled=false;
                
                  //BorrarCarga();
           
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
        
       
}


function EncuentreFacturasPagas(idPago,ValorPagos){
    var form_data = new FormData();
        form_data.append('Accion', 5);
        
        $.ajax({
        //async:false,
        url: './procesadores/tesoreria_pagos.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            if(data=="OK"){
                $('.progress-bar').css('width','100%').attr('aria-valuenow', 100);  
                document.getElementById('LyProgresoCMG').innerHTML="100%";
                document.getElementById("DivMensajes").innerHTML="Facturas Pagas analizadas";
                alertify.success("Facturas Pagas analizadas");
                ActualiceObservaciones(idPago,ValorPagos);
            }else{
                alertify.alert("Error "+data);
                document.getElementById("DivMensajes").innerHTML=data;
                
            }
           
           
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
        
       
}

function ActualiceObservaciones(idPago,ValorPagos){
    var Observaciones=document.getElementById('TxtObservaciones').value;
    var idPago=document.getElementById('idPago').value;
    var form_data = new FormData();
        form_data.append('Accion', 6);
        form_data.append('Observaciones', Observaciones);
        form_data.append('idPago', idPago);
        form_data.append('ValorPagos', ValorPagos);
        
        $.ajax({
        //async:false,
        url: './procesadores/tesoreria_pagos.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            if(data=="OK"){
                ListarPagos();
                alertify.success("Pagos Actualizados");
            }else{
                alertify.alert("Error "+data);
                document.getElementById("DivMensajes").innerHTML=data;
                
            }
           
           
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
        
       
}


function CambiePagina(Page=""){
    
    if(Page==""){
        if(document.getElementById('CmbPage')){
            Page = document.getElementById('CmbPage').value;
        }else{
            Page=1;
        }
    }
    ListarPagos(Page);
}


function AbreFormularioEditarPago(idPago){
    document.getElementById("DivDrawTables").innerHTML='<div id="GifProcess">procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
    
    var form_data = new FormData();
        form_data.append('Accion', 4);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('idPago', idPago);
       $.ajax({// se arma un objecto por medio de ajax  
        url: 'Consultas/tesoreria_pagos.draw.php',// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById('DivDrawTables').innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos    
            $('#CmbEps').select2({
		  
                placeholder: 'Selecciona una EPS',
                ajax: {
                  url: 'buscadores/salud_eps.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {
                      
                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
            
            
             },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            document.getElementById('DivDrawTables').innerHTML="hay un problema!";
            alert(xhr.status);
            alert(thrownError);
          }
      });
}


function ConfirmaEditarPago(idPago){
    alertify.confirm('Está seguro que desea Editar el Pago No '+idPago+'?',
        function (e) {
            if (e) {
                
                EditarPago(idPago);
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

function EditarPago(idPago){
    var idDivMensajes='DivMensajes';
    var idBoton='BtnGuardar';
    document.getElementById(idBoton).disabled=true;
    var Fecha=document.getElementById("Fecha").value;    
    var CmbEps=document.getElementById("CmbEps").value;    
    var CmbBanco=document.getElementById("CmbBanco").value;    
    var NumeroTransaccion=document.getElementById("NumeroTransaccion").value;  
    var CmbTipoPago=document.getElementById("CmbTipoPago").value; 
    
    var ValorTransaccion=document.getElementById("ValorTransaccion").value;
    var Observaciones=document.getElementById("Observaciones").value;
    
    
    var form_data = new FormData();
        form_data.append('Accion', '7'); 
        form_data.append('Fecha', Fecha);
        form_data.append('CmbEps', CmbEps);
        form_data.append('CmbBanco', CmbBanco);
        form_data.append('NumeroTransaccion', NumeroTransaccion);
        form_data.append('CmbTipoPago', CmbTipoPago);
        form_data.append('ValorTransaccion', ValorTransaccion);
        form_data.append('Observaciones', Observaciones);        
        form_data.append('idPago', idPago);        
        
        
        $.ajax({
        url: './procesadores/tesoreria_pagos.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){
                
                alertify.success(respuestas[1]);
                ListarPagos();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                document.getElementById(idBoton).disabled=false;
            }else{
                document.getElementById(idDivMensajes).innerHTML=data;
                
            }
            document.getElementById(idBoton).disabled=false;         
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;   
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
    }
    
ListarPagos();