var iddelpuntoventa;
var iddelempleado;
var idprodu = 0;
var idventas = 0;
var idproductos = 0;
var totalfactura = 0;
var totalConDescuento = 0;
var idCliente = 3;
var descuentoTotal;
var bolivianos1;
var descuent;

$(document).ready(function () {
    $('#codigoInterno').keypress(function (e) {
        if (e.which == 13) {
            codigobarraagregardetalle();
        }
    });
    $('#nombreproducto').keypress(function (e) {
        if (e.which == 13) {
            buscarproductoinput();
        }
    });
    $('#codigoBarra').keypress(function (e) {
        if (e.which == 13) {
            buscarproductoinput();
        }
    });
    $("#cobroAnticipo").formatter({
        'pattern': '{{9999999999}}'
    });

    $("#porcentajepago").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: 30,
        grid_num: 10,
        onFinish: function (data) {
            calcularpagoinicial(data.from);
        }
    });
    iddelpuntoventa = $('#iddelpuntoventa').val();
    iddelempleado = $('#iddelempleado').val();
    Cargartabla();
    validarestado();
    seguridad();
    cargardescuento();
    cargarMoneda();
    cargaralmacendesucursal();
    $(function () {
        $("#cliNombre").on('input', function () {
            var val = this.value;
            if ($('#clienteslist').find('option').filter(function () {
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                var abc = $("#clienteslist option[value='" + $('#cliNombre').val() + "']").attr('data-id');
                inputcargar(abc);
                $("#idCliente").val(abc);
                var route = "/nitrazoncliente/" + abc;
                $.get(route, function (res) {
                    $(res).each(function (key, value) {
                        $("#cliNombre2").val(value.nombre);
                        $("#nit").val(value.nit);
                        $("#razonsocial").val(value.razonSocial);
                    });
                });
            }
        });
        $("#cliNombre2").on('input', function () {
            var val = this.value;
            if ($('#clienteslist2').find('option').filter(function () {
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                var abc = $("#clienteslist2 option[value='" + $('#cliNombre2').val() + "']").attr('data-id');
                inputcargar(abc);
                $("#idCliente").val(abc);
                var route = "/nitrazoncliente/" + abc;
                $.get(route, function (res) {
                    $(res).each(function (key, value) {
                        $("#nit").val(value.nit);
                        $("#razonsocial").val(value.razonSocial);
                    });
                });
            }
        });
    });



    $(".group1").click(function (evento) {
        var val = $(this).val();
        if (val == "contado") {
            $("#pagoEfectivo").attr("style", "display :block;");
            $("#pagoCredito").attr("style", "display:none;");
            $("#pagoTarjeta").attr("style", "display:none;");
            $("#pagoDeposito").attr("style", "display:none;");
            $("#pagoCheque").attr("style", "display:none;");
        } else if (val == "credito") {
            $("#pagoEfectivo").attr("style", "display:none;");
            $("#pagoCredito").attr("style", "display :block;");
            $("#pagoTarjeta").attr("style", "display:none;");
            $("#pagoDeposito").attr("style", "display:none;");
            $("#pagoCheque").attr("style", "display:none;");
        } else if (val == "tarjeta") {
            $("#pagoEfectivo").attr("style", "display:none;");
            $("#pagoCredito").attr("style", "display:none;");
            $("#pagoTarjeta").attr("style", "display:block;");
            $("#pagoDeposito").attr("style", "display:none;");
            $("#pagoCheque").attr("style", "display:none;");
        } else if (val == "deposito") {
            $("#pagoEfectivo").attr("style", "display:none;");
            $("#pagoCredito").attr("style", "display:none;");
            $("#pagoTarjeta").attr("style", "display:none;");
            $("#pagoDeposito").attr("style", "display:block;");
            $("#pagoCheque").attr("style", "display:none;");
        } else {
            $("#pagoEfectivo").attr("style", "display:none;");
            $("#pagoCredito").attr("style", "display:none;");
            $("#pagoTarjeta").attr("style", "display:none;");
            $("#pagoDeposito").attr("style", "display:none;");
            $("#pagoCheque").attr("style", "display:block;");
        }
    });
    $(".des").click(function (evento) {
        var val = $(this).val();
        if (val == "1") {
            $("#descuentoPor").attr("style", "display :block;");
            $("#descuentoImpor").attr("style", "display:none;");

        } else if (val == "2") {
            $("#descuentoPor").attr("style", "display:none;");
            $("#descuentoImpor").attr("style", "display :block;");
        }
    });
    $(".efec").click(function (evento) {
        var bs = document.getElementById('bs').checked;
        var sus = document.getElementById('sus').checked;
        if (bs === true) {
            $("#pago").val("");
            $("#cambio").val("0");
        } else if (sus === true) {
            $("#cambio").val("0");
            $("#pago").val("");
        }
    });
    $(".group2").click(function (evento) {
        var val = $(this).val();
        if (val == "noenvio") {
            $("#pagoEnvio").attr("style", "display :block;");
        } else {
            $("#pagoEnvio").attr("style", "display:none;");
        }
    });
    $("select#almacencombo").change(function () {
        var idalamcennew = $(this).val();
        var idventa = $('#venta').val();
        var route = "/actualizaralmacenventa/" + idventa + "/" + idalamcennew;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#almacen").val(idalamcennew);
                $("#nombrealmacenventa").text(value.nombre);
                $('#resultadoprodcuto').empty();
                $('#datos').empty();
                $("#total").text("0");
            });
        });
    });
});

function calcularpagoinicial(por) {
    var totalfactura = parseFloat($("#totalacobrarcondescuentoenlaventa").text()).toFixed(2);
    por = parseFloat(por).toFixed(2);
    var porcentaje = (totalfactura * por) / 100;
    porcentaje = porcentaje.toFixed(2);
    var saldo = totalfactura - porcentaje;
    saldo = saldo.toFixed(2)
    $("#aCuenta").val(porcentaje);
    $("#saldo").val(saldo);
}

function calcularporcentaje() {
    calcular_totalCredito();
    var acuenta = $("#aCuenta").val();
    acuenta = parseFloat(acuenta).toFixed(2);
    var totalfactura = parseFloat($("#totalacobrarcondescuentoenlaventa").text()).toFixed(2);
    var porcentaje = (acuenta * 100) / totalfactura;
    porcentaje = parseInt(porcentaje);
    var slider = $("#porcentajepago").data("ionRangeSlider");
    slider.update({
        min: 0,
        max: 100,
        from: porcentaje
    });
}

/*
 * Metodo para cargar los clientes en el data list al momento de seleccionar el 
 *  campo de texto para realizar la busqueda
 * @returns {undefined}
 */
function cargarcliente() {
    $('#clienteslist')
            .find('option')
            .remove()
            .end();
    var route = "/listarcliente/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#clienteslist').append('<option data-id=' + value.id + ' value="' + value.nombre + '"> NIT:' + value.NIT);
        });
    });
}


/*
 * Metodo para calcular el cambio cuando se paga en efectivo
 * @returns {undefined}
 */
function calcular_total() {
    var descuentoPorcentaje = document.getElementById('descuentoP').checked;
    var bs = document.getElementById('bs').checked;
    if (descuentoPorcentaje == true) {
        var importe_total = 0;
        var entradavalor = parseFloat($('#pago').val());
        var numero = parseFloat(totalConDescuento);
        if (bs === true) {
            importe_total = parseFloat(entradavalor) - numero;
        } else {
            importe_total = (parseFloat(entradavalor) * bolivianos1) - numero;
        }
        if (importe_total < 0) {
            $('#cambio').val("0");
        } else {
            if (isNaN(importe_total)) {
                $("#cambio").val(0);
            } else {
                importe_total = importe_total.toFixed(2);
                $("#cambio").val(importe_total);
            }
        }
    } else {
        var importe_total = 0;
        var entradavalor = parseFloat($('#pago').val());
        var numero = parseFloat($("#totalcondescuento").val());
        if (bs === true) {
            importe_total = parseFloat(entradavalor) - numero;
        } else {
            importe_total = (parseFloat(entradavalor) * bolivianos1) - numero;
        }
        if (importe_total < 0) {
            $('#cambio').val("0");
        } else {
            if (isNaN(importe_total)) {
                $("#cambio").val(0);
            } else {
                importe_total = importe_total.toFixed(2);
                $("#cambio").val(importe_total);
            }
        }
    }
}
/*
 * Metodo para calcular el saldo del monto a pagar cuando la venta es realizada
 * a credito y se hace un pago inicial
 * @returns {undefined}
 */
