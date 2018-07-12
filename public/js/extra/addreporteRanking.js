
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
    $("#fechainicioegreso").val(hoy);
    $("#Fechafinegreso").val(hoy);
    
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 10 // Creates a dropdown of 15 years to control year
    });
    cargarSucursalVerdadero();
    cargaralmacen();
    
    cargarempleado();

});

function cargarSucursalVerdadero() {


    $("select#empleado").change(function () {
        debugger;
        var idempleado = $(this).val();
        if (idempleado === "") {
//            listar todas las sucursales

            var route = "/listarsucrusal/";
            $('#almacen')
                    .find('option')
                    .remove()
                    .end()
                    .append('<option value="null">Selecione un Sucursal</option>')
                    .val('null');
            $('#almacen').material_select();
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    $('#almacen').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
                    $('#almacen').material_select();
                });
            });
        }


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
                    debugger;
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
                            debugger;
                            $('#almacenV').append('<option value=' + s.id + ' >' + s.nombre + '</option>');
                            $('#almacenV').material_select();
                        });
                    });


                });
            });



        }

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

$("#raking").click(function () {
//reportusuarioactual
    debugger;
    var fechafin = $('#Fechafinegreso').val();
    var fechaninio = $('#fechainicioegreso').val();
    var pdf = document.getElementById('test1').checked;
    var xlsx = document.getElementById('test2').checked;
//    var idempleado = $('#iddelempleado').val();
    var ordenar = $('#ordenar').val();
    var extencion;


    var usuariologueado = $('#iddelempleado').val();
    var idempleado = $('#empleado').val();
    var sucursal = $("#almacen").val();
    var almacen = $('#almacenV').val();

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
    if (ordenar == null) {
//        window.open("/Reportkingproducto/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + extencion);

        /////////////////////////////////////////////////////////////////////////////////
        if (sucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!

            window.open("/reporteRankingSinOrdenarSinSucursalSinEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
            return;
        }

        if (sucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
            window.open("/reporteRankingSinOrdenarConSucursalSinEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);

        }

        if (sucursal !== "null" && idempleado !== "" && almacen === "null") {
            //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
            //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
            window.open("/reporteRankingSinOrdenarConSucursalConEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
        }


        if (sucursal !== "null" && idempleado !== "" && almacen !== "null") {
            //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

            var idempleado = $('#empleado').val();
            window.open("/reporteRankingSinOrdenarConSucursalConEmpleadoConAlmacen/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + almacen);
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////







    } else {
        if (ordenar == 1) {
//            window.open("/Reportkingproductoimporte/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + extencion);
            /////////////////////////////////////////////////////////////////////////////////
            if (sucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!

                window.open("/reporteRankingOrdenarImporteSinSucursalSinEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
                return;
            }

            if (sucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
                window.open("/reporteRankingOrdenarImporteConSucursalSinEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);

            }

            if (sucursal !== "null" && idempleado !== "" && almacen === "null") {
                //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
                //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
                window.open("/reporteRankingOrdenarImporteConSucursalConEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
            }


            if (sucursal !== "null" && idempleado !== "" && almacen !== "null") {
                //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

                window.open("/reporteRankingOrdenarImporteConSucursalConEmpleadoConAlmacen/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + almacen);
            }

        }




        if (ordenar == 2) {
//            window.open("/Reportkingproductocantidad/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + extencion);
            /////////////////////////////////////////////////////////////////////////////////
            if (sucursal === "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO NADA!!
//        reporte de todos los usuarios de todas las sucursales y de todos los almacenes!!

                window.open("/reporteRankingOrdenarCantidadSinSucursalSinEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion);
                return;
            }

            if (sucursal !== "null" && idempleado === "" && almacen === "null") {
//        NO SELECCIONO  EMPLEADO, NO SELECCIONO ALMACEN PERO SI UNA SUCURSAL!!!
//        reporte de la sucursal que selecciono pero de todos lo empleados y de almacenes
                window.open("/reporteRankingOrdenarCantidadConSucursalSinEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + usuariologueado + "/" + extencion + "/" + sucursal);

            }

            if (sucursal !== "null" && idempleado !== "" && almacen === "null") {
                //SELECCIONO A LOS 2 EMPLEADO Y SUCURSAL
                //rpeorte de 1 empleado y de una sucursal
//        var idempleado = $('#empleado').val();
                window.open("/reporteRankingOrdenarCantidadConSucursalConEmpleadoSinAlmacen/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal);
            }


            if (sucursal !== "null" && idempleado !== "" && almacen !== "null") {
                //SELECCIONO A LOS 1 EMPLEADO, 1 SUCURSAL, 1 almacen

                window.open("/reporteRankingOrdenarCantidadConSucursalConEmpleadoConAlmacen/" + fechaninio + "/" + fechafin + "/" + idempleado + "/" + usuariologueado + "/" + extencion + "/" + sucursal + "/" + almacen);
            }


        }
    }
    //
});

function fechaCorrecta(fechaInicio, fechaFin) {
    //Split de las fechas recibidas para separarlas
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