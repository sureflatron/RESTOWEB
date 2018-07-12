$(document).ready(function () {


    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1; //hoy es 0!
    var yyyy = hoy.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    hoy = yyyy + '-' + mm + '-' + dd;
    $("#FEchanicio").val(hoy);
    $("#Fechafin").val(hoy);


    var tabladatos = $('#datos');
    var vacio;

    var fechainicio = $('#Fechainicio').val();
    var fechafin = $('#fechafin').val();

    var route = "/DatoRakingtotal/" + fechainicio + "/" + fechafin;
    $('#datos').empty();
    debugger;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr><td>" + value.Producto + "</td><td>" + value.Cantidadvendida + "</td><td>" + value.ImporteTotal + "</td><td>" + value.Sucursal + "</td></tr>");
        });
    });

});







function imprimir()
{

    var ficha = document.getElementById('imprmir');
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write(ficha.innerHTML);
    ventimp.document.close();
    ventimp.print( );
    ventimp.close();

}