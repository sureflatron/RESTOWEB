
window.onload = function () {
    var d = new Date();

var month = d.getMonth()+1;
var day = d.getDate();
var year=d.getFullYear();
year=year-1
  
    /*var output = d.getFullYear() + '/' +
    (month<10 ? '0' : '') + month + '/' +
    (day<10 ? '0' : '') + day;
    alert(year+'-'+month+'-'+day)*/
    debugger
    var token = $("#token").val();    
    
      $.ajax({          
        url: '/calculosventasmes/'+year+'/'+month,
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {            
            var valores = [];
            var etiquetas = [];
            var colores = [];
            debugger
            $($route).each(
                    
                    function (key, value) {
                        debugger
                        alert (value.fecha)
                        var cant = value.contador;
                        valores.push(cant);
                        var fecha = value.anno + "-" + dame_mes(value.mes);
                        etiquetas.push(fecha);
                        colores.push(dame_color_aleatorio());
                    }
            );
            var lineChartData = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "My dataset",
                        fillColor: "rgba(255,255,255,0)",
                        strokeColor: "#fdb45c ",
                        pointColor: "#fdb45c",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: valores
                    }
                ]
            };
            var lineChart = document.getElementById("line-chart").getContext("2d");
            window.lineChart = new Chart(lineChart).Line(lineChartData, {
                scaleShowGridLines: true,
                scaleGridLineColor: "#eceff1",
                bezierCurve: false,
                scaleFontSize: 12,
                scaleFontStyle: "normal",
                scaleFontColor: "#000",
                responsive: true
            });
        }
    });
    
    
    
    
    /*$.ajax({
        url: '/calculosventasmes'+year+'/'+month,
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'GET',
       
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            var colores = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.total;
                        valores.push(cant);
                        var fecha = value.nombre;
                        var subcad = fecha.substring(0, 10) + "...";
                        etiquetas.push(subcad);
                        colores.push(dame_color_aleatorio());
                    }
            );
            var dataBarChart = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "Bar dataset",
                        backgroundColor: colores,
                        fillColor: "#f7464a",
                        strokeColor: "#f7464a",
                        highlightFill: "rgba(70, 191, 189, 0.4)",
                        highlightStroke: "rgba(70, 191, 189, 0.9)",
                        data: valores
                    }
                ]
            };
            var trendingBarChart = document.getElementById('trending-bar-chart').getContext('2d');
            window.trendingBarChart = new Chart(trendingBarChart).Bar(dataBarChart, {
                scaleShowGridLines: true,
                scaleGridLineColor: "#eceff1",
                showScale: true,
                animationSteps: 15,
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                responsive: true
            });
        }
    });

*/

 
 
    $.ajax({
        url: '/ventasusers',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            var colores = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.total;
                        valores.push(cant);
                        var fecha = value.nombre;
                        var subcad = fecha.substring(0, 10) + "...";
                        etiquetas.push(subcad);
                        colores.push(dame_color_aleatorio());
                    }
            );
            var dataBarChart = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "Bar dataset",
                        backgroundColor: colores,
                        fillColor: "#f7464a",
                        strokeColor: "#f7464a",
                        highlightFill: "rgba(70, 191, 189, 0.4)",
                        highlightStroke: "rgba(70, 191, 189, 0.9)",
                        data: valores
                    }
                ]
            };
            var trendingBarChart = document.getElementById('trending-bar-chart').getContext('2d');
            window.trendingBarChart = new Chart(trendingBarChart).Bar(dataBarChart, {
                scaleShowGridLines: true,
                scaleGridLineColor: "#eceff1",
                showScale: true,
                animationSteps: 15,
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                responsive: true
            });
        }
    });
 
 

    var datos = $('#datos');
    $.ajax({
        url: '/ventasdiassemana',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            var colores = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.total;
                        valores.push(cant);
                        var fecha = value.fecha;
                        var subcad = fecha.substring(0, 20);
                        etiquetas.push(subcad);
                        colores.push(dame_color_aleatorio());
                        datos.append("<tr>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.total + "</td>" +
                                "<td>" + value.totalMoney + " Bs.</td>" +
                                "</tr>");
                    }
            );
            var data = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "First dataset",
                        fillColor: "rgba(128, 222, 234, 0.6)",
                        strokeColor: "#ffffff",
                        pointColor: "#00bcd4",
                        pointStrokeColor: "#ffffff",
                        pointHighlightFill: "#ffffff",
                        pointHighlightStroke: "#ffffff",
                        data: valores
                    }
                ]
            };
            var trendingLineChart = document.getElementById("trending-line-chart").getContext("2d");
            window.trendingLineChart = new Chart(trendingLineChart).Line(data, {
                scaleShowGridLines: true, ///Boolean - Whether grid lines are shown across the chart		
                scaleGridLineColor: "rgba(255,255,255,0.4)", //String - Colour of the grid lines		
                scaleGridLineWidth: 1, //Number - Width of the grid lines		
                scaleShowHorizontalLines: true, //Boolean - Whether to show horizontal lines (except X axis)		
                scaleShowVerticalLines: false, //Boolean - Whether to show vertical lines (except Y axis)		
                bezierCurve: true, //Boolean - Whether the line is curved between points		
                bezierCurveTension: 0.4, //Number - Tension of the bezier curve between points		
                pointDot: true, //Boolean - Whether to show a dot for each point		
                pointDotRadius: 5, //Number - Radius of each point dot in pixels		
                pointDotStrokeWidth: 2, //Number - Pixel width of point dot stroke		
                pointHitDetectionRadius: 20, //Number - amount extra to add to the radius to cater for hit detection outside the drawn point		
                datasetStroke: true, //Boolean - Whether to show a stroke for datasets		
                datasetStrokeWidth: 3, //Number - Pixel width of dataset stroke		
                datasetFill: true, //Boolean - Whether to fill the dataset with a colour				
                animationSteps: 15, // Number - Number of animation steps		
                animationEasing: "easeOutQuart", // String - Animation easing effect			
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                scaleFontSize: 12, // Number - Scale label font size in pixels		
                scaleFontStyle: "normal", // String - Scale label font weight style		
                scaleFontColor: "#fff", // String - Scale label font colour
                tooltipEvents: ["mousemove", "touchstart", "touchmove"], // Array - Array of string names to attach tooltip events		
                tooltipFillColor: "rgba(255,255,255,0.8)", // String - Tooltip background colour		
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                        tooltipFontSize: 12, // Number - Tooltip label font size in pixels
                tooltipFontColor: "#000", // String - Tooltip label font colour		
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                        tooltipTitleFontSize: 14, // Number - Tooltip title font size in pixels		
                tooltipTitleFontStyle: "bold", // String - Tooltip title font weight style		
                tooltipTitleFontColor: "#000", // String - Tooltip title font colour		
                tooltipYPadding: 8, // Number - pixel width of padding around tooltip text		
                tooltipXPadding: 16, // Number - pixel width of padding around tooltip text		
                tooltipCaretSize: 10, // Number - Size of the caret on the tooltip		
                tooltipCornerRadius: 6, // Number - Pixel radius of the tooltip border		
                tooltipXOffset: 10, // Number - Pixel offset from point x to tooltip edge
                responsive: true
            });
        }
    });
    
    /*
     $.ajax({
        url: '/ventasdiassemana',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            var colores = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.total;
                        valores.push(cant);
                        var fecha = value.fecha;
                        var subcad = fecha.substring(0, 20);
                        etiquetas.push(subcad);
                        colores.push(dame_color_aleatorio());
                        datos.append("<tr>" +
                                "<td>" + value.fecha + "</td>" +
                                "<td>" + value.total + "</td>" +
                                "<td>" + value.totalMoney + " Bs.</td>" +
                                "</tr>");
                    }
            );
            var data = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "First dataset",
                        fillColor: "rgba(128, 222, 234, 0.6)",
                        strokeColor: "#ffffff",
                        pointColor: "#00bcd4",
                        pointStrokeColor: "#ffffff",
                        pointHighlightFill: "#ffffff",
                        pointHighlightStroke: "#ffffff",
                        data: valores
                    }
                ]
            };
            var trendingLineChart = document.getElementById("trending-line-chart").getContext("2d");
            window.trendingLineChart = new Chart(trendingLineChart).Line(data, {
                scaleShowGridLines: true, ///Boolean - Whether grid lines are shown across the chart		
                scaleGridLineColor: "rgba(255,255,255,0.4)", //String - Colour of the grid lines		
                scaleGridLineWidth: 1, //Number - Width of the grid lines		
                scaleShowHorizontalLines: true, //Boolean - Whether to show horizontal lines (except X axis)		
                scaleShowVerticalLines: false, //Boolean - Whether to show vertical lines (except Y axis)		
                bezierCurve: true, //Boolean - Whether the line is curved between points		
                bezierCurveTension: 0.4, //Number - Tension of the bezier curve between points		
                pointDot: true, //Boolean - Whether to show a dot for each point		
                pointDotRadius: 5, //Number - Radius of each point dot in pixels		
                pointDotStrokeWidth: 2, //Number - Pixel width of point dot stroke		
                pointHitDetectionRadius: 20, //Number - amount extra to add to the radius to cater for hit detection outside the drawn point		
                datasetStroke: true, //Boolean - Whether to show a stroke for datasets		
                datasetStrokeWidth: 3, //Number - Pixel width of dataset stroke		
                datasetFill: true, //Boolean - Whether to fill the dataset with a colour				
                animationSteps: 15, // Number - Number of animation steps		
                animationEasing: "easeOutQuart", // String - Animation easing effect			
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                scaleFontSize: 12, // Number - Scale label font size in pixels		
                scaleFontStyle: "normal", // String - Scale label font weight style		
                scaleFontColor: "#fff", // String - Scale label font colour
                tooltipEvents: ["mousemove", "touchstart", "touchmove"], // Array - Array of string names to attach tooltip events		
                tooltipFillColor: "rgba(255,255,255,0.8)", // String - Tooltip background colour		
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                        tooltipFontSize: 12, // Number - Tooltip label font size in pixels
                tooltipFontColor: "#000", // String - Tooltip label font colour		
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                        tooltipTitleFontSize: 14, // Number - Tooltip title font size in pixels		
                tooltipTitleFontStyle: "bold", // String - Tooltip title font weight style		
                tooltipTitleFontColor: "#000", // String - Tooltip title font colour		
                tooltipYPadding: 8, // Number - pixel width of padding around tooltip text		
                tooltipXPadding: 16, // Number - pixel width of padding around tooltip text		
                tooltipCaretSize: 10, // Number - Size of the caret on the tooltip		
                tooltipCornerRadius: 6, // Number - Pixel radius of the tooltip border		
                tooltipXOffset: 10, // Number - Pixel offset from point x to tooltip edge
                responsive: true
            });
        }
    });
*/
    var doughnutData = [];
    var listacategorias = $("#listacategorias");
    $.ajax({
        url: '/productstipo',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            $($route).each(
                    function (key, value) {
                        var color = dame_color_aleatorio();
                        listacategorias.append("<div class='col s6 m6 l4'>" +
                                "<i class='mdi-action-bookmark' style='color: " + color + ";'></i><strong>" +
                                value.categoria + "</strong>"
                                + "</div>");
                        var datos = {
                            value: value.total,
                            color: color,
                            highlight: "rgba(70, 191, 189, 0.4)",
                            label: value.categoria
                        };
                        doughnutData.push(datos);
                    }
            );
            var doughnutChart = document.getElementById("doughnut-chart").getContext("2d");
            window.myDoughnut = new Chart(doughnutChart).Doughnut(doughnutData, {
                segmentStrokeColor: "#fff",
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                percentageInnerCutout: 50,
                animationSteps: 15,
                segmentStrokeWidth: 4,
                animateScale: true,
                percentageInnerCutout : 60,
                        responsive: true
            });
        }
    });



