'use restrict'

function ValidarCliente(){
    valoringresado = $('input:text[name=cliente_id]').val();
    valoringresado = valoringresado+"/"+$('input:text[name=factura_tipo]').val();
     if(valoringresado != "" ){
        $.post("buscar-cliente.php",{valorBusqueda:valoringresado}, function(description) {
            if((description!=0) && (description !="E1")){
                description=description.split('/')
                $('#cliente_nombre').val(description[1]);
                $('#cliente_cuit').val(description[2]);
                $('#cliente_sit_iva').val(description[3]);
                $('#cliente_direccion').val(description[4]);
                

            }else{
                if(description=="E1"){
                    alert("CLIENTE NO APTO PARA ESTE TIPO DE COMPROBANTE. PRESIONE F1 PARA AYUDA");
                    $('input:text[name=cliente_id]').val('');
                }else{
                    alert("CLIENTE NO EXISTE. PRESIONE F1 PARA AYUDA");
                    $('input:text[name=cliente_id]').val('');
                }
                
                
            }
        });
     } else {
         ("#resultadoBusqueda").html('');
     };

};

function CargarProducto(){
    valoringresado = $('input:text[name=cod_producto]').val();
     if(valoringresado != "" ){
        $.post("buscar-producto.php",{valorBusqueda1:valoringresado}, function(description) {
            if((description!=0) && (description !="E1")){
                description=description.split('/')
                $('#nom_producto').val(description[1]);
                $('#precio_producto').val(description[2]);
                $('#iva_producto').val(description[3]);
                $('#imp_interno_producto').val(description[4]);
                $('#imp_interno_valor_producto').val(description[5]);
                $('input:text[name=cantidad_producto]').focus();
            }else{
                if(description=="E1"){
                    alert("EL PRODUCTO NO SE ENCUENTRA EN STOCK. PRESIONE F2 PARA AYUDA");
                    $('input:text[name=producto_id]').val('');
                }else{
                    alert("PRODUCTO NO EXISTENTE. PRESIONE F2 PARA AYUDA");
                    $('input:text[name=producto_id]').val('');
                }
                
                
            }
        });
     } else {
         ("#resultadoBusqueda").html('');
     };

};

function TotalizarProducto(){
    precio = $('input:text[name=precio_producto]').val();
    precio = precio.slice(1);
    iva = $('input:text[name=iva_producto]').val();
    iva = iva.slice(0,2);
    imp_interno = $('input:text[name=imp_interno_producto]').val();
    cantidad = $('input:text[name=cantidad_producto]').val();

    total = (precio*cantidad)+((precio*cantidad*iva)/100);

    if(imp_interno!="---"){
        if(imp_interno.includes('$')){
            valor_impuesto = imp_interno.slice(1);
            total = total + (cantidad*valor_impuesto);
        }else{
            valor_impuesto = imp_interno.slice(0,2);
            total = total + (((precio*cantidad) * valor_impuesto) / 100);
        }
    };
    $('#precio_total').val("$"+total);
    

};

function Confirmacion(){
    if (confirm('¿Estas seguro de generar la factura? Esta accion es IRREVERSIBLE')){
       $('#CargarFactura').submit();
    }
};


$(document).ready(function(){    
    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode==112){ //F1
                $('#listarclientes').show(500);
        return false;
        }
        
    });
    $(document).on('keydown', 'body', function(event) {
        if(event.keyCode==113){ //F1
                $('#listarproductos').show(500);
        return false;
        }        
    });
    $(document).on('click',function(){
        $('#listarclientes').hide(1000);
        $('#listarproductos').hide(1000);
    });

});