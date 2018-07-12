 

$(document).ready(function(){
  Cargar();
});
 
var contador=0;
function Cargar(){
 debugger;
 	var id=$('#id').val();
 var route = "/CargarReportVentacruzdas/"+id;
 var tabladatos=$('#datos');
$('#datos').empty();
 $.get(route,function(res){
   $(res).each(function(key,value){
   contador++;
$('#medio').attr('rowspan', contador);
 
  debugger;
tabladatos.append("<tr> <td>"+value.nombre+"</td><td>"
								+value.Cantidad+"</td></tr>");
 
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