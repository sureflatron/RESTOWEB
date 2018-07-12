var iddelpuntoventa;
var idempleado;
var importe = 0;
var idscuotas = [];
var saldosCuotas = [];
var pagandos = [];
var apagar;
var idRecibo;
var totalliteral;
var total;
var idventa;
var idcuotaa;
var cuots;
var cancell = 0;

$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    Cargar();

});

/*
 * Metodo para listar todas las ventas a credito
 * @returns {undefined}
 */
function Cargar() {
    $('#datos').empty();
    var route = "/listarventascredito";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {},
        success: function ($route) {
            var tabladatos = $('#datos');
            tabladatos.empty();
            $($route).each(function (key, value) {
                if (value.cancelado == 1) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.nombrecli + "</td>" +
                            "<td>" + value.razon + "</td>" +
                            "<td>" + value.totalneto + "</td>" +
                            "<td>" + value.cobrado + "</td>" +
                            "<td><div class='chip red accent-4 white-text'><img src='images/login-logo.png' alt='Cancelado'>Cancelado</div></td>" +
//                            "<td>" + value.cobrarCada + "</td>" +
                            "<td>" + value.observaciones + "</td>" +
                            "<td>" +
                            "<button value=" + value.id + " OnClick='detallesCredito(" + value.id + "," + -99999 + ");' class='btn waves-effect btn-floating'  href='#' title='Cobrar' >" +
                            "<i class='material-icons'>credit_card</i>" +
                            "</button>" +
                            "</td><td>" +
                            "</td><td>" +
//                            "<td></td>" +
//                            "<td></td>" +
                            "</tr>");
                } else if (value.saldo == 0 && value.razon == "") {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.nombrecli + "</td>" +
                            "<td></td>" +
                            "<td>" + value.totalneto + "</td>" +
                            "<td>" + value.cobrado + "</td>" +
                            "<td>" + value.saldo + "</td>" +
//                            "<td>" + value.cobrarCada + "</td>" +
                            "<td>" + value.observaciones + "</td>" +
                            "<td>" +
                            "<button value=" + value.id + " OnClick='detallesCredito(" + value.id + "," + value.saldo + ");' class='btn waves-effect btn-floating'  href='#' title='Cobrar' >" +
                            "<i class='material-icons'>credit_card</i>" +
                            "</button>" +
                            "</td><td>" +
                            "<button href='#modal2' value=" + value.id + " OnClick='openmodal(" + value.id + ", " + value.totalneto + ");' class='btn waves-effect btn-floating' title='IMPRIMIR FACTURA' >" +
                            "<i class='mdi-action-print'></i>" +
                            "</button>" +
                            "</td><td>" +
                            "<button href='#' value=" + value.id + " OnClick='productosVendidos(" + value.id + ");' class='btn waves-effect btn-floating'  href='#' title='PRODUCTOS' >" +
                            "<i class='mdi-action-wallet-travel'></i>" +
                            "</button>" +
                            "</td>" +
//                            "<td>" +
//                            "</td>" +
//                            "<td></td>" +
                            "</tr>");
                } else if (value.razon == "") {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.nombrecli + "</td>" +
                            "<td></td>" +
                            "<td>" + value.totalneto + "</td>" +
                            "<td>" + value.cobrado + "</td>" +
                            "<td>" + value.saldo + "</td>" +
//                            "<td>" + value.cobrarCada + "</td>" +
                            "<td>" + value.observaciones + "</td>" +
                            "<td>" +
                            "<button value=" + value.id + " OnClick='detallesCredito(" + value.id + "," + value.saldo + ");' class='btn waves-effect btn-floating'  href='#' title='Cobrar' >" +
                            "<i class='material-icons'>credit_card</i>" +
                            "</button>" +
                            "</td><td>" +
                            "</td><td>" +
                            "</td>" +
//                            "<td>" +
//                            "<button href='#' value=" + value.id + " OnClick='editarfecha(" + value.id + ");' class='btn waves-effect btn-floating' title='EDITAR' >" +
//                            "<i class='mdi-editor-mode-edit'></i>" +
//                            "</button>" +
//                            "</td>" +
//                            "<td>" +
//                            "<button href='#' value=" + value.id + " OnClick='cancelarcredito(" + value.id + ");' class='btn waves-effect btn-floating' title='Cancelar' >" +
//                            "<i class='mdi-navigation-cancel'></i>" +
//                            "</button>" +
//                            "</td>" +
                            "</tr>");
                } else if (value.saldo == 0) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.nombrecli + "</td>" +
                            "<td>" + value.razon + "</td>" +
                            "<td>" + value.totalneto + "</td>" +
                            "<td>" + value.cobrado + "</td>" +
                            "<td>" + value.saldo + "</td>" +
//                            "<td>" + value.cobrarCada + "</td>" +
                            "<td>" + value.observaciones + "</td>" +
                            "<td>" +
                            "<button value=" + value.id + " OnClick='detallesCredito(" + value.id + "," + value.saldo + ");' class='btn waves-effect btn-floating'  href='#' title='Cobrar' >" +
                            "<i class='material-icons'>credit_card</i>" +
                            "</button>" +
                            "</td><td>" +
                            "</td><td>" +
                            "<button href='#' value=" + value.id + " OnClick='productosVendidos(" + value.id + ");' class='btn waves-effect btn-floating'  href='#' title='PRODUCTOS' >" +
                            "<i class='mdi-action-wallet-travel'></i>" +
                            "</button>" +
                            "</td>" +
