var iddelpuntoventa;
var idempleado;
var nroCuenta;
var idselecionado;
$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    Cargar();
    cargarCuenta();
    $("select#concepto").change(function () {
        debugger;
        idselecionado = $(this).val();
        var route = "/listarCuenta/" + idselecionado + "";
        nroCuenta = 0;
        if (idselecionado === "1") {  //efectivo
            $("#cuentabanco").attr("style", "display :none;");
            $("#cuentacheque").attr("style", "display :none;");
        }

        if (idselecionado === "2") {  //Banco
            $("#cuentabanco").attr("style", "display :block;");
            $("#cuentacheque").attr("style", "display :none;");
            $.get(route, function (res) {
                debugger;
                nroCuenta = res[0].nroCuenta;
            });
        }

        if (idselecionado === "3") {  //Cheque
            $("#cuentabanco").attr("style", "display :none;");
            $("#cuentacheque").attr("style", "display :block;");
        }

    });

    $("select#concepto1").change(function () {
        debugger;
        idselecionado = $(this).val();
        var route = "/listarCuenta/" + idselecionado + "";
        nroCuenta = 0;
        if (idselecionado === "1") {  //efectivo
            $("#cuentabanco").attr("style", "display :none;");
            $("#cuentacheque").attr("style", "display :none;");
        }

        if (idselecionado === "2") {  //Banco
            $("#cuentabanco").attr("style", "display :block;");
            $("#cuentacheque").attr("style", "display :none;");
            $.get(route, function (res) {
                debugger;
                nroCuenta = res[0].nroCuenta;
            });
        }

        if (idselecionado === "3") {  //Cheque
            $("#cuentabanco").attr("style", "display :none;");
            $("#cuentacheque").attr("style", "display :block;");
        }

    });
});

function cargarCuenta() {
    debugger;
    $('#cuenta')
            .find('option')
            .remove()
            .end();
    $('#cuenta').material_select();
    var route = "/listarCuentaBancaria/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#cuenta').append('<option  value=' + value.nroCuenta + ' >' + value.banco + "  " + value.nroCuenta + '</option>');
            $('#cuenta').material_select();
        });
    });
}

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

/*
 * Cargar los Ingresos Registrados en la DB
 */
