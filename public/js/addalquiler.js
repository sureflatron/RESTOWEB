var iddelpuntoventa;
var idempleado;
var perfil;
$(document).ready(function () {
    iddelpuntoventa = $('#iddelpuntoventa').val();
    idempleado = $('#iddelempleado').val();
    perfil = $("#iddelperfil").val();
    Cargar();
    Cargartabla();
    cargaralmacendesucursal();
    $("#cliNombre2").on('input', function () {
        var val = this.value;
        if ($('#clienteslist2').find('option').filter(function () {
            return this.value.toUpperCase() === val.toUpperCase();
        }).length) {
            var abc = $("#clienteslist2 option[value='" + $('#cliNombre2').val() + "']").attr('data-id');
            $("#idCliente").val(abc);
            var route = "/nitrazoncliente/" + abc;
            $.get(route, function (res) {
                $(res).each(function (key, value) {
                    $("#nit").val(value.nit);
                    $("#razonsocial").val(value.razonSocial);
                });
            });
        }
    });
    $(".group1").click(function (evento) {
        var val = $(this).val();
        if (val == "contado") {
            $("#pagoEfectivo").attr("style", "display :block;");
            $("#pagoTarjeta").attr("style", "display:none;");
            $("#pagoDeposito").attr("style", "display:none;");
            $("#pagoCheque").attr("style", "display:none;");
        } else if (val == "tarjeta") {
            $("#pagoEfectivo").attr("style", "display:none;");
            $("#pagoTarjeta").attr("style", "display:block;");
            $("#pagoDeposito").attr("style", "display:none;");
            $("#pagoCheque").attr("style", "display:none;");
        } else if (val == "deposito") {
            $("#pagoEfectivo").attr("style", "display:none;");
            $("#pagoTarjeta").attr("style", "display:none;");
            $("#pagoDeposito").attr("style", "display:block;");
            $("#pagoCheque").attr("style", "display:none;");
        } else {
            $("#pagoEfectivo").attr("style", "display:none;");
            $("#pagoTarjeta").attr("style", "display:none;");
            $("#pagoDeposito").attr("style", "display:none;");
            $("#pagoCheque").attr("style", "display:block;");
        }
    });
    $(".efec").click(function (evento) {
        var bs = document.getElementById('bs').checked;
        var sus = document.getElementById('sus').checked;
        if (bs === true) {
            $("#pago").val("");
            $("#cambio").val("0");
        } else if (sus === true) {
            $("#cambio").val("0");
            $("#pago").val("");
        }
    });
    $("select#almacencombo").change(function () {
        var idalamcennew = $(this).val();
        var idventa = $('#idventa').val();
        var route = "/actualizaralmacenventa/" + idventa + "/" + idalamcennew;
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#idalmacen").val(idalamcennew);
                $("#productosalquilados").empty();
            });
        });
    });
});

