var iddelpuntoventa;


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
    $("#fechainicios").val(hoy);
    $("#Fechafins").val(hoy);
    $("#fechainicioss").val(hoy);
    $("#Fechafinss").val(hoy);
    $("#fechainiciof").val(hoy);
    $("#Fechafinf").val(hoy);
    $("#fechainiciofu").val(hoy);
    $("#Fechafinfu").val(hoy);
    $("#fechainiciofusu").val(hoy);
    $("#Fechafinfusu").val(hoy);
    $("#fechainicioegreso").val(hoy);
    $("#Fechafinegreso").val(hoy);
    $("#fechainicio").val(hoy);
    $("#Fechafin").val(hoy);
    $("#fechainicioegreso").val(hoy);
    $("#Fechafinegreso").val(hoy);
    $("#fechainicioingreso").val(hoy);
    $("#Fechafinegreso").val(hoy);
    $("#fechainicio2").val(hoy);
    $("#Fechafin2").val(hoy);
    $("#fechainicio1").val(hoy);
    $("#Fechafin1").val(hoy);

    var URLactual = window.location.pathname;
    cargarcliente();
    cargarProveedor();
    cargarmesa();
    cargarTurno();
    cargarempleado();

    cargarempleados();
    if (URLactual == "/ReporteKardexInventario") {
        CargarProductos();
    }
    if (URLactual == "/ReporteMovimientoInventario") {
        CargarProductosParaMovimientoInventario();
    }
    if (URLactual == "/Ventacruzada" || URLactual == "/EstadoInventario") {
        CargarProductosParaEstadoInventario();
    }

    cargaralmacen();
    cargaralmacenVerdadero();
    cargaralmacendesucursal();
    cargarSucursalVerdadero();
    cargaralmacenV();
    cargartipoegreso();
//    Cargarsucursal();
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 10 // Creates a dropdown of 15 years to control year
    });

//    $("select#empleado").change(function (evento) {
//        debugger;
//        var val = $(this).val();
//        if (val == "") {
//            $("#sucursaleslista").attr("style", "display:block;");
//        } else {
//            $("#sucursaleslista").attr("style", "display:none;");
//        }
//    });

    iddelpuntoventa = $('#iddelpuntoventa').val();
//    $(".group2").click(function (evento) {
//        var val = $(this).val();
//        if (val == "credito") {
//            $("#filtrosfecha").attr("style", "display :block;");
//        } else {
//            $("#filtrosfecha").attr("style", "display:none;");
//        }
//    });
});



function cargarcliente() {
    $('#cliente')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un Cliente</option>')
            .val('null');
    $('#cliente').material_select();
    var route = "/listarempleadosreporte/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#cliente').append('<option value=' + value.id + ' >' + value.razonSocial + '</option>');
            $('#cliente').material_select();
        });
    });
}

function cargarmesa() {
    $('#mesa')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un Mesa</option>')
            .val('null');
    $('#mesa').material_select();
    var route = "/listarmesa/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#mesa').append('<option value=' + value.id + ' >' + value.numeromesa + '</option>');
            $('#mesa').material_select();
        });
    });
}

function cargarTurno() {
    $('#turno')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un Turno</option>')
            .val('null');
    $('#turno').material_select();
    var route = "/listaturno/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#turno').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#turno').material_select();
        });
    });
}

function cargarempleado() {
    $('#empleado').find('option').remove().end().append('<option value="">Selecione un Empleado</option>').val('');
    $('#empleado').material_select();
    var route = "/listarempleadosreporte/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#empleado').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#empleado').material_select();
        });
    });
}

function cargarempleados() {
    $('#empleados').find('option').remove().end().append('<option value="">Selecione un Empleado</option>').val('');
    $('#empleados').material_select();
    var route = "/listarempleadosreporte/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#empleados').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#empleados').material_select();
        });
    });
}


$("#flujotodo").click(function () {
    debugger;
    var fechafin = $('#Fechafinfusu').val();
    var fechaninio = $('#fechainiciofusu').val();
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        Materialize.toast('Campos vacio fecha fin', 1000, 'rounded');
        Materialize.toast('Campos vacio fechan inicio', 1000, 'rounded');
        return;
    } else {
    }
    window.open("/Flujocajausuario/" + fechaninio + "/" + fechafin);
});


function CargarProductos() {
    debugger;
    var tabladatos = $('#datos');
    var route = "/listadeproducto/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var codB = "";
            if (value.codigoDeBarra !== null) {
                codB = value.codigoDeBarra;
            }
            var codI = "";
            if (value.codigoInterno !== null) {
                codI = value.codigoInterno;
            }
            var col = "";
            if (value.color !== null) {
                col = value.color;
            }
            var tam = "";
            if (value.tamano !== null) {
                tam = value.tamano;
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.categoria + "</td>" +
                    "<td>" + codB + "</td>" +
                    "<td>" + codI + "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.precioVenta + "</td>" +
                    "<td>" + value.origen + "</td>" +
                    "<td>" + col + "</td>" +
                    "<td>" + tam + "</td>" +
                    "<td>" + value.marca + "</td>" +
                    "<td><a class='btn btn-primary' href='javascript:generarreportekardex(" + value.id + ")'>Generar</a></td>" +
                    "</tr>");
        });
        $('#tablacategoria').DataTable().destroy();
        paginador();
    });
}


function CargarProductosParaEstadoInventario() {
    debugger;
    var tabladatos = $('#datosInv');
    var route = "/listadeproducto/";
    $('#datosInv').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var codB = "";
            if (value.codigoDeBarra !== null) {
                codB = value.codigoDeBarra;
            }
            var codI = "";
            if (value.codigoInterno !== null) {
                codI = value.codigoInterno;
            }
            var col = "";
            if (value.color !== null) {
                col = value.color;
            }
            var tam = "";
            if (value.tamano !== null) {
                tam = value.tamano;
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.categoria + "</td>" +
                    "<td>" + codB + "</td>" +
                    "<td>" + codI + "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.precioVenta + "</td>" +
                    "<td>" + value.origen + "</td>" +
                    "<td>" + col + "</td>" +
                    "<td>" + tam + "</td>" +
                    "<td>" + value.marca + "</td>" +
                    "<td><a class='btn btn-primary' href='javascript:generarreporteEstadoInventario(" + value.id + ")'>Generar</a></td>" +
                    "</tr>");
        });
        $('#tablacategoria').DataTable().destroy();
        paginador();
    });
}

