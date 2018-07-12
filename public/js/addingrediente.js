$(document).ready(function(){
Cargar();
});

function Cargar(){
var tabladatos=$('#datos');
debugger;
var route = "/listaingredientes/";
$('#datos').empty();
$.get(route,function(res){
   $(res).each(function(key,value){
tabladatos.append("<tr><td>"+value.nombre+"</td><td><button value="+value.id+" OnClick='Mostrar(this);' class='waves-effect waves-light btn modal-trigger'  href='#modal1'><i class='material-icons'>mode_edit</i></button><button class='btn btn-danger' value="+value.id+" OnClick='Eliminar(this);'><i class='material-icons'>delete</i></button></td></tr>");
    $('.modal-trigger').leanModal();
});
});
}


function Eliminar(btn){
 	 debugger;

 	
 	  if($("#perfilpuedeEliminar").val()==1){



 	 var route = "/Ingrediente/"+btn.value+"";
	var token = $("#token").val();

	$.ajax({
		url: route,
		headers: {'X-CSRF-TOKEN': token},
		type: 'DELETE',
		dataType: 'json',
		success: function(){
			Cargar();
		  Materialize.toast('Eliminación completada', 1000);
		}
	});
	}else{ Materialize.toast('No tiene permiso para Eliminar', 1000);}
}

function Mostrar(btn){
	 var route = "/Ingrediente/"+btn.value+"/edit";

	$.get(route, function(res){
	 
		$("#nombre").val(res.nombre);
		$("#id").val(res.id);
	});
}

$("#actualizar").click(function(){
	 debugger;
	  if($("#perfilpuedeModificar").val()==1){



	var value = $("#id").val();
	var nombre=$("#nombre").val();
	var route = "/Ingrediente/"+value+"";
	var token = $("#token").val();
		
			$.ajax({
		url: route,
		headers: {'X-CSRF-TOKEN': token},
		type: 'PUT',
		dataType: 'json',
		data: {nombre: nombre},
		success: function(){
			Cargar();
			limpiarcampo();
		   $("#modal1").closeModal();
  Materialize.toast('Actualización completada', 1000);
		   
		///	$("#msj-success").fadeIn();
		},error: function() {
                   Materialize.toast('Ya exite este otro ingrediente con este nombre : '+nombre, 1500, 'rounded');
                }

	});
  
		}else{ Materialize.toast('No tiene permiso para Modificar', 1000);}   
		 
});
function limpiarcampo(){
$("#nombre").val("");
$("#addnombre").val("");
}

$("#guardar").click(function(){
		 if($("#perfilpuedeGuardar").val()==1){



	debugger;
	var nombre=$("#addnombre").val();

 
       


	var route = "/Ingrediente";
	var token = $("#token").val();
	$.ajax({
url:route,
headers: {'X-CSRF-TOKEN': token} ,
type:'POST',
dataType:'json',
data:{nombre: nombre},
success: function(){
			Cargar();

		   $("#modal2").closeModal();
		   	limpiarcampo();
  Materialize.toast('Guardado Exitoso', 1000, 'rounded');
		   
		///	$("#msj-success").fadeIn();
		},error: function() {
                   Materialize.toast('Ya exite este ingrediente : '+nombre, 1000, 'rounded');
                }
  
});
	}else{ Materialize.toast('No tiene permiso para Guardar', 1000);}
});


 function Buscadordeproducto()
{
debugger;
		var tableReg = document.getElementById('tablaingrediente');
      var searchText = document.getElementById('buscar').value.toLowerCase();
      var cellsOfRow="";
      var found=false;
      var compareWith="";
 	
      for (var i = 1; i < tableReg.rows.length; i++)
      {
        	cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
        	found = false;
 
       for (var j = 0; j < cellsOfRow.length && !found; j++)
       	 {
        	  compareWith = cellsOfRow[j].innerHTML.toLowerCase();
 
         	 if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1))
        	  {
            found = true;
         	 }
        	}
       		 if(found)
        	{
          tableReg.rows[i].style.display = '';

        	} else {
          tableReg.rows[i].style.display = 'none';

        	}

	  }
 }

