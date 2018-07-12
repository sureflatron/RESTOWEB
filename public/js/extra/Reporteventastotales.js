$(document).ready(function() {
  var tabladatos=$('#datos');
  var vacio;
  var fechainicio=$('#Fechainicio').val();
  var fechafin=$('#fechafin').val();
  var route = "/cargardatostotalesventas/"+fechainicio+"/"+fechafin;
  $('#datos').empty();
  debugger;
  $.get(route,function(res){
    $(res).each(function(key,value){
      if(value.cliente==null){
        $vacio=' ';
      }else{
        $vacio=value.cliente;
      }
      debugger;
      tabladatos.append("<tr>"+
        "<td>"+value.idventa+"</td>"+
        "<td>"+value.fecha+"</td>"+
        "<td>"+value.formaPago+"</td>"+
        "<td>"+$vacio+"</td>"+
        "<td>"+value.sucursal+"</td>"+
        "<td>"+value.total+"</td>"+
        "</tr>");
      document.getElementById('totales').innerHTML = "Total Vendido : "+value.totales;
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
  var Fechainicio=$('#Fechainicio').val();
  var fechafin=$('#fechafin').val();
  var route = "/descargarreporteVentassinusuario/"+Fechainicio+"/"+fechafin;
  $.get(route,function(res){
    $(res).each(function(key,value){
      value;
      debugger;
    });
  });
});