'use restrict'

function ValidarUsername(){
    valoringresado = $('input:text[name=username]').val();
    valorOriginal = $('input:text[name=original_username]').val();

     if(valoringresado != "" ){
        $.post("buscar-change-user.php",{valorBusqueda:valoringresado}, function(description) {
            if(description == "" || valoringresado==valorOriginal){
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

