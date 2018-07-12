var iddelpuntoventa;
var idempleado;
var importe = 0;
var idscuotas = [];
var saldosCuotas = [];
var apagar;
var idRecibo;

var totalliteral;
var total;
var idventa;

$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    Cargar();
});

function Cargar() {
    var tabladatos = $('#datos');
    debugger;
    var route = "/listarcreditocompra/";
    $('#datos').empty();
    var cobrado = 0;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            var cobrado = 0;
            var c = 0;
            if (value.pgado == null) {
                c = 0;
            } else {
                c = value.pgado;
            }
            var cob = parseFloat(c).toFixed(1);
            var cuen = parseFloat(value.aCuenta).toFixed(1);
            if (value.pgado == null) {
                cobrado = cuen;
            } else {
                cobrado = parseFloat(cob) + parseFloat(cuen);
            }
            var totalNeto = parseFloat(value.total).toFixed(1) - parseFloat(value.aCuenta).toFixed(1);
            var saldo = parseFloat(value.total).toFixed(1) - parseFloat(cobrado).toFixed(2);
            //if (totalNeto < cobrado) {
            //   saldo = 0;
            //}
            saldo = parseFloat(saldo).toFixed(1);

            tabladatos.append("<tr>" +
                    "<td>" + value.id + "</td>" +
                    "<td>" + value.fecha + "</td>" +
                    "<td>" + value.hora + "</td>" +
                    "<td>" + value.cliente + "</td>" +
                    "<td>" + value.total + "</td>" +
                    "<td>" + cobrado + "</td>" +
                    "<td>" + saldo + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='detallesCredito(" + value.id + "," + value.saldoacobrar + ");' class='waves-effect waves-light btn' href='#' title='Editar'><i class='material-icons'>credit_card</i></button>" +
                    "</tr>"
                    );

        });
        $('.modal-trigger').leanModal({
            dismissible: false,
            complete: function () {
                $('.lean-overlay').remove();
            }
        });
        paginador();
    });

}