//                            "</td><td>" +
//                            "<td></td>" +
                            "</tr>");
                } else {
                    tabladatos.append("<tr>" +
                            "<td>" + value.id + "</td>" +
                            "<td>" + value.fecha + "</td>" +
                            "<td>" + value.hora + "</td>" +
                            "<td>" + value.nombrecli + "</td>" +
                            "<td>" + value.razon + "</td>" +
                            "<td>" + value.totalneto + "</td>" +
                            "<td>" + value.cobrado + "</td>" +
                            "<td>" + value.saldo + "</td>" +
//                            "<td>" + value.cobrarCada + "</td>" +
                            "<td>" + value.observaciones + "</td>" +
                            "<td>" +
                            "<button value=" + value.id + " OnClick='detallesCredito(" + value.id + "," + value.saldo + ");' class='btn waves-effect btn-floating'  href='#' title='Cobrar' >" +
                            "<i class='material-icons'>credit_card</i>" +
                            "</button>" +
                            "</td><td>" +
                            "</td>" +
//                            "<td>" +
//                            "<button href='#' value=" + value.id + " OnClick='editarfecha(" + value.id + ");' class='btn waves-effect btn-floating'  href='#' title='EDITAR' >" +
//                            "<i class='mdi-editor-mode-edit'></i>" +
//                            "</button>" +
//                            "</td>" +
                            "<td>" +
//                            "<button href='#' value=" + value.id + " OnClick='cancelarcredito(" + value.id + ");' class='btn waves-effect btn-floating' title='Cancelar' >" +
//                            "<i class='mdi-navigation-cancel'></i>" +
//                            "</button>" +
                            "</td>" +
                            "</tr>");
                }
            });
            paginador();
            idscuotas = [];
            saldosCuotas = [];
            apagar = 0;
        },
        error: function () {
            swal({title: "ERROR AL CARGAR LOS CREDITOS",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}

/*
 * Paginador de la tabla de lista de creditos
 * @returns {undefined}
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

/*
 * Metodo para mostrar las cuotas de un credito
 * Se lo usa en el boton
 * @param {type} id -> ID de la venta a credito
 * @returns {undefined}
 */
function detallesCredito(id, saldo) {
    if ($("#perfilpuedeGuardar").val() == 1) {
        $("#creditlist").slideUp(10, function () {
            Cargarcuotas(id, saldo);
            $("#credidetails").slideDown(10);
            $("#cred").text(id);
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1500});
    }
}

/*
 * Metodo para mostrar la lista de cuotas a pagar de un credito
 * @param {type} id -> ID de la venta
 * @returns {undefined}
 */
function Cargarcuotas(id, saldo) {
    $('#data').empty();
    $("#saldo").val(saldo);
    $("#cambio").val(0);
//    var saldoc = saldo;
//    var sald = 0;
    if (saldo <= 0) {
        $("#procesodecobro").hide();
        $("#listadodecuotasacobrar").removeClass("m7");
        $("#listadodecuotasacobrar").removeClass("l7");
    } else {
        $("#procesodecobro").show();
        $("#listadodecuotasacobrar").addClass("m7");
        $("#listadodecuotasacobrar").addClass("l7");
    }
    idcuotaa = id;
    var route = "/cuotascredito/" + id;
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        success: function ($route) {
            var tabladatos = $('#data');
//            var cont = 0;
//            var bool = true;
            $($route).each(function (key, value) {
                $('#tablacuotas').DataTable().destroy();
//                var botn = "<a class='btn btn-danger btn-floating abrir' title='Cobrar' href='javascript:Mostrar(" + value.id + ")'>" +
//                        "<i class='mdi-action-payment'></i>";
//                if (saldoc == -99999) {
//                    bool = false;
//                    cancell = 1;
//                } else {
//                    cancell = 0;
//                }
//                if (value.saldo !== 0 && !bool) {
//                    botn = "";
//                }
                var saldo = value.saldo;
                saldo = parseFloat(saldo).toFixed(2);
                if (value.saldo == 0) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.fechaVencimiento + "</td>" +
                            "<td><span id='imporcuo" + value.id + "'>" + value.importe + "</span></td>" +
                            "<td>" + value.cobrado + "</td>" +
                            "<td><span id='saldocuo" + value.id + "'>" + saldo + "</span></td>" +
                            "<td><div class='chip'>COBRADO</div></td>" +
//                            "<td>" +
//                            "<a class='btn btn-danger btn-floating abrir' title='Cobrar' href='javascript:Mostrar(" + value.id + ")'>" +
//                            "<i class='mdi-editor-insert-drive-file'></i>" +
//                            "</a>" +
//                            "</td>" +
                            "<td>" +
                            "<a class='btn btn-danger btn-floating' title='Historico' href='javascript:Historico(" + value.id + ")'>" +
                            "<i class='mdi-editor-insert-drive-file'></i>" +
                            "</a>" +
                            "</td>" +
                            "</tr>");
                } else {
                    tabladatos.append("<tr>" +
                            "<td>" + value.fechaVencimiento + "</td>" +
                            "<td><span id='imporcuo" + value.id + "'>" + value.importe + "</span></td>" +
                            "<td>" + value.cobrado + "</td>" +
                            "<td><span id='saldocuo" + value.id + "'>" + saldo + "</span></td>" +
                            "<td><input disabled  type='number' name='' value ='0' id='cuota" + value.id + "' min='0' max='" + value.saldo + "' style='color: black !important; text-align: center;'></td>" +
//                            "<td>" +
//                            botn +
//                            "</a>" +
//                            "</td>" +
                            "<td>" + "<a class='btn btn-danger btn-floating' title='Historico' href='javascript:Historico(" + value.id + ")'>" +
                            "<i class='mdi-editor-insert-drive-file'></i>"
                            +
                            "</a>" +
                            "</td>" +
                            "</tr>");
//                    bool = false;
                    idscuotas.push(value.id);
                    saldosCuotas.push(value.saldo);
                }
//                cont++;
//                $("#saldo").val(value.saldo);
            });
//            $("#save").attr('style', 'display: -webkit-inline-box;');
//            $("#monto").attr('style', 'display: -webkit-inline-box;');
//            $("#monto1").attr('style', 'display: -webkit-inline-box;');
//            paginadorCuotas();
//            $(".modal-trigger").leanModal();
//            $("#tablacuotas_length").attr("style", "display:none;");
//            $("#tablacuotas_filter").attr("style", "display:none;");
        },
        error: function () {
            swal({title: "Error al cargar las cuotas del credito :(",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1500});
        }
    });
}

