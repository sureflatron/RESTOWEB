$(document).ready(function () {
    Cargar();
});
var idperfil;
var idObjeto;

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var idperfil = $("#idperfil").val();
    var route = "/listarperfilobjeto/" + idperfil;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            // 1 es "si " 0  es "no"
            idperfil = value.id;
            idObjeto = value.ids;
            var guardar = "";
            var modificar = "";
            var eliminar = "";
            var listar = "";
            var verreporte = "";
            var Imprimir = "";
            if (value.puedeGuardar == 1) {
                guardar = "Puede Guardar";
            } else {
                guardar = "No puede";
            }
            if (value.puedeModificar == 1) {
                modificar = "Puede Modificar";
            } else {
                modificar = "No puede";
            }
            if (value.puedeEliminar == 1) {
                eliminar = "Puede Eliminar";
            } else {
                eliminar = "No puede";
            }
            if (value.puedeListar == 1) {
                listar = "Puede Listar";
            } else {
                listar = "No puede";
            }
            if (value.puedeVerReporte == 1) {
                verreporte = "Puede Ver Reporte";
            } else {
                verreporte = "No puede";
            }
            if (value.puedeImprimir == 1) {
                Imprimir = "Puede Imprimir";
            } else {
                Imprimir = "No puede";
            }
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + guardar + "</td>" +
                    "<td>" + modificar + "</td>" +
                    "<td>" + eliminar + "</td>" +
                    "<td>" + listar + "</td>" +
                    "<td>" + verreporte + "</td>" +
                    "<td>" + Imprimir + "</td>" +
                    "<td>" +
                    "<button value=" + value.idper + "  OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#modal1' title='Editar'><i class='material-icons'>mode_edit</i></button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger btn-floating'  OnClick='Eliminar(" + value.idper + ");' title='Eliminar'><i class='material-icons'>delete</i></button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

function Mostrar(btn) {
    var route = "/Perfilobjeto/" + btn.value + "/edit";
    $('input[type=checkbox]').prop('checked', false);
    $.get(route, function (rest) {
        $(rest).each(function (key, res) {
            $("#id").val(res.id);
            var idobj = res.idObjeto;
//        $("#idobjetos option:selected").val(idobj);
//        $("#idobjetos").material_select();
            $("#idObjetos").val(idobj);
            $("#nombreObjetos").text(res.nombre);
            if (res.puedeImprimir == 1) {
                document.getElementById("Imprimirs").checked = true;
            } else {
                document.getElementById("Imprimirs").checked = false;
            }
            if (res.puedeGuardar == 1) {
                document.getElementById("Guardarss").checked = true;
            } else {
                document.getElementById("Guardarss").checked = false;
            }
            if (res.puedeModificar == 1) {
                document.getElementById("Modificars").checked = true;
            } else {
                document.getElementById("Modificars").checked = false;
            }
            if (res.puedeVerReporte == 1) {
                document.getElementById("VerReportes").checked = true;
            } else {
                document.getElementById("VerReportes").checked = false;
            }
            if (res.puedeEliminar == 1) {
                document.getElementById("Eliminars").checked = true;
            } else {
                document.getElementById("Eliminars").checked = false;
            }
            if (res.puedeListar == 1) {
                document.getElementById("Listars").checked = true;
            } else {
                document.getElementById("Listars").checked = false;
            }
        });
    });
}

$("#actualizar").click(function () {
    var value = $("#id").val();
    var idperfil = $("#idperfils").val();
    var idobjeto = $("#idobjetos").val();
    var guardar = "";
    var modificar = "";
    var eliminar = "";
    var listar = "";
    var verreporte = "";
    var Imprimir = "";
    if ($("#Imprimirs").is(':checked')) {
        Imprimir = 1;
    } else {
        Imprimir = 0;
    }
    if ($("#Guardarss").is(':checked')) {
        guardar = 1;
    } else {
        guardar = 0;
    }
    if ($("#Modificars").is(':checked')) {
        modificar = 1;
    } else {
        modificar = 0;
    }
    if ($("#Eliminars").is(':checked')) {
        eliminar = 1;
    } else {
        eliminar = 0;
    }
    if ($("#Listars").is(':checked')) {
        listar = 1;
    } else {
        listar = 0;
    }
    if ($("#VerReportes").is(':checked')) {
        verreporte = 1;
    } else {
        verreporte = 0;
    }
    var route = "/Perfilobjeto/" + value + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            idobjeto: idobjeto,
            guardar: guardar,
            modificar: modificar,
            eliminar: eliminar,
            listar: listar,
            verreporte: verreporte,
            Imprimir: Imprimir
        },
        success: function () {
            $('#tablacategoria').DataTable().destroy();
            Cargar();
            $('input[type=checkbox]').prop('checked', false);
            $("#modal1").closeModal();
            $('.lean-overlay').remove();
            swal({title: "DATOS ACTUALIZADOS CORRECTAMENTE",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        },
        error: function () {
            swal({title: "ERROR AL GUARDAR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
});

function Eliminar(btn) {
    var route = "/Perfilobjeto/" + btn + "";
    var token = $("#token").val();
    swal({title: "ESTA SEGURO QUE DESEA ELIMINAR OBJETO DEL PERFIL?",
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
}

$("#guardar").click(function () {
    var idperfil = $("#idperfil").val();
    var idobjeto = $("#idobjeto").val();
    var guardar = "";
    var modificar = "";
    var eliminar = "";
    var listar = "";
    var verreporte = "";
    var Imprimir = "";
    if ($("#Imprimir").is(':checked')) {
        Imprimir = 1;
    } else {
        Imprimir = 0;
    }
    if ($("#Guardars").is(':checked')) {
        guardar = 1;
    } else {
        guardar = 0;
    }
    if ($("#Modificar").is(':checked')) {
        modificar = 1;
    } else {
        modificar = 0;
    }
    if ($("#Eliminar").is(':checked')) {
        eliminar = 1;
    } else {
        eliminar = 0;
    }
    if ($("#Listar").is(':checked')) {
        listar = 1;
    } else {
        listar = 0;
    }
    if ($("#VerReporte").is(':checked')) {
        verreporte = 1;
    } else {
        verreporte = 0;
    }
    var route = "/Perfilobjeto";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            idperfil: idperfil,
            idobjeto: idobjeto,
            guardar: guardar,
            modificar: modificar,
            eliminar: eliminar,
            listar: listar,
            verreporte: verreporte,
            Imprimir: Imprimir
        },
        success: function () {
            $('#tablacategoria').DataTable().destroy();
            Cargar();
            swal({title: "GUARDADO EXITOSO",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            $('input[type=checkbox]').prop('checked', false);
        },
        error: function () {
            swal({title: "ERROR AL GUARDAR",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
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
        responsive: true,
        lengthMenu: [[20, 40, 60], [20, 40, 60]]
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