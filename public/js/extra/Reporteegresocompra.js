 $(document).ready(function() {
cargardato();
});

 function cargardato(){

 	var tabladatos=$('#datos');
 var vacio;
///2016-08-26/14
var fechainicio=$('#Fechainicio').val();
var fechafin=$('#fechafin').val();
var idempleado=$('#idempleado').val();
 var route = "/egresocompras/"+fechainicio+"/"+fechafin;
$('#datos').empty();
 debugger;
$.get(route,function(res){
   $(res).each(function(key,value){
 if(value.cliente==null){
$vacio=' ';
   	
 }
  debugger;
tabladatos.append("<tr><td>"+value.Tipo+"</td><td>"+value.id+"</td><td>"+value.fecha+"</td><td>"+value.hora+"</td><td>"+value.empleado+"</td><td>"+value.importe+"</td></tr>");
 
 
 

});
});
 }




  function imprimir()
{
 
  var ficha = document.getElementById('imprmir');
	  var ventimp = window.open(' ', 'popimpr');
	  ventimp.document.write( ficha.innerHTML );
	  ventimp.document.close();
	  ventimp.print( );
	  ventimp.close();

}