function calcular_totalCredito() {
    var importe_total = 0;
    var entradavalor = parseFloat($('#aCuenta').val());
    var saldo = $("#totalacobrarcondescuentoenlaventa").text();
    var numero = parseFloat(saldo);
    importe_total = numero - entradavalor;
    if (importe_total < 0) {
        $('#saldo').val("0");
    } else {
        $("#saldo").val(importe_total.toFixed(2));
    }
}
/*
 * Agregar un producto mediante el Codigo de Barra
 * @returns {undefined}
 */
function codigobarraagregardetalle() {
    var codigoBarra = $("#codigoBarra").val();
    $("#codigoInterno").val("");
    $("#nombreproducto").val("");
    var idalmacen = $("#almacen").val();
    $("#codigoBarra").val("");
    if (!codigoBarra || !codigoBarra.trim().length) {
        return false;
    } else {
        var route = "/buscarbarcode/" + codigoBarra + "/" + idalmacen;
        $.get(route, function (res) {
            var f = false;
            $(res).each(function (key, value) {
                $("#codigoBarra").val("");
                f = true;
                agregaraldetallecodigobarra(value.id, value.stock);
            });
            if (f) {
            } else {
                swal({title: "Producto NO Encontrado",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    }
}
/*
 * Metodo para consultar el estado de la venta
 * Si esta Cobrada, Anulada, Guardada, credito o Proforma
 * @returns {undefined}
 */
function seguridad() {
    var idventa = $('#venta').val();
    var route = "/validarestado/" + idventa;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.estado == 1) {
            }
            if (value.estado == 0) {
                return $mensaje = 0;
            }
            if (value.estado == 2) {
                return $mensaje = 2;
            }
            if (value.estado == 4) {
                return $mensaje = 4;
            }
            if (value.estado == 5) {
                return $mensaje = 5;
            }
        });
    });
}
/*
 * Metodo para buscar un cliente enviando como parametro el NIT del cliente
 * @returns {undefined}
 */
function buscarcliente() {
    $("#razonsocial").val("");
    var idventa = parseInt($('#nit').val()) + "";
    var route = "/buscarcliente/" + idventa;
    $.get(route, function (res) {
        if (res == 0) {
            $("#razonsocial").val("");
        }
        $(res).each(function (key, value) {
            value.razonSocial;
            $("#razonsocial").val(value.razonSocial);
            idCliente = value.id;
        });
    });
}
/*
 * 
 * @param {type} $nit -> NIT del cliente
 * @param {type} $nombre -> Nombre del cliente
 * @returns {undefined}
 */
function guardarcliente($nit, $nombre) {
    var route = "/agregarcliente/" + $nit + "/" + $nombre;
    $.get(route, function (res) {
        if (res == 0) {
            buscarcliente();
            return false;
        }
    });
}


/*
 * Metodo para cargar la tabla con el detalle de la proforma
 * @returns {undefined}
 */
function Cargartabla() {
    debugger;
    var tabladatos = $('#datos');
    var idventa = $('#venta').val();
    var route = "/detalleproformas/" + idventa;
    $('#datos').empty();
    $.get(route, function (res) {
        $('#datos').empty();
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" +
                    "<img src='" + value.imagen + "' width='64px' height='64px' class='circle responsive-img valign profile-image materialboxed' />" +
                    "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.descripcion + "</td>" +
                    "<td>" + value.color + "</td>" +
                    "<td>" + value.cantidad + "</td>" +
                    "<td>" + value.precioVenta + "</td>" +
                    "<td>" + value.subtotal + "</td>" +
                    "<td>" + value.porcentajedescuento + "%</td>" +
                    "<td>" + value.importedescuento + "</td>" +
                    "<td>" + value.totalneto + "</td>" +
                    "<td>" +
                    "<a class='btn dropdown-button' href='#!' data-activates='descu" + value.id + "'>Descuentos<i class='mdi-navigation-arrow-drop-down right'></i></a>" +
                    "<ul id='descu" + value.id + "' class ='dropdown-content'></ul>" +
                    "</td>" +
                    "<td>" +
                    "<a class='btn btn-floating waves-efect modal-trigger' title='EDITAR' href='#modal4' onclick='openmodal(" + value.id + ")'>" +
                    "<i class='mdi-editor-mode-edit'></i>" +
                    "</a>" +
                    "</td>" +
                    "<td>" +
                    "<button class='btn btn-floating waves-efect' OnClick='Eliminar(" + value.id + "," + value.idProducto + " );'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>"
                    );
            cardardescuentoproducto(value.id);
            document.getElementById('total').innerHTML = value.total;
            totalfactura = value.total;
            idproductos = value.idProducto;
            idventas = value.idVenta;
            $('.materialboxed').materialbox();
        });
    });
}

/*
 * Metodo para guardar un nuevo cliente en la base de datos del sistema
 */
$("#nuevocliente").click(function () {
    var nombre = $("#nombre").val();
    var direccion = $("#direccion").val();
    var telefonoFijo = $("#telefonoFijo").val();
    var correo = $("#correo").val();
    var celular = $("#celular").val();
    var razonSocial = $("#razonSocial").val( );
    var preferencias = $("#preferencias").val();
    var idCiudad = $("#ciudad").val();
    var NIT = parseInt($("#NIT").val()) + "";
    if (!nombre || !nombre.trim().length) {
        Materialize.toast('Nombre es Obligatorio', 1000, 'rounded');
        return;
    }
    if (!razonSocial || !razonSocial.trim().length) {
        Materialize.toast('Razon Social Vacia', 1000, 'rounded');
        return;
    }
    if (!NIT || !NIT.trim().length) {
        Materialize.toast('NIT vacio', 1000, 'rounded');
        return;
    }
    if (!correo || !correo.trim().length) {
    } else {
        if (!valEmail(correo)) {
            Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
            return;
        }
    }
    var tipocliente = $("#tipocliente").val( );
    var descuentos = $("#descuento").val();
    var route = "/Cliente";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            nombre: nombre,
            direccion: direccion,
            telefonoFijo: telefonoFijo,
            correo: correo,
            celular: celular,
            razonSocial: razonSocial,
            NIT: NIT,
            Preferencias: preferencias,
            idCiudad: idCiudad,
            idTipoCliente: tipocliente,
            idDescuento: descuentos
        },
        success: function (data) {
            $("#modal2").closeModal();
            Materialize.toast('Concesionario creado exitosamente', 1000, 'rounded');
            $("#nombre").val("");
            $("#direccion").val("");
            $("#telefonoFijo").val("");
            $("#correo").val("");
            $("#celular").val("");
            $("#razonSocial").val("");
            $("#NIT").val("");
            cargarcliente();
            $(data).each(function (key, value) {
                $("#cliNombre").val(value.nombre);
                $("#idCliente").val(value.id);
                $("#cliNombre2").val(value.nombre);
                var route = "/nitrazoncliente/" + value.id;
                $.get(route, function (res) {
                    $(res).each(function (key, value) {
                        $("#nit").val(value.nit);
                        $("#razonsocial").val(value.razonSocial);
                    });
                });
            });
        },
        error: function () {
            Materialize.toast('Error al Guardar el Concesionario', 2000, 'rounded');
        }
    });
});

/*
 * Buscar Producto mediante el input de nombre y codigo Interno
 * @returns {Boolean}
 */
