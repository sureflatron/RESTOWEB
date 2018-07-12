var descuent;
var hoy;
var elcliente = "";
$(document).ready(function () {
    hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1; //hoy es 0!
    var yyyy = hoy.getFullYear();

    if (dd < 10) {
        dd = '0' + dd
    }

    if (mm < 10) {
        mm = '0' + mm
    }

    hoy = yyyy + '-' + mm + '-' + dd;
    $('#fechas').val(hoy);


    debugger;
    $('.fechaentregas').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 15 // Creates a dropdown of 15 years to control year
    });
    $('#fechaentregas').val(hoy);
//    initMap();
    Cargar();
    //cargarcliente();
    Cargartabla();
    cargarmesa();
    validarestado();
    seguridad();
    cargardescuento();
    CargarEmpresa();
    traerdatosprogramados();
    cargaralmacendesucursal();
    iddelpuntoventa = $('#iddelpuntoventa').val();
    iddelempleado = $('#iddelempleado').val();
    $(function () {
        $("#cliNombre").on('input', function () {
            var val = this.value;
            if ($('#clienteslist').find('option').filter(function () {
                return this.value.toUpperCase() === val.toUpperCase();
            }).length) {
                var abc = $("#clienteslist option[value='" + $('#cliNombre').val() + "']").attr('data-id');
                inputcargar(abc);
                elcliente = abc;
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
                elcliente = abc;
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
        debugger;
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
        debugger;
        var val = $(this).val();
        if (val == "noenvio") {
            $("#pagoEnvio").attr("style", "display :block;");
        } else {
            $("#pagoEnvio").attr("style", "display:none;");
        }
    });
    $('#telefonoFijo').formatter({
        'pattern': '{{9999999}}',
        'persistent': true
    });
    $('#celular').formatter({
        'pattern': '{{99999999}}',
        'persistent': true
    });
    $('#NIT').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });
    $('#nit').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });
    $('#pago').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });
    $('#aCuenta').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });
    $('#nrCuotas').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });
    $('#impgarantia').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });
    $("select#almacencombo").change(function () {
        var idalamcennew = $(this).val();
        var idventa = $('#venta').val();
        var route = "/actualizaralmacenventa/" + idventa + "/" + idalamcennew;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#almacen").val(idalamcennew);
                $("#nombrealmacenventa").text(value.nombre);
            });
        });
    });
    $('#editardetalleoptica').hide();
    $('#test85').click(function () {
        $('#antiguaventa').show();
        $('#nuevoventas').hide();
        $('#datallleproducto').show();
    });
    $('#test86').click(function () {
        $('#antiguaventa').hide();
        $('#nuevoventas').show();
        $('#datallleproducto').hide();
    });
});
//
var map;
var infowindow;

function initMap() {
    var punto = new google.maps.LatLng(-13.163622, -72.545926);
    var config = {
        zoom: 16,
        center: punto,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var mapa = new google.maps.Map($("#map")[0], config);
    google.maps.event.addListener(mapa, "click", function (event) {
        debugger;
        alert(event.latLng);
    });
}

var venderSinStock;
var iddelpuntoventa;
var iddelempleado;
var descuentoTotal;
function traerdatosprogramados() {
    debugger;
    var idventa = $('#venta').val();
    var route = "/validarestado/" + idventa;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.estado == 3) {
                traerprogramacion(idventa);
            }
        });
    });
}

function traerprogramacion($idventas) {
    var route = "/Ventaprogramda/" + idventas;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            //entregadomicilio    importetransporte   personaentrega  minutostoleranciaentrega  entregadomicilio
            $('#fechaentrega').val(value.fechaentrega);
            $('#horaentrega').val(value.horaentrega);
            $('#tolerancia').val(value.minutostoleranciaentrega);
            $('#personarecibeenvio').val(value.personaentrega);
            $('#importetransporte').val(value.importetransporte);
            $('#dirreccionenvio').val(value.direccionenvio);
            if (value.entregadomicilio == 1) {
                document.getElementById('sienviodomiciolio').checked = true;
                $('#informaciondelenvio').show();
            } else {
                document.getElementById('noenviodomiciolio').checked = true;
            }
            if (value.Cobroalentregar == 1) {
                document.getElementById('sicobro').checked = true;
            } else {
                document.getElementById('nocobro').checked = true;
            }
        });
    });
}

//////////////////////////////con entrega tartiana***************************************** quitardiceccion

