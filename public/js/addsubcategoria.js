$(document).ready(function () {
    Cargar();
});
function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}
function Cargar() {
    debugger
    var tabladatos = $('#datos');
    var id = $("#idcategoria").val();
    //var route = "/listarciudad/" + id;
    var route = "/listarsubcategoria/" + id;
    $('#datos').empty();
    $.get(route, function (res) {
        debugger
        $(res).each(function (key, value) {
            debugger
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre +"</td>" +
                    "<td><img src='" + value.imagen + "' width='64' height='64' class='circle responsive-img valign profile-image materialboxed'></td>" +                    
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td>"+
                    "<td>" +
                    "<button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>"+
                    "</tr>");
        });
        $(".materialboxed").materialbox();        
        paginador();
    });
}


jQuery('#seleccionarImagen').on('change', function (e) {
    var Lector,
            oFileInput = this;
    if (oFileInput.files.length === 0) {
        return;
    }
    Lector = new FileReader();
    Lector.onloadend = function (e) {
        jQuery('#vistaPrevia').attr('src', e.target.result);
    }
    Lector.readAsDataURL(oFileInput.files[0]);
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
    }
    Lector.readAsDataURL(oFileInput.files[0]);
});



$("#guardar").click(function () {
    debugger
//    if ($("#perfilpuedeEliminar").val() == 1) {
    var nombre = $("#nombre").val();
    var imagen = jQuery('#vistaPrevia').attr('src');
    if (!nombre || !nombre.trim().length || nombre=="") {
        if (!nombre || !nombre.trim().length) {
            Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
            return;
        }
    }
    var idtipoproducto = $("#idcategoria").val();
    //var eliminado=0
    var route = "/SubTipoProducto";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            nombre: nombre, 
            imagen: imagen,            
            idtipoproducto: idtipoproducto
        },
        success: function () {
            debugger
           //  $('#tablacategoria').DataTable().destroy();
                Cargar();
                limpiar();
                $(".lean-overlay").remove();
                $("#modal2").closeModal();
                $("#esteeselnombre").val("");
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
//    } else {
//        swal({title: "NO TIENE PERMISO PARA GUARDAR",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
});

function Eliminar(btn) {
    debugger
//    if ($("#perfilpuedeEliminar").val() == 1) {
    var route = "/SubTipoProducto/" + btn.value + "";
    var token = $("#token").val();
    swal({title: "ESTA SEGURO QUE DESEA ELIMINAR LA CIUDAD?",
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
                           // $('#tablacategoria').DataTable().destroy();
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
//    } else {
//    swal({title: "NO TIENE PERMISO PARA ELIMINAR",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
}

function Mostrar(btn) {
    debugger
    var route = "/SubTipoProducto/" + btn.value + "/edit";
    $.get(route, function (res) {
        debugger
        $("#nombres").val(res.nombre);
         $("#vistaPrevia2").attr("src", res.imagen);
        $("#nombreimg2").val("");
        //$("#id").val(res.id);
        $("#idactualizar").val(res.id);
    });
}

function limpiar() {
    $("#esteeselnombre").val(" ");
    var imagen = jQuery('#vistaPrevia').attr('src', '/images/productoavatar.png');
    $("#seleccionarImagen").val("");
    $("#nombreimg").val("");
}
$("#actualizar").click(function () {
    debugger
    var puede = $("#perfilpuedeModificar").val();
//    if (puede == 1) {
    var value = $("#idactualizar").val();
    var nombre = $("#nombres").val();
    if (!nombre || !nombre.trim().length) {
        Materialize.toast('NOMBRE VACIO', 1000, 'rounded');
        return;
    }
    var imagen = jQuery('#vistaPrevia2').attr('src');
    
    var route = "/SubTipoProducto/" + value + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            nombre: nombre,
            imagen:imagen
              },
        success: function () {
           // $('#tablacategoria').DataTable().destroy();
              Cargar();
                limpiar();
                $(".lean-overlay").remove();
                $("#modal1").closeModal();
                $("#esteeselnombre").val("");
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
//    } else {
//        swal({title: "NO TIENE PERMISO PARA ACTUALIZAR",
//            type: "warning",
//            showConfirmButton: false,
//            closeOnConfirm: false,
//            timer: 1000});
//    }
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