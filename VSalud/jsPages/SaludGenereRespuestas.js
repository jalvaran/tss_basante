/**
 * Controlador para la gestion de respuestas
 * JULIAN ALVARAN 2018-08-03
 * TECHNO SOLUCIONES SAS EN ASOCIACION CON SITIS SA
 * 317 774 0609
 */

/**
 * Cambia el la fecha minima para seleccionar en una fecha final
 * @returns {undefined}
 */
function CambiarFechaFinalRangofacturas(){
    var FechaInicial = document.getElementById("FechaFacturaInicial").value;
    $('#FechaFacturaFinal').attr("min", FechaInicial); 
}
function CambiarFechaInicialRangofacturas(){
    var FechaFinal = document.getElementById("FechaFacturaFinal").value;
    $('#FechaFacturaInicial').attr("max", FechaFinal);
}

function CambiarFechaFinalRangoRadicado(){
    var FechaInicial = document.getElementById("FechaRadicadoInicial").value;
    $('#FechaRadicadoFinal').attr("min", FechaInicial); 
}
function CambiarFechaInicialRangoRadicado(){
    var FechaFinal = document.getElementById("FechaRadicadoFinal").value;
    $('#FechaRadicadoInicial').attr("max", FechaFinal);
}

/**
 * Envia el archivo de carga de glosas masivas al servidor
 * @returns {undefined}
 */
