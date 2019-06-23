'use restrict'

function ValidarPDV(){
    valoringresado = $('input:text[name=pdv_ID]').val();

     if(valoringresado != "" ){
        $.post("buscar-pdv.php",{valorBusqueda:valoringresado}, function(description) {
            if(description != ""){
                $('#validationERROR').hide();
                $('#validationOK').show(600);
                $('#description').val(description);

            }else{
                $('#validationOK').hide(); 
                $('#validationERROR').show(300);
            }
        });
     } else {
         ("#resultadoBusqueda").html('');
     };

};

function ValidarZona(){
    valoringresado = $('input:text[name=zone_ID]').val();

     if(valoringresado != "" ){
        $.post("buscar-zone.php",{valorBusqueda:valoringresado}, function(description) {
            if(description != ""){
                $('#validationZoneERROR').hide();
                $('#validationZoneOK').show(600);
                $('#zone_description').val(description);

            }else{
                $('#validationZoneOK').hide(); 
                $('#validationZoneERROR').show(300);
            }
        });
     } else {
         ("#resultadoBusqueda").html('');
     };

};

function PDVHELP(){

    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode==112){ //F1
                $('#popup-zone').hide();
                $('#popup-pdv').show(500);
        return false;
        }
        
    });

};

function ZONEHELP(){

    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode==112){ //F1
                $('#popup-pdv').hide();
                $('#popup-zone').show(500);
                return false;
        }
    });

};

$(document).ready(function(){
    $(document).on('click',function(){
        $('#popup-pdv').hide(1000);
        $('#popup-zone').hide(1000);
    });

});



