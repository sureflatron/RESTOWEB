$(document).ready(function () {
    Cargar();
    cargarselectcargo();
    $("#descuento").ionRangeSlider({
        grid: true,
        min: 0,
        max: 100,
        from: 0
    });
});

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

function cargarselectcargo() {
    var route = "/listarcargo/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#Cargo').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#Cargo').material_select();
        });
    });
    var router2 = "/listaturno/";
    $.get(router2, function (rest) {
        $(rest).each(function (key, value) {
            $('#turno').append('<option value=' + value.id + ' selected>' + value.nombre + '</option>');
            $('#turno').material_select();

        });
    });
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarempleados/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.genero + "</td>" +
                    "<td>" + value.docIdentidad + "</td>" +
                    "<td>" + value.cargo + "</td>" +
                    "<td>" + value.comision + "% </td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='usuario(this);' title='Asignar usuario'>" +
                    "<i class='material-icons'>assignment_ind</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

function usuario(btn) {
    var route = "/Gestionarusuario/" + btn.value;
    window.location.href = route;
}

function nuevoempleado() {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var route = "/AddEmpleado/";
        window.location.href = route;
    } else {
        swal({title: "NO TIENE PERMISO PARA CREAR EMPRESA",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
}

function Mostrar(btn) {
    var route = "/Empleado/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#id").val(res.id);
        $("#nombre").val(res.nombre);
        $('#fechanacimiento').val(res.fechaNacimiento);
        $('#Docidentidad').val(res.docIdentidad);
        $('#telefonofijo').val(res.telefonoFijo);
        $('#celular').val(res.celular);
        $('#correo').val(res.correoElectronico);
        $('#genero').val(res.genero);
        $('#Cargo').val(res.idCargo);
        $('#Cargo').material_select();
        $('#turno').val(res.idTurno);
        $('#turno').material_select();
        var slider = $("#descuento").data("ionRangeSlider");
        slider.update({
            min: 0,
            max: 100,
            from: res.comision
        });
    });
}

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Empleado/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL EMPLEADO?",
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

$("#guardar").click(function () {
    var route = "/Empleado";
    var token = $("#token").val();
    var nombre = $('#nombre').val();
    var fechaNacimiento = $('#fechanacimiento').val();
    var docIdentidad = $('#Docidentidad').val();
    var telefonoFijo = $('#telefonofijo').val();
    var celular = $('#celular').val();
    var correoElectronico = $('#correo').val();
    var descuento = $("#descuento").val();
    var genero = $('#genero').val();
    var idCargo = $('#Cargo').val();
    var idTurno = $('#turno').val();
    $('input#nombre,input#Docidentidad,input#celular,input#correo').characterCounter();
    if (!nombre || !nombre.trim().length) {
        Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
        return;
    }
    if (!fechaNacimiento || !fechaNacimiento.trim().length) {
        Materialize.toast('FECHA DE NACIMIENTO VACIO', 1000, 'rounded');
        return;
    }
    if (!docIdentidad || !docIdentidad.trim().length) {
        Materialize.toast('DOCUMENTO DE IDENTIDAD VACIO', 1000, 'rounded');
        return;
    }

    if (!celular || !celular.trim().length) {
        Materialize.toast('CELULAR VACIO', 1000, 'rounded');
        return;
    }
    if (!correoElectronico || !correoElectronico.trim().length) {
        Materialize.toast('CORREO ELECTRONICO VACIO', 1000, 'rounded');
        return;
    }
    if (!valEmail(correoElectronico)) {
        Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
        return false;
    }
    if (!idCargo || !idCargo.trim().length || idCargo == "0") {
        Materialize.toast('CARGO VACIO', 1000, 'rounded');
        return;
    }
    if (!idTurno || !idTurno.trim().length || idTurno == "0") {
        Materialize.toast('TURNO VACIO', 1000, 'rounded');
        return;
    }
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            nombre: nombre,
            fechaNacimiento: fechaNacimiento,
            genero: genero,
            telefonoFijo: telefonoFijo,
            celular: celular,
            docIdentidad: docIdentidad,
            correoElectronico: correoElectronico,
            idCargo: idCargo,
            idTurno: idTurno,
            comision: descuento
        },
        success: function () {
            var route = "/Empleados";
            swal({title: "EMPLEADO GUARDADO EXITOSAMENTE",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.href = route;
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
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombre").val();
        var fechaNacimiento = $('#fechanacimiento').val();
        var docIdentidad = $('#Docidentidad').val();
        var telefonoFijo = $('#telefonofijo').val();
        var celular = $('#celular').val();
        var correoElectronico = $('#correo').val();
        var descuento = $("#descuento").val();
        var genero = $('#genero').val();
        var idCargo = $('#Cargo').val();
        var idTurno = $('#turno').val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
        if (!fechaNacimiento || !fechaNacimiento.trim().length) {
            Materialize.toast('FECHA DE NACIMIENTO VACIO', 1000, 'rounded');
            return;
        }
        if (!docIdentidad || !docIdentidad.trim().length) {
            Materialize.toast('DOCUMENTO DE IDENTIDAD VACIO', 1000, 'rounded');
            return;
        }

        if (!celular || !celular.trim().length) {
            Materialize.toast('CELULAR VACIO', 1000, 'rounded');
            return;
        }
        if (!correoElectronico || !correoElectronico.trim().length) {
            Materialize.toast('CORREO ELECTRONICO VACIO', 1000, 'rounded');
            return;
        }
        if (!valEmail(correoElectronico)) {
            Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
            return false;
        }
        if (!idCargo || !idCargo.trim().length || idCargo == "0") {
            Materialize.toast('CARGO VACIO', 1000, 'rounded');
            return;
        }
        if (!idTurno || !idTurno.trim().length || idTurno == "0") {
            Materialize.toast('TURNO VACIO', 1000, 'rounded');
            return;
        }
        var route = "/Empleado/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                fechaNacimiento: fechaNacimiento,
                genero: genero,
                telefonoFijo: telefonoFijo,
                celular: celular,
                docIdentidad: docIdentidad,
                correoElectronico: correoElectronico,
                idCargo: idCargo,
                idTurno: idTurno,
                comision: descuento
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal1").closeModal();
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

function valEmail(valor) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(valor);
}

function cargaralmacendesucursal(idpunto) {
    $('#almacen')
            .find('option')
            .remove()
            .end()
            .append('<option value="null">Selecione un Almacen por Defecto</option>')
            .val('null');
    $('#almacen').material_select();
    debugger;
    var route = "/listaralmacendelasucursal/" + idpunto;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacen').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacen').material_select();
        });
    });
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}