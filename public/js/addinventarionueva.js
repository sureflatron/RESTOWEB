var idcompras = $('#venta').val();
var tipoInventario;
$(document).ready(function () {
    cargartabla();
    cargardatos();
    $("select#tipo").change(function () {
        var tipo = $(this).val();
        var idventa = $('#venta').val();
        if (tipo === "Ingreso") {
            tipoInventario = "Ingreso";
            $("#origen").hide();
            $("#destino").show();
            var route = "/actualizartipo/" + idventa + "/" + tipo;
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    $("#almaceninvdestino").val($("#AlmacenDestino").val());
                    $("#almaceninv").val(0);
                });
            });
        }
        if (tipo === "Egreso") {
            tipoInventario = "Egreso";
            $("#destino").hide();
            $("#origen").show();
            var route = "/actualizartipo/" + idventa + "/" + tipo;
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    $("#almaceninvdestino").val(0);
                    $("#almaceninv").val($("#AlmacenDestino").val());
                });
            });
        }
        if (tipo === "Traspaso") {
            tipoInventario = "Traspaso";
            $("#destino").show();
            $("#origen").show();
            var route = "/actualizartipo/" + idventa + "/" + tipo;
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    $("#Almacen").val(value.idAlmacen);
                    $('#Almacen option:selected').val(value.idAlmacen);
                    $("#Almacen").material_select();
                    $("#AlmacenDestino").val(value.idAlmacenDestino);
                    $('#AlmacenDestino option:selected').val(value.idAlmacenDestino);
                    $("#AlmacenDestino").material_select();
                    $("#almaceninvdestino").val($("#AlmacenDestino").val());
                    $("#almaceninv").val($("#Almacen").val());
                });
            });
        }
    });
    //Origen Egreso
    $("select#Almacen").change(function () {
        var idalamcennew = $(this).val();
        var idventa = $('#venta').val();
        var inv = $("#almaceninvdestino").val();
        if (tipoInventario == 'Traspaso') {
            if (inv == idalamcennew) {
                $("#Almacen").val($("#almaceninv").val());
                $('#Almacen option:selected').val($("#almaceninv").val());
                $("#Almacen").material_select();
                swal({title: "ADVERTENCIA!",
                    text: "Los almacenes de origen y destino deben ser distinos",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                var route = "/actualizaralmaceninv/" + idventa + "/" + idalamcennew;
                $.get(route, function (res) {
                    $(res).each(function (key, value) {
                        if (tipoInventario == "Egreso") {
                            $("#almaceninvdestino").val(0);
                        }
                        $("#almaceninv").val(idalamcennew);
                    });
                });
            }
        } else {
            var route = "/actualizaralmaceninv/" + idventa + "/" + idalamcennew;
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    if (tipoInventario == "Egreso") {
                        $("#almaceninvdestino").val(0);
                    }
                    $("#almaceninv").val(idalamcennew);
                });
            });
        }
    });
    //Destino Ingreso
    $("select#AlmacenDestino").change(function () {
        var idalamcennew = $(this).val();
        var idventa = $('#venta').val();
        var inv = $("#almaceninv").val();
        if (tipoInventario == 'Traspaso') {
            if (inv == idalamcennew) {
                $("#AlmacenDestino").val($("#almaceninvdestino").val());
                $('#AlmacenDestino option:selected').val($("#almaceninvdestino").val());
                $("#AlmacenDestino").material_select();
                swal({title: "ADVERTENCIA!",
                    text: "Los almacenes de origen y destino deben ser distinos",
                    type: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                var route = "/actualizaralmaceninvdestino/" + idventa + "/" + idalamcennew;
                $.get(route, function (res) {
                    $(res).each(function (key, value) {
                        if (tipoInventario == "Ingreso") {
                            $("#almaceninv").val(0);
                        }
                        $("#almaceninvdestino").val(idalamcennew);
                    });
                });
            }
        } else {
            var route = "/actualizaralmaceninvdestino/" + idventa + "/" + idalamcennew;
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    if (tipoInventario == "Ingreso") {
                        $("#almaceninv").val(0);
                    }
                    $("#almaceninvdestino").val(idalamcennew);
                });
            });
        }
    });
});
function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

