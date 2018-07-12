$(document).ready(function () {
    Cargar();
     $("#descuento").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: 0
    });
     $("#descuentos").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: 0
    });
});
function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}
/*
 * Metodo para cargar la tabla con la lista de clientes sregistrados en el sistema
 */
function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarcliente/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var direccion = "";
            var telefonoFijo = "";
            var celular = "";
            var correo = "";
            var ciudad = "";
            var preferencias = "";
            if (value.direccion != null) {
                direccion = value.direccion;
            }
            if (value.telefonoFijo != null) {
                telefonoFijo = value.telefonoFijo;
            }
            if (value.celular != null) {
                celular = value.celular;
            }
            if (value.correo != null) {
                correo = value.correo;
            }
            if (value.ciudad != null) {
                ciudad = value.ciudad;
            }
            if (value.Preferencias != null) {
                preferencias = value.Preferencias;
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + direccion + "</td>" +
                    "<td>" + telefonoFijo + "</td>" +
                    "<td>" + celular + "</td>" +
                    "<td>" + correo + "</td>" +
                    "<td>" + ciudad + "</td>" +
                    "<td>" + value.tipocliente + "</td>" +
                    "<td>" + preferencias + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating' data-target='modal2' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

/*
 * Metodo para mostrar los datos de un cliente en un modal
 */
function Mostrar(btn) {
    var route = "/Cliente/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#id").val(res.id);
        $.get(route, function (res) {
            debugger
            $("#nombres").val(res.nombre);
            $("#direccions").val(res.direccion);
            $("#telefonoFijos").val(res.telefonoFijo);
            $("#correos").val(res.correo);
            $("#celulars").val(res.celular);
            $("#razonSocials").val(res.razonSocial);
            $("#NITs").val(res.NIT);
            $("#ciudads").val(res.idCiudad);
            $("#preferenciass").val(res.Preferencias);
            $('#tipoclientes option:selected').val(res.idTipoCliente);
            $('#tipoclientes').material_select();
            // var slider = $("#descuento").data("ionRangeSlider");
            /* slider.update({
            min: 0,
            max: 100,
            from: res.descuentoporcliente
                });*/ 
             var slider2 = $("#descuentos").data("ionRangeSlider");
            
        slider2.update({
            min: 0,
            max: 100,
            from: res.descuentoporcliente
        });
        });
    });
}
/*
 * Metodo para eliminar un cliente
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Cliente/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL CLIENTE?",
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
 * Metodo para modificar un cliente
 */
$("#actualizar").click(function () {
    debugger
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombres").val( );
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
            return;
        }
        var direccion = $("#direccions").val( );
        var telefonoFijo = $("#telefonoFijos").val( );
        var correo = $("#correos").val( );
        if (!correo || !correo.trim().length) {
        } else {
            if (!valEmail(correo)) {
                Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
                return false;
            }
        }
        var celular = $("#celulars").val( );
        var ciudad = $("#ciudads").val();
        if (!ciudad || !ciudad.trim().length) {
            Materialize.toast('CIUDAD VACIA', 2000, 'rounded');
            return;
        }
        var razonSocial = $("#razonSocials").val( );
        if (!razonSocial || !razonSocial.trim().length) {
            Materialize.toast('RAZON SOCIAL VACIA', 2000, 'rounded');
            return;
        }
        var NIT = parseInt($("#NITs").val()) + "";
        if (!NIT || !NIT.trim().length) {
            Materialize.toast('NIT VACIO', 2000, 'rounded');
            return;
        }
        var preferencias = $("#preferenciass").val();
        var tipocliente = $("#tipoclientes").val();
        debugger
        var descuentos = $("#descuentos").val();
         if ( descuentos>=99) {
            Materialize.toast('DESCUENTOS NO PUEDE APLICAR UN '+descuentos+'%', 2000, 'rounded');
            return;
        }
        debugger;
        var route = "/Cliente/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                direccion: direccion,
                telefonoFijo: telefonoFijo,
                correo: correo,
                celular: celular,
                razonSocial: razonSocial,
                NIT: NIT,
                idCiudad: ciudad,
                Preferencias: preferencias,
                idTipoCliente: tipocliente,
                idDescuento: 0,
                descuentoporcliente: descuentos
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal2").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ACTUALIZACION COMPLETA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                $("#modal2").closeModal();
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
 * Metodo para crear un nuevo cliente s
 */
$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        debugger;
        var nombre = $("#nombre").val( );
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
            return;
        }
        var direccion = $("#direccion").val( );
        var telefonoFijo = $("#telefonoFijo").val( );
        var correo = $("#correo").val( );
        if (!correo || !correo.trim().length) {
        } else {
            if (!valEmail(correo)) {
                Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
                return false;
            }
        }
        var celular = $("#celular").val( );
        var ciudad = $("#ciudad").val();
        if (!ciudad || !ciudad.trim().length) {
            Materialize.toast('CIUDAD VACIA', 2000, 'rounded');
            return;
        }
        var razonSocial = $("#razonSocial").val();
        if (!razonSocial || !razonSocial.trim().length) {
            Materialize.toast('RAZON SOCIAL VACIA', 2000, 'rounded');
            return;
        }
        var NIT = parseInt($("#NIT").val()) + "";
        if (!NIT || !NIT.trim().length) {
            Materialize.toast('NIT VACIO', 2000, 'rounded');
            return;
        }
        var preferencias = $("#preferencias").val();
        var tipocliente = $("#tipocliente").val( );
        var descuentos = $("#descuento").val();
        if (!descuentos || descuentos>=99) {
            Materialize.toast('DESCUENTOS NO PUEDE APLICAR UN '+descuentos+'%', 2000, 'rounded');
            return;
        }
        var route = "/Cliente";
        var token = $("#token").val();
        //return 0
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {nombre: nombre,
                direccion: direccion,
                telefonoFijo: telefonoFijo,
                correo: correo,
                celular: celular,
                razonSocial: razonSocial,
                NIT: NIT,
                idCiudad: ciudad,
                Preferencias: preferencias,
                idTipoCliente: tipocliente,
                idDescuento: 0,
                descuentoporcliente: descuentos,
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $(".lean-overlay").remove();
                $("#modal1").closeModal();
                swal({title: "CLIENTE CREADO EXITOSAMENTE",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                $("#nombre").val("");
                $("#direccion").val("");
                $("#telefonoFijo").val("");
                $("#correo").val("");
                $("#celular").val("");
                $("#razonSocial").val("");
                $("#NIT").val("");
            },
            error: function () {
                swal({title: "ERROR AL GUARDAR CLIENTES",
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
/*
 * Metodo para la paginacion de la tabla
 */
function paginador() {
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

function valEmail(valor) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(valor);
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}