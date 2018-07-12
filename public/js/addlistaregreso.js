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
 * Metodo para listar todos los egresos en la tabla
 */
function Cargar() {
    $('#datos').empty();
    var route = "/listarEgreso";
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
                        "<td>" + value.nombre + "</td>" +
                        "<td>Pagado</td>" +
                        "<td>" + value.provedor + "</td>" +
                        "<td>" + value.pagadoA + "</td>" +
                        "<td>" + value.importe + "</td>" +
                        "<td>" + value.formapago + "</td>" +
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
            swal({title: "ERROR AL LISTAR EGRESOS",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}
/*
 * Metodo para abrir la interfaz y crear un nuevo egreso
 */
$("#nuevo").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        window.location.href = "/addegreso/";
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
/*
 * Mostrar los datos de un egreso en el modal para ser editados
 */
function Mostrar(btn) {
    var route = "/Egreso/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#idactualizar").val(res.id);
        $("#pagadoA").val(res.pagadoA);
        $("#glosa").val(res.glosa);
        $("#importe").val(res.importe);
        $("#idTipoEgreso").val(res.idTipoEgreso);
        $('#idTipoEgreso option:selected').val(res.idTipoEgreso);
        $('#idTipoEgreso').material_select();
        $("#idProveedor").val(res.idProveedor);
        $('#idProveedor option:selected').val(res.idProveedor);
        $('#idProveedor').material_select();
        $("#fecha").val(res.fecha);
    });
}
/*
 * Metodo para actualizar un egreso
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
        var fecha = $("#fecha").val();
        var pagadoA = $("#pagadoA").val( );
        var value = $("#idactualizar").val();
        var glosa = $("#glosa").val();
        var importe = $("#importe").val();
        var idTipoEgreso = $("#idTipoEgreso").val( );
        var idProveedor = $("#idProveedor").val( );
        if (!fecha || !fecha.trim().length) {
            Materialize.toast('FECHA VACIA', 2500, 'rounded');
            return;
        }
        if (!idTipoEgreso || !idTipoEgreso.trim().length) {
            Materialize.toast('TIPO DE EGRESO VACIO', 2500, 'rounded');
            return;
        }
        if (idTipoEgreso == 0) {
            Materialize.toast('TIPO DE EGRESO VACIO', 2500, 'rounded');
            return;
        }
        if (!idProveedor || !idProveedor.trim().length) {
            Materialize.toast('PROVEEDOR VACIO', 2500, 'rounded');
            return;
        }
        if (idProveedor == 0) {
            Materialize.toast('PROVEEDOR VACIO', 2500, 'rounded');
            return;
        }
        if (!pagadoA || !pagadoA.trim().length) {
            Materialize.toast('DEBE INGRESAR EL CAMPO -> PAGADO A', 2500, 'rounded');
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
        var route = "/Egreso/" + value + "";
        var token = $("#token").val();
        var puntoventa = iddelpuntoventa;
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                pagadoA: pagadoA,
                fecha: fecha,
                glosa: glosa,
                idTipoEgreso: idTipoEgreso,
                idProveedor: idProveedor,
                puntoventa: puntoventa,
                importe: importe,
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
                        window.open("/imprimiregreso/" + val.id);
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
 * Metodo para eliminar un egreso
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Egreso/" + btn.value + "";
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
    var pagadoA = $("#pagadoAs").val();
    var fecha = $("#fecha").val();
    var importe = $("#importes").val();
    var glosa = $("#glosas").val();
    var idTipoEgreso = $("#idTipoEgresos").val();
    var idProveedor = $("#idProveedors").val();
    var txnOrigen = $("#txnOrigens").val();
    var puntoventa = iddelpuntoventa;
    if (!fecha || !fecha.trim().length) {
        Materialize.toast('FECHA VACIA', 2500, 'rounded');
        return;
    }
    if (!idTipoEgreso || !idTipoEgreso.trim().length) {
        Materialize.toast('TIPO DE EGRESO VACIO', 2500, 'rounded');
        return;
    }
    if (idTipoEgreso == 0) {
        Materialize.toast('TIPO DE EGRESO VACIO', 2500, 'rounded');
        return;
    }
    if (!idProveedor || !idProveedor.trim().length) {
        Materialize.toast('PROVEEDOR VACIO', 2500, 'rounded');
        return;
    }
    if (idProveedor == 0) {
        Materialize.toast('PROVEEDOR VACIO', 2500, 'rounded');
        return;
    }
    if (!pagadoA || !pagadoA.trim().length) {
        Materialize.toast('DEBE INGRESAR EL CAMPO -> PAGADO A', 2500, 'rounded');
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
    var route = "/Egreso";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            pagadoA: pagadoA,
            fecha: fecha,
            glosa: glosa,
            idTipoEgreso: idTipoEgreso,
            idProveedor: idProveedor,
            txnOrigen: txnOrigen,
            puntoventa: puntoventa,
            importe: importe,
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
                    $("#pagadoAs").val("");
                    $("#glosas").val("");
                    $("#glosas").val("");
                    $("#txnOrigens").val("");
                    $("#importes").val("");
                    $("#glosas").val();
                    swal({title: "EGRESO GUARDADO EXITOSAMENTE",
                        type: "success",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                    window.open("/imprimiregreso/" + val.id);
                    window.location.href = "/GestionarEgreso";
                });
            });
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
 * Imprimir un comprobante de compra
 */
function imprimir(btn) {
    var usuariologueado = $('#iddelempleado').val();
    if ($("#perfilpuedeImprimir").val() == 1) {
        window.open("/imprimiregreso/" + btn.value);
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
//
//  "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn modal-trigger'  href='#modal1' title='Editar'>" +
//                        "<i class='material-icons'>mode_edit</i>" +
//                        "</button>" +