/*
 * Cargar la tabla con el detalle de Inventario
 */
function cargartabla() {
    var tabladatos = $('#datos');
    var idventa = $('#venta').val();
    var route = "/listarinventariototal/" + idventa;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" +
                    "<img src='" + value.imagen + "' width='64' height='64' class='circle responsive-img valign profile-image materialboxed'></td>" +
                    "<td>" + value.producto + "</td>" +
                    "<td>" + value.descripcion + "</td>" +
                    "<td>" + value.color + "</td>" +
                    "<td>" + value.talla + "</td>" +
                    "<td>" + value.marca + "</td>" +
                    "<td>" + value.cantidad + "</td>" +
                    "<td>" + value.costo + "</td>" +
                    "<td>" + value.subtotal + "</td>" +
                    "<td><button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td></tr>");
        });
        paginador();
        $('.materialboxed').materialbox();
    });
}
/*
 * Cargar los datos del Inventario
 * Fecha, Tipo, almacen y Motivo
 */
function cargardatos() {
    var idventa = $('#venta').val();
    var route = "/obtenerdatosinventario/" + idventa;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var fechas = $('#fechas').val();
            var fecha = value.fecha;
            if (fecha == null) {
                $('#fecha').val(fechas);
            } else {
                $('#fecha').val(fecha);
            }
            var valors = value.idAlmacen;
            if (valors == 0) {
            } else {
                $("#Almacen").val(value.idAlmacen);
                $('#Almacen option:selected').val(value.idAlmacen);
                $("#Almacen").material_select();
            }
            if (value.idAlmacenDestino == 0) {
            } else {
                $("#AlmacenDestino").val(value.idAlmacenDestino);
                $('#AlmacenDestino option:selected').val(value.idAlmacenDestino);
                $("#AlmacenDestino").material_select();
            }
            if (value.idtipoinventario == "Ingreso") {
                $("#origen").hide();
                $("#destino").show();
            } else if (value.idtipoinventario == "Egreso") {
                $("#origen").show();
                $("#destino").hide();
            } else {
                $("#origen").show();
                $("#destino").show();
            }
            $("#motivo").val(value.idMotivomovimiento);
            $('#motivo option:selected').val(value.idMotivomovimiento);
            $("#motivo").material_select();
            $("#tipo").val(value.idtipoinventario);
            $('#tipo option:selected').val(value.idtipoinventario);
            $("#tipo").material_select();
            tipoInventario = value.idtipoinventario;
            $('#glosa').val(value.glosa);
            $("#almaceninvdestino").val(value.idAlmacenDestino);
            $("#almaceninv").val(value.idAlmacen);
        });
    });
}
/*
 * Mostrar los detalles de un producto en un modal
 * Para editar la cantidad y la unidad de medida con el que fue insertado al inventario
 */
function Mostrar(btn) {
    var route = "/Detalleinventario/" + btn.value;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#id').val(value.id);
            document.getElementById('nombre').innerHTML = value.nombre;
            $("#idproductoinvenatrio").val(value.idProducto);
            $('#cantidads').val(value.cantidad);
            $("#costos").val(value.costo);
            $("#unidadmedidass").val(value.idUnidadMedida);
            $('#unidadmedidass option:selected').val(value.idUnidadMedida);
            $("#unidadmedidass").material_select();
        });
    });
}
/*
 * Eliminar Producto del detalle de Inventario
 */