function cargardescuento() {
    $('#descuentos')
            .find('option')
            .remove()
            .end()
            .append('<option value="0" >tipo de descuento</option>')
            .val('null')
            ;
    $('#descuentos').material_select();
    var route = "/listardescuentos/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#descuentos').append('<option value=' + value.id + ' >'
                    + value.nombre +
                    '(' + value.descuento + '%)</option>');
            $('#descuentos').material_select();
        });
    });
}

function CargarEmpresa() {
    debugger;
    var route = "/listarEmpresa/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            venderSinStock = value.venderSinStock;
        });
    });
}

$("select#descuentos").change(function () {
    debugger;
    var idselecionado = $(this).val();
    var route = "/Descuento/" + idselecionado + "/edit";
    var totalcargado = $("#totaldefacturas").val(totalfactura);
    $("#totaldefacturassindescuento").val(totalfactura);
    if (totalfactura == 0) {
        Materialize.toast('No tiene total Asignado', 1000, 'rounded');
    } else {
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                debugger;
                $("#iddescuento").val(value.id);
                var totalfacturas = $("#totaldefacturas").val();
                parseFloat(totalfacturas);
                var desc = value.descuento;
                parseFloat(desc);
                var descuento = totalfacturas * desc / 100;
                descuentoTotal = descuento;
                document.getElementById('mostrardescuento').innerHTML = descuento;
                $("#descuentoasignado").val(value.descuento);
                descuent = value.descuento;
                var totalfacturas = totalfacturas - descuento;
                var total = "" + totalfacturas + "";
                $("#datosfactura tr").remove();
                var valor = LITERAL(total);
                var tabladatos = $('#datosfactura');
                $("#totalacobrarcondescuentoenlaventa").text(totalfacturas);
                tabladatos.append("<tr><td id='totalcondescuentoventa'>" + totalfacturas + "</td><td id='totalventaliteral'>" + valor + "</td><tr>");
                $("#totaldefacturas").val(totalfacturas);
            });
        });
    }
});

//////////////////////////////con entrega tartiana  aqui***************************************** quitardiceccion
function mostrardatosdeenvio() {
    $('#informaciondelenvio').show();
}

function ocultardatosdeenvio() {
    $('#informaciondelenvio').hide();
}

var contardirecciones = 0;

$('#nuevadireccion').click(function () {
    contardirecciones++;
    debugger;
    if (contardirecciones == 1) {
        $('#direcion2').show();
        contardirecciones = 1;
        return;
    }
    if (contardirecciones == 2) {
        $('#direcion3').show();
        contardirecciones = 0;
    }
});

var quitarcontador = 1;
$('#quitardiceccion').click(function () {
    quitarcontador++;
    debugger;
    if (quitarcontador == 1) {
        $('#direcion2').hide();
        quitarcontador = 1;
        return false;
    }
    if (quitarcontador == 2) {
        $('#direcion3').hide();
        quitarcontador = 0;
    }
});

$("#programarentrega").click(function () {


    debugger;
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
    if (sienviodomicilio == true)
    {

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
        data: {idventa: idventa, estado: estado, FechaEntrega: FechaEntrega, HoraEntrega: HoraEntrega, EnvioaDomicilio: EnvioaDomicilio, PersonaRecibeenvio: PersonaRecibeenvio, DireccionEnvio: DireccionEnvio, Cobroalentregar: Cobroalentregar, Importetransporte: Importetransporte},
        success: function () {
            Materialize.toast('Envio Programadao !! ', 2000, 'rounded');
            validarestado();
        }, error: function () {



            Materialize.toast('Error!! ', 2000, 'rounded');
        }
    });


});
//////////////////////////////fin con entrega tartiana*****************************************
function calcular_total() {

    importe_total = 0;

    var entradavalor = $('#pago').val();
    var totaltraido = $("#totaldefacturas").val();
    if (totaltraido == '')
    {
        totaltraido = totalfactura;
    }
    var numero = parseInt(totaltraido);
    debugger;
    importe_total = entradavalor - numero;
    if (importe_total < 0) {
        $('#cambio').val("0");
    } else
    {
        $("#cambio").val(importe_total);
    }

}



function seguridad() {
    var idventa = $('#venta').val();

    var route = "/validarestado/" + idventa;
    $.get(route, function (res) {

        $(res).each(function (key, value) {

            if (value.estado == 1)
            {

                //cobrada
                // window.location.href="/listadeventa";
            }
            if (value.estado == 0) {

                return $mensaje = 0;
            }
            if (value.estado == 2) {

                return $mensaje = 2;
            } else {
            }


        });
    });
}


var idprodu = 0;
var idventas = 0;
var idproductos = 0;
var totalfactura = 0;

