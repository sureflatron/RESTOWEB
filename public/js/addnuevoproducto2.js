$(document).ready(function () {
    cargarbombo();
    cargarOrigen();
    cargarMarca();
});
/*
 * No se utiliza en el POS
 * @returns {undefined}
 */
function cargarbombo() {
    var route = "/TipoProducto/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#categoria').append(
                    '<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#categoria').material_select();
        });
    });
}
/*
 * Cargar el combo de Origen
 * @returns {undefined}
 */
function cargarOrigen() {
    var route = "/listaorigen/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#origen').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#origen').material_select();
        });
    });
}
/*
 * Cargar el combo de Marca
 * @returns {undefined}
 */
function cargarMarca() {
    var route = "/listamarca/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#marca').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#marca').material_select();
        });
    });
}

jQuery('#seleccionarImagen').on('change', function (e) {
    var Lector,
            oFileInput = this;
    if (oFileInput.files.length === 0) {
        return;
    }
    ;
    Lector = new FileReader();
    Lector.onloadend = function (e) {
        jQuery('#vistaPrevia').attr('src', e.target.result);
    };
    Lector.readAsDataURL(oFileInput.files[0]);
});
/*
 * Metodo para crear un producto
 */
$("#guardarvolver").click(function () {
    $("#Guardaryvolveresconder").hide();
    var idTipoProducto = $('#categoria').val();
    var nombre = $('#innombre').val();
    var descripcion = $('#indescripcion').val();
    var precioVenta = $('#inprecioVenta').val();
    var marca = $('#marca').val();
    var origen = $('#origen').val();
    var unidadesCaja = $('#inunidadesCaja').val();
    var stockMin = $('#instockMin').val();
    var stockMax = $('#instockMax').val();
    var imagen = jQuery('#vistaPrevia').attr('src');
    var precioVentaCredito = $("#inprecioVentaCredito").val();
    if (!idTipoProducto || !idTipoProducto.trim().length || idTipoProducto == "0") {
        $("#Guardaryvolveresconder").show();
        Materialize.toast('Tipo de Producto vacio', 1000, 'rounded');
        return;
    }
    if (!nombre || !nombre.trim().length) {
        $("#Guardaryvolveresconder").show();
        Materialize.toast('Nombre Vacio', 1000, 'rounded');
        return;
    }
    if (!descripcion || !descripcion.trim().length) {
        $("#Guardaryvolveresconder").show();
        Materialize.toast('Descripcion Vacia', 1000, 'rounded');
        return;
    }
    if (!precioVenta || !precioVenta.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Precio Venta Vacio', 1000, 'rounded');
    }
    if (!precioVentaCredito || !precioVentaCredito.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Precio Venta a Credito Vacio', 1000, 'rounded');
    }
    if (!origen || !origen.trim().length || origen == "0") {
        $("#Guardaryvolveresconder").show();
        Materialize.toast('Origen', 1000, 'rounded');
        return;
    }
    if (!marca || !marca.trim().length || marca == "0") {
        $("#Guardaryvolveresconder").show();
        Materialize.toast('MArca Vacia', 1000, 'rounded');
        return;
    }
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
    var ventadirecta = '';
    if (si) {
        ventadirecta = "0";
    } else {
        ventadirecta = "1";
    }
    var sistock = document.getElementById('test5').checked;
    var conStock = 0;
    if (!sistock) {
        conStock = 1;
    }
    var token = $("#token").val();
    var route = "/Producto";
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            precioVentaCredito: precioVentaCredito,
            conStock: conStock,
            idTipoProducto: idTipoProducto,
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
            $('#innombre').val("");
            $('#indescripcion').val("");
            $('#inprecioVenta').val("");
            $('#inprecioVentaCredito').val("");
            $('#incodInterno').val("");
            $('#incodBarra').val("");
            $('#inmaterial').val("");
            $('#incolor').val("");
            $('#inusado').val("NO");
            $('#intamano').val("");
            $("#inpeso").val("");
            $('#inunidadesCaja').val("0");
            $('#instockMin').val("2");
            $('#instockMax').val("2");
            $("#modelo").val("");
            $("#estilo").val("");
            $("#corte").val("");
            swal({title: "PRODUCTO CREADO",
                text: "Puede crear mas productos si lo desea",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 4000});
            $("#Guardaryvolveresconder").show();
        },
        error: function () {
            $("#Guardaryvolveresconder").show();
            swal({title: "ERROR AL CREAR PRODUCTO",
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