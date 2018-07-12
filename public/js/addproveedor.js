$(document).ready(function () {
    Cargar();
});

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

/*
 * Metodo para ver lalista de proveedores
 */
function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarproveedor/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.direccion + "</td>" +
                    "<td>" + value.telefono + "</td>" +
                    "<td>" + value.contactoRef + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
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
 * Metodo para abrir la interfaz para poder crear un proveedor
 */
$("#nuevoprovedor").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var route = "/Addproveedor";
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
 * Metodo para mostrar los datos de un proveedor en un modal
 */
function Mostrar(btn) {
    var route = "/Proveedor/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#id").val(res.id);
        $("#nombre").val(res.nombre);
        $("#direccion").val(res.direccion);
        $("#telefono").val(res.telefono);
        $("#paginaWeb").val(res.paginaWeb);
        $("#contactoRef").val(res.contactoRef);
        $("#telefonoContacto").val(res.telefonoContacto);
        $("#correoContato").val(res.correoContato);
        $("#idCiudad").val(res.idCiudad);
        $('#idCiudad option:selected').val(res.idCiudad);
        $('#idCiudad').material_select();
    });
}
/*
 * Metodo para eliminar un proveedor
 */
function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Proveedor/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR EL PROVEEDOR?",
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
 * Metodo para guardar un proveedor en la DB
 */
$("#guardar").click(function () {
    var nombre = $("#nombre").val();
    var direccion = $("#direccion").val();
    var telefono = $("#telefono").val();
    var paginaWeb = $("#paginaWeb").val();
    var contactoRef = $("#contactoRef").val();
    var telefonoContacto = $("#telefonoContacto").val();
    var correoContato = $("#correoContato").val();
    if (!correoContato || !correoContato.trim().length) {
    } else {
        if (!valEmail(correoContato)) {
            Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
            return false;
        }
    }
    var idCiudad = $("#idCiudad").val();
    if (!nombre || !nombre.trim().length) {
        Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
        return;
    }
    if (!direccion || !direccion.trim().length) {
        Materialize.toast('DIRECCION VACIA', 2000, 'rounded');
        return;
    }
    if (!contactoRef || !contactoRef.trim().length) {
        Materialize.toast('CONTACTO DE REFERENCIA VACIO', 2000, 'rounded');
        return;
    }
    if (!telefonoContacto || !telefonoContacto.trim().length) {
        Materialize.toast('TELEFONO DE CONTACTO VACIO', 2000, 'rounded');
        return;
    }
    if (!idCiudad || !idCiudad.trim().length || idCiudad == "0") {
        Materialize.toast('CIUDAD VACIA', 2000, 'rounded');
        return;
    }
    var route = "/Proveedor";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            nombre: nombre,
            direccion: direccion,
            telefono: telefono,
            paginaWeb: paginaWeb,
            contactoRef: contactoRef,
            telefonoContacto: telefonoContacto,
            correoContato: correoContato,
            idCiudad: idCiudad
        },
        success: function () {
            $('#tablacategoria').DataTable().destroy();
            Cargar();
            $("#nombre").val(" ");
            $("#direccion").val(" ");
            $("#telefono").val(" ");
            $("#paginaWeb").val(" ");
            $("#contactoRef").val(" ");
            $("#telefonoContacto").val(" ");
            $("#correoContato").val(" ");
            swal({title: "GUARDADO EXITOSO",
                type: "success",
                text: "Puede crear nuevos proveedores si lo desea.",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2500});
        }, error: function () {
            swal({title: "ERROR AL GUARDAR PROVEEDOR",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});
/*
 * Metodo para actualizar un proveedor
 */
$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var nombre = $("#nombre").val();
        var direccion = $("#direccion").val();
        var telefono = $("#telefono").val();
        var paginaWeb = $("#paginaWeb").val();
        var contactoRef = $("#contactoRef").val();
        var telefonoContacto = $("#telefonoContacto").val();
        var correoContato = $("#correoContato").val();
        if (!correoContato || !correoContato.trim().length) {
        } else {
            if (!valEmail(correoContato)) {
                Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
                return false;
            }
        }
        var idCiudad = $("#idCiudad").val();
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 2000, 'rounded');
            return;
        }
        if (!direccion || !direccion.trim().length) {
            Materialize.toast('DIRECCION VACIA', 2000, 'rounded');
            return;
        }
        if (!contactoRef || !contactoRef.trim().length) {
            Materialize.toast('CONTACTO DE REFERENCIA VACIO', 2000, 'rounded');
            return;
        }
        if (!telefonoContacto || !telefonoContacto.trim().length) {
            Materialize.toast('TELEFONO DE CONTACTO VACIO', 2000, 'rounded');
            return;
        }
        if (!idCiudad || !idCiudad.trim().length || idCiudad == "0") {
            Materialize.toast('CIUDAD VACIA', 2000, 'rounded');
            return;
        }
        var route = "/Proveedor/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                direccion: direccion,
                telefono: telefono,
                paginaWeb: paginaWeb,
                contactoRef: contactoRef,
                telefonoContacto: telefonoContacto,
                correoContato: correoContato,
                idCiudad: idCiudad
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
                swal({title: "ERRO AL ACTUALIZAR",
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
 * Paginador de la tabla de lista
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
        order: [0, 'desc'],
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