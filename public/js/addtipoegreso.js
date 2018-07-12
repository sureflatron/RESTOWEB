$(document).ready(function () {
    Cargar();
    $("#closemodal").click(function (event) {
        $(".lean-overlay").remove();
    });
});

function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}

/*
 * Cargar los tabla con los tipos de egresos en la DB
 */
function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listacatipoegreso/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#modal2' title='Editar'>" +
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
 * Mostrar un tipo de egreso en un modal
 */
function Mostrar(btn) {
    var route = "/Tipoegreso/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#nombres").val(res.nombre);
        $("#id").val(res.id);
    });
}
/*
 * Eliminar un tipo de egreso
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Tipoegreso/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL TIPO DE EGRESO?",
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
 * Actualizar Tipo de Egreso
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombres").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Campos vacios', 1000, 'rounded');
            return;
        }
        var route = "/Tipoegreso/" + value + "";
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
            }, error: function () {
                swal({title: "YA EXISTE TIPO DE EGRESO",
                    type: "errror",
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
 * Guardar Tipo de egreso 
 */
$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#nombre").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Campos vacios', 1000, 'rounded');
            return;
        }
        var route = "/Tipoegreso";
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
                $(".lean-overlay").remove();
                $("#modal1").closeModal();
                swal({title: "GUARDADO EXITOSO",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                $("#nombre").val("");
            }, error: function () {
                swal({title: "TIPO DE EGRESO YA CREADO",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warninig",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
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