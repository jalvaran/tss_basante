var i = 0;

function timedCount() {
    var Page="../../Consultas/Restaurante.process.php?Accion=Alertas&TipoAlerta=1";
    if (XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                httpEdicion = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                httpEdicion = new ActiveXObject("Microsoft.XMLHTTP");
            }
            httpEdicion.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    postMessage(this.responseText) ;
                    
                }
            };
        
        httpEdicion.open("POST",Page,true);
        httpEdicion.send();
    setTimeout("timedCount()",10000);
}

timedCount(); 