function Cargar() {
    var containerw = $('#divrow2');
    var route = "/listacategorias/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            containerw.append("<div class='col s6 m4 l3' style='margin-bottom: 15px !important;'>" +
                    "<img src='" + value.imagen + "' width='64px' height='64px' class='circle responsive-img valign profile-image'/>" +
                    "<h6 id='textnombre'>" + value.nombre + "</h6>" +
                    "<a class='btn' href='#' onclick='Mostrar(" + value.id + ")'><i class='material-icons'>playlist_add</i></a>" +
                    "</div>"
                    );
        });
        $('.materialboxed').materialbox();
    });
}

function buscarcliente() {
    debugger;
    $("#razonsocial").val("");
    var idventa = $('#nit').val();
    var route = "/buscarcliente/" + idventa;
    $.get(route, function (res) {
        if (res == 0) {
            var nombre = $("#razonsocial").val("");
        }
        $(res).each(function (key, value) {
            var dato = value.razonSocial;
            var nombre = $("#razonsocial").val(value.razonSocial);

        });
    });
}

function guardarcliente($nit, $nombre) {

    var route = "/agregarcliente/" + $nit + "/" + $nombre;

    $.get(route, function (res) {
        debugger;
        if (res == 0) {
            return;
        }
        {

        }
    });

}

//calcular_total
$("#nuevocliente").click(function () {


    debugger;
    var nombre = $("#nombre").val();
    var direccion = $("#direccion").val();
    var telefonoFijo = $("#telefonoFijo").val();
    var correo = $("#correo").val();
    var celular = $("#celular").val();
    var razonSocial = $("#razonSocial").val( );
    var NIT = $("#NIT").val( );
    if (!nombre || !nombre.trim().length) {
        Materialize.toast('Nombre Es obligatorio', 1000, 'rounded');
        return;
    } else {
    }
    if (!razonSocial || !razonSocial.trim().length) {
        Materialize.toast('Campos vacios', 1000, 'rounded');
        return;
    } else {
    }
    if (!NIT || !NIT.trim().length) {
        Materialize.toast('Campos vacios', 1000, 'rounded');
        return;
    } else {
    }
    var route = "/Cliente";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {nombre: nombre, direccion: direccion, telefonoFijo: telefonoFijo, correo: correo, celular: celular, razonSocial: razonSocial, NIT: NIT},
        success: function () {

            cargarcliente();



            $("#modal2").closeModal();
            Materialize.toast('Guardado Exitoso', 1000, 'rounded');
            $("#nombre").val("");
            $("#direccion").val("");
            $("#telefonoFijo").val("");
            $("#correo").val("");
            $("#celular").val("");
            $("#razonSocial").val("");
            $("#NIT").val("");
        }, error: function () {
            Materialize.toast('Ya se creo Cliente !! ' + nombre, 2000, 'rounded');

        }
    });


});
//editarventa
function cargarmesa() {

    var idventa = $('#venta').val();
    var route = "/Bucarmesaporventa/" + idventa;
    $.get(route, function (res) {

        $(res).each(function (key, value) {

            var valors = $("#mesa").val(value.idmesa);
            $('#mesa option:selected').val(value.idmesa);
            $("#mesa").material_select();

        });
    });
}

function cargarcliente() {
    $('#cliente')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un Cliente</option>')
            .val('null')
            ;
    $('#cliente').material_select();
    var route = "/listarcliente/";
    $.get(route, function (res) {

        $(res).each(function (key, value) {

            $('#cliente').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#cliente').material_select();

        });
    });

}

function Mostrar($btn) {
    var idtipo = parseInt($btn);
    var containerw = $('#sdivrow');
    var almacen = $("#almacen").val();
    containerw.empty();
    var route = "/listaproductoportipo/" + idtipo + "/" + almacen;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var colocarcero = 0;
            debugger;
            if (value.stock == null) {
                colocarcero = 0;
            } else {
                colocarcero = value.stock;
            }
            containerw.append("<div class='col s6 m4 l3' style='margin-bottom: 15px !important;'>" +
                    "<img src='" + value.imagen + "' width='64px' height='64px' class='circle responsive-img valign profile-image'/>" +
                    "<h6><strong>" + value.nombre + "</strong></h6>" +
                    "<h6 id='txtprecioventa'>Precio: " + value.precioVenta + "</h6>" +
                    "<h6>Stock: <p id='txtstock'>" + colocarcero + "</p></h6>" +
                    "<a class='btn' href='#' onclick='agregar(" + value.id + ")'><i class='mdi-image-exposure-plus-1'></i></a>" +
                    "</div>");
//            window.location.href = '#tituloproductos';

        });
        document.getElementById('titulotipoprodcuto').click();
        document.getElementById('tituloproductos').click();
    });
    return "dato";
}
//productodetalleventa  para el cambio de venta hacer referencia a estas variables idprodu es la que trae la venta y al cargar la 
//tabla var route = "/detalleventas/"+2; ordenar segun la venta con el detalle de venta 