/*
 * Metodo para dividir el monto a pagar por las cuotas disponibles en la lista de 
 * cuotas del credito
 * @returns {undefined}
 */
function calcularPago() {
    pagandos = [];
    // monto ingresado en el campo de texto
    var monto = $("#monto").val();
    // saldo por cobrar del credito
    var saldo = $("#saldo").val();
    // consultar si el valor en el input(monto) esta vacio le asigna 0 por defecto
    if (!monto || !monto.trim().length) {
        monto = 0;
    }
    //concertir a variables numericas el monto y el saldo
    monto = parseFloat(monto);
    saldo = parseFloat(saldo);
    //calcula el cambio restando el saldo del monto a cobrar
    var cambio = saldo - monto;
    //en el caso de que el monto a pagar por el cliente sea menor al saldo, el cambio es 0
    if (monto < saldo) {
        cambio = 0;
    }
    //asigna el valor del cambio al input aplicando valor absoluto para que no mueste cantidades negativas
    $("#cambio").val(Math.abs(cambio.toFixed(2)));
    //recorre un arreglo con los ids de las cuotas del credito
    for (var a = 0; a < idscuotas.length; a++) {
        //obtiene el id de la cuota
        var id = idscuotas[a];
        //obtiene el saldo de la cuota
        var sald = parseFloat(saldosCuotas[a]);
        //consulta si el monto en mayor a 0 para poder asignar el monto a cancelar de cada cuota
        if (monto > 0) {
            //si el monto es igual al importe de la primera cutoa coloca en importe en la primera cuota y el resto lo deja en 0
            if (monto == sald) {
                pagandos.push(monto);
                $("#cuota" + id).val(monto.toFixed(2));
//                apagar = 0;
                return false;
            } else if (monto > sald) {
                //si el monto es mayor al saldo, asigna el importe de la cuota a la primera cuota.
                pagandos.push(sald);
                $("#cuota" + id).val(sald.toFixed(2));
                //luego resta el importe de la cuota al monto a cancelar.
                monto = monto - sald;
//                apagar = monto;
                //si el monto llega ser una cantidad negativa, automaticamente se le asigna 0
                if (monto <= 0) {
                    monto = 0;
                }
            } else if (monto > 0) {
                //en el caso de que el monto sea menor al saldo de la cuota y mayor a 0 asigan el saldo restante a la ultima cuota del credito
                $("#cuota" + id).val(monto.toFixed(2));
                pagandos.push(monto);
                //luego resta el importe de la cuota al monto a cancelar.
                monto = monto - sald;
//                apagar = monto;
                //si el monto llega ser una cantidad negativa, automaticamente se le asigna 0
                if (monto <= 0) {
                    monto = 0;
                }
            } else if (monto <= 0) {
                //en el caso de que el monto sea menor a 0 se coloca 0 en los importes de las cuotas
                pagandos.push(0);
                $("#cuota" + id).val(0);
//                apagar = 0;
            }
        } else if (monto <= 0) {
            //en el caso de que el monto sea menor a 0 todos los importes de las cuotas se colocan en 0
            pagandos.push(0);
            $("#cuota" + id).val(0);
        }
    }
}