function Eliminar(btn) {
    var route = "/Detalleinventario/" + btn.value + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'DELETE',
        dataType: 'json',
        success: function () {
            $('#tablacategoria').DataTable().destroy();
            cargartabla();
            swal({title: "Bien!",
                text: "Producto Eliminado!",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        },
        error: function () {
            swal({title: "Error!",
                text: "Error al Eliminar",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.reload();
        }
    });
}
/*
 * Actualizar la cantidad de un producto en el detalle de inventario
 */
$("#actualizar").click(function () {
    var value = $("#id").val();
    var idpro = $("#idproductoinvenatrio").val();
    var idcompra = $('#venta').val();
    var cantidad = $('#cantidads').val();
    var costo = $("#costos").val();
    if (!cantidad || !cantidad.trim().length) {
        Materialize.toast('CANTIDAD VACIA', 1500, 'rounded');
        return;
    }
    if (parseInt(cantidad) <= 0) {
        Materialize.toast('La cantidad debe ser mayor a 0', 1500, 'rounded');
        return;
    }
    if (!costo || !costo.trim().length) {
        Materialize.toast('COSTO VACIO', 1500, 'rounded');
        return;
    }
    costo = parseInt(costo);
    if (costo < 0) {
        Materialize.toast('El costo debe ser mayor a 0', 1500, 'rounded');
        return;
    }
    if (tipoInventario == "Ingreso") {
        var route = "/Detalleinventario/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                idcompra: idcompra,
                cantidad: cantidad,
                costo: costo
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                cargartabla();
                $("#modal1").closeModal();
                $(".lean-overlay").remove();
                Materialize.toast('Actualización completada', 1000, 'rounded');
            }, error: function () {
                Materialize.toast('Error al Actualizar', 1500, 'rounded');
            }
        });
    } else if (tipoInventario == "Egreso") {
        var almacen = $("#almaceninv").val();
        var route = "/obtenerstock/" + idpro + "/" + almacen;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                var stock = parseInt(value.stock);
                cantidad = parseInt(cantidad);
                if (stock < cantidad) {
                    if (tipoInventario == "Egreso") {
                        return Materialize.toast("La cantidad que decea egresar no esta disponible", 2500, 'rounded');
                    } else if (tipoInventario == "Traspaso") {
                        return Materialize.toast("La cantidad que decea traspasar al otro almacen no esta disponible", 2500, 'rounded');
                    }
                }
                var route = "/Detalleinventario/" + value + "";
                var token = $("#token").val();
                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'PUT',
                    dataType: 'json',
                    data: {
                        idcompra: idcompra,
                        cantidad: cantidad
                    },
                    success: function () {
                        $('#tablacategoria').DataTable().destroy();
                        cargartabla();
                        $("#modal1").closeModal();
                        $(".lean-overlay").remove();
                        Materialize.toast('Actualización completada', 1000, 'rounded');
                    }, error: function () {
                        Materialize.toast('Error al Actualizar', 1500, 'rounded');
                    }
                });
            });
        });
    }
});
/*
 * finalizar la transaccion de inventario para luego imprimir el comprobante
 */
