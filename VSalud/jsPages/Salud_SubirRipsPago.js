/*
 * Archivo JS que se encargará de realizar las consultas, validaciones y envío  de la informacion
 * correspondiente a la carga de RIPS de pago de Forma Manual
 */
/**
 * 
 * Funcion para validar si existe una cuenta RIPS
 */

/**
 * 
 * Captura la informacion del formulario
 */
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
 * Envia la peticion para que sea cargado el AR
 * @returns {undefined}
 */
function CargarAR(){
    
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
    var form_data = getInfoForm();
        form_data.append('idAccion', 1);
        
        $.ajax({
        //async:false,
        url: './procesadores/Salud_SubirRipsPago.process.php',
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
                InsertarRIPSPago();
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

function InsertarRIPSPago(){
    var TipoGiro = document.getElementById('CmbTipoGiro').value;
    var form_data = new FormData();
        form_data.append('idAccion', 2);
        form_data.append('CmbTipoGiro', TipoGiro);
        //console.log("Tipo Giro"+TipoGiro)
        $.ajax({
        //async:false,
        url: './procesadores/Salud_SubirRipsPago.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
            if(data=="OK"){
                $('.progress-bar').css('width','40%').attr('aria-valuenow', 40);  
                document.getElementById('LyProgresoCMG').innerHTML="40%";
                document.getElementById("DivMensajes").innerHTML="Registros del archivo insertados en la tabla temporal";
                alertify.success("Registros insertados en la tabla de pagos");
                EncuentreFacturasPagasConDiferencia();
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


function EncuentreFacturasPagasConDiferencia(){
    var form_data = new FormData();
        form_data.append('idAccion', 3);
        
        $.ajax({
        //async:false,
        url: './procesadores/Salud_SubirRipsPago.process.php',
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
                EncuentreFacturasPagas();
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


function EncuentreFacturasPagas(){
    var form_data = new FormData();
        form_data.append('idAccion', 4);
        
        $.ajax({
        //async:false,
        url: './procesadores/Salud_SubirRipsPago.process.php',
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
                //EncuentreFacturasPagas();
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
