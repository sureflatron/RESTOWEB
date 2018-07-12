$(document).ready(function () {
    Cargar();
    cargarbombo();
    cargarOrigen();
    cargarMarca();
});

function Cargar() {
    debugger;
    var tabladatos = $('#datos');
    var route = "/listadeproducto/";
    $('#datos').empty();
    var nombres = "";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.tipo == "tipo") {
                tabladatos.append("<tr>" +
                        "<td>" + value.categoria + "</td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.precioVenta + "</td>" +
                        "<td>" + value.origen + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><button value=" + value.id + " OnClick='Mostrar(this);' class='waves-effect waves-light btn modal-trigger' href='#modal1' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                        "<button class='btn btn-danger' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                        "<button  class='btn'  value=" + value.id + " OnClick='redirigircomposicion(this);' title='Composicion'><i class='material-icons'>view_list</i></button></td>" +
                        "</tr>");
                $('.modal-trigger').leanModal();
            } else {
                tabladatos.append("<tr>" +
                        "<td>" + value.categoria + "</td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.precioVenta + "</td>" +
                        "<td>" + value.origen + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><button value=" + value.id + " OnClick='Mostrar(this);' class='waves-effect waves-light btn modal-trigger' href='#modal1' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                        "<button class='btn btn-danger' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                        "</tr>");
                $('.modal-trigger').leanModal();
            }
        });
        paginador();
    });
}

function redirigiringrediente(btn) {
    var route = "/vistaingrediente/" + btn.value;
    window.location.href = route;
    debugger;
}

function redirigircomposicion(btn) {
    var route = "/vistacomposicion/" + btn.value;
    window.location.href = route;
    debugger;
}

////no estoy utilizando 
function ActuaCompo(btn) {
    var route = "/listacomproducto/" + btn.value;
    $.get(route, function (res) {
        limpiarcampos();
        $(res).each(function (key, value) {
            debugger;
            $("#idcompo").val(value.idcomposicion);
            $("#productonombre").val(value.nombre);
            $("#cantidad").val(value.cantidad);
        });
    });
}

////----------------------------------------------------------
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
        debugger;
        $(res).each(function (key, value) {
            $('#origen').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#origen').material_select();
        });
    });
}

function cargarMarca() {
    var route = "/listamarca/";
    $.get(route, function (res) {
        debugger;
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
            var valor = $("#categoria").val(value.idcategoria);
            $('#categoria option:selected').val(value.idcategoria);
            $("#categoria").material_select();
            var escomida = value.escomida;
            var esingrediente = value.esingrediente;
            if (escomida == 1) {
                document.getElementById('test1').checked = 1;
            }
            if (escomida == 0) {
                document.getElementById('test2').checked = 1;
            }
            if (esingrediente == 1) {
                document.getElementById('test1s').checked = 1;
            }
            if (esingrediente == 0) {
                document.getElementById('test2s').checked = 1;
            }
            $("#id").val(value.id);
            $("#nombre").val(value.nombre);
            $("#descripcion").val(value.descripcion);
            $("#codigoInterno").val(value.codigoInterno);
            $("#codigoBarra").val(value.codigoDeBarra);
            $("#material").val(value.material);
            $("#color").val(value.material);
            $("#usado").val(value.usado);
            $("#tamano").val(value.tamano);
            $("#peso").val(value.peso);
            $("#unidadesCaja").val(value.unidadesCaja);
            $("#stockMin").val(value.stockMin);
            $("#stockMax").val(value.stockMax);
            $("#precioVenta").val(value.precioVenta);
            $("#vistaPrevia").attr("src", value.imagen);
            $("#nombreimg").val("");
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

function Eliminar(btn) {
    debugger;
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Producto/" + btn.value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'DELETE',
            dataType: 'json',
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                Materialize.toast('Eliminación completada', 1000);
            }
        });
    } else {
        Materialize.toast('No tiene permiso para Eliminar', 1000);
    }
}

$("#nuevoproducto").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var route = "/AddProducto/";
        window.location.href = route;
    } else {
        Materialize.toast('No tiene permiso para Guardar', 1000);
    }
});

$("#actualizar").click(function () {
    debugger;
    if ($("#perfilpuedeModificar").val() == 1) {
        var tipoproducto = 'item';
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
        var tamano = $("#tamano").val();
        var peso = $("#peso").val();
        var origen = $("#origen").val();
        var material = $("#material").val();
        var unidadesCaja = $("#unidadesCaja").val();
        var stockMax = $("#stockMax").val();
        var stockMin = $("#stockMin").val();
        var imagen = jQuery('#vistaPrevia').attr('src');
        var route = "/Producto/" + id + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                idTipoProducto: idTipoProducto,
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
                stockMax: stockMax
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal1").closeModal();
                Materialize.toast('Actualización completada', 1000);
            }
        });
    } else {
        Materialize.toast('No tiene permiso para Modificar', 1000);
    }
});

$("#actualizarComposicion").click(function () {
    debugger;
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

function Buscadordeproducto() {
    debugger;
    var tabladatos = $('#datos');
    var searchText = $('#buscar').val();
    if (searchText == "") {
        $('#tablacategoria').DataTable().destroy();
        Cargar();
        return;
    }
    var route = "/buscarProducto/" + searchText;
    $.get(route, function (res) {
        $('#datos').empty();
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.categoria + "</td>" +
                    "<td>" + value.codigoDeBarra + "</td>" +
                    "<td>" + value.codigoInterno + "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.precioVenta + "</td>" +
                    "<td>" + value.origen + "</td>" +
                    "<td>" + value.color + "</td>" +
                    "<td>" + value.tamano + "</td>" +
                    "<td>" + value.marca + "</td>" +
                    "<td><button value=" + value.id + " OnClick='Mostrar(this);' class='waves-effect waves-light btn modal-trigger' href='#modal1' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                    "<button class='btn btn-danger' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                    "<button  class='btn'  value=" + value.id + " OnClick='redirigircomposicion(this);' title='Composicion'><i class='material-icons'>view_list</i></button>" +
                    "</td>" +
                    "</tr>");
            $('.modal-trigger').leanModal();
        });
    });
    paginador();
}

function paginador() {
    debugger;
    $('#tablacategoria').DataTable({
        "pagingType": "full_numbers",
        retrieve: true,
    });
}