function buscarproductoinput() {
    var codigoInterno = $("#codigoInterno").val();
    var nombre = $("#nombreproducto").val();
    var tabladatos = $('#resultadoprodcuto');
    var idalmacen = $("#almacen").val();
    tabladatos.empty();
    if (!nombre || !nombre.trim().length) {
    } else {
        var route = "/buscarnombreproducto/" + nombre + "/" + idalmacen;
        $.get(route, function (res) {
            $('#resultadoprodcuto').empty();
            $(res).each(function (key, value) {
                var Stock;
                if (value.stock == null) {
                    Stock = 0;
                } else {
                    Stock = value.stock;
                }
//                var productoNroFactura = value.id + '~' + value.nroFacturaCompra;
                $('#resultadoprodcuto').append("<tr>" +
                        "<td><img src='" + value.imagen + "' width='64px' height='64px' class='circle responsive-img valign profile-image materialboxed' /></td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><label id='stockP" + value.id + "' style='font-size: 15px; color: black;'>" + Stock + "</label></td>" +
                        "<td><input type='text' name='' value='" + value.precioVenta + "' id='precioP" + value.id + "' min='0' onkeypress='run(this)' ></td>" +
                        "<td><input type='text' name='' value ='1' id='" + value.id + "' onkeypress='return isNumberKey(this);' ></td>" +
                        "<td><button class='btn btn-floating waves-efect' value='" + value.id + "' OnClick='agregar(this);'><i class='mdi-av-playlist-add' ></i></button></td>" +
                        "</tr>");
            });
            $('.materialboxed').materialbox();
        });
        return false;
    }
    if (!codigoInterno || !codigoInterno.trim().length) {
    } else {
        var route = "/buscarcodintero/" + codigoInterno + "/" + idalmacen;
        $.get(route, function (res) {
            $('#resultadoprodcuto').empty();
            $(res).each(function (key, value) {
                var Stock;
                if (value.stock == null) {
                    Stock = 0;
                } else {
                    Stock = value.stock;
                }
//                var productoNroFactura = value.id + '~' + value.nroFacturaCompra;
                $('#resultadoprodcuto').append("<tr>" +
                        "<td><img src='" + value.imagen + "' width='64px' height='64px' class='circle responsive-img valign profile-image materialboxed' /></td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><label id='stockP" + value.id + "' style='font-size: 15px; color: black;'>" + Stock + "</label></td>" +
                        "<td><input type='text' name='' value='" + value.precioVenta + "' id='precioP" + value.id + "' min='0' onkeypress='run(this)'></td>" +
                        "<td><input type='text' name='' value ='1' id='" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><button class='btn btn-floating waves-efect' value='" + value.id + "' OnClick='agregar(this);'><i class='mdi-av-playlist-add' ></i></button></td>" +
                        "</tr>");
            });
            $('.materialboxed').materialbox();
        });
        return false;
    }
}

/*
 * Metodo para agregar un producto al detalle de la venta
 * @param {type} $btn -> BOTON con el ID del producto a vender
 * @returns {undefined}
 */
function agregar(btn) {
    debugger;
    idprodu = parseInt(btn);
    document.getElementById('idproducto').value = btn.value;
    agregardetalleventa();
}
/*
 * Metodo para cargar la tabla con el detalle de la venta
 * @returns {undefined}
 */


function openmodal(btn) {
    debugger;
    var idVenta = $("#venta").val();
    if (idVenta == "") {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "No se puede editar el detalle de una venta cobrada o anulada",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/ValidarventaantiguaProforma/" + idVenta;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede editar el detalle de una venta anulada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede editar el detalle de una venta cobrada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        Mostrar(btn);
        $("#modal4").openModal();
    });
}


function openmodalChasis(btn, cantidad) {
    debugger;
    var idVenta = $("#venta").val();
    if (idVenta == "") {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "No se puede editar el detalle de una venta cobrada o anulada",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/ValidarventaantiguaProforma/" + idVenta;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede editar el detalle de una venta anulada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede editar el detalle de una venta cobrada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        var cant = cantidad;
        MostrarChasis(btn, cant);
        $("#modal5").openModal();
    });
}


function MostrarChasis(btn, cantid) {
    debugger;
    var idVenta = $("#venta").val();
    var cantida = cantid;
    var tabladatos = $('#resultadoChasis');
    var route = "/listarChasisNoVendidos/" + btn;
    $('#resultadoChasis').empty();
    $.get(route, function (res) {
        $('#resultadoChasis').empty();
        $(res).each(function (key, value) {
            var concatenar = value.idProducto + "~" + value.nroChasis + "~" + value.nroMotor + "~" + value.seleccionado + "~" + cantida;
            var selec = value.seleccionado;
            var var1
            if (selec == 0) {
                var1 = "";
            } else {
                var1 = "checked";
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.nroFacturaCompra + "</td>" +
                    "<td>" + value.nroContainer + "</td>" +
                    "<td>" + value.nroChasis + "</td>" +
                    "<td>" + value.nroMotor + "</td>" +
                    "<td>" +
                    "<input type='checkbox' id='" + concatenar + "' name ='listaCompras' value=" + concatenar + " " + var1 + "/><label for='" + concatenar + "'></label>" +
                    "</td>" +
                    "</tr>"
                    );
        });
    });

}

$("#guardarChasis").click(function () {
    debugger;
    var selected = new Array();
    $("input:checkbox[name=listaCompras]:checked").each(function () {
        selected.push($(this).val());
    });

    var NOselected = new Array();
    $("input:checkbox[name=listaCompras]").each(function () {
        NOselected.push($(this).val());
    });


    var nuevoVector = new Array();
    var paso;
//    var seleccionados = selected.length;
    if (selected.length == 0) {
        var i;
        for (i = 0; NOselected.length > i; i++) {
            var s = NOselected[i];
            s = s + "~" + "0";
            nuevoVector.push(s);
        }
    } else {
        for (paso = 0; NOselected.length > paso; paso++) {
            var paso1
            var noselec = NOselected[paso];
            var selec;
            var boolean = false;
            for (paso1 = 0; selected.length > paso1; paso1++) {
                selec = NOselected[paso];
                if (selected.indexOf(selec) >= 0 && boolean == false) {
                    selec = selec + "~" + "1";
                    nuevoVector.push(selec);
                    boolean = true;

                }
            }

            if (boolean == false) {
                selec = selec + "~" + "0";
                nuevoVector.push(selec);
            }

        }
    }

    var tamano = selected.length;
    var cont = 0;
    var idventa = $("#venta").val();
    var noseSeleccionado = nuevoVector[cont];
    var varSeleccionado = noseSeleccionado.split("~");
    var productoSeleccionado = varSeleccionado[0];

    var cantidadChasis = varSeleccionado[4];
    var seleccioneCheck = selected.length;
    var vector;


    var route = "/cantidadDeChasis/";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {productoSeleccionado: productoSeleccionado,
            idventa: idventa,
            cantidadChasis: cantidadChasis,
            selected1: nuevoVector,
            seleccioneCheck: seleccioneCheck},
        success: function (json) {
//            MostrarChasis(productoC);
            var exito1 = json.exito;
            var can = json.cantidadCH;
//                document.writeln(exito1);
            if (exito1 === "1") {
                Materialize.toast('Guardado Correctamente', 2500, 'rounded');
            }

            if (can === "2") {
                Materialize.toast('SELECCIONE LA CANTIDA CORRECTA DE CHASIS!', 2500, 'rounded');
            }
            Cargartabla();

        },
        error: function (json) {
//            MostrarChasis(productoC);
//            Materialize.toast('Error al Seleccionar', 2500, 'rounded');
        }
    });



});



function Mostrar(btn) {
    var route = "/Detalleventa/" + btn;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#idproC").val(value.idProducto);
            $("#iddetalleventa").val(value.id);
            $('#nombreprod').text(value.nombre);
            $('#descripcionprod').text(value.descripcion);
            jQuery('#imgprod').attr('src', value.imagen);
            $("#precioprod").text(value.precio);
            $("#cantidadproducto").val(value.cantidad);
            $("#subtotalpord").text(value.total);
        });
    });
}

function cambiarcanidad() {
    var route = "/actualizarcantidad";
    var token = $("#token").val();
    var idAlmacen = $("#almacen").val();
    var id = $("#iddetalleventa").val();
    var pro = $("#idproC").val();
    var cantidad = $("#cantidadproducto").val();
    if (cantidad == "" || cantidad == "0") {
        swal({title: "Adverencia!",
            text: "Inserte la cantidad",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
        return false;
    }
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            id: id,
            idAlmacen: idAlmacen,
            cantidad: cantidad,
            pro: pro
        },
        success: function ($route) {
            $($route).each(function (key, value) {
                if (value.mensaje == "Cantidad Actualizada Exitosamente") {
                    swal({title: "Bien!",
                        text: value.mensaje,
                        type: "success",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                    return $("#modal4").closeModal();
                } else {
                    swal({title: "Adverencia!",
                        text: value.mensaje,
                        type: "warning",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                    return $("#modal4").closeModal();
                }
            });
            Cargartabla();
        }, error: function () {
            swal({title: "Error Al Actualizar",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            return location.reload();
        }
    });
}

function cardardescuentoproducto(id) {
    var lista = $('#descu' + id);
    var route = "/listardescuentos/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            lista.append("<li>" +
                    "<a href='#!' onclick='asignarDescuento(" +
                    id + "," + value.id +
                    ");'>" + value.descuento +
                    "%<span class='badge'>"
                    + value.nombre +
                    "</span></a>" +
                    "</li>");
        });
        $(".dropdown-button").dropdown();
    });
}

