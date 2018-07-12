$(document).ready(function () {
    Cargar();
    $('.materialboxed').materialbox();
});

var cont = 0;

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

function Cargar() {
    var route = "/listarEmpresa/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var stock = "";
            if (value.venderSinStock == 0) {
                stock = "SI";
            } else if (value.venderSinStock == 1) {
                stock = "NO";
            }
            var actividad = value.actividad;
            var web = value.web;
            $("#logo").attr("src", value.imagen);
            $("#nombre-empresa").text(value.nombre);
            $('#actividad2').text(actividad);
            $("#propietario2").text(value.propietario);
            $("#email2").text(value.correo);
            $("#web2").text(web);
            $("#stock2").text(stock);
            $("#botoneditar").val(value.id);
        });
    });
}
//actividad  propietario 
function Mostrar(btn) {
    var route = "/Empresa/" + btn.value + "/edit";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#nombre").val(value.nombre);
            $("#web").val(value.web);
            $("#actividad").val(value.actividad);
            $("#propietario").val(value.propietario);
            $("#correo").val(value.correo);
            var stock = value.venderSinStock;
            if (stock == 0) {
                document.getElementById("test1").checked = true;
            } else {
                document.getElementById("test2").checked = true;
            }
            $("#vistaPrevia2").attr("src", value.imagen);
            $("#nombreimg2").val("");
            $("#id").val(value.id);
        });
    });
}

$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombre").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
        var web = $("#web").val();
        var correo = $("#correo").val();
        if (!correo || !correo.trim().length) {
        } else {
            if (!valEmail(correo)) {
                Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
                return false;
            }
        }
        var actividad = $("#actividad").val();
        var propietario = $("#propietario").val();
        var item = document.getElementById('test1').checked;
        debugger;
        var venderSinStock = 0;
        if (item) {
            venderSinStock = 0;
        } else {
            venderSinStock = 1;
        }
        var imagen = jQuery('#vistaPrevia2').attr('src');
        var route = "/Empresa/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                imagen: imagen,
                web: web,
                correo: correo,
                actividad: actividad,
                propietario: propietario,
                venderSinStock: venderSinStock
            },
            success: function () {
                swal({title: "ACTUALIZACION COMPLETA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal1").closeModal();
                $('.lean-overlay').remove();
            },
            error: function () {
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
});

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

jQuery('#seleccionarImagen2').on('change', function (e) {
    var Lector,
            oFileInput = this;
    if (oFileInput.files.length === 0) {
        return;
    }
    ;
    Lector = new FileReader();
    Lector.onloadend = function (e) {
        jQuery('#vistaPrevia2').attr('src', e.target.result);
    };
    Lector.readAsDataURL(oFileInput.files[0]);
});

$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#nombres").val();
        var web = $("#webs").val();
        var correo = $("#correos").val();
        if (!correo || !correo.trim().length) {
        } else {
            if (!valEmail(correo)) {
                Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
                return false;
            }
        }
        var actividad = $("#actividads").val();
        var propietario = $("#propietarios").val();
        var item = document.getElementById('test3').checked;
        debugger;
        var venderSinStock = 0;
        if (item) {
            venderSinStock = 0;
        } else {
            venderSinStock = 1;
        }
        var imagen = jQuery('#vistaPrevia').attr('src');
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
        var route = "/Empresa";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                imagen: imagen,
                web: web,
                correo: correo,
                actividad: actividad,
                propietario: propietario,
                venderSinStock: venderSinStock
            },
            success: function () {
                swal({title: "EMPRESA GUARDADA EXITOSAMENTE",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                $("#esteeselnombre").val("");
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                limpiar();
                $("#modal2").closeModal();
                $(".lean-overlay").remove();
            },
            error: function () {
                swal({title: "ERROR AL GUARDAR",
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    } else {
        swal({title: "NO TIENE PERMISO PARA CREAR EMPRESA",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
});

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Empresa/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR LA EMPRESA?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminarla!",
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
                                swal({title: "OPERACION COMPLETA",
                                    type: "success",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
                                $('#tablacategoria').DataTable().destroy();
                                Cargar();
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

function limpiar() {
    $("#nombres").val("");
    $("#webs").val("");
    $("#correos").val("");
    $("#actividads").val("");
    $("#propietarios").val("");
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
        order: [1, 'asc'],
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


function valEmail(valor) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(valor);
}