$(document).ready(function () {
    CargarProductos();

    cargaralmacenVerdadero();
    cargaralmacen();

});

function cargaralmacenVerdadero() {

    debugger;
    $("select#almacen").change(function () {
        debugger;
        var idsucursals = $(this).val();
        var route = "/listarAlmacenS/" + idsucursals + "";
        $('#almacenV')
                .find('option')
                .remove()
                .end()
                .append('<option value="null">Selecione un Almacen</option>')
                .val('null');
        $('#almacenV').material_select();
        $.get(route, function (res) {
            debugger;
            $(res).each(function (key, value) {
                debugger;
                $('#almacenV').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
                $('#almacenV').material_select();
            });
        });
    });


}

function cargaralmacen() {
    //carga lasucursal
    $('#almacen')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione una Sucursal</option>')
            .val('null');
    $('#almacen').material_select();
    debugger;
    var route = "/listarsucrusal/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacen').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacen').material_select();
        });
    });
}

//$("#ventacruzadas").click(
function ventacruzadaid(producto) {
//    var producto = $('#producto').val();
    var idempleado = $('#iddelempleado').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var almacen = $('#almacenV').val();
    var sucursal = $('#almacen').val();
    debugger;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }

    if (almacen === "null" && sucursal === "null") {
        window.open("/ReportVentacruzdas/" + producto + "/" + idempleado + "/" + extencion);
    }

    if (almacen === "null" && sucursal !== "null") {
        window.open("/ReportVentacruzdasConSucursal/" + producto + "/" + idempleado + "/" + extencion + "/" + sucursal);
    }

    if (almacen !== "null" && sucursal !== "null") {
        window.open("/ReportVentacruzdasConSucursalConAlmacen/" + producto + "/" + idempleado + "/" + extencion + "/" + sucursal+ "/" + almacen);
    }


}
//        );

function CargarProductos() {
    debugger;
    var tabladatos = $('#datos');
    var route = "/listadeproducto/";
    $('#datos').empty();
    var nombres = "";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.tipo == "tipo") {
                tabladatos.append("<tr>" +
                        "<td>" + value.categoria + "</td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.precioVenta + "</td>" +
                        "<td>" + value.origen + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><a class='btn btn-primary' href='ventacruzadaid(" + value.id + ")'>Generar</a></td>" +
                        "</tr>");
            } else {
                tabladatos.append("<tr>" +
                        "<td>" + value.categoria + "</td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.precioVenta + "</td>" +
                        "<td>" + value.origen + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><a class='btn btn-primary' href='javascript:ventacruzadaid(" + value.id + ")'>Generar</a></td>" +
                        "</tr>");
            }
        });
        paginador();
    });
}

function paginador() {
    $('#tablacategoria tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
    });
    var table = $('#tablacategoria').DataTable({
        pagingType: "full_numbers",
        retrieve: true,
        order: [0, 'asc'],
        responsive: true
    });
    table.columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });
}