function agregar($btn) {
    idprodu = parseInt($btn);
    var route = "/productodetalleventa/" + idprodu;
    document.getElementById('idproducto').value = idprodu;
    debugger;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            document.getElementById('nombreproducto').innerHTML = value.nombre;
            Materialize.toast('Producto Seleccionado: ' + value.nombre + " ", 1000, 'rounded');
        });
    });
//    document.getElementById('tituloproductos').click();
//    document.getElementById('titulotipoprodcuto').click();
}


function Cargartabla() {
    var tabladatos = $('#datos');
    var idproducto = $('#Idproducto').val();
    var idventa = $('#venta').val();
    var route = "/detalleventas/" + idventa;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {

            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.cantidad + "</td>" +
                    "<td>" + value.precioVenta + "</td>" +
                    "<td>" + value.subtotal + "</td>" +
                    "<td>" +
                    "<button class='btn' value=" + value.id + "    OnClick='Eliminar(this);'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
            // Actualizartotal(value.subtotal);
            document.getElementById('total').innerHTML = value.total;
            totalfactura = value.total;
            idproductos = value.idProducto;
            idventas = value.idVenta;

        });
    });
}
function limpiar() {
//guardar
    $('#cantidadproducto').val("1");
    document.getElementById('total').innerHTML = "";
}

$("#agregardetalle").click(function () {
    debugger;
    var idventa = $('#venta').val();
    var cantidad = $('#cantidadproducto').val();
    var stockdisponible = parseInt($('#txtstock').text());
    if (idventa == '') {
        Materialize.toast('Esta venta ya fue cobrada. Cree una nueva venta', 2500, 'rounded');
        $('#generadordeventa').show();
    }
    if (venderSinStock == 0) {
        if (stockdisponible < cantidad) {
            Materialize.toast('La cantidad que decea vender no esta disponible', 2500, 'rounded');
        } else {

            var route = "/validarestado/" + idventa;
            $.get(route, function (res) {

                $(res).each(function (key, value) {

                    if (value.estado == 1)
                    {
                        Materialize.toast('Esta venta ya fue cobrada. Cree una nueva venta', 5000, 'rounded');
                        $('#generadordeventa').show();

                    }
                    if (value.estado == 0) {


                        debugger;
                        idventas = $('#venta').val();
                        if (!idventas || !idventas.trim().length) {
                            Materialize.toast('Cree un nueva venta para poder agregar al detalle ', 5000, 'rounded');
                            return;
                        } else {
                        }
                        var idProducto = $('#idproducto').val();
                        if (!idProducto || !idProducto.trim().length) {
                            Materialize.toast('Selecione un producto ', 1000, 'rounded');
                            return;
                        } else {
                        }


                        var route = "/Detalleventa";
                        var token = $("#token").val();

                        $.ajax({
                            url: route,
                            headers: {'X-CSRF-TOKEN': token},
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                idVenta: idventas,
                                idProducto: idProducto,
                                cantidad: cantidad,
                                precioventa: "null"
                            },
                            success: function () {
                                Cargartabla();
                                limpiar();
                                Materialize.toast('Guardado Exitoso', 1000, 'rounded');
                                window.location.href = '#botonesdepago';
                            }, error: function () {
                                Materialize.toast('Error al guardar', 1000, 'rounded');
                            }
                        });
                    }
                    if (value.estado == 2) {

                        Materialize.toast('Esta venta ya fue Anulada. Cree una nueva venta', 5000, 'rounded');
                    } else {
                    }


                });
            });
        }
    } else {
        var route = "/validarestado/" + idventa;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                if (value.estado == 1) {
                    Materialize.toast('Esta venta ya fue cobrada. Cree una nueva venta', 5000, 'rounded');
                    $('#generadordeventa').show();
                }
                if (value.estado == 0) {
                    debugger;
                    idventas = $('#venta').val();
                    if (!idventas || !idventas.trim().length) {
                        Materialize.toast('Cree un nueva venta para poder agregar al detalle ', 5000, 'rounded');
                        return;
                    }
                    var idProducto = $('#idproducto').val();
                    if (!idProducto || !idProducto.trim().length) {
                        Materialize.toast('Selecione un producto ', 1000, 'rounded');
                        return;
                    }
                    var cantidad = $('#cantidadproducto').val();
                    var route = "/Detalleventa";
                    var token = $("#token").val();
                    $.ajax({
                        url: route,
                        headers: {'X-CSRF-TOKEN': token},
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            idVenta: idventas,
                            idProducto: idProducto,
                            cantidad: cantidad,
                            precioventa: "null"
                        },
                        success: function () {
                            Cargartabla();
                            limpiar();
                            Materialize.toast('Guardado Exitoso', 1000, 'rounded');
                            window.location.href = '#botonesdepago';

                        }, error: function () {

                            Materialize.toast('Error al guardar', 1000, 'rounded');

                        }

                    });
                }
                if (value.estado == 2) {

                    Materialize.toast('Esta venta ya fue Anulada. Cree una nueva venta', 5000, 'rounded');
                } else {
                }


            });
        });

    }