function asignarDescuento(id, idesc) {
    debugger;
    var idventa = $('#venta').val();
    if (idventa == '') {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "Esta venta ya fue cobrada o anulada. Cree una nueva",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var route = "/Detalleproforma/" + id + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            iddescuento: idesc
        },
        success: function () {
            swal({title: "Bien!",
                text: "Descuento asignado correctamente",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1500});
            Cargartabla();
        }, error: function () {
            return swal({title: "Error",
                text: "Error al asignar descuento",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1500});
        }
    });
}
/*
 * Agregar un producto mediante el lector de codigo de barra
 * @param {type} $idpro -> ID del producto a vender
 * @param {type} $stockPro -> Stock actual del producto
 * @returns {Boolean}
 */
function agregaraldetallecodigobarra(idpro, stockPro) {
    $('#resultadoprodcuto').empty();
    var idventa = $('#venta').val();
    if (idventa == '') {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "Esta venta ya fue cobrada o anulada. Cree una nueva",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/ValidarventaantiguaProforma/" + idventa;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede agregar productos a una venta Anulada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "Este Venta ya fue cobrada, Porfavor realize una nueva",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        var idAlmacen = $("#almacen").val();
        var route = "/Detalleventa";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                idVenta: idventa,
                idAlmacen: idAlmacen,
                idProducto: idpro,
                cantidad: 1,
                precioventa: "null"
            },
            success: function ($route) {
                $($route).each(function (key, value) {
                    if (value.mensaje == "Producto Agregado con Exito a la Venta") {
                        Cargartabla();
                        swal({title: "Bien!",
                            text: value.mensaje,
                            type: "success",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                    } else {
                        swal({title: "Adverencia!",
                            text: value.mensaje,
                            type: "warning",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 2000});
                    }
                });
            },
            error: function () {
                swal({title: "Error!",
                    text: "Error al Agregar el Producto",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                return location.reload();
            }
        });

    });
}
/*
 * Metodo para agregar un producto al detalle de la venta mediante el  boton
 * @returns {Boolean}
 */
function agregardetalleventa() {
    debugger;

    var idProductoCom = $('#idproducto').val();


    var idventa = $('#venta').val();
    var precioventa = $('#precioP' + idProductoCom).val();
    var idProducto = idProductoCom;
    var cantidad = $('#' + idProducto).val();
    if (idventa == '') {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "Esta venta ya fue cobrada o anulada. Cree una nueva",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/Validarproformantigua/" + idventa;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede agregar productos a una venta Anulada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "Este Venta ya fue cobrada, Porfavor realize una nueva",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        var idAlmacen = $("#almacen").val();
        var route = "/Detalleproforma";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                idVenta: idventa,
                idAlmacen: idAlmacen,
                idProducto: idProducto,
                cantidad: cantidad,
                precioventa: precioventa,
            },
            success: function ($route) {
                $($route).each(function (key, value) {
                    debugger;
                    if (value.mensaje == "Producto Agregado con Exito a la Proforma") {
                        Cargartabla();
                        $('#resultadoprodcuto').empty();
                        $("#nombreproducto").val("");
                        $("#codigoInterno").val("");
                        $("#codigoBarra").val("");
                        swal({title: "Bien!",
                            text: value.mensaje,
                            type: "success",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                    } else {
                        swal({title: "Adverencia!",
                            text: value.mensaje,
                            type: "warning",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 2000});
                    }
                });
            },
            error: function () {
                swal({title: "Error!",
                    text: "Error al Agregar el Producto",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                return location.reload();
            }
        });
    });
}
/*
 * Metodo para generar una nueva venta al presionar un boton
 */
$("#generarventa").click(function () {
    var idpunto = iddelpuntoventa;
    var route = "/GenerarProforma/" + idpunto;
    var iddelaventa = 0;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            iddelaventa = parseInt(value.id);
            window.location.href = "/Proformas/" + iddelaventa;
        });
    });
});
/**
 * Metodo no utilizado
 * @returns {undefined}
 */
function cargarfactura() {
    cargardescuento();              //modificado
    $("#mostrarimporte").text("0 %");       //modificado
    $("#mostrardescuento").text(0);         //modificado
    $("#totaldescuento").val(0);
    var total = $("#total").text();
    totalConDescuento = $("#total").text();
    document.getElementById('totalfijo').innerHTML = total;
    $("#datosfactura tr").remove();


   // var valor = LITERAL(total);
    var tabladatos = $('#datosfactura');
    $("#totalacobrarcondescuentoenlaventa").text(total);
    tabladatos.append("<tr><td id='totalcondescuentoventa'>" + total + "</td><td id='totalventaliteral'>" + valor + "</td><tr>");
    var porcentaje = total * 0.3;
    porcentaje = porcentaje.toFixed(2);
    var saldo = total - porcentaje;
    $("#aCuenta").val(porcentaje);
    $("#saldo").val(saldo.toFixed(2));

}
/*
 * Validar el Estado de la venta
 * @returns {undefined}
 */
function validarestado() {
    var mensaje;
    var idventa = $('#venta').val();
    var route = "/validarestado/" + idventa;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            //estado
            if (value.estado == 1) {
                mensaje = "Cobrado";
            }
            if (value.estado == 0) {
                mensaje = "Guardado";
            }
            if (value.estado == 2) {
                mensaje = "Anulado";
            }
            if (value.estado == 4) {
                mensaje = "Proforma";
            }
            if (value.estado == 5) {
                mensaje = "Credito";
            }
        });
    });
}
/*
 * Guardar la factura de una venta para su posteerior impresion
 */