function CargarProductosParaMovimientoInventario() {
    debugger;
    var tabladatos = $('#datosMov');
    var route = "/listadeproducto/";
    $('#datosMov').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var codB = "";
            if (value.codigoDeBarra !== null) {
                codB = value.codigoDeBarra;
            }
            var codI = "";
            if (value.codigoInterno !== null) {
                codI = value.codigoInterno;
            }
            var col = "";
            if (value.color !== null) {
                col = value.color;
            }
            var tam = "";
            if (value.tamano !== null) {
                tam = value.tamano;
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.categoria + "</td>" +
                    "<td>" + codB + "</td>" +
                    "<td>" + codI + "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.precioVenta + "</td>" +
                    "<td>" + value.origen + "</td>" +
                    "<td>" + col + "</td>" +
                    "<td>" + tam + "</td>" +
                    "<td>" + value.marca + "</td>" +
                    "<td><a class='btn btn-primary' href='javascript:generarreporteMovimientoInventario(" + value.id + ")'>Generar</a></td>" +
                    "</tr>");
        });
        $('#tablacategoria').DataTable().destroy();
        paginador();
    });
}

function generarreporteMovimientoInventario(idProducto) {
    debugger;
    var fechafin = $('#Fechafin').val();
    var fechaninio = $('#fechainicio').val();
    var sucursal = $('#almacen').val();
    var almacen = $('#almacenV').val();
    var usuariologueado = $('#iddelempleado').val();
    var idProducto = idProducto;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }

    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }


    if (sucursal === "null" && almacen === "null") {
        window.open("/ReportMovimientoInventarioProducto/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + idProducto);
    }

    if (sucursal !== "null" && almacen === "null") {
        window.open("/ReportMovimientoInventarioConSucursalProducto/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + idProducto);

    }

    if (sucursal !== "null" && almacen !== "null") {
        window.open("/ReportMovimientoInventarioConSucursalConAlmacenProducto/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + almacen + "/" + idProducto);

    }

}


$("#generarreportemoviemientoinventario").click(function () {
    debugger;
    var fechafin = $('#Fechafin').val();
    var fechaninio = $('#fechainicio').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var usuariologueado = $('#iddelempleado').val();
    var sucursal = $("#almacen").val();
    var almacen = $("#almacenV").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

    if (sucursal === "null" && almacen === "null") {
        window.open("/ReportMovimientoInventario/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
    }

    if (sucursal !== "null" && almacen === "null") {
        window.open("/ReportMovimientoInventarioConSucursal/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);

    }

    if (sucursal !== "null" && almacen !== "null") {
        window.open("/ReportMovimientoInventarioConSucursalConAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + almacen);

    }


});

function generarreporteEstadoInventario(idProducto) {
    var idSucursal = $('#almacen').val();
    var idAlmacen = $('#almacenV').val();
    var idempleado = $('#iddelempleado').val();
    var idProducto = idProducto + "";
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (idAlmacen === "null" && idSucursal === "null") {
        //NO SELECCIONO NADA!!
        window.open("/reporteEstadoInventarioConProducto/" + idempleado + "/" + extencion + "/" + idProducto);
    }
    if (idAlmacen === "null" && idSucursal !== "null") {
        //SELECCIONO 1 SUCURSAL!!
        window.open("/reporteEstadoInventarioConSucursalConProducto/" + idempleado + "/" + extencion + "/" + idSucursal + "/" + idProducto);
    }
    if (idAlmacen !== "null" && idSucursal !== "null") {
        //SELECCIONO 1 ALMACEN Y 1 SUCURSAL!!
        debugger;
        window.open("/reporteEstadoInventarioConSucursalConAlmacenConProducto/" + idempleado + "/" + extencion + "/" + idSucursal + "/" + idAlmacen + "/" + idProducto);
    }
}


$('#estadoInventario').click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var idempleado = $('#iddelempleado').val();
    var extencion;
    var idSucursal = $('#almacen').val();
    var idAlmacen = $('#almacenV').val();
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }

    if (idSucursal === "null" && idAlmacen === "null") {
        //NO SELECCIONO NADA
        window.open("/reporteEstadoInventario/" + idempleado + "/" + extencion);
    }

    if (idAlmacen === "null" && idSucursal !== "null") {
        //SELECCIONO 1 SUCURSAL!!
        window.open("/reporteEstadoInventarioConSucursal/" + idempleado + "/" + extencion + "/" + idSucursal);
    }

    if (idAlmacen !== "null" && idSucursal !== "null") {
        //SELECCIONO 1 ALMACEN Y 1 SUCURSAL!!

        window.open("/reporteEstadoInventarioConSucursalConAlmacen/" + idempleado + "/" + extencion + "/" + idSucursal + "/" + idAlmacen);
    }


});

$('#analisisabc').click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var idempleado = $('#iddelempleado').val();
    var extencion;
    var idSucursal = $('#almacen').val();
    var idAlmacen = $('#almacenV').val();
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }

    if (idSucursal === "null" && idAlmacen === "null") {
        //NO SELECCIONO NADA
        window.open("/reporteAnalisisAbc/" + idempleado + "/" + extencion);
    }

    if (idAlmacen === "null" && idSucursal !== "null") {
        //SELECCIONO 1 SUCURSAL!!
        window.open("/reporteAnalisisAbcConSucursal/" + idempleado + "/" + extencion + "/" + idSucursal);
    }

    if (idAlmacen !== "null" && idSucursal !== "null") {
        //SELECCIONO 1 ALMACEN Y 1 SUCURSAL!!

        window.open("/reporteAnalisisAbcConSucursalConAlmacen/" + idempleado + "/" + extencion + "/" + idSucursal + "/" + idAlmacen);
    }


});


$('#analisiseqq').click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var idempleado = $('#iddelempleado').val();
    var extencion;
    var idSucursal = $('#almacen').val();
    var idAlmacen = $('#almacenV').val();
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }

    if (idSucursal === "null" && idAlmacen === "null") {
        //NO SELECCIONO NADA
        window.open("/reporteeqq/" + idempleado + "/" + extencion);
    }

    if (idAlmacen === "null" && idSucursal !== "null") {
        //SELECCIONO 1 SUCURSAL!!
        window.open("/reporteeqqConSucursal/" + idempleado + "/" + extencion + "/" + idSucursal);
    }

    if (idAlmacen !== "null" && idSucursal !== "null") {
        //SELECCIONO 1 ALMACEN Y 1 SUCURSAL!!

        window.open("/reporteeqqConSucursalConAlmacen/" + idempleado + "/" + extencion + "/" + idSucursal + "/" + idAlmacen);
    }


});


