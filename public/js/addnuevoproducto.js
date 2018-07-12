var idcategoriaglobal=0;
$(document).ready(function () {
    
    
    cargarOrigen();
    cargarMarca();
    debugger
    cargarbombo();
    cargarSucursalVerdadero();
   // cargarsubcategoria();
});
/*
 * No se utiliza en el POS
 * @returns {undefined}
 */
function cargarbombo() {
    debugger
    var route = "/TipoProducto/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger            
                idcategoriaglobal=value.id;
                         
            $('#categoria').append(
                    '<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#categoria').material_select();
            
        });
        
    });    
}
//
/*function cargarsubcategoria() {
    debugger
    var route = "/listarSubTipoProducto/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#subcategoria').append(
                    '<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#subcategoria').material_select();
        });
    });
}*/
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

function cargarSucursalVerdadero() {  
    debugger;
    $("select#categoria").change(function () {
        var idcategoria = $(this).val();
        if (idcategoria !== "") {
            var idsubcategoria = "";
            var route = "/listarsubcategorias/" + idcategoria + "";
            $('#subcategoria')
                    .find('option')
                    .remove()
                    .end()
                    .val('null');
            $('#subcategoria').material_select();
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                        if(value.id!=""){
                    idsubcategoria = value.id;
                    $('#subcategoria').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
                    $('#subcategoria').material_select();                                                                             
                        }else 
                        {
                            $('#subcategoria')
                            .find('option')
                            .remove()
                            .end()
                            .append('<option value="null">Selecione una Subcategoria</option>')
                            .val('null');
                            $('#subcategoria').material_select();
                    
                        }
                    
                });
            });
        } else {
           // cargaralmacen();
        }
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
    var Lector, oFileInput = this;
    if (oFileInput.files.length === 0) {
        return;
    }
    Lector = new FileReader();
    Lector.onloadend = function (e) {
        jQuery('#vistaPrevia').attr('src', e.target.result);
    }
    Lector.readAsDataURL(oFileInput.files[0]);
});
/*
 * Metodo para crear un producto
 */
$("#guardarvolver").click(function () {
    debugger
    $("#Guardaryvolveresconder").hide();
    var idTipoProducto = $('#categoria').val();
   // var idSubTipoProducto = $('#subcategoria').val();
    var idSubTipoProducto = 1;
    
    var costo_inventario=$('#costo_inventario').val();
    var costo_pedido=    $('#costo_pedido').val();            
    
    debugger
    var nombre = $('#innombre').val();
    var descripcion = $('#indescripcion').val();
    var precioVenta = $('#inprecioVenta').val();
    var codInterno = $('#incodInterno').val();
    var codBarra = $('#incodBarra').val();
    var marca = $('#marca').val();
    var origen = $('#origen').val();
    var material = $('#inmaterial').val();
    var color = $('#incolor').val();
    var usado = $('#inusado').val();
    var tamano = "ninguno";
    var peso = "no";
   // var tamano = $('#intamano').val();
   // var peso = $("#inpeso").val();
    var unidadesCaja = $('#inunidadesCaja').val();
    var stockMin = $('#instockMin').val();
    var stockMax = $('#instockMax').val();
    var imagen = jQuery('#vistaPrevia').attr('src');
    var modelo = $("#modelo").val();
  /*  var estilo = $("#estilo").val();
    var corte = $("#corte").val();
    */
    var estilo = "dicobol_estilo";
    var corte =  "dicobol_corte";
    var precioVentaCredito = $("#inprecioVentaCredito").val();
    if (!idTipoProducto || !idTipoProducto.trim().length || idTipoProducto == "0") {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Tipo de Producto Vacio', 1000, 'rounded');
    }
    if (!codInterno || !codInterno.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Codigo Interno Vacio', 1000, 'rounded');
    }
    if (!codBarra || !codBarra.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Codigo de BarraVacio', 1000, 'rounded');
    }
    if (!nombre || !nombre.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Nombre Vacio', 1000, 'rounded');
    }
    if (!descripcion || !descripcion.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Descripcion Vacia', 1000, 'rounded');
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
        return Materialize.toast('Origen Vacio', 1000, 'rounded');
    }
    if (!marca || !marca.trim().length || marca == "0") {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Marca vacia', 1000, 'rounded');
    }
    if (!material || !material.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Material Vacio', 1000, 'rounded');
    }
    if (!color || !color.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Color Vacio', 1000, 'rounded');
    }
  /*  if (!tamano || !tamano.trim().length) {
        $("#Guardaryvolveresconder").show();
        return Materialize.toast('Tamaño vacio', 1000, 'rounded');
    }*/
    var item = document.getElementById('test1').checked;
    var servicio = document.getElementById('test2').checked;
    var tipoproducto = '';
    if (item) {
        tipoproducto = 'item';
    } else if (servicio) {
        tipoproducto = 'servicio';
    } else {
        tipoproducto = "combo";
    }
    var sistock = document.getElementById('test3').checked;
    var conStock = 0;
    if (!sistock) {
        conStock = 1;
    }
    var token = $("#token").val();
    var route = "/Producto";
    // return 0
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            precioVentaCredito: precioVentaCredito,
            conStock: conStock,
            idTipoProducto: idTipoProducto,
            idSubTipoProducto:idSubTipoProducto,
            nombre: nombre,
            descripcion: descripcion,
            precioVenta: precioVenta,
            imagen: imagen,
            tipoproducto: tipoproducto,
            codigoInterno: codInterno,
            codigoDeBarra: codBarra,
            idMarca: marca,
            idOrigen: origen,
            material: material,
            color: color,
            usado: usado,
            tamano: tamano,
            peso: peso,
            unidadesCaja: unidadesCaja,
            stockMin: stockMin,
            stockMax: stockMax,
            modelo: modelo,
            estilo: estilo,
            corte: corte,
            costo_inventario:costo_inventario,
            costo_pedido:costo_pedido
            
        },
        success: function () {
            $('#innombre').val("");
            $('#indescripcion').val("");
            $('#inprecioVenta').val("");
            $('#inprecioVentaCredito').val("");
            $('#intamano').val("");
            $('#inunidadesCaja').val("0");
            $('#instockMin').val("2");
            $('#instockMax').val("2");
            swal({title: "PRODUCTO CREADO",
                text: "Puede crear mas productos si lo desea",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2500});
            $("#Guardaryvolveresconder").show();
        },
        error: function () {
            swal({title: "ERROR AL CREAR PRODUCTO",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $("#Guardaryvolveresconder").show();
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