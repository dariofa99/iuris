$("#btn_enviar").click(function(){


	var mydata = "#myform";
	var route = "/actuaciones" ;
	var token = $("#token").val();
	var formData= new FormData(document.getElementById("myform"));

	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: formData,
		 cache: false,
            contentType: false,
            processData: false,



		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			$('#msg-success').fadeIn();
			$(mydata)[0].reset();

			llenarTabla(res); //en caso de exito llama a la funcion llenarTabla para poner nuevo registro
			 alert('Actuación guardada con éxito!!2');
			 $("#myModal_act_create").modal("hide");

		}



	});


/*oculta mensaje y limpia campos*/
$("#btn_modal").click(function(){
		$('#msg-success').hide();
		
});


});







/* <Listar registros> */

$(document).ready(ListarTabla(0));  //Muestra tabla al cargar pagina
//$("#btn_enviar").on("click", ListarTabla); //Muestra tabla al dar clic en agregar nuevo registro 


/* Listar tabla llama los datos consultados desde el controller*/
function ListarTabla(datobandera){

	//var tabla= $("#datos");
	var mydata = $("#id_control_list").val();
	var route = "/actuaciones" ;
	var token = $("#token").val();
	

/*	if (datobandera==1){
		tabla.empty();
	}*/

	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: {id_control_list:mydata, bandera:datobandera}, //se envia id para listar datos que cumplan con ese identificador



		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			
			//console.log(res); //muestra lo que retorna el controlador
			llenarTabla(res);
		}



	});	




}



function llenarTabla(res){

var tabla= $("#datos");
var actuacion_estado;
var color;
var rutadescarga;
var textodescarga;

	$(res).each(function(key, value){


		if (value.actdocnomgen!=''){
			rutadescarga= "/actpdfdownload/"+value.id;
			textodescarga="Descargar Archivo";
		}else{

			rutadescarga= "#"+value.id;
			textodescarga="";

		}


			if(value.actestado=='1'){
				actuacion_estado='Enviado a revisión';
				color='green';

			}else if(value.actestado=='2'){
				actuacion_estado='Pendiente por requerimiento';
				color='yellow';

			}else if(value.actestado=='3'){
				actuacion_estado='Enviado con correcciones';
				color='red';

			}else if(value.actestado=='4'){
				actuacion_estado='Aprobado';
				color='blue';

			}else if(actestado=='0'){
				actuacion_estado='.';
				color='blue';

			}else{
				actuacion_estado='No especificado';
				color='blue';

			}	
				
				tabla.append("<tr role='row' class='odd'><td>"+ value.actnombre + "</td><td>"+ value.actdescrip + "</td><td><span class='pull-center badge bg-"+color+"'>"+ actuacion_estado + "</span></td><td>" + value.actfecha + "</td><td><a href='"+rutadescarga+"'  >"+textodescarga+"</a></td><td> <button type='button' value="+value.id+"  OnClick='Mostrar(this);' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#myModal_act_edit'  >Editar</button>  </td></tr>"); 
	});

}

/* </Listar registros> */







/* Envia id de actuacion */

function Mostrar(btn){

	//console.log(btn.value); //muestra id actuacion

	var route = "/actuaciones/"+btn.value+"/edit" ;

	$.get(route, function(res){

		//console.log(res.actestado); 
		$("#idact").val(res.id);
		$("#actnombre").val(res.actnombre);
		$("#actfecha").val(res.actfecha);
		$("#actdescrip").text(res.actdescrip);
		$("#actestado > option[value="+ res.actestado +"]").attr('selected',true);

		

	});

}





$("#btn_act_edit").click(function(){

	var value = $("#idact").val();
	var mydata = "#myform_act_edit";
	var route = "/actuaciones/"+value+"" ;
	var token = $("#token").val();



	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'PUT',
		datatype: 'json',
		data: $("#myform_act_edit").serialize(),



		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){

		    $('#msg-success').fadeIn();	
			$("#datos").empty();
			$(mydata)[0].reset();
			ListarTabla(0);
		}

	});

	



});





/* */
/*oculta mensaje y limpia campos*/
$("#btn_enviar_req").click(function(){


	var fechahorareq=$("#fechareq").val()+" "+$("#horareq").val();

		$("#reqfechahoracomp12").val(fechahorareq);
		alert('Guardado!!2');
		 $("#mymodal").modal("hide");
});













