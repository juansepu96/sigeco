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
                $('#generate').prop("disabled", true); 
                $('#generate').css("background", "gray");
            }
            if(description == "b"){
                alert("LA FECHA TIENE QUE SER  ANTERIOR O IGUAL A LA FECHA DEL ASIENTO DE CIERRE");
                $('#generate').prop("disabled", true); 
                $('#generate').css("background", "gray");
            }
            if(description == "c"){
                alert("LA FECHA NO SE ENCUENTRA DENTRO DEL PERIODO FISCAL");
                $('#generate').prop("disabled", true); 
                $('#generate').css("background", "gray");
            }
            if(description == "d"){
                alert("LA FECHA ES ANTERIOR A LA ULTIMA IMPRESION DEL LIBRO DIARIO");
                $('#generate').prop("disabled", true); 
                $('#generate').css("background", "gray");
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
                $('#generate').css("background", "blue");
            }
            
        });
    }

};



$(document).ready(function(){
    
    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode==112){ //F1
                $('#listarcuentas').show(500);
        return false;
        }
        
    });

    $(document).on('click',function(){
        $('#listarcuentas').hide(1000);
    });

});





