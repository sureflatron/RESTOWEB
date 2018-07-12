$(document).ready(function () {
    Cargar();
});

var cont = 0;

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

/*
 * Metodo para listar los origenes
 */
function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listaorigen/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.id + "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.abreviatura + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i></button>" +
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
 * Metodo para eliminar un Origen
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Origen/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL ORIGEN?",
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
 * Mostrar los datos de un origen en un modal
 */
function Mostrar(btn) {
    var route = "/Origen/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#Abreviatura").val(res.abreviatura);
        $("#nombre").val(res.nombre);
        $("#id").val(res.id);
    });
}
/*
 * Actualizar un origen
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombre").val();
        var abreviatura = $("#Abreviatura").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Campos vacios', 1000, 'rounded');
            return;
        }
        if (!abreviatura || !abreviatura.trim().length) {
            Materialize.toast('Campos vacios', 1000, 'rounded');
            return;
        }
        var route = "/Origen/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                abreviatura: abreviatura
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal1").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ACTUALIZACION COMPLETA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                swal({title: "YA EXISTE ORIGEN CON ESTE NOMBRE",
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
 * Limpiar campos del modal
 */
function limpiarcampo() {
    $("#esteeselnombre").val("");
    $("#esteesAbreviatura").val("");
}
/*
 * Guardar un nuevo origen
 */
$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#esteeselnombre").val();
        var abreviatura = $("#esteesAbreviatura").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Campos vacios', 1000, 'rounded');
            return;
        }
        if (!abreviatura || !abreviatura.trim().length) {
            Materialize.toast('Campos vacios', 1000, 'rounded');
            return;
        }
        var route = "/Origen";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                abreviatura: abreviatura
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                limpiarcampo();
                $(".lean-overlay").remove();
                $("#modal2").closeModal();
                swal({title: "GUARDADO EXITOSO",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                swal({title: "YA EXISTE ORIGEN",
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

function reordenar() {
    var num = 1;
    $('#tablacategoria tbody tr').each(function () {
        $(this).find('td').eq(0).text(num);
        num++;
    });
}
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