function Cargar() {
    $('#datos').empty();
    var route = "/listarIngreso";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            iddelpuntoventa: iddelpuntoventa
        },
        success: function ($route) {
            var tabladatos = $('#datos');
            $($route).each(function (key, value) {
                tabladatos.append("<tr>" +
                        "<td>" + value.id + "</td>" +
                        "<td>" + value.fecha + "</td>" +
                        "<td>" + value.hora + "</td>" +
                        "<td>Cobrado</td>" +
                        "<td>" + value.recibido + "</td>" +
                        "<td>" + value.importe + "</td>" +
                        "<td>" + value.formapago + "</td>" +
                        "<td>" + value.restgistrado + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" +
                        "</td><td>" +
                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar' title='Eliminar'>" +
                        "<i class='material-icons'>delete</i>" +
                        "</button>" +
                        "</td><td>" +
                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='imprimir(this);' title='imprimir'>" +
                        "<i class='material-icons'>print</i>" +
                        "</button>" +
                        "</td>" +
                        "</tr>");
            });
            paginador();
        }, error: function () {
            swal({title: "ERROR AL LISTAR INGRESOS",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}

$("#nuevo").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        window.location.href = "/addingreso/";
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
/*
 * Mostrar los datos de un ingreso en el modal para editarse
 */
function Mostrar(btn) {
    var route = "/Ingreso/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#idactualizar").val(res.id);
        $("#recibidoDe").val(res.recibidoDe);
        $("#glosa").val(res.glosa);
        $("#importe").val(res.importe);
        $("#idTipoIngreso").val(res.idTipoIngreso);
        $('#idTipoIngreso option:selected').val(res.idTipoIngreso);
        $('#idTipoIngreso').material_select();
        $("#fecha").val(res.fecha);
    });
}
/*
 * Actualizar Ingreso
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var formapago;
        if (idselecionado == "3") {
            nroCuenta = $("#cheque1").val();
        }

        if (idselecionado == "2") {
            nroCuenta = $("#cuenta").val();
        }
        formapago = $('#concepto1').val();
        var value = $("#idactualizar").val();
        var fecha = $("#fecha").val();
        var importe = $("#importe").val();
        var recibidoDe = $("#recibidoDe").val();
        var glosa = $("#glosa").val();
        var idTipoIngreso = $("#idTipoIngreso").val();
        if (!fecha || !fecha.trim().length) {
            Materialize.toast('FECHA VACIA', 2500, 'rounded');
            return;
        }
        if (!idTipoIngreso || !idTipoIngreso.trim().length) {
            Materialize.toast('TIPO DE INGRESO VACIO', 2500, 'rounded');
            return;
        }
        if (!recibidoDe || !recibidoDe.trim().length) {
            Materialize.toast('DEBE INGRESAR EL CAMPO -> RECIBIDO DE', 2500, 'rounded');
            return;
        }
        if (!importe || !importe.trim().length) {
            Materialize.toast('IMPORTE VACIO', 2500, 'rounded');
            return;
        }
        if (parseFloat(importe) <= 0) {
            Materialize.toast('EL IMPORTE DEBE SER MAYOR A 0', 2500, 'rounded');
            return;
        }
        var route = "/Ingreso/" + value + "";
        var token = $("#token").val();
        var puntoventa = iddelpuntoventa;
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                importe: importe,
                fecha: fecha,
                recibidoDe: recibidoDe,
                glosa: glosa,
                idTipoIngreso: idTipoIngreso,
                puntoventa: puntoventa,
                nrocuenta: nroCuenta,
                formapago: formapago
            },
            success: function ($route) {
                $($route).each(function (key, value) {
                    $(value).each(function (key, val) {
                        var usuariologueado = $('#iddelempleado').val();
                        $('#tablacategoria').DataTable().destroy();
                        Cargar();
                        $("#modal1").closeModal();
                        $('.lean-overlay').remove();
                        swal({title: "ACTUALIZACION COMPLETA",
                            type: "success",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                        window.open("/imprimiringreso/" + val.id);
                    });
                });
            }, error: function () {
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
 * Eliminar Ingreso
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Ingreso/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL EGRESO?",
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

$("#guardar").click(function () {
    var formapago;
    if (idselecionado == "3") {
        nroCuenta = $("#cheque").val();
    }
    if (idselecionado == "2") {
        nroCuenta = $("#cuenta").val();
    }
    formapago = $('#concepto').val();
    var importe = $("#importes").val();
    var fecha = $("#fecha").val();
    var recibidoDe = $("#recibidoDes").val();
    var glosa = $("#glosas").val();
    var idTipoIngreso = $("#idTipoIngresos").val();
    var txnOrigen = $("#txnOrigens").val();
    if (!fecha || !fecha.trim().length) {
        Materialize.toast('FECHA VACIA', 2500, 'rounded');
        return;
    }
    if (!idTipoIngreso || !idTipoIngreso.trim().length) {
        Materialize.toast('TIPO DE INGRESO VACIO', 2500, 'rounded');
        return;
    }
    if (!recibidoDe || !recibidoDe.trim().length) {
        Materialize.toast('DEBE INGRESAR EL CAMPO -> RECIBIDO DE', 2500, 'rounded');
        return;
    }
    if (!importe || !importe.trim().length) {
        Materialize.toast('IMPORTE VACIO', 2500, 'rounded');
        return;
    }
    if (parseFloat(importe) <= 0) {
        Materialize.toast('EL IMPORTE DEBE SER MAYOR A 0', 2500, 'rounded');
        return;
    }
    var puntoventa = iddelpuntoventa;
    var route = "/Ingreso";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            importe: importe,
            fecha: fecha,
            recibidoDe: recibidoDe,
            glosa: glosa,
            idTipoIngreso: idTipoIngreso,
            txnOrigen: txnOrigen,
            puntoventa: puntoventa,
            nrocuenta: nroCuenta,
            formapago: formapago
        },
        success: function ($route) {
            $($route).each(function (key, value) {
                $(value).each(function (key, val) {
                    var usuariologueado = $('#iddelempleado').val();
                    $('#tablacategoria').DataTable().destroy();
                    Cargar();
                    $("#modal1").closeModal();
                    $("#importes").val("");
                    $("#recibidoDes").val("");
                    $("#txnOrigens").val("");
                    $("#glosas").val("");
                    swal({title: "GUARDADO EXITOSO",
                        type: "success",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                    window.open("/imprimiringreso/" + val.id);
                    window.location.href = "/GestionarIngreso";
                });
            });
        }, error: function () {
            swal({title: "ERROR AL CREAR INGRESO",
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
        order: [0, 'desc'],
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
 * Imprimir comprobante de ingreso
 */
function imprimir(btn) {
    var usuariologueado = $('#iddelempleado').val();
    if ($("#perfilpuedeImprimir").val() == 1) {
        window.open("/imprimiringreso/" + btn.value);
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

//"<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn modal-trigger'  href='#modal1' title='Editar'>" +
//                        "<i class='material-icons'>mode_edit</i>" +
//                        "</button>" +