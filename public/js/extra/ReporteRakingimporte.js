$(document).ready(function () {
    var tabladatos = $('#datos');
    var vacio;

    var fechainicio = $('#Fechainicio').val();
    var fechafin = $('#fechafin').val();

    var route = "/DatoRakingimporte/" + fechainicio + "/" + fechafin;
    $('#datos').empty();
    debugger;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.cliente == null) {
                $vacio = ' ';

            }

            tabladatos.append("<tr><td>" + value.Producto + "</td><td>" + value.CantidadVendido + "</td><td>" + value.ImporteTotal + "</td><td>" + value.Sucursal + "</td></tr>");




        });
    });
//paginador();
});


//
//function paginador(){
//    debugger;
//    $('#tabla').DataTable( {
//      "pagingType": "full_numbers",
//       retrieve: true,
//       "order": [ 0, "desc" ],
//         "lengthMenu": [[20, 40, 60], [20, 40, 60]]
//    });
//}




function imprimir()
{

    var ficha = document.getElementById('imprmir');
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write(ficha.innerHTML);
    ventimp.document.close();
    ventimp.print( );
    ventimp.close();

}