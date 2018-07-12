$(document).ready(function () {
    Cargar();
    $(function () {
        $("#productonombre").on('input', function () {
            var val = this.value;
            if ($('#productolist').find('option').filter(function () {
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                var abc = $("#productolist option[value='" + $('#productonombre').val() + "']").attr('data-id');
                $("#idingrediente").val(abc);
            }
        });
    });
    $(function () {
        $("#unidadnombre").on('input', function () {
            var val = this.value;
            if ($('#unidadlist').find('option').filter(function () {
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                var abc = $("#unidadlist option[value='" + $('#unidadnombre').val() + "']").attr('data-id');
                $("#idunidad").val(abc);
            }
        });
    });
});

function Cargar() {
    var tabladatos = $('#datos');
    var idproducto = $('#Idproducto').val();
    var route = "/listaconunidadyingredientes/" + idproducto;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td> " + value.nombre + " </td>" +
                    "<td> " + value.descripcion + " </td>" +
                    "<td>" + value.abreviatura + "</td>" +
                    "<td>" + value.cantidad + "</td>" +
                    "<td>" + value.costo + "</td>" +
                    "<td>" +
                    "<button value=" + value.eliminar + " OnClick='Mostrar(this);' class='btn-floating waves-effect waves-light btn modal-trigger'  href='#modal10' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-floating' value=" + value.eliminar + "   OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
            $('.modal-trigger').leanModal();
            document.getElementById('total').innerHTML = value.total;
        });
    });
}

function Mostrar(btn) {
    var route = "/editardetalleingrediente/" + btn.value;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#id").val(value.eliminar);
            document.getElementById('ingredientelabel').innerHTML = value.elingrediente;
            $("#unidad").val(value.numunidada);
            $("#unidad").material_select();
            $("#cantidad").val(value.cantidad);
            $("#Costoactuaa").val(value.costo);
        });
    });
}

function limpiarcampo() {
    $('#nuevacantidad').val("");
    $('#costo').val("");
    $('#productonombre').val("");
    $("#unidadnombre").val("");
}

$("#guardar").click(function () {
    var id = $('#Idproducto').val();
    var idIngrediente = $('#idingrediente').val();
    var idUnidadMedida = $('#idunidad').val();
    var cantidad = $('#nuevacantidad').val();
    var costo = $('#costo').val();
    if (!idIngrediente || !idIngrediente.trim().length) {
        return Materialize.toast('Ingrediente Vacio', 1000, 'rounded');
    }
    if (!idUnidadMedida || !idUnidadMedida.trim().length) {
        return Materialize.toast('Unidad de Medida Vacio', 1000, 'rounded');
    }
    if (!cantidad || !cantidad.trim().length) {
        return Materialize.toast('Cantidad Vacia', 1000, 'rounded');
    }
    if (!costo || !costo.trim().length) {
        return Materialize.toast('Costo Vacio', 1000, 'rounded');
    }
    var route = "/Ingredienteproducto";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            idIngrediente: idIngrediente,
            idUnidadMedida: idUnidadMedida,
            cantidad: cantidad,
            costo: costo
        },
        success: function () {
            Cargar();
            limpiarcampo();
            swal({title: "Guardado Exitosamente",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }, error: function () {
            swal({title: "Ya existe el ingrediente",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});

function Eliminar(btn) {
    var route = "/Ingredienteproducto/" + btn.value + "";
    var token = $("#token").val();
    swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL INGREDIENTE?",
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
                            swal({title: "Eliminacion Completa",
                                type: "success",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1000});
                        }, error: function () {
                            swal({title: "Error al Eliminar",
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

$("#actualizar").click(function () {
    var value = $("#id").val();
    var unidad = $("#unidad").val();
    var cantidad = $("#cantidad").val();
    var costo = $('#Costoactuaa').val();
    var route = "/Ingredienteproducto/" + value;
    if (!cantidad || !cantidad.trim().length) {
        return Materialize.toast('Campos vacios', 1000, 'rounded');
    }
    if (!costo || !costo.trim().length) {
        return Materialize.toast('Campos vacios', 1000, 'rounded');
    }
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {unidad: unidad, cantidad: cantidad, costo: costo},
        success: function () {
            Cargar();
            $("#modal10").closeModal();
            swal({title: "Actualizacion Completa",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        },
        error: function () {
            swal({title: "Error al Actualizar",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function NumCheck(e, field) {
    key = e.keyCode ? e.keyCode : e.which
    // backspace
    if (key == 8)
        return true
    // 0-9
    if (key > 47 && key < 58) {
        if (field.value == "")
            return true
        regexp = /.[0-9]{9}$/
        return !(regexp.test(field.value))
    }
    // .
    if (key == 46) {
        if (field.value == "")
            return false
        regexp = /^[0-9]+$/
        return regexp.test(field.value)
    }
    // other key
    return false

}