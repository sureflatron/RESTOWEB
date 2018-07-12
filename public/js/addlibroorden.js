$(document).ready(function () {
    Cargar();
});

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

/*
 * Metodo para cargar la tabla con los datos del libro de orden
 */
function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarlibroorden/";
    $('#datos').empty();
    var estados = "";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.estado == 0) {
                estados = "Activo";
            }
            if (value.estado == 1) {
                estados = "Inactivo";
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.NIT + "</td>" +
                    "<td>" + value.nroAutorizacion + "</td>" +
                    "<td>" + value.nroInicio + "</td>" +
                    "<td>" + value.nroFin + "</td>" +
                    "<td>" + estados + "</td>" +
                    "<td>" + value.tipo + "</td>" +
                    "<td>" + value.fechaInicio + "</td>" +
                    "<td>" + value.fechaFin + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}
/*
 * Metodo para mostrar en un modal los datos del libro de orden
 */
function Mostrar(btn) {
    var route = "/LibroOrden/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#idactualizar").val(res.id);
        $("#idSucursal").val(res.idSucursal);
        $("#idSucursal").val(res.idSucursal);
        $('#idSucursal option:selected').val(res.idSucursal);
        $('#idSucursal').material_select();
        $("#NIT").val(res.NIT);
        $("#nroAutorizacion").val(res.nroAutorizacion);
        $("#nroInicio").val(res.nroInicio);
        $("#nroFin").val(res.nroFin);
        $("#tipo").val(res.tipo);
        $("#fechaInicio").val(res.fechaInicio);
        $("#fechaFin").val(res.fechaFin);
        $("#llave").val(res.llave);
        var habilitado = res.estado;
        if (habilitado == 0) {
            document.getElementById('test1').checked = 1;
        }
        if (habilitado == 1) {
            document.getElementById('test2').checked = 1;
        }
    });
}
/*
 * Metodo para elimianr un libro de orden
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/LibroOrden/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL LIBRO DE ORDEN?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminarlo!",
            cancelButtonText: "No, cancelar!",
            closeOnConfirm: false,
            closeOnCancel: false},
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: route,
                            headers: {'X-CSRF-TOKEN': token},
                            type: 'DELETE',
                            dataType: 'json',
                            success: function () {
                                $('#tablacategoria').DataTable().destroy();
                                Cargar();
                                swal({title: "ELIMINACION COMPLETA",
                                    type: "success",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
                            }
                        });
                    } else {
                        swal({title: "CANCELADO",
                            type: "error",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                    }
                });
    } else {
        swal({title: "NO TIENE PERMISO PARA ELIMINAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Metodo para actualizar un libro de orden
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#idactualizar").val();
        var idSucursal = $("#idSucursal").val();
        var NIT = parseInt($("#NIT").val()) + "";
        var nroAutorizacion = parseInt($("#nroAutorizacion").val()) + "";
        var nroInicio = parseInt($("#nroInicio").val()) + "";
        var nroFin = parseInt($("#nroFin").val()) + "";
        var tipo = $("#tipo").val();
        var fechaInicio = $("#fechaInicio").val();
        var fechaFin = $("#fechaFin").val();
        var si = document.getElementById('test1').checked;
        var no = document.getElementById('test2').checked;
        var estado;
        var llave = $("#llave").val();
        if (si == true) {
            estado = 0;
        }
        if (no == true) {
            estado = 1;
        }
        if (!idSucursal || !idSucursal.trim().length || idSucursal == 0) {
            Materialize.toast('SUCURSAL VACIA', 2000, 'rounded');
            return;
        }
        if (!NIT || !NIT.trim().length) {
            Materialize.toast('NIT VACIO', 2000, 'rounded');
            return;
        }
        if (!nroInicio || !nroInicio.trim().length) {
            Materialize.toast('NRO DE INICIO VACIO', 2000, 'rounded');
            return;
        }
        if (!nroFin || !nroFin.trim().length) {
            Materialize.toast('NRO DE FIN VACIO', 2000, 'rounded');
            return;
        }
        if (!fechaInicio || !fechaInicio.trim().length) {
            Materialize.toast('FECHA DE INICIO VACIA', 2000, 'rounded');
            return;
        }
        if (!fechaFin || !fechaFin.trim().length) {
            Materialize.toast('FECHA DE FIN VACIA', 2000, 'rounded');
            return;
        }
        if (!llave || !llave.trim().length) {
            Materialize.toast('LLAVE VACIA', 2000, 'rounded');
            return;
        }
        var validacion = fechaCorrecta(fechaInicio, fechaFin);
        if (validacion == false) {
            Materialize.toast('INSERTE LAS FECHAS CORRECTAMENTE', 2000, 'rounded');
            return;
        }
        var route = "/LibroOrden/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                idSucursal: idSucursal,
                NIT: NIT,
                nroAutorizacion: nroAutorizacion,
                nroInicio: nroInicio,
                nroFin: nroFin,
                tipo: tipo,
                fechaInicio: fechaInicio,
                fechaFin: fechaFin,
                estado: estado,
                llave: llave
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal1").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ACTUALIZACION EXITOSA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                $("#modal1").closeModal();
                swal({title: "ERROR AL ACTUALIZAR",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA MODIFICAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
/*
 * Metodo para abrir la ventana y crear un nuevo libro de orden
 */
