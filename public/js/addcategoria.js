$(document).ready(function () {
    Cargar();
});

var cont = 0;

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}



function Cargar() {
    debugger;
    var tabladatos = $('#datos');
    var route = "/listarCategoria/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            cont++;
            debugger;
            tabladatos.append("<tr>" +
                    "<td>" + value.id + "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td><img src='" + value.imagen + "' width='64' height='64' class='circle responsive-img valign profile-image materialboxed'></td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button id='btn" + value.id + "' class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button id='btn" + value.id + "' class='btn btn-floating' value=" + value.id + " OnClick='Subcategoria(this);' title='Subcategoria'>" +                   
                        "<i class='material-icons'>view_list</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        $(".materialboxed").materialbox();
        paginador();
    });
}

function Mostrar(btn) {
    var route = "/TipoProducto/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#nombre").val(res.nombre);
        $("#vistaPrevia2").attr("src", res.imagen);
        $("#nombreimg2").val("");
        $("#id").val(res.id);
    });
}
/*
function Mostrarsub(btn) {
    var route = "/TipoProducto/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#nombre").val(res.nombre);
        $("#vistaPrevia2").attr("src", res.imagen);
        $("#nombreimg2").val("");
        $("#id").val(res.id);
    });
}*/


function Subcategoria(btn) {
    debugger
    var direccion = "/GestionarSubcategoriarafa/";
    var route = direccion + btn.value;
    window.location.href = route;
}


function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/TipoProducto/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR lA CATEGORIA?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminarla!",
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
        var value = $("#id").val();
        var nombre = $("#nombre").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Nombre Vacio', 1000, 'rounded');
            return;
        }
        var imagen = jQuery('#vistaPrevia2').attr('src');
        var route = "/TipoProducto/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {nombre: nombre, imagen: imagen},
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

function limpiar() {
    $("#esteeselnombre").val(" ");
    var imagen = jQuery('#vistaPrevia').attr('src', '/images/productoavatar.png');
    $("#seleccionarImagen").val("");
    $("#nombreimg").val("");
}

jQuery('#seleccionarImagen').on('change', function (e) {
    var Lector,
            oFileInput = this;
    if (oFileInput.files.length === 0) {
        return;
    }
    Lector = new FileReader();
    Lector.onloadend = function (e) {
        jQuery('#vistaPrevia').attr('src', e.target.result);
    }
    Lector.readAsDataURL(oFileInput.files[0]);
});

jQuery('#seleccionarImagen2').on('change', function (e) {
    var Lector,
            oFileInput = this;
    if (oFileInput.files.length === 0) {
        return;
    }
    Lector = new FileReader();
    Lector.onloadend = function (e) {
        jQuery('#vistaPrevia2').attr('src', e.target.result);
    }
    Lector.readAsDataURL(oFileInput.files[0]);
});

$("#guardar").click(function () {
    debugger
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#esteeselnombre").val();
        var imagen = jQuery('#vistaPrevia').attr('src');
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Nombre Vacio', 1000, 'rounded');
            return;
        }
        var route = "/TipoProducto";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                imagen: imagen
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                limpiar();
                $(".lean-overlay").remove();
                $("#modal2").closeModal();
                $("#esteeselnombre").val("");
                swal({title: "CATEGORIA GUARDADA EXITOSAMENTE",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
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