///aqui

});
$("#generarventa").click(function () {
    var idpunto = iddelpuntoventa;
    var route = "/GenerarVenta/" + idpunto;
    var iddelaventa = 0;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            iddelaventa = parseInt(value.id);
            document.getElementById('venta').value = iddelaventa;
            window.location.href = "/Ventares/" + iddelaventa;
            // Cargartabla();

        });
    });

    //id="cobrarventas"  listadeventa
});


function cargarfactura() {

    debugger;
    var idventa = $('#venta').val();
    var idpuntoventa = iddelpuntoventa;
    var total = "" + totalfactura + "";
    //por aqui

    document.getElementById('totalfijo').innerHTML = totalfactura;
    $("#datosfactura tr").remove();
    var valor = LITERAL(total);
    var tabladatos = $('#datosfactura');
    $("#totalacobrarcondescuentoenlaventa").text(totalfactura);
    tabladatos.append("<tr><td id='totalcondescuentoventa'>" + totalfactura + "</td><td id='totalventaliteral'>" + valor + "</td><tr>");

}

function Actualizartotal($totals) {
    debugger;
    var total = $totals;
    idventas = $('#venta').val();
    var route = "/Detalleventa/" + idventas + "";
    var token = $("#token").val();
    debugger;

    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {total: total},
    });

}
//agregardetalle
$("#cobrarventas").click(function () {
});
//aqui

function validarestado() {
    var mensaje;
    var idventa = $('#venta').val();
    debugger;
    var route = "/validarestado/" + idventa;
    $.get(route, function (res) {

        $(res).each(function (key, value) {
//estado
            if (value.estado == 1)
            {

                // document.getElementById('estados').innerHTML = "Cobrado";
                $mensaje = "Cobrado";
            }
            if (value.estado == 0) {

                //  document.getElementById('estados').innerHTML = "Guardado";
                $mensaje = "Guardado";
            }
            if (value.estado == 2) {

                //   document.getElementById('estados').innerHTML = "Anulado";
                $mensaje = "Anulado";
            } else {
            }
            if (value.estado == 3) {

                //  document.getElementById('estados').innerHTML = "Programado";
                $mensaje = "Programado";
            } else {
            }

        });
    });



}

//hola
function empresafactura() {
    var routes = "/facturaempresa/" + iddelpuntoventa;
    var factura;
    $.get(routes, function (res) {

        $factura = res;
        if (factura == 1)
        {
            alert("Oye esto y pasa eso");

            return;
        } else {
        }
    });

}

