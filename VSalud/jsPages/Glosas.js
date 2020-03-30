/**
 * Controlador para la gestion de glosas
 * JULIAN ALVARAN 2018-07-19
 * TECHNO SOLUCIONES SAS EN ASOCIACION CON SITIS SA
 * 317 774 0609
 */

/**
 * Cambia el la fecha minima para seleccionar en una fecha final
 * @returns {undefined}
 */
function CambiarFechaFinal(){
    var FechaInicial = document.getElementById("FiltroFechaInicial").value;
    $('#FiltroFechaFinal').attr("min", FechaInicial); 
}
function CambiarFechaInicial(){
    var FechaFinal = document.getElementById("FiltroFechaFinal").value;
    $('#FiltroFechaInicial').attr("max", FechaFinal);
}

// Solo permite ingresar numeros.

function CampoNumerico(e){
    //var no_digito = /\D/g;
    //this.value = this.value.replace(no_digito , '');
    
	var key = window.Event ? e.which : e.keyCode
        
	return ((key >= 48 && key <= 57) || (key >= 0 && key <= 31) || key == 127)

}

function ValidarFecha(idTxtFecha){
    var FechaValidar = document.getElementById(idTxtFecha).value;
    var hoy             = new Date();
    var fechaFormulario = new Date(FechaValidar);

    // Compara solo las fechas => no las horas!!
    hoy.setHours(0,0,0,0);

    if (fechaFormulario > hoy ) {
        return 1;    
    }else{
        return 0;
    }
}

/**
 * Buscar una cuenta RIPS por diferentes criterios
 * @param {type} event
 * @returns {undefined}
 */