function Cargar() {
    $("#listado").hide();
    $("#cargando").show();
    $('#datos').empty();
    var route = "/obtenerservicios";
    var token = $("#token").val();
    var idalmacen = $("#idalmacen").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'GET',
        dataType: 'json',
        data: {
            idalmacen: idalmacen
        },
        success: function ($route) {
            var tabladatos = $('#datos');
            $($route).each(function (key, value) {
                tabladatos.append("<tr>" +
                        "<td><img src='" + value.imagen + "' width='64' height='64' class='circle responsive-img valign profile-image materialboxed' id='img" +
                        value.id + "'></td>" +
                        "<td>" + value.codigoDeBarra +
                        "</td><td id='nombre" + value.id + "'>" + value.nombre +
                        "</td><td>" + value.descripcion +
                        "</td><td>" + value.precio +
                        "</td>" +
                        "<td>" +
                        "<a class='btn waves-effect btn-floating' id='agregardetalle' name='Agregar' onclick='agregardetalle("
                        + value.id + ")'>" +
                        "<i class='mdi-action-add-shopping-cart'></i>" +
                        "</a>" +
                        "</td>" +
                        "</tr>");
            });
            $(".materialboxed").materialbox();
            $("#listado").show();
            $("#cargando").hide();
        },
        error: function () {
            swal({title: "ERROR AL CARGAR LOS PRODUCTOS",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}

function agregardetalle(id) {
    var idVenta = $("#idventa").val();
    if (idVenta == "") {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "No se puede agregar productos a un alquier anulado",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/Validarventaantigua/" + idVenta;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede agregar productos a un alquier anulado",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "Este Alquiler ya fue cobrado, Porfavor realize uno nuevo",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        var idAlmacen = $("#idalmacen").val();
        var route = "/Alquiler";
        var token = $("#token").val();
        swal({
            imageUrl: jQuery('#img' + id).attr('src'),
            title: $("#nombre" + id).text(),
            text: "Inserte la Cantidad a Alquilar",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Cantidad"},
                function (inputValue) {
                    if (inputValue === false) {
                        return false;
                    }
                    if (inputValue === "") {
                        return swal.showInputError("Necesita Ingresar una Cantidad");
                    }
                    var cant = parseInt(inputValue);
                    if (isNaN(cant)) {
                        return swal("Adverencia!", "Debe agregar una cantidad", "warning");
                    }
                    $.ajax({
                        url: route,
                        headers: {'X-CSRF-TOKEN': token},
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            idVenta: idVenta,
                            idAlmacen: idAlmacen,
                            idProducto: id,
                            cantidad: inputValue
                        },
                        success: function ($route) {
                            $($route).each(function (key, value) {
                                if (value.mensaje == "Producto Agregado con Exito al Alquiler") {
                                    Cargartabla();
                                    swal({title: "Bien!",
                                        text: "Agregaste: " + inputValue + " " + $("#nombre" + id).text(),
                                        type: "success",
                                        showConfirmButton: false,
                                        closeOnConfirm: false,
                                        timer: 1000});
                                } else {
                                    swal({title: "Adverencia!",
                                        text: value.mensaje,
                                        type: "warning",
                                        showConfirmButton: false,
                                        closeOnConfirm: false,
                                        timer: 1000});
                                }
                            });
                        },
                        error: function () {
                            swal({title: "Error!",
                                text: "Error al Agregar el Producto",
                                type: "error",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1000});
                        }
                    });
                });
    });
}

function Eliminar(id) {
    var idventa = $("#idventa").val();
    if (idventa == "") {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "No se puede agregar productos a un alquier anulado",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/Validarventaantigua/" + idventa;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede agregar productos a un alquier anulado",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "Este Alquiler ya fue cobrado, Porfavor realize uno nuevo",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        var route = "/Detalleventa/" + id + "";
        var token = $("#token").val();
        swal({title: "Eliminar?",
            text: "Esta seguro que desea eliminar?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, eliminar!",
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
                            data: {
                                idventa: idventa,
                                idproducto: id
                            },
                            success: function () {
                                swal({title: "Eliminado Correctamente",
                                    type: "success",
                                    showConfirmButton: false,
                                    closeOnConfirm: false,
                                    timer: 1000});
                                var trAEliminar = document.getElementById("detpro" + id);
                                trAEliminar.parentNode.removeChild(trAEliminar);
                            },
                            error: function () {
                                swal({title: "Error Al Eliminar",
                                    type: "error",
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
                }
        );
    });
}

function buscarcodigodebarra() {
    var tableReg = document.getElementById('servicios');
    var searchText = document.getElementById('barcode').value.toLowerCase();
    var cellsOfRow = "";
    var found = false;
    var compareWith = "";
    for (var i = 1; i < tableReg.rows.length; i++) {
        cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        found = false;
        for (var j = 0; j < cellsOfRow.length && !found; j++) {
            compareWith = cellsOfRow[j].innerHTML.toLowerCase();
            if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)) {
                found = true;
            }
        }
        if (found) {
            tableReg.rows[i].style.display = '';
        } else {
            tableReg.rows[i].style.display = 'none';
        }
    }
}

function buscarnombre() {
    var tableReg = document.getElementById('servicios');
    var searchText = document.getElementById('nombrepro').value.toLowerCase();
    var cellsOfRow = "";
    var found = false;
    var compareWith = "";
    for (var i = 1; i < tableReg.rows.length; i++) {
        cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        found = false;
        for (var j = 0; j < cellsOfRow.length && !found; j++) {
            compareWith = cellsOfRow[j].innerHTML.toLowerCase();
            if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)) {
                found = true;
            }
        }
        if (found) {
            tableReg.rows[i].style.display = '';
        } else {
            tableReg.rows[i].style.display = 'none';
        }
    }
}