$("#guardarfactura").click(function () {
    debugger;
    var totalsindesceutos = $("#total").text();
    if (totalsindesceutos == '') {
        return Materialize.toast('No se puede cobrar una venta sin detalle de venta', 2500, 'rounded');
    }
    var idventa = $("#venta").val();
    if (idventa == '') {
        $('#generadordeventa').show();
        return Materialize.toast('No se puede cobrar la misma venta. Cierre la ventana y cree una nueva venta.', 6000, 'rounded');
    }
    var estado;
    var route = "/Validarventaantigua/" + idventa;
    $.get(route, function (res) {
        debugger;
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
            var aCuenta = parseInt($("#aCuenta").val()) + "";
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
                debugger;
                pago = "Credito";
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
                    return Materialize.toast("Es necesario asignar un cliente para realizar el credito", 1000, "rounded");
                }
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
                debugger;
            }
            if (pago == "Efectivo") {
                if (parseFloat(pagos) < parseFloat(totalsindesceutos)) {
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

            var token = $("#token").val();
            var route = "/Factura";
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
                    idpuntoventa: idpuntoventa,
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
                    PersonaRecibeenvio: PersonaRecibeenvio
                },
                success: function ($idfactura) {
                    var nFactura;
                    $($idfactura).each(function (key, value) {
                        debugger;

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
                                $("#totalacobrarcondescuentoenlaventa").text(0);
                                $('#razonsocial').val("");
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
                                    window.open("/imprimirnotaventa/" + idventa);
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



$("#anularventa").click(function () {

    var idventa = $('#venta').val();
    if (!idventa || !idventa.trim().length) {
        Materialize.toast('Error : No se puede anular una venta anualda!! ', 1000, 'rounded');
        return;
    } else {
    }
    var route = "/AnularVenta/" + idventa;

    $.get(route, function (res) {

        debugger;
        if (res == 1) {
            validarestado();
            $("#datos tr").remove();
            $("#datosfactura tr").remove();
            $('#razonsocial').val("");
            $('#nit').val("");
            $('#venta').val("");
            $("#totalacobrarcondescuentoenlaventa").text(0);
            document.getElementById('total').innerHTML = "0";
            $('#generadordeventa').show();
            $("#modal1").closeModal();
            Materialize.toast('Venta Anulada', 1500, 'rounded');
        }
        if (res == 2) {
            Materialize.toast('No se pude anular un venta anulada!..', 2000, 'rounded');
            $('#generadordeventa').show();
        }
        if (res == 0) {
            Materialize.toast('Esta Venta fue cobrada no se pude anular', 1500, 'rounded');
        }
    });

});
function Eliminar(btn) {
    debugger;
    var idventa = $('#venta').val();
    var route = "/validarestado/" + idventa;
    $.get(route, function (res) {

        $(res).each(function (key, value) {

            if (value.estado == 1)
            {
                Materialize.toast('No puede eliminar el detalle ya fue cobrada', 1000, 'rounded');
                //cobrada
                // window.location.href="/listadeventa";
            }
            if (value.estado == 0) {
                var route = "/Detalleventa/" + btn.value + "";
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
                        idproducto: idproducto},
                    success: function () {
                        Cargartabla();
                        limpiar();
                        Materialize.toast('Eliminación completada', 1000);
                    }, error: function () {
                        Materialize.toast('Error', 1000, 'rounded');
                    }
                });

            }
            if (value.estado == 2) {

                Materialize.toast('No puede eliminar el detalle ya fue anulada', 1000, 'rounded');
            } else {
            }

        });
    });
}


//---------------------hola-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// Función modulo, regresa el residuo de una división 
function mod(dividendo, divisor)
{
    resDiv = dividendo / divisor;
    parteEnt = Math.floor(resDiv);            // Obtiene la parte Entera de resDiv 
    parteFrac = resDiv - parteEnt;      // Obtiene la parte Fraccionaria de la división
    //modulo = parteFrac * divisor;  // Regresa la parte fraccionaria * la división (modulo) 
    modulo = Math.round(parteFrac * divisor)
    return modulo;
} // Fin de función mod

// Función ObtenerParteEntDiv, regresa la parte entera de una división
function ObtenerParteEntDiv(dividendo, divisor)
{
    resDiv = dividendo / divisor;
    parteEntDiv = Math.floor(resDiv);
    return parteEntDiv;
} // Fin de función ObtenerParteEntDiv

// function fraction_part, regresa la parte Fraccionaria de una cantidad
function fraction_part(dividendo, divisor)
{
    resDiv = dividendo / divisor;
    f_part = Math.floor(resDiv);
    return f_part;
} // Fin de función fraction_part


// function string_literal conversion is the core of this program 
// converts numbers to spanish strings, handling the general special 
// cases in spanish language. 
function string_literal_conversion(number)
{
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
    if (centenas == 1 && decenas == 0 && unidades == 0)
    {
        string_hundreds = "cien ";
    }

// when you've got 10, you don't say any of the 11-19 special 
// cases.. just say 'diez' 
    if (decenas == 1 && unidades == 0)
    {
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
    if (decenas >= 3 && unidades >= 1)
    {
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


function LITERAL(number)
{

    //number = number_format (number, 2);
    number1 = number;
    //settype (number, "integer");
    cent = number1.split(".");
    centavos = cent[1];
    //Mind Mod
    number = cent[0];

    if (centavos == 0 || centavos == undefined)
    {
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
        moneda = " BOLIVIANOS ";
    } else
    {
        moneda = " BOLIVIANOS ";
    }
    /* Regresa el número en cadena entre paréntesis y con tipo de moneda y la fase M.N.*/
    //Mind Mod, si se deja MIL pesos y se utiliza esta función para imprimir documentos
    //de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
    if (cad == 'MIL ')
    {
        cad = 'UN MIL ';
    }


    return cad + moneda + centavos + " Bs.";
}

function inputcargar(btn) {
    var route = "/Cliente/" + btn + "/edit";
    $.get(route, function (res) {
        //para el envio
        debugger;
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
    debugger;
    var route = "/listaralmacensucursal/" + idpunto;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacencombo').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacencombo').material_select();
        });
    });
}

$("#Imprimir").click(function () {
    var idventa = $('#venta').val();
    window.open("/reporteoptica/" + idventa);
    window.open("/recibooptica/" + idventa);
    Materialize.toast('Descargando.....', 2500, 'rounded');
});

$("#ultimo").click(function () {
    var route = "/agregarrecibocliente";
    var token = $("#token").val();
    var idventa = $('#venta').val();
    var idCliente = elcliente;
    var nombrelente = $('#nombrelente').val();
    var ESFLejos = $("#ESFLejos").val();
    var CILLejos = $("#CILLejos").val();
    var EJELejos = $("#EJELejos").val();
    var PRISMALejos = $("#PRISMALejos").val();
    var DILejos = $("#DILejos").val();
    var laboratorio = $("#LABORATORIO").val();
    var totaloptica = $("#totaloptica").val();
    var fecha = $('#fechas').val();
    var ESFCerca = $("#ESFCerca").val();
    var CILCerca = $("#CILCerca").val();
    var EJECerca = $("#EJECerca").val();
    var PRISMACerca = $("#PRISMACerca").val();
    var DICerca = $("#DICerca").val();
    var ESFLejosizquierdo = $("#ESFLejosizquierdo").val();
    var CILLejosizquierdo = $("#CILLejosizquierdo").val();
    var EJELejosizquierdo = $("#EJELejosizquierdo").val();
    var PRISMALejosizquierdo = $("#PRISMALejosizquierdo").val();
    var DILejosizquierdo = $("#DILejosizquierdo").val();
    var ESFCercaizquierdo = $("#ESFCercaizquierdo").val();
    var CILCercaizquierdo = $("#CILCercaizquierdo").val();
    var EJECercaizquierdo = $("#EJECercaizquierdo").val();
    var PRISMACercaizquierdo = $("#PRISMACercaizquierdo").val();
    var DICercaizquierdo = $("#DICercaizquierdo").val();
    var ORGANICO = "NULL";
    var POLICARBONATO = "NULL";
    var HIGHLITE = "NULL";
    var HIGHINDEX = "NULL";
    var VIDRIO = "NULL";
    var BLANCO = "NULL";
    var ANTIRREFLEX = "NULL";
    var FOTOCROMATICO = "NULL";
    var CONTINTE = "NULL";
    var SENCILLA = "NULL";
    var BIFOCAL = "NULL";
    var PROGRESIVO = "NULL";
    var FUTUREX = "NULL";
    if (document.getElementById('ORGANICO').checked == true)
    {
        ORGANICO = 1;
    } else {
        ORGANICO = 0;
    }
    if (document.getElementById('POLICARBONATO').checked == true)
    {
        POLICARBONATO = 1;
    } else {
        POLICARBONATO = 0;
    }
    if (document.getElementById('HIGHLITE').checked == true)
    {
        HIGHLITE = 1;
    } else {
        HIGHLITE = 0;
    }
    if (document.getElementById('HIGHINDEX').checked == true)
    {
        HIGHINDEX = 1;
    } else {
        HIGHINDEX = 0;
    }
    if (document.getElementById('VIDRIO').checked == true)
    {
        VIDRIO = 1;
    } else {
        VIDRIO = 0;
    }
    if (document.getElementById('BLANCO').checked == true)
    {
        BLANCO = 1;
    } else {
        BLANCO = 0;
    }
    if (document.getElementById('ANTIRREFLEX').checked == true)
    {
        ANTIRREFLEX = 1;
    } else {
        ANTIRREFLEX = 0;
    }
    if (document.getElementById('FOTOCROMATICO').checked == true)
    {
        FOTOCROMATICO = 1;
    } else {
        FOTOCROMATICO = 0;
    }
    if (document.getElementById('CONTINTE').checked == true)
    {
        CONTINTE = 1;
    } else {
        CONTINTE = 0;
    }

    if (document.getElementById('SENCILLA').checked == true)
    {
        SENCILLA = 1;
    } else {
        SENCILLA = 0;
    }
    if (document.getElementById('BIFOCAL').checked == true)
    {
        BIFOCAL = 1;
    } else {
        BIFOCAL = 0;
    }
    if (document.getElementById('PROGRESIVO').checked == true)
    {
        PROGRESIVO = 1;
    } else {
        PROGRESIVO = 0;
    }
    if (document.getElementById('FUTUREX').checked == true)
    {
        FUTUREX = 1;
    } else {
        FUTUREX = 0;
    }
    var totalinterno = $("#totalinterno").val();
    var fechaentrega = $('#fechaentregas').val();
    debugger;
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            ORGANICO: ORGANICO,
            POLICARBONATO: POLICARBONATO,
            HIGHLITE: HIGHLITE,
            HIGHINDEX: HIGHINDEX,
            VIDRIO: VIDRIO,
            BLANCO: BLANCO,
            ANTIRREFLEX: ANTIRREFLEX,
            FOTOCROMATICO: FOTOCROMATICO,
            CONTINTE: CONTINTE,
            SENCILLA: SENCILLA,
            BIFOCAL: BIFOCAL,
            PROGRESIVO: PROGRESIVO,
            FUTUREX: FUTUREX,
            idventa: idventa,
            idCliente: idCliente,
            ESFLejos: ESFLejos,
            CILLejos: CILLejos,
            EJELejos: EJELejos,
            PRISMALejos: PRISMALejos,
            DILejos: DILejos,
            ESFCerca: ESFCerca,
            CILCerca: CILCerca,
            EJECerca: EJECerca,
            PRISMACerca: PRISMACerca,
            DICerca: DICerca,
            laboratorio: laboratorio,
            totaloptica: totaloptica, fecha: hoy, nombrelente: nombrelente,
            ESFLejosizquierdo: ESFLejosizquierdo,
            CILLejosizquierdo: CILLejosizquierdo,
            EJELejosizquierdo: EJELejosizquierdo,
            PRISMALejosizquierdo: PRISMALejosizquierdo,
            DILejosizquierdo: DILejosizquierdo,
            ESFCercaizquierdo: ESFCercaizquierdo,
            CILCercaizquierdo: CILCercaizquierdo,
            EJECercaizquierdo: EJECercaizquierdo,
            PRISMACercaizquierdo: PRISMACercaizquierdo,
            DICercaizquierdo: DICercaizquierdo,
            totalinterno: totalinterno,
            fechaentrega: fechaentrega
        },
        success: function () {
            Materialize.toast('Guardado Exitoso', 1000, 'rounded');
            Cargartabla();
            limpardatosdeoptica();

        }, error: function () {
            Materialize.toast('Producto Fue Agregado', 1000, 'rounded');
        }
    });
});

