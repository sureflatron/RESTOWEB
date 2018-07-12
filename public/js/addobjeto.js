$(document).ready(function () {
    Cargar();
    Cargarcombo();
});

function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarobjeto/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            var habilitados = "";
            var visible = "";
            if (value.habilitado == 1) {
                habilitados = "Habilidato";
            } else {
                habilitados = "No Habilitado";
            }
            if (value.visibleEnMenu == 1) {
                visible = "Visible";
            } else {
                visible = "No Visible";
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.tipoObjeto + "</td>" +
                    "<td>" + habilitados + "</td>" +
                    "<td>" + visible + "</td>" +
                    "<td>" + value.nommodulo + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#modal2' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

function Cargarcombo() {
    var route = "/listarmodulo/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            $('#modulo').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#modulo').material_select();
            $('#modulos').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#modulos').material_select();
        });
    });
}

function Mostrar(btn) {
    var route = "/Objeto/" + btn.value + "/edit";
    $.get(route, function (res) {
        var habilitado = res.habilitado;
        var visibleEnMenu = res.visibleEnMenu;
        if (habilitado == 1) {
            document.getElementById('test1s').checked = 1;
        }
        if (habilitado == 0) {
            document.getElementById('test2s').checked = 1;
        }
        if (visibleEnMenu == 1) {

            document.getElementById('test3s').checked = 1;
        }
        if (visibleEnMenu == 0) {
            document.getElementById('test4s').checked = 1;
        }
        $("#idactualizar").val(res.id);
        $("#nombres").val(res.nombre);
        $("#tipoObjetos").val(res.tipoObjeto);
        $("#urlObjetos").val(res.urlObjeto);
        $('#modulos').val(res.idModulo);
        $('#modulos').material_select();
    });
}

function limpiar() {
    $("#nombre").val("");
    $("#urlObjeto").val("");
    var si = document.getElementById('test1').checked;
    var no = document.getElementById('test2').checked;
    if (si == true) {
        si == false;
    }
    if (no == true) {
        no = false;
    } else {
        si = false;
    }
    var sii = document.getElementById('test3').checked;
    var noo = document.getElementById('test4').checked;
    if (sii == true) {
        sii == false;
    }
    if (noo == true) {
        noo = false;
    } else {
        sii == false;
    }
}

$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var habilitado = 0;
        var visibleEnMenu = 0;
        var si = document.getElementById('test1').checked;
        var no = document.getElementById('test2').checked;
        if (si == true) {
            habilitado = 0;
        }
        if (no == true) {
            habilitado = 0;
        } else {
            habilitado = 1;
        }
        var sii = document.getElementById('test3').checked;
        var noo = document.getElementById('test4').checked;
        if (sii == true) {
            visibleEnMenu = 0;
        }
        if (noo == true) {
            visibleEnMenu = 0;
        } else {
            visibleEnMenu = 1;
        }
        var nombre = $("#nombre").val();
        var tipoObjeto = $("#tipoObjeto").val();
        var urlObjeto = $("#urlObjeto").val();
        var modulo = $("#modulo").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('Campos vacios' + '  Nombre', 2500, 'rounded');
            return;
        }
        if (!tipoObjeto || !tipoObjeto.trim().length) {
            Materialize.toast('Campos vacios' + '  Tipo Objeto', 2500, 'rounded');
            return;
        }
        if (!urlObjeto || !urlObjeto.trim().length) {
            Materialize.toast('Campos vacios' + '  Url Objeto', 2500, 'rounded');
            return;
        }
        var route = "/Objeto";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                tipoObjeto: tipoObjeto,
                modulo: modulo,
                urlObjeto: urlObjeto,
                habilitado: habilitado,
                visibleEnMenu: visibleEnMenu
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $(".lean-overlay").remove();
                limpiar();
                $("#modal1").closeModal();
                swal({title: "GUARDADO EXITOSO",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});

            }, error: function () {
                swal({title: "ERROR AL GUARDAR",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA GUARDAR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});

function limpiar() {
    $("#nombre").val(" ");
    $("#tipoObjeto").val(" ");
    $("#urlObjeto").val(" ");
    $("#modulo").val(" ");
    document.getElementById('test1').checked = false;
    document.getElementById('test2').checked = false;
    document.getElementById('test3').checked = false;
    document.getElementById('test4').checked = false;
}

$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var habilitado = 0;
        var visibleEnMenu = 0;
        var si = document.getElementById('test1s').checked;
        var no = document.getElementById('test2s').checked;
        if (si == true) {
            habilitado = 1;
        }
        if (no == true) {
            habilitado = 0;
        }
        var sii = document.getElementById('test3s').checked;
        var noo = document.getElementById('test4s').checked;
        if (sii == true) {
            visibleEnMenu = 1;
        }
        if (noo == true) {
            visibleEnMenu = 0;
        }
        var nombre = $("#nombres").val();
        var tipoObjeto = $("#tipoObjetos").val();
        var urlObjeto = $("#urlObjetos").val();
        var modulo = $("#modulos").val();
        var value = $("#idactualizar").val();
        var route = "/Objeto/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                tipoObjeto: tipoObjeto,
                modulo: modulo,
                urlObjeto: urlObjeto,
                habilitado: habilitado,
                visibleEnMenu: visibleEnMenu
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal2").closeModal();
                $('.lean-overlay').remove();
                swal({title: "DATOS ACTUALIZADOS CORRECTAMENTE",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }, error: function () {
                swal({title: "ERROR AL GUARDAR",
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

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Objeto/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR OBJETO?",
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
        order: [0, 'asc'],
        responsive: true
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