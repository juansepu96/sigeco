'use restrict'

function ValidarAccount(){
    valoringresado = $('input:text[name=account_renglon]').val();

     if(valoringresado != "" ){
        $.post("buscar-account.php",{valorBusqueda:valoringresado}, function(description) {
            if(description != ""){
                $('#account_name').val(description);

            }else{
                alert("CUENTA ERRONEA. ASEGURESE DE INGRESAR CUENTA CORRECTA. PRESIONE F1 PARA AYUDA");
                $('input:text[name=account_renglon]').val('');
                $('input:text[name=account_renglon]').focus();
            }
        });
     } else {
         ("#resultadoBusqueda").html('');
     };

};

function ValidarFecha(){
    valoringresado = $('#date').val();

     if(valoringresado != "" ){
        $.post("validar-fecha.php",{valorBusqueda:valoringresado}, function(description) {
            if(description == "a"){
                alert("LA FECHA TIENE QUE SER POSTERIOR O IGUAL A LA FECHA DEL ASIENTO DE APERTURA");
            }
            if(description == "b"){
                alert("LA FECHA TIENE QUE SER  ANTERIOR O IGUAL A LA FECHA DEL ASIENTO DE CIERRE");
            }
            if(description == "c"){
                alert("LA FECHA NO SE ENCUENTRA DENTRO DEL PERIODO FISCAL");
            }
            if(description == ""){
                ValidarTipo();
            }
        });
     };

};

function ValidarTipo(){
    valoringresado = $('#tipo').val();
     if(valoringresado != "" ){
        $.post("validar-tipo.php",{valorBusqueda:valoringresado}, function(description) {
            if(description != ""){
                alert("YA EXISTE UN ASIENTO DEL MISMO TIPO Y SOLO DEBE HABER UNO.");
                $('#generate').prop("disabled", true); 
                $('#generate').css("background", "gray");
            }else{
                $('#generate').prop("disabled", false); 
            }
            
        });
    }

};

function ValidarClave(){
    valoringresado = $('#clave').val();
     if(valoringresado != "" ){
        $.post("validar-clave.php",{valorBusqueda:valoringresado}, function(description) {
            if(description != ""){
                alert("CLAVE ERRONEA. NO TIENE PERMISOS PARA REGISTRAR ASIENTOS");
                $('#registrar').prop("disabled", true); 
                $('#registrar').hide();
                $('#clave').focus();
            }else{
                $('#registrar').prop("disabled", false); 
                $('#registrar').show();
            }
            
        });
    }

};



$(document).ready(function(){
    
    $('#generate').css("background", "blue");
    

});