function BuscarCuentaXCriterio(Criterio=1){
  document.getElementById("DivFacturas").innerHTML='';  
  document.getElementById("DivDetallesUsuario").innerHTML='';  
  document.getElementById("DivActividadesFacturas").innerHTML='';  
  document.getElementById("DivCuentas").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
         
  if(Criterio==1){
        MostrarFacturas('',$('#TxtBuscarFact').val());
    }       
  var form_data = new FormData();
    form_data.append('idFactura', $('#TxtBuscarFact').val());
    form_data.append('CuentaRIPS', $('#TxtBuscarCuentaRIPS').val());
    form_data.append('CuentaGlobal', $('#TxtBuscarCuentaGlobal').val());
    form_data.append('CmdEstadoGlosa', $('#CmdEstadoGlosa').val());
    form_data.append('idEPS', $('#idEPS').val());
    
    
    /*
     if(Criterio==1){//Si se busca por numero de factura
         form_data.append('idFactura', $('#TxtBuscarFact').val());
         MostrarFacturas('',$('#TxtBuscarFact').val());
         document.getElementById("TxtBuscarCuentaGlobal").value="";
         document.getElementById("TxtBuscarCuentaRIPS").value="";
         document.getElementById("CmdEstadoGlosa").value="";
         document.getElementById("idEPS").value="";
         
     }
     if(Criterio==2){//Si se busca por Cuenta RIPS
         form_data.append('CuentaRIPS', $('#TxtBuscarCuentaRIPS').val());
         document.getElementById("TxtBuscarCuentaGlobal").value="";
         document.getElementById("TxtBuscarFact").value="";
         document.getElementById("CmdEstadoGlosa").value="";
         document.getElementById("idEPS").value="";
         
     } 
     if(Criterio==3){//Si se busca por Cuenta Global
         form_data.append('CuentaGlobal', $('#TxtBuscarCuentaGlobal').val());
         document.getElementById("TxtBuscarCuentaRIPS").value="";
         document.getElementById("TxtBuscarFact").value="";
         document.getElementById("CmdEstadoGlosa").value="";
         document.getElementById("idEPS").value="";
         
     }
     if(Criterio==4){//Si se busca por Estado de GLosa 
         form_data.append('CmdEstadoGlosa', $('#CmdEstadoGlosa').val());
         document.getElementById("TxtBuscarCuentaRIPS").value="";
         document.getElementById("TxtBuscarFact").value="";
         document.getElementById("TxtBuscarCuentaGlobal").value="";
         document.getElementById("idEPS").value="";
         
     }
     if(Criterio==5){//Si se busca por EPS
         form_data.append('idEPS', $('#idEPS').val());
         document.getElementById("TxtBuscarCuentaRIPS").value="";
         document.getElementById("TxtBuscarFact").value="";
         document.getElementById("TxtBuscarCuentaGlobal").value="";
         document.getElementById("CmdEstadoGlosa").value="";
         
     }
    */
   
  $.ajax({
    url: './Consultas/vista_salud_cuentas_rips.search.php',
    //dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,
    type: 'post',
    success: function(data){
        //console.log(data)
      if (data != "") { 
          document.getElementById('DivCuentas').innerHTML=data;
                
      }else {
        alert("No hay resultados para la consulta");
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
  })
}
/*
 * Muestra las facturas que corresponden a una cuenta RIPS, o a un numero de factura en particular
 * @param {type} CuentaRIPS
 * @param {type} NumFactura
 * @returns {undefined}
 */
function MostrarFacturas(CuentaRIPS,NumFactura='',LimpiarDivs=1){
    //document.getElementById("TxtBuscarCuentaRIPS").value=CuentaRIPS;
    document.getElementById("TxtCuentaActiva").value=CuentaRIPS;
    if(LimpiarDivs==1){
        document.getElementById("DivDetallesUsuario").innerHTML='';
        document.getElementById("DivActividadesFacturas").innerHTML='';
        document.getElementById("DivHistoricoGlosas").innerHTML='';
        document.getElementById("DivFormRespuestasGlosas").innerHTML='';
        document.getElementById("DivRespuestasGlosasTemporal").innerHTML='';
    }
    document.location.href = "#AnclaFacturas";
    document.getElementById("DivFacturas").innerHTML='<div id="GifProcess">Buscando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
    var form_data = new FormData();
        form_data.append('CuentaRIPS', CuentaRIPS);
        form_data.append('idFactura', NumFactura);
        $.ajax({
        url: './Consultas/busqueda_af.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
          if (data != "") { 
              document.getElementById('DivFacturas').innerHTML=data;
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  
/*
 * Filtra las facturas por rango de fechas 
 * @returns {undefined}
 */
function FiltreRangoFechas(){
     var form_data = new FormData();
        form_data.append('FechaInicial', $('#FiltroFechaInicial').val());
        form_data.append('FechaFinal', $('#FiltroFechaFinal').val());
        form_data.append('idEPS', $('#idEPS').val());
        form_data.append('CuentaRIPS', $('#TxtCuentaActiva').val());
        
        form_data.append('idEstadoGlosas', $('#CmbEstadoGlosaFacturas').val());
        document.getElementById("DivFacturas").innerHTML='<div id="GifProcess">Buscando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
   
        $.ajax({
        url: './Consultas/busqueda_af.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
          if (data != "") { 
              document.getElementById('DivFacturas').innerHTML=data;
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  

/*
 * Filtra las cuentas por rango de fechas 
 * @returns {undefined}
 */
function FiltreCuentasRangoFechas(){
    
     var form_data = new FormData();
        form_data.append('idEPS', $('#idEPS').val());
        form_data.append('FechaInicial', $('#FiltroFechaInicialCuentas').val());
        form_data.append('FechaFinal', $('#FiltroFechaFinalCuentas').val());
        form_data.append('idFactura', $('#TxtBuscarFact').val());
        form_data.append('CuentaRIPS', $('#TxtBuscarCuentaRIPS').val());
        form_data.append('CuentaGlobal', $('#TxtBuscarCuentaGlobal').val());
        form_data.append('CmdEstadoGlosa', $('#CmdEstadoGlosa').val());
        
        document.getElementById("DivCuentas").innerHTML='<div id="GifProcess">Buscando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
   
        $.ajax({
        url: './Consultas/vista_salud_cuentas_rips.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
          if (data != "") { 
              document.getElementById('DivCuentas').innerHTML=data;
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  

/*
 * Filtra las cuentas por rango de fechas 
 * @returns {undefined}
 */
function FiltreCuentasFechaRadicado(){
     var form_data = new FormData();
        form_data.append('idEPS', $('#idEPS').val());
        form_data.append('CuentaRIPS', $('#TxtBuscarCuentaRIPS').val());
        form_data.append('CuentaGlobal', $('#TxtBuscarCuentaGlobal').val());
        form_data.append('CmdEstadoGlosa', $('#CmdEstadoGlosa').val());
        form_data.append('FechaRadicado', $('#FiltroFechaRadicadoCuentas').val());
        document.getElementById("DivCuentas").innerHTML='<div id="GifProcess">Buscando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
   
        $.ajax({
        url: './Consultas/vista_salud_cuentas_rips.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
          if (data != "") { 
              document.getElementById('DivCuentas').innerHTML=data;
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  
/**
 * Filtra las facturas por estado de la glosa
 * @returns {undefined}
 */
function FiltreFacturasXEstadoGlosa(){
     var form_data = new FormData();
        form_data.append('idEstadoGlosas', $('#CmbEstadoGlosaFacturas').val());
        
        document.getElementById("DivFacturas").innerHTML='<div id="GifProcess">Buscando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
   
        $.ajax({
        url: './Consultas/busqueda_af.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
          if (data != "") { 
              document.getElementById('DivFacturas').innerHTML=data;
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  
/**
 * Funcion que espera el evento Click sobre el boton de mostrar factura
 * para buscar el usuario y datos de la misma y dibujarlos en la interfaz de glosar 
 * @param {type} idFactura
 * @returns {undefined}
 */
function MostrarActividades(idFactura){
    document.getElementById('TxtFacturaActiva').value=idFactura;
    document.getElementById('DivHistoricoGlosas').innerHTML='';
    document.getElementById('DivFormRespuestasGlosas').innerHTML='';
    document.getElementById('DivRespuestasGlosasTemporal').innerHTML='';
    BuscarUsuarioFactura(idFactura);
    BuscarActividadesFactura(idFactura);
    document.location.href = "#AnclaDetalleFacturas";
}
/**
 * Busca el Usuario o Paciente al que se le realizó una factura, y los valores de la factura
 * @param {type} idFactura
 * @returns {undefined}
 */
function BuscarUsuarioFactura(idFactura){
    var form_data = new FormData();
        form_data.append('idFactura', idFactura);
        document.getElementById('DivDetallesUsuario').innerHTML="Buscando Paciente...";
        $.ajax({
        //async:false,    
        url: './Consultas/PacienteFactura.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
          if (data != "") { 
              document.getElementById('DivDetallesUsuario').innerHTML=data;
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })   
}
/**
 * Busca las actividades asociadas a una factura
 * @param {type} idFactura
 * @returns {undefined}
 */
function BuscarActividadesFactura(idFactura){
    var form_data = new FormData();
        form_data.append('idFactura', idFactura);
        document.getElementById('DivActividadesFacturas').innerHTML="Buscando Actividades...";
        $.ajax({
        //async:false,     
        url: './Consultas/ActividadesFactura.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            //console.log(data)
          if (data != "") { 
              document.getElementById('DivActividadesFacturas').innerHTML=data;
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })   
}
/**
 * Esta funcion realizará todas las peticiones necesarias al servidor para la devolucion de una factura
 * @param {type} idFactura
 * @returns {undefined}
 */    
function DevolverFactura(idFactura){
    if($('#FechaAuditoria').val()=='' || $('#FechaDevolucion').val()=='' || $('#CodigoGlosa').val()=='' || $('#Observaciones').val()==''){
        alertify.set({ labels: {
                ok     : "OK",
                cancel : "Cancelar"
            } });
        alertify.alert("Todos los campos son obligatorios");
        return 0;
    }
    
    alertify.set({ labels: {
                ok     : "Devolver",
                cancel : "Cancelar"
            } });
    alertify.confirm("Está seguro que desea devolver la Factura "+idFactura+"?<br><strong>NOTA: Esta acción es irreversible. <strong>",
    function (e) {
        if (e) {
            if(ValidarFecha('FechaDevolucion')){
                alertify.error("La fecha de devolución seleccionada es mayor a la actual, por favor seleccione una fecha válida");
                return;
            }
            if(ValidarFecha('FechaAuditoria')){
                alertify.error("La fecha de Recibo de auditoría seleccionada es mayor a la actual, por favor seleccione una fecha válida");
                return;
            }
            AccionesGlosarFacturas(idFactura,1);
                        
        } else {
            alertify.error("Se canceló la devolución de la factura: "+idFactura);

        }
    });
}

/**
 * Dibuja los diferentes formularios donde se capturará la gestion de glosas
 * @param {type} idFormulario
 * @param {type} idFactura
 * @returns {undefined}
 */
function DibujeFormulario(idFormulario,idFactura){
        if ($('#DivHistorialGlosas').length) {
            document.getElementById('DivHistorialGlosas').innerHTML='';
        }
        if ($('#DivFormRespuestasGlosas').length) {
            document.getElementById('DivFormRespuestasGlosas').innerHTML='';
        }
        if ($('#DivRespuestasGlosasTemporal').length) {
            document.getElementById('DivRespuestasGlosasTemporal').innerHTML='';
        }
       
        
        document.getElementById('BtnModalGlosar').click();
        document.getElementById('BtnCierreModal').focus();
        var form_data = new FormData();       
        
        form_data.append('idFactura', idFactura);
        form_data.append('idFormulario', idFormulario);
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
            document.getElementById("DivGlosar").innerHTML=data;
            
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
            
            document.getElementById("CodigoGlosa_chosen").style.width = "400px";      
        }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de devolver la factura "+idFactura,0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Realiza todas las acciones que se ejecutaran para el sistema de glosado de facturas
 * @param {type} idFactura -> El numero de factura que se realizará la glosa
 * @param {type} idAccion  -> La accion a realizar 1: Devolucion de una factura
 * @returns {undefined}
 */
function AccionesGlosarFacturas(idFactura,idAccion,TipoArchivo='',idActividad=''){
        if(idAccion==1){
            
            var form_data = getInfoFormDevoluciones();
            if(form_data==0){
                return;
            }
        }
        
        if(idAccion==2){ //Glosar una actividad inicial
           
            if(ValidarFecha('FechaIPS')==1){
                //console.log(ValidarFecha('FechaIPS'));
                alertify.alert("La fecha de IPS no puede ser mayor a la de hoy");
                document.getElementById('FechaIPS').focus();
                return;
            }
            if(ValidarFecha('FechaAuditoria')==1){
                alertify.alert("La fecha de Auditoria no puede ser mayor a la de hoy");
                document.getElementById('FechaAuditoria').focus();
                return;
            }
            
            if(ValidaValorGlosa()==1){
                
                return;
            }
            var form_data = getInfoFormGlosasRespuestas();
            if(form_data==0){
                return;
            }
        }
        
        
        
        form_data.append('idAccion', idAccion); //Devolver una factura
        form_data.append('idFactura', idFactura);
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if(idAccion==1){
                document.getElementById("DivGlosar").innerHTML=data;
                //document.getElementById('BtnModalGlosar').click();
                BuscarUsuarioFactura(idFactura);
                BuscarActividadesFactura(idFactura);
                RefrescarDiv();
                alertify.success("Se realizó la devolución de la factura "+idFactura);
            }
            
            if(idAccion==2){
                //console.log(data);
                var datos=JSON.parse(data);
                if(datos.Error){
                    alertify.alert(datos.msg);
                    
                }else{
                    alertify.success(datos.msg);
                    DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,3); //Dibuja las glosas temporales   
                    DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,2); //Dibuja el formulario para iniciar el registro de una nueva Glosa
                
                }
                
                
                
                
                //document.getElementById("DivGlosar").innerHTML="Tarea Registrada";
                
                //alertify.success("Se realizó el registro de la glosa inicial ");
            }

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de devolver la factura "+idFactura,0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * 
 * Captura la informacion del Formulario de devoluciones
 */
function getInfoFormDevoluciones(){
    if($('#FechaAuditoria').val()=='' || $('#FechaDevolucion').val()=='' || $('#CodigoGlosa').val()=='' || $('#Observaciones').val()==''){
        alertify.set({ labels: {
                ok     : "OK",
                cancel : "Cancelar"
            } });
        alertify.alert("Todos los campos son obligatorios");
        return 0;
    }
    var form_data = new FormData();
    form_data.append('FechaAuditoria', $('#FechaAuditoria').val());
    form_data.append('FechaDevolucion', $('#FechaDevolucion').val());
    form_data.append('CodigoGlosa', $('#CodigoGlosa').val());
    form_data.append('Observaciones', $('#Observaciones').val());
    form_data.append('ValorFactura', $('#ValorFacturaDevolucion').val());
    form_data.append('Soporte', $('#UpSoporteDevolucion').prop('files')[0]);    
    return form_data;
}

/**
 * Funcion que espera el evento Click sobre el boton de Glosar actividad
 * para Glosar una actividad en especifico
 * @param {type} Tabla ->tabla de donde viene la actividad
 * @param {type} idActividad -> id de la actividad que se va a glosar
 * @returns {undefined}
 */
function GlosarActividad(TipoArchivo,idActividad,idFactura,CodActividad){
    
    
    document.getElementById('TxtActividadActiva').value=CodActividad;
    document.getElementById('TxtFacturaActiva').value=idFactura;
    document.getElementById('DivHistoricoGlosas').innerHTML='';
    document.getElementById('DivFormRespuestasGlosas').innerHTML='';
    document.getElementById('DivRespuestasGlosasTemporal').innerHTML='';
    document.getElementById('BtnModalGlosar').click();
    document.getElementById('BtnCierreModal').focus();
    
    DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,2); //Dibuja el formulario para iniciar el registro de una nueva Glosa
    DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,3); //Dibuja las glosas temporales
    
}

/**
 * Dibuja los diferentes formularios donde se capturará la gestion de glosas de las actividades
 * @param {type} idFormulario
 * @param {type} idFactura
 * @returns {undefined}
 */
function DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,idFormulario){
        
        var form_data = new FormData();       
        
        form_data.append('TipoArchivo', TipoArchivo);
        form_data.append('idActividad', idActividad);
        form_data.append('idFormulario', idFormulario);
        form_data.append('idFactura', idFactura);
        
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                if(idFormulario==3){
                    document.getElementById("DivHistorialGlosas").innerHTML=data;
                }else{
                    document.getElementById("DivGlosar").innerHTML=data;
                }
                
            
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
            
            document.getElementById("CodigoGlosa_chosen").style.width = "400px";  
            document.getElementById("ValorEPS").onkeypress= CampoNumerico;
        }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de dibujar el formulario de glosar la actividad "+idActividad,0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * 
 * Captura la informacion del Formulario de devoluciones
 */
function getInfoFormGlosasRespuestas(Opciones=0){
    if ($('#CodigoGlosa').length) {
        if($('#CodigoGlosa').val()==''){
            alertify.set({ labels: {
                ok     : "OK",
                cancel : "Cancelar"
            } });
            alertify.alert("Todos los campos son obligatorios");
            return 0;
        }
    } 
    
    
    if($('#FechaIPS').val()=='' || $('#FechaAuditoria').val()=='' || $('#ValorEPS').val()=='' || $('#ValorAceptado').val()=='' || $('#Observaciones').val()==''){
        alertify.set({ labels: {
                ok     : "OK",
                cancel : "Cancelar"
            } });
        alertify.alert("Todos los campos son obligatorios");
        return 0;
    }
    
    if ($('#ValorLevantado').length) { //Verifico que existe el campo de texto Valor Levantado
        var ValorLevantado=$('#ValorLevantado').val();
      } else {
        var ValorLevantado=0;
      }
    var form_data = new FormData();
    form_data.append('FechaAuditoria', $('#FechaAuditoria').val());
    form_data.append('FechaIPS', $('#FechaIPS').val());
    form_data.append('CodigoGlosa', $('#CodigoGlosa').val());
    form_data.append('Observaciones', $('#Observaciones').val());
    form_data.append('idActividad', $('#idActividad').val());
    form_data.append('TipoArchivo', $('#TipoArchivo').val());
    form_data.append('ValorEPS', $('#ValorEPS').val());
    form_data.append('ValorAceptado', $('#ValorAceptado').val());
    form_data.append('ValorConciliar', $('#ValorConciliar').val());
    form_data.append('TotalActividad', $('#TotalActividad').val());
    form_data.append('ValorLevantado', ValorLevantado);
    form_data.append('Soporte', $('#UpSoporteGlosa').prop('files')[0]);    
    return form_data;
}
/**
 * Valida que no se ingrese un mayor valor a glosar y  calcula el valor a conciliar
 * @param {type} ValorMaximoAGlosar
 * @returns {undefined}
 */
function ValidaValorGlosa(valor){
    var ValorGlosado = Math.round(document.getElementById('ValorEPS').value);
    var ValorXGlosar = Math.round(document.getElementById('ValorXGlosarMax').value);
    if(ValorGlosado > ValorXGlosar){
        alertify.alert("El valor de la glosa digitado es mayor al que se puede Glosar");
        return 1;
    }
    document.getElementById('ValorConciliar').value=document.getElementById('ValorEPS').value-document.getElementById('ValorAceptado').value;
    return 0;
}
/**
 * Eliminar una glosa de la tabla temporal
 * @param {type} idGlosa
 * @returns {undefined}
 */
function EliminarGlosaTemporal(idGlosa,idFactura,idActividad,TipoArchivo){
    var form_data = new FormData();
        form_data.append('idGlosa', idGlosa);
        form_data.append('idAccion', 3);
        $.ajax({
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //async:false,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
          if (data != "") { 
              DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,3); //Dibuja las glosas temporales
              DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,2); //Dibuja el formulario para registro de glosas
              alertify.success(data);
              
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })       
}


/**
 * Dibuja los diferentes formularios para la edicion de glosas de las actividades
 * @param {type} idGlosa
 * @param {type} idFormulario
 * @returns {undefined}
 */
function DibujeFormularioEdicionActividades(idGlosa,idFormulario){
        
        var form_data = new FormData();       
        
        form_data.append('idGlosa', idGlosa);
        form_data.append('idFormulario', idFormulario);
        
        
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                
                document.getElementById("DivGlosar").innerHTML=data;
            
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
            if ($('#ValorAceptado').length) {
                    document.getElementById("ValorAceptado").onkeypress= CampoNumerico;
                }
                if ($('#ValorLevantado').length) {
                    document.getElementById("ValorLevantado").onkeypress= CampoNumerico;
                }
                if ($('#ValorEPS').length) {
                    document.getElementById("ValorEPS").onkeypress= CampoNumerico;
                }
            document.getElementById("CodigoGlosa_chosen").style.width = "400px";      
        }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de dibujar el formulario de glosar la actividad "+idActividad,0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Editar Una glosa de la tabla temporal
 * @param {type} idGlosaTemp
 * @param {type} idAccion
 * @param {type} TipoArchivo
 * @param {type} idActividad
 * @param {type} idFactura
 * @returns {undefined}
 */
function EditarGlosaTemporal(idGlosaTemp,idAccion,TipoArchivo,idActividad,idFactura){
                
        if(idAccion==4){ //Editar una actividad inicial
           
            if(ValidarFecha('FechaIPS')==1){
                //console.log(ValidarFecha('FechaIPS'));
                alertify.alert("La fecha de IPS no puede ser mayor a la de hoy");
                document.getElementById('FechaIPS').focus();
                return;
            }
            if(ValidarFecha('FechaAuditoria')==1){
                alertify.alert("La fecha de Auditoria no puede ser mayor a la de hoy");
                document.getElementById('FechaAuditoria').focus();
                return;
            }
            if(ValidaValorGlosa()==1){
                
                return;
            }
            var form_data = getInfoFormGlosasRespuestas();
                
            if(form_data==0){
                return;
            }
        }
        
        
        
        form_data.append('idAccion', idAccion); //Devolver una factura
        form_data.append('idGlosaTemp', idGlosaTemp);
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            if(idAccion==4){
                DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,3);//Dibujar las glosas temporales
                alertify.success(data);
                DibujeFormularioActividades(TipoArchivo,idActividad,idFactura,2); //Dibuja el formulario para iniciar el registro de una nueva Glosa
   
                
            }

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de editar la glosa ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Guadar las Glosas Temporales en la tabla de glosas iniciales
 * @returns {undefined}
 */
function GuadarGlosasTemporales(idFactura){
        
        if ($('#BtnDevolverFactura').length) {
            document.getElementById('BtnDevolverFactura').disabled=true;
        }
        
        var form_data = new FormData();        
        form_data.append('idAccion', 5); //Devolver una factura
        document.getElementById("DivGlosar").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
  
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            alertify.success(data);
            document.getElementById('DivGlosar').innerHTML=data;
            document.getElementById('DivHistorialGlosas').innerHTML='';
            
            BuscarActividadesFactura(idFactura);
            RefrescarDiv();
            if ($('#BtnActualizarListaFacturasCuenta').length) {
                document.getElementById("BtnActualizarListaFacturasCuenta").click();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de editar la glosa ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Se visualiza el historico de glosas de una actividad para responder o contraglosar
 * @returns {undefined}
 */
function VerDetallesActividad(CodActividad,idFactura){
        document.location.href = "#AnclaDetalleActividades";
        document.getElementById("DivGlosar").innerHTML="";
        document.getElementById("DivFormRespuestasGlosas").innerHTML="";
        document.getElementById('TxtActividadActiva').value=CodActividad;
        document.getElementById('TxtFacturaActiva').value=idFactura;
        
        var form_data = new FormData();       
        
        form_data.append('CodActividad', CodActividad);
        form_data.append('idFactura', idFactura);
        form_data.append('idFormulario', 5); //Formulario donde se dibujan las actividades glosadas de esta factura
        
        
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                
                document.getElementById("DivHistoricoGlosas").innerHTML=data;
                //document.getElementById('BtnModalFacturas').click();
              
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de dibujar el historico",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
}
/**
 * Formulario de respuesta para las glosas(6) y contra glosas (9) 
 * @param {type} idGlosa
 * @returns {undefined}
 */
function RespuestaGlosa(idGlosa,idFormulario=6){
    document.location.href = '#AnclaFormularioRespuestas';
    var form_data = new FormData();       
        
        form_data.append('idGlosa', idGlosa);
        form_data.append('idFormulario', idFormulario); //Formulario donde se dibujan las actividades glosadas de esta factura
                
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                
                document.getElementById("DivFormRespuestasGlosas").innerHTML=data;
                
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }

                if ( $("#ValorAceptado").length > 0 ) {
                    document.getElementById("ValorAceptado").onkeypress= CampoNumerico;
                }
                if ( $("#ValorLevantado").length > 0 ) {
                    document.getElementById("ValorLevantado").onkeypress= CampoNumerico;
                }
                
                if ( $("#CodigoGlosa_chosen").length > 0 ) {
                    
                    document.getElementById("CodigoGlosa_chosen").style.width = "400px";
                }
                
                DibujeRespuestaTemporal('');//Dibuja la tabla temporal de las respuestas a las glosas
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de dibujar el historico",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
}
/**
 * Se valida que el valor aceptado por la ips no sea mayor al glosado
 * @returns {Number}
 */
function ValidaValorXConciliar(){
    var ValorGlosado = Math.round(document.getElementById('ValorEPS').value);
    var ValorAceptadoIPS = Math.round(document.getElementById('ValorAceptado').value);
    if(ValorAceptadoIPS > ValorGlosado){
        alertify.alert("El valor aceptado no puede ser mayor al valor Glosado");
        return 1;
    }
    document.getElementById('ValorConciliar').value=document.getElementById('ValorEPS').value-document.getElementById('ValorAceptado').value;
    return 0;
}

/**
 * Se valida que el valor aceptado por la ips no sea mayor al glosado
 * @returns {Number}
 */
function ValidaValorLevantado(){
    var ValorLevantado=0;
    if ( $("#ValorLevantado").length > 0 ) { //Se verifica si se está dibujando el valor levantado
        ValorLevantado = Math.round(document.getElementById('ValorLevantado').value);
    }
    var ValorGlosado = Math.round(document.getElementById('ValorEPS').value);
    var ValorAceptadoIPS = Math.round(document.getElementById('ValorAceptado').value);
    
    var ValorXConciliar=ValorGlosado-ValorAceptadoIPS-ValorLevantado;
    if(ValorXConciliar < 0){
        alertify.alert("El valor levantado no puede ser mayor al valor x Conciliar");
        return 1;
    }
    document.getElementById('ValorConciliar').value=ValorXConciliar;
    return 0;
}

function Numeros(string){//Solo numeros
    var out = '';
    var filtro = '1234567890';//Caracteres validos
	
    //Recorrer el texto y verificar si el caracter se encuentra en la lista de validos 
    for (var i=0; i<string.length; i++)
       if (filtro.indexOf(string.charAt(i)) != -1) 
             //Se añaden a la salida los caracteres validos
	     out += string.charAt(i);
	
    //Retornar valor filtrado
    return out;
} 
/**
 * Valida valores de conciliacion
 * @returns {Number}
 */
function ValidaValoresConciliacion(){
       
    if ( $("#BtnConciliarGlosa").length > 0 ) {
        document.getElementById('BtnConciliarGlosa').disabled=false;
    }
    
    if ( $("#BtnEditarContraGlosa").length > 0 ) {
        document.getElementById('BtnEditarContraGlosa').disabled=false;
    }

    
    var ValorLevantado =Numeros(document.getElementById('ValorLevantado').value);
    if(ValorLevantado==''){
        ValorLevantado=0;
    }
    document.getElementById('ValorLevantado').value=ValorLevantado;
    /*
    alert(val(document.getElementById('ValorLevantado').value));
    if(val(document.getElementById('ValorLevantado').value)<0){
        document.getElementById('BtnConciliarGlosa').disabled=true;
        alertify.alert("El valor levantado no puede contener letras");
        return;
    }
    */
    var ValorLevantado = Math.round(document.getElementById('ValorLevantado').value);
    var ValorGlosado = Math.round(document.getElementById('ValorEPS').value);
    var ValorAceptadoIPS = ValorGlosado-ValorLevantado;
    document.getElementById('ValorAceptado').value=ValorAceptadoIPS;
    var ValorXConciliar=ValorGlosado-ValorAceptadoIPS-ValorLevantado;
    document.getElementById('ValorConciliar').value=ValorXConciliar;
    
    if(ValorLevantado > ValorGlosado){
        if ( $("#BtnConciliarGlosa").length > 0 ) {
            document.getElementById('BtnConciliarGlosa').disabled=true;
        }

        if ( $("#BtnEditarContraGlosa").length > 0 ) {
            document.getElementById('BtnEditarContraGlosa').disabled=true;
        }
        alertify.alert("El valor levantado no puede ser mayor al Valor Glosado");
        //document.getElementById('ValorConciliar').value='';
        return 1;
    }
    
    if(ValorAceptadoIPS > ValorGlosado){
        alertify.alert("El valor Aceptado no puede ser mayor al Valor Glosado");
        //document.getElementById('ValorConciliar').value='';
        return 1;
    }
    
    if(ValorXConciliar != 0){
        alertify.alert("El valor X Conciliar no puede ser diferente a Cero");
        //document.getElementById('ValorConciliar').value='';
        return 1;
    }
    
    
    return 0;
}
/**
 * Agrega una Respuesta a Glosa Temporal (6) o Contra Glosas (10)
 * @param {type} idGlosa
 * @returns {undefined}
 */
function AgregarRespuestaGlosaTemporal(idGlosa,idAccion=6){
    
    var form_data = getInfoFormGlosasRespuestas();        
        form_data.append('idAccion', idAccion); //6 respuesta a Glosa temporal, 10 Contra Glosar
        form_data.append('idGlosa', idGlosa); //id de la Glosa a agregar
        var Valida=0;
        var Mensaje="El valor aceptado no puede ser mayor al valor Glosado";
        if(ValidarFecha('FechaIPS')==1){
            //console.log(ValidarFecha('FechaIPS'));
            alertify.alert("La fecha de IPS no puede ser mayor a la de hoy");
            document.getElementById('FechaIPS').focus();
            return;
        }
        if(ValidarFecha('FechaAuditoria')==1){
            alertify.alert("La fecha de Auditoria no puede ser mayor a la de hoy");
            document.getElementById('FechaAuditoria').focus();
            return;
        }
        if(idAccion==12){// viene de las conciliaciones (Si el Valor X Conciliar no es Cero no debe continuar)
            if(document.getElementById('ValorConciliar').value !=0){
                alertify.alert("El Valor X Conciliar no puede ser diferente a cero, por favor ajuste el valores levantado  por la EPS y el Valor Aceptado por la IPS ");
                return;
            }
        }
        Valida=ValidaValorXConciliar();
        if(idAccion==10){
            form_data.append('ValorLevantado', document.getElementById('ValorLevantado').value); //id de la Glosa a agregar
            Valida=ValidaValorLevantado();
            Mensaje="El valor levantado no puede ser mayor al valor x Conciliar";
        }
        
        if(Valida==1){
            alertify.error(Mensaje);
            return;
        }
        
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            alertify.success(data);
            //document.getElementById('DivRespuestasGlosasTemporal').innerHTML=data;
            document.getElementById('DivFormRespuestasGlosas').innerHTML=data;
            DibujeRespuestaTemporal('');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de editar la glosa ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Dibuja la tabla temporal de las respuesta a las glosas
 * temporal (1) Respuestas a glosas, (2) ContraGlosas
 * @param {type} idGlosa
 * @param {type} Temporal  Determina si es una respuesta a una contraglosa para saber como voy a guardar los datos en la tabla real de la temporal
 * @returns {undefined}
 */
function DibujeRespuestaTemporal(idGlosa){
    document.getElementById("DivRespuestasGlosasTemporal").innerHTML='';
    var form_data = new FormData();       
        
        form_data.append('idGlosa', idGlosa); //id de la Glosa que se está respodiendo
        form_data.append('idFormulario', 7);  //Formulario donde se dibuja la tabla temporal de las respuestas a las glosas
              
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                
                document.getElementById("DivRespuestasGlosasTemporal").innerHTML=data;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de dibujar el temporal de respuestas",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
}

/**
 * Eliminar una repuesta a glosa temporal
 * @param {type} idGlosa
 * @returns {undefined}
 */
function EliminarRepuestaGlosaTemporal(idGlosa,idAccion){
    var form_data = new FormData();
        form_data.append('idGlosa', idGlosa);
        form_data.append('idAccion', idAccion); //7 para eliminar una respuesta a glosa temporal
        $.ajax({
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //async:false,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
          if (data != "") { 
              
                alertify.success(data);
                document.getElementById("DivFormRespuestasGlosas").innerHTML=data;
                DibujeRespuestaTemporal('');
          }else {
            alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })       
}
/**
 * envia la peticion al servidor para dibujar el formulario de edicion de respuestas a glosas
 * @param {type} idGlosa
 * @param {type} idFormulario
 * @returns {undefined}
 */
function DibujeFormularioEdicionRespuestas(idGlosa,idFormulario){
    var form_data = new FormData();       
        
        form_data.append('idGlosa', idGlosa); //id de la Glosa que se está respodiendo
        form_data.append('idFormulario', idFormulario);  //Formulario para dibujar el fomulario para la edicion de una repuesta de glosa
                
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                
                document.getElementById("DivFormRespuestasGlosas").innerHTML=data;
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }
                if ( $("#ValorAceptado").length > 0 ) {
                    document.getElementById("ValorAceptado").onkeypress= CampoNumerico;
                }
                if ( $("#ValorEPS").length > 0 ) {
                    document.getElementById("ValorEPS").onkeypress= CampoNumerico;
                }
                if ( $("#ValorLevantado").length > 0 ) {
                    document.getElementById("ValorLevantado").onkeypress= CampoNumerico;
                }
                
                
                document.getElementById("CodigoGlosa_chosen").style.width = "400px"; 
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de dibujar el temporal de respuestas",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
}
/**
 * Envia la peticion al servidor para editar una respuesta temporal a una glosa
 * @param {type} idGlosa
 * @returns {undefined}
 */
function EditarRespuestaGlosaTemporal(idGlosa){
    var form_data = getInfoFormGlosasRespuestas();        
        form_data.append('idAccion', 8); //Agregar respuesta a Glosa temporal
        form_data.append('idGlosa', idGlosa); //id de la Glosa a agregar
        if(ValidaValorLevantado()==1){
            return;
            alertify.error("Valor Levantado superior al valor X conciliar");
        }
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            alertify.success(data);
            
            document.getElementById('DivFormRespuestasGlosas').innerHTML=data;
            DibujeRespuestaTemporal('');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de editar la glosa ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Guarda las respuestas de la tabla temporal a la real
 * @returns {undefined}
 */
function GuadarRespuestasTemporales(idActividad,idFactura){
    var form_data = new FormData();
        
        form_data.append('idAccion', 9); //9 para guardar las respuestas de la tabla temporal a la real
        document.getElementById("DivFormRespuestasGlosas").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
  
        $.ajax({
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //async:false,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
          if (data != "") { 
                document.getElementById("DivFormRespuestasGlosas").innerHTML=data;
                document.getElementById("DivRespuestasGlosasTemporal").innerHTML='';
                RefrescarDiv();
                alertify.success(data);
                
          }else{
            alertify.alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Refresca el estado de la cuenta
 * @returns {undefined}
 */
function RefrescaEstadoCuenta(){
    
    var CuentaRIPS = document.getElementById("TxtCuentaActiva").value;
    
    var form_data = new FormData();
        
        form_data.append('idAccion', 17); //Se solicita el estado de la cuenta
        form_data.append('CuentaRIPS', CuentaRIPS);
        
        $.ajax({
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //async:false,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
          if (data != "") { 
                var idDivEstadoCuenta="EstadoGlosaCuenta_"+CuentaRIPS;
                
                document.getElementById(idDivEstadoCuenta).innerHTML=data;
                           
                
          }else{
            alertify.alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * Refresca el estado de la factura
 * @returns {undefined}
 */
function RefrescaEstadoFactura(){
    
    var Factura = document.getElementById("TxtFacturaActiva").value;
    
    var form_data = new FormData();
        
        form_data.append('idAccion', 18); //Se solicita el estado de la cuenta
        form_data.append('Factura', Factura);
        
        $.ajax({
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //async:false,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
          if (data != "") { 
                var idDivEstadoFactura="EstadoGlosaFactura_"+Factura;
                
                document.getElementById(idDivEstadoFactura).innerHTML=data;
                           
                
          }else{
            alertify.alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * Refresca El Semaforo de las cuentas
 * @returns {undefined}
 */
function RefrescaSemaforoCuenta(){
    
    var CuentaRIPS = document.getElementById("TxtCuentaActiva").value;
    
    var form_data = new FormData();
        
        form_data.append('idAccion', 19); //Se solicita el estado de la cuenta
        form_data.append('CuentaRIPS', CuentaRIPS);
        
        $.ajax({
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //async:false,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
          if (data != "") { 
                var idDivSemaforoCuenta="DivSemaforoCuenta_"+CuentaRIPS;
                
                document.getElementById(idDivSemaforoCuenta).innerHTML=data;
                           
                
          }else{
            alertify.alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * Refresca Semaforo de la factura
 * @returns {undefined}
 */
function RefrescaSemaforoFactura(){
    
    var Factura = document.getElementById("TxtFacturaActiva").value;
    
    var form_data = new FormData();
        
        form_data.append('idAccion', 20); //Se solicita el estado de la cuenta
        form_data.append('Factura', Factura);
        
        $.ajax({
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //async:false,
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
          if (data != "") { 
                var idDivSemaforoFactura="DivSemaforoFactura_"+Factura;
                
                document.getElementById(idDivSemaforoFactura).innerHTML=data;
                           
                
          }else{
            alertify.alert("No hay resultados para la consulta");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


/**
 * Refresca los div donde están dibujadas las facturas y las actividades, 
 * se utiliza para despues de una accion que cambie un estado o valor
 * @returns {undefined}
 */
function RefrescarDiv(){
    document.getElementById("DivFormRespuestasGlosas").innerHTML="";
    document.getElementById("DivRespuestasGlosasTemporal").innerHTML="";
    document.getElementById("DivHistoricoGlosas").innerHTML="";
    RefrescaEstadoCuenta();
    RefrescaEstadoFactura();
    RefrescaSemaforoCuenta();
    RefrescaSemaforoFactura();
    
    /*
    if ($('#idDivEstadoFactura').length) {
        document.getElementById("BtnActualizarFacturas").click();
    }
    if ($('#BtnActualizarCuentas').length) {
        document.getElementById("BtnActualizarCuentas").click();
    }
    */
    if ($('#BtnActualizarActividades').length) {
        document.getElementById("BtnActualizarActividades").click();
    }
    
    //var CodActividad = document.getElementById("TxtActividadActiva").value;
    //var Factura = document.getElementById("TxtFacturaActiva").value;
    //var idBotonFactura = 'BtnMostrar_'+Factura;
    //alert("Refrescando"+CodActividad+Factura);
    //VerDetallesActividad(CodActividad,Factura);
    //MostrarActividades(Factura);
    
    
    //document.location.href = "#AnclaDetalleFacturas";
    //document.getElementById(idBotonFactura).click();
}
/**
 * Anula una glosa
 * @param {type} idGlosa
 * @param {type} idFactura
 * @param {type} CodActividad
 * @param {type} TipoArchivo
 * @returns {undefined}
 */
function AnularGlosa(idGlosa,idFactura,CodActividad,TipoArchivo,SoloRespuesta=0){
    alertify.set({ labels: {
        ok     : "OK",
        cancel : "Cancelar"
    } });
    alertify.prompt("Escriba el por qué Anulará esta Glosa", function (e, str) {
            if (e) {
                    if (str != '') {
                    
                        var form_data = new FormData();   
                            form_data.append('idAccion', 13); //Agregar respuesta a Glosa temporal
                            form_data.append('idGlosa', idGlosa); //id de la Glosa a anular
                            form_data.append('idFactura', idFactura); 
                            form_data.append('CodActividad', CodActividad); 
                            form_data.append('TipoArchivo', TipoArchivo); 
                            form_data.append('SoloRespuesta', SoloRespuesta);
                            form_data.append('Observaciones', str); 
                            $.ajax({
                            //async:false,
                            url: './Consultas/AccionesGlosarFacturas.process.php',
                            //dataType: 'json',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            type: 'post',
                            success: function(data){

                                alertify.alert(data);    
                                
                                RefrescarDiv();

                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                alertify.error("Error al tratar de editar la glosa ",0);
                                alert(xhr.status);
                                alert(thrownError);
                              }
                          })
                    
                    }else{
                       alertify.alert("Debes Escribir una observacion"); 
                    }
                    //alertify.success("You've clicked OK and typed: " + str);
            } else {
                    alertify.error("haz cancelado la accion");
            }
    }, "");
    
    
}
/**
 * Muestra el formulario de edicion para una glosa inicial
 * @param {type} idGlosa
 * @returns {undefined}
 */
function MuestraEditarGlosaInicial(idGlosa,CodActividad,Descripcion){
    
    document.getElementById('DivHistorialGlosas').innerHTML='';
    document.getElementById('DivFormRespuestasGlosas').innerHTML='';
    document.getElementById('DivRespuestasGlosasTemporal').innerHTML='';
    document.getElementById('BtnModalGlosar').click();
    document.getElementById('BtnCierreModal').focus();
    
    var form_data = new FormData();       
        
        form_data.append('idGlosa', idGlosa); //id de la Glosa que se está respodiendo
        form_data.append('idFormulario', 12);  //Formulario para dibujar el fomulario para la edicion de una repuesta de glosa
        form_data.append('CodActividad', CodActividad);
        form_data.append('Descripcion', Descripcion);
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                
                document.getElementById("DivGlosar").innerHTML=data;
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }
                document.getElementById("ValorEPS").onkeypress= CampoNumerico;
                document.getElementById("CodigoGlosa_chosen").style.width = "400px"; 
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al dibujar el formulario",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
    
        
    
}
/**
 * Envia los datos para editar una glosa inicial
 * @param {type} idGlosaInicial
 * @param {type} idGlosaRespuesta
 * @returns {undefined}
 */
function EditarGlosaInicial(idGlosaInicial,idGlosaRespuesta){
       
    var form_data = getInfoFormGlosasRespuestas();   
        form_data.append('idGlosaInicial', idGlosaInicial); //Agregar respuesta a Glosa temporal
        form_data.append('idGlosaRespuesta', idGlosaRespuesta); //id de la Glosa a anular
        form_data.append('idAccion', 14); 
        
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){

            alertify.alert(data);  
            document.getElementById("DivGlosar").innerHTML=data;
            RefrescarDiv();

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de editar la glosa ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Muestra el formulario para la edicion de una respuesta a una glosa
 * @param {type} idGlosa
 * @param {type} CodActividad
 * @param {type} Descripcion
 * @returns {undefined}
 */
function MuestraEditarGlosaRespondida(idGlosa,idFormulario){
    
    document.getElementById('DivHistorialGlosas').innerHTML='';
    document.getElementById('DivFormRespuestasGlosas').innerHTML='';
    document.getElementById('DivRespuestasGlosasTemporal').innerHTML='';
    document.getElementById('BtnModalGlosar').click();
    document.getElementById('BtnCierreModal').focus();
    
    var form_data = new FormData();       
        
        form_data.append('idGlosa', idGlosa); //id de la Glosa que se está respodiendo
        form_data.append('idFormulario', idFormulario);  //Formulario para dibujar el fomulario para la edicion de una repuesta de glosa
        
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                
                document.getElementById("DivGlosar").innerHTML=data;
                if ($('#ValorAceptado').length) {
                    document.getElementById("ValorAceptado").onkeypress= CampoNumerico;
                }
                if ($('#ValorLevantado').length) {
                    document.getElementById("ValorLevantado").onkeypress= CampoNumerico;
                }
                if ($('#ValorEPS').length) {
                    document.getElementById("ValorEPS").onkeypress= CampoNumerico;
                }
                
                
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }
                if ($('#CodigoGlosa_chosen').length) {
                    document.getElementById("CodigoGlosa_chosen").style.width = "400px"; 
                }
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al dibujar el formulario",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
    
        
    
}
/**
 * Envia la peticion para editar una glosa
 * @param {type} idGlosa
 * @returns {undefined}
 */
function EditarRespuestaGlosa(idGlosa){
    var form_data = getInfoFormGlosasRespuestas();        
        form_data.append('idAccion', 15); //Agregar respuesta a Glosa temporal
        form_data.append('idGlosa', idGlosa); //id de la Glosa a editar
         
        
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            alertify.success(data);
            
            document.getElementById('DivGlosar').innerHTML=data;
            //MostrarActividades(idFactura);
            RefrescarDiv();
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de editar la glosa ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Dibuja el formulario para conciliar por actividad
 * @param {type} TipoArchivo
 * @param {type} idArchivo
 * @param {type} idFactura
 * @param {type} CodActividad
 * @returns {undefined}
 */
function DibujeFormularioConciliarActividad(TipoArchivo,idArchivo,idFactura,CodActividad){
    
    document.getElementById('DivHistoricoGlosas').innerHTML='';
    document.getElementById('DivFormRespuestasGlosas').innerHTML='';
    document.getElementById('DivRespuestasGlosasTemporal').innerHTML='';
    document.getElementById('BtnModalGlosar').click();
    document.getElementById('BtnCierreModal').focus();
    
    var form_data = new FormData();       
        
        form_data.append('TipoArchivo', TipoArchivo); //id de la Glosa que se está respodiendo
        form_data.append('idFormulario', 15);  //Formulario para dibujar el fomulario para la edicion de una repuesta de glosa
        form_data.append('idActividad', idArchivo);
        form_data.append('num_factura', idFactura);
        form_data.append('CodigoActividad', CodActividad);
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            if (data != "") { 
                //document.getElementById("DivHistorialGlosas").innerHTML='Conciliacion';
                document.getElementById("DivGlosar").innerHTML=data;
                for (var selector in config) {
                    $(selector).chosen(config[selector]);
                }
                document.getElementById("ValorLevantado").onkeypress= CampoNumerico;
                document.getElementById("CodigoGlosa_chosen").style.width = "400px"; 
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al dibujar el formulario",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
    
}

function ConciliarActividad(idFactura,CodActividad,idArchivo,TipoArchivo){
    var DescripcionActividad = document.getElementById('DescripcionActividad').value;
    var ValorLevantado = document.getElementById('ValorLevantado').value;
    if(ValorLevantado==''){
        alertify.alert('El valor levantado no puede estar vacío');
        return;
    }
    var form_data = getInfoFormGlosasRespuestas(1);        
        form_data.append('idAccion', 16); //Agregar respuesta a Glosa temporal
        form_data.append('idFactura', idFactura); 
        form_data.append('CodActividad', CodActividad);
        form_data.append('DescripcionActividad', DescripcionActividad);
        
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
                        
            alertify.success(data);
            
            document.getElementById('DivGlosar').innerHTML=data;
            //MostrarActividades(idFactura);
            RefrescarDiv();
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de editar la glosa ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Verhistorial de glosas de una factura
 * @param {type} idFactura
 * @returns {undefined}
 */
 
function VerHistoricoGlosas(idFactura,CodigoActividad){
    document.location.href = "#AnclaFormularioRespuestas";
    document.getElementById("DivFormRespuestasGlosas").innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
  
    console.log('entra');
    var form_data = new FormData();       
        
        form_data.append('idFormulario', 19);  //Formulario para dibujar el fomulario para la edicion de una repuesta de glosa
        
        form_data.append('num_factura', idFactura);
        form_data.append('CodigoActividad', CodigoActividad);
        $.ajax({
        //async:false,
        url: './Consultas/GlosasFormularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data)
            if (data != "") { 
                //document.getElementById("DivHistorialGlosas").innerHTML='Conciliacion';
                document.getElementById("DivFormRespuestasGlosas").innerHTML=data;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error",0);
            alertify.alert(xhr.status);
            alertify.alert(thrownError);
          }
      })
}


function ConfirmarGeneracionXMLGlosas(){
    alertify.confirm("Está seguro que desea iniciar el proceso de generacion de XML para las glosas?",
    function (e) {
        if (e) {
           
            CalcularArchivosXMLARealizarGlosasIniciales();
                        
        } else {
            alertify.error("Se canceló el proceso");

        }
    });
}


function CalcularArchivosXMLARealizarGlosasIniciales(){
    document.getElementById('DivProcessGeneracionXML').innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
    var form_data = new FormData();      
        form_data.append('idAccion', 21); //Agregar respuesta a Glosa temporal
                
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data);
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                document.getElementById('DivGeneracionXML').innerHTML=respuestas[1];
                GenerarXMLGlosas(respuestas[2]);
            }else{
                document.getElementById('DivProcessGeneracionXML').innerHTML="";
                alertify.alert(data);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alertify.error("Error al tratar de generar los xml de las glosas ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function GenerarXMLGlosas(TotalRegistros){
    
    var form_data = new FormData();      
        form_data.append('idAccion', 22); //Agregar respuesta a Glosa temporal
        form_data.append('TotalRegistros', TotalRegistros); //Agregar respuesta a Glosa temporal        
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data);
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                //alertify.success(respuestas[1]);
                document.getElementById('DivGeneracionXML').innerHTML=respuestas[1];
                if(respuestas[2] == 0){
                    document.getElementById('DivProcessGeneracionXML').innerHTML="";
                    document.getElementById('DivGeneracionXML').innerHTML="Se crearon "+TotalRegistros+" Archivos XML de Glosas";
                    alertify.success("XML Creados correctamente");
                    CalcularTotalArchivosFTPDeGlosasASubir();
                }else{
                    
                    GenerarXMLGlosas(TotalRegistros);
                }
                
            }else{
                document.getElementById('DivProcessGeneracionXML').innerHTML="";
                alertify.alert(data);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById('DivProcessGeneracionXML').innerHTML="";
            alertify.error("Error al tratar de generar los xml de las glosas ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function CalcularTotalArchivosFTPDeGlosasASubir(){
    document.getElementById('DivProcessSubirFTP').innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
    var form_data = new FormData();      
        form_data.append('idAccion', 23); 
                
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data);
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                document.getElementById('DivSubirFTP').innerHTML=respuestas[1];
                SubirFTPGlosas(respuestas[2]);
            }else{
                document.getElementById('DivProcessSubirFTP').innerHTML="";
                alertify.alert(data);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById('DivProcessSubirFTP').innerHTML="";
            alertify.error("Error al tratar de subir los ftp de las glosas ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function SubirFTPGlosas(TotalRegistros){
    
    var form_data = new FormData();      
        form_data.append('idAccion', 24); //Agregar respuesta a Glosa temporal
        form_data.append('TotalRegistros', TotalRegistros); //Agregar respuesta a Glosa temporal        
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data);
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                //alertify.success(respuestas[1]);
                document.getElementById('DivSubirFTP').innerHTML=respuestas[1];
                if(respuestas[2] == 0){
                    alertify.success("XML Subidos al FTP correctamente");
                    document.getElementById('DivProcessSubirFTP').innerHTML="";
                    document.getElementById('DivSubirFTP').innerHTML="Se subieron al servidor ftp "+TotalRegistros+" Archivos XML de Glosas";
                    CalcularTotalArchivosFTPDeGlosasASubirIniciales();
                }else{
                    SubirFTPGlosas(TotalRegistros);
                }
                
            }else{
                document.getElementById('DivProcessSubirFTP').innerHTML="";
                alertify.alert(data);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById('DivProcessSubirFTP').innerHTML="";
            alertify.error("Error al tratar de subir los ftp de las glosas ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


function CalcularTotalArchivosFTPDeGlosasASubirIniciales(){
    document.getElementById('DivProcessSubirFTP').innerHTML='<div id="GifProcess">Procesando...<br><img   src="../images/cargando.gif" alt="Cargando" height="100" width="100"></div>';
    var form_data = new FormData();      
        form_data.append('idAccion', 26); 
                
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data);
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                document.getElementById('DivSubirFTP').innerHTML=respuestas[1];
                SubirFTPGlosasIniciales(respuestas[2]);
            }else{
                document.getElementById('DivProcessSubirFTP').innerHTML="";
                alertify.alert(data);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById('DivProcessSubirFTP').innerHTML="";
            alertify.error("Error al tratar de subir los ftp de las glosas ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function SubirFTPGlosasIniciales(TotalRegistros){
    
    var form_data = new FormData();      
        form_data.append('idAccion', 27); //Agregar respuesta a Glosa temporal
        form_data.append('TotalRegistros', TotalRegistros); //Agregar respuesta a Glosa temporal        
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data);
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                //alertify.success(respuestas[1]);
                document.getElementById('DivSubirFTP').innerHTML=respuestas[1];
                if(respuestas[2] == 0){
                    alertify.success("XML Subidos al FTP correctamente");
                    document.getElementById('DivProcessSubirFTP').innerHTML="";
                    document.getElementById('DivSubirFTP').innerHTML="Se subieron al servidor ftp "+TotalRegistros+" Archivos XML de Glosas";
                }else{
                    SubirFTPGlosasIniciales(TotalRegistros);
                }
                
            }else{
                document.getElementById('DivProcessSubirFTP').innerHTML="";
                alertify.alert(data);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById('DivProcessSubirFTP').innerHTML="";
            alertify.error("Error al tratar de subir los ftp de las glosas ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

function CrearXMLGlosaInicial(NumeroFactura,ValorGlosado){
    
    var form_data = new FormData();      
        form_data.append('idAccion', 25); //Crear Glosa Inicial
        form_data.append('NumeroFactura', NumeroFactura);    
        form_data.append('ValorGlosado', ValorGlosado);       
        $.ajax({
        //async:false,
        url: './Consultas/AccionesGlosarFacturas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data);
            var respuestas = data.split(';'); 
            if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                document.getElementById('DivGeneracionXML').innerHTML=respuestas[1];
                RefrescarDiv();                
            }else{
                document.getElementById('DivProcessGeneracionXML').innerHTML="";
                alertify.alert(data);
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById('DivProcessGeneracionXML').innerHTML="";
            alertify.error("Error al tratar de generar los xml de las glosas ",0);
            alert(xhr.status);
            alert(thrownError);
          }
      })
}