$("#guardarfactura").click(function () {
    debugger;
    var totalsindesceutos = $("#total").text();
    var fechaEntregaVisal = $("#fechaEntrega").val();
    var cobroAnticipo = $("#cobroAnticipo").val();
//    if (!fechaEntregaVisal || !fechaEntregaVisal.trim().length) {
//        Materialize.toast('FECHA DE ENTREGA OBLIGATORIO', 1000, 'rounded');
//        return;
//    }
var totalcondescuento = $("#totalcondescuentoventa").text();
    if (cobroAnticipo > totalcondescuento) {
        return Materialize.toast('El Anticipo no debe ser mayor al total de la Proforma!!', 2500, 'rounded');
    }

    if (totalsindesceutos == '') {
        return Materialize.toast('No se puede cobrar una venta sin detalle de venta', 2500, 'rounded');
    }
    var idventa = $("#venta").val();
    if (idventa == '') {
        $('#generadordeventa').show();
        return Materialize.toast('No se puede cobrar la misma venta. Cierre la ventana y cree una nueva venta.', 6000, 'rounded');
    }
    var estado;
    var route = "/ValidarventaantiguaProforma/" + idventa;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            return Materialize.toast('No se puede cobrar una venta Anulada', 2500, 'rounded');
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return Materialize.toast('Otra venta se realizo con este numero de vetna. Por favor cree una nueva venta', 2500, 'rounded');
        }
        if (estado == 0 || estado == 4) {
            var nombre = $("#razonsocial").val();
            var nit = parseInt($("#nit").val()) + "";
            var idCliente = $("#idCliente").val();
            if (idCliente == "") {
                idCliente = 1;
            }
            var route = "/buscarcliente/" + nit;
            $.get(route, function (res) {
                if (res == 0) {
                    guardarcliente(nit, nombre);
                }
            });
            var aCuenta = parseFloat($("#aCuenta").val()) + "";
            var saldoACobrar = $("#saldo").val();
            var cuotasSaldo = parseInt($("#nrCuotas").val()) + "";
            var cobrarCada = $("#cobrarCada").val();
            var observaciones = $("#observaciones").val();
            var pagos;
            var cambio;
            var nroCuenta;
            var pago;
            var Efectivo = document.getElementById('test1').checked;
            var Tarjetadebito = document.getElementById('test2').checked;
            var Tarjetacredito = document.getElementById('test3').checked;
            var Depositobanco = document.getElementById('test4').checked;
            var Cheque = document.getElementById('test5').checked;
//            var Tarjetadebito = false;
//            var Tarjetacredito = false;
//            var Depositobanco = false;
//            var Cheque = false;

            var Credito = document.getElementById('test6').checked;
            if (Efectivo == true) {
                pago = "Efectivo";
                pagos = parseInt($("#pago").val());
                cambio = parseInt($("#cambio").val());
            }
            if (Tarjetadebito == true) {
                pago = "Tarjeta de Debito";
                pagos = $("#titular").val();
                cambio = $("#tarjeta").val();
                if (!cambio || !cambio.trim().length) {
                } else {
                    if (valcreditcard(cambio)) {
                    } else {
                        return Materialize.toast("Nro. de Tarjeta Incorrecto. Por Favor ingrese un numero valido", 2500, 'rounded');
                    }
                }
            }
            if (Tarjetacredito == true) {
                pago = "Tarjeta de Credito";
                pagos = $("#titular").val();
                cambio = $("#tarjeta").val();
                if (!cambio || !cambio.trim().length) {
                } else {
                    if (valcreditcard(cambio)) {
                    } else {
                        return Materialize.toast("Nro. de Tarjeta Incorrecto. Por Favor ingrese un numero valido", 2500, 'rounded');
                    }
                }
            }
            if (Depositobanco == true) {
                pago = "Deposito Banco";
                pagos = $("#deposita").val();
                cambio = $("#nrotran").val();
            }
            if (Cheque == true) {
                pago = "Cheque";
                pagos = $("#anombre").val();
                cambio = $("#cheque").val();
            }
            if (Credito == true) {
                pago = "Credito";
                if (cobrarCada == null) {
                    return Materialize.toast("Seleccione el plan de pagos", 2500, 'rounded');
                }
                if (!aCuenta || !aCuenta.trim().length || !saldoACobrar || !saldoACobrar.trim().length || !cuotasSaldo || !cuotasSaldo.trim().length || !cobrarCada || !cobrarCada.trim().length || !observaciones || !observaciones.trim().length) {
                    return Materialize.toast("Ingrese todos los datos del credito", 1000, 'rounded');
                }
                if (parseFloat(saldoACobrar) <= 0) {
                    return Materialize.toast("El saldo del credito no debe ser menor o igual 0", 1000, "rounded");
                }
                if (parseFloat(aCuenta) < 0) {
                    return Materialize.toast("El monto del pago inicial no debe ser menor 0", 1000, "rounded");
                }
                if (parseInt(cuotasSaldo) <= 0) {
                    return Materialize.toast("La cantidad de cuotas debe ser mayo a 0", 1000, "rounded");
                }
                if (idCliente == "" || idCliente == 1) {
                    return Materialize.toast("Es necesario asignar un Concesionario para realizar el credito", 1000, "rounded");
                }
            }

            if (idCliente == "" || idCliente == 1) {
                return Materialize.toast("Es necesario asignar un Concesionario para realizar la Proforma", 1000, "rounded");
            }
            var idpuntoventa = $("#iddelpuntoventa").val();
            var idelemeplaedo = $("#iddelempleado").val();
            var idmesas = $("#mesa").val();
            var totalcondescuento = $("#totalcondescuentoventa").text();
            var totalliteralventa = $("#totalventaliteral").text();

            var idTipoDescuento = $("#descuentos").val();
            var porcentajedescuento;
            var importedescuento = $("#mostrardescuento").text();
            var descuentoPorcentaje = document.getElementById('descuentoP').checked;
            if (descuentoPorcentaje == true) {
                var idTipoDescuento = $("#descuentos").val();
                if (idTipoDescuento == 1) {
                    porcentajedescuento = 0;
                } else {
                    porcentajedescuento = descuent;
                }
            } else {
                porcentajedescuento = $("#mostrarimporte").text();
                parseFloat(porcentajedescuento);
                if (porcentajedescuento < 0) {
                    return Materialize.toast("El descuento Excede lo permitido", 1000, "rounded");
                }
                parseFloat(totalsindesceutos);
                parseFloat(totalcondescuento);
                importedescuento = totalsindesceutos - totalcondescuento;
            }
            if (pago == "Efectivo") {
                parseFloat(pagos);
                parseFloat(totalcondescuento);
                if (pagos < totalcondescuento) {
                    return Materialize.toast('La cantidad a pagar debe ser mayor o igual al monto total de la venta', 2000, 'rounded');
                }
            }

            var confactura = document.getElementById('sifactura').checked;

            if (confactura) {
                if (!nombre || !nombre.trim().length) {
                    Materialize.toast('Campos vacio Nombre', 1000, 'rounded');
                    return false;
                }
                if (!nit || !nit.trim().length) {
                    Materialize.toast('Campos vacios', 1000, 'rounded');
                    return false;
                }
            }
            var conenvio = document.getElementById('test22').checked;
            var FechaEntrega = $("#fechaentrega").val();
            var HoraEntrega = $("#horaentrega").val();
            var idventa = $('#venta').val();
            var PersonaRecibeenvio = $("#personarecibeenvio").val();
            var DireccionEnvio = $("#dirreccionenvio").val();
            var Importetransporte = $("#importetransporte").val();
            var ci = $("#ci").val();
            var celular = $("#celulars").val();
            var departamento = $("#ciudadd").val();
            var estadoenvio;
            if (conenvio == true) {
                estadoenvio = 2;
                if (!PersonaRecibeenvio || !PersonaRecibeenvio.trim().length) {
                    Materialize.toast('Persona Recibe envio Es obligatorio', 1000, 'rounded');
                    return;
                }
                if (!ci || !ci.trim().length) {
                    Materialize.toast('La Cedula de Identidad Es obligatorio', 1000, 'rounded');
                    return;
                } else {
                    ci = parseInt($("#ci").val());
                }
                if (!celular || !celular.trim().length) {
                    Materialize.toast('El telefono Es obligatorio', 1000, 'rounded');
                    return;
                } else {
                    celular = parseInt($("#celulars").val());
                }
                if (!DireccionEnvio || !DireccionEnvio.trim().length) {
                    Materialize.toast('Direccion Envío Es obligatorio', 1000, 'rounded');
                    return;
                }
                if (!Importetransporte || !Importetransporte.trim().length) {
                    Materialize.toast('Importe transporte Es obligatorio', 1000, 'rounded');
                    return;
                }
                if (!FechaEntrega || !FechaEntrega.trim().length) {
                    Materialize.toast('Fecha Entrega Es obligatorio', 1000, 'rounded');
                    return;
                }
                if (!HoraEntrega || !HoraEntrega.trim().length) {
                    Materialize.toast('Hora Entrega Es obligatorio', 1000, 'rounded');
                    return;
                }

            } else {
                PersonaRecibeenvio = "S/R";
                DireccionEnvio = "S/E";
                Importetransporte = "S/T";
                FechaEntrega = "";
                HoraEntrega = "";
                estadoenvio = 1;
                ci = 0;
                celular = 0;
                departamento = 0;
            }
            var garantia = parseInt($("#impgarantia").val());
            var vendedor = $("#vendedor").val();
            debugger;
            var token = $("#token").val();
            var route = "/Proforma";
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': token},
                type: 'POST',
                dataType: 'json',
                data: {
                    idTipoDescuento: idTipoDescuento,
                    porcentajedescuento: porcentajedescuento,
                    importedescuento: importedescuento,
                    pagos: pagos,
                    cambio: cambio,
                    idventa: idventa,
                    idpuntoventa: iddelpuntoventa,
                    total: totalcondescuento,
                    totaltotal: totalsindesceutos,
                    valorliteral: totalliteralventa,
                    nombre: nombre,
                    nit: nit,
                    idelemeplaedo: idelemeplaedo,
                    pago: pago,
                    idmesas: idmesas,
                    aCuenta: aCuenta,
                    saldoACobrar: saldoACobrar,
                    cuotasSaldo: cuotasSaldo,
                    cobrarCada: cobrarCada,
                    idCliente: idCliente,
                    facturacredito: confactura,
                    observaciones: observaciones,
                    garantia: garantia,
                    DireccionEnvio: DireccionEnvio,
                    Importetransporte: Importetransporte,
                    FechaEntrega: FechaEntrega,
                    HoraEntrega: HoraEntrega,
                    estadoenvio: estadoenvio,
                    celular: celular,
                    departamento: departamento,
                    nroCuenta: nroCuenta,
                    PersonaRecibeenvio: PersonaRecibeenvio,
                    fechaEntregaVisal: fechaEntregaVisal,
                    cobroAnticipo: cobroAnticipo
                },
                success: function ($idfactura) {
                    var nFactura;
                    $($idfactura).each(function (key, value) {
                        if (value == "LAS CANTIDADES DE CHASIS NO SON LAS CORRECTAS!") {
                            return Materialize.toast(value, 1000, 'rounded');
                        }
                        if (value == "No se puede generar factura porque no existe libro de órdenes activos") {
                            return Materialize.toast(value, 1000, 'rounded');
                        }
                        if (value == "Fecha limite a Terminado") {
                            return Materialize.toast(value, 1000, 'rounded');
                        }
                        if (value == "limite de numero de factura") {
                            return  Materialize.toast(value, 1000, 'rounded');
                        } else {
                            $(value).each(function (key, values) {
                                validarestado();
                                nFactura = values.id;
                                $("#datos tr").remove();
                                $("#resultadoprodcuto tr").remove();
                                $("#datosfactura tr").remove();
                                $('#razonsocial').val("");
                                $('#cobroAnticipo').val("");
                                $('#nit').val("");
                                $('#importe').val("");
                                $('#totals').val("0");
                                $('#venta').val("");
                                document.getElementById('total').innerHTML = "0";
                                $('#generadordeventa').show();
                                $("#modal1").closeModal();
                                Materialize.toast('Guardado Exitoso', 1000, 'rounded');
                                if (confactura == true) {
                                    window.open("/imprirfactura/" + nFactura);
                                } else {
                                    window.open("/imprimirProforma/" + idventa);
//                                    window.open("/imprimirContrato/" + idventa);
//                                    window.open("/imprimirChasis/" + idventa);
                                }
                            });
                        }
                    });
                }, error: function () {
                    Materialize.toast('No se encontro un libro de orden', 1000, 'rounded');
                }
            });
        }
    });
});

