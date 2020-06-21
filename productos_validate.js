'use restrict'

function ValidarImpuestoInterno(){
    valoringresado = $('#impuesto_interno').val();
     if(valoringresado != "0" ){        
                $('#impuesto_interno_valor').show(500);
            }else{
                $('#impuesto_interno_valor').hide(500);

            }
};