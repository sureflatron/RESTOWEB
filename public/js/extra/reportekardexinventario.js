$(document).ready(function() {
  var tabladatos=$('#datos');
  var fechaInicio=$('#fechaInicio').val();
  var fechaFin=$('#fechaFin').val();
  var idProducto=$('#idProducto').val();
  var idAlmacen=$('#idAlmacen').val();
  Mostrar(idProducto);
  var route = "/reportKardexDatos/"+fechaInicio+"/"+fechaFin+"/"+idProducto+"/"+idAlmacen;
  $('#datos').empty();
  debugger;
  $.get(route,function(res){
    $(res).each(function(key,value){
      debugger;
      tabladatos.append("<tr>"+
        "<td>"+value.fecha+"</td>"+
        "<td>"+value.transaccion+"</td>"+
        "<td>"+value.origen+"</td>"+
        "<td>"+value.ingresos+"</td>"+
        "<td>"+value.egresos+"</td>"+
        "</tr>");
    });
  });
});

function imprimir(){
  var ficha = document.getElementById('imprmir');
	var ventimp = window.open(' ', 'popimpr');
	ventimp.document.write( ficha.innerHTML );
	ventimp.document.close();
	ventimp.print( );
	ventimp.close();
}

$("#descargar").click(function(){
  debugger;
  var fechaInicio=$('#fechaInicio').val();
  var fechaFin=$('#fechaFin').val();
  var idProducto=$('#idProducto').val();
  var idAlmacen=$('#idAlmacen').val()
  var route = "/descargarreportekardex/"+fechaInicio+"/"+fechaFin+"/"+idProducto+"/"+idAlmacen;
  $.get(route,function(res){
    $(res).each(function(key,value){
      value;
      debugger;
    });
  });
});

function Mostrar(idProducto){
  var route = "/Producto/"+idProducto+"/edit";
  $.get(route, function(res){
    $(res).each(function(key,value){
      $("#nombre").text(value.nombre);
      $("#descripcion").text(value.descripcion);
      $("#codigoInterno").text(value.codigoInterno);
      $("#codigoBarra").text(value.codigoDeBarra);
      $("#material").text(value.material);
      $("#color").text(value.material);
      $("#tamano").text(value.tamano);
      $("#precioVenta").text(value.precioVenta);
    });
  });
}