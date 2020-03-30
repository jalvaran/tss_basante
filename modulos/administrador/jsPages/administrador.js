/**
 * Controlador para el administrador
 * JULIAN ALVARAN 2018-07-19
 * TECHNO SOLUCIONES SAS EN ASOCIACION CON SITIS SA
 * 317 774 0609
 */


/*
 * Dibuja los Filtros
 * @returns {undefined}
 */
function DibujaFiltros(Tabla){
       
    var form_data = new FormData();
        form_data.append('Accion', 3);
        form_data.append('Tabla', Tabla);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              document.getElementById('DivOpciones1').innerHTML=data;
              
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
 * Selecciona la tabla a dibujar
 * @param {type} Tabla
 * @returns {undefined}
 */
function SeleccionarTabla(Tabla){
    document.getElementById('TxtTabla').value=Tabla;
    var Condicion = document.getElementById('TxtCondicion').value;
    var OrdenColumna = document.getElementById('TxtOrdenNombreColumna').value;
    var Orden = document.getElementById('TxtOrdenTabla').value;
    var Limit = document.getElementById('TxtLimit').value;
    var Page = document.getElementById('TxtPage').value;
    var form_data = new FormData();
        form_data.append('Accion', 2);
        form_data.append('Tabla', Tabla);
        form_data.append('Condicion', Condicion);
        form_data.append('OrdenColumna', OrdenColumna);
        form_data.append('Orden', Orden);
        form_data.append('Limit', Limit);
        form_data.append('Page', Page);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              document.getElementById('tabla').innerHTML=data;
              
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
 * Escribe en una caja de texto
 * @param {type} idCaja
 * @param {type} Valor
 * @returns {undefined}
 */
function EscribirEnCaja(idCaja,Valor){
    document.getElementById(idCaja).value=Valor;
}
/**
 * Funcion para cambiar la palabra desc x asc y viceversa en la caja de texto utilizada para enviar el orden al dobujador de la tabla
 * @returns {undefined}
 */
function CambiarOrden(){
    var OrdenActual=document.getElementById('TxtOrdenTabla').value;
    if(OrdenActual=='DESC'){
        document.getElementById('TxtOrdenTabla').value='ASC';
    }else{
        document.getElementById('TxtOrdenTabla').value='DESC';
    }
}

/**
 * Dibuja una tabla con todos sus componentes
 * @param {type} Tabla
 * @returns {undefined}
 */
function DibujeTabla(Tabla=''){
    if(Tabla==''){
        var Tabla = document.getElementById('TxtTabla').value;
    }
    LimpiarFiltros();
    SeleccionarTabla(Tabla);
    DibujaAccionesTablas();
    DibujaFiltros(Tabla);
    DibujaControlCampos();
    DibujaOperacionesTablas(Tabla);
}
/**
 * Limpia las cajas de texto utilizadas para los filtros
 * @returns {undefined}
 */
function LimpiarFiltros(){
    document.getElementById('TxtOrdenTabla').value='DESC';
    document.getElementById('TxtCondicion').value='';
    document.getElementById('TxtOrdenNombreColumna').value='';
    document.getElementById('TxtPage').value='1';
    if ($('#DivFiltrosAplicados').length){
        document.getElementById('DivFiltrosAplicados').innerHTML='';
        var Tabla = document.getElementById('TxtTabla').value
        SeleccionarTabla(Tabla);
    }
    
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

/*
$(document).on("click",function(e) {
    var id='DivSubMenuLateral';              
    var container = $("#DivSubMenuLateral");
    var container2 = $("#aMenuPrincipal");

       if (!container.is(e.target) && container.has(e.target).length === 0) { 
           if(!container2.is(e.target) && container2.has(e.target).length === 0){
               document.getElementById(id).style.display="none";
           }
                       
       }
});
*/

/**
 * Agrega un condicional a la caja de texto utilizada para ese fin
 * @returns {undefined}
 */
function AgregaCondicional(){
   
    var Tabla = document.getElementById('TxtTabla').value
    var Columna = document.getElementById('CmbColumna').value
    var Condicional = document.getElementById('CmbCondicion').value
    var Busqueda = document.getElementById('TxtBusquedaTablas').value
    var CondicionActual = document.getElementById('TxtCondicion').value
    var CondicionFinal="";
    var Operador = "";
    var combo = document.getElementById("CmbColumna");
    var ColumnaSeleccionada = combo.options[combo.selectedIndex].text;
    document.getElementById('TxtPage').value=1;
    if(CondicionActual != ''){
        Operador = " AND ";
    }
    switch(Condicional) {
        case '=':            
            CondicionFinal= Operador + Columna + " = '" + Busqueda + "'";            
            break;
        case '*':            
            CondicionFinal= Operador + Columna + " LIKE '%" + Busqueda + "%'";            
            break;
        case '>':            
            CondicionFinal= Operador + Columna + " > '" + Busqueda + "'";            
            break;
        case '<':            
            CondicionFinal= Operador + Columna + " < '" + Busqueda + "'";            
            break;
        case '>=':            
            CondicionFinal= Operador + Columna + " >= '" + Busqueda + "'";            
            break;
        case '<=':            
            CondicionFinal= Operador + Columna + " <= '" + Busqueda + "'";            
            break;
        case '#%':            
            CondicionFinal= Operador + Columna + " LIKE '" + Busqueda + "%'";            
            break;
        case '<>':            
            CondicionFinal= Operador + Columna + " <> '" + Busqueda + "'";            
            break;    
    } 
    document.getElementById('TxtCondicion').value=document.getElementById('TxtCondicion').value+" "+CondicionFinal;
    
    SeleccionarTabla(Tabla);
    if(document.getElementById('DivFiltrosAplicados').innerHTML==''){
        document.getElementById('DivFiltrosAplicados').innerHTML='<a href="#" id="aBorrarFiltros" onclick="LimpiarFiltros();" style="color:green"><span class="btn btn-block btn-primary btn-xs"><strong>Limpiar Filtros</strong></span></a> <strong>Filtros Aplicados: </strong><br>';
    }
    var lista='<i class="fa fa-circle-o text-aqua"></i><span> '+ColumnaSeleccionada+' '+ Condicional + ' '+Busqueda+' </span><br>';
    document.getElementById('DivFiltrosAplicados').innerHTML=document.getElementById('DivFiltrosAplicados').innerHTML+" "+lista;
    
}

/*
 * Dibuja las operaciones que se pueden realizar en una tabla
 * @returns {undefined}
 */
function DibujaOperacionesTablas(Tabla){
       
    var form_data = new FormData();
        form_data.append('Accion', 4);
        form_data.append('Tabla', Tabla);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              document.getElementById('DivOpciones2').innerHTML=data;
              
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
 * Envia la peticion para realizar una consulta proveniente de las acciones
 * @returns {undefined}
 */
function ConsultaAccionesTablas(){
       
    var Tabla = document.getElementById('TxtTabla').value
    var Columna = document.getElementById('CmbColumnaAcciones').value
    var AccionTabla = document.getElementById('CmbAccionTabla').value
    var CondicionActual = document.getElementById('TxtCondicion').value    
    var combo = document.getElementById("CmbColumnaAcciones");
    var ColumnaSeleccionada = combo.options[combo.selectedIndex].text;   
    
    var combo2 = document.getElementById("CmbAccionTabla");
    var TxtAccionSeleccionada = combo2.options[combo2.selectedIndex].text;   
       
    var form_data = new FormData();
        form_data.append('Accion', 5);
        form_data.append('Tabla', Tabla);
        form_data.append('Columna', Columna);
        form_data.append('AccionTabla', AccionTabla);
        form_data.append('CondicionActual', CondicionActual);
        form_data.append('ColumnaSeleccionada', ColumnaSeleccionada);
        form_data.append('TxtAccionSeleccionada', TxtAccionSeleccionada);
        
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
                if(document.getElementById('DivResultadosAcciones').innerHTML==''){
                    document.getElementById('DivResultadosAcciones').innerHTML='<strong>Resultados: </strong><br>';
                }
                
                document.getElementById('DivResultadosAcciones').innerHTML=document.getElementById('DivResultadosAcciones').innerHTML+" "+data;
              
              
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
 * Cambia el limite de las tablas
 * @returns {undefined}
 */
function CambiarLimiteTablas(){
    var Tabla = document.getElementById('TxtTabla').value;
    var Limite = document.getElementById('CmbLimit').value;
    document.getElementById('TxtPage').value=1;
    document.getElementById('TxtLimit').value=Limite;
    SeleccionarTabla(Tabla);
}

function AvanzarPagina(){
    var Tabla = document.getElementById('TxtTabla').value;
    var PaginaActual = document.getElementById('TxtPage').value;
    PaginaActual++;
    document.getElementById('TxtPage').value=PaginaActual;
    SeleccionarTabla(Tabla);
}

function RetrocederPagina(){
    var Tabla = document.getElementById('TxtTabla').value;
    var PaginaActual = document.getElementById('TxtPage').value;
    PaginaActual--;
    if(PaginaActual>0){
        document.getElementById('TxtPage').value=PaginaActual;
        SeleccionarTabla(Tabla);
    }
}

function SeleccionaPagina(){
    var Tabla = document.getElementById('TxtTabla').value;
    var PaginaActual = document.getElementById('CmbPage').value;
    document.getElementById('TxtPage').value=PaginaActual;
    SeleccionarTabla(Tabla);
}

/**
 * Dibuja el control de campos
 * @param {type} Tabla
 * @returns {undefined}
 */
function DibujaControlCampos(){
    var Tabla = document.getElementById('TxtTabla').value;   
    var form_data = new FormData();
        form_data.append('Accion', 7);
        form_data.append('Tabla', Tabla);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              document.getElementById('DivControlCampos').innerHTML=data;
              SeleccionarTabla(Tabla);
              //iCheckCSS(); //Esta funcion no permite realizar funciones, se deshabilita hasta que se encuentre solucion
              
          }else {
                alertify.alert("No se pudo dibujar el control de campos para la tabla");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  


/**
 * Dibuja las acciones
 * @param {type} Tabla
 * @returns {undefined}
 */
function DibujaAccionesTablas(){
    var Tabla = document.getElementById('TxtTabla').value;   
    var form_data = new FormData();
        form_data.append('Accion', 8);
        form_data.append('Tabla', Tabla);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              document.getElementById('DivOpciones3').innerHTML=data;
              SeleccionarTabla(Tabla);
              //iCheckCSS(); //Esta funcion no permite realizar funciones, se deshabilita hasta que se encuentre solucion
              
          }else {
                alertify.alert("No se pudo dibujar las acciones para la tabla");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  

/**
 * Oculta o muestra un campo de una tabla
 * @param {type} Tabla
 * @param {type} Campo
 * @returns {undefined}
 */
function OcultaMuestraCampoTabla(Tabla,Campo){
       
    var form_data = new FormData();
        form_data.append('Accion', 6);
        form_data.append('Tabla', Tabla);
        form_data.append('Campo', Campo);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              if(data=="OK"){
                  SeleccionarTabla(Tabla);
                  alertify.success("Estado de la columna "+Campo+" Cambiado");
              }else{
                  alertify.alert(data);
              }
              
              SeleccionarTabla(Tabla);
              
          }else{
                alertify.alert("No se pudo dibujar el control de campos para la tabla");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  


/**
 * Dibuja el formulario para ingresar un registro nuevo
 * @param {type} Tabla
 * @returns {undefined}
 */
function DibujaFormularioNuevoRegistro(Tabla){
    
    $("#ModalAcciones").modal()

    var form_data = new FormData();
        form_data.append('Accion', 9);
        form_data.append('Tabla', Tabla);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              document.getElementById('DivFormularios').innerHTML=data;
              ConvierteSelects();
              EnfocaPrimerCampo('TxtNuevoRegistro');              
              AgregaEventosCamposEspeciales();
              ValidacionContrasenaSegura();
          }else {
                alertify.alert("No se pudo dibujar el formulario");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  

/**
 * Dibuja el formulario para editar un registro
 * @param {type} Tabla
 * @returns {undefined}
 */
function DibujaFormularioEditarRegistro(Tabla,idEditar){
    
    $("#ModalAcciones").modal()

    var form_data = new FormData();
        form_data.append('Accion', 10);
        form_data.append('Tabla', Tabla);
        form_data.append('idEditar', idEditar);
        $.ajax({
        url: './Consultas/administrador.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data != "") { 
              document.getElementById('DivFormularios').innerHTML=data;
              ConvierteSelects();
              EnfocaPrimerCampo('TxtNuevoRegistro');              
              AgregaEventosCamposEspeciales();
              ValidacionContrasenaSegura();
          }else {
              alertify.alert("No se pudo dibujar el formulario de edición");
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}  

/**
 * Enfoca el primer campo de un formulario
 * @param {type} NombreCampos
 * @returns {undefined}
 */
function EnfocaPrimerCampo(NombreCampos){
  
  var form1Inputs = document.getElementsByName(NombreCampos);
  var idElemento=form1Inputs[0].id;
  document.getElementById(idElemento).focus();
    
}
/**
 * Obtiene los campos de un formulario
 * @param {type} NombreCampos
 * @returns {FormData|getFormInsert.form_data}
 */
function getFormInsert(NombreCampos){
  var form_data = new FormData();  
  var form1Inputs = document.getElementsByName(NombreCampos);
  
  for(let i=0; i<form1Inputs.length; i++){  
        
        form_data.append(form1Inputs[i].id, form1Inputs[i].value);
  }
  
  var Selects = document.getElementsByName("CmbInserts");
  
  for(let i=0; i<Selects.length; i++){  
        
        form_data.append(Selects[i].id, Selects[i].value);
  }
  
  return form_data;
}

/**
 * Obtiene los campos de un formulario
 * @param {type} NombreCampos
 * @returns {FormData|getFormInsert.form_data}
 */
function ConvierteSelects(){
  var form_data = new FormData();  
  var form1Inputs = document.getElementsByName("CmbInserts");
  var idElemento="";
  for(let i=0; i<form1Inputs.length; i++){  
        idElemento=form1Inputs[i].id;
        $('#'+idElemento).select2();        
  }
  
  return form_data;
}


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
/**
 * Guarga un registro 
 * @param {type} event
 * @returns {undefined}
 */
function GuardarRegistro(event){
    event.preventDefault();
    var TipoFormulario = document.getElementById('TxtTipoFormulario').value;
    
    var Tabla = document.getElementById('TxtTabla').value;    
    var form_data = getFormInsert('TxtNuevoRegistro');
    
    if(TipoFormulario == 'Insertar'){
        var idAccion = 1;
    }
    
    if(TipoFormulario == 'Editar'){
        var idEditar = document.getElementById('TxtIdEdit').value;
        var idAccion = 2;
        form_data.append('idEditar', idEditar);
    }
    
    
        form_data.append('idAccion', idAccion);
        form_data.append('Tabla', Tabla);
        $.ajax({
        url: './procesadores/tablas.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           
          if (data == "OK") { 
              alertify.success("Datos Registrados correctamente");
              document.getElementById('DivFormularios').innerHTML="";
              
              CierraModal("ModalAcciones");
              SeleccionarTabla(Tabla);
              
          }else {
                alertify.alert("Error: "+data);
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })        
}
    
    
/**
 * Agrega eventos a campos que requieran acciones especificas se dibujan desde ajax
 * @returns {undefined}
 */    
function AgregaEventosCamposEspeciales(){
    
    if ($('#Login').length) {
        document.getElementById("Login").addEventListener("change", VerificaLogin);
    }

    if ($('#RutaImagen').length) {
        document.getElementById("RutaImagen").addEventListener("change", ValideImagenEmpresa);
    }

    if ($('#Tipo').length) {
        document.getElementById("Tipo").addEventListener("change", VerificaTipoUsuario);
    }
}    

/**
 * Verifica si ya existe un usuario con el mismo login
 * @returns {undefined}
 */
function VerificaLogin(){
    var form_data = new FormData();
        form_data.append('idAccion', 1);
        form_data.append('Login', $('#Login').val());
      
    $.ajax({
        
        url: 'buscadores/ConsultarLogin.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data)
           if(data=="Error"){
                alertify.alert("El usuario ya Existe");
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=true;
                }
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=true;
                }
            }else{
                alertify.success("Usuario disponible");
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=false;
                }
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=false;
                }
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //alert("Error al tratar de borrar el archivo");
            alert(xhr.status);
            alert(thrownError);
          }
      })
}
/**
 * Verifica si ya existe un mismo tipo de usuario
 * @returns {undefined}
 */
function VerificaTipoUsuario(){
    var form_data = new FormData();
        form_data.append('idAccion', 1);
        form_data.append('Tipo', $('#Tipo').val());
      
    $.ajax({
        
        url: 'buscadores/ConsultarLogin.search.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log(data)
           if(data=="Error"){
                alertify.alert("El Tipo de Usuario ya Existe");
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=true;
                }
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=true;
                }
            }else{
                alertify.success("Tipo de usuario disponible");
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=false;
                }
                if ($('#BtnModalGuardar').length) {
                    document.getElementById('BtnModalGuardar').disabled=false;
                }
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //alert("Error al tratar de borrar el archivo");
            alert(xhr.status);
            alert(thrownError);
          }
      })
}

/**
 * Verifica si la extension de la imagen de la empresa es valida
 * @returns {Boolean}
 */
function ValideImagenEmpresa(){
    var fileInput = document.getElementById('RutaImagen');
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
    if(!allowedExtensions.exec(filePath)){
        alertify.alert('Solo se permiten archivos con extension .jpeg/.jpg/.png/.gif');
        fileInput.value = '';
        if ($('#BtnModalGuardar').length) {
            document.getElementById('BtnModalGuardar').disabled=true;
        }
        
        if ($('#BtnModalGuardar').length) {
            document.getElementById('BtnModalGuardar').disabled=true;
        }
        
        return false;
    }else{
        if ($('#BtnModalGuardar').length) {
            document.getElementById('BtnModalGuardar').disabled=false;
        }
        if ($('#BtnModalGuardar').length) {
            document.getElementById('BtnModalGuardar').disabled=false;
        }
        
        alertify.success("Imagen permitida");
    }
}

/**
 * Valida que la contraseña sea segura
 * @returns {undefined}
 */
function ValidacionContrasenaSegura(){
  var longitud = false,
    minuscula = false,
    numero = false,
    mayuscula = false;
  $('input[type=password]').keyup(function() {
    var pswd = $(this).val();
    if (pswd.length < 8) {
      $('#length').removeClass('valid').addClass('invalid');
      longitud = false;
    } else {
      $('#length').removeClass('invalid').addClass('valid');
      longitud = true;
    }

    //validate letter
    if (pswd.match(/[A-z]/)) {
      $('#letter').removeClass('invalid').addClass('valid');
      minuscula = true;
    } else {
      $('#letter').removeClass('valid').addClass('invalid');
      minuscula = false;
    }

    //validate capital letter
    if (pswd.match(/[A-Z]/)) {
      $('#capital').removeClass('invalid').addClass('valid');
      mayuscula = true;
    } else {
      $('#capital').removeClass('valid').addClass('invalid');
      mayuscula = false;
    }

    //validate number
    if (pswd.match(/\d/)) {
      $('#number').removeClass('invalid').addClass('valid');
      numero = true;
    } else {
      $('#number').removeClass('valid').addClass('invalid');
      numero = false;
    }
    if(longitud==true && minuscula == true && mayuscula == true && numero == true ){
        if ($('#BtnModalGuardar').length) {
                document.getElementById('BtnModalGuardar').disabled=false;
            }
        }else{
        if ($('#BtnModalGuardar').length) {
                document.getElementById('BtnModalGuardar').disabled=true;
            }
        }
  }).focus(function() {
    $('#pswd_info').show();
  }).blur(function() {
    $('#pswd_info').hide();
  });

  
}

