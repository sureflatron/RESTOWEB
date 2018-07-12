$(document).ready(function () {
    Cargar();
    $(function () {
        $("#productonombre").on('input', function () {
            var val = this.value;
            if ($('#productolist').find('option').filter(function () {
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                var abc = $("#productolist option[value='" + $('#productonombre').val() + "']").attr('data-id');
                $("#idcomposicion").val(abc);
            }
        });
    });
});

function Cargar() {
    var tabladatos = $('#datos');
    var idproducto = $('#Idproducto').val();
    var route = "/listacomposicionyproducto/" + idproducto;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td> " + value.nombre + " </td>" +
                    "<td> " + value.descripcion + " </td>" +
                    "<td>" + value.cantidad + "</td>" +
                    "</td>" +
                    "<td>" +
                    "<button value='" + value.id + "' OnClick='Mostrar(this);' class='waves-effect waves-light btn modal-trigger btn-floating'  href='#modal1' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-floating' value='" + value.id + "' OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        $('.modal-trigger').leanModal({
            complete: function () {
                $('.lean-overlay').remove();
            }
        });
    });
}

$("#guardar").click(function () {
    var id = $('#Idproducto').val();
    var idcomposicion = $('#idcomposicion').val();
    var cantidad = $('#nuevacantidad').val();
    if (!cantidad || !cantidad.trim().length) {
        return Materialize.toast('Cantidad Vacia', 2000, 'rounded');
    }
    if (!idcomposicion || !idcomposicion.trim().length) {
        return Materialize.toast('Composicion Vacia', 2000, 'rounded');
    }
    var route = "/ComposicionProducto";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {id: id, idcomposicion: idcomposicion, cantidad: cantidad},
        success: function () {
            Cargar();
            limpiarcampo();
            swal({title: "PRODUCTO GUARDADO EXITOSAMENTE",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $('.lean-overlay').remove();
        }, error: function () {
            if (cantidad == "") {
                swal({title: "ERROR AL GUARDAR",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            } else {
                swal({title: "EL PRODUCTO YA EXISTE",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
            $('.lean-overlay').remove();
        }
    });
});

function limpiarcampo() {
    $('#nuevacantidad').val("");
}

function Eliminar(btn) {
    var route = "/ComposicionProducto/" + btn.value + "";
    var token = $("#token").val();
    swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL PRODUCTO DEL COMBO?",
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
                        }, error: function () {
                            swal({title: "ERROR AL ELIMINAR",
                                type: "error",
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
}

function Mostrar(btn) {
    var route = "/editarcomposicion/" + btn.value;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#idcompo").val(value.id);
            $("#cantidadnueva").val(value.cantidad);
            document.getElementById('ingredientelabel').innerHTML = value.nombre;
        });
    });
}

$("#actualizar").click(function () {
    var value = $("#idcompo").val();
    var cantidad = $("#cantidadnueva").val();
    var route = "/ComposicionProducto/" + value + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {cantidad: cantidad},
        success: function () {
            Cargar();
            $("#modal1").closeModal();
            swal({title: "ACTUALIZACION CORRECTA",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $('.lean-overlay').remove();
        },
        error: function () {
            swal({title: "ERROR AL ACTUALIZAR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $('.lean-overlay').remove();
        }
    });
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}