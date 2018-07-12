$(document).ready(function () {
    Cargar();
    $('#contador').formatter({
        'pattern': '{{9999999}}',
        'persistent': true
    });
});

function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

function Cargar() {
    var tabladatos = $('#datos');
    var route = "/obtenercontador/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.contador + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn btn-floating'  href='#' title='Editar'><i class='material-icons'>mode_edit</i></button><t/d>" +
                    "<td><button class='btn btn-danger btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Colocar Cero'><i class='material-icons'>loop</i></button>" +
                    "</td>" +
                    "</tr>");
        });
        paginador();
    });
}

function Mostrar(btn) {
    var route = "/mostrarcontador/" + btn.value;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            document.getElementById('nombre').innerHTML = value.nombre;
            $("#contador").val(value.contador);
            $("#id").val(value.id);
        });

    });
}

function Eliminar(btn) {
    if ($("#perfilpuedeModificar").val() == 1) {
        var route = "/colocarcero/" + btn.value;
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            success: function () {
                Cargar();
                swal({title: "ACTUALIZACION COMPLETA",
                    type: "success",
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
}

$("#actualizar").click(function () {
    if ($("#perfilpuedeModificar").val() == 1) {
        var value = $("#id").val();
        var cantidad = $("#contador").val();
        if (!cantidad || !cantidad.trim().length) {
            Materialize.toast('CANTIDAD VACIA', 1000, 'rounded');
            return;
        }
        if (parseInt(cantidad) < 0) {
            Materialize.toast('La cantidad debe ser mayor o igual a 0', 1000, 'rounded');
            return;
        }
        var route = "/actualizarcontador/" + value;
        var token = $("#token").val();
        $.ajax({
            url: route,
            headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: {cantidad: cantidad},
            success: function () {
                Cargar();
                $("#modal1").closeModal();
                $('.lean-overlay').remove();
                swal({title: "ACTUALIZACION COMPLETA",
                    type: "success",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            },
            error: function () {
                $("#modal1").closeModal();
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

function paginador() {
    // Setup - add a text input to each footer cell
    $('#tabla tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" style=" border-radius: 3px;"/>');
    });
    var table = $('#tabla').DataTable({
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