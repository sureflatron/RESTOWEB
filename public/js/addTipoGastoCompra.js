$(document).ready(function () {
    Cargar();
});


function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarGastoCompra/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" +
                    "<button value=" + value.idTipoGasto + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.idTipoGasto + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

function Mostrar(btn) {
    var route = "/TipoGastoCompra/" + btn.value + "/edit";
    $.get(route, function (res) {
        debugger;
        $("#nombrenuevo").val(res[0].nombre);
        $("#idactualizar").val(res[0].idTipoGasto);
    });
}

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/TipoGastoCompra/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR ESTE CONCEPTO?",
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
        var nombre = $("#nombrenuevo").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast("NOMBRE VACIO", 2000, 'rounded');
            return;
        }
        var route = "/TipoGastoCompra/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {nombre: nombre},
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
        var nombre = $("#nombre").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast("NOMBRE VACIO", 2000, 'rounded');
            return;
        }
        var route = "/TipoGastoCompra";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {nombre: nombre},
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal1").closeModal();
                $(".lean-overlay").remove();
                swal({title: "CONCEPTO GUARDADO EXITOSAMENTE",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                $("#nombre").val("");
            },
            error: function () {
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