/*******************************************ajax para pestaña requerimientos*****************************************************/

$("#btn_enviar_req").click(function(){


	var mydata = "#myform_req";
	var route = "/requerimientos" ;
	var token = $("#token").val();

	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: $(mydata).serialize(),


		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			$('#msg-success').fadeIn();
			$(mydata)[0].reset();

			llenarTabla_req(res); //en caso de exito llama a la funcion llenarTabla para poner nuevo registro
			



		}



	});


/*oculta mensaje y limpia campos*/
$("#btn_modal_req").click(function(){
		$('#msg-success').hide();
		
});


});







/* <Listar registros> */

$(document).ready(ListarTabla_req(0));  //Muestra tabla al cargar pagina
//$("#btn_enviar").on("click", ListarTabla); //Muestra tabla al dar clic en agregar nuevo registro 


/* Listar tabla llama los datos consultados desde el controller*/
function ListarTabla_req(datobandera){

	//var tabla= $("#datos");
	var mydata = $("#id_control_list_req").val();
	var route = "/requerimientos" ;
	var token = $("#token").val();
	

/*	if (datobandera==1){
		tabla.empty();
	}*/

	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: {id_control_list_req:mydata, bandera:datobandera}, //se envia id para listar datos que cumplan con ese identificador



		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			
			console.log(res); //muestra lo que retorna el controlador
			llenarTabla_req(res);
		}



	});	




}



function llenarTabla_req(res){

var tabla= $("#datos_req");
var ruta_genpdf;

	$(res).each(function(key, value){

		ruta_genpdf= '/reqpdfgen/'+value.id;

				
				tabla.append("<tr role='row' class='odd'><td>"+ value.reqmotivo + "</td><td>"+ value.reqdescrip + "</td><td>"+ value.reqfecha +"</td><td> <a href='"+ruta_genpdf+"' target='_blank' class='btn btn-primary' role='button'>Imprimir</a>  </td></tr>"); 
	});

}

/* </Listar registros> */













/*................................... <Editar usuario desde expedientes>.................................................... */
/*.......................................................................................................................... */
/*.......................................................................................................................... */

$("#btn_exp_user_carga").click(function(){

	$('#msg-success').hide();

	var value= $("#btn_exp_user_carga").val();

	var route = "/expuser/"+value+"/edit" ;

	
	$.get(route, function(res){

		//console.log(res.email); 
		$("#id").val(res.id);
		$("#idnumber").val(res.idnumber);
		$("#name").val(res.name);
		$("#lastname").val(res.lastname);
		$("#description").val(res.description);
		$("#address").val(res.address);
		$("#email").val(res.email);
		$("#tel1").val(res.tel1);
		$("#tel2").val(res.tel2);
		$("#estrato > option[value="+ res.estrato +"]").attr('selected',true);
		$("#estadocivil > option[value="+ res.estadocivil +"]").attr('selected',true);
		$("#genero > option[value="+ res.genero +"]").attr('selected',true);

		

	});

});




$("#btn_exp_user_edit").click(function(){

	var value= $("#id").val();
	var mydata = "#myform_exp_user_edit";
	var route = "/expuser/"+value+"" ;
	var token = $("#token").val();



	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'PUT',
		datatype: 'json',
		data: $("#myform_exp_user_edit").serialize(),



		
		success:function(res){

		    $('#msg-success').fadeIn();	

		}

	});

});













/*................................... <crear usuario desde expedientes>.................................................... */
/*.......................................................................................................................... */
/*.......................................................................................................................... */







$("#btn_exp_user_create").click(function(){


	var mydata = "#myform_exp_user_create";
	var route = "/expuser" ;
	var token = $("#token").val();


	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: $(mydata).serialize(),

		
		success:function(res){
			$(mydata)[0].reset();
			$("#myModal_exp_user_create .close").click();
			$("#expidnumber").val(res.idnumber);
			//console.log(res.idnumber); 
		    $('#msg-success').fadeIn();


		}

	});

});


$("#btn_exp_user_cargar_create").click(function(){
	$('#msg-success').hide();	

});





//Comprobar si la cedula ya

