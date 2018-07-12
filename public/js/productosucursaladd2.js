$(document).ready(function () {
    cargarsucursal();
});

$('#sucursal').on('change', function () {
    var selectVal = $("#sucursal option:selected").val();
    $('#tablacategoria').DataTable().destroy();
    Cargar(selectVal);
});

function cargarsucursal() {
    var route = "/sucursales/";
    var pordefecto, pordefertoidsucursal;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#sucursal').append(
                    '<option value=' + value.id + ' selected name=' + value.nombre + '>' + value.nombre + '</option>');
            $('#sucursal').material_select();
            pordefecto = value.nombre;
            pordefertoidsucursal = value.id;
        });
        Cargar(pordefertoidsucursal);
    });
}

function Cargar(valor) {
    $("#cargando").show();
    $("#listado").hide();
    var tabladatos = $('#datos');
    var route = "/cargarproductosucursal/" + valor + "";
    $('#tablacategoria').DataTable().destroy();
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var categoria = "";
            if (value.categoria != null) {
                categoria = value.categoria;
            }
            var codigoDeBarra = "";
            if (value.codigoDeBarra != null) {
                codigoDeBarra = value.codigoDeBarra;
            }
            var codigoInterno = "";
            if (value.codigoInterno != null) {
                codigoInterno = value.codigoInterno;
            }
            var nombre = "";
            if (value.nombre != null) {
                nombre = value.nombre;
            }
            var precioVenta = "";
            if (value.precioVenta != null) {
                precioVenta = value.precioVenta;
            }
            var precioVentaCredito = "";
            if (value.precioVentaCredito != null) {
                precioVentaCredito = value.precioVentaCredito;
            }
            var origen = "";
            if (value.origen != null) {
                origen = value.origen;
            }
            var marca = "";
            if (value.marca != null) {
                marca = value.marca;
            }
            var idproducto = "";
            if (value.id != null) {
                idproducto = value.id;
            }
            var sucursal = value.sucursal;
            if (sucursal == "NO ASIGNADO") {
                sucursal = "<div class='chip'>NO ASIGNADO</div>";
            } else {
                sucursal = "<div class='chip naranja white-text'>" + value.sucursal + "</div>";
            }
            tabladatos.append("<tr> " +
                    "<td>" + categoria + "</td>" +
                    "<td>" + codigoDeBarra + "</td>" +
                    "<td>" + codigoInterno + "</td>" +
                    "<td>" + nombre + "</td>" +
                    "<td><input type='text' id='" + "precio" + idproducto + value.idsucursal + "' onkeypress='return NumCheck(event, this)' value='" + precioVenta + "'></input></td>" +
                    "<td><input type='text' id='" + "precioCredito" + idproducto + value.idsucursal + "' onkeypress='return NumCheck(event, this)' value='" + precioVentaCredito + "'></input></td>" +
                    "<td>" + origen + "</td>" +
                    "<td>" + marca + "</td>" +
                    "<td>" + value.tipo + "</td>" +
                    "<td>" + sucursal + "</td>" +
                    "<td id=" + value.id + "><button value=" + value.id + " OnClick='guardar2(" + idproducto + " , " + value.idsucursal + ");' class='btn-floating waves-effect waves-light btn' href='#' title='Operacion'><i class='mdi-content-add'></i></button></td>" +
                    "</tr>");

        });
        paginador();
        $("#cargando").hide();
        $("#listado").show();
    });
}
function guardar2(idproducto, idsucursal) {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var sucursal = $("#sucursal option:selected").val();
        var precioVenta = $('#precio' + idproducto + idsucursal).val();
        var precioVentaCredito = $('#precioCredito' + idproducto + idsucursal).val();
        if (!precioVenta || !precioVenta.trim().length) {
            return Materialize.toast('Precio Venta Vacio', 1000, 'rounded');
        }
        if (!precioVentaCredito || !precioVentaCredito.trim().length) {
            return Materialize.toast('Precio Venta a Credito Vacio', 1000, 'rounded');
        }
        if (precioVenta > 0) {
            if (idsucursal == 0) {
                var route = "/guardarproductosucursal";
                var token = $("#token").val();
                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idproducto: idproducto,
                        idsucursal: sucursal,
                        precioVenta: precioVenta,
                        precioVentaCredito: precioVentaCredito
                    },
                    success: function () {
                        Cargar(sucursal);
                        swal({title: "Bien! :)",
                            text: "Precios Asignados Correctamente",
                            type: "success",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1500});
                    }, error: function () {
                        swal({title: ":(",
                            text: "Error al guardar los precios",
                            type: "error",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1500});
                    }
                });
            } else {
                if (sucursal == idsucursal) {
                    var route = "/guardarproductosucursal/" + idproducto + "";
                    var token = $("#token").val();
                    $.ajax({
                        url: route,
                        headers: {'X-CSRF-TOKEN': token},
                        type: 'PUT',
                        dataType: 'json',
                        data: {
                            idproducto: idproducto,
                            idsucursal: idsucursal,
                            precioVenta: precioVenta,
                            precioVentaCredito: precioVentaCredito
                        },
                        success: function () {
                            Cargar(sucursal);
                            swal({title: "Bien! :)",
                                text: "Precios Actualizados Correctamente",
                                type: "success",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1500});
                        }, error: function () {
                            swal({title: ":(",
                                text: "Error al actualizar los precios",
                                type: "error",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1500});

                        }
                    });
                } else {
                    var combo = document.getElementById("sucursal");
                    var sucursales = combo.options[combo.selectedIndex].text;
                    swal({title: "ESTA EN LA SUCURSAL <" + sucursales + ">'                 SELECCIONE SU SUCURSAL",
                        type: "error",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1500});
                }
            }
        } else {
            swal({title: "Advertencia!",
                text: "El Precio de Venta al Contado no puede ser " + precioVenta,
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1500});
        }
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
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
        centered: true,
        order: [10, 'desc'],
        responsive: true,
        lengthMenu: [[20, 40, 60], [20, 40, 60]]
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