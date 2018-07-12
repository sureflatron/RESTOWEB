var idcompras = $('#venta').val();
var iddelpuntoventa = $('#iddelpuntoventa').val();
var iddelempleado = $('#iddelempleado').val();
$(document).ready(function () {
      $('#nombreproducto').keypress(function (e) {
        if (e.which == 13) {
            buscarproductoinput();
        }
    });
    
    
    Cargarproducto();
    cargartabla();
    cargardatos();
});

function buscarproductoinput() {
    debugger
    var codigoInterno = $("#codigoInterno").val();
    var nombre = $("#nombreproducto").val();
    var tabladatos = $('#resultadoprodcuto');
    var idalmacen = $("#almacen").val();
    //var almacen = $("#almacen").val();
  //  var sucursal = $("#idsucursal").val();
    tabladatos.empty();
    if (!nombre || !nombre.trim().length) {
    } else {
        var route = "/buscarnombreproductocompra/" + nombre + "/" + idalmacen;
        $.get(route, function (res) {
            debugger
            $('#resultadoprodcuto').empty();
            $(res).each(function (key, value) { 
                if(value.stock<value.stockMin){
                $('#resultadoprodcuto').append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.nropedidos + "</td>" +
                        "<td bgcolor='#73F49E>" + value.stockMax + "</td>" +
                       // "<td>" + value.color + "</td>" +
                        "<td bgcolor='#FF0000'>" + value.stockMin + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stock_optimo + "</td>" +
                        "<td bgcolor='#FF0000'>" + value.stock + "</td>" +
                       // "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");    
                }else {
                    if(value.stock==value.stockMin){
                    $('#resultadoprodcuto').append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.nropedidos + "</td>" +
                        "<td>" + value.stockMax + "</td>" +
                       // "<td>" + value.color + "</td>" +
                        "<td bgcolor='#FF9100'>" + value.stockMin + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stock_optimo + "</td>" +
                        "<td bgcolor='#FF9100'>" + value.stock + "</td>" +
                       // "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");      
                    }else {
                        $('#resultadoprodcuto').append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.nropedidos + "</td>" +
                        "<td>" + value.stockMax + "</td>" +
                       // "<td>" + value.color + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stockMin + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stock_optimo + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stock + "</td>" +
                       // "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");  
                    }
                     
                }
                
                cargarunidaddemedida(value.id);                
            });
            $('.materialboxed').materialbox();
        });
        return false;
    }
    if (!codigoInterno || !codigoInterno.trim().length) {
    } else {
        var route = "/buscarcodintero/" + codigoInterno + "/" + idalmacen + "/" + tipodeventa + "/" + sucursal;
        $.get(route, function (res) {
            $('#resultadoprodcuto').empty();
            $(res).each(function (key, value) {
                var Stock;
                if (value.stock == null) {
                    Stock = 0;
                } else {
                    Stock = value.stock;
                }
                $('#resultadoprodcuto').append("<tr>" +
                        "<td><img src='" + value.imagen + "' alt='img-prod' width='64px' height='64px' class='circle responsive-img valign profile-image materialboxed' /></td>" +
                        "<td>" + value.codigoDeBarra + "</td>" +
                        "<td>" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><label id='stockP" + value.id + "' style='font-size: 15px; color: black;'>" + Stock + "</label></td>" +
                        "<td><input type='text' name='' value='" + value.precioVenta + "' id='precioP" + value.id + "' min='0' onkeypress='return NumCheck(event,this)'></td>" +
                        "<td><input type='text' name='' value ='1' id='" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><button class='btn btn-floating waves-efect'  OnClick='agregar(" + value.id + ");'><i class='mdi-av-playlist-add' ></i></button></td>" +
                        "</tr>");
            });
            $('.materialboxed').materialbox();
        });
        return false;
    }
}

var totals;
function openmodal(btn) {
    $("#modal1").openModal();
    Mostrar(btn);
}

