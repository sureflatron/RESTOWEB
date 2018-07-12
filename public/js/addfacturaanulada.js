var iddelpuntoventa;
var idempleado;
var perfil;
var sucursal;
$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    perfil = $("#iddelperfil").val();
    sucursal = $("#sucursalnombre").text();
    debugger;
    var hoy = new Date();
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
    $("#fecha").val(hoy);
    Cargar(hoy);
    $('#nit').formatter({
        'pattern': '{{999999999999999}}',
        'persistent': true
    });

    $("#fecha").change(function () {
        $('#tablacategoria').DataTable().destroy();
        Cargar($("#fecha").val());
    });
});

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

/*
 * Metodo para cargar la tabla con los detalles de la factura
 * @returns {undefined}
 */
function Cargar(fecha) {
    $("#listado").hide();
    $("#cargando").show();
    var tabladatos = $('#datos');
    $('#datos').empty();
    var route = "/listarfacturas/";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {
            fecha: fecha,
            sucursal: sucursal,
            puntoventa: iddelpuntoventa
        },
        success: function (res) {
            $(res).each(function (key, value) {
                tabladatos.append("<tr>" +
                        "<td>" + value.idVenta + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.fecha + "</td>" +
                        "<td>" + value.nroFactura + "</td>" +
                        "<td>" + value.razonSocial + "</td>" +
                        "<td>" + value.NIT + "</td>" +
                        "<td>" + value.total + "</td>" +
                        "<td>" +
                        "<button OnClick='editarfactura(" + value.id + ");' class='waves-effect waves-light btn btn-floating' href='#' title='Editar'>" +
                        "<i class='material-icons'>edit</i>" +
                        "</button>" +
                        "</td><td>" +
                        "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating' href='#' title='Anular'>" +
                        "<i class='material-icons'>delete</i>" +
                        "</button>" +
                        "</td><td>" +
                        "<button OnClick='imprimirfactura(" + value.id + ");' class='btn btn-floating' title='ImprimirFactura'>" +
                        "<i class='material-icons'>print</i>" +
                        "</button>" +
                        "</td><td>" +
                        "<button OnClick='comanda(" + value.idVenta + ");' class='btn btn-floating' title='comanda'>" +
                        "<i class='material-icons'>assignment</i>" +
                        "</button>" +
                        "</td></tr>");
            });
            paginador();
            $("#listado").show();
            $("#cargando").hide();
        },
        error: function () {
            swal({title: "ERROR AL CARGAR LAS FACTURAS",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}
/*
 * Metodo para imprimir una factura
 * @param {type} $id ->ID de la venta para mostrar factura
 * @returns {undefined}
 */
function imprimirfactura($id) {
    if ($("#perfilpuedeImprimir").val() == 1) {
        window.open("/imprirfactura/" + $id);
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Mostrar el modal para insertar el motivo por el que se va a anular un afactura
 * @param {type} btn -> Id de la factura a anular
 * @returns {undefined}
 */
function Mostrar(btn) {
    $("#id").val(btn.value);
}
/*
 * Metodo para imprimir la comanda
 * @param {type} $id -> ID de la venta para imprimir la comanda
 * @returns {undefined}
 */
function comanda($id) {
    if ($("#perfilpuedeImprimir").val() == 1) {
        window.open("/imprircomanda/" + $id);
    } else {
        swal({title: "NO TIENE PERMISO PARA IMPRIMIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}
/*
 * Metodo para abrir el modal y editar una factura
 * @param {type} $id -> ID de la factura a editar
 * @returns {undefined}
 */
function editarfactura($id) {
    var route = "/editarfactura/" + $id;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#ideditar").val(value.id);
            $("#nit").val(value.NIT);
            $("#razonsocial").val(value.razonSocial);
            $("#modal2").openModal();
        });
    });
}
/*
 * Metodo para editar una factiura
 * @param {type} param
 */
$("#editar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var idfactura = $("#ideditar").val();
        var nit = $("#nit").val();
        if (!nit || !nit.trim().length) {
            Materialize.toast('NIT VACIO', 2000, 'rounded');
            return;
        }
        var razonsocial = $("#razonsocial").val();
        if (!razonsocial || !razonsocial.trim().length) {
            Materialize.toast('RAZON SOCIAL VACIA', 2000, 'rounded');
            return;
        }
        var route = "/editarfacturaventa";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {idfactura: idfactura, nit: nit, razonsocial: razonsocial},
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar($("#fecha").val());
                $("#modal2").closeModal();
                swal({title: "FACTURA ACTUALIZADA CORRECTAMENTE",
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
/*
 * Metodo para anular una factura
 */
$("#anular").click(function () {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var idfactura = $("#id").val();
        var motivo = $("#motivo").val();
        if (!motivo || !motivo.trim().length) {
            Materialize.toast('EL MOTIVO ES OBLIGATORIO PARA ANULAR LA FACTURA', 2000, 'rounded');
            return;
        }
        var route = "/anularfacturaventa";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {idfactura: idfactura, motivo: motivo},
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar($("#fecha").val());
                $("#modal1").closeModal();
                $("#motivo").val("");
                swal({title: "FACTURA ANULADA CORRECTAMENTE",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 2000});
            }, error: function () {
                swal({title: "ERROR AL ANULAR LA FACTURA, Intentelo nuevamente",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 2000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA ELIMINAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
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
        order: [3, 'desc'],
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