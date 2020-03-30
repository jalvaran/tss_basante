/**
 * Cambia los estilos de los botones para indicar cual cuenta o factura o actividad está activa
 * JULIAN ALVARAN 2018-07-19
 * TECHNO SOLUCIONES SAS EN ASOCIACION CON SITIS SA
 * 317 774 0609
 */

function CambiarColorBtnCuentas(id){
        
    $(".btn-info").each(function(index) {
      
      var idBoton=$(this).attr('id');
      document.getElementById(idBoton).className="btn btn-warning";
      document.getElementById(idBoton).style.color="white";
      
    });
    $('#'+id).addClass("btn-info");
    document.getElementById(id).style.color="red";
    
}

function CambiarColorBtnFacturas(id){
        
    $(".btn-default").each(function(index) {
      
      var idBoton=$(this).attr('id');
      document.getElementById(idBoton).className="btn btn-success";
       document.getElementById(idBoton).style.color="white";
      
    });
    document.getElementById(id).className="btn btn-default";
    document.getElementById(id).style.color="red";
    
}

function CambiarColorBtnDetalles(id){
        
    $(".btn-primary").each(function(index) {
      
      var idBoton=$(this).attr('id');
      document.getElementById(idBoton).className="btn btn-success";
       document.getElementById(idBoton).style.color="white";
      
    });
    document.getElementById(id).className="btn btn-primary";
    document.getElementById(id).style.color="red";
    
}