function limpardatosdeoptica() {
    $('#nombrelente').val(" ");
    $('#LABORATORIO').val(" ");
    $('#ESFLejos').val(" ");
    $('#CILLejos').val(" ");
    $('#EJELejos').val(" ");
    $('#PRISMALejos').val(" ");
    $('#DILejos').val(" ");
    $('#ESFCerca').val(" ");
    $('#CILCerca').val(" ");
    $('#EJECerca').val(" ");
    $('#PRISMACerca').val(" ");
    $('#DICerca').val(" ");
    $('#ESFLejosizquierdo').val(" ");
    $('#CILLejosizquierdo').val(" ");
    $('#PRISMALejosizquierdo').val(" ");
    $('#DILejosizquierdo').val(" ");
    $('#ESFCercaizquierdo').val(" ");
    $('#CILCercaizquierdo').val(" ");
    $('#EJECercaizquierdo').val(" ");
    $('#PRISMACercaizquierdo').val(" ");
    $('#DICercaizquierdo').val(" ");
    $('#totaloptica').val("0");
    $('#totalinterno').val("0");
    document.getElementById("ORGANICO").checked = false;
    document.getElementById("POLICARBONATO").checked = false;
    document.getElementById("HIGHLITE").checked = false;
    document.getElementById("HIGHINDEX").checked = false;
    document.getElementById("VIDRIO").checked = false;
    document.getElementById("BLANCO").checked = false;
    document.getElementById("ANTIRREFLEX").checked = false;
    document.getElementById("FOTOCROMATICO").checked = false;
    document.getElementById("CONTINTE").checked = false;
    document.getElementById("SENCILLA").checked = false;
    document.getElementById("BIFOCAL").checked = false;
    document.getElementById("PROGRESIVO").checked = false;
    document.getElementById("FUTUREX").checked = false;
    $('#detalleoptica').show();
    $('#editardetalleoptica').hide();
}