function detallesCredito(id, saldo)
{
    debugger;
    if ($("#perfilpuedeGuardar").val() == 1) {
        $("#tablacont").slideUp(10, function () {
            debugger;
            Cargarcuotas(id, saldo);
            debugger;
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
function Cargarcuotas(id, saldo) {
    debugger;
    $('#data').empty();
    $("#saldo").val(saldo);
    var sald = 0;
    idcuotaa = id;
    var route = "/listarCuotas/" + id;
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        success: function ($route) {
            var tabladatos = $('#data');
            var cont = 0;
            var bool = true;
            $($route).each(function (key, value) {
                $('#tablacuotas').DataTable().destroy();
                var importe = parseFloat(value.importe).toFixed(1);
                var cobrado = parseFloat(value.totalCobrado).toFixed(1);
                if (value.totalCobrado == null) {
                    cobrado = 0;
                }
                var saldo = parseFloat(importe) - cobrado;
                sald = sald + saldo;

                var botn = "<a class='btn btn-danger btn-floating abrir' title='Cobrar' href='javascript:Mostrar(" + value.id + ")'>" +
                        "<i class='mdi-action-payment'></i>";
                debugger;
                if (saldo !== 0 && !bool) {
                    botn = "";
                }
                if (saldo == 0) {
                    tabladatos.append("<tr>" +
                            "<td>" + value.fechaVencimiento + "</td>" +
                            "<td><span id='imporcuo" + value.id + "'>" + importe + "</span></td>" +
                            "<td>" + cobrado + "</td>" +
                            "<td><span id='saldocuo" + value.id + "'>" + saldo.toFixed(1) + "</span></td>" +
//                            "<td><div class='chip amber darken-4 white-text'>COBRADO</div></td>" +
                            "<td>" +
//                            "<a class='btn btn-danger btn-floating abrir' title='Cobrar' href='javascript:Mostrar(" + value.id + ")'>" +
//                            "<i class='mdi-editor-insert-drive-file'></i>" +
//                            "</a>" +
                            "</td>" +
                            "<td>" +
                            "<a class='btn btn-danger btn-floating' title='Historico' href='javascript:Historico(" + value.id + ")'>" +
                            "<i class='mdi-editor-insert-drive-file'></i>" +
                            "</a>" +
                            "</td>" +
                            "</tr>");
                } else {
                    tabladatos.append("<tr>" +
                            "<td>" + value.fechaVencimiento + "</td>" +
                            "<td><span id='imporcuo" + value.id + "'>" + importe + "</span></td>" +
                            "<td>" + cobrado + "</td>" +
                            "<td><span id='saldocuo" + value.id + "'>" + saldo.toFixed(1) + "</span></td>" +
//                            "<td><input disabled  type='number' name='' value ='0' id='cuota" + value.id + "' min='0' max='" + saldo + "'></td>" +
                            "<td>" +
                            botn +
                            "</a>" +
                            "</td>" +
                            "<td>" + "<a class='btn btn-danger btn-floating' title='Historico' href='javascript:Historico(" + value.id + ")'>" +
                            "<i class='mdi-editor-insert-drive-file'></i>"
                            +
                            "</a>" +
                            "</td>" +
                            "</tr>");
                    bool = false;
//                    idscuotas.push(value.id);
//                    saldosCuotas.push(parseFloat(saldo).toFixed(1));
                }
                cont++;
                $("#saldo").val(sald.toFixed(2));
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
            Materialize.toast('No hay cuotas para mostrar !! ', 2000, 'rounded');
        }
    });

}

function Mostrar(id) {
    debugger;
    $("#importecuota").val($("#imporcuo" + id).text());
    $("#saldocuotass").val($("#saldocuo" + id).text());
    $("#cuotaapagarcredito").val(id);
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


function guardarcobro() {
    debugger;
    var idcuota = $("#cuotaapagarcredito").val();
    var importe = $("#importecuota").val();
    var saldo = $("#saldocuotass").val();
    var pagando = $("#importepagar").val();
    if (pagando == "") {
        return swal({title: "Advetencia!",
            text: "Debe Ingresar al Monto a Pagar",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    importe = parseFloat(importe);
    saldo = parseFloat(saldo);
    pagando = parseFloat(pagando);
    if (pagando <= 0) {
        return swal({title: "Advetencia!",
            text: "El monto a pagar debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (pagando > importe) {
        return swal({title: "Advetencia!",
            text: "El monto a pagar debe ser menor al importe de la cuota",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (pagando > saldo) {
        return swal({title: "Advetencia!",
            text: "El monto a pagar debe ser menor al saldo de la cuota",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var formaPago;
    var Efectivo = document.getElementById('test1').checked;
    // var Tarjetadebito = document.getElementById('test2').checked;
    //var Tarjetacredito = document.getElementById('test3').checked;
    //var Depositobanco = document.getElementById('test4').checked;
    //var Cheque = document.getElementById('test5').checked;
    if (Efectivo) {
        formaPago = "Efectivo";
    }
    //  if (Tarjetadebito) {
    //     formaPago = "Tarjeta de Debito";
//    }
//    if (Tarjetacredito) {
//        formaPago = "Tarjeta de Credito";
//    }
//    if (Depositobanco) {
//        formaPago = "Deposito Banco";
//    }
//    if (Cheque) {
//        formaPago = "Cheque";
//    }
    var route = "/CobroaCuota";
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
            formaPago: formaPago
        },
        success: function (data) {
            $(data).each(function (key, values) {
                $(values).each(function (key, value) {
                    swal({title: "El Pago de la cuota fue Exitoso",
                        text: "El monto a pagar debe ser menor al importe de la cuota",
                        type: "success",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                    Cargarcuotas(idcuotaa);
                    //  window.open("/printrecibo/" + value.id);
                });
            });
        }, error: function () {
            swal({title: "Advetencia!",
                text: "El monto a pagar debe ser menor al importe de la cuota",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}
function calcularPago() {
    var monto = $("#monto").val();
    for (var a = 0; a < idscuotas.length; a++) {
        var id = idscuotas[a];
        var sald = saldosCuotas[a];
        if (monto != "") {
            if (monto == sald) {
                $("#cuota" + id).val(monto);
                apagar = 0;
                return;
            } else if (monto > sald) {
                $("#cuota" + id).val(sald);
                monto = monto - sald;
                apagar = monto;
            } else if (monto > 0) {
                $("#cuota" + id).val(monto);
                monto = monto - sald;
                apagar = monto;
            } else if (monto <= 0) {
                $("#cuota" + id).val(0);
                apagar = 0;
            }
        } else {
            $("#cuota" + id).val(0);
            apagar = 0;
        }
    }
}
function Historico(id) {
    debugger;
    var route = "cuotascreditopagadasC/" + id;
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        success: function ($route) {
            $("#cuotasdatos").empty();
            var tabladatos = $('#cuotasdatos');
            $($route).each(function (key, value) {
                tabladatos.append("<tr>" +
                        "<td>" + value.id + "</td>" +
                        "<td>" + value.fecha + "</td>" +
                        "<td>" + value.importe + " Bs.</td>" +
                        "</tr>");
                cuots = id;
            });
            $("#modal5").openModal();
            $("#visaalm").hide();
        },
        error: function () {
            Materialize.toast('No hay cuotas para mostrar !! ', 2000, 'rounded');
        }
    });
}

$("#save").click(function (event) {
    debugger;
    if (apagar > 0) {
        Materialize.toast('El monto a pagar excede el saldo del credito con: ' + apagar + " Bs.", 2000, 'rounded');
        return;
    }
    var idVenta = $("#cred").text();
    for (var a = 0; a < idscuotas.length; a++) {
        var idCuentaCobrar = idscuotas[a];
        var importePagar = $("#cuota" + idCuentaCobrar).val();
        if (importePagar != 0) {
            var importePagar = $("#cuota" + idCuentaCobrar).val();
            var route = "/CobroaCuota";
            var token = $("#token").val();
            var glosa = $("#monto").val();
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': token},
                type: 'POST',
                dataType: 'json',
                data: {
                    importe: importePagar,
                    idPuntoVenta: iddelpuntoventa,
                    idCuentaCobrar: idCuentaCobrar,
                    glosa: glosa
                },
                success: function ($idcredito) {
                    $($idcredito).each(function (key, value) {
                        $(value).each(function (key, values) {
                            debugger;
                            if (a == idscuotas.length) {
                                debugger;
                                Materialize.toast('Guardado Exitoso', 1000, 'rounded');
                                //   window.open("/printrecibo/" + idVenta + "/" + values.id);
                                idscuotas = [];
                                saldosCuotas = [];
                                apagar = 0;
                            }
                        });
                    });
                }, error: function () {
                    Materialize.toast('Error', 1000, 'rounded');
                }
            });
        }
    }
    listarCreditos();
});

function listarCreditos() {
    $("#credidetails").slideUp(10, function () {
        $("#tablacont").slideDown(10);
        $('#tablacuotas').DataTable().destroy();
        $("#credito").val("");
        $("#tabla").DataTable().destroy();
        Cargar();
        $("#monto").val("");
    });
}

//function paginador() {
//    $('#tabla').DataTable({
//        "pagingType": "full_numbers",
//        retrieve: true,
//        "order": [0, "desc"],
//        "lengthMenu": [[20, 40, 60], [20, 40, 60]]
//    });
//   
//}
function paginador() {
    $('#tabla tfoot th').each(function () {
        var title = $(this).text();
        if (title == "") {

        } else {
            $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
        }
    });
    var table = $('#tabla').DataTable({
        "pagingType": "full_numbers",
        retrieve: true,
        "order": [0, "desc"],
        "lengthMenu": [[20, 40, 60], [20, 40, 60]]
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