/*    $.ajax({
        url: '/ventasmes',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            var colores = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.contador;
                        valores.push(cant);
                        var fecha = value.anno + "-" + dame_mes(value.mes);
                        etiquetas.push(fecha);
                        colores.push(dame_color_aleatorio());
                    }
            );
            var lineChartData = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "My dataset",
                        fillColor: "rgba(255,255,255,0)",
                        strokeColor: "#fdb45c ",
                        pointColor: "#fdb45c",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: valores
                    }
                ]
            };
            var lineChart = document.getElementById("line-chart").getContext("2d");
            window.lineChart = new Chart(lineChart).Line(lineChartData, {
                scaleShowGridLines: true,
                scaleGridLineColor: "#eceff1",
                bezierCurve: false,
                scaleFontSize: 12,
                scaleFontStyle: "normal",
                scaleFontColor: "#000",
                responsive: true
            });
        }
    });
*/
    var datosMensuales = $('#datos-mensuales');
    $.ajax({
        url: '/ventasmes',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            var colores = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.total;
                        valores.push(cant);
                        var fecha = value.anno + "-" + dame_mes(value.mes);
                        etiquetas.push(fecha);
                        colores.push(dame_color_aleatorio());
                        datosMensuales.append("<tr>" +
                                "<td>" + fecha + "</td>" +
                                "<td>" + value.contador + "</td>" +
                                "<td>" + value.total + " Bs.</td>" +
                                "</tr>");
                    }
            );
            var data = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "First dataset",
                        fillColor: "rgba(128, 222, 234, 0.2)",
                        strokeColor: "#ffffff",
                        pointColor: "orange",
                        pointStrokeColor: "#ffffff",
                        pointHighlightFill: "#ffffff",
                        pointHighlightStroke: "#ffffff",
                        data: valores
                    }
                ]
            };
            var trendingLineChart = document.getElementById("trending-line-chart2").getContext("2d");
            window.trendingLineChart = new Chart(trendingLineChart).Line(data, {
                scaleShowGridLines: true, ///Boolean - Whether grid lines are shown across the chart		
                scaleGridLineColor: "rgba(255,255,255,0.4)", //String - Colour of the grid lines		
                scaleGridLineWidth: 1, //Number - Width of the grid lines		
                scaleShowHorizontalLines: true, //Boolean - Whether to show horizontal lines (except X axis)		
                scaleShowVerticalLines: false, //Boolean - Whether to show vertical lines (except Y axis)		
                bezierCurve: true, //Boolean - Whether the line is curved between points		
                bezierCurveTension: 0.4, //Number - Tension of the bezier curve between points		
                pointDot: true, //Boolean - Whether to show a dot for each point		
                pointDotRadius: 5, //Number - Radius of each point dot in pixels		
                pointDotStrokeWidth: 2, //Number - Pixel width of point dot stroke		
                pointHitDetectionRadius: 20, //Number - amount extra to add to the radius to cater for hit detection outside the drawn point		
                datasetStroke: true, //Boolean - Whether to show a stroke for datasets		
                datasetStrokeWidth: 3, //Number - Pixel width of dataset stroke		
                datasetFill: true, //Boolean - Whether to fill the dataset with a colour				
                animationSteps: 15, // Number - Number of animation steps		
                animationEasing: "easeOutQuart", // String - Animation easing effect			
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                scaleFontSize: 12, // Number - Scale label font size in pixels		
                scaleFontStyle: "normal", // String - Scale label font weight style		
                scaleFontColor: "#fff", // String - Scale label font colour
                tooltipEvents: ["mousemove", "touchstart", "touchmove"], // Array - Array of string names to attach tooltip events		
                tooltipFillColor: "rgba(255,255,255,0.8)", // String - Tooltip background colour		
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                        tooltipFontSize: 12, // Number - Tooltip label font size in pixels
                tooltipFontColor: "#000", // String - Tooltip label font colour		
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                        tooltipTitleFontSize: 14, // Number - Tooltip title font size in pixels		
                tooltipTitleFontStyle: "bold", // String - Tooltip title font weight style		
                tooltipTitleFontColor: "#000", // String - Tooltip title font colour		
                tooltipYPadding: 8, // Number - pixel width of padding around tooltip text		
                tooltipXPadding: 16, // Number - pixel width of padding around tooltip text		
                tooltipCaretSize: 10, // Number - Size of the caret on the tooltip		
                tooltipCornerRadius: 6, // Number - Pixel radius of the tooltip border		
                tooltipXOffset: 10, // Number - Pixel offset from point x to tooltip edge
                responsive: true
            });
        }
    });

    $.ajax({
        url: '/ventastipopago',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.total;
                        valores.push(cant);
                        var fecha = value.formaPago;
                        if (value.formaPago == "Tarjeta de Credito") {
                            fecha = "T. Credito";
                        }
                        if (value.formaPago == "Tarjeta de Debito") {
                            fecha = "T. Debito";
                        }
                        if (value.formaPago == "Deposito Banco") {
                            fecha = "D. Banco";
                        }
                        etiquetas.push(fecha);
                    }
            );
            var dataBarChart = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "Bar dataset",
                        fillColor: "#00b0ff",
                        strokeColor: "#00b0ff",
                        highlightFill: "rgba(70, 191, 189, 0.4)",
                        highlightStroke: "rgba(70, 191, 189, 0.9)",
                        data: valores
                    }
                ]
            };
            var trendingBarChart = document.getElementById('trending-bar-chart2').getContext('2d');
            window.trendingBarChart = new Chart(trendingBarChart).Bar(dataBarChart, {
                scaleShowGridLines: true,
                scaleGridLineColor: "#eceff1",
                showScale: true,
                animationSteps: 15,
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                responsive: true
            });
        }
    });

    var datosporductos = $('#datos-top-productos');
    $.ajax({
        url: '/topproductos',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            var valores = [];
            var etiquetas = [];
            $($route).each(
                    function (key, value) {
                        var cant = value.Cantidadvendida;
                        valores.push(cant);
                        var fecha = value.Producto;
                        etiquetas.push(fecha);
                        datosporductos.append("<tr>" +
                                "<td>" + value.Producto + "</td>" +
                                "<td>" + value.codigoDeBarra + "</td>" +
                                "<td>" + value.color + "</td>" +
                                "<td>" + value.tamano + "</td>" +
                                "<td>" + value.Cantidadvendida + "</td>" +
                                "<td>" + value.ImporteTotal + " Bs.</td>" +
                                "</tr>");
                    }
            );
            var lineChartData = {
                labels: etiquetas,
                datasets: [
                    {
                        label: "My dataset",
                        fillColor: "rgba(255,255,255,0.1)",
                        strokeColor: "#fff ",
                        pointColor: "#fff",
                        pointStrokeColor: "e91e63",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: valores
                    }
                ]
            };
            var lineChart = document.getElementById("line-chart2").getContext("2d");
            window.lineChart = new Chart(lineChart).Line(lineChartData, {
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(255,255,255,0.4)",
                bezierCurve: false,
                scaleFontSize: 12,
                scaleFontStyle: "normal",
                scaleFontColor: "#fff",
                responsive: true
            });
        }
    });

    var doughnutData2 = [];
    var listaclientefacturas = $("#listaclientefacturas");
    var datosclientesfacturas = $('#datos-cliente-facturas');
    $.ajax({
        url: '/topclientesfactura',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            $($route).each(
                    function (key, value) {
                        var color = dame_color_aleatorio();
                        listaclientefacturas.append("<div class='col s6 m6 l4'>" +
                                "<i class='mdi-action-bookmark' style='color: " + color + ";'></i><strong>" +
                                value.razonSocial + "</strong>"
                                + "</div>");
                        datosclientesfacturas.append("<tr>" +
                                "<td>" + value.razonSocial + "</td>" +
                                "<td>" + value.nit + "</td>" +
                                "<td>" + value.cantidad + "</td>" +
                                "<td>" + value.total + "</td>" +
                                "</tr>");
                        var datos = {
                            value: value.total,
                            color: color,
                            hhighlight: "rgba(70, 191, 189, 0.4)",
                            label: value.razonSocial
                        };
                        doughnutData2.push(datos);
                    }
            );
            var doughnutChart = document.getElementById("doughnut-chart2").getContext("2d");
            window.myDoughnut = new Chart(doughnutChart).Doughnut(doughnutData2, {
                segmentStrokeColor: "#fff",
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                percentageInnerCutout: 50,
                animationSteps: 15,
                segmentStrokeWidth: 4,
                animateScale: true,
                percentageInnerCutout : 60,
                        responsive: true
            });
        }
    });

    var doughnutData3 = [];
    var listaclientesventas = $("#listaclientesventas");
    var datosclientesventas = $('#datos-cliente-ventas');
    $.ajax({
        url: '/topclientesventas',
        headers: {'X-CSRF-TOKEN': token},
        dataType: 'json',
        type: 'POST',
        contentType: 'application/json; charset=utf-8',
        success: function ($route) {
            $($route).each(
                    function (key, value) {
                        if (value.cliente == "SIN NOMBRE") {

                        } else {
                            var color = dame_color_aleatorio();
                            listaclientesventas.append("<div class='col s6 m6 l4'>" +
                                    "<i class='mdi-action-bookmark' style='color: " + color + ";'></i><strong>" +
                                    value.cliente + "</strong>"
                                    + "</div>");
                            datosclientesventas.append("<tr>" +
                                    "<td>" + value.cliente + "</td>" +
                                    "<td>" + value.NIT + "</td>" +
                                    "<td>" + value.cantidad + "</td>" +
                                    "<td>" + value.totalventa + "</td>" +
                                    "</tr>");
                            var datos = {
                                value: value.totalventa,
                                color: color,
                                highlight: "rgba(70, 191, 189, 0.4)",
                                label: value.cliente
                            };
                            doughnutData3.push(datos);
                        }
                    }
            );
            var doughnutChart = document.getElementById("doughnut-chart3").getContext("2d");
            window.myDoughnut = new Chart(doughnutChart).Doughnut(doughnutData3, {
                segmentStrokeColor: "#fff",
                tooltipTitleFontFamily: "'Roboto','Helvetica Neue', 'Helvetica', 'Arial', sans-serif", // String - Tooltip title font declaration for the scale label		
                percentageInnerCutout: 50,
                animationSteps: 15,
                segmentStrokeWidth: 4,
                animateScale: true,
                percentageInnerCutout : 60,
                        responsive: true
            });
        }
    });
};


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

function dame_mes(mes) {
    if (mes == 1) {
        return "EN";
    } else if (mes == 2) {
        return "FEB";
    } else if (mes == 3) {
        return "MAR";
    } else if (mes == 4) {
        return "ABR";
    } else if (mes == 5) {
        return "MAY";
    } else if (mes == 6) {
        return "JUN";
    } else if (mes == 7) {
        return "JUL";
    } else if (mes == 8) {
        return "AGO";
    } else if (mes == 9) {
        return "SEPT";
    } else if (mes == 10) {
        return "OCT";
    } else if (mes == 11) {
        return "NOV";
    } else if (mes == 12) {
        return "DIC";
    }
}