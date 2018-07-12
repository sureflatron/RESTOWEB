var passworUsuario;
$(document).ready(function () {
    Cargar();
});


function Cargar() {
    var id = $("#idempleado").val();
    var route = "/listarusuario/" + id;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            passworUsuario = value.password;
        });
    });
}

$("#resetear").click(function () {
    var password1 = $("#password1").val();
    var passwordval2 = $("#password2").val();
    var passwordval3 = $("#password3").val();

    if (password1 === "") {
        swal({title: "Escriba su Contraseña Actual",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
        return false;
    }

    if (passwordval2 === "") {
        swal({title: "Escriba su nueva contraseña",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
        return false;
    }

    if (passwordval3 === "") {
        swal({title: "Repita su nueva contraseña",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
        return false;
    }

    if (passwordval2 != passwordval3) {
        swal({title: "LAS CONTRASEÑAS DEBEN COINCIDIR",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
        return false;
    } else {
        var id = $("#idempleado").val();

        var route = "/actualizarcontrasenia";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                password: passwordval3,
                passwordAntigua: password1,
                id: id
            },
            success: function (json) {
                $("#password1").val("");
                $("#password2").val("");
                $("#password3").val("");
                swal({title: "Contraseña actualizada",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
                window.location.href = "/index";
            },
            error: function (json) {
                swal({title: "CONTRASEÑA ACTUAL INCORRECTA!!",
                    text: json.message,
                    type: "error",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
        });
    }
});