function paginador() {
    $('#tablacategoria tfoot th').each(function () {
        var title = $(this).text();
        if (title == "") {
        } else {
            $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
        }
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

///----------------------------------------------//
//-------------------REPORTES--------------------//
//-----------------------------------------------//
//Ventas del usuario actual
$("#generarreportecaja").click(function () {
    debugger;
    var fechafin = $('#Fechafin').val();
    var fechaninio = $('#fechainicio').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#iddelempleado').val();
    var idalmacen = $("#almacen1").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    if (idalmacen == "null") {
        window.open("/reportusuarioactualPorUsuarioNA/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion);
    } else {
        window.open("/reportusuarioactual/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion + "/" + idalmacen);
    }
});
//Detalles de ventas del usuario actual
$("#generarreportecajatotales").click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafins').val();
    var fechaninio = $('#fechainicios').val();
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#iddelempleado').val();
    var idalmacen = $("#almacen1").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    if (idalmacen == "null") {
        window.open("/reportDetalleVentaNO/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion);
    } else {
        window.open("/reportDetalleVenta/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion + "/" + idalmacen);
    }
});


$("#detalleventasporusuario").click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafins').val();
    var fechaninio = $('#fechainicios').val();
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#empleado').val();
    var sucursal = $("#almacen").val();
    var almacen = $('#almacenV').val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

    if (sucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
        usuariologueado = $('#iddelempleado').val();

        window.open("/reporteDetalleVentaSinSucursalSinEmpleadoSinSucursal/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
        return;
    }

    if (sucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
        window.open("/ventaDetalleVentaConSucursalSinAlmacenSinEmpleado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);

    }

    if (sucursal !== "null" && idempleado !== "" && almacen === "null") {
        //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
        //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
        window.open("/reportDetalleVentaConEmpleadoConSucursalSinAlmacen/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
    }


    if (sucursal === "null" && idempleado !== "" && almacen == "null") {
//        SELECCIONO UN EMPLEADO!!
//        reporte de todas las sucursales pero de un solo empleado
        var idempleado = $('#empleado').val();
        window.open("/reportDetalleVentaNO/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion);
    }

    if (sucursal !== "null" && idempleado !== "" && almacen !== "null") {
        //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

        var idempleado = $('#empleado').val();
        window.open("/reportDetalleVentaConSucursalConEmpleadoConAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + sucursal + "/" + almacen);
    }
});


$("#generarreportecajatotalesporusuario").click(function () {
    debugger;
    var fechafin = $('#Fechafinss').val();
    var fechaninio = $('#fechainicioss').val();
    var idempleado = $('#empleado').val();
    var idsucursal = $('#almacen').val();
    var usuariologueado = $('#iddelempleado').val();
    var almacen = $('#almacenV').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

    //===================================================================================================

    if (idsucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
        usuariologueado = $('#iddelempleado').val();
        window.open("/reportusuarioactual/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
        return;
    }

    if (idsucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
        window.open("/reporteVentaConSucursalSinAlmacenSinEmpleado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + idsucursal);

    }

    if (idsucursal !== "null" && idempleado !== "" && almacen === "null") {
        //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
        //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
        window.open("/reporteVentaConSucursalSinAlmacenConEmpleado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal);
    }

    if (idsucursal === "null" && idempleado !== "" && almacen === "null") {
//        SELECCIONO UN EMPLEADO!!
//        reporte de todas las sucursales pero de un solo empleado

        window.open("/reporteVentaSinSucursalSinAlmacenConEmpleado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion);
    }

    if (idsucursal !== "null" && idempleado !== "" && almacen !== "null") {
        //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

        window.open("/reporteVentaConSucursalConAlmacenConEmpleado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal + "/" + almacen);
    }



//    if (idempleado == 0) {   //no selecciono nada
//
//        if (idsucursal == "null") {
//            window.open("/reportusuarioactual/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
//        } else {
//            window.open("/reportusuarioactual/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + idsucursal);
//        }
//    } else {
//        
//        window.open("/reportusuarioactualPorUsuarioNA/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion);
//    }

});

$("#egresoporusuario").click(function () {
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafinegreso').val();
    var fechaini = $('#fechainicioegreso').val();
    var idusuarioLogueado = $('#iddelempleado').val();
    var idempleado = $('#empleado').val();
    var idtipoegreso = $("#egresos").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaini || !fechaini.trim().length) {
        return swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    var validacion = fechaCorrecta(fechaini, fechafin);
    if (validacion == false) {
        return swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    if (idempleado == "" && idtipoegreso == "") {
        return window.open("/reporteEgresoCompraSinEmpleado/" + fechaini + "/" + fechafin + "/" + idusuarioLogueado + "/" + extencion);
    }
    if (idempleado !== "" && idtipoegreso == "") {
        return window.open("/reporteEgresoCompraConEmpleado/" + fechaini + "/" + fechafin + "/" + idusuarioLogueado + "/" + idempleado + "/" + extencion);
    }
    if (idempleado == "" && idtipoegreso !== "") {
        return window.open("/reporteEgresoConTipo/" + fechaini + "/" + fechafin + "/" + idusuarioLogueado + "/" + extencion + "/" + idtipoegreso);
    }
    if (idempleado !== "" && idtipoegreso !== "") {
        return window.open("/reporteEgresoConEmpleadoConTipo/" + fechaini + "/" + fechafin + "/" + idempleado + "/" + idusuarioLogueado + "/" + extencion + "/" + idtipoegreso);
    }
});

$("#egreso").click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafinegreso').val();
    var fechaini = $('#fechainicioegreso').val();
    var idusuarioLogueado = $('#iddelempleado').val();
    var idempleado = $('#iddelempleado').val();

    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaini || !fechaini.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaini, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    window.open("/reporteEgresoCompra/" + fechaini + "/" + fechafin + "/" + idusuarioLogueado + "/" + idempleado + "/" + extencion);
});


$("#ingreso").click(function () {
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafinegreso').val();
    var fechaini = $('#fechainicioegreso').val();
    var usuariologeado = $('#iddelempleado').val();
    var idempleado = $('#iddelempleado').val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaini || !fechaini.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaini, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    window.open("/reporteIngreso/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + idempleado + "/" + extencion);
});

$("#ingresoPorUsuario").click(function () {
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafinegreso').val();
    var fechaini = $('#fechainicioegreso').val();
    var usuariologeado = $('#iddelempleado').val();
    var idempleado = $('#empleado').val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaini || !fechaini.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaini, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    if (idempleado === "") {
        //no selecciono nada
        window.open("/reporteIngresoSinEmpleado/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + extencion);
    } else {
        window.open("/reporteIngreso/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + idempleado + "/" + extencion);

    }

});

function generarreportekardex(idProducto) {
    var fechafin = $('#Fechafin').val();
    var fechaninio = $('#fechainicio').val();
    var idSucursal = $('#almacen').val();
    var idAlmacen = $('#almacenV').val();
    var idempleado = $('#iddelempleado').val();
    var idProducto = idProducto;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

//    if (idAlmacen === "null" && idSucursal === "null") {
//        //NO SELECCIONO NADA!!
//        window.open("/reportKardexInventario/" + fechaninio + "/" + fechafin + "/" + idProducto + "/" + idempleado + "/" + extencion);
//    }
//    
//    if (idAlmacen === "null" && idSucursal !== "null") {
//        //SELECCIONO 1 SUCURSAL!!
//        window.open("/reportKardexInventarioConSucursal/" + fechaninio + "/" + fechafin + "/" + idProducto + "/" + idempleado + "/" + extencion + "/" + idSucursal);
//    }

    if (idSucursal == "null") {
        return swal({title: "Adevertencia!",
            text: "Seleccione la sucursal",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }

    if (idAlmacen == "null") {
        return swal({title: "Adevertencia!",
            text: "Seleccione un Almacen",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    //    if (idAlmacen !== "null" && idSucursal !== "null") {
    //SELECCIONO 1 ALMACEN Y 1 SUCURSAL!!
    window.open("/reportKardexInventarioConSucursalConAlmacen/" + fechaninio + "/" + fechafin + "/" + idProducto + "/" + idempleado + "/" + extencion + "/" + idAlmacen + "/" + idSucursal);
    //    } 
}

function fechaCorrecta(fechaInicio, fechaFin) {
//    //Split de las fechas recibidas para separarlas
//    var x = fechaInicio.split("-");
//    var z = fechaFin.split("-");
//    //Cambiamos el orden al formato americano, de esto dd/mm/yyyy a esto mm/dd/yyyy
//    fechaInicio = x[1] + "-" + x[2] + "-" + x[0];
//    fechaFin = z[1] + "-" + z[2] + "-" + z[0];
    //Comparamos las fechas
    if (Date.parse(fechaInicio) > Date.parse(fechaFin)) {
        return false;
    } else {
        return true;
    }
}


$("#compraCredito").click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;

    var credito = document.getElementById('cred1').checked;
    var efectivo = document.getElementById('cred2').checked;

    if (credito == true) {
        var fechafin = $('#Fechafin1').val();
        var fechaini = $('#fechainicio1').val();
        var usuariologeado = $('#iddelempleado').val();
        var empleado = $("#empleado").val();
        var proveedor = $("#proveedor").val();

        var extencion;
        if (pdf == true) {
            extencion = 0;
        }
        if (xlsx == true) {
            extencion = 1;
        }
        if (!fechafin || !fechafin.trim().length && !fechaini || !fechaini.trim().length) {
            swal({title: "DATOS ERRONEOS",
                text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }
        var validacion = fechaCorrecta(fechaini, fechafin);
        if (validacion == false) {
            swal({title: "DATOS ERRONEOS",
                text: "La fecha final no debe ser menor a la fecha de inicio",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }

        if (empleado === "" && proveedor === "null") {
            window.open("/reporteCompraCredito/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + extencion);
        }

        if (empleado !== "" && proveedor === "null") {
            window.open("/reporteCompraCreditoConEmpleadoSinProveedor/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + empleado + "/" + extencion);
        }

        if (empleado === "" && proveedor !== "null") {
            window.open("/reporteCompraCreditoSinEmpleadoConProveedor/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + proveedor + "/" + extencion);
        }

        if (empleado !== "" && proveedor !== "null") {
            window.open("/reporteCompraCreditoConEmpleadoConProveedor/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + empleado + "/" + proveedor + "/" + extencion);
        }
    }

    if (efectivo == true) {
        var fechafin = $('#Fechafin1').val();
        var fechaini = $('#fechainicio1').val();
        var usuariologeado = $('#iddelempleado').val();
        var empleado = $("#empleado").val();
        var proveedor = $("#proveedor").val();
        var extencion;
        if (pdf == true) {
            extencion = 0;
        }
        if (xlsx == true) {
            extencion = 1;
        }
        if (!fechafin || !fechafin.trim().length && !fechaini || !fechaini.trim().length) {
            swal({title: "DATOS ERRONEOS",
                text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }
        var validacion = fechaCorrecta(fechaini, fechafin);
        if (validacion == false) {
            swal({title: "DATOS ERRONEOS",
                text: "La fecha final no debe ser menor a la fecha de inicio",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }
        if (empleado === "" && proveedor === "null") {
            window.open("/reporteCompraEfectivo/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + extencion);
        }

        if (empleado !== "" && proveedor === "null") {
            window.open("/reporteCompraEfectivoConEmpleadoSinProveedor/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + empleado + "/" + extencion);
        }

        if (empleado === "" && proveedor !== "null") {
            window.open("/reporteCompraEfectivoSinEmpleadoConProveedor/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + proveedor + "/" + extencion);
        }

        if (empleado !== "" && proveedor !== "null") {
            window.open("/reporteCompraEfectivoConEmpleadoConProveedor/" + fechaini + "/" + fechafin + "/" + usuariologeado + "/" + empleado + "/" + proveedor + "/" + extencion);
        }
    }


});



function cargaralmacen() {
    //carga lasucursal
    $('#almacen')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione una Sucursal</option>')
            .val('null');
    $('#almacen').material_select();
    var route = "/listarsucrusal/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacen').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacen').material_select();
        });
    });
}


function cargarProveedor() {
    //carga lasucursal
    $('#proveedor')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un proveedor</option>')
            .val('null');
    $('#proveedor').material_select();
    var route = "/listarProveedor/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#proveedor').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#proveedor').material_select();
        });
    });
}


function cargaralmacenV() {
    //carga todos los almacenes
    $('#almacenV')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un Almacen</option>')
            .val('null');
    $('#almacenV').material_select();

}


function cargarSucursalVerdadero() {
    debugger;

    $("select#empleado").change(function () {
        var idempleado = $(this).val();



        if (idempleado !== "") {
            var idsucursal = "";
            var route = "/listarSucursalCombo/" + idempleado + "";
            $('#almacen')
                    .find('option')
                    .remove()
                    .end()
                    .val('null');
            $('#almacen').material_select();
            $.get(route, function (res) {
                $(res).each(function (key, value) {

                    idsucursal = value.id;
                    $('#almacen').append('<option value=' + value.id + ' >' + value.sucursal + '</option>');
                    $('#almacen').material_select();

                    var route = "/listarAlmacenS/" + idsucursal + "";
                    $('#almacenV')
                            .find('option')
                            .remove()
                            .end()
                            .append('<option value="null">Selecione un Almacen</option>')
                            .val('null');
                    $('#almacenV').material_select();
                    $.get(route, function (res) {
                        $(res).each(function (key, s) {

                            $('#almacenV').append('<option value=' + s.id + ' >' + s.nombre + '</option>');
                            $('#almacenV').material_select();
                        });
                    });


                });
            });



        } else {

//cargaralmacenVerdadero();            
//cargaralmacendesucursal();            


            cargaralmacen();



















        }

    });
}



function cargaralmacenVerdadero() {


    $("select#almacen").change(function () {

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

            $(res).each(function (key, value) {

                $('#almacenV').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
                $('#almacenV').material_select();
            });
        });
    });








//    $('#almacenV')
//            .find('option')
//            .remove()
//            .end()
//            .append('<option value="null">Selecione un Almacen</option>')
//            .val('null');
//    $('#almacenV').material_select();
//    debugger;
//    var route = "/listarAlmacenCombo/";
//    $.get(route, function (res) {
//        $(res).each(function (key, value) {
//            $('#almacenV').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
//            $('#almacenV').material_select();
//        });
//    });
}






function cargaralmacendesucursal() {
    var idpunto = $("#iddelpuntoventa").val();
    $('#almacen1')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un Almacen</option>')
            .val('null');
    $('#almacen1').material_select();
    var route = "/listaralmacensucursal/" + idpunto;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacen1').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacen1').material_select();
        });
    });


}




$("#flujoporusuarios").click(function () {
    var fechafin = $('#Fechafinfu').val();
    var fechaninio = $('#fechainiciofu').val();
    var idempleado = $('#empleados').val();
    var usuariologueado = $('#iddelempleado').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var sucursal = $("#almacen").val();
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        return swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        return swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (idempleado === "" && sucursal === "null") {
        //no selecciono nada
        // el reporte se llama reporteFlujoCajaCualquierEmpleado
        window.open("/FlujocajaCualquierEmpleado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
    }

    if (idempleado !== "" && sucursal === "null") {
        //selecciono un empleado y no sucursal
        //el reporte se llama reporteFLujoCajaSinSucursalConEmpleado
        window.open("/FlujocajaporusurioConEmpleadoSinSucursal/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion);
    }

    if (idempleado !== "" && sucursal !== "null") {
        //selecciono los 2
        // el reporte se llama reporteFlujoCaja
        window.open("/FlujocajaporusurioConEmpleadoConSucursal/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
    }

    if (idempleado === "" && sucursal !== "null") {
        window.open("/FlujocajaporusurioSinEmpleadoConSucursal/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);

    }
});


$("#flujoporusuaeio").click(function () {
    var fechafin = $('#Fechafinf').val();
    var fechaninio = $('#fechainiciof').val();
    var idempleado = $('#iddelempleado').val();
    var usuariologueado = $('#iddelempleado').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;

    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

    window.open("/FlujocajaporusurioConEmpleadoSinSucursal/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion);
});
























$("#vetascomisionactual").click(function () {
    debugger;
    var fechafin = $('#Fechafin').val();
    var fechaninio = $('#fechainicio').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#iddelempleado').val();
    var idalmacen = $("#almacen1").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    if (idalmacen == "null") {
        window.open("/reportusuarioactualPorUsuarioNAComision/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion);
    } else {
        window.open("/reportusuarioactualComision/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion + "/" + idalmacen);
    }
});



$("#vetascomisionporusuario").click(function () {
    debugger;
    var fechafin = $('#Fechafinss').val();
    var fechaninio = $('#fechainicioss').val();
    var idempleado = $('#empleado').val();
    var idsucursal = $('#almacen').val();
    var usuariologueado = $('#iddelempleado').val();
    var almacen = $('#almacenV').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

    //===================================================================================================

    if (idsucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
        usuariologueado = $('#iddelempleado').val();
        window.open("/reportusuarioactualComision/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
        return;
    }

    if (idsucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
        window.open("/reporteVentaConSucursalSinAlmacenSinEmpleadoComision/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + idsucursal);

    }

    if (idsucursal !== "null" && idempleado !== "" && almacen === "null") {
        //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
        //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
        window.open("/reporteVentaConSucursalSinAlmacenConEmpleadoComision/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal);
    }

    if (idsucursal === "null" && idempleado !== "" && almacen === "null") {
//        SELECCIONO UN EMPLEADO!!
//        reporte de todas las sucursales pero de un solo empleado

//        window.open("/reporteVentaSinSucursalSinAlmacenConEmpleado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion);
    }

    if (idsucursal !== "null" && idempleado !== "" && almacen !== "null") {
        //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

        window.open("/reporteVentaConSucursalConAlmacenConEmpleadoComision/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal + "/" + almacen);
    }
});




$("#garantiasalquileractual").click(function () {
    debugger;
    var fechafin = $('#Fechafin').val();
    var fechaninio = $('#fechainicio').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#iddelempleado').val();
    var idalmacen = $("#almacen1").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var devueltos = document.getElementById('test3').checked;
    var filt;
    if (devueltos) {
        filt = 0;
    } else {
        filt = 2;
    }
    if (idalmacen == "null") {
        window.open("/reportusuarioactualPorUsuarioAlquiler/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + extencion + "/" + filt);
    } else {
        window.open("/reportusuarioactualAlquiler/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + +extencion + "/" + idalmacen + "/" + filt);
    }
});


$("#vetasalquilerporusuario").click(function () {
    debugger;
    var fechafin = $('#Fechafinss').val();
    var fechaninio = $('#fechainicioss').val();
    var idempleado = $('#empleado').val();
    var idsucursal = $('#almacen').val();
    var usuariologueado = $('#iddelempleado').val();
    var almacen = $('#almacenV').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var devueltos = document.getElementById('test3').checked;
    var filt;
    if (devueltos) {
        filt = 0;
    } else {
        filt = 2;
    }
    //===================================================================================================

    if (idsucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
        usuariologueado = $('#iddelempleado').val();
        window.open("/reportAlquilerSinSucursalSinAlmacenSinEmpleado/" + fechaninio
                + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + filt);
        return;
    }

    if (idsucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
        window.open("/reporteVentaConSucursalSinAlmacenSinEmpleadoAlquiler/" + fechaninio + "/"
                + fechafin + "/" + usuariologueado + "/" + extencion + "/" + idsucursal + "/" + filt);

    }

    if (idsucursal !== "null" && idempleado !== "" && almacen === "null") {
        //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
        //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
        window.open("/reporteVentaConSucursalSinAlmacenConEmpleadoAlquiler/" + fechaninio + "/" + fechafin +
                "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal + "/" + filt);
    }

    if (idsucursal !== "null" && idempleado !== "" && almacen !== "null") {
        //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

        window.open("/reporteVentaConSucursalConAlmacenConEmpleadoAlquiler/" + fechaninio + "/" + fechafin + "/"
                + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal + "/" + almacen + "/" + filt);
    }
});




$("#reportecompraactual").click(function () {
    debugger;
    var fechafin = $('#Fechafins').val();
    var fechaninio = $('#fechainicios').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#iddelempleado').val();
    var idalmacen = $("#almacen1").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    if (idalmacen == "null") {
        window.open("/reportDetalleCompraNO/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion);
    } else {
        window.open("/reportDetalleCompra/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + idempleado + "/" + extencion + "/" + idalmacen);
    }
});

$("#detallecompras").click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafins').val();
    var fechaninio = $('#fechainicios').val();
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#empleado').val();
    var sucursal = $("#almacen").val();
    var almacen = $('#almacenV').val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

    if (sucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
        usuariologueado = $('#iddelempleado').val();

        window.open("/reporteDetalleCompraSinSucursalSinEmpleadoSinSucursal/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
        return;
    }

    if (sucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
        window.open("/compraDetalleCompraConSucursal/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);

    }

    if (sucursal !== "null" && idempleado !== "" && almacen === "null") {
        //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
        //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
        window.open("/compraDetalleCompraConSucursalSinAlmacenConEmpleado/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
    }


//    if (sucursal === "null" && idempleado !== "" && almacen == "null") {
////        SELECCIONO UN EMPLEADO!!
////        reporte de todas las sucursales pero de un solo empleado
//        var idempleado = $('#empleado').val();
//        window.open("/reportDetalleVentaNO/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion);
//    //aqui nunca va a entrar!!
//    }

    if (sucursal !== "null" && idempleado !== "" && almacen !== "null") {
        //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

        var idempleado = $('#empleado').val();
        window.open("/reportDetalleCompraConSucursalConEmpleadoConAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + sucursal + "/" + almacen);
    }
});



$("#txnInventario").click(function () {
    debugger;
    var fechafinal1 = $('#Fechafinegreso').val();
    var fechainicio1 = $('#fechainicioegreso').val();
    var idempleado1 = $('#empleado').val();
    var motivo1 = $('#motivo').val();

    if (!fechafinal1 || !fechainicio1.trim().length && !fechainicio1 || !fechainicio1.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechainicio1, fechafinal1);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    $('#datos').empty();

    if (idempleado1 === "" && motivo1 === "") {
        $('#datos').empty();

        var route = "/listarinventarioSoloFecha/" + fechainicio1 + "/" + fechafinal1;
        var tabladatos = $('#datos');
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                debugger;
                var tipo = "";
                var fecha = "";
                var hora = "";
                if (value.tipo != null) {
                    tipo = value.tipo;
                }
                if (value.fecha != null) {
                    fecha = value.fecha;
                }
                if (value.hora != null) {
                    hora = value.hora;
                }
                if (value.estado == 1) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + fecha + "</td>" +
                            "<td>" + hora + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" + value.motivo + "</td>" +
                            "<td>" + value.glosa.substring(0, 40) + "</td>" +
                            "<td>" +
                            "<button class='btn btn-danger' value=" + value.id + " OnClick='imprimir(this);' title='imprimir'>" +
                            "<i class='material-icons'>print</i></button>" +
                            "</td>" +
                            "</tr>");
                }
            });

            paginador();
        });

    }


    if (idempleado1 !== "" && motivo1 === "") {
        $('#datos').empty();

        var route = "/listarinventarioConEmpleadoSinMotivo/" + idempleado1 + "/" + iddelpuntoventa + "/" + fechainicio1 + "/" + fechafinal1;
        var tabladatos = $('#datos');
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                debugger;
                var tipo = "";
                var fecha = "";
                var hora = "";
                if (value.tipo != null) {
                    tipo = value.tipo;
                }
                if (value.fecha != null) {
                    fecha = value.fecha;
                }
                if (value.hora != null) {
                    hora = value.hora;
                }
                if (value.estado == 1) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + fecha + "</td>" +
                            "<td>" + hora + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" + value.motivo + "</td>" +
                            "<td>" + value.glosa.substring(0, 40) + "</td>" +
                            "<td>" +
                            "<button class='btn btn-danger' value=" + value.id + " OnClick='imprimir(this);' title='imprimir'>" +
                            "<i class='material-icons'>print</i></button>" +
                            "</td>" +
                            "</tr>");
                }
            });

            paginador();
        });

    }



    if (idempleado1 === "" && motivo1 !== "") {
        $('#datos').empty();

        var route = "/listarinventarioSoloMotivo/" + motivo1 + "/" + fechainicio1 + "/" + fechafinal1;
        var tabladatos = $('#datos');
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                debugger;
                var tipo = "";
                var fecha = "";
                var hora = "";
                if (value.tipo != null) {
                    tipo = value.tipo;
                }
                if (value.fecha != null) {
                    fecha = value.fecha;
                }
                if (value.hora != null) {
                    hora = value.hora;
                }
                if (value.estado == 1) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + fecha + "</td>" +
                            "<td>" + hora + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" + value.motivo + "</td>" +
                            "<td>" + value.glosa.substring(0, 40) + "</td>" +
                            "<td>" +
                            "<button class='btn btn-danger' value=" + value.id + " OnClick='imprimir(this);' title='imprimir'>" +
                            "<i class='material-icons'>print</i></button>" +
                            "</td>" +
                            "</tr>");
                }
            });

            paginador();
        });

    }



    if (idempleado1 !== "" && motivo1 !== "") {
        $('#datos').empty();

        var route = "/listarinventarioConEmpleadoConPuntoConMotivo/" + idempleado1 + "/" + iddelpuntoventa + "/" + fechainicio1 + "/" + fechafinal1 + "/" + motivo1;
        var tabladatos = $('#datos');
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                debugger;
                var tipo = "";
                var fecha = "";
                var hora = "";
                if (value.tipo != null) {
                    tipo = value.tipo;
                }
                if (value.fecha != null) {
                    fecha = value.fecha;
                }
                if (value.hora != null) {
                    hora = value.hora;
                }
                if (value.estado == 1) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + fecha + "</td>" +
                            "<td>" + hora + "</td>" +
                            "<td>" + value.nombre + "</td>" +
                            "<td>" + value.motivo + "</td>" +
                            "<td>" + value.glosa.substring(0, 40) + "</td>" +
                            "<td>" +
                            "<button class='btn btn-danger' value=" + value.id + " OnClick='imprimir(this);' title='imprimir'>" +
                            "<i class='material-icons'>print</i></button>" +
                            "</td>" +
                            "</tr>");
                }
            });

            paginador();
        });

    }


});

function paginador() {
    // Setup - add a text input to each footer cell
    $('#tablacategoria tfoot th').each(function () {
        var title = $(this).text();
        if (title == "") {
        } else {
            $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
        }
    });
    var table = $('#tablacategoria').DataTable({
        pagingType: "full_numbers",
        retrieve: true,
        order: [0, 'desc'],
        responsive: true,
        lengthMenu: [[10, 20, 40], [10, 20, 40]]
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

function imprimir(btn) {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }

    var iddelempleado = $('#iddelempleado').val();

    if ($("#perfilpuedeImprimir").val() === "1") {
        window.open("/reporteinventario/" + btn.value + "/" + iddelempleado + "/" + extencion);
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

$("#ventaAnuladaELiminada").click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;

    var anulada = document.getElementById('cred1').checked;
    var eliminada = document.getElementById('cred2').checked;

    if (anulada == true) {
        var fechafin = $('#Fechafin1').val();
        var fechaninio = $('#fechainicio1').val();
        var usuariologueado = $('#iddelempleado').val();
        var idempleado = $("#empleado").val();
        var idsucursal = $("#almacen").val();
        var almacen = $('#almacenV').val();

        var extencion;
        if (pdf == true) {
            extencion = 0;
        }
        if (xlsx == true) {
            extencion = 1;
        }
        if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
            swal({title: "DATOS ERRONEOS",
                text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }
        var validacion = fechaCorrecta(fechaninio, fechafin);
        if (validacion == false) {
            swal({title: "DATOS ERRONEOS",
                text: "La fecha final no debe ser menor a la fecha de inicio",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }

        if (idsucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
            usuariologueado = $('#iddelempleado').val();
            window.open("/listadoVentasAnuladas/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
            return;
        }

        if (idsucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
            window.open("/listadoVentasSinEmpleadoConSucursalSinAlmacenAnulada/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + idsucursal);

        }

        if (idsucursal !== "null" && idempleado !== "" && almacen === "null") {
            //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
            //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
            window.open("/listadoVentasConEmpleadoConSucursalSinAlmacenAnulada/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal);
        }

        if (idsucursal === "null" && idempleado !== "" && almacen === "null") {
//        SELECCIONO UN EMPLEADO!!
//        reporte de todas las sucursales pero de un solo empleado

            window.open("/listadoVentasConEmpleadoSinSucursalSinAlmacenAnulada/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion);
        }

        if (idsucursal !== "null" && idempleado !== "" && almacen !== "null") {
            //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

            window.open("/listadoVentasConEmpleadoConSucursalConAlmacenAnulada/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal + "/" + almacen);
        }
    }

    if (eliminada == true) {
        var fechafin = $('#Fechafin1').val();
        var fechaninio = $('#fechainicio1').val();
        var usuariologueado = $('#iddelempleado').val();
        var idempleado = $("#empleado").val();
        var idsucursal = $("#almacen").val();
        var almacen = $('#almacenV').val();
        var extencion;
        if (pdf == true) {
            extencion = 0;
        }
        if (xlsx == true) {
            extencion = 1;
        }
        if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
            swal({title: "DATOS ERRONEOS",
                text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }
        var validacion = fechaCorrecta(fechaninio, fechafin);
        if (validacion == false) {
            swal({title: "DATOS ERRONEOS",
                text: "La fecha final no debe ser menor a la fecha de inicio",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "OK",
                closeOnConfirm: true});
            return;
        }
        if (idsucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
            usuariologueado = $('#iddelempleado').val();
            window.open("/listadoVentasEliminadas/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
            return;
        }

        if (idsucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
            window.open("/listadoVentasSinEmpleadoConSucursalSinAlmacenEliminadas/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + idsucursal);

        }

        if (idsucursal !== "null" && idempleado !== "" && almacen === "null") {
            //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
            //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
            window.open("/listadoVentasConEmpleadoConSucursalSinAlmacenEliminar/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal);
        }

        if (idsucursal === "null" && idempleado !== "" && almacen === "null") {
//        SELECCIONO UN EMPLEADO!!
//        reporte de todas las sucursales pero de un solo empleado

            window.open("/listadoVentasConEmpleadoSinSucursalSinAlmacenEliminadas/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion);
        }

        if (idsucursal !== "null" && idempleado !== "" && almacen !== "null") {
            //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

            window.open("/listadoVentasConEmpleadoConSucursalConAlmacenEliminada/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + idempleado + "/" + extencion + "/" + idsucursal + "/" + almacen);
        }
    }


});


$("#ventaCredito").click(function () {
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafins').val();
    var fechaninio = $('#fechainicios').val();
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#empleado').val();
    var sucursal = $("#almacen").val();
    var cliente = $("#clienteslist option[value='" + $('#cliNombre').val() + "']").attr('data-id');
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        return swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        return swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    var reporte = document.getElementById('test3').checked;
    var reporte2 = document.getElementById('test4').checked;
    if (reporte) {
        if (sucursal === "null" && idempleado === "" && typeof cliente === "undefined") {
            usuariologueado = $('#iddelempleado').val();
            return window.open("/reporteVentaCreditoSinEmpleadoSinSucursalSinCliente/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
        }
        if (sucursal !== "null" && idempleado === "" && typeof cliente === "undefined") {
            return window.open("/reporteVentaCreditoSinEmpleadoConSucursalSinCliente/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
        }
        if (sucursal !== "null" && idempleado !== "" && typeof cliente === "undefined") {
            return window.open("/reporteVentaCreditoConEmpleadoConSucursalSinCliente/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
        }
        if (sucursal !== "null" && idempleado !== "" && typeof cliente !== "undefined") {
            var idempleado = $('#empleado').val();
            return window.open("/reporteVentaCreditoConEmpleadoConSucursalConCliente/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + cliente);
        }
        if (sucursal === "null" && idempleado === "" && typeof cliente !== "undefined") {
            return window.open("/reporteVentaCreditoSinEmpleadoSinSucursalConCliente/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + cliente);
        }
    } else if (reporte2) {
        if (sucursal === "null" && idempleado === "" && typeof cliente === "undefined") {
            return window.open("/reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalSinCliente/" + usuariologueado + "/" + extencion + "/" + fechaninio + "/" + fechafin);
        }
        if (sucursal !== "null" && idempleado === "" && typeof cliente !== "undefined") {
            return  window.open("/reporteCreditoVentaAtrasadasSinEmpleadoConSucursalConCliente/" + cliente + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + fechaninio + "/" + fechafin);
        }
        if (sucursal !== "null" && idempleado !== "" && typeof cliente !== "undefined") {
            return window.open("/reporteCreditoVentaAtrasadasConEmpleadoConSucursalConCliente/" + idempleado + "/" + cliente + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + fechaninio + "/" + fechafin);
        }
        if (sucursal !== "null" && idempleado !== "" && typeof cliente == "undefined") {
            return window.open("/reporteCreditoVentaAtrasadasConEmpleadoConSucursalSinCliente/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + fechaninio + "/" + fechafin);
        }
        if (sucursal !== "null" && idempleado === "" && typeof cliente === "undefined") {
            return window.open("/reporteVentaCreditoAtrasadasSinEmpleadoConSucursalSinCliente/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + fechaninio + "/" + fechafin);
        }
        if (sucursal === "null" && idempleado === "" && typeof cliente !== "undefined") {
            return window.open("/reporteVentaCreditoAtrasadasSinEmpleadoSinSucursalConCliente/" + cliente + "/" + usuariologueado + "/" + extencion + "/" + fechaninio + "/" + fechafin);
        }
    } else {
        if (sucursal === "null" && idempleado === "" && typeof cliente === "undefined") {
            usuariologueado = $('#iddelempleado').val();
            return window.open("/reporteVentaCreditoSinEmpleadoSinSucursalSinClienteConsolidado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
        }
        if (sucursal !== "null" && idempleado === "" && typeof cliente === "undefined") {
            return window.open("/reporteVentaCreditoSinEmpleadoConSucursalSinClienteConsolidado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
        }
        if (sucursal !== "null" && idempleado !== "" && typeof cliente === "undefined") {
            return window.open("/reporteVentaCreditoConEmpleadoConSucursalSinClienteConsolidado/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
        }
        if (sucursal !== "null" && idempleado !== "" && typeof cliente !== "undefined") {
            var idempleado = $('#empleado').val();
            return window.open("/reporteVentaCreditoConEmpleadoConSucursalConClienteConsolidado/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + cliente);
        }
        if (sucursal === "null" && idempleado === "" && typeof cliente !== "undefined") {
            return window.open("/reporteVentaCreditoSinEmpleadoSinSucursalConClienteConsolidado/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + cliente);
        }
    }
});



$("#detalleventasBelleMarie").click(function () {
    debugger;
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
    var fechafin = $('#Fechafins').val();
    var fechaninio = $('#fechainicios').val();
    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#empleado').val();
    var sucursal = $("#almacen").val();
    var almacen = $('#almacenV').val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    }
    if (xlsx == true) {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechaninio || !fechaninio.trim().length) {
        swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }

    if (sucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!
        usuariologueado = $('#iddelempleado').val();
//  
        window.open("/ventaDetalleVentaSinSucursaSinAlmacenSinEmpleadoBelleMarie/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
        return;
    }

    if (sucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
        window.open("/ventaDetalleVentaConSucursaSinAlmacenSinEmpleadoBelleMarie/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
//ok 
    }

    if (sucursal !== "null" && idempleado !== "" && almacen === "null") {
        //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
        //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
//ok 
        window.open("/ventaDetalleVentaConSucursaSinAlmacenConEmpleadoBelleMarie/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
    }


    if (sucursal !== "null" && idempleado !== "" && almacen !== "null") {
        //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen
//ok   
        var idempleado = $('#empleado').val();
        window.open("/ventaDetalleVentaConSucursaConAlmacenConEmpleadoBelleMarie/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + almacen);
    }
});

$("#reporteflujoingresosegresos").click(function () {
    var pdf = document.getElementById('test1').checked;
    var fechainicio = $('#fechainicio').val();
    var fechafin = $('#Fechafin').val();
    var usuariologueado = $('#iddelempleado').val();
    var sucursal = $("#almacen").val();
    var extencion;
    if (pdf == true) {
        extencion = 0;
    } else {
        extencion = 1;
    }
    if (!fechafin || !fechafin.trim().length && !fechainicio || !fechainicio.trim().length) {
        return swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            showConfirmButton: false,
            timer: 2000
        });
    }
    var validacion = fechaCorrecta(fechainicio, fechafin);
    if (validacion == false) {
        return swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            showConfirmButton: false,
            timer: 2000});
    }
    if (sucursal == "null") {
        return window.open("reporteFlujoIngresosEgresos/" + fechainicio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
    } else {
        return window.open("reporteFlujoIngresosEgresosConSucursal/" + fechainicio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
    }
});

function cargartipoegreso() {
    $('#egresos')
            .find('option')
            .remove()
            .end()
            .append('<option value="">Selecione un Tipo de Egreso</option>')
            .val('null');
    $('#egresos').material_select();
    var route = "/listaregresotipo";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#egresos').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#egresos').material_select();
        });
    });
}
