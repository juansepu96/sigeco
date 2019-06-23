'use restrict'


function ValidarUsername(){
    valoringresado = $('input:text[name=username]').val();

     if(valoringresado != "" ){
        $.post("buscar-user.php",{valorBusqueda:valoringresado}, function(description) {
            if(description == ""){
                $('#validationERROR').hide();
                $('#validationOK').show(600);

            }else{
                $('#validationOK').hide(); 
                $('#validationERROR').show(300);
            }
        });
     } else {
         ("#resultadoBusqueda").html('');
     };

};

