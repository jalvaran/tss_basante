/*
 * Archivo JS que se encargará de realizar las consultas, validaciones y envío  de la informacion
 * correspondiente a la carga de RIPS de Forma Manual
 */
/**
 * 
 * Funcion para validar si existe una cuenta RIPS
 */
var ErroresArchivos=0;
function ValidaCuentaRIPS(){
    
    var CuentaRIPS = document.getElementById('CuentaRIPS').value;
    var DivDestino =  'DivConsultas';
    var form_data = new FormData();
        form_data.append('idValidacion', 1)
        form_data.append('CuentaRIPS', CuentaRIPS)
                
        $.ajax({
        
        url: 'Consultas/Validaciones.php',
        dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{
            //console.log(data);
            
            if(data.msg==='OK'){
                
                document.getElementById('BtnSubirZip').disabled=false;
                                    
            }
            
            if(data.msg==='Error'){
                alertify.alert("El numero de Cuenta ya Existe!");
                document.getElementById('BtnSubirZip').disabled=true;
            }
            
        },
        error: function(xhr, ajaxOptions, thrownError){
          alert(xhr.status);
          alert(thrownError);
        }
      })
        
}
/**
 * 
 * Captura la informacion del formulario
 */
function getInfoForm(){
          
    var form_data = new FormData();
    form_data.append('idEPS', $('#idEPS').val());
    form_data.append('CuentaRIPS', $('#CuentaRIPS').val());
    form_data.append('FechaRadicado', $('#FechaRadicado').val());
    form_data.append('NumeroRadicado', $('#NumeroRadicado').val());
    form_data.append('CuentaGlobal', $('#CuentaGlobal').val());
    form_data.append('CmbEscenario', $('#CmbEscenario').val());
    form_data.append('CmbSeparador', $('#CmbSeparador').val());
    form_data.append('CmbTipoNegociacion', $('#CmbTipoNegociacion').val());    
    form_data.append('UpSoporteRadicado', $('#UpSoporteRadicado').prop('files')[0]);
    form_data.append('ArchivosZip', $('#ArchivosZip').prop('files')[0]);
    return form_data;
}
/**
 * Envía la informacion 
 * @param {type} event
 * @returns {undefined}
 */
function submitInfo(event){
  document.getElementById('BtnSubirZip').disabled=true; 
  if($('#idEPS').val()=='' || $('#CuentaRIPS').val()=='' || $('#FechaRadicado').val()=='' || $('#NumeroRadicado').val()=='' || $('#CmbTipoNegociacion').val()=='' || $('#ArchivosZip').val()==''){
        alertify.alert("Los campos indicados con * son obligatorios");
        return;
  }  
  //event.preventDefault();
  var form_data = getInfoForm();
      form_data.append('idAccion', 1);
      
  $.ajax({
    url: './Consultas/Salud_SubirRips.info.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        //console.log(data);
        document.getElementById("DivConsultas").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';
        if(data.CT == 0){
           
            alertify.error("No se recibió ningún archivo CT",0);
            return;
            
        }
        if(data.ErrorFecha){
            document.getElementById("DivConsultas").innerHTML="";
            alertify.alert("La Fecha de Radicado es mayor a la Actual, por favor selecciona otra fecha");
            return;         
        }
        if(data.msg==='ErrorCuentaRIPS'){ //Si no es igual la cuenta rips escrita con el del archivo
            document.getElementById("DivConsultas").innerHTML="";
            alertify.alert("El numero de Cuenta RIP digitado no es igual del del CT: "+data.CuentaRIPSCT);
            return;            
        }
        
        VerificarCT(data.Separador); //Se verifica que el CT contenga todos los archivos enviados
        ErroresArchivos=document.getElementById("Parar").value;
        
        if(ErroresArchivos==1){
            document.getElementById("Parar").value=0;
            return;
        }
        
        for(i=0;i<data.Archivos.length;i++){ //Guarda los archivos en las tablas temporales
            var prefijo = data.Archivos[i].substr(0,2);
            
            if(prefijo!="CT" && prefijo!="AD"){

                GrabarArchivoEnTemporal(data.Archivos[i],0);
            }
           
            
        }
        
        ErroresArchivos=document.getElementById("Parar").value;
        
        if(ErroresArchivos==1){
            document.getElementById("Parar").value=0;
            return;
        }
        
        //Se valida Si una factura ya está cargada y si es así para y muestra cuales
        
        VerificaDuplicados();
        ErroresArchivos=document.getElementById("Parar").value;
        
        if(ErroresArchivos==1){
            document.getElementById("Parar").value=0;
            return;
        }
        
        //Se valida Si una factura está duplicada y devuelta dependiendo de la respuesta del usuario se reescribirá o no
        
        VerificaDevoluciones();
        ErroresArchivos=document.getElementById("Parar").value;
        
        if(ErroresArchivos==1){
            document.getElementById("Parar").value=0;
            return;
        }
        
        //Se va a enviar para guardar en el repositorio final
        for(i=0;i<data.Archivos.length;i++){
            var prefijo = data.Archivos[i].substr(0,2);
                
            if((data.Archivos.length-1)==i){
                
                AnalizaArchivos(data.Archivos[i],1);
                  
            }else{
                if(prefijo!="CT" && prefijo!="AD"){
                    
                    AnalizaArchivos(data.Archivos[i],0);
                }
            }
            
            
        }
        
    },
    error: function (xhr, ajaxOptions, thrownError) {
        console.log(thrownError);
        alert(xhr.status);
        alert(JSON.parse(thrownError));
      }
  })
}