$("#nuevolibroorden").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var route = "/Nuevolibroorden/";
        window.location.href = route;
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
/*
 * Metodo para guardar un nuevo libro de orden
 */
$("#guardar").click(function () {
    var idSucursal = $("#idSucursal").val();
    var NIT = parseInt($("#NIT").val()) + "";
    var nroAutorizacion = parseInt($("#nroAutorizacion").val()) + "";
    var nroInicio = parseInt($("#nroInicio").val()) + "";
    var nroFin = parseInt($("#nroFin").val()) + "";
    var tipo = $("#tipo").val();
    var fechaInicio = $("#fechaInicio").val();
    var fechaFin = $("#fechaFin").val();
    var llave = $("#llave").val();
    var si = document.getElementById('test1').checked;
    var no = document.getElementById('test2').checked;
    var estado;
    if (si == true) {
        estado = 0;
    }
    if (no == true) {
        estado = 1;
    }
    if (!idSucursal || !idSucursal.trim().length || idSucursal == 0) {
        Materialize.toast('SUCURSAL VACIA', 2000, 'rounded');
        return;
    }
    if (!NIT || !NIT.trim().length) {
        Materialize.toast('NIT VACIO', 2000, 'rounded');
        return;
    }
    if (!nroInicio || !nroInicio.trim().length) {
        Materialize.toast('NRO DE INICIO VACIO', 2000, 'rounded');
        return;
    }
    if (!nroFin || !nroFin.trim().length) {
        Materialize.toast('NRO DE FIN VACIO', 2000, 'rounded');
        return;
    }
    if (!fechaInicio || !fechaInicio.trim().length) {
        Materialize.toast('FECHA DE INICIO VACIO', 2000, 'rounded');
        return;
    }
    if (!fechaFin || !fechaFin.trim().length) {
        Materialize.toast('FECHA DE FIN VACIO', 2000, 'rounded');
        return;
    }
    if (!llave || !llave.trim().length) {
        Materialize.toast('LLAVE VACIA', 2000, 'rounded');
        return;
    }
    var validacion = fechaCorrecta(fechaInicio, fechaFin);
    if (validacion == false) {
        Materialize.toast('INSERTE LAS FECHAS CORRECTAMENTE', 2000, 'rounded');
        return;
    }
    var route = "/LibroOrden";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            idSucursal: idSucursal,
            NIT: NIT,
            nroAutorizacion: nroAutorizacion,
            nroInicio: nroInicio,
            nroFin: nroFin,
            tipo: tipo,
            fechaInicio: fechaInicio,
            fechaFin: fechaFin,
            estado: estado,
            llave: llave
        },
        success: function () {
            swal({title: "GUARDADO EXITOSO",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.href = "/Gestionarlibroorden";
        }, error: function () {
            swal({title: "ERROR AL GUARDAR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
/*
 * Paginador de la tabla
 */
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
/*
 * Metodo para comparar las fechas y saber cual es mayor
 */
function fechaCorrecta(fechaInicio, fechaFin) {
    //Split de las fechas recibidas para separarlas
    var x = fechaInicio.split("-");
    var z = fechaFin.split("-");
    //Cambiamos el orden al formato americano, de esto dd/mm/yyyy a esto mm/dd/yyyy
    fechaInicio = x[1] + "-" + x[2] + "-" + x[0];
    fechaFin = z[1] + "-" + z[2] + "-" + z[0];
    //Comparamos las fechas
    if (Date.parse(fechaInicio) > Date.parse(fechaFin)) {
        return false;
    } else {
        return true;
    }
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}