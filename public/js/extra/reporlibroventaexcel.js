$(document).ready(function () {
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth() + 1; //hoy es 0!
    var yyyy = hoy.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    hoy = yyyy + '-' + mm + '-' + dd;
    $("#fechainicio").val(hoy);
    $("#Fechafin").val(hoy);
    
    
    
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 10 // Creates a dropdown of 15 years to control year
    });
});
$("#generarlibro").click(function () {
    var fechafin = $('#Fechafin').val();
    var fechaninio = $('#fechainicio').val();
    var idSucursal = $('#sucursal').val();
    if ((!fechafin || !fechafin.trim().length) && (!fechaninio || !fechaninio.trim().length)) {
        return swal({title: "DATOS ERRONEOS",
            text: "Debe ingresar la fecha de inicio y fin para poder generar el reporte",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
    }
    var validacion = fechaCorrecta(fechaninio, fechafin);
    if (validacion == false) {
        swal({title: "DATOS ERRONEOS",
            text: "La fecha final no debe ser menor a la fecha de inicio",
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "OK",
            closeOnConfirm: true});
        return;
    }
    window.open("/reporlibroventaexcel/" + fechaninio + "/" + fechafin + "/" + idSucursal);
});

function fechaCorrecta(fechaInicio, fechaFin) {
    if (Date.parse(fechaInicio) > Date.parse(fechaFin)) {
        return false;
    } else {
        return true;
    }
}