$(document).ready(function () {
    Cargar();
});

function Cargar($dato) {
    if ($dato == null) {
        var route = "/listarmesa/";
    } else {
        var route = "/buscarmesa/" + $dato;
    }
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.estado == 0) {
                $("#inicio").append("<div class='col s12 m1 l1' onclick='Generarventa(" + value.id + ");'>" +
                        "<div class='card-panel light-green accent-4'>" +
                        "<span class=white-text'>" + value.numeromesa + "<span>" +
                        "<h6 class='white-text'>Cap.:" + value.capacidad + "</h6>" +
                        "</div>" +
                        "</div>");
            }
            if (value.estado == 1) {
                $("#inicio").append("<div class='col s12 m1 l1' onclick='alert(" + value.id + ");'>" +
                        "<div class='card-panel  light-blue darken-4'>" +
                        "<span class=white-text'>" + value.numeromesa + "<span>" +
                        "<h6 class='white-text'>Cap.:" + value.capacidad + "</h6>" +
                        "</div>" +
                        "</div>");
            }
            if (value.estado == 2) {
                $("#inicio").append("<div class='col s12 m1 l1' onclick='buscarventaconmesa(" + value.id + ");'>" +
                        "<div class='card-panel red accent-2'>" +
                        "<span class=white-text'>" + value.numeromesa + "<span>" +
                        "<h6 class='white-text'>Cap.:" + value.capacidad + "</h6>" +
                        "</div>" +
                        "</div>");
            }
        });
    });
}

function Generarventa($id) {
    cambiarestado($id);
    generarventaconmesa($id);
}

function buscarventaconmesa($id) {
    var route = "/Buscarventaconmesa/" + $id;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            debugger;
            var dato = value.id;
            cambiarestado($id);
            window.open("/editarventasres/" + dato);
        });
    });

}

function generarventaconmesa($idmesa) {
    var idmesas = $idmesa;
    var iddelpuntoventa = $('#iddelpuntoventa').val();
    var idventa;
    var route = "/generarventadesdemesa";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            idmesas: idmesas,
            iddelpuntoventa: iddelpuntoventa
        },
        success: function ($idventa) {
            $($idventa).each(function (key, value) {
                $(value).each(function (key, values) {
                    idventa = values.id;
                    window.open("/Ventares/" + idventa);
                });
            });
        }, error: function () {
            Materialize.toast('Ya exite ingrediente ', 1500, 'rounded');
        }
    });

}


function cambiarestado($idpuntodeventa) {
    $idpuntodeventa;
    debugger;
    var route = "/Cambiarestadomesa/" + $idpuntodeventa;
    $.get(route, function (res) {
        Cargar();
        $("#inicio").empty();
        $(res).each(function (key, value) {
            Cargar();
        });
    });
}

$("#test1").click(function () {
    debugger;
    var libre = document.getElementById("test1").checked;
    if (libre == true) {
        $("#inicio").empty();
        libre = 0;
        Cargar(libre);
    } else {
        $("#inicio").empty();
        Cargar();
    }
});


$("#test2").click(function () {
    debugger;
    var reservado = document.getElementById("test2").checked;
    if (reservado == true) {
        $("#inicio").empty();
        reservado = 1;
        Cargar(reservado);
    } else {
        $("#inicio").empty();
        Cargar();
    }
});

$("#test3").click(function () {
    debugger;
    var ocupado = document.getElementById("test3").checked;
    if (ocupado == true) {
        $("#inicio").empty();
        ocupado = 2;
        Cargar(ocupado);
    } else {
        $("#inicio").empty();
        Cargar();
    }
});
