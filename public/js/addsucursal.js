$(document).ready(function () {
    Cargar();
});

function openmodal(btn) {
    $("#modal2").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/listarsucrusal/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.direccion.substring(0, 30) + "...</td>" +
                    "<td>" + value.telefono + "</td>" +
                    "<td>" + value.ciudad + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td></tr>");
        });
        paginador();
    });
}

function Mostrar(btn) {
    var route = "/Sucursal/" + btn.value + "/edit";
    $.get(route, function (res) {
        $("#idactualizar").val(res.id);
        $("#nombres").val(res.nombre);
        $("#telefonos").val(res.telefono);
        $("#dirrecions").val(res.direccion);
        $('#ciudads option:selected').val(res.idCiudad);
        $('#ciudads').material_select();
        $('#empresas option:selected').val(res.idEmpresa);
        $('#empresas').material_select();
    });
}

$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nombre = $("#nombre").val();
        var telefono = $("#telefono").val();
        var dirrecion = $("#dirrecion").val();
        var ciudad = $("#ciudad").val();
        var empresa = $("#empresa").val();
        if (!empresa || !empresa.trim().length || empresa == "0") {
            Materialize.toast('EMPRESA VACIA', 1000, 'rounded');
            return;
        }
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
        if (!dirrecion || !dirrecion.trim().length) {
            Materialize.toast('DIRECCION VACIA', 1000, 'rounded');
            return;
        }
        if (!ciudad || !ciudad.trim().length || ciudad == "0") {
            Materialize.toast('CIUDAD VACIA', 1000, 'rounded');
            return;
        }
        var route = "/Sucursal";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                nombre: nombre,
                telefono: telefono,
                dirrecion: dirrecion,
                ciudad: ciudad,
                empresa: empresa
            },
            success: function () {
                $('#tablacategoria').DataTable().destroy();
                Cargar();
                $("#modal1").closeModal();
                $("#nombre").val(" ");
                $(".lean-overlay").remove();
                swal({title: "SUCURSAL GUARDADA EXITOSAMENTE",
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

$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#idactualizar").val();
        var nombre = $("#nombres").val();
        var telefono = $("#telefonos").val();
        var dirrecion = $("#dirrecions").val();
        var ciudad = $("#ciudads").val();
        var empresa = $("#empresas").val();
        if (!empresa || !empresa.trim().length || empresa == "0") {
            Materialize.toast('EMPRESA VACIA', 1000, 'rounded');
            return;
        }
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
        if (!dirrecion || !dirrecion.trim().length) {
            Materialize.toast('DIRECCION VACIA', 1000, 'rounded');
            return;
        }
        if (!ciudad || !ciudad.trim().length || ciudad == "0") {
            Materialize.toast('CIUDAD VACIA', 1000, 'rounded');
            return;
        }
        var route = "/Sucursal/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                nombre: nombre,
                telefono: telefono,
                dirrecion: dirrecion,
                ciudad: ciudad,
                empresa: empresa
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
            }, error: function () {
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

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Sucursal/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR LA SUCURSAL?",
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


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}