function cargartabla() {
    var tabladatos = $('#datos');
    var idventa = $('#venta').val();
    var route = "/listarcomprastotal/" + idventa;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            tabladatos.append("<tr>" +
                    "<td>" + value.nombre + "</td>" +
                    "<td>" + value.descripcion + "</td>" +
                    "<td>" + value.color + "</td>" +
                    "<td>" + value.talla + "</td>" +
                    "<td>" + value.marca + "</td>" +
                    "<td>" + value.cantidad + "</td>" +
                    "<td>" + value.costo + "</td>" +
                    "<td>" + value.subtotal + "</td>" +
                    "<td>" +
                    "<button value=" + value.id + " OnClick='openmodal(this);' class='waves-effect waves-light btn waves-effect btn-floating'  href='#' title='Editar'>" +
                    "<i class='material-icons'>mode_edit</i>" +
                    "</button>" +
                    "</td><td>" +
                    "<button class='btn btn-danger waves-effect btn-floating' value=" + value.id + " OnClick='Eliminar(this);' title='Eliminar'>" +
                    "<i class='material-icons'>delete</i>" +
                    "</button>" +
                    "</td>" +
                    "</tr>");
            totals = value.total;
            document.getElementById('total').innerHTML = value.total;
            $('#saldo').val(value.total);
        });
        paginador();
    });
}

//elimanractualizartotal
function cargardatos() {
    var idventa = $('#venta').val();
    var route = "/obtenerdatoscompra/" + idventa;
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            var fechas = $('#fechas').val();
            var fecha = value.fecha;
            if (fecha == null) {
                $('#fecha').val(fechas);
            } else {
                $('#fecha').val(fecha);
            }
            var valors = value.idProveedor;
            if (valors == null) {
            } else {
                $("#proveedor").val(value.idProveedor);
                $('#proveedor option:selected').val(value.idProveedor);
                $("#proveedor").material_select();
                $("#almacen").val(value.idAlmacen);
                $('#almacen option:selected').val(value.idAlmacen);
                $("#almacen").material_select();
            }
            $('#glosa').val(value.glosa);
            $('#tiempodeorden').val(value.tiempodeorden);
            //tiempodeorden
            if (value.estado == 2) {
                document.getElementById('test2').checked = true;
                $('#nrCuotas').val(value.cuotassaldo);
                $('#saldo').val(value.saldoacobrar);
                $('#aCuenta').val(value.acuenta);
                $('#cobrarCada').val(value.cobrarcada);
                $('#pagoCredito').show();
            } else {
                document.getElementById('test1').checked = true;
            }
        });
    });
}

function Cargaringrediente() {
    var route = "/listaingredientes/";
    $('#datos').empty();
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#ingrediente").append('<option value=' + value.id + '  >' + value.nombre + '</option>');
            $("#ingrediente").material_select();
            $("#ingredientes").append('<option value=' + value.id + '  >' + value.nombre + '</option>');
            $("#ingredientes").material_select();
        });
    });
}

function Cargarproducto() {
    var route = "/listadeproducto/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $("#ingrediente").append('<option value=' + value.id + '  >' + value.nombre + '</option>');
            $("#ingrediente").material_select();
        });
    });
}

function Mostrar(btn) {
    var route = "/Detallecompra/" + btn.value;
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#id').val(value.id);
            document.getElementById('nombre').innerHTML = value.nombre;
            $('#cantidads').val(value.cantidad);
            $('#costos').val(value.costo); //nombre
        });
    });
}