function guardarcobro() {
    // monto ingresado en el campo de texto
    var monto = $("#monto").val();
    if (!monto || !monto.trim().length) {
        return swal({title: "Advetencia!",
            text: "Debe Ingresar al Monto a Cobrar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1500});
    }
    if (monto == 0) {
        return swal({title: "Advetencia!",
            text: "El mono a cobrar debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1500});
    }
    var cambio = $("#cambio").val();
    monto = (parseFloat(monto) - parseFloat(cambio));
    var formaPago;
    var Efectivo = document.getElementById('test1').checked;
    var Tarjetadebito = document.getElementById('test2').checked;
    var Tarjetacredito = document.getElementById('test3').checked;
    var Depositobanco = document.getElementById('test4').checked;
    var Cheque = document.getElementById('test5').checked;
    if (Efectivo) {
        formaPago = "Efectivo";
    }
    if (Tarjetadebito) {
        formaPago = "Tarjeta de Debito";
    }
    if (Tarjetacredito) {
        formaPago = "Tarjeta de Credito";
    }
    if (Depositobanco) {
        formaPago = "Deposito Banco";
    }
    if (Cheque) {
        formaPago = "Cheque";
    }
    for (var a = 0; a < idscuotas.length; a++) {
        var idcuota = idscuotas[a];
        var pagando = pagandos[a];
        if (parseFloat(pagando) > 0) {
            var route = "/Creditos";
            var token = $("#token").val();
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': token},
                type: 'POST',
                dataType: 'json',
                data: {
                    importe: pagando,
                    idPuntoVenta: iddelpuntoventa,
                    idCuentaCobrar: idcuota,
                    formaPago: formaPago,
                    pago: monto
                },
                success: function (data) {
                    $(data).each(function (key, values) {
                        $(values).each(function (key, value) {
                            swal({title: "Bien!",
                                text: "Credito cobrado exitosamente",
                                type: "success",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1500});
                            pagandos = [];
                            $("#monto").val("");
                            $("#cambio").val("");
                            $("#credidetails").hide();
                            $("#creditlist").show();
                            if (a == idscuotas.length) {
                                a = 0;
                                Cargar();
                                window.open("/printrecibo/" + value.id);
                            }
                        });
                    });
                }, error: function () {
                    swal({title: "Error!",
                        text: "Error al cobrar el credito",
                        type: "error",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1500});
                }
            });
        }
    }
//    var idcuota = $("#cuotaapagarcredito").val();
//    var importe = $("#importecuota").val();
//    var saldo = $("#saldocuota").val();
//    var pagando = $("#importepagar").val();
//    if (pagando == "") {
//        return swal({title: "Advetencia!",
//            text: "Debe Ingresar al Monto a Pagar",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
//    importe = parseFloat(importe);
//    saldo = parseFloat(saldo);
//    pagando = parseFloat(pagando);
//    if (pagando <= 0) {
//        return swal({title: "Advetencia!",
//            text: "El monto a pagar debe ser mayor a 0",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
//    if (pagando > importe) {
//        return swal({title: "Advetencia!",
//            text: "El monto a pagar debe ser menor al importe de la cuota",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
//    if (pagando > saldo) {
//        return swal({title: "Advetencia!",
//            text: "El monto a pagar debe ser menor al saldo de la cuota",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
}

function openmodal(idv, totn) {
    $("#modal2").openModal();
    factura(idv, totn);
}

function cancelarcredito(id) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var route = "/obtenerdatosvent/" + id;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#idventcancel").val(value.id);
                $("#observacionescancel").val(value.observaciones);
            });
            $("#modal8").openModal();
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA MODIFICAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function editarfecha(id) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var route = "/obtenerdatosvent/" + id;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#idventafecha").val(value.id);
                $("#nombrecliefe").val(value.cliente);
                $("#totalfe").val(value.total);
                $("#observacionesfe").val(value.observaciones);
                $("#fechadeentregafe").val(value.cobrarCada);
            });
            $("#modal7").openModal();
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA MODIFICAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

/*
 * Metodo para mostrar las cuotas pagadas de un credito 
 * este metodo lo ejecuta el boton
 * @param {type} id -> Id de la venta a credito
 * @returns {undefined}
 */
