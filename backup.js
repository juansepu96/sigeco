'use restrict'


function Backup(){
    cartel = $('#auth');

    cartel.show(600);

};


function ValidateBackup(){
    valoringresado = $('input:text[name=backup_ID]').val();    

     if(valoringresado != "" ){
        $.post("backup_validate.php",{valorBusqueda:valoringresado}, function(description) {
            if(description == "YES"){
                $('#submit-backup').show(600);
            }else{
                alert('ERROR. LA CLAVE ES INVÁLIDA. CONTACTE AL ADMINISTRADOR');
                $('#submit-backup').hide();
            }
        });
     }else{
        $('#submit-backup').hide(600);

    }

};

$(document).ready(function(){

    $('#close-popup').on('click',function(){
        $('#auth').hide(1000);
    });

});