function comprIdnumber() {

	var value = $.trim($("#idnumber").val());

	var route = "/expuser/"+value+"" ;


	//asignar cedula automatica	
	$("#email").val(value+"@mail.com");
	//








	
	$.get(route, function(res){

		$(res).each(function(key, res){

		$("#btn_exp_user_create").hide();
		$("#btn_exp_user_create_edit").fadeIn();	

		$("#id").val(res.id);
		$("#idnumber").val(res.idnumber);
		$("#name").val(res.name);
		$("#lastname").val(res.lastname);
		$("#description").val(res.description);
		$("#address").val(res.address);
		$("#email").val(res.email);
		$("#tel1").val(res.tel1);
		$("#tel2").val(res.tel2);
		$("#estrato > option[value="+ res.estrato +"]").attr('selected',true);
		$("#estadocivil > option[value="+ res.estadocivil +"]").attr('selected',true);
		$("#genero > option[value="+ res.genero +"]").attr('selected',true);


		


		if(res.pbesena=='1'){
			$("#pbesena").attr('checked',true);
		}



		if(res.pbepersondiscap=='1'){
			$("#pbepersondiscap").attr('checked',true);
		}


		if(res.pbevictimconflic=='1'){
			$("#pbevictimconflic").attr('checked',true);
		}



		if(res.pbeadultomayor=='1'){
			$("#pbeadultomayor").attr('checked',true);
		}


		if(res.pbeminoetnica=='1'){
			$("#pbeminoetnica").attr('checked',true);
		}


		if(res.pbemadrecomuni=='1'){
			$("#pbemadrecomuni").attr('checked',true);
		}

		if(res.pbecabezaflia=='1'){
			$("#pbecabezaflia").attr('checked',true);
		}


		if(res.pbeninguna=='1'){
			$("#pbeninguna").attr('checked',true);
		}



		if(res.tipodoc!='.'){
			$("#tipodoc > option[value="+ res.tipodoc +"]").attr('selected',true);
		}



		

				
	});

		


		

	});

}




$("#pbeninguna").click(function(){
	
	if($("#pbeninguna").prop('checked')){

		$("#pbesena").attr('checked',false);
		$("#pbepersondiscap").attr('checked',false);
		$("#pbevictimconflic").attr('checked',false);
		$("#pbeadultomayor").attr('checked',false);
		$("#pbeminoetnica").attr('checked',false);
		$("#pbemadrecomuni").attr('checked',false);
		$("#pbecabezaflia").attr('checked',false);


		$("#pbesena").attr('disabled',true);
		$("#pbepersondiscap").attr('disabled',true);
		$("#pbevictimconflic").attr('disabled',true);
		$("#pbeadultomayor").attr('disabled',true);
		$("#pbeminoetnica").attr('disabled',true);
		$("#pbemadrecomuni").attr('disabled',true);
		$("#pbecabezaflia").attr('disabled',true);
	}else{

		$("#pbesena").attr('checked',true);
		$("#pbepersondiscap").attr('checked',true);
		$("#pbevictimconflic").attr('checked',true);
		$("#pbeadultomayor").attr('checked',true);
		$("#pbeminoetnica").attr('checked',true);
		$("#pbemadrecomuni").attr('checked',true);
		$("#pbecabezaflia").attr('checked',true);


		$("#pbesena").attr('disabled',false);
		$("#pbepersondiscap").attr('disabled',false);
		$("#pbevictimconflic").attr('disabled',false);
		$("#pbeadultomayor").attr('disabled',false);
		$("#pbeminoetnica").attr('disabled',false);
		$("#pbemadrecomuni").attr('disabled',false);
		$("#pbecabezaflia").attr('disabled',false);

	}





});



//cuando encuentra la cedula 

$("#btn_exp_user_create_edit").click(function(){

	var value= $("#id").val();
	var mydata = "#myform_exp_user_create";
	var route = "/expuser/"+value+"" ;
	var token = $("#token").val();



	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'PUT',
		datatype: 'json',
		data: $("#myform_exp_user_create").serialize(),

		
		success:function(res){

			$("#expidnumber").val(res.idnumber);
			$(mydata)[0].reset();
			$("#myModal_exp_user_create .close").click();
		    $('#msg-success').fadeIn();
			$("#btn_exp_user_create_edit").hide();
		    $("#btn_exp_user_create").fadeIn();


		}

	});

});