$("#guardaryimprimir").click(function () {
	
    var saldoacobrar = $("#saldo").val();
    var acuenta = $("#aCuenta").val();
    var cobrarcada = $("#cobrarCada").val();
    var cuotassaldo = $("#nrCuotas").val();
    var total = $("#total").text();
    var id = $("#venta").val();
    debugger
    var fecha = $("#fecha").val();
    var proveedor = $("#proveedor").val();
    var glosa = $('#glosa').val();
    var tiempodeorden = $('#tiempodeorden').val();
    var almacen = $("#almacen").val();
    var formapago;
    var Efectivo = document.getElementById('test1').checked;
    var Credito = document.getElementById('test2').checked;
    var tarjetaDebito = document.getElementById('test3').checked;
    var tarjetaCredito = document.getElementById('test4').checked;
    var depositobanco = document.getElementById('test5').checked;
    var Cheque = document.getElementById('test6').checked;
    var estado = 0;
    if (Efectivo == true) {
        estado = 1;
        formapago = "Efectivo";

    }
    if (Credito == true) {
        estado = 2;
        formapago = "Credito";
        if (!acuenta || !acuenta.trim().length) {
            return swal({title: "Advertencia!",
                text: "Debe ingresar un monto en  A Cuenta",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        total = parseFloat(total);
        if (acuenta >= total) {
            return swal({title: "Advertencia!",
                text: "Debe dejar a cuenta un monto menor al total de la compra",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        if (!cuotassaldo || !cuotassaldo.trim().length) {
            return swal({title: "Advertencia!",
                text: "Debe ingresar el numero de cuotas del credito",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        cuotassaldo = parseInt(cuotassaldo);
        if (cuotassaldo <= 0) {
            return swal({title: "Advertencia!",
                text: "El numero de cuotas debe ser mayor a 0",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        cobrarcada = parseInt(cobrarcada);
        if (cobrarcada >= 31 || cobrarcada <= 0) 
        {
            return swal({title: "Advertencia!",
                text: "Debe colocar una fecha entre 1 y 30 en el campo Cobrar Cada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
    }
    if (tarjetaDebito == true) {
        estado = 1;
        formapago = "Tarjeta de Debito";
    }
    if (tarjetaCredito == true) {
        estado = 1;
        formapago = "Tarjeta de Credito";
    }
    if (depositobanco == true) {
        estado = 1;
        formapago = "Deposito a Banco";
    }
    if (Cheque == true) {
        estado = 1;
        formapago = "Cheque";
    }
  
    if (Efectivo == true) {
        saldoacobrar = "0";
    }

    var route = "/Compras/" + id + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {fecha: fecha,
            proveedor: proveedor,
            formapago: formapago,
            iddelpuntoventa: iddelpuntoventa,
            glosa: glosa,
            tiempodeorden:tiempodeorden,
            almacen: almacen,
            estado: estado,
            saldoacobrar: saldoacobrar,
            acuenta: acuenta,
            cobrarcada: cobrarcada,
            cuotassaldo: cuotassaldo
        },
        success: function () {
            debugger
            window.open("/reportecompra/" + id + "/" + iddelempleado);
            Materialize.toast('Guardado!!', 1000);
            window.location.href = "/Gestionarcompras";
        },
        error: function () {
            Materialize.toast('Error   !! ', 2000, 'rounded');
        }
    });
});


$("#ordenaryimprimir").click(function () {    
    var saldoacobrar = $("#saldo").val();
    var acuenta = $("#aCuenta").val();
    var cobrarcada = $("#cobrarCada").val();
    var cuotassaldo = $("#nrCuotas").val();
    var total = $("#total").text();
    var id = $("#venta").val();//id de la compra    
    var fecha = $("#fecha").val();
    var proveedor = $("#proveedor").val();
    var glosa = $('#glosa').val();
    //debugger
    var tiempodeorden = $('#tiempodeorden').val();
    
    var almacen = $("#almacen").val();
    var formapago;
    var ordenestado=0;
    var Efectivo = document.getElementById('test1').checked;
    var Credito = document.getElementById('test2').checked;
    var tarjetaDebito = document.getElementById('test3').checked;
    var tarjetaCredito = document.getElementById('test4').checked;
    var depositobanco = document.getElementById('test5').checked;
    var Cheque = document.getElementById('test6').checked;
    var estado = 0;
    if (Efectivo == true) {
        //estado = 1;//antes de el estado de la orden de compra 
        estado = 0;
        formapago = "Efectivo";

    }
    if (Credito == true) {
        estado = 2;
        formapago = "Credito";
        if (!acuenta || !acuenta.trim().length) {
            return swal({title: "Advertencia!",
                text: "Debe ingresar un monto en  A Cuenta",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        total = parseFloat(total);
        if (acuenta >= total) {
            return swal({title: "Advertencia!",
                text: "Debe dejar a cuenta un monto menor al total de la compra",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        if (!cuotassaldo || !cuotassaldo.trim().length) {
            return swal({title: "Advertencia!",
                text: "Debe ingresar el numero de cuotas del credito",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        cuotassaldo = parseInt(cuotassaldo);
        if (cuotassaldo <= 0) {
            return swal({title: "Advertencia!",
                text: "El numero de cuotas debe ser mayor a 0",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        cobrarcada = parseInt(cobrarcada);
        if (cobrarcada >= 31 || cobrarcada <= 0) 
        {
            return swal({title: "Advertencia!",
                text: "Debe colocar una fecha entre 1 y 30 en el campo Cobrar Cada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
    }
    debugger
    if(tiempodeorden<0 || tiempodeorden=="")
    {
      return swal({title: "Advertencia!",
                text: "Tiempo de orden no puede ser 0 o vacio",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});  
    }
    //return 0
    if (tarjetaDebito == true) {
        estado = 1;
        formapago = "Tarjeta de Debito";
    }
    if (tarjetaCredito == true) {
        estado = 1;
        formapago = "Tarjeta de Credito";
    }
    if (depositobanco == true) {
        estado = 1;
        formapago = "Deposito a Banco";
    }
    if (Cheque == true) {
        estado = 1;
        formapago = "Cheque";
    }
  
    if (Efectivo == true) {
        saldoacobrar = "0";
    }

    var route = "/Compras/" + id + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {fecha: fecha,
            proveedor: proveedor,
            formapago: formapago,
            iddelpuntoventa: iddelpuntoventa,
            glosa: glosa,
            ordenestado:ordenestado,
            tiempodeorden:tiempodeorden,
            almacen: almacen,
            estado: estado,
            saldoacobrar: saldoacobrar,
            acuenta: acuenta,
            cobrarcada: cobrarcada,
            cuotassaldo: cuotassaldo
        },
        success: function ($route) {
            debugger
             $($route).each(function (key, value) {
                 debugger
                 if(value=="bien"){
                      debugger
                      window.open("/reporteordencompra/" + id + "/" + iddelempleado);
                      Materialize.toast('Guardado!!', 1000);
                      window.location.href = "/Gestionarcompras";
                 }else {
                      swal({title: "Mal!",
                      text: "Agrege Detalle de Productos A La Orden",
                      type: "warning",
                      showConfirmButton: false,
                      closeOnConfirm: false,
                      timer: 2000});
                  // window.location.reload();
                 }
               
                 
             });
           
        },
        error: function () {
            Materialize.toast('Error   !! ', 2000, 'rounded');
        }
    });
});


$("#volderyeliminar").click(function () {
    var route = "/eliminarordencompra";
    
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {idcompras: idcompras},
        success: function () {
            debugger
            $('#tablacategoria').DataTable().destroy();
            cargartabla();            
            swal({title: "Bien!",
                text: "Orden Eliminada",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
            window.location.href = "/Gestionarcompras";
        },
        error: function () {
            swal({title: "Mal!",
                text: "Error Al Eliminar",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.reload();
        }
    });
});
//voldersindatos

$("#actualizaryimprimir").click(function () {
    var tiempodeorden = $("#tiempodeorden").val();
    var total = $("#total").text();
    var id = $("#venta").val();
    var fecha = $("#fecha").val();
    var proveedor = $("#proveedor").val();
    var glosa = $('#glosa').val();
    var almacen = $("#almacen").val();
    var saldoacobrar = $("#saldo").val();
    var acuenta = $("#aCuenta").val();
    var cobrarcada = $("#cobrarCada").val();
    var cuotassaldo = $("#nrCuotas").val();
    var formapago;
    var Efectivo = document.getElementById('test1').checked;
    var Credito = document.getElementById('test2').checked;
    var tarjetaDebito = document.getElementById('test3').checked;
    var tarjetaCredito = document.getElementById('test4').checked;
    var depositobanco = document.getElementById('test5').checked;
    var Cheque = document.getElementById('test6').checked;
    var estado = 0;
    if (Efectivo == true) {
        estado = 1;
        formapago = "Efectivo";
    }
    if (Credito == true) {
        estado = 2;
        formapago = "Credito";
        if (!acuenta || !acuenta.trim().length) {
            return swal({title: "Advertencia!",
                text: "Debe ingresar un monto en  A Cuenta",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        total = parseFloat(total);
        if (acuenta >= total) {
            return swal({title: "Advertencia!",
                text: "Debe dejar a cuenta un monto menor al total de la compra",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        if (!cuotassaldo || !cuotassaldo.trim().length) {
            return swal({title: "Advertencia!",
                text: "Debe ingresar el numero de cuotas del credito",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        cuotassaldo = parseInt(cuotassaldo);
        if (cuotassaldo <= 0) {
            return swal({title: "Advertencia!",
                text: "El numero de cuotas debe ser mayor a 0",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
        cobrarcada = parseInt(cobrarcada);
        if (cobrarcada >= 31 || cobrarcada <= 0) {
            return swal({title: "Advertencia!",
                text: "Debe colocar una fecha entre 1 y 30 en el campo Cobrar Cada",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
        }
    }
    if (tarjetaDebito == true) {
        estado = 1;
        formapago = "Tarjeta de Debito";
    }
    if (tarjetaCredito == true) {
        estado = 1;
        formapago = "Tarjeta de Credito";
    }
    if (depositobanco == true) {
        estado = 1;
        formapago = "Deposito a Banco";
    }
    if (Cheque == true) {
        estado = 1;
        formapago = "Cheque";
    }
    if (tiempodeorden<0 || tiempodeorden==""){
         return swal({title: "Advertencia!",
                text: "El Tiempo de Orden no debe ser <0 || Vacio",
                type: "warning",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
    }
    debugger
    var route = "/Compras/" + id + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            tiempodeorden: tiempodeorden,
            fecha: fecha,
            proveedor: proveedor,
            glosa: glosa,
            formapago: formapago,
            almacen: almacen,
            estado: estado,
            saldoacobrar: saldoacobrar,
            acuenta: acuenta,
            cobrarcada: cobrarcada,
            cuotassaldo: cuotassaldo,
            iddelpuntoventa: iddelpuntoventa
        },
        success: function () {
            window.open("/reportecompra/" + id + "/" + iddelempleado);
            Materialize.toast('Actualización completada', 1000);
            window.location.href = "/Gestionarcompras";
        }, error: function () {
            Materialize.toast('Error !! ', 2000, 'rounded');
        }
    });
});

function Eliminar(btn) {
    $total = document.getElementById('total').innerHTML;
    var idetallecompra = btn.value;
    var route = "/elimanractualizartotal";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {idetallecompra: idetallecompra, idcompras: idcompras},
        success: function () {
            $('#tablacategoria').DataTable().destroy();
            cargartabla();
            document.getElementById('total').innerHTML = "0";
            swal({title: "Bien!",
                text: "Producto Eliminado",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        },
        error: function () {
            swal({title: "Bien!",
                text: "Error Al Eliminar",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.reload();
        }
    });
}

$("#actualizar").click(function () {
    var value = $("#id").val();
    var idcompra = $('#venta').val();
    var compra = $("#venta").val();
    var cantidad = $('#cantidads').val();
    if (!cantidad || !cantidad.trim().length) {
        return swal({title: "Advertencia!",
            text: "Ingrese la Cantidad",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (parseInt(cantidad) <= 0) {
        return swal({title: "Advertencia!",
            text: "La cantidad debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var costo = $('#costos').val();
    if (!costo || !costo.trim().length) {
        return swal({title: "Advertencia!",
            text: "Ingrese el Costo",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (parseInt(costo) <= 0) {
        return swal({title: "Advertencia!",
            text: "EL Costo debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    var total = cantidad * costo;
    var route = "/Detallecompra/" + value + "";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'PUT',
        dataType: 'json',
        data: {
            idcompra: idcompra,
            cantidad: cantidad,
            costo: costo,
            total: total,
            compra: compra
        },
        success: function () {
            $('#tablacategoria').DataTable().destroy();
            cargartabla();
            $("#modal1").closeModal();
            swal({title: "Bien!",
                text: "Cantidad Actualizada",
                type: "success",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
        }, error: function () {
            swal({title: "Error!",
                text: "Error al actualizar",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 1000});
            window.location.reload();
        }
    });
});

$("#buscarproducto").click(function () {
debugger
    var codigoBarra = $("#codigoBarra").val();
    var codigoInterno = $("#codigoInterno").val();
    var nombre = $("#nombreproducto").val();
    var tabladatos = $('#resultadoprodcuto');
    $('#resultadoprodcuto').empty();
    if (!nombre || !nombre.trim().length) {
    } else {
        var route = "/buscarProducto/" + nombre + "/1";
        $.get(route, function (res) {
            $('#resultadoprodcuto').empty();
            $(res).each(function (key, value) {
                $("#codigoBarra").val("");
                   if(value.stock<value.stockMin){
                       $('#resultadoprodcuto').append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                       // "<td>" + value.color + "</td>" +
                        "<td bgcolor='#FF0000'>" + value.stockMin + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stockMax + "</td>" +
                        "<td bgcolor='#FF0000'>" + value.stock + "</td>" +
                       // "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");   
                   }else {
                       if(value.stock==value.stockMin){
                           $('#resultadoprodcuto').append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                       // "<td>" + value.color + "</td>" +
                        "<td bgcolor='#FF9100'>" + value.stockMin + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stockMax + "</td>" +
                        "<td bgcolor='#FF9100'>" + value.stock + "</td>" +
                       // "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");   
                       }else {
                           $('#resultadoprodcuto').append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                       // "<td>" + value.color + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stockMin + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stockMax + "</td>" +
                        "<td bgcolor='#73F49E'>" + value.stock + "</td>" +
                       // "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");   
                       }
                   }
                 
                cargarunidaddemedida(value.id);
            });
        });
        return;
    }

    if (!codigoInterno || !codigoInterno.trim().length) {
    } else {
        $('#resultadoprodcuto').empty();
        var route = "/buscarProducto/" + codigoInterno + "/1";
        $.get(route, function (res) {
            $('#resultadoprodcuto').empty();
            $(res).each(function (key, value) {
                $("#codigoBarra").val("");
                tabladatos.append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");
                cargarunidaddemedida(value.id);
            });
        });
        return;
    }
    if (!codigoBarra || !codigoBarra.trim().length) {
    } else {
        $('#resultadoprodcuto').empty();
        var route = "/buscarProducto/" + codigoInterno + "/1";
        $.get(route, function (res) {
            $('#resultadoprodcuto').empty();
            $(res).each(function (key, value) {
                $("#codigoBarra").val("");
                tabladatos.append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return isNumberKey(this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");
                cargarunidaddemedida(value.id);
            });
        });
        return;
    }
});

function codigobarraagregardetalle() {
    var codigoBarra = $("#codigoBarra").val();
    var tabladatos = $('#resultadoprodcuto');
    if (!codigoBarra || !codigoBarra.trim().length) {
        return;
    } else {
        var route = "/buscarProducto/" + codigoBarra + "/1";
        $.get(route, function (res) {
            $(res).each(function (key, value) {
                $("#codigoBarra").val("");
                tabladatos.append("<tr>" +
                        "<td>" + value.codigoInterno + "</td>" +
                        "<td><input type='hidden'  id=" + value.id + " value=" + value.id + ">" + value.nombre + "</td>" +
                        "<td>" + value.descripcion + "</td>" +
                        "<td>" + value.material + "</td>" +
                        "<td>" + value.color + "</td>" +
                        "<td>" + value.tamano + "</td>" +
                        "<td>" + value.marca + "</td>" +
                        "<td><select id='uni" + value.id + "''><option></option></select></td>" +
                        "<td><input type='text' name='' value ='1' id='cant" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><input type='text' name='' value ='1' id='cost" + value.id + "' onkeypress='return NumCheck(event, this);'></td>" +
                        "<td><button class='btn waves-effect btn-floating'  OnClick='agregardetallecompra(" + value.id + ");'><i class='mdi-av-playlist-add'></i></button></td>" +
                        "</tr>");
                cargarunidaddemedida(value.id);
            });
        });
    }
}

function agregardetallecompra($pro) {
    var idcompra = $('#venta').val();
    var idingrediente = $('#' + $pro).val();
    var cantidad = $('#cant' + $pro).val();
    var costo = $('#cost' + $pro).val();
    var unidadmedida = $('#uni' + $pro).val();
    var total = cantidad * costo;
    if (!cantidad || !cantidad.trim().length) {
        return swal({title: "Advertencia!",
            text: "Cantidad Vacia",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (parseInt(cantidad) <= 0) {
        return swal({title: "Advertencia!",
            text: "La cantidad debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (!costo || !costo.trim().length) {
        return swal({title: "Advertencia!",
            text: "Costo Vacio",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    if (parseInt(costo) <= 0) {
        return swal({title: "Advertencia!",
            text: "EL costo debe ser mayor a 0",
            type: "warning",
            showConfirmButton: false,
            closeOnConfirm: false,
            timer: 1000});
    }
    debugger
    var route = "/Detallecompra";
    var token = $("#token").val();
    $.ajax({
        url: route,
        headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: {
            idcompra: idcompra,
            idingrediente: idingrediente,
            cantidad: cantidad,
            costo: costo,
            total: total,
            unidadmedida: unidadmedida
        },
        success: function ($route) {
            $($route).each(function (key, value) {
                var tipo;
                if (value.mensaje == "Producto agregado con exito a la Compra") {
                    tipo = "success";
                } else {
                    tipo = "warning"
                }

                $('#tablacategoria').DataTable().destroy();
                cargartabla();
                swal({title: value.mensaje,
                    type: tipo,
                    showConfirmButton: false,
                    closeOnConfirm: false,
                    timer: 3000});
                $('#codigoInterno').val("");
                $('#nombreproducto').val("");
                $('#codigoBarra').val("");
                
                notificacionBasica(idingrediente,idcompra,cantidad)
            });
        },
        error: function () {
            swal({title: "Error!",
                type: "error",
                showConfirmButton: false,
                closeOnConfirm: false,
                timer: 2000});
           // window.location.reload();
        }
    });
}

function notificacionBasica(idingrediente,idcompra,cantidad) {
     var notification = null;
    if (!('Notification' in window)) {
        // el navegador no soporta la API de notificaciones
        alert('Su navegador no soporta la API de Notificaciones :(');
        return;
    } else if (Notification.permission === "granted") {
        debugger        
        var route = "/validarstockmaximocompra/" + idingrediente + "/" + idcompra + "/" + cantidad;
        $.get(route, function(res) {
            debugger          
            if (res.mensaje != "ninguno")
            {
                notification = new Notification(
                        "Control StockMin de Seguridad", {
                    body: res.mensaje,
                    dir: 'ltr',
                     icon: '../images/save64.png'
                    //icon: "{{asset('images/save64.png')}}"
                    //{{public_path().'/perfilnuevo/'.$inscripcion->foto}}
                });
            }
        });
    } else if (Notification.permission !== 'denied') {
        // se pregunta al usuario para emplear las notificaciones
        Notification.requestPermission(function(permission) {
            if (permission === "granted") {
                notification = new Notification(
                        "Hola Mundo");
            }
        });
     }
 }

function cargarunidaddemedida($id) {
    $('#uni' + $id)
            .find('option')
            .remove()
            .end()
            .val('null');
    $('#uni' + $id).material_select();
    var route = "/listaunidad/";
    $.get(route, function (res) {
        $(res).each(function (key, value) {
            $('#uni' + $id).append('<option value=' + value.id + ' >' + value.nombre + '</option>');
            $('#uni' + $id).material_select();
        });
    });
}

function paginador() {
    $('#tablacategoria').DataTable({
        "pagingType": "full_numbers",
        retrieve: true,
        "order": [0, "desc"],
        responsive: true
    });
}

function mostrar() {
    $('#pagoCredito').show();
}

function ocultar() {
    $('#pagoCredito').hide();
}

function calcular_totalCredito() {
    importe_total = 0;
    var entradavalor = $('#aCuenta').val();
    var totaltraido = totals;
    if (totaltraido == '') {
        totaltraido = totalfactura;
    }
    var numero = parseInt(totaltraido);
    importe_total = numero - entradavalor;
    if (importe_total < 0) {
        $('#saldo').val("0");
    } else {
        $("#saldo").val(importe_total);
    }
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function NumCheck(e, field) {
    key = e.keyCode ? e.keyCode : e.which
    // backspace
    if (key == 8)
        return true
    // 0-9
    if (key > 47 && key < 58) {
        if (field.value == "")
            return true
        regexp = /.[0-9]{4}$/
        return !(regexp.test(field.value))
    }
    // .
    if (key == 46) {
        if (field.value == "")
            return false
        regexp = /^[0-9]+$/
        return regexp.test(field.value)
    }
    // other key
    return false

}