function detallesCreditoPagado(id) {
    if ($("#perfilpuedeGuardar").val() == 1) {
        $("#creditlist").slideUp(10, function () {
            CargarcuotasPagadas(id);
            $("#credidetails").slideDown(10);
            $("#cred").text(id);
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Metodo para listar las ventas a creditos
 * @returns {undefined}
 */
function listarCreditos() {
    $("#credidetails").slideUp(10, function () {
        $("#creditlist").slideDown(10);
        $("#credito").val("");
        $("#tablacategoria").DataTable().destroy();
        Cargar();
        $("#monto").val("");
    });
}

function listarCreditosP() {
    $("#productCredit").slideUp(10, function () {
        $("#creditlist").slideDown(10);
        $("#credito").val("");
        $("#tablacategoria").DataTable().destroy();
        $("#monto").val("");
        Cargar();
    });
}

function mostrarProductos(id) {
    var contenedor = $("#productosdelcredito");
    contenedor.empty();
    var route = "/obtenerporductocreditos/" + id;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#clienteNombre").val(value.cliente);
            $("#fechaEntrega").val(value.cobrarCada);
            $("#observacionesventa").val(value.observaciones);
            var color;
            $("#botonproductos").empty();
            if (value.estado == 'A') {
                color = "pink accent-2";
                $("#botonproductos").append("<a class = 'btn waves-effect waves-light right' name = 'Modista' href='javascript:enviaramodista(" + id + ");'>" +
                        "Enviar Producto a Modista<i class='mdi-content-send right'></i></a>");
            } else if (value.estado == 'M') {
                color = "light-blue accent-3";
                $("#botonproductos").append("<a class = 'btn waves-effect waves-light right' name = 'Entregar' href='javascript:entregarcliente(" + id + ");'>" +
                        "Entregar Producto<i class='mdi-content-send right'></i></a>");
            } else {
                color = "light-green accent-3";
            }
            contenedor.append("<div class='product col s12 m4 l3' style='margin-top: 20px;'>" +
                    "<div class='card'>" +
                    "<div class='card-image waves-effect waves-block waves-light'>" +
                    "<a href='#' class='btn-floating btn-large btn-price waves-effect waves-light " + color + "'>" + value.estado + "</a>" +
                    "<img src='" + value.imagen + "' alt='product-img' width='200'></div>" +
                    "<ul class = 'card-action-buttons' >" +
                    "<li> <a class='btn-floating waves-effect waves-light light-blue' ><i class='mdi-action-info activator'></i></a>" +
                    "</li></ul><div class='card-content' ><div class='row' ><div class='col s8'>" +
                    "<p class='card-title grey-text text-darken-4'><a href='#' class='grey-text text-darken-4'><strong>" + value.nombre + "</strong></a>" +
                    "</p></div><div class ='col s4 no-padding'>" +
                    "<a href = ''> </a><img src='" + value.logo + "' alt='amazon' class='responsive - img' width='50px'>" +
                    "</a></div></div></div><div class = 'card-reveal'>" +
                    "<span class = 'card-title grey-text text-darken-4'><i class ='di-navigation-close right'></i><strong>" + value.nombre + "</strong></span>" +
                    "<p><strong>Descripcion: </strong>" + value.descripcion + "</br>" +
                    "<strong>Codigo de Barra: </strong>" + value.codigoDeBarra + "</br>" +
                    "<strong>Categoria: </strong>" + value.categoria + "</br>" +
                    "<strong>Estilo: </strong>" + value.estilo + "</br>" +
                    "<strong>Color: </strong>" + value.color + "</br>" +
                    "<strong>Marca: </strong>" + value.marca + "</br>" +
                    "<strong>Precio Venta: </strong>" + value.preciofinal + " Bs.</br>" +
                    "</p></div></div></div>");
        });
    });
}

function enviaramodista(id) {
    var route = "/enviarmodista/" + id;
    $.get(route, function (res) {
        window.open("/imprimirenviomodista/" + res);
        $("#productCredit").slideUp(10, function () {
            $("#creditlist").slideDown(10);
            $('#tablacuotas').DataTable().destroy();
            $("#credito").val("");
            $("#tablacategoria").DataTable().destroy();
            $("#monto").val("");
            Cargar();
        });
    });
}

function entregarcliente(id) {
    var route = "/entregarcliente/" + id;
    $.get(route, function (res) {
        window.open("/imprimirentrega/" + res);
        $("#productCredit").slideUp(10, function () {
            $("#creditlist").slideDown(10);
            $('#tablacuotas').DataTable().destroy();
            $("#credito").val("");
            $("#tablacategoria").DataTable().destroy();
            $("#monto").val("");
            Cargar();
        });
    });
}
/*
 * Metodo para mostrar la lista de cuotas de un credito pagado en su totalidad
 * @param {type} id -> ID de la venta realizada a credito
 * @returns {undefined}
 */
