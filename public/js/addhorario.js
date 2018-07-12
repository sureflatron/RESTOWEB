$(document).ready(function () {
    Cargar();
});

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var id = $("#idturno").val();
    var route = "/listadehorario/" + id;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.dia + "</td>" +
                    "<td>" + value.horaEntrada + "</td>" +
                    "<td>" + value.horaSalida + "</td>" +
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

function Mostrar(btn) {
    var route = "/Horario/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#achoraentrada").val(res.horaEntrada);
        $("#achorasalida").val(res.horaSalida);
        $('#diasactua').val(res.dia);
        $("#diasactua").material_select();
        $("#id").val(res.id);
    });
}

$("#actualizar").click(function () {
//    if ($("#perfilpuedeModificar").val() == 1) {
    var value = $("#id").val();
    var achoraentrada = $("#achoraentrada").val() + ":00";
    if (!$("#achoraentrada").val() || !$("#achoraentrada").val().trim().length) {
        Materialize.toast('SELECCIONE LA HORA DE ENTRADA', 1000, 'rounded');
        return;
    }
    var achorasalida = $("#achorasalida").val() + ":00";
    if (!$("#achorasalida").val() || !$("#achorasalida").val().trim().length) {
        Materialize.toast('SELECCIONE LA HORA DE SALIDA', 1000, 'rounded');
        return;
    }
    var dia = $("#diasactua").val();
    if (!dia || !dia.trim().length) {
        Materialize.toast('SELECCIONE UN DIA', 1000, 'rounded');
        return;
    }
    var route = "/Horario/" + value + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            achoraentrada: achoraentrada,
            achorasalida: achorasalida,
            dia: dia
        },
        success: function () {
            Cargar();
            $("#modal1").closeModal();
            $('.lean-overlay').remove();
            swal({title: "ACTUALIZACION COMPLETA",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }, error: function () {
            swal({title: "ERROR AL ACTUALIZAR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
//    } else {
//        swal({title: "NO TIENE PERMISO PARA ACTUALIZAR",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
});

$("#guardar").click(function () {
//    if ($("#perfilpuedeGuardar").val() == 1) {
    var dia = $("#dias").val();
    if (!dia || !dia.trim().length) {
        Materialize.toast('SELECCIONE UN DIA', 1000, 'rounded');
        return;
    }
    var horaEntrada = $("#horaentrada").val() + ":00";
    if (!$("#horaentrada").val() || !$("#horaentrada").val().trim().length) {
        Materialize.toast('SELECCIONE LA HORA DE ENTRADA', 1000, 'rounded');
        return;
    }
    var horaSalida = $("#horasalida").val() + ":00";
    if (!$("#horasalida").val() || !$("#horasalida").val().trim().length) {
        Materialize.toast('SELECCIONE LA HORA DE SALIDA', 1000, 'rounded');
        return;
    }
    var id = $("#idturno").val();
    var route = "/Horario";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            horaEntrada: horaEntrada,
            horaSalida: horaSalida,
            id: id,
            dia: dia
        },
        success: function () {
            Cargar();
            $("#horaentrada").val("");
            $("#horasalida").val("");
            swal({title: "HORARIO GUARDADO EXITOSAMENTE",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }, error: function () {
            swal({title: "ERROR AL GUARDAR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
//    } else {
//        swal({title: "NO TIENE PERMISO PARA CREAR HORARIO",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
});

function Eliminar(btn) {
//    if ($("#perfilpuedeEliminar").val() == 1) {
    var route = "/Horario/" + btn.value + "";
    var token = $("#token").val();
    swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL HORARIO?",
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
//    } else {
//        swal({title: "NO TIENE PERMISO PARA ELIMINAR",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
}

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