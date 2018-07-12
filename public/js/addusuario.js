$(document).ready(function () {
    Cargar();
    $('#show').click(function () {
        var checkbox = document.getElementById('show');
        var passField = document.getElementById('password');
        if (checkbox.checked == true) {
            passField.type = "text";
        } else {
            passField.type = "password";
        }
    });
    $("select#sucursal").change(function () {
        var sucursal = $(this).val();
        cargaralmacendesucursal(sucursal);
        $("#almacen").removeAttr("disabled");
    });
});
function Cargar() {
    var id = $("#idempleado").val();
    var route = "/listarusuario/" + id;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#actualizar").show();
            $("#guardar").hide();
            $("#usuario").val(value.nombreUsuario);
            $("#password").val(value.password);
            if (typeof value.perfil !== "undefined") {
                $('#perfil').val(value.perfil);
                $("#perfil").material_select();
            }
            $("#fechainicio").val(value.fechainicio);
            $("#fechafin").val(value.fechafin);
            if (value.urlFoto == null) {
            } else {
                $("#vistaPrevia2").attr("src", value.urlFoto);
            }
            if (typeof value.sucursal === "undefined") {
                $("#almacen").removeAttr("disabled");
                $("#guardar").show();
                $("#actualizar").hide();
            } else if (value.sucursal !== 'null') {
                $("#sucursal").val(value.sucursal);
                $('#sucursal option:selected').val(value.sucursal);
                $("#sucursal").material_select();
                $("#almacen").val(value.almacenpordefecto);
                $('#almacen option:selected').val(value.almacenpordefecto);
                $('#almacen').material_select();
                $("#almacen").removeAttr("disabled");
            }
            var multi = value.ventamultialmacen;
            if (typeof value.ventamultialmacen !== "undefined") {
                if (multi == "0") {
                    document.getElementById("test1").checked = true;
                    document.getElementById("test2").checked = false;
                } else {
                    document.getElementById("test1").checked = false;
                    document.getElementById("test2").checked = true;
                }
            }
            var cansell = value.puedevender;
            if (typeof value.puedevender !== "undefined") {
                if (cansell == "0") {
                    document.getElementById("test3").checked = true;
                    document.getElementById("test4").checked = false;
                } else {
                    document.getElementById("test3").checked = false;
                    document.getElementById("test4").checked = true;
                }
            }
        });
    });
    $("#guardar").show();
    $("#actualizar").hide();
}