//function CargarcuotasPagadas(id) {
//    $('#data').empty();
//    var route = "/cuotascredito/" + id;
//    var token = $("#token").val();
//    $.ajax({
//        url: route,
//        headers: {'X-CSRF-TOKEN': token},
//        type: 'POST',
//        dataType: 'json',
//        success: function ($route) {
//            var tabladatos = $('#data');
//            $($route).each(function (key, value) {
//                $('#tablacuotas').DataTable().destroy();
//                var cobrado = parseFloat(value.totalCobrado).toFixed(1);
//                var importe = parseFloat(value.importe).toFixed(1);
//                if (value.totalCobrado == null) {
//                    cobrado = 0;
//                }
//                var saldo = parseFloat(importe).toFixed(1) - cobrado;
//                if (saldo < 0) {
//                    saldo = 0;
//                }
//                if (saldo == 0) {
//                    tabladatos.append("<tr>" +
//                            "<td>" + value.fechaVencimiento + "</td>" +
//                            "<td><span id='imporcuo" + value.id + "'>" + importe + "</span></td>" +
//                            "<td>" + cobrado + "</td>" +
//                            "<td><span id='saldocuo" + value.id + "'>" + saldo.toFixed(1) + "</span></td>" +
////                            "<td><div class='chip amber darken-4 white-text'>COBRADO</div></td>" +
//                            "<td>" +
//                            "<button class='btn btn-danger btn-floating abrir'  value=" + value.id + " title='Cobrar' href='javascript:Mostrar(this)'>" +
//                            "<i class='mdi-editor-insert-drive-file'></i>" +
//                            "</button>" +
//                            "</td>" +
//                            "</tr>");
//                } else {
//                    tabladatos.append("<tr>" +
//                            "<td>" + value.fechaVencimiento + "</td>" +
//                            "<td><span id='imporcuo" + value.id + "'>" + importe + "</span></td>" +
//                            "<td>" + cobrado + "</td>" +
//                            "<td><span id='saldocuo" + value.id + "'>" + saldo.toFixed(1) + "</span></td>" +
////                            "<td><input disabled  type='number' name='' value ='0' id='cuota" + value.id + "' min='0' max='" + saldo + "'></td>" +
//                            "<td>" +
//                            "<button class='btn btn-danger btn-floating abrir' value=" + value.id + " title='Cobrar' href='javascript:Mostrar(this)'>" +
//                            "<i class='mdi-editor-insert-drive-file'></i>" +
//                            "</button>" +
//                            "</td>" +
//                            "</tr>");
//                }
//            });
////            $(".modal-trigger").leanModal();
////            paginadorCuotas();
////            $("#tablacuotas_length").attr("style", "display:none;");
////            $("#tablacuotas_filter").attr("style", "display:none;");
////            $("#save").attr('style', 'display: none;');
////            $("#monto").attr('style', 'display: none;');
////            $("#monto1").attr('style', 'display: none;');
//        },
//        error: function () {
//            Materialize.toast('No hay cuotas para mostrar !! ', 2000, 'rounded');
//        }
//    });
//}

/*
 * Paginador de la lista de cuotas de un credito
 * @returns {undefined}
 */
function paginadorCuotas() {
    $('#tablacuotas').DataTable({
        "pagingType": "full_numbers",
        retrieve: true,
        "order": [0, "asc"],
        "lengthMenu": [[20, 40, 60], [20, 40, 60]],
        responsive: true
    });
}
/*
 * Metodo para mostrar el historial de pagos de una cuota en especifico
 * @param {type} btn
 * @returns {undefined}
 */
function Mostrar(id) {
    $("#importecuota").val($("#imporcuo" + id).text());
    $("#saldocuotass").val($("#saldocuo" + id).text());
    $("#cuotaapagarcredito").val(id);
//    var route = "cuotascreditopagadas/" + id;
//    var token = $("#token").val();
//    $.ajax({
//        url: route,
//        headers: {'X-CSRF-TOKEN': token},
//        type: 'GET',
//        dataType: 'json',
//        success: function ($route) {
//            $("#cuotasdatos").empty();
//            var tabladatos = $('#cuotasdatos');
//            $($route).each(function (key, value) {
//                tabladatos.append("<tr>" +
//                        "<td>" + value.id + "</td>" +
//                        "<td>" + value.fecha + "</td>" +
//                        "<td>" + value.importe + " Bs.</td>" +
//                        "</tr>");
//            });
//        },
//        error: function () {
//            Materialize.toast('No hay cuotas para mostrar !! ', 2000, 'rounded');
//        }
//    });
}


function Historico(id) {
    var route = "cuotascreditopagadas/" + id;
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        success: function ($route) {
            $("#cuotasdatos").empty();
            var tabladatos = $('#cuotasdatos');
            var bool = false;
            $($route).each(function (key, value) {
                tabladatos.append("<tr>" +
                        "<td>" + value.id + "</td>" +
                        "<td>" + value.fecha + "</td>" +
                        "<td>" + value.importe + " Bs.</td>" +
                        "<td>" + value.formaPago + "</td>" +
//                        "<td>" +
//                        "<button value=" + value.id +
//                        " OnClick='cargardatospago(this);' class='waves-effect waves-light btn btn-floating' title='Editar'>" +
//                        "<i class='material-icons'>mode_edit</i>" +
//                        "</button>" +
//                        "</td>" +
//                        "<td>" +
//                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
//                        "<i class='material-icons'>delete</i>" +
//                        "</button>" +
//                        "</td>" +
                        "</tr>");
                cuots = id;
                bool = true;
            });
            if (bool) {
                $("#modal5").openModal();
                $("#visaalm").hide();
            } else {
                swal({title: ":(",
                    type: "warning",
                    text: "No ha realizado ningun pago de la cuota",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 2000});
            }
        },
        error: function () {
            Materialize.toast('No hay cuotas para mostrar !! ', 2000, 'rounded');
        }
    });
}