/*
 * Metodo para anular una venta
 */
$("#anularventa").click(function () {
    var idventa = $('#venta').val();
    if (!idventa || !idventa.trim().length) {
        $('#generadordeventa').show();
        return swal({title: "Advertencia",
            text: "No se puede anular una venta ya cobrada o anulada",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 2000});
    }
    var route = "/AnularVenta/" + idventa;
    $.get(route, function (res) {
        if (res.mensaje == "Venta anulada exitosamente") {
            validarestado();
            $("#datos tr").remove();
            $("#datosfactura tr").remove();
            $('#razonsocial').val("");
            $('#nit').val("");
            $('#venta').val("");
            document.getElementById('total').innerHTML = "0";
            $('#generadordeventa').show();
            $("#modal1").closeModal();
            return swal({title: "Bien!",
                text: res.mensaje,
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        } else {
            return swal({title: "Advertencia!",
                text: res.mensaje,
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
    });
});
/*
 * Metodo para generar una proforma
 */
$("#proforma").click(function () {
    var idventa = $('#venta').val();
    var idCliente = $('#idCliente').val();
    var total = $("#total").text();
    if (!idventa || !idventa.trim().length) {
        return swal({title: "Adverencia!",
            text: "Esta venta ya fue cobrada o anulada. Cree una nueva",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (!idCliente || !idCliente.trim().length) {
        return swal({title: "Adverencia!",
            text: "Asigne un Concesionario a la venta",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (total == "0") {
        return swal({title: "Adverencia!",
            text: "No se puede hacer una proforma sin detalle",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
        return false;
    }
    var route = "/proforma/" + idventa + "/" + idCliente + "/" + total;
    $.get(route, function (res) {
        if (res == 1) {
            validarestado();
            $("#datos tr").remove();
            $("#datosfactura tr").remove();
            $('#razonsocial').val("");
            $('#nit').val("");
            $('#venta').val("");
            document.getElementById('total').innerHTML = "0";
            $('#generadordeventa').show();
            $("#modal1").closeModal();
            $('#generadordeventa').show();
            swal({title: "Bien!",
                text: "Proforma realizada exitosamente",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.open("/imprimirproforma/" + idventa);
        }
        if (res == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede hacer una proforma de una venta anulada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (res == 0) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "Esta venta ya fue cobrada. No se puede hacer proforma.",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
/*
 * Eliminar un producto del detalle de la venta
 * @param {type} btn -> BTN con el ID del productoa eliminar
 * @returns {undefined}
 */
function Eliminar(btn, idpro) {
    var idventa = $('#venta').val();
    if (!idventa || !idventa.trim().length) {
        return swal({title: "Adverencia!",
            text: "Esta venta ya fue cobrada o anulada. Cree una nueva",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var route = "/validarestado/" + idventa;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.estado == 1) {
                return swal({title: "Adverencia!",
                    text: "Esta venta ya fue cobrada o anulada. Cree una nueva",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
            if (value.estado == 0 || value.estado == 4) {
                var route = "/Detalleproforma/" + btn + "";
                var token = $("#token").val();
                var idventa = $('#venta').val();
                var idproducto = btn.value;
                $.ajax({
                    url: route,
                    headers: {'X-CSRF-TOKEN': token},
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        idventa: idventa,
                        idproducto: idproducto,
                        idpro: idpro
                    },
                    success: function () {
                        Cargartabla();
                        $("#total").text("0");
                        return swal({title: "Bien!",
                            text: "Producto eliminado de la venta",
                            type: "success",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                    },
                    error: function () {
                        swal({title: "Error",
                            text: "Error al eliminar el producto",
                            type: "error",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                        return location.reload();
                    }
                });
            }
            if (value.estado == 2) {
                return swal({title: "Adverencia!",
                    text: "No se puede eliminar el producto de la venta, porque ya fue anulada.",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    });
}

/*
 * Metodo para cargar los descuentos a un combo
 * @returns {undefined}
 */
function cargardescuento() {
    $('#descuentos')
            .find('option')
            .remove()
            .end();
    $("#descuentos").append('<option value="1" >Seleccione el tipo de Descuento</option>');
    $('#descuentos').material_select();
    var route = "/listardescuentos/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#descuentos').append('<option value=' + value.id + ' >' + value.nombre + '  (' + value.descuento + '%)</option>');
            $('#descuentos').material_select();
        });
    });
}
/*
 * Metodo para aplicar el descuentpo a la venta seleccionando un descuento del 
 * combo
 */
$("select#descuentos").change(function () {
    var idselecionado = $(this).val();
    var route = "/Descuento/" + idselecionado + "/edit";
    var totalcargado = $("#totaldefacturas").val(totalfactura);
    $("#totaldefacturassindescuento").val(totalfactura);
    if (totalfactura == 0) {
        Materialize.toast('No tiene total Asignado', 1000, 'rounded');
    } else {
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#iddescuento").val(value.id);
                var totalfacturas = $("#totaldefacturas").val();
                var descuento = totalfacturas * value.descuento / 100;
                descuentoTotal = descuento;
                document.getElementById('mostrardescuento').innerHTML = descuento.toFixed(2);
                $("#descuentoasignado").val(value.descuento);
                descuent = value.descuento;
                var totalfacturas = totalfacturas - descuento;
                var total = "" + totalfacturas + "";
                totalConDescuento = totalfacturas;
                $("#datosfactura tr").remove();
                var valor = LITERAL(total);
                var tabladatos = $('#datosfactura');
                $("#totalacobrarcondescuentoenlaventa").text(totalfacturas);
                tabladatos.append("<tr><td id='totalcondescuentoventa'>" + totalfacturas + "</td><td id='totalventaliteral'>" + valor + "</td><tr>");
                $("#totaldefacturas").val(totalfacturas);
                var porcentaje = totalfacturas * 0.3;
                porcentaje = porcentaje.toFixed(2);
                var saldo = totalfacturas - porcentaje;
                $("#aCuenta").val(porcentaje);
                $("#saldo").val(saldo.toFixed(2));
            });
        });
    }
});

/*******************************************************************************
 ************************************* ENVIOS***********************************
 * *****************************************************************************
 */
function mostrardatosdeenvio() {
    $('#informaciondelenvio').show();
}

function ocultardatosdeenvio() {
    $('#informaciondelenvio').hide();
}

var contardirecciones = 0;

$('#nuevadireccion').click(function () {
    contardirecciones++;
    if (contardirecciones == 1) {
        $('#direcion2').show();
        contardirecciones = 1;
        return;
    } else {
    }
    if (contardirecciones == 2) {
        $('#direcion3').show();
        contardirecciones = 0;
        return;
    } else {
        return;
    }
});

var quitarcontador = 1;

$('#quitardiceccion').click(function () {
    quitarcontador++;
    if (quitarcontador == 1) {
        $('#direcion2').hide();
        quitarcontador = 1;
        return;
    } else {
    }
    if (quitarcontador == 2) {
        $('#direcion3').hide();
        quitarcontador = 0;
        return;
    } else {
        return;
    }
});

$("#programarentrega").click(function () {
    var FechaEntrega = $("#fechaentrega").val();
    var HoraEntrega = $("#horaentrega").val();
    var idventa = $('#venta').val();
    var estado = 3;
//*****esto ya valida
    var EnvioaDomicilio;
    var PersonaRecibeenvio = $("#personarecibeenvio").val();
    var DireccionEnvio = $("#dirreccionenvio").val();
    var Cobroalentregar;
    var Importetransporte = $("#importetransporte").val();
    var sienviodomicilio = document.getElementById('sienviodomiciolio').checked;
    var noenviodomicilio = document.getElementById('noenviodomiciolio').checked;
    var sicobro = document.getElementById('sicobro').checked;
    var nocobro = document.getElementById('nocobro').checked;
    if (sicobro == true) {
        Cobroalentregar = 1;
    } else {
        Cobroalentregar = 0;
    }
    if (nocobro == true) {
        Cobroalentregar = 0;
    }
    if (sienviodomicilio == true) {
        if (!PersonaRecibeenvio || !PersonaRecibeenvio.trim().length) {
            Materialize.toast('Persona Recibe envio Es obligatorio', 1000, 'rounded');
            return;
        } else {
        }
        if (!DireccionEnvio || !DireccionEnvio.trim().length) {
            Materialize.toast('Direccion Envío Es obligatorio', 1000, 'rounded');
            return;
        } else {
        }
        if (!Importetransporte || !Importetransporte.trim().length) {
            Materialize.toast('Importe transporte Es obligatorio', 1000, 'rounded');
            return;
        } else {
        }
        EnvioaDomicilio = 1;
    } else {
        EnvioaDomicilio = 0;
        PersonaRecibeenvio = "S/R";
        DireccionEnvio = "S/E";
        Importetransporte = "S/T";
    }
    if (noenviodomicilio == true) {
        EnvioaDomicilio = 0;
    }
    if (!FechaEntrega || !FechaEntrega.trim().length) {
        Materialize.toast('Fecha Entrega Es obligatorio', 1000, 'rounded');
        return;
    } else {
    }
    if (!HoraEntrega || !HoraEntrega.trim().length) {
        Materialize.toast('Hora Entrega Es obligatorio', 1000, 'rounded');
        return;
    } else {
    }
    //idventa
    var route = "/conenvioventa";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            idventa: idventa,
            estado: estado,
            FechaEntrega: FechaEntrega,
            HoraEntrega: HoraEntrega,
            EnvioaDomicilio: EnvioaDomicilio,
            PersonaRecibeenvio: PersonaRecibeenvio,
            DireccionEnvio: DireccionEnvio,
            Cobroalentregar: Cobroalentregar,
            Importetransporte: Importetransporte
        },
        success: function () {
            Materialize.toast('Envio Programadao !! ', 2000, 'rounded');
            validarestado();
        }, error: function () {
            Materialize.toast('Error!! ', 2000, 'rounded');
        }
    });
});


/*******************************************************************************
 * *****************************************************************************
 * *********************************LITERAL*************************************
 * *****************************************************************************
 * *****************************************************************************
 */
// Función modulo, regresa el residuo de una división 
function mod(dividendo, divisor) {
    resDiv = dividendo / divisor;
    parteEnt = Math.floor(resDiv);            // Obtiene la parte Entera de resDiv 
    parteFrac = resDiv - parteEnt;      // Obtiene la parte Fraccionaria de la división
    //modulo = parteFrac * divisor;  // Regresa la parte fraccionaria * la división (modulo) 
    modulo = Math.round(parteFrac * divisor)
    return modulo;
} // Fin de función mod

// Función ObtenerParteEntDiv, regresa la parte entera de una división
function ObtenerParteEntDiv(dividendo, divisor) {
    resDiv = dividendo / divisor;
    parteEntDiv = Math.floor(resDiv);
    return parteEntDiv;
} // Fin de función ObtenerParteEntDiv

// function fraction_part, regresa la parte Fraccionaria de una cantidad
function fraction_part(dividendo, divisor) {
    resDiv = dividendo / divisor;
    f_part = Math.floor(resDiv);
    return f_part;
} // Fin de función fraction_part

// function string_literal conversion is the core of this program 
// converts numbers to spanish strings, handling the general special 
// cases in spanish language. 
function string_literal_conversion(number) {
    // first, divide your number in hundreds, tens and units, cascadig 
    // trough subsequent divisions, using the modulus of each division 
    // for the next. 
    centenas = ObtenerParteEntDiv(number, 100);
    number = mod(number, 100);
    decenas = ObtenerParteEntDiv(number, 10);
    number = mod(number, 10);
    unidades = ObtenerParteEntDiv(number, 1);
    number = mod(number, 1);
    string_hundreds = "";
    string_tens = "";
    string_units = "";
    // cascade trough hundreds. This will convert the hundreds part to 
    // their corresponding string in spanish.
    if (centenas == 1) {
        string_hundreds = "ciento ";
    }
    if (centenas == 2) {
        string_hundreds = "doscientos ";
    }
    if (centenas == 3) {
        string_hundreds = "trescientos ";
    }
    if (centenas == 4) {
        string_hundreds = "cuatrocientos ";
    }
    if (centenas == 5) {
        string_hundreds = "quinientos ";
    }

    if (centenas == 6) {
        string_hundreds = "seiscientos ";
    }

    if (centenas == 7) {
        string_hundreds = "setecientos ";
    }

    if (centenas == 8) {
        string_hundreds = "ochocientos ";
    }

    if (centenas == 9) {
        string_hundreds = "novecientos ";
    }

    // end switch hundreds 

    // casgade trough tens. This will convert the tens part to corresponding 
    // strings in spanish. Note, however that the strings between 11 and 19 
    // are all special cases. Also 21-29 is a special case in spanish. 
    if (decenas == 1) {
        //Special case, depends on units for each conversion
        if (unidades == 1) {
            string_tens = "once";
        }

        if (unidades == 2) {
            string_tens = "doce";
        }

        if (unidades == 3) {
            string_tens = "trece";
        }

        if (unidades == 4) {
            string_tens = "catorce";
        }

        if (unidades == 5) {
            string_tens = "quince";
        }

        if (unidades == 6) {
            string_tens = "dieciseis";
        }

        if (unidades == 7) {
            string_tens = "diecisiete";
        }

        if (unidades == 8) {
            string_tens = "dieciocho";
        }

        if (unidades == 9) {
            string_tens = "diecinueve";
        }
    }
    //alert("STRING_TENS ="+string_tens);

    if (decenas == 2) {
        string_tens = "veinti";

    }
    if (decenas == 3) {
        string_tens = "treinta";
    }
    if (decenas == 4) {
        string_tens = "cuarenta";
    }
    if (decenas == 5) {
        string_tens = "cincuenta";
    }
    if (decenas == 6) {
        string_tens = "sesenta";
    }
    if (decenas == 7) {
        string_tens = "setenta";
    }
    if (decenas == 8) {
        string_tens = "ochenta";
    }
    if (decenas == 9) {
        string_tens = "noventa";
    }

    // Fin de swicth decenas


    // cascades trough units, This will convert the units part to corresponding 
    // strings in spanish. Note however that a check is being made to see wether 
    // the special cases 11-19 were used. In that case, the whole conversion of 
    // individual units is ignored since it was already made in the tens cascade. 

    if (decenas == 1)
    {
        string_units = "";  // empties the units check, since it has alredy been handled on the tens switch 
    } else
    {
        if (unidades == 1) {
            string_units = "un";
        }
        if (unidades == 2) {
            string_units = "dos";
        }
        if (unidades == 3) {
            string_units = "tres";
        }
        if (unidades == 4) {
            string_units = "cuatro";
        }
        if (unidades == 5) {
            string_units = "cinco";
        }
        if (unidades == 6) {
            string_units = "seis";
        }
        if (unidades == 7) {
            string_units = "siete";
        }
        if (unidades == 8) {
            string_units = "ocho";
        }
        if (unidades == 9) {
            string_units = "nueve";
        }
        // end switch units 
    } // end if-then-else 


//final special cases. This conditions will handle the special cases which 
//are not as general as the ones in the cascades. Basically four: 

// when you've got 100, you dont' say 'ciento' you say 'cien' 
// 'ciento' is used only for [101 >= number > 199] 
    if (centenas == 1 && decenas == 0 && unidades == 0) {
        string_hundreds = "cien ";
    }

// when you've got 10, you don't say any of the 11-19 special 
// cases.. just say 'diez' 
    if (decenas == 1 && unidades == 0) {
        string_tens = "diez ";
    }

// when you've got 20, you don't say 'veinti', which is used 
// only for [21 >= number > 29] 
    if (decenas == 2 && unidades == 0)
    {
        string_tens = "veinte ";
    }

// for numbers >= 30, you don't use a single word such as veintiuno 
// (twenty one), you must add 'y' (and), and use two words. v.gr 31 
// 'treinta y uno' (thirty and one) 
    if (decenas >= 3 && unidades >= 1) {
        string_tens = string_tens + " y ";
    }

// this line gathers all the hundreds, tens and units into the final string 
// and returns it as the function value.
    final_string = string_hundreds + string_tens + string_units;


    return final_string;

} //end of function string_literal_conversion()================================ 

// handle some external special cases. Specially the millions, thousands 
// and hundreds descriptors. Since the same rules apply to all number triads 
// descriptions are handled outside the string conversion function, so it can 
// be re used for each triad. 

function LITERAL(number) {
    //number = number_format (number, 2);
    number1 = number;
    //settype (number, "integer");
    cent = number1.split(".");
    centavos = cent[1];
    //Mind Mod
    number = cent[0];
    if (centavos == 0 || centavos == undefined) {
        centavos = "00";
    }

    if (number == 0 || number == "")
    { // if amount = 0, then forget all about conversions, 
        centenas_final_string = " cero "; // amount is zero (cero). handle it externally, to 
        // function breakdown 
    } else
    {

        millions = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
        number = mod(number, 1000000);           // conversion function 

        if (millions != 0)
        {
            // This condition handles the plural case 
            if (millions == 1)
            {              // if only 1, use 'millon' (million). if 
                descriptor = " millon ";  // > than 1, use 'millones' (millions) as 
            } else
            {                           // a descriptor for this triad. 
                descriptor = " millones ";
            }
        } else
        {
            descriptor = " ";                 // if 0 million then use no descriptor. 
        }
        millions_final_string = string_literal_conversion(millions) + descriptor;


        thousands = ObtenerParteEntDiv(number, 1000);  // now, send the thousands to the string 
        number = mod(number, 1000);            // conversion function. 
        //print "Th:".thousands;
        if (thousands != 1)
        {                   // This condition eliminates the descriptor 
            thousands_final_string = string_literal_conversion(thousands) + " mil ";
            //  descriptor = " mil ";          // if there are no thousands on the amount 
        }
        if (thousands == 1)
        {
            thousands_final_string = " mil ";
        }
        if (thousands < 1)
        {
            thousands_final_string = " ";
        }

        // this will handle numbers between 1 and 999 which 
        // need no descriptor whatsoever. 

        centenas = number;
        centenas_final_string = string_literal_conversion(centenas);

    } //end if (number ==0) 

    /*if (ereg("un",centenas_final_string))
     {
     centenas_final_string = ereg_replace("","o",centenas_final_string); 
     }*/
    //finally, print the output. 

    /* Concatena los millones, miles y cientos*/
    cad = millions_final_string + thousands_final_string + centenas_final_string;

    /* Convierte la cadena a Mayúsculas*/
    cad = cad.toUpperCase();

    if (centavos.length > 2)
    {

        if (centavos.substring(2, 3) >= 5) {
            centavos = centavos.substring(0, 1) + (parseInt(centavos.substring(1, 2)) + 1).toString();
        } else {

            centavos = centavos.substring(0, 1);
        }
    }

    /* Concatena a los centavos la cadena "/100" */
    if (centavos.length == 1)
    {
        centavos = centavos + "0";
    }
    centavos = centavos + "/100";


    /* Asigna el tipo de moneda, para 1 = PESO, para distinto de 1 = PESOS*/
    if (number == 1)
    {
        moneda = " DOLARES ";
    } else
    {
        moneda = " DOLARES ";
    }
    /* Regresa el número en cadena entre paréntesis y con tipo de moneda y la fase M.N.*/
    //Mind Mod, si se deja MIL pesos y se utiliza esta función para imprimir documentos
    //de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
    if (cad == 'MIL ') {
        cad = 'UN MIL ';
    }
    return cad + moneda + centavos + " $us.";
}

function valEmail(valor) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(valor);
}


// takes the form field value and returns true on valid number
function valcreditcard(value) {
    // accept only digits, dashes or spaces
    if (/[^0-9-\s]+/.test(value))
        return false;

    // The Luhn Algorithm. It's so pretty.
    var nCheck = 0, nDigit = 0, bEven = false;
    value = value.replace(/\D/g, "");

    for (var n = value.length - 1; n >= 0; n--) {
        var cDigit = value.charAt(n),
                nDigit = parseInt(cDigit, 10);

        if (bEven) {
            if ((nDigit *= 2) > 9)
                nDigit -= 9;
        }

        nCheck += nDigit;
        bEven = !bEven;
    }

    return (nCheck % 10) == 0;
}




/*******************************************************************************
 **** Muestra el total con descuento editable***********************************
 * *****************************************************************************
 */

function calcular_total_Modificado() {
    var totalfactura = 0;
    var importe_porcentaje = 0;
    var totaltraido = $("#totalfijo").text();
    if (totaltraido == "0") {
        return false;
    }
    var entradavalor = $('#totalcondescuento').val();
    if (entradavalor == "") {
        return false;
    }
    totaltraido = parseFloat(totaltraido);
    entradavalor = parseFloat(entradavalor);
    debugger;
    if (entradavalor > totaltraido) {
        $("#mostrarimporte").text("0 %");
        $("#datosfactura tr").remove();
        var total1 = $("#totalfijo").text();
        var valor1 = LITERAL(total1);
        var tabladatos = $('#datosfactura');
        $("#totalacobrarcondescuentoenlaventa").text(total1);
        tabladatos.append("<tr><td id='totalcondescuentoventa'>" + total1 + "</td><td id='totalventaliteral'>" + valor1 + "</td><tr>");
        return false;
    }
    if (totaltraido === '') {
        totaltraido = totalfactura;
    }
    var numero = parseInt(totaltraido);
    importe_porcentaje = (entradavalor * 100).toFixed(2);
    var porcentajemitad = importe_porcentaje / numero;
    var porcentajereal = (100 - porcentajemitad).toFixed(1);

    if (entradavalor == "0" || entradavalor == "") {
        $("#mostrarimporte").text("0 %");

        var idselecionado = $("#descuentos").val();
        var route = "/Descuento/" + idselecionado + "/edit";
        var totalcargado = $("#totaldefacturas").val(totalfactura);
        $("#totaldefacturassindescuento").val(totalfactura);

        $("#datosfactura tr").remove();
        var total1 = $("#totalfijo").text();
        var valor1 = LITERAL(total1);
        var tabladatos = $('#datosfactura');
        $("#totalacobrarcondescuentoenlaventa").text(total1);
        tabladatos.append("<tr><td id='totalcondescuentoventa'>" + total1 + "</td><td id='totalventaliteral'>" + valor1 + "</td><tr>");
        var total1 = parseFloat(total1);
        var porcentaje = total1 * 0.3;
        porcentaje = porcentaje.toFixed(2);
        var saldo = total1 - porcentaje;
        $("#aCuenta").val(porcentaje);
        $("#saldo").val(saldo.toFixed(2));

    } else {
        $("#mostrarimporte").text(porcentajereal + " %");
        $("#datosfactura tr").remove();
        var total1 = "" + entradavalor + "";
        var valor1 = LITERAL(total1);
        var tabladatos = $('#datosfactura');
        $("#totalacobrarcondescuentoenlaventa").text(entradavalor);
        tabladatos.append("<tr><td id='totalcondescuentoventa'>" + entradavalor + "</td><td id='totalventaliteral'>" + valor1 + "</td><tr>");
        var total1 = parseFloat(entradavalor);
        var porcentaje = total1 * 0.3;
        porcentaje = porcentaje.toFixed(2);
        var saldo = total1 - porcentaje;
        $("#aCuenta").val(porcentaje);
        $("#saldo").val(saldo.toFixed(2));
    }
}


function cargarMoneda() {
    var idmoneda = $("#idmoneda").val();
    var route = "/listarmoneda/" + idmoneda;
    $.get(route, function (res) {
        bolivianos1 = parseFloat(res[0].bs);
    });
}

function inputcargar(btn) {
    var route = "/Cliente/" + btn + "/edit";
    $.get(route, function (res) {
        $("#id").val(res.id);
        $("#nombres").val(res.nombre);
        $("#dirreccionenvio").val(res.direccion);
        $("#telefonoFijos").val(res.telefonoFijo);
        $("#correos").val(res.correo);
        $("#celulars").val(res.celular);
        $("#razonSocials").val(res.razonSocial);
//        $("#NITs").val(res.NIT);
//        $('#tipoclientes option:selected').val(res.idTipoCliente);
//        $('#tipoclientes').material_select();
        $('#ci').val(res.NIT);
//        descuentoProbando = res.idDescuento;
    });
}



function cargaralmacendesucursal() {
    var idpunto = $("#iddelpuntoventa").val();
    $('#almacencombo')
            .find('option')
            .remove()
            .end();
    $('#almacencombo').material_select();
    var route = "/listaralmacensucursal/" + idpunto;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacencombo').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacencombo').material_select();
        });
        $("#almacencombo").val($("#almacen").val());
        $("#almacn option:selec").val($("#almacen").val());
        $('#almacencombo').material_select();
    });
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function run(field) {
    setTimeout(function () {
        var regex = /\d*\.?\d?/g;
        field.value = regex.exec(field.value);
    }, 1);
}




