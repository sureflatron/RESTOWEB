$(document).ready(function () {
    Cargar();
    cargarbombo();
    cargarOrigen();
    cargarMarca();
});

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listadeproducto/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var categoria = "";
            if (value.categoria != null) {
                categoria = value.categoria;
            }
            var nombre = "";
            if (value.nombre != null) {
                nombre = value.nombre;
            }
            var precioVenta = "";
            if (value.precioVenta != null) {
                precioVenta = value.precioVenta;
            }
            var tipo = value.tipo;
            var descripcion = value.descripcion.substring(0, 50) + "...";
            if (tipo == "combo") {
                tabladatos.append("<tr>" +
                        "<td>" + value.tipo + "</td>" +
                        "<td>" + categoria + "</td>" +
                        "<td>" + nombre + "</td>" +
                        "<td>" + descripcion + "</td>" +
                        "<td>" + precioVenta + "</td>" +
                        "<td><button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating' href='#' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                        "</td><td>" +
                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                        "</td>" +
                        "<td>" +
                        "<button  class='btn btn-floating'  value=" + value.id + " OnClick='redirigircomposicion(this);' title='Composicion'>" +
                        "<i class='material-icons'>view_list</i>" +
                        "</button>" +
                        "</td><td>" +
                        "</td>" +
                        "</tr>");
            } else if (tipo == "ingrediente" || tipo == "item") {
                tabladatos.append("<tr>" +
                        "<td>" + value.tipo + "</td>" +
                        "<td>" + categoria + "</td>" +
                        "<td>" + nombre + "</td>" +
                        "<td>" + descripcion + "</td>" +
                        "<td>" + precioVenta + "</td>" +
                        "<td><button value=" + value.id + " OnClick='openmodal(this);' class='btn-floating waves-effect waves-light btn modal-trigger' href='#modal1' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                        "</td><td>" +
                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                        "</td>" +
                        "<td></td><td></td>" +
                        "</tr>");
            } else {
                tabladatos.append("<tr>" +
                        "<td>" + value.tipo + "</td>" +
                        "<td>" + categoria + "</td>" +
                        "<td>" + nombre + "</td>" +
                        "<td>" + descripcion + "</td>" +
                        "<td>" + precioVenta + "</td>" +
                        "<td><button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating' href='#' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                        "</td><td>" +
                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                        "</td>" +
                        "<td>" +
                        "</td><td>" +
                        "<button  class='btn btn-floating' value=" + value.id + " OnClick='redirigiringrediente(this);' title='Ingrediente'>" +
                        "<i class='material-icons'>view_module</i>" +
                        "</button>" +
                        "</td>" +
                        "</tr>");
            }
        });
        paginador();
    });
}

function redirigiringrediente(btn) {
    var route = "/vistaingrediente/" + btn.value;
    window.location.href = route;
}

function redirigircomposicion(btn) {
    var route = "/vistacomposicion/" + btn.value;
    window.location.href = route;
}

function limpiarcampos() {
    $("#productonombre").val("");
    $("#cantidad").val("");
}

function cargarbombo() {
    var route = "/TipoProducto/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#categoria').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#categoria').material_select();
        });
    });
}

function cargarOrigen() {
    var route = "/listaorigen/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#origen').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#origen').material_select();
        });
    });
}

function cargarMarca() {
    var route = "/listamarca/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#marca').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#marca').material_select();
        });
    });
}

function Mostrar(btn) {
    $("#categoria").material_select();
    var route = "/Producto/" + btn.value + "/edit";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#categoria").val(value.idcategoria);
            $('#categoria option:selected').val(value.idcategoria);
            $("#categoria").material_select();
            var tipo = value.tipo;
            if (tipo == "item") {
                document.getElementById("test1").checked = true;
            } else if (tipo == "servicio") {
                document.getElementById("test2").checked = true;
            } else if (tipo == "comida") {
                document.getElementById("test3").checked = true;
            } else if (tipo == "ingrediente") {
                document.getElementById("test4").checked = true;
            } else {
                document.getElementById("test2").checked = true;
            }
            $("#id").val(value.id);
            $("#nombre").val(value.nombre);
            $("#descripcion").val(value.descripcion);
            $("#unidadesCaja").val(value.unidadesCaja);
            $("#stockMin").val(value.stockMin);
            $("#stockMax").val(value.stockMax);
            $("#precioVenta").val(value.precioVenta);
            $("#precioVentaCredito").val(value.precioVentaCredito);
            $("#vistaPrevia").attr("src", value.imagen);
            $("#nombreimg").val("");
            var ventadirec = value.ventadirecta;
            if (ventadirec == "0") {
                document.getElementById("test8").checked = true;
            } else if (ventadirec == "1") {
                document.getElementById("test9").checked = true;
            }
            var conStock = value.conStock;
            if (conStock == 0) {
                document.getElementById("test5").checked = true;
            } else {
                document.getElementById("test6").checked = true;
            }
        });
    });
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
    };
    Lector.readAsDataURL(oFileInput.files[0]);
});

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Producto/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL PRODUCTO?",
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
 * Metodo crear nuevo producto
 */
