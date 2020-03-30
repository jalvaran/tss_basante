/*
 * Archivo JS que se encargará de realizar las consultas, validaciones y envío  de la informacion
 * correspondiente a la carga de RIPS de Forma Manual
 */
/**
 * 
 * Funcion para validar si existe una cuenta RIPS
 */
document.getElementById("ArchivosZip").addEventListener("change", ValidaCuentaRIPS); 
var ErroresArchivos=0;
var ValorCapita=0;
function ValidaCuentaRIPS(){
    
    var CuentaRIPS = document.getElementById('CuentaRIPS').value;
    var DivDestino =  'DivConsultas';
    var form_data = new FormData();
        form_data.append('idAccion', 1)
        form_data.append('CuentaRIPS', CuentaRIPS)
                
        $.ajax({
        //async:false,
        url: 'Consultas/Salud_SubirRips.process.php',
        //dataType: "json",
        cache: false,
        processData: false,
        contentType: false,
        data: form_data,
        type: 'POST',
        success: (data) =>{
                        
            if(data==='OK'){
                
                document.getElementById('BtnSubirZip').disabled=false;
                                    
            }
            
            if(data==='Error'){
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
    form_data.append('CmbCuentaContable', $('#CmbCuentaContable').val()); 
    
    return form_data;
}
/**
 * Enviar El  .zip
 * @returns {undefined}
 */
function EnviarZIP(){
    
    document.getElementById('BtnSubirZip').disabled=true; 
    
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';

    if($('#idEPS').val()=='' || $('#CuentaRIPS').val()=='' || $('#FechaRadicado').val()=='' || $('#NumeroRadicado').val()=='' || $('#CmbTipoNegociacion').val()=='' || $('#ArchivosZip').val()==''){
          alertify.alert("Los campos indicados con * son obligatorios");
          BorrarCarga();
          if($('#idEPS').val()==''){
            document.getElementById('BtnSubirZip').disabled=false; 
          }
          return;
    } 
    
    if($('#CmbCuentaContable').val()==''){
        console.log("Entra a validacion")
        var r = confirm("Está seguro que no desea enviar una cuenta contable?");
        if(r==false){
            document.getElementById('BtnSubirZip').disabled=false; 
            alertify.error("Proceso cancelado");
            return;
        }
    }
    
    var form_data = new FormData();
        form_data.append('idAccion', 2);
        form_data.append('ArchivosZip', $('#ArchivosZip').prop('files')[0]);
      
    $.ajax({
        async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','4%').attr('aria-valuenow', 4);  
                document.getElementById('LyProgresoCMG').innerHTML="4%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>El Archivo Fue Enviado y Extraido</h4>";
                VerificarCT();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Borra la ultima carga realizada
 * @returns {undefined}
 */
function BorrarCarga(){
    var form_data = new FormData();
    form_data.append('idAccion',3);
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            alertify.error("Temporales borrados"); 
            document.getElementById('DivProcess').innerHTML='';
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Verifica Si existe el CT y Si se subieron los archivos allí descritos
 * @returns {undefined}
 */
function VerificarCT(){
    
    var form_data = new FormData();
        form_data.append('idAccion', 4);
        form_data.append('CuentaRIPS', $('#CuentaRIPS').val());
      
    $.ajax({
        async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data=="OK"){
                $('.progress-bar').css('width','8%').attr('aria-valuenow', 8);  
                document.getElementById('LyProgresoCMG').innerHTML="8%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Archivo CT Verificado</h4>";
                VerificaArchivosRelacionadosCT();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Verifica si los archivos relacionados en el CT fueron Subidos
 * @returns {undefined}
 */
function VerificaArchivosRelacionadosCT(){
    var form_data = new FormData();
        form_data.append('idAccion', 5);
        form_data.append('CmbSeparador', $('#CmbSeparador').val());
      
    $.ajax({
        async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','12%').attr('aria-valuenow', 12);  
                document.getElementById('LyProgresoCMG').innerHTML="12%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Archivos Relacionados en CT listos para Guardar en Temporal</h4>";
                CargarAF();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Envia la peticion para que sea cargado el AF
 * @returns {undefined}
 */
function CargarAF(){
    
    var form_data = getInfoForm();
        form_data.append('idAccion', 6);
        form_data.append('UpSoporteRadicado', $('#UpSoporteRadicado').prop('files')[0]);
        
        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                httpEdicion = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                httpEdicion = new ActiveXObject("Microsoft.XMLHTTP");
            }
            httpEdicion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data=this.responseText;
                    if(data=="OK"){
                        $('.progress-bar').css('width','16%').attr('aria-valuenow', 16);  
                        document.getElementById('LyProgresoCMG').innerHTML="16%";
                        document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AF Subidos a la Tabla Temporal</h4>";
                        VerificaValorCuenta();

                    }else{
                        document.getElementById("DivProcess").innerHTML='';
                        document.getElementById("DivConsultas").innerHTML=data;
                        BorrarCarga();
                        document.getElementById('BtnSubirZip').disabled=false;
                    }
                    
                }else{
                    document.getElementById("DivConsultas").innerHTML=this.responseText;
                }
            };
        
        httpEdicion.open("POST",'./Consultas/Salud_SubirRips.process.php',true);
        httpEdicion.send(form_data);
        
   
}


/**
 * Envia la peticion para que sea cargado el AF
 * @returns {undefined}
 */
function VerificaValorCuenta(){
   // var TipoNegociacion=$('#CmbTipoNegociacion').value();
    var TipoNegociacion=document.getElementById('CmbTipoNegociacion').value;
    var form_data = getInfoForm();
        form_data.append('idAccion', 28);
        form_data.append('UpSoporteRadicado', $('#UpSoporteRadicado').prop('files')[0]);
        
        if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                httpEdicion = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                httpEdicion = new ActiveXObject("Microsoft.XMLHTTP");
            }
            httpEdicion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data=this.responseText;
                    var respuestas = data.split(';');
                    if(respuestas[0]=="OK"){
                        
                        var ValorCuenta = respuestas[1];
                        alertify.set({ labels: {
                            ok     : "Continuar",
                            cancel : "Cancelar Subida"
                        } });
                        if(TipoNegociacion=='evento'){    
                            alertify.confirm("Esta Cuenta está por valor de: "+ new Intl.NumberFormat().format(ValorCuenta) +", Desea continuar?",
                            function (e) {
                                if (e) {
                                    
                                    VerificaDuplicados();                    

                                } else {
                                    alertify.error("Se canceló el proceso");

                                    document.getElementById("GifProcess").innerHTML="";
                                    document.getElementById('BtnSubirZip').disabled=false;
                                    document.getElementById("DivConsultas").innerHTML="<h2>PROCESO DE CARGA CANCELADO</h2>";
                                    BorrarCarga();
                                    return;
                                }
                            });
                        }else{
                            
                            alertify.prompt("Esta Cuenta está por valor de: "+ new Intl.NumberFormat().format(ValorCuenta) +", para continuar por favor digite el valor por el cual se facturó esta capita: "
                            , function (e, str) {
                            if (e) {
                                    if (str != '') {
                                        if(!$.isNumeric(str) || str <= 0){
                                            alertify.alert("El valor ingresado debe ser un número mayor a Cero");
                                            document.getElementById("GifProcess").innerHTML="";
                                            document.getElementById('BtnSubirZip').disabled=false;
                                            document.getElementById("DivConsultas").innerHTML="<h2>PROCESO DE CARGA CANCELADO</h2>";
                                            BorrarCarga();
                                            return;
                                        }
                                        
                                        var ValorIngresado= new Intl.NumberFormat().format(str);
                                        alertify.confirm("El valor digitado es: "+ ValorIngresado +", Desea continuar?",
                                        function (e) {
                                            if (e) {
                                                ValorCapita=str;
                                                VerificaDuplicados();                   

                                            } else {
                                                alertify.error("Se canceló el proceso");

                                                document.getElementById("GifProcess").innerHTML="";
                                                document.getElementById('BtnSubirZip').disabled=false;
                                                document.getElementById("DivConsultas").innerHTML="<h2>PROCESO DE CARGA CANCELADO</h2>";
                                                BorrarCarga();
                                                return;
                                            }
                                        });
                                        
                                    
                                    }else{
                                       alertify.alert("Debes Escribir una observacion");
                                       document.getElementById("GifProcess").innerHTML="";
                                        document.getElementById('BtnSubirZip').disabled=false;
                                        document.getElementById("DivConsultas").innerHTML="<h2>PROCESO DE CARGA CANCELADO</h2>";
                                        BorrarCarga();
                                        return;
                                    }
                                    //alertify.success("You've clicked OK and typed: " + str);
                            } else {
                                    alertify.error("Se canceló el proceso");

                                    document.getElementById("GifProcess").innerHTML="";
                                    document.getElementById('BtnSubirZip').disabled=false;
                                    document.getElementById("DivConsultas").innerHTML="<h2>PROCESO DE CARGA CANCELADO</h2>";
                                    BorrarCarga();
                                    return;
                            }
                            }, "");
                            
                            
                        }

                    }else{
                        document.getElementById("DivProcess").innerHTML='';
                        document.getElementById("DivConsultas").innerHTML=data;
                        BorrarCarga();
                        document.getElementById('BtnSubirZip').disabled=false;
                    }
                    
                }else{
                    document.getElementById("DivConsultas").innerHTML=this.responseText;
                }
            };
        
        httpEdicion.open("POST",'./Consultas/Salud_SubirRips.process.php',true);
        httpEdicion.send(form_data);
        
   
}


/**
 * Verifica si hay facturas Duplicadas
 * @returns {undefined}
 */
function VerificaDuplicados(){
    
    var form_data = new FormData();
    form_data.append('idAccion', 25);
    
    $.ajax({
    //async:false,
    url: './Consultas/Salud_SubirRips.process.php',
    //dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data==="OK"){
            alertify.success("No hay facturas Duplicadas");
            VerificaDevoluciones();
        }else if(data==="Error"){
            
            document.getElementById("GifProcess").innerHTML="";
            document.getElementById('BtnSubirZip').disabled=false;
            alertify.error("Hay Facturas Duplicadas",0);
            document.getElementById("DivConsultas").innerHTML="<h2 style='color:red'><strong>Hay Facturas Duplicadas, no puede continuar <a href='vista_af_duplicados.php' target='_BLANK'>ver</a></strong><h2>";
            //BorrarCarga();
        }else{
            document.getElementById("DivProcess").innerHTML='';
            document.getElementById("DivConsultas").innerHTML=data;
            BorrarCarga();
            document.getElementById('BtnSubirZip').disabled=false;
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
    form_data.append('idAccion', 26);//hay devoluciones duplicadas?    
    $.ajax({
    //async:false,
    url: './Consultas/Salud_SubirRips.process.php',
    //dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data==="SI"){
            alertify.set({ labels: {
                ok     : "Actualizar",
                cancel : "No Cargar"
            } });
            alertify.confirm("Hay Facturas Duplicadas en Estado de Devolucion desea actualizarlas?,<br> <a href='vista_af_devueltos.php' target='_blank'>VER RELACION.</a><br> <strong>NOTA: Esta acción es irreversible. <strong>",
            function (e) {
                if (e) {
                    
                    ActualizarFacturasDevueltas();                    
                     
                } else {
                    alertify.error("Se canceló la actualización de las facturas devueltas previamente, no puede continuar");
                    
                    document.getElementById("GifProcess").innerHTML="";
                    document.getElementById('BtnSubirZip').disabled=false;
                    document.getElementById("DivConsultas").innerHTML="<h2>PROCESO DE CARGA CANCELADO</h2>";
                    BorrarCarga();
                }
            });
        }else if(data==="NO"){
            alertify.success("No hay duplicados en estado devolución");
            CargarAC();
        }else{
            document.getElementById("DivProcess").innerHTML='';
            document.getElementById("DivConsultas").innerHTML=data;
            BorrarCarga();
            document.getElementById('BtnSubirZip').disabled=false;
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        
        alert(xhr.status);
        alert(thrownError);
      }
  })
    
}

/**
 * Actualiza las Facturas Devueltas
 * @returns {undefined}
 */
function ActualizarFacturasDevueltas(){
    
    var form_data = new FormData();
    form_data.append('idAccion', 27);
    
    $.ajax({
    //async:false,
    url: './Consultas/Salud_SubirRips.process.php',
    //dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        if(data==="OK"){
            alertify.success("Facturas en estado de devolucion Actualizadas");
            CargarAC();
        }else{
            document.getElementById("DivProcess").innerHTML='';
            document.getElementById("DivConsultas").innerHTML=data;
            BorrarCarga();
            document.getElementById('BtnSubirZip').disabled=false;
        }
             
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
  
}
/**
 * Carga los AC en la temporal
 * @returns {undefined}
 */
function CargarAC(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 7);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','20%').attr('aria-valuenow', 20);  
                document.getElementById('LyProgresoCMG').innerHTML="20%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AC Subidos a la Tabla Temporal</h4>";
                CargarAP();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Carga los AP al temporal
 * @returns {undefined}
 */
function CargarAP(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 8);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','24%').attr('aria-valuenow', 24);  
                document.getElementById('LyProgresoCMG').innerHTML="24%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AP Subidos a la Tabla Temporal</h4>";
                CargarAT();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Carga los AT al temporal
 * @returns {undefined}
 */
function CargarAT(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 9);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','28%').attr('aria-valuenow', 28);  
                document.getElementById('LyProgresoCMG').innerHTML="28%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AT Subidos a la Tabla Temporal</h4>";
                CargarAM();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Carga los AT al temporal
 * @returns {undefined}
 */
function CargarAM(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 10);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','32%').attr('aria-valuenow', 32);  
                document.getElementById('LyProgresoCMG').innerHTML="32%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AM Subidos a la Tabla Temporal</h4>";
                CargarAH();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Carga los AH al temporal
 * @returns {undefined}
 */
function CargarAH(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 11);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','36%').attr('aria-valuenow', 36);  
                document.getElementById('LyProgresoCMG').innerHTML="36%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AH Subidos a la Tabla Temporal</h4>";
                CargarUS();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Carga los US al temporal
 * @returns {undefined}
 */
function CargarUS(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 12);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','40%').attr('aria-valuenow', 40);  
                document.getElementById('LyProgresoCMG').innerHTML="40%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>US Subidos a la Tabla Temporal</h4>";
                CargarAN();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Carga los US al temporal
 * @returns {undefined}
 */
function CargarAN(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 13);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','44%').attr('aria-valuenow', 44);  
                document.getElementById('LyProgresoCMG').innerHTML="44%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AN Subidos a la Tabla Temporal</h4>";
                CargarAU();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Carga los AU al temporal
 * @returns {undefined}
 */
function CargarAU(){
    var form_data = getInfoForm();
        form_data.append('idAccion', 14);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','48%').attr('aria-valuenow', 48);  
                document.getElementById('LyProgresoCMG').innerHTML="48%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AU Subidos a la Tabla Temporal</h4>";
                AnalizarAF();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * Analizar los AF 
 * @returns {undefined}
 */
function AnalizarAF(){
    
    var CuentaGlobal=document.getElementById('CuentaGlobal').value;

    var form_data = new FormData();
        form_data.append('idAccion', 15);
        form_data.append('CuentaGlobal', CuentaGlobal);  
        form_data.append('ValorCapita', ValorCapita);  
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','52%').attr('aria-valuenow', 52);  
                document.getElementById('LyProgresoCMG').innerHTML="52%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AF Analizados y Guardados</h4>";
                AnalizarAC();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los AC 
 * @returns {undefined}
 */
function AnalizarAC(){
    var form_data = new FormData();
        form_data.append('idAccion', 16);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','56%').attr('aria-valuenow', 56);  
                document.getElementById('LyProgresoCMG').innerHTML="56%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AC Analizados y Guardados</h4>";
                AnalizarAP();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los AP
 * @returns {undefined}
 */
function AnalizarAP(){
    var form_data = new FormData();
        form_data.append('idAccion', 17);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','60%').attr('aria-valuenow', 60);  
                document.getElementById('LyProgresoCMG').innerHTML="60%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AP Analizados y Guardados</h4>";
                AnalizarAM();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los AM
 * @returns {undefined}
 */
function AnalizarAM(){
    var form_data = new FormData();
        form_data.append('idAccion', 18);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','64%').attr('aria-valuenow', 64);  
                document.getElementById('LyProgresoCMG').innerHTML="64%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AM Analizados y Guardados</h4>";
                AnalizarAT();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los AT
 * @returns {undefined}
 */
function AnalizarAT(){
    var form_data = new FormData();
        form_data.append('idAccion', 19);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','68%').attr('aria-valuenow', 68);  
                document.getElementById('LyProgresoCMG').innerHTML="68%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AT Analizados y Guardados</h4>";
                AnalizarAH();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los AH
 * @returns {undefined}
 */
function AnalizarAH(){
    var form_data = new FormData();
        form_data.append('idAccion', 20);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','72%').attr('aria-valuenow', 72);  
                document.getElementById('LyProgresoCMG').innerHTML="72%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AH Analizados y Guardados</h4>";
                AnalizarUS();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los US
 * @returns {undefined}
 */
function AnalizarUS(){
    var form_data = new FormData();
        form_data.append('idAccion', 21);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','76%').attr('aria-valuenow', 76);  
                document.getElementById('LyProgresoCMG').innerHTML="76%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>US Analizados y Guardados</h4>";
                AnalizarAN();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los AN
 * @returns {undefined}
 */
function AnalizarAN(){
    var form_data = new FormData();
        form_data.append('idAccion', 22);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','80%').attr('aria-valuenow', 80);  
                document.getElementById('LyProgresoCMG').innerHTML="80%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AN Analizados y Guardados</h4>";
                AnalizarAU();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Analizar los AU
 * @returns {undefined}
 */
function AnalizarAU(){
    var form_data = new FormData();
        form_data.append('idAccion', 23);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','84%').attr('aria-valuenow', 84);  
                document.getElementById('LyProgresoCMG').innerHTML="84%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>AU Analizados y Guardados</h4>";
                ModificaAutoincrementales();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                BorrarCarga();
                document.getElementById('BtnSubirZip').disabled=false;
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * Modifica los AutoIncrementales
 * @returns {undefined}
 */
function ModificaAutoincrementales(){
    var form_data = new FormData();
        form_data.append('idAccion', 24);
              
    $.ajax({
        //async:false,
        url: './Consultas/Salud_SubirRips.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','100%').attr('aria-valuenow', 100);  
                document.getElementById('LyProgresoCMG').innerHTML="100%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Proceso terminado exitósamente!</h4>";
                document.getElementById("UpSoporteRadicado").value="";
                document.getElementById("ArchivosZip").value="";
                document.getElementById("NumeroRadicado").value="";
                document.getElementById("CuentaGlobal").value="";
                document.getElementById("CuentaRIPS").value="";
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById('BtnSubirZip').disabled=false;
                BorrarCarga();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;
                document.getElementById('BtnSubirZip').disabled=false;
                BorrarCarga();
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error al tratar de borrar el archivo",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