function Cargartabla() {
    $("#productosalquilados").empty();
    var idventa = $("#idventa").val();
    var route = "/detalleventas/" + idventa;
    $.get(route, function (res) {
        $("#productosalquilados").empty();
        $(res).each(function (key, value) {
            $("#productosalquilados").append("<div class='product col s12 m6 l6' style='margin-top: 20px;' id='detpro" + value.id + "'>" +
                    "<div class='card'>" +
                    "<div class='card-image waves-effect waves-block waves-light'>" +
                    "<a href='#' class='btn-floating btn-large btn-price waves-effect waves-light orange darken-4'>" + value.precioVenta + "</a>" +
                    "<img src='" + value.imagen + "' alt='product-img' width='200' height='200'></div>" +
                    "<ul class = 'card-action-buttons' >" +
                    "<li> <a class='btn-floating waves-effect waves-light light-blue' ><i class='mdi-action-info activator'></i></a>" +
                    "</li><li> <a class='btn-floating waves-effect waves-light amber accent-3' href='javascript:openmodal(" + value.id + ")'><i class='mdi-editor-mode-edit'></i></a>" +
                    "</li><li> <a class='btn-floating waves-effect waves-light red accent-3' href='javascript:Eliminar(" + value.id + ")' ><i class='mdi-action-delete'></i></a>" +
                    "</li></ul><div class='card-content' ><div class='row' ><div class='col s12'>" +
                    "<p class='card-title grey-text text-darken-4'><a href='#' class='grey-text text-darken-4'>" + value.nombre + "</a>" +
                    "</p></div><div class ='col s4 no-padding'>" +
                    "</div></div></div><div class = 'card-reveal'>" +
                    "<span class = 'card-title grey-text text-darken-4'><i class ='di-navigation-close right'></i><strong>" + value.nombre + "</strong></span>" +
                    "<p><strong>Descripcion: </strong>" + value.descripcion + "</br>" +
                    "<strong>Color: </strong>" + value.color + "</br>" +
                    "<strong>Talla: </strong>" + value.talla + "</br>" +
                    "<strong>Cantidad: </strong>" + value.cantidad + "</br>" +
                    "<strong>Precio de Venta: </strong>" + value.precioVenta + "</br>" +
                    "<strong>SubTotal: </strong>" + value.subtotal + "</br>" +
                    "</p></div></div></div>");
        });
    });
}

function openmodal(btn) {
    var idVenta = $("#idventa").val();
    if (idVenta == "") {
        $('#generadordeventa').show();
        return swal({title: "Adverencia!",
            text: "No se puede agregar productos a un alquier anulado",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/Validarventaantigua/" + idVenta;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede agregar productos a un alquier anulado",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "Este Alquiler ya fue cobrado, Porfavor realize uno nuevo",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }

        Mostrar(btn);
        $("#modal2").openModal();
    });
}