function cargardatospago(btn) {
    var puede = $("#perfilpuedeModificar").val();
    if (puede == 1) {
        if (cancell == 0) {
            var route = "/Creditos/" + btn.value + "/edit";
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    var sald = parseFloat(value.importe) + parseFloat(value.totalCobrado);
                    $("#cuotapagadaid").val(value.id);
                    $("#importecuotacobrado").val(value.importe);
                    $("#saldocuotacobrado").val(value.totalCobrado);
                    $("#saldocuota").val(sald);
                    $("#modal6").openModal();
                });
            });
        } else {
            swal({title: "Advertencia!",
                text: "EL CREDITO YA FUE CANCELADO NO SE PUEDE MODIFICAR EL IMPORTE PAGADO",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
    } else {
        swal({title: "NO TIENE PERMISO PARA EDITAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function actualizarmonto() {
    var puede = $("#perfilpuedeModificar").val();
    if (puede == 1) {
        var id = $("#cuotapagadaid").val();
        var saldocuotacobrado = $("#nombres").val();
        var importe = $("#importecuotacobrado").val();
        var saldocuota = $("#saldocuota").val();
        if (!importe || !importe.trim().length) {
            return swal({title: "Advertencia!",
                text: "Debe ingresar el importe ",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        saldocuotacobrado = parseFloat(saldocuotacobrado);
        importe = parseFloat(importe);
        if (importe <= 0) {
            return swal({title: "Advertencia!",
                text: "El importe debe ser mayor a 0 ",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (importe > saldocuota) {
            return swal({title: "Advertencia!",
                text: "El importe excede al saldo de la cuota",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        var route = "/Creditos/" + id + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {importe: importe},
            success: function () {
                $("#modal5").closeModal();
                $("#modal6").closeModal();
                debugger;
                Cargarcuotas(idcuotaa);
                Historico(cuots);
                swal({title: "Actualizacion Completa",
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
        swal({title: "NO TIENE PERMISO PARA ACTUALIZAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        debugger;
        if (cancell == 0) {
            var route = "/Creditos/" + btn.value + "";
            var token = $("#token").val();
            swal({title: "Esta seguro que decea eliminar el pago del credito?",
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
                                    $("#modal5").closeModal();
                                    Cargarcuotas(idcuotaa);
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
            swal({title: "Advertencia!",
                text: "EL CREDITO FUE CANCELADO, NO SE PUEDE ELIMINAR LA CUOTA PAGADA",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
    } else {
        swal({title: "NO TIENE PERMISO PARA ELIMINAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}


/*
 * Metodo para buscar el cliente al cual se busca el cliente mediante el nit al
 * momento de la facturasion
 * @returns {undefined}
 */
function buscarcliente() {
    $("#razonsocial").val("");
    var idventa = $('#nit').val();
    var route = "/buscarcliente/" + idventa;
    $.get(route, function (res) {
        if (res == 0) {
            $("#razonsocial").val("");
        }
        $(res).each(function (key, value) {
            $("#razonsocial").val(value.razonSocial);
        });
    });
}
/*
 * transformar el total de la venta a literal
 * @param {type} id
 * @param {type} totalNeto
 * @returns {undefined}
 */
function factura(id, totalNeto) {
    totalliteral = LITERAL(totalNeto + ".00");
    total = totalNeto;
    idventa = id;
}
/*
 * Metodo para imprimir la factura de una venta
 * @returns {undefined}
 */
function imprimirfactura() {
    if ($("#perfilpuedeImprimir").val() == 1) {
        var nit = $("#nit").val();
        if (!nit || !nit.trim().length) {
            return Materialize.toast('El NIT no puede estar vacio', 1000, 'rounded');
        }
        var nombre = $("#razonsocial").val();
        if (!nombre || !nombre.trim().length) {
            return Materialize.toast('La Razon Social no puede estar vacia', 1000, 'rounded');
        }
        var token = $("#token").val();
        var route = "/Factura";
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                idventa: idventa,
                idpuntoventa: iddelpuntoventa,
                valorliteral: totalliteral,
                total: total,
                nombre: nombre,
                nit: nit,
                idelemeplaedo: idempleado,
                facturacredito: true
            },
            success: function ($idfactura) {
                var nFactura;
                $($idfactura).each(function (key, value) {
                    if (value == "No se puede generar factura porque no existe libro de órdenes activos") {
                        return Materialize.toast(value, 1000, 'rounded');
                    } else {
                    }
                    if (value == "Fecha limite a Terminado") {
                        return Materialize.toast(value, 1000, 'rounded');
                    } else {
                    }
                    if (value == "limite de numero de factura") {
                        return  Materialize.toast(value, 1000, 'rounded');
                    } else {
                        $(value).each(function (key, values) {
                            nFactura = values.id;
                            $("#modal2").closeModal();
                            Materialize.toast('Guardado Exitoso', 1000, 'rounded');
                            Cargar();
                            window.open("/imprirfactura/" + nFactura);
                        });
                    }
                });
            },
            error: function () {
                swal({title: "Error al crear la factura",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1500});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}



function productosVendidos(id) {
    if ($("#perfilpuedeGuardar").val() == 1) {
        $("#creditlist").slideUp(10, function () {
            $("#productCredit").slideDown(10);
            $("#credv").text(id);
            mostrarProductos(id);
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}


//---------------------hola-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Función modulo, regresa el residuo de una división 
function mod(dividendo, divisor) {
    resDiv = dividendo / divisor;
    parteEnt = Math.floor(resDiv); // Obtiene la parte Entera de resDiv 
    parteFrac = resDiv - parteEnt; // Obtiene la parte Fraccionaria de la división
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
        string_units = ""; // empties the units check, since it has alredy been handled on the tens switch 
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
        number = mod(number, 1000000); // conversion function 

        if (millions != 0)
        {
// This condition handles the plural case 
            if (millions == 1)
            {              // if only 1, use 'millon' (million). if 
                descriptor = " millon "; // > than 1, use 'millones' (millions) as 
            } else
            {                           // a descriptor for this triad. 
                descriptor = " millones ";
            }
        } else
        {
            descriptor = " "; // if 0 million then use no descriptor. 
        }
        millions_final_string = string_literal_conversion(millions) + descriptor;
        thousands = ObtenerParteEntDiv(number, 1000); // now, send the thousands to the string 
        number = mod(number, 1000); // conversion function. 
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
        moneda = " BOLIVIANOS ";
    } else
    {
        moneda = " BOLIVIANOS ";
    }
    /* Regresa el número en cadena entre paréntesis y con tipo de moneda y la fase M.N.*/
//Mind Mod, si se deja MIL pesos y se utiliza esta función para imprimir documentos
//de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
    if (cad == 'MIL ') {
        cad = 'UN MIL ';
    }
    return cad + moneda + centavos + " Bs.";
}

$(document).delegate('.abrir', 'click', function () {
    $("#contentdiv").hide();
    $('[colspan="6"]').parent('tr').remove();
    $(this).parents('tr')
            .after('<tr/>')
            .next()
            .append('<td colspan="6" id="visaalm"/>')
            .children('td')
            .append('<div/>')
            .children()
            .css('background', 'white')
            .html($('#contentdiv').html());
});

//        if ($("#monto").val() != "") {
//            var mo = parseFloat($("#monto").val()).toFixed(1);
//            var sal = parseFloat($("#saldo").val()).toFixed(1);
//            if (mo <= 0) {
//                return Materialize.toast('El monto a pagar no debe ser menor o igual a 0', 2000, 'rounded');
//            }
//            if (parseInt(mo) > parseInt(sal)) {
//                return Materialize.toast('El monto a pagar excede el saldo del credito con: ' + apagar + ' Bs.', 2000, 'rounded');
//            }
//            var idVenta = $("#cred").text();
//            for (var a = 0; a < idscuotas.length; a++) {
//                var idCuentaCobrar = idscuotas[a];
//                var importePagar = $("#cuota" + idCuentaCobrar).val();
//                if (importePagar != 0) {
//                    var importePagar = $("#cuota" + idCuentaCobrar).val();
//                    var route = "/Creditos";
//                    var token = $("#token").val();
//                    var glosa = parseFloat($("#monto").val()).toFixed(1);
//                    $.ajax({
//                        url: route,
//                        headers: {'X-CSRF-TOKEN': token},
//                        type: 'POST',
//                        dataType: 'json',
//                        data: {
//                            importe: importePagar,
//                            idPuntoVenta: iddelpuntoventa,
//                            idCuentaCobrar: idCuentaCobrar,
//                            glosa: glosa
//                        },
//                        success: function (data) {
//                            $(data).each(function (key, value) {
//                                $(value).each(function (key, values) {
//                                    var f = idscuotas.length;
//                                    debugger;
//                                    if (a == f) {
//                                        Materialize.toast('Guardado Exitoso', 1000, 'rounded');
//                                        listarCreditos();
//                                        window.open("/printrecibo/" + idVenta);
//                                        idscuotas = [];
//                                        saldosCuotas = [];
//                                        apagar = 0;
//                                    }
//                                });
//                            });
//                        }, error: function () {
//                            Materialize.toast('Error', 1000, 'rounded');
//                        }
//                    });
//                }
//            }
//        } else {
//            Materialize.toast('Inserte el monto a pagar', 2000, 'rounded');
//        }


$("#guardar").click(function (event) {
    imprimirfactura();
});
$("#actfecha").click(function (event) {
    var idVenta = $("#idventafecha").val();
    var fechaentrega = $("#fechadeentregafe").val();
    var d = new Date();
    d.setDate(d.getDate() + 45);
    if (Date.parse(fechaentrega) < Date.parse(d)) {
        return swal({title: "Advertenvia!",
            text: "Coloque una nueva fecha",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var route = "/updatedate";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {
            id: idVenta,
            fecha: fechaentrega
        },
        success: function () {
            Cargar();
            swal({title: "ACTUALIZACION COMPLETA",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $("#modal7").closeModal();
        },
        error: function () {
            swal({title: "ERROR AL ACTUALIZAR LA FECHA",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
$("#guardarcancel").click(function (event) {
    var idVenta = $("#idventcancel").val();
    var observaciones = $("#observacionescancel").val();
    var route = "/updatecancel";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {
            id: idVenta,
            observaciones: observaciones
        },
        success: function () {
            Cargar();
            swal({title: "CREDITO CANCELADO EXITOSAMENTE",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $("#modal8").closeModal();
        },
        error: function () {
            swal({title: "ERROR AL CANCELAR EL CREDITO",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});

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