$("#guardar").click(function () {
    var usuario = $("#usuario").val();
    if (!usuario || !usuario.trim().length) {
        return Materialize.toast('Nombre de Usuario Vacio', 2500, 'rounded');
    }
    var password = $("#password").val();
    if (!password || !password.trim().length) {
        return Materialize.toast('Password Vacia', 2500, 'rounded');
    }
    var fechainicio = $("#fechainicio").val();
    var fechafin = $("#fechafin").val();
    var perfils = $("#perfil").val();
    if (!perfils || !perfils.trim().length || perfils == "null") {
        return Materialize.toast('Perfil Vacio', 2500, 'rounded');
    }
    var sucursal = $("#sucursal").val();
    if (!sucursal || !sucursal.trim().length || sucursal == "null" || sucursal == "0") {
        return Materialize.toast('Sucursal Vacia', 2500, 'rounded');
    }
    var item = document.getElementById('test1').checked;
    var ventamultialmacen = '';
    if (item) {
        ventamultialmacen = 0;
    } else {
        ventamultialmacen = 1;
    }
    var almacen = $("#almacen").val();
    if (!almacen || !almacen.trim().length || almacen == "null" || almacen == "0" || almacen == "Seleccione un almacen por defecto") {
        return Materialize.toast('Seleccione Un Almacen', 2500, 'rounded');
    }
    var id = $("#idempleado").val();
    var imagen = jQuery('#vistaPrevia2').attr('src');
    var route = "/Usuario";
    var token = $("#token").val();
    var vent = document.getElementById('test3').checked;
    var cansell = '';
    if (vent) {
        cansell = 0;
    } else {
        cansell = 1;
    }
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            usuario: usuario,
            password: password,
            fechainicio: fechainicio,
            fechafin: fechafin,
            perfils: perfils,
            sucursal: sucursal,
            id: id,
            imagen: imagen,
            ventamultialmacen: ventamultialmacen,
            almacen: almacen,
            cansell: cansell
        },
        success: function () {
            swal({title: "USUARIO GUARDADO EXITOSAMENTE",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.href = "/Empleados";
        }, error: function () {
            swal({title: "ERROR AL GUARDAR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
$("#actualizar").click(function () {
    var usuario = $("#usuario").val();
    if (!usuario || !usuario.trim().length) {
        return Materialize.toast('Nombre de Usuario Vacio', 2500, 'rounded');
    }
    var password = $("#password").val();
    if (!password || !password.trim().length) {
        return Materialize.toast('Password Vacia', 2500, 'rounded');
    }
    var fechainicio = $("#fechainicio").val();
    var fechafin = $("#fechafin").val();
    var perfils = $("#perfil").val();
    if (!perfils || !perfils.trim().length || perfils == "null") {
        return Materialize.toast('Perfil Vacio', 2500, 'rounded');
    }
    var sucursal = $("#sucursal").val();
    var id = $("#idempleado").val();
    var imagen = jQuery('#vistaPrevia2').attr('src');
    if (!sucursal || !sucursal.trim().length || sucursal == "null" || sucursal == "0") {
        return Materialize.toast('Sucursal Vacia', 2500, 'rounded');
    }
    var item = document.getElementById('test1').checked;
    var ventamultialmacen = '';
    if (item) {
        ventamultialmacen = 0;
    } else {
        ventamultialmacen = 1;
    }
    var almacen = $("#almacen").val();
    if (!almacen || !almacen.trim().length || almacen == "null" || almacen == "0" || almacen == "Seleccione un almacen por defecto") {
        return Materialize.toast('Seleccione Un Almacen', 2500, 'rounded');
    }
    var route = "/Usuario/" + id + "";
    var token = $("#token").val();
    var vent = document.getElementById('test3').checked;
    var cansell = '';
    if (vent) {
        cansell = 0;
    } else {
        cansell = 1;
    }
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            usuario: usuario,
            password: password,
            fechainicio: fechainicio,
            fechafin: fechafin,
            perfils: perfils,
            sucursal: sucursal,
            id: id,
            imagen: imagen,
            ventamultialmacen: ventamultialmacen,
            almacen: almacen,
            cansell: cansell
        },
        success: function () {
            swal({title: "ACTUALIZACION COMPLETA",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.href = "/Empleados";
        }, error: function () {
            swal({title: "ERROR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
jQuery('#seleccionarImagen2').on('change', function (e) {
    var Lector,
            oFileInput = this;
    if (oFileInput.files.length === 0) {
        return;
    }
    Lector = new FileReader();
    Lector.onloadend = function (e) {
        jQuery('#vistaPrevia2').attr('src', e.target.result);
    };
    Lector.readAsDataURL(oFileInput.files[0]);
});
$("#actualizars").click(function () {
    var password = $("#passwordold").val();
    var passwordnew = $("#passwordnew").val();
    var passwordnewrepeat = $("#passwordnewrepeat").val();
    if (passwordnew.length == 0 || passwordnewrepeat.length == 0 || password.length == 0) {
        return swal({title: "Advertencia!",
            text: "Inserte todos los datos",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (passwordnew != passwordnewrepeat) {
        return swal({title: "Advertencia!",
            text: "Las nuevas contraseñas deben coincidir",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var id = $("#idempleado").val();
    var route = "/actualizarpassword";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            password: passwordnew,
            id: id,
            passwordold: password},
        success: function (rout) {
            $(rout).each(function (key, value) {
                if (value.mensaje == "Contraseña Actualizada") {
                    $("#passwordold").val("");
                    $("#passwordnew").val("");
                    $("#passwordnewrepeat").val("");
                    swal({title: "Bien!",
                        text: "Actualizacion Completa",
                        type: "success",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                } else {
                    swal({title: "Advertencia!",
                        text: value.mensaje,
                        type: "warning",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                }
            });
        }, error: function () {
            swal({title: "Error :(",
                text: "No se pudo actulizar su contraseña, intente nuevamente",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
function cargaralmacendesucursal(idpunto) {
    $('#almacen')
            .find('option')
            .remove()
            .end();
    var route = "/listaralmacendelasucursal/" + idpunto;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacen').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacen').material_select();
        });
    });
}
$("#cambiarimagen").click(function () {
    var id = $("#idempleado").val();
    var imagen = jQuery('#vistaPrevia2').attr('src');
    if (document.getElementById("seleccionarImagen2").files.length == 0) {
        return swal({text: "Seleccione la nueva imagen de perfil",
            title: "Advertencia",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 2000});
    }
    var route = "/cambiarimagen";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            imagen: imagen
        },
        success: function () {
            swal({title: "Bien!",
                text: "Imagen actualizada correctamente",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            jQuery('#imgperfil').attr('src', imagen);
        }, error: function () {
            swal({title: ":(",
                text: "Imagen muy pesada, porfavor seleccione otra",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
    });
});