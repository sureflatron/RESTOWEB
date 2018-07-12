 $(document).ready(function() {
cargaringreso();
cargaregreso();
});


 function imprimir()
{
 
  var ficha = document.getElementById('imprmir');
	  var ventimp = window.open(' ', 'popimpr');
	  ventimp.document.write( ficha.innerHTML );
	  ventimp.document.close();
	  ventimp.print( );
	  ventimp.close();

}

function cargaringreso(){

var tabladatos=$('#datoingreso');
 var vacio;
///2016-08-26/14
var fechainicio=$('#Fechainicio').val();
var fechafin=$('#fechafin').val();
var idempleado=$('#idempleado').val();

 var route = "/cargaringreso/"+fechainicio+"/"+fechafin+"/"+idempleado;
$('#datoingreso').empty();
 debugger;
$.get(route,function(res){
   $(res).each(function(key,value){
 
  debugger;
tabladatos.append("<tr><td>"+value.Tipo+"</td><td>"+value.id+"</td><td>"+value.fecha+"</td><td>"+value.hora+"</td><td>"+value.empleado+"</td><td>"+value.importe+"</td></tr>");
 
 
 

});
});

var routes = "/totalingreso/"+fechainicio+"/"+fechafin+"/"+idempleado;
$('#datoingreso').empty();
 
 debugger;
$.get(routes,function(ress){
   $(ress).each(function(key,value){
 
 if (value.importe==null) {

$vacio="0";
 }else {

$vacio=value.importe;
 }
  debugger;
 
   document.getElementById('totalingreso').innerHTML = "Total Ingreso : "+$vacio;
 

});
});
}

function cargaregreso(){

var tabladatos=$('#datoegreso');
 var vacio;
///2016-08-26/14
var fechainicio=$('#Fechainicio').val();
var fechafin=$('#fechafin').val();
var idempleado=$('#idempleado').val();

 var route = "/cargaregresos/"+fechainicio+"/"+fechafin+"/"+idempleado;
$('#datoegreso').empty();
 debugger;
$.get(route,function(res){
   $(res).each(function(key,value){
 
  debugger;
tabladatos.append("<tr><td>"+value.Tipo+"</td><td>"+value.id+"</td><td>"+value.fecha+"</td><td>"+value.hora+"</td><td>"+value.empleado+"</td><td>"+value.importe+"</td></tr>");
 


});
});

var routes = "/totalegreso/"+fechainicio+"/"+fechafin+"/"+idempleado;
$('#datoegreso').empty();
 debugger;
$.get(routes,function(ress){
   $(ress).each(function(key,value){
 
 
  debugger;
  if (value.importe==null) {

$vacio="0";
 }else {

$vacio=value.importe;
 }
   document.getElementById('totalegreso').innerHTML = "Total Egreso : "+$vacio;
 

});
});
}