'use restrict'

function ValidateKey(){
    valoringresado = $('input:text[name=unique_key]').val();    
     if(valoringresado != "" ){
        $.post("validate_unique_key.php",{valorBusqueda:valoringresado}, function(description) {
            if(description == "YES"){
                $('#validationOK').show(600);
            }else{
                alert('ERROR. LA CLAVE ES INVÁLIDA O NO ESTÁ HABILITADA PARA CREAR MAS EMPRESAS. CONTACTE AL ADMINISTRADOR');
                $('#validationOK').hide();
            }
        });
     };

};