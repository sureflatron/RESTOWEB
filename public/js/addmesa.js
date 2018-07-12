$(document).ready(function () {
    Cargar();

});

function openmodal(id) {
    $("#modal2").openModal();
    Mostrar(id);
}

function Cargar() {
    var tabladatos = $('#datos');
    debugger;
    var route = "/listarmesa/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var estado = value.estado;
            var estadofinal;
            if (estado == 0) {
                estadofinal = "Libre";
            }
            if (estado == 1) {
                estadofinal = "Reservada";
            }
            if (estado == 2) {
                estadofinal = "Ocupada";
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.id + "</td>" +
                    "<td>" + value.numeromesa + "</td>" +
                    "<td>" + value.ubicacion + "</td>" +
                    "<td>" + value.capacidad + "</td>" +
                    "<td>" + estadofinal + "</td>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</tr>");

        });
        paginador();
    });
}


function Mostrar(btn) {
    var route = "/Mesa/" + btn.value + "/edit";
    $.get(route, function (res) {
        debugger;
        var estado = res.estado;
        if (estado == 0) {
            document.getElementById('test0s').checked = 1;
        }
        if (estado == 1) {
            document.getElementById('test1s').checked = 1;
        }
        if (estado == 2) {
            document.getElementById('test2s').checked = 1;
        }
        $("#id").val(res.id);
        $("#nummesas").val(res.numeromesa);
        $("#ubicacions").val(res.ubicacion);
        $("#capacidads").val(res.capacidad);
        $("#sucursals").val(res.idSucual);
        $('#sucursals option:selected').val(res.idSucual);
        $("#sucursals").material_select();
    });
}


$("#guardar").click(function () {
    if ($("#perfilpuedeGuardar").val() == 1) {
        var nummesa = $("#nummesa").val();
        var ubicacion = $("#ubicacion").val();
        var capacidad = $("#capacidad").val();
        var sucursal = $("#sucursal").val();

        var estado;
        var libre = document.getElementById('test0').checked;
        var reservado = document.getElementById('test1').checked;
        var ocupado = document.getElementById('test2').checked;
        if (libre == true) {
            estado = 0;
        }
        if (reservado == true) {
            estado = 1;
        }
        if (ocupado == true) {
            estado = 2;
        }
        if (!nummesa || !nummesa.trim().length) {
            Materialize.toast('Campos vacios' + nummesa, 2500, 'rounded');
            return;
        }
        var route = "/Mesa";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {
                estado: estado,
                sucursal: sucursal,
                nummesa: nummesa,
                capacidad: capacidad,
                ubicacion: ubicacion
            },
            success: function () {
                Cargar();
                $("#nummesa").val("");
                $("#ubicacion").val("");
                $("#capacidad").val("");
                $("#modal1").closeModal();
                $('.lean-overlay').remove();
                swal({title: "GUARDADO EXITOSO",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }, error: function () {
                swal({title: "YA EXISTE MESA",
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

function Eliminar(btn) {
    if ($("#perfilpuedeEliminar").val() == 1) {
        var route = "/Mesa/" + btn.value + "";
        var token = $("#token").val();
        swal({title: "ESTA SEGURO QUE DESEA ELIMINAR LA MESA?",
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


$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var nummesa = $("#nummesas").val();
        var ubicacion = $("#ubicacions").val();
        var capacidad = $("#capacidads").val();
        var sucursal = $("#sucursals").val();
        var estado;
        var libre = document.getElementById('test0s').checked;
        var reservado = document.getElementById('test1s').checked;
        var ocupado = document.getElementById('test2s').checked;
        if (libre == true) {
            estado = 0;
        }
        if (reservado == true) {
            estado = 1;
        }
        if (ocupado == true) {
            estado = 2;
        }
        var value = $("#id").val();
        var route = "/Mesa/" + value + "";
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: {
                estado: estado,
                sucursal: sucursal,
                nummesa: nummesa,
                capacidad: capacidad,
                ubicacion: ubicacion
            },
            success: function () {
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
                swal({title: "ERROR AL CREAR LA MESA",
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