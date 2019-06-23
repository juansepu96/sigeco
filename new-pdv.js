'use restrict'

function ValidarPDV(){
    valoringresado = $('input:text[name=pdv_ID]').val();

     if(valoringresado != "" ){
        $.post("buscar-new-pdv.php",{valorBusqueda:valoringresado}, function(description) {
            if(description == ""){
                $('#validationERROR').hide();
                $('#validationOK').show(600);
            }else{
                $('#validationOK').hide(); 
                $('#validationERROR').show(600);
            }
        });
     } else {
         ("#resultadoBusqueda").html('');
     };

};

$(document).ready(function(){

    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode==112){ //F1
            $('#popup-pdv').show(1000);
        return false;
         }
     });

    $(document).on('click',function(){
        $('#popup-pdv').hide(1000);
    });

});