$("#nuevoproducto").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var route = "/AddProductoResto/";
        window.location.href = route;
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});
/*
 * Metodo para actualizar un producto
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var item = document.getElementById('test1').checked;
        var comida = document.getElementById('test3').checked;
        var ingrediente = document.getElementById('test4').checked;
        var tipoproducto = '';
        if (item) {
            tipoproducto = 'item';
        } else if (comida) {
            tipoproducto = "comida";
        } else if (ingrediente) {
            tipoproducto = "ingrediente";
        } else {
            tipoproducto = "combo";
        }
        var si = document.getElementById('test8').checked;
        var no = document.getElementById('test9').checked;
        var ventadirecta = '';
        if (si) {
            ventadirecta = "0";
        } else if (no) {
            debugger;
            ventadirecta = "1";
        }
        var idTipoProducto = $('#categoria').val();
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();
        var precioVenta = $("#precioVenta").val();
        var marca = $("#marca").val();
        var origen = $("#origen").val();
        var unidadesCaja = $("#unidadesCaja").val();
        var stockMax = $("#stockMax").val();
        var stockMin = $("#stockMin").val();
        var imagen = jQuery('#vistaPrevia').attr('src');
        var precioVentaCredito = $("#precioVentaCredito").val();
        if (!idTipoProducto || !idTipoProducto.trim().length || idTipoProducto == "0") {
            return Materialize.toast('Tipo de Producto Vacio', 1000, 'rounded');
        }
        if (!nombre || !nombre.trim().length) {
            return Materialize.toast('Nombre Vacio', 1000, 'rounded');
        }
        if (!descripcion || !descripcion.trim().length) {
            return Materialize.toast('Descripcion Vacia', 1000, 'rounded');
        }
        if (!precioVenta || !precioVenta.trim().length) {
            return Materialize.toast('Precio Venta Vacio', 1000, 'rounded');
        }
        if (!precioVentaCredito || !precioVentaCredito.trim().length) {
            return Materialize.toast('Precio Venta a Credito Vacio', 1000, 'rounded');
        }
        if (!origen || !origen.trim().length || origen == "0") {
            return Materialize.toast('Campos vacios', 1000, 'rounded');
        }
        if (!marca || !marca.trim().length || marca == "0") {
            return Materialize.toast('Marca Vacia', 1000, 'rounded');
        }
        var sistock = document.getElementById('test5').checked;
        var conStock = 0;
        if (!sistock) {
            conStock = 1;
        }
        var route = "/ProductoResto/";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                precioVentaCredito: precioVentaCredito,
                conStock: conStock,
                idTipoProducto: idTipoProducto,
                id: id,
                nombre: nombre,
                descripcion: descripcion,
                precioVenta: precioVenta,
                imagen: imagen,
                tipoproducto: tipoproducto,
                idMarca: marca,
                idOrigen: origen,
                unidadesCaja: unidadesCaja,
                stockMin: stockMin,
                stockMax: stockMax,
                ventadirecta: ventadirecta
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

$("#actualizarComposicion").click(function () {
    var id = $("#idcompo").val();
    var cantidad = $("#cantidad").val();
    var route = "/ComposicionProducto/" + id + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {id: id, cantidad: cantidad},
        success: function () {
            $('#tablacategoria').DataTable().destroy();
            Cargar();
            $("#modal2").closeModal();
            Materialize.toast('Actualización completada', 1000);
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
        order: [2, 'asc'],
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