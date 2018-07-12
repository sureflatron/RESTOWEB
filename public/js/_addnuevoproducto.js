$(document).ready(function(){
	cargarbombo();
	cargarOrigen();
	cargarMarca();
});

function cargarbombo(){
	var route = "/TipoProducto/";
	$.get(route, function(res){
		debugger;
		 $(res).each(function(key,value){
	 		$('#categoria').append('<option value='+value.id+' selected>'+value.nombre+'</option>');
	        $('#categoria').material_select();
		});
	});
}

function cargarOrigen(){
	var route = "/listaorigen/";
	$.get(route, function(res){
		debugger;
		$(res).each(function(key,value){
			$('#origen').append('<option value='+value.id+' selected>'+value.nombre+'</option>');
			$('#origen').material_select(); 	
		});
	});
}

function cargarMarca(){
	var route = "/listamarca/";
	$.get(route, function(res){
		debugger;
		$(res).each(function(key,value){
			$('#marca').append('<option value='+value.id+' selected>'+value.nombre+'</option>');
			$('#marca').material_select(); 	
		});
	});
}

jQuery('#seleccionarImagen').on('change', function(e) {
	var Lector,
  	oFileInput = this;
	if (oFileInput.files.length === 0) {
    	return;
    };
	Lector = new FileReader();
	Lector.onloadend = function(e) {
  		jQuery('#vistaPrevia').attr('src', e.target.result);          
  	};
  	Lector.readAsDataURL(oFileInput.files[0]);
});

$("#guardarvolver").click(function(){
 	debugger;
 	var idTipoProducto = $('#categoria').val();
 	var nombre = $('#innombre').val();
 	var descripcion= $('#indescripcion').val();
 	var precioVenta=  $('#inprecioVenta').val();
 	var codInterno = $('#incodInterno').val();
 	var codBarra = $('#incodBarra').val();
 	var marca = $('#marca').val();
 	var origen = $('#origen').val();
 	var material = $('#inmaterial').val();
 	var color = $('#incolor').val();
 	var usado = $('#inusado').val();
 	var tamano = $('#intamano').val();
 	var peso = $("#inpeso").val();
 	var unidadesCaja = $('#inunidadesCaja').val();
 	var stockMin = $('#instockMin').val();
 	var stockMax = $('#instockMax').val();
 	var imagen =jQuery('#vistaPrevia').attr('src');
    if (!nombre || !nombre.trim().length) {
    	Materialize.toast('Campos vacios', 1000, 'rounded');
    	return;
    }else {}
   	if (!descripcion || !descripcion.trim().length) {
    	Materialize.toast('Campos vacios', 1000, 'rounded');
        return;
    }else {}
    if (!precioVenta || !precioVenta.trim().length) {
    	Materialize.toast('Campos vacios', 1000, 'rounded');
    	return;
    }else {}
	var tipoproducto='item';
	var token = $("#token").val();
	var route = "/Producto";
	if (!/^([0-9])*$/.test(precioVenta)){
	 	Materialize.toast('No puede colocar letras en el Campo "Precio de Venta" ', 1000);
	}
    $.ajax({
		url:route,
		headers: {'X-CSRF-TOKEN': token} ,
		type:'POST',
		dataType:'json',
		data:{
			idTipoProducto: idTipoProducto,
			nombre:nombre,
			descripcion:descripcion,
			precioVenta:precioVenta,
			imagen:imagen,
			tipoproducto:tipoproducto,
			codigoInterno:codInterno,
			codigoDeBarra:codBarra,
			idMarca:marca,
			idOrigen:origen,
			material:material,
			color:color,
			usado:usado,
			tamano:tamano,
			peso:peso,
			unidadesCaja:unidadesCaja,
			stockMin:stockMin,
			stockMax:stockMax
		},
		success: function(){
			$('#innombre').val("");
			$('#indescripcion').val("");
			$('#inprecioVenta').val("");
		 	$('#incodInterno').val("");
		 	$('#incodBarra').val("");
		 	$('#inmaterial').val("");
		 	$('#incolor').val("");
		 	$('#inusado').val("");
		 	$('#intamano').val("");
		 	$("#inpeso").val("");
		 	$('#inunidadesCaja').val("");
		 	$('#instockMin').val("");
 			$('#instockMax').val("");
			Materialize.toast('El producto  creado ', 2000, 'rounded');
		},
		error: function() {			 
			Materialize.toast('El producto ya fue creado ', 2000, 'rounded');	 
        }
	});
});

$("#guardar").click(function(){
	debugger;	
	var idTipoProducto = $('#categoria').val();
	var nombre = $('#innombre').val();
	var descripcion= $('#indescripcion').val();
	var precioVenta=  $('#inprecioVenta').val();
	var token = $("#token").val();
	var route = "/Producto";
	if (!/^([0-9])*$/.test(precioVenta)){ 
    	Materialize.toast('No puede colocar letras en el Campo "Precio de Venta" ', 1000);
	}else {}
    $.ajax({
		url:route,
		headers: {'X-CSRF-TOKEN': token} ,
		type:'POST',
		dataType:'json',
		data:{idTipoProducto: idTipoProducto,nombre:nombre,descripcion:descripcion,precioVenta:precioVenta},
		success: function(){
			Materialize.toast('Guardado Exitoso', 1000, 'rounded');
		 	$("#ingredienteycomposicionmostrar").show();
			$("#Guardaryvolveresconder").hide();
		},
		error: function() {
		    Materialize.toast('Erorr al crear producto  !! ', 2000, 'rounded');
		}
	});
});