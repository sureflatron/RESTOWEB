$(document).ready(function () {
    $.ajax({
        url: 'Index.aspx/UsuariosPorFecha',
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function (data) {
            var resultado = data.d;
            var valores = [];
            var etiquetas = [];
            var colores = [];
            for (c = 0; c < resultado.length; c++) {
                var cant = resultado[c].Cantidad;
                valores.push(cant);
                var fecha = resultado[c].FechaHoraForDisplay;
                etiquetas.push(fecha)
                colores.push(dame_color_aleatorio());
            }
            var datos = {
                type: "pie",
                data: {
                    datasets: [{
                        label: 'Cantidad de Usuarios',
                        data: valores,
                        backgroundColor: colores,
                    }],
                    labels: etiquetas
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                    }
                }
            };
            var canvas = document.getElementById('chart').getContext('2d');
            window.pie = new Chart(canvas, datos);
        }
    })
});

function dame_numero_aleatorio(superior, inferior) {
    var numPosibilidades = (superior + 1) - inferior;
    var aleat = Math.random() * numPosibilidades;
    aleat = Math.floor(aleat);
    aleat = (inferior + aleat);
    return aleat
}

function dame_color_aleatorio() {
    color_aleat = "#"
    hexadecimal = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F")
    var inferior = 0;
    var superior = hexadecimal.length - 1;
    for (i = 0; i < 6; i++) {
        color_aleat += hexadecimal[dame_numero_aleatorio(superior, inferior)];
    }
    return color_aleat
}