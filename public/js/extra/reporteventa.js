$(document).ready(function () {
    var tabladatos = $('#datos');
    var vacio;
///2016-08-26/14
    var fechainicio = $('#Fechainicio').val();
    var fechafin = $('#fechafin').val();
    var idempleado = $('#idempleado').val();
    var route = "/cargardatos/" + fechainicio + "/" + fechafin + "/" + idempleado;
    $('#datos').empty();
    debugger;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.cliente == null) {
                vacio = ' ';
            }else{
                vacio = value.cliente;
            }
            debugger;
            tabladatos.append("<tr>"+
                    "<td>" + value.idventa + "</td>"+
                    "<td>" + value.fecha + "</td>"+
                    "<td>" + value.formaPago + "</td>"+
                    "<td>" + vacio + "</td>"+
                    "<td>" + value.sucursal + "</td>"+
                    "<td>" + value.total + "</td>"+
                    "</tr>");

            document.getElementById('totales').innerHTML = "Total Vendido : " + value.totales;


        });
    });
});



function imprimir()
{
    // var Obj = document.getElementById("desaparece");
    //Obj.style.visibility = 'hidden';
    //window.print();

    var ficha = document.getElementById('imprmir');
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write(ficha.innerHTML);
    ventimp.document.close();
    ventimp.print( );
    ventimp.close();

}