$("#guardaryimprimir").click(function () {
    var id = $("#venta").val();
    var fecha = $("#fecha").val();
    if (!fecha || !fecha.trim().length) {
        Materialize.toast('FECHA VACIA', 1000, 'rounded');
        return;
    }
    var almacen = $("#Almacen").val();
    var almacenDestino = $("#AlmacenDestino");
    if (!almacen || !almacen.trim().length) {
        Materialize.toast('ALMACEN VACIO', 1000, 'rounded');
        return;
    }
    var motivo = $("#motivo").val();
    if (!motivo || !motivo.trim().length) {
        Materialize.toast('MOTIVO VACIO', 1000, 'rounded');
        return;
    }
    var glosa = $('#glosa').val();
    var tipo = $("#tipo").val();
    if (!tipo || !tipo.trim().length) {
        Materialize.toast('TIPO VACIO', 1000, 'rounded');
        return;
    }
    if (tipo == "Traspaso") {
        if (almacen == almacenDestino) {
            return Materialize.toast('El almacen de origen debe ser diferente al almacen de destino', 2500, 'rounded');
        }
    }
    var iddelempleado = $('#iddelempleado').val();
    var route = "/Inventario/" + id + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            fecha: fecha,
            glosa: glosa,
            motivo: motivo,
            tipo: tipo
        },
        success: function () {
            window.open("/reporteinventario/" + id + "/" + iddelempleado);
            swal({title: "INVENTARIO REALIZADO CON EXITO",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.href = "/gestionarinventario";
        }, error: function () {
            swal({title: "ERROR AL GUARDAR EL INVENTARIO",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
/*
 * Buscar Producto mediante el boton
 */
$("#buscarproducto").click(function () {
    debugger;
    var codigoBarra = $("#codigoBarra").val();
    var codigoInterno = $("#codigoInterno").val();
    var nombre = $("#nombreproducto").val();
    $('#resultadoprodcuto').empty();
    if (!nombre || !nombre.trim().length) {
    } else {
        listProducto(nombre);
          debugger;
        return;
    }
    if (!codigoInterno || !codigoInterno.trim().length) {
    } else {
        listProducto(codigoInterno);
        debugger;
        return;
    }
    if (!codigoBarra || !codigoBarra.trim().length) {
    } else {
        listProducto(codigoBarra);
          debugger;
        return;
    }
});
/*
 * Metodo PAra Buscar producto pasando como parametro el Codigo de Barra, Codigo 
 * Interno o Nombre
 */
function listProducto(param) {
      debugger;
    var tabladatos = $('#resultadoprodcuto');
    $('#resultadoprodcuto').empty();
    var route = "/buscarProducto/" + param + "/1";
    $.get(route, function (res) {
        $('#resultadoprodcuto').empty();
        $(res).each(function (key, value) {
            $("#codigoBarra").val("");
            tabladatos.append("<tr>" +
                    "<td><img src='" + value.imagen + "' width='64' height='64' class='circle responsive-img valign profile-image materialbox'></td>" +
                    "<td>" + value.codigoDeBarra + "</td>" +
                    "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                    "<td>" + value.descripcion + "</td>" +
                    "<td>" + value.origen + "</td>" +
                    "<td>" + value.material + "</td>" +
                    "<td>" + value.color + "</td>" +
                    "<td>" + value.tamano + "</td>" +
                    "<td>" + value.marca + "</td>" +
                    "<td>" + value.stock + "</td>" +
                    "<td>" + value.precioVenta + "</td>" +
                    "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                    "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                    "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                    "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td></tr>");
            cargarunidaddemedida(value.id);
            $(".materialbox").materialbox();
        });
    });
}
/*
 * Metodo para buscar producto mediante el codigo de barra
 * @returns {undefined}
 */
function codigobarraagregardetalle() {
    var codigoBarra = $("#codigoBarra").val();
    var tabladatos = $('#resultadoprodcuto');
    if (!codigoBarra || !codigoBarra.trim().length) {
        return;
    } else {
        var route = "/buscarProducto/" + codigoBarra + "/1";
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#codigoBarra").val("");
                tabladatos.append("<tr>" +
                        "<td><img src='" + value.imagen + "' width='64' height='64' class='circle responsive-img valign profile-image materialbox'></td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.origen + "</td>" +
                        "<td>" + value.material + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td>" + value.stock + "</td>" +
                        "<td>" + value.precioVenta + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='number' name='' value ='1' id='cant" + value.id + "'></td>" +
                        "<td><input type='number' name='' value ='1' id='cost" + value.id + "'></td>" +
                        "<td><button class='btn'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td></tr>");
                cargarunidaddemedida(value.id);
                $(".materialbox").materialbox();
            });
        });
    }
}
/*
 * Cargar Unidad de Medida
 * @param {type} $id -> ID del producto en el cual se va a mostrar el combo 
 * con la unidad de medida
 * @returns {undefined}
 */
function cargarunidaddemedida($id) {
    $('#uni' + $id)
            .find('option')
            .remove()
            .end()
            .val('null');
    $('#uni' + $id).material_select();
    var route = "/listaunidad/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#uni' + $id).append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#uni' + $id).material_select();
        });
    });
}
/*
 * Agregar un producto al detalle de inventario
 * @param {type} $pro
 * @returns {undefined}
 */
