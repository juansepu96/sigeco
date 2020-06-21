'use restrict'

function ValidarCuit() 
{
var vec = new Array(10);
var cuit = $('input:text[name=cuit]').val();
esCuit=false;
cuit_rearmado="";
errors = ''
for (i=0; i < cuit.length; i++)
{   
    caracter=cuit.charAt( i);
    if ( caracter.charCodeAt(0) >= 48 && caracter.charCodeAt(0) <= 57 )
    {
        cuit_rearmado +=caracter;
    }
}
cuit=cuit_rearmado;
if ( cuit.length != 11) {  // si no estan todos los digitos
    esCuit=false;
    errors = 'Cuit < 11 ';
} else {
    x=i=dv=0;
    // Multiplico los dígitos.
    vec[0] = cuit.charAt(  0) * 5;
    vec[1] = cuit.charAt(  1) * 4;
    vec[2] = cuit.charAt(  2) * 3;
    vec[3] = cuit.charAt(  3) * 2;
    vec[4] = cuit.charAt(  4) * 7;
    vec[5] = cuit.charAt(  5) * 6;
    vec[6] = cuit.charAt(  6) * 5;
    vec[7] = cuit.charAt(  7) * 4;
    vec[8] = cuit.charAt(  8) * 3;
    vec[9] = cuit.charAt(  9) * 2;
                
    // Suma cada uno de los resultado.
    for( i = 0;i<=9; i++) 
    {
        x += vec[i];
    }
    dv = (11 - (x % 11)) % 11;
    if ( dv == cuit.charAt( 10) )
    {
        esCuit=true;
    }
}
if ( esCuit ) 
{
    $('#agregar').show(600);

}else{
    $('#agregar').hide();
    alert( "CUIT erróneo. Reintente" );
}
}