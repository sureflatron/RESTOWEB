 
 $(document).ready(function() {
cargaregreso();
 
tarjeta();
cargaringreso();


});
var ingreso;
var egreso ;
var tarjetas;

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
 

 var route = "/cargaringresototal/"+fechainicio+"/"+fechafin ;
$('#datoingreso').empty();
 
$.get(route,function(res){
   $(res).each(function(key,value){
 
 
tabladatos.append("<tr><td>"+value.Tipo+"</td><td>"+value.id+"</td><td>"+value.fecha+"</td><td>"+value.hora+"</td><td>"+value.empleado+"</td><td>"+value.importe+"</td></tr>");
 
 tarjeta();
 

});
});

var routes = "/totalingresototal/"+fechainicio+"/"+fechafin;
$('#datoingreso').empty();
 
 
$.get(routes,function(ress){
   $(ress).each(function(key,value){
 
 if (value.importe==null) {

$vacio="0";
 }else {

$vacio=value.importe;
 }
 
   document.getElementById('totalingreso').innerHTML = "Total Ingreso : "+$vacio;
   document.getElementById('totalingresos').innerHTML =$vacio;
   ingreso=$vacio;
  

});
});

}


function cargaregreso(){

var tabladatos=$('#datoegreso');
 var vacio;
var totaldeegreso="";
var fechainicio=$('#Fechainicio').val();
var fechafin=$('#fechafin').val();
 

 var route = "/cargaregresostotal/"+fechainicio+"/"+fechafin;
$('#datoegreso').empty();
 
$.get(route,function(res){
   $(res).each(function(key,value){
 
tabladatos.append("<tr><td>"+value.Tipo+"</td><td>"+value.id+"</td><td>"+value.fecha+"</td><td>"+value.hora+"</td><td>"+value.empleado+"</td><td>"+value.importe+"</td></tr>");
 


});
});

var routes = "/totalegresototal/"+fechainicio+"/"+fechafin;
$('#datoegreso').empty();

$.get(routes,function(ress){
   $(ress).each(function(key,value){
 
 
  
  if (value.importe==null) {

$vacio="0";
 }else {

$vacio=value.importe;

 }
 $totaldeegreso=value.importe;
   document.getElementById('totalegreso').innerHTML = "Total Egreso : "+$vacio;
      document.getElementById('totalegresos').innerHTML =$vacio;
 
egreso=$vacio;

});
});
 

}
function tarjeta()
{

  var fechainicio=$('#Fechainicio').val();
var fechafin=$('#fechafin').val();
var routes = "/totaltarjeta/"+fechainicio+"/"+fechafin;
 

$.get(routes,function(ress){
   $(ress).each(function(key,value){
 
 
  
  if (value.importe==null) {

$vacio="0";
 }else {

$vacio=value.importe;

 }
 $totaldeegreso=value.importe;
   document.getElementById('totaltarjeta').innerHTML =$vacio;
 
 tarjetas=$vacio;
 
 
 

});
});

}
 