$(document).ready(function() {
    $('#Cuentas').select2({
		  
                placeholder: 'Busque una CuentaRIPS o EPS',
                ajax: {
                  url: './buscadores/CuentaRIPS.querys.php',
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
              
    $('#CmbFacturas').select2({
		  
                placeholder: 'Busque una o varias Facturas',
                ajax: {
                  url: './buscadores/Facturas.querys.php',
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
});
/**
 * Borra la ultima carga en caso de no pasar alguna validacion o que e produzca algun error
 * @returns {undefined}
 */
function BorrarCarga(){
    if($("#GifProcess").length > 0){
        document.getElementById("GifProcess").innerHTML="";
    }
    
    var form_data = new FormData();
        form_data.append('idAccion', 2);
        
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                
                //document.getElementById("DivConsultas").innerHTML="Carga Borrada";
                alertify.error("Carga Borrada");
            }else{
                document.getElementById("DivProcess").innerHTML=data;
                
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
 * Envía la peticion para generar respuestas a las cuentas
 * @returns {undefined}
 */
function EnviarCuentas(){
    document.getElementById("DivConsultas").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';

    if($('#Cuentas').val()==null || $('#Cuentas').val()==''){
          alertify.alert("por favor seleccione una o varias cuentas");          
          return;
    } 
    
    
    var form_data = new FormData();
        form_data.append('idAccion', 1);
        form_data.append('Cuentas', $('#Cuentas').val());
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
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
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Se recibieron las cuentas solicitadas</h4>";
                CrearArchivoRespuestas();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;                
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
/**
 * Envia las facturas para analizarlas
 * @returns {undefined}
 */
function EnviarFacturas(){
    document.getElementById("DivConsultas").innerHTML='';     
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';

    if($('#CmbFacturas').val()==null || $('#CmbFacturas').val()==''){
        alertify.alert("por favor seleccione una o varias facturas");          
        return;
    } 
    
    
    var form_data = new FormData();
        form_data.append('idAccion', 7);
        form_data.append('CmbFacturas', $('#CmbFacturas').val());
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
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
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Se recibieron las facturas solicitadas</h4>";
                CrearArchivoRespuestas();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;                
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

/**
 * Envia las facturas x rango de fecha para analizarlas
 * @returns {undefined}
 */
function EnviarFacturasRangoFecha(){
    
    if($('#idEPS').val()==null || $('#idEPS').val()=='' || $('#FechaFacturaInicial').val()==null || $('#FechaFacturaInicial').val()=='' || $('#FechaFacturaFinal').val()==null || $('#FechaFacturaFinal').val()==''){
        alertify.alert("por favor seleccione una EPS y un rango de fechas valido");          
        return;
    } 
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';
 
    var form_data = new FormData();
        form_data.append('idAccion', 8);
        form_data.append('FechaFacturaInicial', $('#FechaFacturaInicial').val());
        form_data.append('FechaFacturaFinal', $('#FechaFacturaFinal').val());
        form_data.append('idEPS', $('#idEPS').val());
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
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
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Se recibieron las facturas solicitadas</h4>";
                CrearArchivoRespuestas();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;                
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


/**
 * Envia las facturas x rango de fecha para analizarlas
 * @returns {undefined}
 */
function EnviarFacturasRangoFechaRadicado(){
    
    if($('#idEPS').val()==null || $('#idEPS').val()=='' || $('#FechaRadicadoInicial').val()==null || $('#FechaRadicadoInicial').val()=='' || $('#FechaRadicadoFinal').val()==null || $('#FechaRadicadoFinal').val()==''){
        alertify.alert("por favor seleccione una EPS y un rango de fechas valido");          
        return;
    } 
    document.getElementById("DivProcess").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/process.gif" alt="Cargando" height="100" width="100"></div>';
 
    var form_data = new FormData();
        form_data.append('idAccion', 9);
        form_data.append('FechaRadicadoInicial', $('#FechaRadicadoInicial').val());
        form_data.append('FechaRadicadoFinal', $('#FechaRadicadoFinal').val());
        form_data.append('idEPS', $('#idEPS').val());
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
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
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Se recibieron las facturas solicitadas</h4>";
                CrearArchivoRespuestas();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;                
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

/**
 * Crea el archivo donde se van a almacenar todas las respuestas
 * @returns {undefined}
 */
function CrearArchivoRespuestas(){
    var form_data = new FormData();
        form_data.append('idAccion', 3);
        
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
           if(data==="OK"){
                $('.progress-bar').css('width','40%').attr('aria-valuenow',40);  
                document.getElementById('LyProgresoCMG').innerHTML="40%";
                document.getElementById("DivConsultas").innerHTML="<h4 style='color:green'>Archivo de Respuestas Preparado</h4>";
                EscribaRespuestasFacturasEnExcel();
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;                
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
/**
 * Envía la orden para realizar la escritura en el archivo de excel
 * @returns {undefined}
 */
function EscribaRespuestasFacturasEnExcel(){
    var form_data = new FormData();
        form_data.append('idAccion', 4);
        
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
           if(data==="OK"){                
                document.getElementById('DivConsultas').innerHTML="Registrando Facturas en Excel";
                $('.progress-bar').css('width','60%').attr('aria-valuenow', 60);  
                document.getElementById('LyProgresoCMG').innerHTML="60%";                
                VerifiqueSoportes();
            
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;                
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
/**
 * Mira si se solicita con soportes y arma los copia a la respectiva carpeta
 * @returns {undefined}
 */
function VerifiqueSoportes(){
    var Soportes = document.getElementById('CmbSoportes').value;
    
    if(Soportes=='0'){
        ComprimirRespuestas();
        return;
    }
    
    var form_data = new FormData();
        form_data.append('idAccion', 5);
        
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
           if(data==="OK"){
                
                document.getElementById('DivConsultas').innerHTML="Soportes preparados";
                $('.progress-bar').css('width','80%').attr('aria-valuenow', '80');  
                document.getElementById('LyProgresoCMG').innerHTML="80%";                
                ComprimirRespuestas();
            
            }else{
                document.getElementById("DivProcess").innerHTML='';
                document.getElementById("DivConsultas").innerHTML=data;                
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
/**
 * Comprime la carpeta con las respuestas
 * @returns {undefined}
 */
function ComprimirRespuestas(){
    var Soportes = document.getElementById('CmbSoportes').value;
    
    var form_data = new FormData();
        form_data.append('idAccion', 6);
        form_data.append('Soportes', Soportes);
      
    $.ajax({
        //async:false,
        url: './Consultas/SaludGenereRespuestas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                
            //document.getElementById('BtnModalDescargas').click();
            $('.progress-bar').css('width','100%').attr('aria-valuenow', '100');  
            document.getElementById('LyProgresoCMG').innerHTML="100%";
            //document.getElementById("DivDescargas").innerHTML=data;
            document.getElementById("DivLinkDescargas").innerHTML=data;
            document.getElementById("DivProcess").innerHTML='';
            document.getElementById("DivConsultas").innerHTML='Proceso Terminado';                
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.alert("Error de generar el reporte de respuestas",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}