/**
 * 
 * Verifica los archivos que tenga el CT
 */
function VerificarCT(Separador){
    
    var form_data = new FormData();
    form_data.append('idAccion', 2);
    form_data.append('Separador', Separador);
    form_data.append('CuentaRIPS', $('#CuentaRIPS').val());
    $.ajax({
    async:false,
    url: './Consultas/Salud_SubirRips.info.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data.Errores>0){
            for(i=1;i<=data.Errores;i++){
                
                alertify.error("El Archivo "+data.ArchivosNE[i]+" No Existe en los Archivos cargados",0);
            }
        document.getElementById("Parar").value=1; 
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
  
}

/**
 * 
 * Modificar Autoincrementables
 */
function ModificaAI(){
    
    var form_data = new FormData();
    form_data.append('idAccion', 5);
    
    $.ajax({
    url: './Consultas/Salud_SubirRips.info.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        
        if(data.msg==="OK"){        
            alertify.success("Los autoincrementables se han modificado");
        }    
        
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
  
}

/**
 * 
 * Enviar archivo para grabarlo en temporal
 */
function GrabarArchivoEnTemporal(Archivo,Fin){
    
    var form_data = getInfoForm();
    form_data.append('idAccion', 3);
    form_data.append('NombreArchivo', Archivo);
    $.ajax({
    async:false,
    url: './Consultas/Salud_SubirRips.info.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data.msg==='OK'){
            alertify.success("Archivo "+Archivo+" sin errores");     
            document.getElementById("DivConsultas").innerHTML=document.getElementById("DivConsultas").innerHTML+"<li>Archivo "+Archivo+" Subido a tabla Temporal";
            if(Fin===1){
                document.getElementById("GifProcess").innerHTML="";
                document.getElementById('BtnSubirZip').disabled=false;
            }
        }
        if(data.msg==='Error'){
            document.getElementById("Parar").value=1;
            document.getElementById("GifProcess").innerHTML="";
            document.getElementById('BtnSubirZip').disabled=false;
            document.getElementById("DivConsultas").innerHTML=document.getElementById("DivConsultas").innerHTML+"<li>Archivo "+Archivo+" Contiene errores";
           
            if(data.Error.Pos===1){
                EliminarCarga();
                alertify.error("La Prestadora No Existe - Error en las lineas: "+data.Error.Lines,0);
            }
            if(data.Error.Pos===9){
                EliminarCarga();
                alertify.error("La Aseguradora No Coincide- Error en las lineas: "+data.Error.Lines,0);
            }
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
  
}

/**
 * 
 * Analiza Archivos para Subirlos a los repositorios reales
 */
function VerificaDuplicados(){
    
    var form_data = new FormData();
    form_data.append('idAccion', 6);
    
    $.ajax({
    async:false,
    url: './Consultas/Salud_SubirRips.info.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data.msg==="OK"){
            alertify.success("No hay facturas Duplicadas");
        }
        if(data.msg==="Error"){
            document.getElementById("Parar").value=1;
            document.getElementById("GifProcess").innerHTML="";
            document.getElementById('BtnSubirZip').disabled=false;
            alertify.error("Hay Facturas Duplicadas",0);
            document.getElementById("DivConsultas").innerHTML="<h2><strong>Hay Facturas Duplicadas, no puede continuar <a href='vista_af_duplicados.php' target='_BLANK'>ver</a></strong><h2>";
            
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
  
}


/**
 * 
 * Analiza Archivos para Subirlos a los repositorios reales
 */
function AnalizaArchivos(Archivo,Fin){
    
    var form_data = new FormData();
    form_data.append('idAccion', 4);
    form_data.append('Archivo', Archivo);
    $.ajax({
    async:false,
    url: './Consultas/Salud_SubirRips.info.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data.msg==="OK"){
            document.getElementById("DivConsultas").innerHTML=document.getElementById("DivConsultas").innerHTML+"<li>Archivo "+Archivo+" Guardado Correctamente";
            if(Fin===1){
                ModificaAI();
                document.getElementById("GifProcess").innerHTML="";
                document.getElementById('BtnSubirZip').disabled=false;
                document.getElementById("Parar").value=0;
                document.getElementById("DivConsultas").innerHTML="<h3><strong>Archivos Subidos y verificados correctamente</strong></h3>";
            }
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
  
}
//Verifica devoluciones
function VerificaDevoluciones(){
    
    var form_data = new FormData();
    form_data.append('idValidacion', 2);//hay devoluciones duplicadas?    
    $.ajax({
    async:false,
    url: './Consultas/Validaciones.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data.msg==="SI"){
            alertify.set({ labels: {
                ok     : "Actualizar",
                cancel : "No Cargar"
            } });
            alertify.confirm("Hay Facturas Duplicadas en Estado de Devolucion desea actualizarlas?,<br> <a href='vista_af_devueltos.php' target='_blank'>VER RELACION.</a><br> <strong>NOTA: Esta acción es irreversible. <strong>",
            function (e) {
                if (e) {
                    document.getElementById("Parar").value=0;
                    ActualizarFacturasDevueltas();
                     
                } else {
                    alertify.error("Se canceló la actualización de las facturas devueltas previamente, no puede continuar");
                    document.getElementById("Parar").value=1;
                    document.getElementById("GifProcess").innerHTML="";
                    document.getElementById('BtnSubirZip').disabled=false;
                    document.getElementById("DivConsultas").innerHTML="<h2>PROCESO DE CARGA CANCELADO</h2>";
           
                }
            });
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        document.getElementById("Parar").value=1;
        alert(xhr.status);
        alert(thrownError);
      }
  })
    
}
//Se actualizan las facturas devueltas por orden del usuario
function ActualizarFacturasDevueltas(){
    var form_data = new FormData();
    form_data.append('idValidacion', 3);//hay devoluciones duplicadas?    
    $.ajax({
    async:false,
    url: './Consultas/Validaciones.php',
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data.msg==="OK"){
            document.getElementById("DivConsultas").innerHTML=document.getElementById("DivConsultas").innerHTML+"<li><strong>Facturas en estado de devolucion Actualizadas</strong>";
            alertify.success("Se actualizaron las facturas devueltas previamente",0); 
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        document.getElementById("Parar").value=1;
        alert(xhr.status);
        alert(thrownError);
      }
  })
}

function EliminarCarga(){
    var form_data = new FormData();
       form_data.append('idValidacion', 3);//hay devoluciones duplicadas?    
       $.ajax({
       async:false,
       url: './Consultas/Salud_SubirRips.info.php',
       dataType: 'json',
       cache: false,
       contentType: false,
       processData: false,
       data: form_data,
       type: 'post',
       success: function(data){
           if(data.msg==="OK"){
               document.getElementById("DivConsultas").innerHTML=document.getElementById("DivConsultas").innerHTML+"<li><strong>Facturas en estado de devolucion Actualizadas</strong>";
               alertify.success("Se actualizaron las facturas devueltas previamente",0); 
           }

       },
       error: function (xhr, ajaxOptions, thrownError) {
           document.getElementById("Parar").value=1;
           alert(xhr.status);
           alert(thrownError);
         }
     })   
}