function agregardetallecompra($pro) {
    var idcompra = $('#venta').val();
    var inventario = $('#venta').val();
    var producto = $('#' + $pro).val();
    var cantidad = $('#cant' + $pro).val();
    var costo = $('#cost' + $pro).val();
    var unidad = $('#uni' + $pro).val();
    var total = cantidad * costo;
    if (!cantidad || !cantidad.trim().length) {
        return swal({title: "Advertencia!",
            text: "La cantidad es obligatoria",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (parseInt(cantidad) <= 0) {
        return swal({title: "Advertencia!",
            text: "La cantidad debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (!costo || !costo.trim().length) {
        return swal({title: "Advertencia!",
            text: "EL Costo es Obligatorio.",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (parseInt(costo) <= 0) {
        return swal({title: "Advertencia!",
            text: "El Costo Debe Ser MAyor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (!unidad || !unidad.trim().length) {
        return swal({title: "Advertencia!",
            text: "La Unidad de Medida es Obligatoria",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (unidad == 0) {
        return swal({title: "Advertencia!",
            text: "La Unidad de Medida es Obligatoria",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var stock;
    if (tipoInventario == "Ingreso") {
        var route = "/Detalleinventario";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                idcompra: idcompra,
                unidad: unidad,
                producto: producto,
                inventario: inventario,
                cantidad: cantidad,
                costo: costo,
                total: total
            },
            success: function ($route) {
                $($route).each(function (key, value) {
                    $('#tablacategoria').DataTable().destroy();
                    $('#resultadoprodcuto').empty();
                    $('#nombreproducto').val("");
                    $('#codigoInterno').val("");
                    $('#codigoBarra').val("");
                    cargartabla();
                    Materialize.toast(value.mensaje, 2500, 'rounded');
                    $('#cant' + $pro).val("");
                    $('#cost' + $pro).val("");
                    window.location.href = "#det";
                });
            },
            error: function () {
                Materialize.toast('ERROR AL AGREGAR AL INVENTARIO', 1000, 'rounded');
            }
        });
    } else if (tipoInventario == "Egreso" || tipoInventario == "Traspaso") {
        var almacen = $("#almaceninv").val();
        var route = "/obtenerstock/" + producto + "/" + almacen;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                stock = parseInt(value.stock);
                cantidad = parseInt(cantidad);
                if (stock < cantidad) {
                    if (tipoInventario == "Egreso") {
                        return Materialize.toast("La cantidad que decea egresar no esta disponible", 2500, 'rounded');
                    } else if (tipoInventario == "Traspaso") {
                        return Materialize.toast("La cantidad que decea traspasar al otro almacen no esta disponible", 2500, 'rounded');
                    }
                }
                var route = "/Detalleinventario";
                var token = $("#token").val();
                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        idcompra: idcompra,
                        unidad: unidad,
                        producto: producto,
                        inventario: inventario,
                        cantidad: cantidad,
                        costo: costo,
                        total: total
                    },
                    success: function ($route) {
                        $($route).each(function (key, value) {
                            $('#tablacategoria').DataTable().destroy();
                            $('#resultadoprodcuto').empty();
                            $('#nombreproducto').val("");
                            $('#codigoInterno').val("");
                            $('#codigoBarra').val("");
                            cargartabla();
                            Materialize.toast(value.mensaje, 2500, 'rounded');
                            $('#cant' + $pro).val("");
                            $('#cost' + $pro).val("");
                            window.location.href = "#det";
                        });
                    },
                    error: function () {
                        Materialize.toast('ERROR AL AGREGAR AL INVENTARIO', 1000, 'rounded');
                    }
                });
            });
        });
    }
}
/*
 * Paginador de tabla de detalle de inventario
 */
function paginador() {
    $('#tablacategoria').DataTable({
        "pagingType": "full_numbers",
        retrieve: true,
        responsive: true
    });
}
/*
 * Metodo para buscra un producto mediante el cuadro de texto de Nombre
 * sin presionar el boton
 */
function buscarInputNombre() {
    var nombre = $("#nombreproducto").val();
    $('#resultadoprodcuto').empty();
    if (!nombre || !nombre.trim().length) {
    } else {
        listProducto(nombre);
        return;
    }
}
/*
 * Metodo para buscar Mediante el cuadro de texto Codigo Interno Sin tener  
 * que presionar el boton de busqueda
 */
function buscarInputCodInterno() {
    var codigoInterno = $("#codigoInterno").val();
    $('#resultadoprodcuto').empty();
    if (!codigoInterno || !codigoInterno.trim().length) {
    } else {
        listProducto(codigoInterno);
        return;
    }
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
        regexp = /.[0-9]{4}$/
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