function Mostrar(btn) {
    var route = "/Detalleventa/" + btn;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#iddetalleventa").val(value.id);
            $('#nombreprod').text(value.nombre);
            $('#descripcionprod').text(value.descripcion);
            jQuery('#imgprod').attr('src', value.imagen);
            $("#precioprod").text(value.precio);
            $("#cantidadproducto").val(value.cantidad);
            $("#subtotalpord").text(value.total);
        });
    });
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function cambiarcanidad() {
    var route = "/actualizarcantidad";
    var token = $("#token").val();
    var idAlmacen = $("#idalmacen").val();
    var id = $("#iddetalleventa").val();
    var cantidad = $("#cantidadproducto").val();
    if (cantidad == "") {
        return swal({title: "Adverencia!",
            text: "Inserte la cantidad",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (cantidad == "0") {
        return swal({title: "Adverencia!",
            text: "La cantidad debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            id: id,
            idAlmacen: idAlmacen,
            cantidad: cantidad
        },
        success: function ($route) {
            $($route).each(function (key, value) {
                if (value.mensaje == "Cantidad Actualizada Exitosamente") {
                    swal({title: "Bien!",
                        text: value.mensaje,
                        type: "success",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                } else {
                    swal({title: "Adverencia!",
                        text: value.mensaje,
                        type: "warning",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                }
                $("#modal2").closeModal();
            });
            Cargartabla();
        }, error: function () {
            swal({title: "Error Al Actualizar",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
    });
}

function calcular_total() {
    var bs = document.getElementById('bs').checked;
    var pago = parseFloat($('#pago').val());
    var total = parseFloat($("#totalfijo").text());
    var dolar = parseFloat($("#dolar").val());
    var cambio;
    if (bs === true) {
        cambio = pago - total;
    } else {
        cambio = (pago * dolar) - total;
    }
    if (cambio < 0) {
        $('#cambio').val(0);
    } else {
        if (isNaN(cambio)) {
            $("#cambio").val(0);
        } else {
            $("#cambio").val(cambio.toFixed(2));
        }
    }
}

function cargardatos() {
    var id = $("#idventa").val();
    var route = "/Alquiler/" + id;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            if (value.total == "0") {
                return swal({title: "Advertencia!",
                    text: "Alquiler sin Detalle.",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
            if (value.total == null) {
                return swal({title: "Advertencia!",
                    text: "Alquiler sin Detalle.",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
            $("#totalfijo").text(value.total);
            $("#datosfactura").empty();
            var total1 = "" + value.total + "";
            if (total1 !== "null") {
                var valor1 = LITERAL(total1);
                var tabladatos = $('#datosfactura');
                tabladatos.append("<tr><td id='totalcondescuentoventa'>" + value.total + "</td><td id='totalventaliteral'>" + valor1 + "</td><tr>");
            }
            $("#modal1").openModal();
        });
    });
}



/*******************************************************************************
 * *****************************************************************************
 * *********************************LITERAL*************************************
 * *****************************************************************************
 * *****************************************************************************
 */
// Función modulo, regresa el residuo de una división 
function mod(dividendo, divisor) {
    resDiv = dividendo / divisor;
    parteEnt = Math.floor(resDiv); // Obtiene la parte Entera de resDiv 
    parteFrac = resDiv - parteEnt; // Obtiene la parte Fraccionaria de la división
    //modulo = parteFrac * divisor;  // Regresa la parte fraccionaria * la división (modulo) 
    modulo = Math.round(parteFrac * divisor)
    return modulo;
} // Fin de función mod

// Función ObtenerParteEntDiv, regresa la parte entera de una división
function ObtenerParteEntDiv(dividendo, divisor) {
    resDiv = dividendo / divisor;
    parteEntDiv = Math.floor(resDiv);
    return parteEntDiv;
} // Fin de función ObtenerParteEntDiv

// function fraction_part, regresa la parte Fraccionaria de una cantidad
function fraction_part(dividendo, divisor) {
    resDiv = dividendo / divisor;
    f_part = Math.floor(resDiv);
    return f_part;
} // Fin de función fraction_part

// function string_literal conversion is the core of this program 
// converts numbers to spanish strings, handling the general special 
// cases in spanish language. 
function string_literal_conversion(number) {
    // first, divide your number in hundreds, tens and units, cascadig 
    // trough subsequent divisions, using the modulus of each division 
    // for the next. 
    centenas = ObtenerParteEntDiv(number, 100);
    number = mod(number, 100);
    decenas = ObtenerParteEntDiv(number, 10);
    number = mod(number, 10);
    unidades = ObtenerParteEntDiv(number, 1);
    number = mod(number, 1);
    string_hundreds = "";
    string_tens = "";
    string_units = "";
    // cascade trough hundreds. This will convert the hundreds part to 
    // their corresponding string in spanish.
    if (centenas == 1) {
        string_hundreds = "ciento ";
    }
    if (centenas == 2) {
        string_hundreds = "doscientos ";
    }
    if (centenas == 3) {
        string_hundreds = "trescientos ";
    }
    if (centenas == 4) {
        string_hundreds = "cuatrocientos ";
    }
    if (centenas == 5) {
        string_hundreds = "quinientos ";
    }

    if (centenas == 6) {
        string_hundreds = "seiscientos ";
    }

    if (centenas == 7) {
        string_hundreds = "setecientos ";
    }

    if (centenas == 8) {
        string_hundreds = "ochocientos ";
    }

    if (centenas == 9) {
        string_hundreds = "novecientos ";
    }

    // end switch hundreds 

    // casgade trough tens. This will convert the tens part to corresponding 
    // strings in spanish. Note, however that the strings between 11 and 19 
    // are all special cases. Also 21-29 is a special case in spanish. 
    if (decenas == 1) {
        //Special case, depends on units for each conversion
        if (unidades == 1) {
            string_tens = "once";
        }

        if (unidades == 2) {
            string_tens = "doce";
        }

        if (unidades == 3) {
            string_tens = "trece";
        }

        if (unidades == 4) {
            string_tens = "catorce";
        }

        if (unidades == 5) {
            string_tens = "quince";
        }

        if (unidades == 6) {
            string_tens = "dieciseis";
        }

        if (unidades == 7) {
            string_tens = "diecisiete";
        }

        if (unidades == 8) {
            string_tens = "dieciocho";
        }

        if (unidades == 9) {
            string_tens = "diecinueve";
        }
    }
    //alert("STRING_TENS ="+string_tens);

    if (decenas == 2) {
        string_tens = "veinti";
    }
    if (decenas == 3) {
        string_tens = "treinta";
    }
    if (decenas == 4) {
        string_tens = "cuarenta";
    }
    if (decenas == 5) {
        string_tens = "cincuenta";
    }
    if (decenas == 6) {
        string_tens = "sesenta";
    }
    if (decenas == 7) {
        string_tens = "setenta";
    }
    if (decenas == 8) {
        string_tens = "ochenta";
    }
    if (decenas == 9) {
        string_tens = "noventa";
    }

    // Fin de swicth decenas


    // cascades trough units, This will convert the units part to corresponding 
    // strings in spanish. Note however that a check is being made to see wether 
    // the special cases 11-19 were used. In that case, the whole conversion of 
    // individual units is ignored since it was already made in the tens cascade. 

    if (decenas == 1)
    {
        string_units = ""; // empties the units check, since it has alredy been handled on the tens switch 
    } else
    {
        if (unidades == 1) {
            string_units = "un";
        }
        if (unidades == 2) {
            string_units = "dos";
        }
        if (unidades == 3) {
            string_units = "tres";
        }
        if (unidades == 4) {
            string_units = "cuatro";
        }
        if (unidades == 5) {
            string_units = "cinco";
        }
        if (unidades == 6) {
            string_units = "seis";
        }
        if (unidades == 7) {
            string_units = "siete";
        }
        if (unidades == 8) {
            string_units = "ocho";
        }
        if (unidades == 9) {
            string_units = "nueve";
        }
        // end switch units 
    } // end if-then-else 


//final special cases. This conditions will handle the special cases which 
//are not as general as the ones in the cascades. Basically four: 

// when you've got 100, you dont' say 'ciento' you say 'cien' 
// 'ciento' is used only for [101 >= number > 199] 
    if (centenas == 1 && decenas == 0 && unidades == 0) {
        string_hundreds = "cien ";
    }

// when you've got 10, you don't say any of the 11-19 special 
// cases.. just say 'diez' 
    if (decenas == 1 && unidades == 0) {
        string_tens = "diez ";
    }

// when you've got 20, you don't say 'veinti', which is used 
// only for [21 >= number > 29] 
    if (decenas == 2 && unidades == 0)
    {
        string_tens = "veinte ";
    }

// for numbers >= 30, you don't use a single word such as veintiuno 
// (twenty one), you must add 'y' (and), and use two words. v.gr 31 
// 'treinta y uno' (thirty and one) 
    if (decenas >= 3 && unidades >= 1) {
        string_tens = string_tens + " y ";
    }

// this line gathers all the hundreds, tens and units into the final string 
// and returns it as the function value.
    final_string = string_hundreds + string_tens + string_units;
    return final_string;
} //end of function string_literal_conversion()================================ 

// handle some external special cases. Specially the millions, thousands 
// and hundreds descriptors. Since the same rules apply to all number triads 
// descriptions are handled outside the string conversion function, so it can 
// be re used for each triad. 

function LITERAL(number) {
    //number = number_format (number, 2);
    number1 = number;
    //settype (number, "integer");
    cent = number1.split(".");
    centavos = cent[1];
    //Mind Mod
    number = cent[0];
    if (centavos == 0 || centavos == undefined) {
        centavos = "00";
    }

    if (number == 0 || number == "")
    { // if amount = 0, then forget all about conversions, 
        centenas_final_string = " cero "; // amount is zero (cero). handle it externally, to 
        // function breakdown 
    } else
    {

        millions = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
        number = mod(number, 1000000); // conversion function 

        if (millions != 0)
        {
            // This condition handles the plural case 
            if (millions == 1)
            {              // if only 1, use 'millon' (million). if 
                descriptor = " millon "; // > than 1, use 'millones' (millions) as 
            } else
            {                           // a descriptor for this triad. 
                descriptor = " millones ";
            }
        } else
        {
            descriptor = " "; // if 0 million then use no descriptor. 
        }
        millions_final_string = string_literal_conversion(millions) + descriptor;
        thousands = ObtenerParteEntDiv(number, 1000); // now, send the thousands to the string 
        number = mod(number, 1000); // conversion function. 
        //print "Th:".thousands;
        if (thousands != 1)
        {                   // This condition eliminates the descriptor 
            thousands_final_string = string_literal_conversion(thousands) + " mil ";
            //  descriptor = " mil ";          // if there are no thousands on the amount 
        }
        if (thousands == 1)
        {
            thousands_final_string = " mil ";
        }
        if (thousands < 1)
        {
            thousands_final_string = " ";
        }

        // this will handle numbers between 1 and 999 which 
        // need no descriptor whatsoever. 

        centenas = number;
        centenas_final_string = string_literal_conversion(centenas);
    } //end if (number ==0) 

    /*if (ereg("un",centenas_final_string))
     {
     centenas_final_string = ereg_replace("","o",centenas_final_string); 
     }*/
    //finally, print the output. 

    /* Concatena los millones, miles y cientos*/
    cad = millions_final_string + thousands_final_string + centenas_final_string;
    /* Convierte la cadena a Mayúsculas*/
    cad = cad.toUpperCase();
    if (centavos.length > 2)
    {

        if (centavos.substring(2, 3) >= 5) {
            centavos = centavos.substring(0, 1) + (parseInt(centavos.substring(1, 2)) + 1).toString();
        } else {

            centavos = centavos.substring(0, 1);
        }
    }

    /* Concatena a los centavos la cadena "/100" */
    if (centavos.length == 1)
    {
        centavos = centavos + "0";
    }
    centavos = centavos + "/100";
    /* Asigna el tipo de moneda, para 1 = PESO, para distinto de 1 = PESOS*/
    if (number == 1)
    {
        moneda = " BOLIVIANOS ";
    } else
    {
        moneda = " BOLIVIANOS ";
    }
    /* Regresa el número en cadena entre paréntesis y con tipo de moneda y la fase M.N.*/
    //Mind Mod, si se deja MIL pesos y se utiliza esta función para imprimir documentos
    //de caracter legal, dejar solo MIL es incorrecto, para evitar fraudes se debe de poner UM MIL pesos
    if (cad == 'MIL ') {
        cad = 'UN MIL ';
    }
    return cad + moneda + centavos + " Bs.";
}



$("#cobrarventa").click(function () {
    var total = $("#totalfijo").text();
    var idventa = $("#idventa").val();
    if (total == '') {
        return swal({title: "Adverencia!",
            text: "No se puede cobrar sin detalle",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (idventa == '') {
        return swal({title: "Adverencia!",
            text: "No se puede cobrar un alquiler dos veces",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var estado;
    var route = "/Validarventaantigua/" + idventa;
    $.get(route, function (res) {
        estado = res;
        if (estado == 2) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "No se puede cobrar un alquiler anulado",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 1 || estado == 5) {
            $('#generadordeventa').show();
            return swal({title: "Adverencia!",
                text: "Este Alquiler ya fue cobrado, Porfavor realize uno nuevo",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }
        if (estado == 0 || estado == 4) {
            var razonsocial = $("#razonsocial").val();
            var nit = parseInt($("#nit").val()) + "";
            var idCliente = $("#idCliente").val();
            if (idCliente == "") {
                idCliente = 1;
            }
            var observaciones = $("#observaciones").val();
            var formapago;
            var cambio;
            var nroCuenta;
            var cobrarCada = $("#cobrarCada").val();
            if (cobrarCada == null || cobrarCada == "") {
                return swal({title: "Advertencia!",
                    text: "Debe seleccionar la fecha de entrega",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1500});
            }
            var pago;
            var Efectivo = document.getElementById('test1').checked;
            var Tarjetadebito = document.getElementById('test2').checked;
            var Tarjetacredito = document.getElementById('test3').checked;
            var Depositobanco = document.getElementById('test4').checked;
            var Cheque = document.getElementById('test5').checked;
            if (Efectivo == true) {
                formapago = "Efectivo";
                pago = $("#pago").val();
                cambio = $("#cambio").val();
            }
            if (Tarjetadebito == true) {
                formapago = "Tarjeta de Debito";
                pago = $("#titular").val();
                cambio = $("#tarjeta").val();
                if (!cambio || !cambio.trim().length) {
                } else {
                    if (valcreditcard(cambio)) {
                    } else {
                        return swal({title: "Adverencia!",
                            text: "Numero de Tarjeta Incorrecto",
                            type: "warning",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                    }
                }
            }
            if (Tarjetacredito == true) {
                formapago = "Tarjeta de Credito";
                pago = $("#titular").val();
                cambio = $("#tarjeta").val();
                if (!cambio || !cambio.trim().length) {
                } else {
                    if (valcreditcard(cambio)) {
                    } else {
                        return swal({title: "Adverencia!",
                            text: "Numero de Tarjeta Incorrecto",
                            type: "warning",
                            showConfirmButton: false,
                            closeOnConfirm: false,
                            timer: 1000});
                    }
                }
            }
            if (Depositobanco == true) {
                formapago = "Deposito Banco";
                pago = $("#deposita").val();
                cambio = $("#nrotran").val();
            }
            if (Cheque == true) {
                formapago = "Cheque";
                pago = $("#anombre").val();
                cambio = $("#cheque").val();
            }
            var idpuntoventa = $("#iddelpuntoventa").val();
            var idelemeplaedo = $("#iddelempleado").val();
            var idmesas = $("#mesa").val();
            var totalliteralventa = $("#totalventaliteral").text();
            var confactura = document.getElementById('sifactura').checked;
            if (confactura) {
                if (!razonsocial || !razonsocial.trim().length) {
                    return swal({title: "Adverencia!",
                        text: "Razon Social Vacia",
                        type: "warning",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                }
                if (!nit || !nit.trim().length) {
                    return swal({title: "Adverencia!",
                        text: "Nit Vacio",
                        type: "warning",
                        showConfirmButton: false,
                        closeOnConfirm: false,
                        timer: 1000});
                    ;
                }
            }
            var garantia = parseInt($("#impgarantia").val());
            if (garantia <= 0 || !nit || !nit.trim().length) {
                return swal({title: "Adverencia!",
                    text: "Debe colocar una garantia para finalizar el alquiler",
                    type: "warning",
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 1000});
            }
            var token = $("#token").val();
            var route = "/Factura";
            $.ajax({
                url: route,
                headers: {'X-CSRF-TOKEN': token},
                type: 'POST',
                dataType: 'json',
                data: {
                    idTipoDescuento: 1,
                    porcentajedescuento: 0,
                    importedescuento: 0,
                    pagos: pago,
                    cambio: cambio,
                    idventa: idventa,
                    idpuntoventa: idpuntoventa,
                    total: total,
                    totaltotal: total,
                    valorliteral: totalliteralventa,
                    nombre: razonsocial,
                    nit: nit,
                    idelemeplaedo: idelemeplaedo,
                    pago: formapago,
                    idmesas: idmesas,
                    aCuenta: 0,
                    saldoACobrar: 0,
                    cuotasSaldo: 0,
                    cobrarCada: cobrarCada,
                    idCliente: idCliente,
                    facturacredito: confactura,
                    observaciones: observaciones,
                    garantia: garantia,
                    DireccionEnvio: "S/E",
                    Importetransporte: "S/E",
                    FechaEntrega: "S/E",
                    HoraEntrega: "S/E",
                    estadoenvio: "S/E",
                    celular: "S/E",
                    departamento: 0,
                    nroCuenta: 0,
                    PersonaRecibeenvio: "S/E"
                },
                success: function ($idfactura) {
                    var nFactura;
                    $($idfactura).each(function (key, value) {
                        if (value == "No se puede generar factura porque no existe libro de órdenes activos") {
                            return swal({title: "Adverencia!",
                                text: "No se puede generar factura porque no existe libro de órdenes activos",
                                type: "warning",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1000});
                        }
                        if (value == "Fecha limite a Terminado") {
                            return swal({title: "Adverencia!",
                                text: "Fecha limite a Terminado",
                                type: "warning",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1000});
                        }
                        if (value == "limite de numero de factura") {
                            return  swal({title: "Adverencia!",
                                text: "Limite de numero de factura",
                                type: "warning",
                                showConfirmButton: false,
                                closeOnConfirm: false,
                                timer: 1000});
                        } else {
                            $(value).each(function (key, values) {
                                nFactura = values.id;
                                $("#datosfactura").empty();
                                $("#garantia").val("");
                                $('#razonsocial').val("");
                                $('#nit').val("");
                                $('#idventa').val("");
                                document.getElementById('totalfijo').innerHTML = "0";
                                $("#modal1").closeModal();
                                $('#generadordeventa').show();
                                if (confactura == true) {
                                    window.open("/imprirfactura/" + nFactura);
                                } else {
                                    window.open("/imprimirnotaalquiler/" + idventa);
                                }
                            });
                        }
                    });
                }, error: function () {
                    Materialize.toast('No se encontro un libro de orden', 1000, 'rounded');
                }
            });
        }
    });
});
// takes the form field value and returns true on valid number
function valcreditcard(value) {
    // accept only digits, dashes or spaces
    if (/[^0-9-\s]+/.test(value))
        return false;
    // The Luhn Algorithm. It's so pretty.
    var nCheck = 0, nDigit = 0, bEven = false;
    value = value.replace(/\D/g, "");
    for (var n = value.length - 1; n >= 0; n--) {
        var cDigit = value.charAt(n),
                nDigit = parseInt(cDigit, 10);
        if (bEven) {
            if ((nDigit *= 2) > 9)
                nDigit -= 9;
        }

        nCheck += nDigit;
        bEven = !bEven;
    }

    return (nCheck % 10) == 0;
}

$("#generarventa").click(function () {
    var iddelpuntoventa = $("#iddelpuntoventa").val();
    var idpunto = iddelpuntoventa;
    var route = "/GenerarAlquiler/" + idpunto;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var dato = value.id;
            window.location.href = "/alquilernuevo/" + dato;
        });
    });
});


/*
 * Metodo para guardar un nuevo cliente en la base de datos del sistema
 */
$("#nuevocliente").click(function () {
    var nombre = $("#nombre").val();
    var direccion = $("#direccion").val();
    var telefonoFijo = $("#telefonoFijo").val();
    var correo = $("#correo").val();
    var celular = $("#celular").val();
    var razonSocial = $("#razonSocial").val( );
    var preferencias = $("#preferencias").val();
    var idCiudad = $("#ciudad").val();
    var NIT = parseInt($("#NIT").val()) + "";
    if (!nombre || !nombre.trim().length) {
        Materialize.toast('Nombre es Obligatorio', 1000, 'rounded');
        return;
    }
    if (!razonSocial || !razonSocial.trim().length) {
        Materialize.toast('Razon Social Vacia', 1000, 'rounded');
        return;
    }
    if (!NIT || !NIT.trim().length) {
        Materialize.toast('NIT vacio', 1000, 'rounded');
        return;
    }
    if (!correo || !correo.trim().length) {
    } else {
        if (!valEmail(correo)) {
            Materialize.toast('Inserte una direccion de correo correcta', 2000, 'rounded');
            return;
        }
    }
    var tipocliente = $("#tipocliente").val( );
    var descuentos = $("#descuento").val();
    var route = "/Cliente";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            nombre: nombre,
            direccion: direccion,
            telefonoFijo: telefonoFijo,
            correo: correo,
            celular: celular,
            razonSocial: razonSocial,
            NIT: NIT,
            Preferencias: preferencias,
            idCiudad: idCiudad,
            idTipoCliente: tipocliente,
            idDescuento: descuentos
        },
        success: function (data) {
            $("#modal3").closeModal();
            Materialize.toast('Cliente creado exitosamente', 1000, 'rounded');
            $("#nombre").val("");
            $("#direccion").val("");
            $("#telefonoFijo").val("");
            $("#correo").val("");
            $("#celular").val("");
            $("#razonSocial").val("");
            $("#NIT").val("");
            cargarcliente();
            $(data).each(function (key, value) {
                $("#cliNombre").val(value.nombre);
                $("#idCliente").val(value.id);
                $("#cliNombre2").val(value.nombre);
                var route = "/nitrazoncliente/" + value.id;
                $.get(route, function (res) {
                    $(res).each(function (key, value) {
                        $("#nit").val(value.nit);
                        $("#razonsocial").val(value.razonSocial);
                    });
                });
            });
        },
        error: function () {
            Materialize.toast('Error al Guardar el Cliente', 2000, 'rounded');
        }
    });
});

/*
 * Metodo para cargar los clientes en el data list al momento de seleccionar el 
 *  campo de texto para realizar la busqueda
 * @returns {undefined}
 */
function cargarcliente() {
    $('#clienteslist')
            .find('option')
            .remove()
            .end();
    var route = "/listarcliente/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#clienteslist').append('<option data-id=' + value.id + ' value="' + value.nombre + '"> NIT:' + value.NIT);
        });
    });
}

function cargaralmacendesucursal() {
    var idpunto = $("#iddelpuntoventa").val();
    $('#almacencombo')
            .find('option')
            .remove()
            .end();
    $('#almacencombo').material_select();
    var route = "/listaralmacensucursal/" + idpunto;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#almacencombo').append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#almacencombo').material_select();
        });
        $("#almacencombo").val($("#idalmacen").val());
        $("#almacn option:selec").val($("#idalmacen").val());
        $('#almacencombo').material_select();
    });
}