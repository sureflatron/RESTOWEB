$(document).ready(function () {
    Cargar();
});

function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarCuentaBancaria/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            tabladatos.append("<tr>" +
                    "<td>" + value.banco + "</td>" +
                    "<td>" + value.nroCuenta + "</td>" +
                    "<td>" + value.razonSocial + "</td>" +
                    "<td>" + value.tipoCuenta + "</td>" +
                    "<td>" + value.moneda + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td>" +
                    "<td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

function Mostrar(btn) {
    debugger;
    var route = "/CuentaBancaria/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#idactualizar").val(res[0].id);
        $("#banco1").val(res[0].banco);
        $("#cuenta1").val(res[0].nroCuenta);
        $("#tipocuenta1").val(res[0].tipoCuenta);
        $("#moneda1").val(res[0].moneda);
        $("#razonSocials").val(res[0].razonSocial);
    });
}

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/CuentaBancaria/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR ESTA CUENTA?",
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

$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#idactualizar").val();
        var banco = $("#banco1").val();
        var cuenta = $("#cuenta1").val();
        var tcuenta = $("#tipocuenta1").val();
        var moneda = $("#moneda1").val();
        var razonSocial = $("#razonSocials").val();
        if (!cuenta || !cuenta.trim().length) {
            return Materialize.toast('CUENTA VACIA', 2000, 'rounded');
        }
        if (!tcuenta || !tcuenta.trim().length) {
            return Materialize.toast('TIPO DE CUENTA VACIO', 2000, 'rounded');
        }
        if (!razonSocial || !razonSocial.trim().length) {
            return Materialize.toast('RAZON SOCIAL VACIA', 2000, 'rounded');
        }
        var route = "/CuentaBancaria/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                banco: banco,
                cuenta: cuenta,
                tipocuenta: tcuenta,
                moneda: moneda,
                razonSocial: razonSocial
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal2").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ACTUALIZACION COMPLETA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                $("#modal2").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ERROR AL ACTUALIZAR",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA ACTUALIZAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});

$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var banco = $("#banco").val();
        var cuenta = $("#cuenta").val();
        var tcuenta = $("#tipocuenta").val();
        var moneda = $("#moneda").val();
        var razonSocial = $("#razonSocial").val();
        if (!cuenta || !cuenta.trim().length) {
            return Materialize.toast('CUENTA VACIA', 1000, 'rounded');
        }
        if (!tcuenta || !tcuenta.trim().length) {
            return Materialize.toast('TIPO DE CUENTA VACIO', 1000, 'rounded');
        }
        if (!razonSocial || !razonSocial.trim().length) {
            return Materialize.toast('RAZON SOCIAL VACIA', 2000, 'rounded');
        }
        var route = "/CuentaBancaria";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                banco: banco,
                cuenta: cuenta,
                tipocuenta: tcuenta,
                moneda: moneda,
                razonSocial: razonSocial
            },
            success: function (route) {
                $(route).each(function (key, value) {
                    if (value.mensaje == "Guardado exitoso") {
                        $('#tablacategoria').DataTable().destroy();
                        Cargar();
                        $("#modal1").closeModal();
                        $(".lean-overlay").remove();
                        swal({title: "CUENTA GUARDADA EXITOSAMENTE",
                            type: "success",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                        $("#nombre").val("");
                        $("#cuenta").val("");
                        $("#tipocuenta").val("");
                        $("#razonSocial").val("");
                    } else {
                        swal({title: "Advertencia!",
                            text: "El numero de cuenta ya existe",
                            type: "warning",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 2000});
                    }
                });
            },
            error: function () {
                $('.lean-overlay').remove();
                swal({title: "ERROR AL GUARDAR",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
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