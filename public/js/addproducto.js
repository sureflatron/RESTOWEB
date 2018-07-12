$(document).ready(function () {
    Cargar();
    cargarbombo();
    cargarOrigen();
    cargarMarca();    
    cargarSucursalVerdadero();
    cargarsubcategoria();
});


function cargarsubcategoria() {
    debugger
    var route = "/listarSubTipoProducto/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {

            $("#subcategoria").val(value.idsubcategoria);
            $('#subcategoria option:selected').val(value.idsubcategoria);
            $("#subcategoria").material_select();

            $('#subcategoria').append(
                    '<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#subcategoria').material_select();
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
            $('#subcategoria').find('option').remove().end().val('null');// esto vacis el select o el option 
            
           // $('#subcategoria').material_select();
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    debugger
                        if(value.id!=""){
                    idsubcategoria = value.id;
                    $('#subcategoria').append('<option  hidden value=' + value.id + ' >' + value.nombre + '</option>');
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
function openmodal(btn) {
    debugger
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
            var origen = "";
            if (value.origen != null) {
                origen = value.origen;
            }
            var color = "";
            if (value.color != null) {
                color = value.color;
            }
            var tamano = "";
            if (value.tamano != null) {
                tamano = value.tamano;
            }
            var marca = "";
            if (value.marca != null) {
                marca = value.marca;
            }
            var tipo = value.tipo;
            if (tipo == "combo") {
                tabladatos.append("<tr>" +
                        "<td>" + categoria + "</td>" +
                        "<td>" + codigoDeBarra + "</td>" +
                        "<td>" + codigoInterno + "</td>" +
                        "<td>" + nombre + "</td>" +
                        "<td>" + precioVenta + "</td>" +
                        "<td>" + origen + "</td>" +
                        "<td>" + color + "</td>" +
                        "<td>" + tamano + "</td>" +
                        "<td>" + marca + "</td>" +
                        "<td>" + value.tipo + "</td>" +
                        "<td><button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating' href='#' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                        "</td><td>" +
                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                        "</td>" +
                        "<td>" +
                        "<button  class='btn btn-floating'  value=" + value.id + " OnClick='redirigircomposicion(this);' title='Composicion'>" +
                        "<i class='material-icons'>view_list</i>" +
                        "</button>" +
                        "</td>" +
                        "</tr>");
            } else {
                tabladatos.append("<tr>" +
                        "<td>" + categoria + "</td>" +
                        "<td>" + codigoDeBarra + "</td>" +
                        "<td>" + codigoInterno + "</td>" +
                        "<td>" + nombre + "</td>" +
                        "<td>" + precioVenta + "</td>" +
                        "<td>" + origen + "</td>" +
                        "<td>" + color + "</td>" +
                        "<td>" + tamano + "</td>" +
                        "<td>" + marca + "</td>" +
                        "<td>" + value.tipo + "</td>" +
                        "<td><button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating' href='#' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                        "</td><td>" +
                        "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                        "</td>" +
                        "<td></td>" +
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
    var route = "/vistacombo/" + btn.value;
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
    debugger
    $("#categoria").material_select();
    var route = "/Producto/" + btn.value + "/edit";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger
            $("#categoria").val(value.idcategoria);
            $('#categoria option:selected').val(value.idcategoria);
            $("#categoria").material_select();            
            ////////////////////////////////////
            /*
            $("#subcategoria").val(value.idsubcategoria);
            $('#subcategoria option:selected').val(value.idsubcategoria);
            $("#subcategoria").material_select();
            */
            var tipo = value.tipo;
            if (tipo == "item") {
                document.getElementById("test1").checked = true;
            } else if (tipo == "servicio") {
                document.getElementById("test2").checked = true;
            } else {
                document.getElementById("test5").checked = true;
            }
            var conStock = value.conStock;
            if (conStock == 0) {
                document.getElementById("test3").checked = true;
            } else {
                document.getElementById("test4").checked = true;
            }
            $("#id").val(value.id);
            $("#nombre").val(value.nombre);
            $("#descripcion").val(value.descripcion);
            $("#codigoInterno").val(value.codigoInterno);
            $("#codigoBarra").val(value.codigoDeBarra);
            $("#material").val(value.material);
            $("#color").val(value.color);
            $("#usado").val(value.usado);
            $("#tamano").val(value.tamano);
            $("#peso").val(value.peso);
            $("#unidadesCaja").val(value.unidadesCaja);
            $("#stockMin").val(value.stockMin);
            $("#stockMax").val(value.stockMax);
            $("#precioVenta").val(value.precioVenta);
            $("#vistaPrevia").attr("src", value.imagen);
            $("#nombreimg").val("");
            $("#modelo").val(value.modelo);
            $("#estilo").val(value.estilo);
            $("#corte").val(value.corte);
            $("#precioVentaCredito").val(value.precioVentaCredito);
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
        var route = "/AddProducto/";
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
        var servicio = document.getElementById('test2').checked;
        var tipoproducto = '';
        if (item) {
            tipoproducto = 'item';
        } else if (servicio) {
            tipoproducto = 'servicio';
        } else {
            tipoproducto = "combo";
        }
        var idTipoProducto = $('#categoria').val();
        var id = $("#id").val();
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();
        var precioVenta = $("#precioVenta").val();
        var codigoInterno = $("#codigoInterno").val();
        var codigoBarra = $("#codigoBarra").val();
        var material = $("#material").val();
        var color = $("#color").val();
        var marca = $("#marca").val();
        var usado = $("#usado").val();
        //var tamano = $("#tamano").val();
         var tamano = "ninguno";
         var peso = "no";
        // var peso = $("#peso").val();
        var origen = $("#origen").val();
        var material = $("#material").val();
        var unidadesCaja = $("#unidadesCaja").val();
        var stockMax = $("#stockMax").val();
        var stockMin = $("#stockMin").val();
        var imagen = jQuery('#vistaPrevia').attr('src');
        var modelo = $("#modelo").val();        
        /*var estilo = $("#estilo").val();
        var corte = $("#corte").val();*/        
        var estilo = "dicobol_estilo";
        var corte = "dicobol_corte";
        debugger
        var costo_inventario=$("#costo_inventario").val();
        var costo_pedido    =$("#costo_pedido").val();
        var idSubTipoProducto = 1;
        
        var precioVentaCredito = $("#precioVentaCredito").val();
        if (!idTipoProducto || !idTipoProducto.trim().length || idTipoProducto == "0") {
            Materialize.toast('Tipo de Producto Vacio', 1000, 'rounded');
            return;
        }
        if (!codigoInterno || !codigoInterno.trim().length) {
            Materialize.toast('Codigo Interno Vacio', 1000, 'rounded');
            return;
        }
        if (!codigoBarra || !codigoBarra.trim().length) {
            Materialize.toast('Codigo de BarraVacio', 1000, 'rounded');
            return;
        }
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Nombre Vacio', 1000, 'rounded');
            return;
        }
        if (!descripcion || !descripcion.trim().length) {
            Materialize.toast('Descripcion Vacia', 1000, 'rounded');
            return;
        }
        if (!precioVenta || !precioVenta.trim().length) {
            Materialize.toast('Precio Venta Vacio', 1000, 'rounded');
            return;
        }
        if (!precioVentaCredito || !precioVentaCredito.trim().length) {
            return Materialize.toast('Precio Venta a Credito Vacio', 1000, 'rounded');
        }
        if (!origen || !origen.trim().length || origen == "0") {
            Materialize.toast('Origen Vacio', 1000, 'rounded');
            return;
        }
        if (!marca || !marca.trim().length || marca == "0") {
            Materialize.toast('Marca vacia', 1000, 'rounded');
            return;
        }
        if (!material || !material.trim().length) {
            Materialize.toast('Material vacio', 1000, 'rounded');
            return;
        }
        if (!color || !color.trim().length) {
            Materialize.toast('Color vacio', 1000, 'rounded');
            return;
        }
       /* if (!tamano || !tamano.trim().length) {
            Materialize.toast('Tamaño vacio', 1000, 'rounded');
            return;
        }*/
        var sistock = document.getElementById('test3').checked;
        var conStock = 0;
        if (!sistock) {
            conStock = 1;
        }
        var route = "/Producto/" + id + "";
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
                idSubTipoProducto:idSubTipoProducto,
                id: id,
                nombre: nombre,
                descripcion: descripcion,
                precioVenta: precioVenta,
                imagen: imagen,
                tipoproducto: tipoproducto,
                codigoInterno: codigoInterno,
                codigoDeBarra: codigoBarra,
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
        order: [1, 'desc'],
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