//$(document).ready(function(){
//	    $(document).ajaxStart(function(){
 //       $("#wait").css("display", "block");
 //   });

/*$("#btn_enviar").click(function(){

oculta mensaje y limpia campos
$("#btn_modal").click(function(){
		$('#msg-success').hide();

});

});*/

/* <Listar registros> */

$(document).ready(ListarTabla(0));  //Muestra tabla al cargar pagina
//$("#btn_enviar").on("click", ListarTabla); //Muestra tabla al dar clic en agregar nuevo registro 

function storeActuacion(form){
	var errors = validateForm(form);
	if (errors <= 0) {
		$("#myModal_act_create").modal("hide");
		$("#myModal_act_add_revision").modal("hide");
		$("#wait").css("display", "block");
		var mydata = "#"+form; 
		var route = "/actuaciones" ;
		var formData= new FormData(document.getElementById(form));
		$.ajax({ 
		url: route,  
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: formData,
		cache: false,
        contentType: false,
        processData: false,  
         beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      $("#wait").css("display", "block");   
    },
		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			//console.log(res);
			$('#msg-success').fadeIn();
			$("#"+form)[0].reset();
			ListarTabla(0); //en caso de exito llama a la funcion llenarTabla para poner nuevo registro
			// alert('Actuación guardada con éxito1!!');
			 $("#wait").css("display", "none");
		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
		});
	}
}

/* Listar tabla llama los datos consultados desde el controller*/
function ListarTabla(datobandera){

	//var tabla= $("#datos");
	var mydata = $("#id_control_list").val();
	if(mydata){
	var route = "/actuaciones" ;
	//var token = $("#token").val();
/*	if (datobandera==1){
		tabla.empty();
	}*/
	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'GET',
		datatype: 'json',
		data: {id_control_list:mydata, bandera:datobandera},
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");   
    },
     //se envia id para listar datos que cumplan con ese identificador
		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){			
			////console.log(res); //muestra lo que retorna el controlador
			llenarTabla(res,'datos');
		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }

	});	
	}
}



function llenarTabla(res,tabla){
var tabla= $("#"+tabla);
tabla.html('');
var actuacion_estado;
var dateServer = getDateServer();
var color;
var rutadescarga;
var textodescarga;
var row; 
var estadoBtn='';
var btnsParent ='';
var btnsChildren = '';
console.log(res)
	$(res).each(function(key, value){
		var aprobado = false;
		var revisado = false;
		var firmado = false;
		var con_respuesta = false;
		var child_length = value.children.length;
		end_status = '';
		ultimo = '';
		var swac=false;
		$(value.children).each(function(llave, val){
				if (val.actestado_id =='104') {
					aprobado = true;
					child_estado = val.actestado_id;					
				}
				if (val.actestado_id =='101') {
					revisado = true;
				}

				if (val.actestado_id =='102' && swac===false) {
					ultimo = val;
					swac=true;
				}

		if (llave == 0) end_status = val;

		});
 
		if (value.parent.actdocnomgen!=''){
			rutadescarga= "/actpdfdownload/"+value.parent.id+"/estudiante";
			textodescarga="Descargar Archivo ";
			btnDescargaArchivoEs ="<a href='"+rutadescarga+"' target='_blank' >"+textodescarga+"</a>";
		}else{
			textodescarga="Sin Archivo";
			btnDescargaArchivoEs = "<label>"+textodescarga+"</label>";
		}
 
		//Botones
		var btn_detalles = "<button "+estadoBtn+" type='button' value="+value.parent.id+"  OnClick='Mostrar(this,0,\"myModal_act_details\")' class='btn btn-success btn-sm'> Detalles </button>";
		var btn_editar = " <button "+estadoBtn+" type='button' value="+value.parent.id+"  OnClick='Mostrar(this,0,\"myModal_act_edit\");' class='btn btn-primary btn-sm'  > Editar </button> ";
		var btn_eliminar = " <button "+estadoBtn+" type='button' value="+value.parent.id+"  OnClick='eliminarAct(this);' class='btn btn-danger btn-sm'  >Eliminar</button> " ;
		var btn_revisar = " <button "+estadoBtn+" type='button' value="+value.parent.id+"  OnClick='Mostrar(this,0,\"myModal_act_edit_docen\");' class='btn btn-primary btn-sm'> Revisar </button> ";
    	var btn_ag_correccion = " <button "+estadoBtn+" type='button' value="+value.parent.id+"  OnClick='Mostrar(this,"+value.parent.actestado_id+",\"myModal_act_add_revision\");' class='btn btn-primary btn-sm' > Ag. Corrección </button> ";
		var btn_edit_revision = " <button "+estadoBtn+" type='button' value="+value.parent.id+"  OnClick='Mostrar(this,0,\"myModal_act_edit_docen\");' class='btn btn-warning btn-sm'> Editar Revisón </button> ";
    	var btn_add_anexo =" <button "+estadoBtn+" type='button' value="+value.parent.id+" OnClick='Mostrar(this,136,\"myModal_act_add_revision\");' class='btn btn-default btn-sm' style='color:#777' title='Agregar anexo a actuación' >Ag. Anexo</button>";      	      	   
		var btn_add_respuesta =" <button "+estadoBtn+" type='button' value="+value.parent.id+" OnClick='Mostrar(this,"+value.parent.actestado_id+",\"myModal_act_add_revision\");' class='btn btn-default btn-sm' style='color:#777'>Ag. Respuesta</button>"
		var bnt_rev_anexo_false =" <button "+estadoBtn+" type='button' value="+value.parent.id+"  class='btn btn-default btn-sm btn_change_status' style='color:#777'>Revisar</button>"
		var bnt_rev_anexo_true =" <button "+estadoBtn+" type='button' value="+value.parent.id+" class='btn btn-default btn-sm btn_change_status' style='color:#777'>Quitar revisado</button>"
		var bnt_return_actestado =" <button "+estadoBtn+" type='button' id="+value.parent.actestado_id+"	 value="+value.parent.id+" class='btn btn-warning btn-sm btn_change_status' style='color:#fff'>Volver a correcciones</button>"
									
			if(value.parent.actestado_id=='101'){
				color='green';
			if (value.user.name == 'estudiante') {
				btnsParent = btn_detalles + btn_add_anexo; 
				if (value.user.idnumber==value.parent.actusercreated) {
					btnsParent +=""+btn_editar+" "+btn_eliminar;
				}
            }
			if (value.user.name == 'docente') {
				btnsParent = btn_detalles;
				if (value.docenteasig) {
					btnsParent +=  btn_revisar;;
				}  
		
			}
			if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
				btnsParent = btn_detalles + btn_revisar + btn_editar + btn_eliminar;
			
      }


			}else if(value.parent.actestado_id=='102'){				
				color='yellow';
				estadoBtn = '';
				if (value.user.name == 'estudiante') {
                btnsParent = btn_detalles; 
				btnsParent += btn_ag_correccion;
				btnsParent += btn_add_anexo;
		       	}
////console.log(value.user.name)
			if (value.user.name == 'docente') {		
				btnsParent = btn_detalles;	
				if (value.docenteasig) {
					btnsParent += btn_edit_revision;;
				} 	
						
			}
			if ( value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {		
				btnsParent = btn_detalles + btn_edit_revision + btn_eliminar;		
					
			}
			/* if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
             	btnsParent = btn_detalles;
				btnsParent += btn_edit_revision;
				btnsParent += btn_eliminar;
            } */
			}else if(value.parent.actestado_id=='104'){
				actuacion_estado='Aprobado';
				color='blue';
			if (value.user.name == 'estudiante') {				
				if(!aprobado) btnsParent = btn_detalles;
        if (!firmado && !con_respuesta) btnsParent += btn_add_anexo;
				if(!con_respuesta && firmado) btnsParent += btn_add_respuesta;
			}
			if (value.user.name == 'docente') {
			    btnsParent = btn_detalles;
      }
      if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
             	btnsParent = btn_detalles;
				//btnsParent += btn_revisar;
				btnsParent += btn_eliminar;
				btnsParent += btn_editar;
      }

			}else if(value.parent.actestado_id=='136'){
				
				color='gray';
			if (value.user.name == 'estudiante') {				
				btnsParent = btn_detalles+""+btn_editar+" "+btn_eliminar;
			}
			if (value.user.name == 'docente') {
				btnsParent = btn_detalles;
				value.docenteasig ? btnsParent += bnt_rev_anexo_false :'';
             }
             if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
				 btnsParent = btn_detalles;
				 btnsParent += bnt_rev_anexo_false;
				//btnsParent += btn_revisar;
				btnsParent += btn_eliminar;
            }

			}else if(value.parent.actestado_id=='138'){
				
				color='gray';
			if (value.user.name == 'estudiante') {				
				btnsParent = btn_detalles;
			}
			if (value.user.name == 'docente') {
				btnsParent = btn_detalles;
				value.docenteasig ? btnsParent += bnt_rev_anexo_true : '';
				
             }
             if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
				 btnsParent = btn_detalles;
				 btnsParent += bnt_rev_anexo_true;
				//btnsParent += btn_revisar;
				btnsParent += btn_eliminar;
            }

			}else if(value.parent.actestado_id=='139'){
			//anexo revisado	
				color='default';
			if (value.user.name == 'estudiante') {				
				btnsParent = btn_detalles;
			}
			if (value.user.name == 'docente') {
				btnsParent = btn_detalles;
			
             }
             if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
				 btnsParent = btn_detalles;
				btnsParent += btn_eliminar + bnt_return_actestado;
            }

			}else if(value.parent.actestado_id=='140'){
				//enviado por docente	
					color='purple';
				if (value.user.name == 'estudiante') {		
					const btn_ag_correccion = " <button " + estadoBtn + " type='button' value=" + value.parent.id + "  OnClick='Mostrar(this," + value.parent.actestado_id + ",\"myModal_act_add_revision\");' class='btn btn-primary btn-sm' > Agr. Actuación </button> ";
					btnsParent = btn_detalles + btn_ag_correccion;
				
				}
				if (value.user.name == 'docente') {
					
					if (value.children.length<=0) {					
						btnsParent = btn_detalles;
						if (value.user.idnumber==value.parent.actusercreated) {
							btnsParent += btn_editar + btn_eliminar;
						}           	
					}else{
						btnsParent = btn_detalles;            
				}
					
				 }
				 if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
					btnsParent = btn_detalles;
					//btnsParent += btn_editar;
					btnsParent += btn_eliminar;
					//  btnsParent += bnt_rev_anexo_true;
					//btnsParent += btn_revisar;
					//btnsParent += btn_eliminar;
				}
	
				}else if(actestado_id=='0'){
				actuacion_estado='.';
				color='blue';
				estadoBtn = '';

			}else{
				actuacion_estado='No especificado';
				color='ora';
				estadoBtn = '';

			}
				

                if (value.children.length >0) {
var con=0;
                	$(value.children).each(function(key_c, child){
console.log('id: ',child.id);
            var btn_detalles_child = "<button "+estadoBtn+" type='button' value="+child.id+"  OnClick='Mostrar(this,0,\"myModal_act_details\");' class='btn btn-success btn-sm'> Detalles </button>";
						var btn_editar_child = " <button "+estadoBtn+" type='button' value="+child.id+"  OnClick='Mostrar(this,0,\"myModal_act_edit\");' class='btn btn-primary btn-sm'  > Editar </button> ";
						var btn_eliminar_child  = " <button "+estadoBtn+" type='button' value="+child.id+"  OnClick='eliminarAct(this);' class='btn btn-danger btn-sm'  >Eliminar</button> " ;
						var btn_revisar_child = " <button "+estadoBtn+" type='button' value="+child.id+"  OnClick='Mostrar(this,0,\"myModal_act_edit_docen\");' class='btn btn-primary btn-sm'  > Revisar </button> ";
				   /* var btn_ag_correccion_child = " <button "+estadoBtn+" type='button' value="+child.id+"  OnClick='Mostrar(this,0,\"myModal_act_add_revision\");' class='btn btn-primary btn-sm'> Ag. Corrección </button> ";*/
						var btn_edit_revision_child = " <button "+estadoBtn+" type='button' value="+child.id+"  OnClick='Mostrar(this,0,\"myModal_act_edit_docen\");' class='btn btn-warning btn-sm'> Editar Revisón </button> ";
				   /* var btn_add_firmado_child =" <button "+estadoBtn+" type='button' value="+child.id+" OnClick='Mostrar(this,"+child.actestado_id+",\"myModal_act_add_revision\");' class='btn btn-default btn-sm  ' style='color:#777'>Ag. Anexo</button>";      	      	   
						var btn_add_respuesta_child =" <button "+estadoBtn+" type='button' value="+child.id+" OnClick='Mostrar(this,"+child.actestado_id+",\"myModal_act_add_revision\");' class='btn btn-default btn-sm' style='color:#777'>Ag. Respuesta</button>"*/
						var bnt_rev_firmado_false =" <button "+estadoBtn+" type='button' value="+child.id+"  class='btn btn-default btn-sm btn_change_status' style='color:#777'>Revisar</button>"
						var bnt_rev_firmado_true =" <button "+estadoBtn+" type='button' value="+child.id+" class='btn btn-default btn-sm btn_change_status' style='color:#777'>Quitar revisado</button>"
						var bnt_return_actestado_child =" <button "+estadoBtn+" type='button' id="+child.actestado_id+" value="+child.id+" class='btn btn-warning btn-sm btn_change_status' style='color:#ffffff'>Volver a correcciones</button>"
						

                		if (child.actdocnomgen!=''){
							rutadescarga= "/actpdfdownload/"+child.id+"/estudiante";
							textodescarga="Descargar Archivo ";
							btnDescargaArchivoChild ="<a href='"+rutadescarga+"' target='_blank' >"+textodescarga+"</a> ";
						}else{

							rutadescarga= "#"+child.id;
							textodescarga="Sin Archivo";
							btnDescargaArchivoChild ="<label>Sin archivo</label> ";

						}


						if(child.actestado_id=='101'){
							actuacion_estado_child='Enviado a revisión';
							color_child='green';
							estadoBtn = '';

						if (value.user.name=='estudiante') {
							
							if(ultimo != ""){
								var  before_child_fecha_limit = ultimo.fecha_limit;
								
								if(before_child_fecha_limit > dateServer){
								//	console.log('ggggggg2', ultimo.actnombre,before_child_fecha_limit , dateServer)
									btnsChildren = btn_editar_child;
									btnsChildren +=	btn_eliminar_child;	
								}else{
									btnsChildren = "";
									btnsChildren +=	"";	
								}
							}else{
								var  before_fecha_limit = value.parent.fecha_limit;
								//console.log('hhhhhh',value.parent.actnombre,before_fecha_limit , dateServer)
								if(before_fecha_limit > dateServer){
									btnsChildren = btn_editar_child;
									btnsChildren +=	btn_eliminar_child;	
								}else{
									btnsChildren = "";
									btnsChildren +=	"";	
								}
							}			
			            	btnsParent = btn_detalles+""+btn_add_anexo;
			            }
			            if (value.user.name=='docente') {
										btnsParent = btn_detalles;
								 btnsChildren =btn_detalles_child ;
							if (value.docenteasig) btnsChildren +=  btn_revisar_child;
						}
									
						if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
							btnsParent = btn_detalles + btn_eliminar;
						    btnsChildren =btn_detalles_child +  btn_revisar_child;
						}
						
						}else if(child.actestado_id=='102'){
							color_child='yellow';
							estadoBtn = '';

						if (value.user.name == 'estudiante') {
								btnsChildren = btn_detalles_child;
				        }
							if (value.user.name == 'docente') {
								btnsParent = btn_detalles;
								btnsChildren = btn_detalles_child;
								
								if (!aprobado && !revisado && end_status.id == child.id)  {
										btnsChildren = btn_detalles_child;
									if (value.docenteasig) btnsChildren += btn_edit_revision_child;
							   }
							}

							if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
								btnsParent = btn_detalles + btn_eliminar;
								if (!revisado && con == 0) {
										btnsChildren = btn_detalles_child +  btn_edit_revision_child;
										
										if (value.user.idnumber==child.actuserupdated) {
											//btnsChildren += btn_eliminar_child;   
										}  


				               	}else if(!firmado && con == 0){
										btnsChildren = btn_edit_revision_child;
										
				               	}else{
									btnsChildren = btn_detalles_child + btn_edit_revision_child;
							   }
							}


						}else if(child.actestado_id=='104'){
							color_child='blue';
							estadoBtn = '';
							if (value.user.name == 'estudiante') {								 
				                btnsChildren = btn_detalles_child;
				            	if (!firmado) {
				            		btnsParent = btn_detalles;
				              		btnsParent += btn_add_anexo;
			                 	}
				            	
				            }

							if (value.user.name == 'docente') {
								btnsChildren =  btn_detalles_child;
				                btnsParent = btn_detalles;
				            }
				            if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
								btnsChildren =  btn_detalles_child;
								btnsChildren +=  btn_eliminar_child;
				                btnsParent = btn_detalles;
				                btnsParent += btn_eliminar;
				            }


						}else if(child.actestado_id=='136'){
							//firmado
							color_child='gray';
							estadoBtn = '';
							if (value.user.name == 'estudiante') {								 
				            	btnsChildren = btn_detalles_child
				            	btnsChildren += btn_editar_child;
				            	btnsChildren += btn_eliminar_child;			            	
				            }

							if (value.user.name == 'docente' || value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
								btnsChildren = btn_detalles_child;
								if(!con_respuesta){
									value.docenteasig ? btnsChildren += bnt_rev_firmado_false :''; 					
									
								  }
								  if (revisado) {
									btnsParent = btn_detalles + btn_eliminar;
								  }								
				                
				            }

						}else if(child.actestado_id=='138'){
							color_child='gray';
							estadoBtn = '';
							if (value.user.name == 'estudiante') {								 
				            	btnsChildren = btn_detalles_child
				            	//btnsChildren += btn_editar_child;
				            	
				            	if(!con_respuesta){
				            	//	btnsParent = btn_detalles; 
				              		//btnsParent += btn_add_respuesta_child
				              	}
				            	
				            }

							if (value.user.name == 'docente' || value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
								btnsChildren = btn_detalles_child;
								if(!con_respuesta){
									value.docenteasig ? btnsChildren += bnt_rev_firmado_true :''; 
				            		 
				              	}
				                if (revisado) {
													btnsParent = btn_detalles;
													}	
				            }

						}else if(child.actestado_id=='139'){
							color_child='default';
							estadoBtn = '';
							if (value.user.name == 'estudiante') {								 
								btnsChildren = btn_detalles_child;
							if (!firmado) {
								btnsParent = btn_detalles;
									btnsParent += btn_add_anexo;
								 }
							
						}

			if (value.user.name == 'docente') {
				btnsChildren =  btn_detalles_child;
								btnsParent = btn_detalles;
						}
						if (value.user.name == 'amatai' || value.user.name =='diradmin' || value.user.name == 'dirgral') {
							btnsChildren =  btn_detalles_child;
							btnsChildren +=  btn_eliminar_child + bnt_return_actestado_child;
								btnsParent = btn_detalles;
								btnsParent += btn_eliminar;
						}

						}else{
							actuacion_estado_child='No especificado';
							color_child='ora';
							estadoBtn = '';

						}
con++;			
						
						var dias = getDiffdays(child.fecha_limit, child.actfecha);
						
						if(dias<=0) dias = child.fecha_limit;
						var color_bg = getDiffdaysColor(child.fecha_limit, child.actfecha,child.id);

						if(end_status.id != child.id && child.actestado_id!='136'){
							dias = child.fecha_limit;
							color_bg = 'bg-gray';
						} 
						if(dias===null){
							dias = child.actfecha;
						}
						
                		tabla.prepend("<tr><td  colspan='6'> <tr><td><i class='fa fa-reply' style='transform:rotate(180deg)'> &nbsp; </i>  "+
                		child.actnombre + "</td><td>"+child.actdescrip+"</td><td><span class='pull-center badge bg-"+color_child+"'>"+
							child.ref_nombre + "</span></td><td><span class='badge "+color_bg+"'>" + dias + "</span></td><td> "+btnDescargaArchivoChild+"</td><td>"+
                		btnsChildren+"</td></tr></td></tr>");
		           	});

                }
		var dias = getDiffdays(value.parent.fecha_limit, value.parent.actfecha);
		var color_bg = getDiffdaysColor(value.parent.fecha_limit, value.parent.actfecha,value.parent.id)   
		if(dias<0 || value.children.length > 0){
			dias = value.parent.fecha_limit;	
			color_bg = 'bg-gray';
		} 
		
				tabla.prepend("<tr role='row' class='odd row-parent'><td>"+ value.parent.actnombre +
				"</td><td>"+value.parent.actdescrip+"</td><td><span class='pull-center badge bg-"+color+"'>"+
				value.ref_nombre + "</span></td><td><span class='badge "+color_bg+"'>" +
					dias + "</span></td><td>"+btnDescargaArchivoEs+"</td><td width='28%'> "+btnsParent+"</td></tr>"); 

				//llenar modal de detalle actu<cion
				document.getElementById("tbl_actuacion_det").rows[4].cells[1].innerHTML = value.parent.actnombre;
				document.getElementById("tbl_actuacion_det").rows[5].cells[1].innerHTML = value.parent.actdescrip;
				document.getElementById("tbl_actuacion_det").rows[6].cells[1].innerHTML = value.parent.actdocenrecomendac; 
	});
	
}

function getDiffdays(fecha_limit,date_2=''){
	if(fecha_limit!=null){
		var given = moment(fecha_limit,"YYYY-MM-DD");
		var current = moment().startOf('day');
		var dias = moment.duration(given.diff(current)).asDays();
		return dias;
	}
	return date_2;	
}

function getDiffdaysColor(fecha_limit,actfecha='',id){
	if(fecha_limit!=null){
		var percent = 100;
		var ini = moment(fecha_limit, "YYYY-MM-DD");
		var fin = moment(actfecha, "YYYY-MM-DD");
		var dias_total = moment.duration(ini.diff(fin)).asDays();
		dias = getDiffdays(fecha_limit,actfecha);
		x_percent = dias * percent / dias_total;
		color = 'bg-gray';		
		if(x_percent>=75){
			color = 'bg-green';
		}else if(x_percent>=30){
			color = 'bg-orange';
		}else if(x_percent<=29 && x_percent>=0){
			color = 'bg-red';
		}else if(x_percent<0){
			color = 'bg-gray';
			
		}
		//console.log(color);
		return color;
	}
	return actfecha;	
}


/*Mostrar y ocultar boton para actualizar el archivo actuacion*/

function showBtn(id){
	//$("#btnActDoc"+id).css({'display':'block','background':'red'});
	$("#btnActDoc"+id).show();
}
function hideBtn(id){
	$("#btnActDoc"+id).hide();
}
////////////////////////////////////////////////////////////////////

/* </Listar registros> */







/* Envia id de actuacion */

function Mostrar(btn,child_estado,modal){
	// alert(modal)
	var route = "/actuaciones/"+btn.value+"/edit" ;
	var label = '';
	$("#btn_enviar_actuacion").text('Agregar Actuación');
	$("#actestado_id2").val(101);
	$.get(route, function(res){
		console.log(res)
		if (child_estado=='104') {
		 	label = 'Agregando Anexo a Actuación';
		 	$("#actestado_id2").val(136);
		 	$("#btn_enviar_actuacion").text('Agregar Anexo');
		 	$("#lbl_tip_act").text('Motivo');
		}else if(child_estado=='136'){
			label = 'Agregando Anexo a Actuación';
		 	$("#actestado_id2").val(136);
		 	$("#btn_enviar_actuacion").text('Agregar Anexo');
		 	$("#lbl_tip_act").text('Motivo');
		}else{
			label = 'Agregando Corrección a Actuación: '+res.id;
		}
		$("#idact").val(res.id);
		$("#idact2").val(res.id);
		$("#lab-txt-doc").text(res.actdocnompropio);
		$("#actnombre").val(res.actnombre);
		$("#actnombre_edit").val(res.actnombre);
		$("#actnombre_cr").val(res.actnombre);
		$("#actfecha").val(res.actfecha);
		$("#fecha_limite").val(res.fecha_limit);
		$("#fecha_limit_doc").val(res.fecha_limit);	
		$("#fullnameest").val(res.estudiante.name+' '+res.estudiante.lastname)
		$("#"+modal+" #actdescrip").text(res.actdescrip);
		$("#actestado_id > option[value="+ res.actestado_id +"]").attr('selected',true);
		$("#actestado > option[value="+ res.actestado_id +"]").attr('selected',true);
	//	console.log(res)
		if(res.actestado_id==102) $("#fecha_limit_doc").prop('disabled',false);

		$("#actdocenrecomendac").text(res.actdocenrecomendac);
		//$("#myform_act_edit_doc").attr('url','/actuaciones/update/doc/'+btn.value);
		$(".lab_id_act").text(label);
		$("#parent_actuacion_id").val(res.parent.parent_rev_actid);
		if (res.actdocnompropio_docente!='') {
			$("#lab_doc_file").text(res.actdocnompropio_docente);
		}else{
			$("#lab_doc_file").text('La actuación no tiene archivo');
		}
		if (res.actdocnompropio!='') {
			$(".lab_doc_file_est").text(res.actdocnompropio);
		}else{
			$(".lab_doc_file_est").text('La actuación no tiene archivo');
		}
		
		$("#act_id").val(res.id);

		
       llenarModalDetails(res);
       $("#"+modal).modal('show');

	});

} 

function llenarModalDetails(res){
	hideElement('cont_notas_ac');
	hideElement("btn_cam_nt_act");
	if (res.actestado_id=='101' || res.actestado_id=='136' || res.actestado_id=='140' || res.actestado_id=='137' || res.actestado_id=='138') {
		$("#actfecha_det").val(res.created_at);
		hideElement('datos_docente');
	}else{
		$("#actfecha_det").val(res.actdocenfechamod);
		showElement('datos_docente');
	}
	//$("#myform_act_edit_docen")[0].reset();
	//console.log(res.actestado_id);
	$("#actnombre_det").val(res.actnombre);
	$("#actdescrip_det").val(res.actdescrip); 
	//$("#actestado_det > option[value="+ res.actestado_id +"]").attr('selected',true);
	$("#actestado_det").val(res.actestado_id);
	$("#fecha_limit_d").val(res.fecha_limit);
	$("#label_nombre_docente").text(res.docente_update.name+' '+res.docente_update.lastname);
	rutadescarga= "/actpdfdownload/"+res.id+"/docente";
	if (res.actdocnompropio_docente!='' && res.actdocnompropio_docente!=null) {
		$("#lab-nombre-doc").html('<a href="'+rutadescarga+'" target="_blank">'+res.actdocnompropio_docente+'</a>');
	}else{
		$("#lab-nombre-doc").html('<i>Sin archivo</i>');
	
	} 
	var segmento_id = $("#segmento_id").val();
	
	if(res.notas_f.encontrado){ 
		$("#lbl_not_conac").text(res.notas_f.nota_conocimiento);
		$("#lbl_not_aplac").text(res.notas_f.nota_aplicacion);
		$("#lbl_not_etiac").text(res.notas_f.nota_etica);
		$("#ntaconcepto_text").val(res.notas_f.nota_concepto); 
		$("#cont_notas_ac #lbldocevname").text(res.notas_f.docevname); 

		showElement('cont_notas_ac');
		console.log('ids',segmento_id,res.notas_f.segmento_id ,res.notas_f.can_edit)
		if(segmento_id == res.notas_f.segmento_id && res.notas_f.can_edit) {
			showElement('btn_cam_nt_act');
 
		}
 
	}else{
		if (res.notas!=null && res.notas!='' ) {
			var notas = JSON.parse(res.notas);
		$("#lbl_not_conac").text(notas.ntaconocimiento);
		$("#lbl_not_aplac").text(notas.ntaaplicacion);
		$("#lbl_not_etiac").text(notas.ntaetica);
		$("#ntaconcepto_text").val(notas.ntaconcepto); 
		showElement('cont_notas_ac');
		}
	}

	$("#actuacion_id").val(res.id);
	
	$("#actdocenrecomendac_det").val(res.actdocenrecomendac);

	//$("#myModal_act_details").modal('show');


}

function updateActuacion(){

	var errors = validateForm('myform_act_edit');
	if (errors<=0) {
	var value = $("#idact").val();
	var mydata = "#myform_act_edit";
	var route = "/actuaciones/update/"+value+"" ;
	//var token = $("#token").val();
	var formDatae = new FormData(document.getElementById("myform_act_edit"));
	
	$("#myModal_act_edit").modal('toggle');
    $.ajax({ 
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: formDatae,
		cache: false,
        contentType: false,
        processData: false,  
		 beforeSend: function(xhr){
      	xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      	$("#wait").css("display", "block");   
    	},
        /*muestra div con mensaje de 'regristrado'*/
		success:function(res){
		
			$('#msg-success').fadeIn();	
			//$("#datos").empty();
			$(mydata)[0].reset();
			ListarTabla(0);
			//llenarTabla(res)
			
			$("#wait").css("display", "none");
		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
	});
	}
}





/////////////////////////calificar actuaciones desde rol docente//////////

$("#btn_act_edit_docen").click(function(){
	if($("#actestado").val()!=104){
		$("#formAddNotas .form-control").removeClass('required');
	}
	var errors = validateForm('myform_act_edit_docente'); 
console.log(errors)
	if (errors<=0) {
	var value = $("#idact").val();
	var mydata = "#myform_act_edit_docente";
	var route = "/actuaciones/store/revision" ;
	//var token = $("#token").val();
		var formDatas = new FormData(document.getElementById("myform_act_edit_docente"));
	
    $.ajax({ 
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data:formDatas,
		cache: false,
		contentType: false,
        processData: false,  
		 beforeSend: function(xhr){
      	xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      	$("#wait").css("display", "block");   
    	},
        /*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			$('#msg-success').fadeIn();	
			$("#myModal_act_edit_docen").modal('hide'); 
			hideElement('formAddNotas');
			$(mydata)[0].reset();
			ListarTabla(0);
			
			$("#wait").css("display", "none");
		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
	});
	}
	
});


//Actualizar doc
$("#btn_act_edit_doc").click(function(e){
	e.preventDefault();
	e.stopPropagation();

	var value = $("#idact2").val();
	var mydata = "#myform_act_edit_doc";
	var route = "/actuaciones/update/doc/"+value+"" ;
	var token = $("#token").val();
	var formData = new FormData(document.getElementById("myform_act_edit_doc"));
	$.ajax({ 
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		data:formData,
		cache: false,
        contentType: false,
        processData: false,         
		beforeSend: function(xhr){   

		var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", function(){
        	//aqui deberia ir el codigo.--- :(
        }, false);
       xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
		$("#wait").css("display", "block");		
		},
		
	       
		//muestra div con mensaje de 'regristrado'
		success:function(res){

			//console.log(res);
			$("#datos").empty();
			$(mydata)[0].reset();
			ListarTabla(0);
			$("#myModal_act_edit_doc").modal('toggle');
			$("#wait").css("display", "none");
		},
    error:function(xhr, textStatus, thrownError){
       alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
	});

	
});

/**/
/*Eliminar una actuacion*/
function eliminarAct(obj){
	var msj = confirm('¿Está seguro de eliminar el registro?\nRecuerde que no podrá recuperar información asociada a esta actuación');
	if (msj) {
	var route = "/actuaciones/"+obj.value;
	//var token = $("#token").val();
	$.ajax({ 
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'DELETE',
		datatype: 'json',
		data:obj.value,
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");   
    },
		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			//console.log(res);
			$('#msg-success').fadeIn();	
			$("#datos").html('');
			ListarTabla(0);
			
		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
	});	
	}
}





/* */
/*oculta mensaje y limpia campos*/
$("#btn_enviar_req").click(function(){
	var fechahorareq=$("#fechareq").val()+" "+$("#horareq").val();
	$("#reqfechahoracomp12").val(fechahorareq);
	//alert('Guardado1!!');
	// $("#mymodal").modal("hide");
});













/*******************************************ajax para pestaña requerimientos*****************************************************/

$("#btn_enviar_req").click(function(){


	var mydata = "#myform_req";
	var route = "/requerimientos" ;
	//var token = $("#token").val();
	 var errors = validateForm('myform_req');
	 if (errors.length <= 0) {
	 $.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'POST',
		datatype: 'json',
		data: $(mydata).serialize(),
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");   
    },
		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			$('#msg-success').fadeIn();
			$(mydata)[0].reset();
			$("#mymodal").modal("hide");
			ListarTabla_req(0);
			//llenarTabla_req(res); //en caso de exito llama a la funcion llenarTabla para poner nuevo registro
			},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
	});
	 }
	


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
	if(mydata){
	var route = "/requerimientos" ;
	//var token = $("#token").val();
/*	if (datobandera==1){
		tabla.empty();
	}*/
	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'GET',
		datatype: 'json',
		data: {id_control_list_req:mydata, bandera:datobandera},
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");   
    }, //se envia id para listar datos que cumplan con ese identificador
		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			
			////console.log(res); //muestra lo que retorna el controlador
			llenarTabla_req(res);
		},
    error:function(xhr, textStatus, thrownError){
      alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
	});	
	}
	
}

function getFechaActual(){
	var f = new Date();
	if ((f.getMonth() +1)<10) {
		month = "0"+(f.getMonth() +1);
		
	}else{
		month = (f.getMonth() +1);
	}

	if ((f.getDate())<10) {
		day = "0"+(f.getDate());
		
	}else{
		day = (f.getDate());
	}
	var fechaActual = (f.getFullYear() + "-" + (month) + "-" + day);

	return fechaActual;
}

function llenarTabla_req(res){

var tabla= $("#datos_req");
tabla.html('');
var ruta_genpdf;
var btnEdit ="";
var btnDel = "";
var btnAsistencia = "";
var btnDetalles = "";
var btnChangeEstado ="";
fechaActual = getFechaActual();
console.log(res)
	$(res.requerimientos).each(function(key, value){
		if (fechaActual >= value.reqfecha && value.reqentregado && !value.evaluado) {
			estadoBtn = '';
			label = 'Evaluar'
		}else{
			estadoBtn = 'disabled';
			label = 'Evaluado';
			if (!value.reqentregado && !value.evaluado) {
				label = 'Sin entregar';
			}		
		}
			ruta_genpdf= '/reqpdfgen/'+value.id;		
		/* 	var btns = '';		
			var btnPrint = "<a href='"+ruta_genpdf+"' target='_blank' class='btn btn-warning btn-block' role='button'>Imprimir</a>";
			var btnEdit = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_edit'  class='btn btn-primary btn-block' role='button'>Editar</a>";
			var btnDel = "<a href='#'  class='btn btn-danger btn-block' OnClick='delReq("+value.id+")' role='button'>Eliminar</a>";
			var btnAsistencia = "<button href='#' "+estadoBtn+" data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_asist'  class='btn btn-primary btn-block' role='button'>Comentar</button>";
			var btnDetalles = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_details'  class='btn btn-success btn-block' role='button'>Detalles</a>";
		  */
		 var btnPrint = "<a href='"+ruta_genpdf+"' target='_blank' class='btn btn-warning btn-block' role='button'>Imprimir</a>";
		
			if(value.expestado=='3' || value.expestado=='2'){
				 btnEdit = "";
				 btnDel = "";

				} else {

				if (res.userSession.name =='estudiante') {
				
				if (value.reqentregado == 0) {
					//btns = 	btnEdit+""+	btnDel;
				btnEdit = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_edit'  class='btn btn-primary btn-block' role='button'>Editar</a>";
					btnDel = "<a href='#'  class='btn btn-danger btn-block' OnClick='delReq("+value.id+")' role='button'>Eliminar</a>";
					btnAsistencia='';
					btnDetalles='';

				}

				if(value.reqentregado == 1){
			  // btns = btnAsistencia+""+btnDetalles;
					btnAsistencia = "<button href='#' "+estadoBtn+" data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_asist'  class='btn btn-primary btn-block' role='button'>Comentar</button>";
				 btnDetalles = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_details'  class='btn btn-success btn-block' role='button'>Detalles</a>";
				 
				}	
				
				if(value.evaluado){
					btnEdit = "";
					btnDel = "";
					btnAsistencia = "<button href='#' disabled data-toggle='modal'  class='btn btn-primary btn-block' role='button'>Evaluado</button>";
					 btnDetalles = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_details'  class='btn btn-success btn-block' role='button'>Detalles</a>";
					 
					}	
				 
				}

				if (res.userSession.name =='docente' ) {
				//		btns = btnAsistencia+" "+btnDetalles;
				 	btnPrint=''; 
						btnEdit = "";
						btnDel = "";	
						btnAsistencia = "<button href='#' "+estadoBtn+" data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_asist'  class='btn btn-primary btn-block' role='button'>"+label+"</button>";
						btnDetalles = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_details'  class='btn btn-success btn-block' role='button'>Detalles</a>";
			
				}
 
				if (res.userSession.name =='coordprac' || res.userSession.name =='amatai' || res.userSession.name =='diradmin' || res.userSession.name =='dirgral') {
				//		btns = btnAsistencia+""+btnDetalles;
			 	 btnPrint='';	
					btnAsistencia = "<button href='#' "+estadoBtn+" data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_asist'  class='btn btn-primary btn-block' role='button'>Asistencia</button>";
					btnDetalles = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_details'  class='btn btn-success btn-block' role='button'>Detalles</a>";
					btnEdit = "";
					btnDel = "<a href='#'  class='btn btn-danger btn-block' OnClick='delReq("+value.id+")' role='button'>Eliminar</a>";
					
				}

				if (res.userSession.name =='amatai' || res.userSession.name =='secretaria') {
				//	btns = btnDetalles;
			 btnDetalles = "<a href='#' data-toggle='modal' OnClick='searchReq("+value.id+")' data-target='#myModal_req_details'  class='btn btn-success btn-block' role='button'>Detalles</a>";
				if (!value.reqentregado) {
				 	btnChangeEstado = "<a href='#' OnClick='changeStateReq("+value.id+")'   class='btn btn-primary btn-block' role='button'>Marcar como Entregado</a>";
				  }else{
				  btnChangeEstado = "<a href='#' OnClick='changeStateReq("+value.id+")'   class='btn btn-danger btn-block' role='button'>Marcar como No Entregado</a>";
				}
				}
				
			}

			row = "<tr role='row' class='odd'>";
			row += "<td>"+ value.created_at.substring(0, 11) + "</td>";
			row += "<td>"+ value.reqmotivo + "</td>";
			row += "<td>"+ value.reqfecha + "</td>";
			row +="<td>"+ value.reqhora + "</td>";
			row +="<td>"+ value.req_asistencia.ref_reqasistencia +"</td>";
			if (value.reqentregado) {
			row +="<td> Entregado</td>";
			}else{
				row +="<td> Sin entregar</td>";
			}
			if (value.evaluado) {
				row +="<td> Evaluado</td>";
				}else{
					row +="<td> Sin evaluar</td>";
				}
			
			row +="<td>"+btnPrint+""+btnEdit+""+btnAsistencia+""+btnDel+""+btnChangeEstado+""+btnDetalles+"</td>";
			row +="</tr>";

				//var valDes = '<textarea rows="5" cols="50" readonly>'+ value.reqdescrip + '</textarea>' 
				tabla.prepend(row); 
	});

}


/*
	Buscar requerimiento para acualizarlo
*/
function searchReq(id){
	var route = "/requerimientos/"+id+"/edit" ;
	var token = $("#token").val();
	$("#req_id").val(id);
	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'GET',
		datatype: 'json',
		data: {'id_req':id},
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
     // $("#wait").css("display", "block");   
    }, //se envia id para listar datos que cumplan con ese identificador
		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			//console.log(res); //muestra lo que retorna el controlador
			llenarFormEditReq(res);
			llenarModalDetailsReq(res);
			llenarModalUpdateReq(res);
		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }



	});


}

////////////////////////////////////
 function llenarFormEditReq(res){
//console.log(res.requerimiento.reqfecha)
 	$("#reqid").val(res.requerimiento.id);
 	$("#reqfecha_ed").val(res.requerimiento.reqfecha);
 	//$("#reqfecha_d").val(res.requerimiento.reqfecha);
 	$("#reqhora_ed").val(res.requerimiento.reqhora);
	$("#reqmotivo").val(res.requerimiento.reqmotivo);
 	$("#reqdescrip").val(res.requerimiento.reqdescrip); 	 	
 }
 //////////////////////////////////////////////////////
 function llenarModalUpdateReq(res){
 	$("#req_id").val(res.requerimiento.id);
 	$("#lab_cod_exp").text(res.requerimiento.expediente.expid);
 	$("#lab_fech_crea").text(res.requerimiento.created_at);
 	$("#lab_ced_solic").text(res.requerimiento.expediente.solicitante.idnumber);
 	$("#lab_nom_solic").text(res.requerimiento.expediente.solicitante.name);
 	$("#lab_apell_solic").text(res.requerimiento.expediente.solicitante.lastname);
 	
 	$("#lab_fech_cita").text(res.requerimiento.reqfecha);
	$("#lab_hora_cita").text(res.requerimiento.reqhora);

	$("#reqcomentario_est").val(res.requerimiento.reqcomentario_est);
	$("#reqcomentario_coorprac").val(res.requerimiento.reqcomentario_coorprac);
	$("#reqid_asistencia").val(res.requerimiento.reqid_asistencia);
	
 	
 }

 function llenarModalDetailsReq(res){
	 console.log(res)
 	hideElement('cont_notas_req');
 	$("#req_id_det").val(res.requerimiento.id);
 	$("#lab_cod_exp_det").text(res.requerimiento.expediente.expid);
 	$("#lab_fech_crea_det").text(res.requerimiento.created_at);
 	$("#lab_ced_solic_det").text(res.requerimiento.expediente.solicitante.idnumber);
 	$("#lab_nom_solic_det").text(res.requerimiento.expediente.solicitante.name);
  	$("#lab_apell_solic_det").text(res.requerimiento.expediente.solicitante.lastname); 	
 	$("#lab_fech_cita_det").text(res.requerimiento.reqfecha);
	$("#lab_hora_cita_det").text(res.requerimiento.reqhora);

 	$("#lab_req_motivo_det").text(res.requerimiento.reqmotivo);
 	$("#lab_req_descrip_det").text(res.requerimiento.reqdescrip);
 	$("#lab_req_asistencia_det").text(res.requerimiento.req_asistencia.ref_reqasistencia);
 	$("#lab_req_comcoor_det").text(res.requerimiento.reqcomentario_coorprac);
 	$("#lab_req_comest_det").text(res.requerimiento.reqcomentario_est);
	 hideElement('btn_cam_nt_req');
	 var segmento_id = $("#segmento_id").val();
	// alert(segmento_id)
	 if(res.requerimiento.notas_f.encontrado){
		$("#lbl_not_etireq").text(res.requerimiento.notas_f.nota_etica);
		$("#ntaconcepto_req").text(res.requerimiento.notas_f.nota_concepto); 
		$("#cont_notas_req #lbldocevname").text(res.requerimiento.notas_f.docevname); 
		showElement('cont_notas_req');
		if(segmento_id && res.requerimiento.notas_f.segmento_id && res.requerimiento.notas_f.can_edit){
			showElement('btn_cam_nt_req');
		}
	}else{
		if (res.requerimiento.notas!=null && res.requerimiento.notas!='') {
			var notas = JSON.parse(res.requerimiento.notas); 
			$("#lbl_not_etireq").text(notas.ntaetica);
			$("#ntaconcepto_req").text(notas.ntaconcepto);
			

			showElement('cont_notas_req');
			} 
	}
 	
	 
 	

 }
 //Actualizar requerimiento
function actReq(){

	var errors = validateForm('myform_req_edit');
	if (errors <= 0) {
		var req_id = $("#reqid").val();
		var route = "/requerimientos/"+req_id+"";
		//var token = $("#token").val();	
		$("#myModal_req_edit").modal("hide");
		$.ajax({
			url: route,
			headers: { 'X-CSRF-TOKEN' : token },
			type:'PUT',
			datatype: 'json',
			data: $("#myform_req_edit").serialize(),//se envia id para listar datos que cumplan con ese identificador
			 beforeSend: function(xhr){
		      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
		      $("#wait").css("display", "block");   
		    },
			/*muestra div con mensaje de 'regristrado'*/
			success:function(res){
				//console.log(res); //muestra lo que retorna el controlador
				ListarTabla_req(0);	
				
				$("#wait").css("display", "none");		
			},
    error:function(xhr, textStatus, thrownError){
       alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
		});	
	}
	
}
//Elimina un requerimiento
function delReq(id){
	var msj = confirm("¿Está seguro de eliminar en registro?");
	if (msj) {
	var route = "/requerimientos/"+id+"";
	var token = $("#token").val();
	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'DELETE',
		datatype: 'json',
		data:{'id':id},
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");   
    }, //se envia id para listar datos que cumplan con ese identificador
		/*muestra div con mensaje de 'regristrado'*/
		success:function(res){
			//console.log(res); //muestra lo que retorna el controlador
			ListarTabla_req(0);			
		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
	});
	}
}
/////////////////////////////////////////////////

/* </Listar registros> */












/*................................... <cierre de casos/ expedientes>..................................................-..... */
/*.......................................................................................................................... */
/*.......................................................................................................................... */


/*$("#btn_exp_edit_cierre_caso").click(function(){

	var value= $("#expid").val();


	var mydata = "#myform_exp_edit_cierre_caso";
	var route = "/expcierrecaso/"+value+"" ;
	var token = $("#token").val(); 



	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'PUT',
		datatype: 'json',
		data: $("#myform_exp_edit_cierre_caso").serialize(),



		
		success:function(res){
			llenarTabla_cierre_caso(res);

			$("#expcierrecasonotaest").attr('disabled',true);
			$("#expcierrecasocpto").attr('disabled',true);
			
			$("#btn_exp_edit_cierre_caso").remove();	

		    $('#msg-successs').fadeIn();	
		    $("#msg-successs").css('display', 'block');
		    $("#myModal_exp_edit_cierre_caso").modal('toggle');


		    //rol docente: en caso de aprobado desabilita todo  
		    //1:abierto 2:solicitud cierre 3: aprobado 4: rechazado
		    if(res.expestado=='3'){

		    	
		    	$("#btn_trigger_exp_edit_cierre_caso").remove();
		    	$("#expestado").attr('disabled',true);	
		    	$("#expcierrecasonotadocen").attr('disabled',true);		
				$("#btn_exp_edit_cierre_caso").remove();	

		    }



		},
    error:function(xhr, textStatus, thrownError){
       alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }

	});

}); */






///para desabilitar formularios segun rol



/*$(document).ready(function(){
	var value =  $("#expid").val();
	if(value){
	var route = "/expcierrecaso/"+value+"" ;
	$.get(route, function(res){	
            $("#expcierrecasonotadocen2").val(res.expcierrecasonotadocen);
		    $("#expcierrecasonotaest2").val(res.expcierrecasonotaest);
		    $("#expcierrecasocpto2 > option[value="+ res.expcierrecasocpto +"]").attr('selected',true);
			$("#expestado2 > option[value="+ res.expestado +"]").attr('selected',true);	
			//rol docente: en caso de aprobado desabilita todo  
		    //1:abierto 2:solicitud cierre 3: aprobado/cerrado 4: rechazado
		    if(res.expestado=='3'){
		    	$("#btn_trigger_exp_edit_cierre_caso").remove();
		    	$("#expestado").attr('disabled',true);	
		    	$("#expcierrecasonotadocen").attr('disabled',true);		
				$("#btn_exp_edit_cierre_caso").remove();	
		    }
	});
	}
});*/

function llenarTabla_cierre_caso(res){
var tabla= $("#datos_cierre_caso");
	$(res).each(function(key, value){
/*		document.getElementById("tbl_cierre_caso").rows[1].cells[0].innerHTML = value.expcierrecasocpto;
		document.getElementById("tbl_cierre_caso").rows[1].cells[1].innerHTML = value.expcierrecasonotaest ;
		document.getElementById("tbl_cierre_caso").rows[1].cells[2].innerHTML = value.expcierrecasonotadocen ;
		document.getElementById("tbl_cierre_caso").rows[1].cells[3].innerHTML =  value.expestado ;*/

 			$("#expcierrecasonotadocen2").val(res.expcierrecasonotadocen);
		    $("#expcierrecasonotaest2").val(res.expcierrecasonotaest);
		    $("#expcierrecasocpto2 > option[value="+ res.expcierrecasocpto +"]").attr('selected',true);
			$("#expestado2 > option[value="+ res.expestado +"]").attr('selected',true);	
				  
				   
	});

}







/*................................... <Editar usuario desde expedientes>.................................................... */
/*.......................................................................................................................... */
/*.......................................................................................................................... */

$("#btn_exp_user_carga").click(function(){

	$('#msg-success').hide();

	var value= $("#btn_exp_user_carga").val();

	var route = "/expuser/"+value+"/edit" ;

	
	$.get(route, function(res){
		llenarModalUserEdit(res);
	});

});

function llenarModalUserEdit(res) {
		$("#id").val(res.id);
		$("#idnumber").val(res.idnumber);
		$("#name").val(res.name);
		$("#lastname").val(res.lastname);
		$("#description").val(res.description);
		$("#address").val(res.address);
		$("#email").val(res.email);
		$("#tel1").val(res.tel1);
		$("#tel2").val(res.tel2);
		$("#estrato_id > option[value="+ res.estrato_id +"]").attr('selected',true);
		$("#estadocivil_id > option[value="+ res.estadocivil_id +"]").attr('selected',true);
		$("#genero_id > option[value="+ res.genero_id +"]").attr('selected',true);

		if(res.pbesena=='1'){
			$("#pbesena").attr('checked',true);
		}else{
			$("#pbesena").attr('checked',false);	
		} 
		if(res.pbepersondiscap=='1'){
			$("#pbepersondiscap").attr('checked',true);
		}else{
			$("#pbepersondiscap").attr('checked',false);
		}

		if(res.pbevictimconflic=='1'){
			$("#pbevictimconflic").attr('checked',true);
		}else{
			$("#pbevictimconflic").attr('checked',false);
		}
		
		if(res.pbeadultomayor=='1'){
			$("#pbeadultomayor").attr('checked',true);
		}else{
			$("#pbeadultomayor").attr('checked',false);
		}
		
		if(res.pbeminoetnica=='1'){
			$("#pbeminoetnica").attr('checked',true);
		}else{
			$("#pbeminoetnica").attr('checked',false);
		}
		
		if(res.pbemadrecomuni=='1'){
			$("#pbemadrecomuni").attr('checked',true);
		}else{
			$("#pbemadrecomuni").attr('checked',false);
		}
		
		if(res.pbecabezaflia=='1'){
			$("#pbecabezaflia").attr('checked',true);
		}else{
			$("#pbecabezaflia").attr('checked',false);
		}
		
 
		if(res.pbeninguna=='1'){
			$("#pbeninguna").attr('checked',true);
			$(".check-user").attr('disabled',true);
		}else{
			$("#pbeninguna").attr('checked',false);
			$(".check-user").attr('disabled',false);
		}
		
		if(res.tipodoc!='.'){
			$("#tipodoc > option[value="+ res.tipodoc +"]").attr('selected',true);
		}
}


$("#btn_exp_user_edit").click(function(){
	var errors = validateForm("myform_exp_user_edit");
	if (errors.length<=0) {
		var value= $("#id").val();
	var mydata = "#myform_exp_user_edit";
	var route = "/expuser/"+value+"" ;
	//var token = $("#token").val();
	$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'PUT',
		datatype: 'json',
		data: $("#myform_exp_user_edit").serialize(),
		 beforeSend: function(xhr){

      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      $("#wait").css("display", "block");   
    },	
		success:function(res){
			$("#wait").css("display", "none");
			$("#myModal_exp_user_edit").modal('hide');
		    $('#msg-success').fadeIn();	 

		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }

	});
	}
	

});













/*................................... <crear usuario desde expedientes>.................................................... */
/*.......................................................................................................................... */
/*.......................................................................................................................... */







$("#btn_exp_user_create").click(function(){
	var mydata = "#myform_exp_user_create";
	var route = "/expuser" ;
	//var token = $("#token").val();
	var errors = validateForm('myform_exp_user_create');
		if (errors.length <= 0) {
			$.ajax({
			url: route,
			headers: { 'X-CSRF-TOKEN' : token },
			type:'POST',
			datatype: 'json',
			data: $(mydata).serialize(),	
			 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");   
    },	
			success:function(res){
				$(mydata)[0].reset();
				$("#myModal_exp_user_create .close").click();
				$("#expidnumber").val(res.idnumber);
				////console.log(res.idnumber); 
			    $('#msg-success').fadeIn();
			},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }
		});
	}
});
//Validar form expedientes store
$("#myFormExpsStore").submit(function(){
	var errors = validateForm('myFormExpsStore');
	if (errors.length > 0) {
		return false;
	}else{
		return true;
	}
	

});
//Funcion para validar un formulario
function validateForm(form){	
	var errors = [];
	$("#"+form+" .required").each(function(index,obj){
		if ($(this).attr('disabled')!='disabled') {
			if ($(this).val() =='') {
	  			errors.push('El campo '+$(this).attr('data-name')+' es obligatorio');
	  			$(this).css({'background':'#FDEDEC','border':'1px solid #EAEDED'});
	  			$(this).attr('placeholder','Requerido');
	  			//console.log($(this));
	  		}else if ($(this).val() !='') {
	  			//errors.push('El campo '+$(this).attr('data-name')+' es obligatorio');
	  			$(this).css({'background':'#fff','border':'1px solid #EAEDED'});
	  			//$(this).attr('placeholder','Requerido');
	  			////console.log($(this).getAttribute('class'));
	  		}	
		}
  		  			
  	});
  	return errors 	
} 

$("#btn_exp_user_cargar_create").click(function(){
	$('#msg-success').hide();	

});





//Comprobar si la cedula ya

function comprIdnumber() {

	var value = $.trim($("#idnumber").val());
	var route = "/expuser/"+value+"" ;
	var tipodoc_id = $("#tipodoc_id").val();
	if (tipodoc_id!='' && value!='') {
	$("#email").val(value+"@mail.com");
	//	
	var mydata = "#myform_exp_user_create";
	$.get(route, function(res){

		if (res.length>0) {
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
		$("#cursando_id").val(res.cursando_id);
		$("#estrato_id > option[value="+ res.estrato_id +"]").attr('selected',true);
		$("#estadocivil_id > option[value="+ res.estadocivil_id +"]").attr('selected',true);
		$("#genero_id > option[value="+ res.genero_id +"]").attr('selected',true);
		$("#active").prop('checked',res.active);
		if(res.pbesena=='1'){
			$("#pbesena").attr('checked',true);
		}else{
			$("#pbesena").attr('checked',false);	
		}
		if(res.pbepersondiscap=='1'){
			$("#pbepersondiscap").attr('checked',true);
		}else{
			$("#pbepersondiscap").attr('checked',false);
		}

		if(res.pbevictimconflic=='1'){
			$("#pbevictimconflic").attr('checked',true);
		}else{
			$("#pbevictimconflic").attr('checked',false);
		}
		
		if(res.pbeadultomayor=='1'){
			$("#pbeadultomayor").attr('checked',true);
		}else{
			$("#pbeadultomayor").attr('checked',false);
		}
		
		if(res.pbeminoetnica=='1'){
			$("#pbeminoetnica").attr('checked',true);
		}else{
			$("#pbeminoetnica").attr('checked',false);
		}
		
		if(res.pbemadrecomuni=='1'){
			$("#pbemadrecomuni").attr('checked',true);
		}else{
			$("#pbemadrecomuni").attr('checked',false);
		}
		
		if(res.pbecabezaflia=='1'){
			$("#pbecabezaflia").attr('checked',true);
		}else{
			$("#pbecabezaflia").attr('checked',false);
		}
		

		if(res.pbeninguna=='1'){
			$("#pbeninguna").attr('checked',true);
			$(".check-user").attr('disabled',true);
		}else{
			$("#pbeninguna").attr('checked',false);
			$(".check-user").attr('disabled',false);
		}
		
		if(res.tipodoc!='.'){
			$("#tipodoc > option[value="+ res.tipodoc +"]").attr('selected',true);
		}
	});
		}
		
	});
	}
	//asignar cedula automatica	
	
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
	//var token = $("#token").val();


	errors = validateForm('myform_exp_user_create');
	if (errors.length<=0) {
		
		$.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'PUT',
		datatype: 'json',
		data: $("#myform_exp_user_create").serialize(),
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      $("#wait").css("display", "block");   
    },

		
		success:function(res){
			$("#wait").css("display", "none"); 

			$("#expidnumber").val(res.idnumber);
			$(mydata)[0].reset();
			$("#myModal_exp_user_create .close").click();
		    $('#msg-success').fadeIn();
			$("#btn_exp_user_create_edit").hide();
		    $("#btn_exp_user_create").fadeIn();


		},
    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }

	});
	}
});
//    $(document).ajaxComplete(function(){
//        $("#wait").css("display", "none");
//    });
//});//cierra el querri document




/*................................... <calificaciones>.................................................... */
/*.......................................................................................................................... */
/*.......................................................................................................................... */
$(".notac").change(function(){



	var col1=   parseFloat($("#notcasocol1").val());
	var col2= 	parseFloat($("#notcasocol2").val());
	var col3= 	parseFloat($("#notcasocol3").val());
	if (col1 > 5) { alert('La nota no puede ser mayor a 5'); $("#notcasocol1").val(''); }
	if (col2 > 5) { alert('La nota no puede ser mayor a 5'); $("#notcasocol2").val(''); }
	if (col3 > 5) { alert('La nota no puede ser mayor a 5'); $("#notcasocol3").val(''); }
	if (!isFinite(col1)) { var col1= parseFloat(0);	}
	if (!isFinite(col2)) { var col2= parseFloat(0);	}
	if (!isFinite(col3)) { var col3= parseFloat(0);	}


	var notatotalcaso= (col1+col2+col3)/3;

		//alert((notatotalcaso));

	$("#nottotcol1").val(notatotalcaso.toFixed(1));

 	sumaTotalNotas();


});

$(".notaa").change(function(){



	var col1=   parseFloat($("#notactcol1").val());
	var col2= 	parseFloat($("#notactcol2").val());
	var col3= 	parseFloat($("#notactcol3").val());
	if (col1 > 5) { alert('La nota no puede ser mayor a 5'); $("#notactcol1").val(''); }
	if (col2 > 5) { alert('La nota no puede ser mayor a 5'); $("#notactcol2").val(''); }
	if (col3 > 5) { alert('La nota no puede ser mayor a 5'); $("#notactcol3").val(''); }
	if (!isFinite(col1)) { var col1= parseFloat(0);	}
	if (!isFinite(col2)) { var col2= parseFloat(0);	}
	if (!isFinite(col3)) { var col3= parseFloat(0);	}


	var notatotalcaso= (col1+col2+col3)/3;

		//alert((notatotalcaso));

	$("#nottotcol2").val(notatotalcaso.toFixed(1));


 	sumaTotalNotas();

});

$(".notar").change(function(){



	var col1=   parseFloat($("#notreqcol1").val());
	var col2= 	parseFloat($("#notreqcol2").val());
	var col3= 	parseFloat($("#notreqcol3").val());
	if (col1 > 5) { alert('La nota no puede ser mayor a 5'); $("#notreqcol1").val(''); }
	if (col2 > 5) { alert('La nota no puede ser mayor a 5'); $("#notreqcol2").val(''); }
	if (col3 > 5) { alert('La nota no puede ser mayor a 5'); $("#notreqcol3").val(''); }
	if (!isFinite(col1)) { var col1= parseFloat(0);	}
	if (!isFinite(col2)) { var col2= parseFloat(0);	}
	if (!isFinite(col3)) { var col3= parseFloat(0);	}


	var notatotalreq= (col1+col2+col3)/3;

		//alert((notatotalreq));

	$("#nottotcol3").val(notatotalreq.toFixed(1));

 	sumaTotalNotas();

});

function sumaTotalNotas(){



	var col1=   parseFloat($("#notcasocol1").val());
	var col2= 	parseFloat($("#notcasocol2").val());
	var col3= 	parseFloat($("#notcasocol3").val());

	if (!isFinite(col1)) { var col1= parseFloat(0);	}
	if (!isFinite(col2)) { var col2= parseFloat(0);	}
	if (!isFinite(col3)) { var col3= parseFloat(0);	}


	var notatotalcaso= (col1+col2+col3)/3;

		//alert((notatotalcaso));

	$("#nottotcol4").val(notatotalcaso.toFixed(1));




}

$("#btn_calificaciones_create").click(function(){



	//var value = $("#id").val();
	var mydata = "#myform_califica_create";

	var route = "/notas";
	//var token = $("#token").val();
	
    $.ajax({ 
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'post',
		datatype: 'json',
		data:$("#myform_califica_create").serialize(),
		cache: false,
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");
    },

        /*muestra div con mensaje de 'regristrado'*/

        success:function(res){

        	alert('¡Notas guardadas con éxito!');

		},

    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }



	});

});

//carga el selector de estudiantes cuando se cambia el tipo de consulta para la asignacion automatica
$("#exptipoproce_id2").change(function(){
	var idconsul = $("#exptipoproce_id2").val();
	var optionest = "";

if (idconsul>=1) {


				var prsimpes=0;
				var prconplejas=0;
				var colortext="\'label label-danger \'";
				var colortext2="\'label label-danger \'";
          var route = "selectconest/"+idconsul;
          $("#wait").css("display", "block");
          $.get(route, function(res){

             if (res == "") {
               $("#expidnumberest").append('<option value="000000" data-content="<span class=\'label label-danger \'>ERROR AL CARGAR LOS DATOS</span> ">ERROR AL CARGAR LOS DATOS</option>');//coloca una nueva opcion
                $(".estselect1").selectpicker("refresh");//refresca el select
                $("#wait").css("display", "none");
                  } else {
          $("#expidnumberest").find('option').remove().end();//elimina opciones existentes
          var optionest = '';
          $(res).each(function(key, value){
          		if (value.simples != 0) {
				prsimpes=(value.simples_cerradas*100)/value.simples;
				}
				if (value.complejas != 0) {
				prconplejas=(value.complejas_cerradas*100)/value.complejas;
				}

				 if (prsimpes < 40) {
  					colortext="\'label label-success \'";
				 }
				 if (prsimpes >= 40 && prsimpes <= 60) {
  				  	colortext="\' label label-warning \'";
				 }
				 if (prsimpes > 60 ) {
  				  	colortext="\' label label-danger \'";
				 }
				 if (prconplejas < 40) {
  				 	colortext2="\' label label-success \'";
				 }
				 if (prconplejas >= 40 && prconplejas <= 60) {
  				 	colortext2="\' label label-warning \'";
				 }
				 if (prconplejas > 60 ) {
  				 	colortext2="\' label label-danger \'";
				 }
				 	var nombre_com = value.name+' '+value.lastname;
				 	optionest +='<option value="'+value.astid_estudent+'" data-content="'+nombre_com.toUpperCase()+' <span class='+colortext+'>A.'+value.simples+'</span> <span class='+colortext2+'>S.'+value.complejas+'</span>">'+value.name+' '+value.lastname+'</option>';
                // //console.log(value.idnumber);

          });
          	//$("#expidnumberest").append('<option value="0000000" data-content="luis carlos <span class=\'label label-danger \'>S.11</span> ">luis carlos</option>');//coloca una nueva opcion
$("#expidnumberest").append(optionest);//coloca una nueva opcion
 //$('#contencalendarid').append('<tr><td>'+parseInt(key+1)+'</td><td>'+value.name+' '+value.lastname+'</td><td>'+textcurso+'</td></tr>');
$(".estselect1").selectpicker("refresh");//refresca el select
        }
        $("#wait").css("display", "none");

          });

}
});

//carga la tabla de reporte asistencia
$("#reporasistencia_btn").click(function(){


				var prsimpes=0;
				var prconplejas=0;

          var route = "turnos/asistencia";
          $("#wait").css("display", "block");
          $.get(route, function(res){

             if (res == "") {
               $("#contenrepasistencia").append('');//coloca una nueva opcion

                $("#wait").css("display", "none");
                  } else {
         //$("#expidnumberest").find('option').remove().end();//elimina opciones existentes
	var datosasis = "";
          $(res).each(function(key, value){

 // //console.log(value.idnumber);
		datosasis +='<tr>'+
		              '<td>'+parseInt(key+1)+'</td>'+
                      '<td>'+value.idnumber+'</td>'+
                      '<td>'+value.name+' '+value.lastname+'</td>'+
                      '<td>'+value.ref_nombre+'</td>'+
                      '<td>'+value.asistencia+'</td>'+
                      '<td>'+parseInt(parseInt(value.falta_doble*2)+parseInt(value.falta_simple))+'</td>'+
                      '<td>'+value.reposicion+'</td>'+
                      '<td><button type="button" class="btn btn-success btn-sm btn_det_rasis" id="dt_rasis-'+value.idnumber+'" name="'+value.name+' '+value.lastname+'">Detalles</button></td>'+
                    '</tr>';


          });

$("#contenrepasistencia").append(datosasis);//coloca una nueva opcion
        }
        $("#wait").css("display", "none");

          });


});


//carga los detalles del reporte asistencia
$("#tbl_repor_asis").on("click" , ".btn_det_rasis" ,function(){
var nombre = '';
          var textlugar = [];
          textlugar["130"]="Consultorios";
          textlugar["131"]="C.J. Virtuales";
          textlugar["132"]="Of. Desplazados";
          textlugar["133"]="Externo";
          textlugar["134"]="Otro";
var id=getIdAttr($(this).attr('id'), "-", 1);
 $("#ced_det_asis").text(' '+id);
 $("#nom_det_asis").text($(this).attr('name'));
$("#table-details-asistencia tr").remove();
          var route = "turnos/asistencia/detalles/"+id;
          $("#wait").css("display", "block");
          $.get(route, function(res){

             if (res == "") {
             	$("#estadp_det_asis").text('No hay información');
               $("#table-details-asistencia").append('');//coloca una nueva opcion

                $("#wait").css("display", "none");
                  } else {
         //$("#expidnumberest").find('option').remove().end();//elimina opciones existentes
	var datosasis = "";
          $(res).each(function(key, value){
          nombre = value.name+' '+value.lastname;
 // //console.log(value.idnumber);
		datosasis +='<tr>'+
		              '<td>'+parseInt(key+1)+'</td>'+
                      '<td>'+value.ref_nombre+'</td>'+
                      '<td>'+textlugar[value.astid_lugar]+'</td>'+
                      '<td>'+value.astfecha+'</td>'+
                      '<td><div class="textcor">'+value.astdescrip_asist+'</div></td>'+
					 '</tr>';


          });

$("#table-details-asistencia").append(datosasis);//coloca una nueva opcion
 $("#nom_det_asis").text(' '+nombre);
 $("#estadp_det_asis").text('');
        }
        $("#wait").css("display", "none");
          });

 $("#myModal_reporasis").modal("show");

});

//guarda la busqueda de listar expediente para boton atras
$('#table_list_model').on('click',"a.btn-edit-le",function(){
	var url =window.location;
// Check browser support
if (typeof(Storage) !== "undefined") {
    // Store
    localStorage.setItem("dirreg", url);
    // Retrieve
} else {

}

});

$("a.btn-atrasexed").click(function(){
window.location.href = localStorage.getItem("dirreg");
});

var n_text = 0;
var opcion_busq = '';
var text_busq = '';
var sw_bq = 1;
$('.table-buscar-expe').on('keyup',"div.disabled-fun2 input",function(){
	
	var text_busq_sub = text_busq.substring(0, 3);
	text_busq = $(this).val();
	var text_busq_sub2 = text_busq.substring(0, 3);
	var verifi = '';
	if (text_busq.length > 3) {
		if (text_busq_sub.length == 3) {	
			if (text_busq_sub == text_busq_sub2) {	
					var resultado = $('div.disabled-fun2 li.no-results').text();
					resultado = resultado.substring(0, 17);
				if (resultado == 'No hay resultados' && sw_bq == 1) {
					verifi = 'verifi';
					Consulta_users (text_busq, verifi);
					verifi = '';
					//console.log('no va');
				}
			} else {
				Consulta_users (text_busq, verifi);
			}
		}
	} else if (text_busq.length === 3) {
		$('div.disabled-fun2 li.no-results').text('Buscando...');
		$("#select_data_consultantes").find('option').remove().end();//elimina opciones existentes
		$(".disabled-fun2").selectpicker('render');
		$(".disabled-fun2").selectpicker("refresh");//refresca el select
		Consulta_users (text_busq, verifi);
	} else if (text_busq.length < 3) {
		$('div.disabled-fun2 li.no-results').text('La busqueda requiere más información');
		$("#select_data_consultantes").find('option').remove().end();//elimina opciones existentes
		$(".disabled-fun2").selectpicker('render');
		$(".disabled-fun2").selectpicker("refresh");//refresca el select
	}
	n_text = text_busq.length;
});

function Consulta_users (text_busq, origen) {
	var route = "users/get/"+text_busq;
	$.get(route, function(res){
		if (res == "") {
			$('div.disabled-fun2 li.no-results').text('No hay registros para:"'+text_busq+'"');
			$("#select_data_consultantes").find('option').remove().end();//elimina opciones existentes
			$(".disabled-fun2").selectpicker('render');
			$(".disabled-fun2").selectpicker("refresh");//refresca el select
			if (origen == 'verifi') {
				sw_bq = 0;
			}
		} else {
			sw_bq = 1;
			$("#select_data_consultantes").find('option').remove().end();//elimina opciones existentes
			$(".disabled-fun2").selectpicker('render');
			opcion_busq = '';
			 $(res).each(function(key, value){
				opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name.toUpperCase() + '</option>';
			});
			$("#select_data_consultantes").append(opcion_busq);//coloca una nueva opcion
		$(".disabled-fun2").selectpicker("refresh");//refresca el select
		}
	});
  }

function tConvert (time) {
	// Check correct time format and split into components
	time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
  
	if (time.length > 1) { // If time format correct
	  time = time.slice (1);  // Remove full string match value
	  time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
	  time[0] = +time[0] % 12 || 12; // Adjust hours
	}
	time[3]= " ";
	if (time[0] < 10) {
		time[0]="0"+time[0];
	} 
	//time = time.replace(":00PM", " PM");
	 //time = time.replace(":00AM", " AM");
	return time.join (''); // return adjusted time or original string
  }
  

function filas_horario_docente(order, cedula, hora_concate) {
	
	if (hora_concate == 0) {
		var id_dias = parseInt(order) + parseInt(1);
		var h_i = "";
		var h_f = "";
	} else { 
	var horas = hora_concate.split("_");
	var h_i = tConvert (horas[0]);
	var h_f = tConvert (horas[1]);
	var id_dias = hora_concate.replace(/:/g, "");
	}
	order = parseInt(order) + parseInt(1);	
	var new_tr = `<tr id="tr_${cedula}_${order}" class="docente-fila-horario">

					<td>
						<div class="bootstrap-timepicker" style="min-width:118px;max-width:118px;">
            			    <div class="form-group">
								<div class="input-group">
									<input type="text" id="hora_ini_doc_${order}" class="form-control timepicker" value="${h_i}">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>
                  			<!-- /.input group -->
                			</div>
                			<!-- /.form group -->
              			</div>					
					</td>
					<td>
						<div class="bootstrap-timepicker" style="min-width:118px;max-width:118px;">
            			    <div class="form-group">
								<div class="input-group">
									<input type="text" id="hora_fin_doc_${order}" class="form-control timepicker" value="${h_f}">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
								</div>
                  			<!-- /.input group -->
                			</div>
                			<!-- /.form group -->
              			</div>				
					</td>
					<td>
						<label><input type="checkbox" id="Lunes_${id_dias}" class="dias orderfhd_${order}" value=""></label>						
					</td>
					<td>
						<label><input type="checkbox" id="Martes_${id_dias}" class="dias orderfhd_${order}" value=""></label>						
					</td>
					<td>
						<label><input type="checkbox" id="Miercoles_${id_dias}" class="dias orderfhd_${order}" value=""></label>						
					</td>					
					<td>
						<label><input type="checkbox" id="Jueves_${id_dias}" class="dias orderfhd_${order}" value=""></label>						
					</td>
					<td>
						<label><input type="checkbox" id="Viernes_${id_dias}" class="dias orderfhd_${order}" value=""></label>						
					</td>
					<td  style="vertical-align: middle;">
					<button id="id-tr_${cedula}_${order}" class="btn btn-danger horariomenos" data-toggle="tooltip" data-placement="top" title="Quitar horario"><span class="glyphicon glyphicon-minus"></span></button>				
					</td>
				</tr>
				`;
		$("#table_turnos_docentes").append(new_tr);
		$("#horariomas").val(order);
		$('.timepicker').timepicker({
				showInputs: false
			  });
   
   }

$("#select_doc_horario").change(function(){
	var docidmunber = $(this).val();
	consultar_horario(docidmunber);
});

function consultar_horario(docidmunber) {
	if (docidmunber != 0) {
		dias = [];
		horas = [];
		checks_bd = [];
	$('.docente-fila-horario').remove();
	var name_docente = $("#select_doc_horario option:selected").text();	
	$("#name_doc_horairo").text(name_docente);
	var route = "/turnos/docentes/"+docidmunber;
	$.get(route, function(res){
		if (res == "") {
			filas_horario_docente(1, docidmunber,0);
		} else {
			var horas_primary = [];
			$(res).each(function(key, value){
				var hora_concate = value.trnd_hora_inicio+'_'+value.trnd_hora_fin;
				if (!horas_primary.includes(hora_concate)) {
					horas_primary.push(hora_concate);
				}				
			});
			info_filas(res, horas_primary, docidmunber);
			
		}
	});
	} else {
		$('.docente-fila-horario').remove();
		$("#name_doc_horairo").text('Seleccione un docente');
	}

}

 var checks_bd = [];
function info_filas(info, horas_primary, docidnumber) {
	var order = 0;
	$(horas_primary).each(function(key, hora_value){
		filas_horario_docente(order, docidnumber, hora_value);
		$(info).each(function(key, value){
			var hora_concate = value.trnd_hora_inicio+'_'+value.trnd_hora_fin;
			if (hora_value == hora_concate) {
				var id_dia = value.trnd_dia+"_"+hora_concate;
				id_dia = id_dia.replace(/:/g, "");
				checks_bd.push(id_dia);
				$("#"+id_dia).prop('checked',true);
				//console.log($("#"+id_dia).val());
			}
		});
	order=order+1;
	});

}

$("#horariomas").click(function(){
	var order = $(this).val();
	var docidmunber = $("#select_doc_horario").val();
	filas_horario_docente(order, docidmunber,0);

});

$('#table_turnos_docentes').on('click',".horariomenos",function(){
	var id_btn = $(this).attr('id');
	//se determina la informacion a eliminar de la bd.
	var v_id_btn2 = id_btn.split("_");
	var order = v_id_btn2[2];
	var checks = $(".orderfhd_"+order).map(function() {
		return $(this).attr("id");
	  }).get();
	$(checks).each(function(key, value){
		if (dias.includes(value)) {
			var i = dias.indexOf( value );
			  dias.splice( i, 1 );
		}
		if (checks_bd.includes(value)) {
			dias.push(value);
		}
	});
	
	//se elimina grficamente la informacion.
	var v_id_btn = id_btn.split("-");
	var id_tr = v_id_btn[1];	
	$('#'+id_tr).remove();

});

var dias= [];

$('#table_turnos_docentes').on('click',".dias",function(){
	var id_check = $(this).attr('id');
	if (dias.includes(id_check)) {
		var i = dias.indexOf( id_check );
  		dias.splice( i, 1 );
	} else {
		dias.push(id_check);	
	}
	
});

var horas = [];

$('#table_turnos_docentes').on('click',".timepicker",function(){
	var id_time = $(this).attr("id");
	var v_id_time = id_time.split("_");
	var order = v_id_time [3];
	if (!$("#Miercoles_"+order).attr('class')) { //verifica si es nuevo o esta en la bd
		horas.push(id_time);
	}
});

function getTwentyFourHourTime(amPmString) { 
	var d = new Date("1/1/2013 " + amPmString); 
	var horas_fun = d.getHours();
	var minutes = d.getMinutes();
	if (horas_fun < 10) {horas_fun = "0"+horas_fun;}
	if (minutes < 10) {minutes = "0"+minutes;}
	return horas_fun +":"+ minutes+":00"; 
}

function getTwentyFourHourTimealter(amPmString) { 
	var d = new Date("1/1/2013 " + amPmString); 
	var horas_fun = d.getHours();
	var minutes = d.getMinutes();
	if (horas_fun < 10) {horas_fun = "0"+horas_fun;}
	if (minutes < 10) {minutes = "0"+minutes;}
	return horas_fun +""+ minutes+"00"; 
}

function dospuntoshora(cadena) { 
	var horas_fun = cadena.substring(0, 2);
	var minutes = cadena.substring(2, 4);
	return horas_fun +":"+ minutes+":00"; 
}

$("#guardar_horario_doc").click(function(){
	var mydata=[];
	var docidmunber = $("#select_doc_horario").val();
	if (dias.length > 0) { // insertar o eliminar dias
		$(dias).each(function(key, value){
			var v_info_id = value.split("_");
			if ($("#"+value).prop('checked')) { //crea el registro
				if (v_info_id.length==2) {
					var hora_ini_i = getTwentyFourHourTime($("#hora_ini_doc_"+v_info_id[1]).val());
					var hora_fin_i = getTwentyFourHourTime($("#hora_fin_doc_"+v_info_id[1]).val());
					//console.log("usuario:"+docidmunber+" accion:crear"+" value:"+v_info_id[0]+" hora_i:"+hora_ini_i+" hora_f:"+hora_fin_i);
					var info = {"usuario":docidmunber, "accion":"crear", "value":v_info_id[0], "hora_i":hora_ini_i, "hora_f":hora_fin_i};
					mydata.push(info);
				} else if (v_info_id.length==3) {
					var hora_ini_i = dospuntoshora(v_info_id[1]);
					var hora_fin_i = dospuntoshora(v_info_id[2]);
					//console.log("usuario:"+docidmunber+" accion:crear"+" value:"+v_info_id[0]+" hora_i:"+hora_ini_i+" hora_f:"+hora_fin_i);
					var info = {"usuario":docidmunber, "accion":"crear","value":v_info_id[0], "hora_i":hora_ini_i, "hora_f":hora_fin_i};
					mydata.push(info);
				}
			} else { //elimina el registro
				var hora_ini_e = dospuntoshora(v_info_id[1]);
				var hora_fin_e = dospuntoshora(v_info_id[2]);
				//console.log("usuario:"+docidmunber+" accion:eliminar"+" value:"+v_info_id[0]+" hora_i:"+hora_ini_e+" hora_f:"+hora_fin_e);
				var info = {"usuario":docidmunber, "accion":"eliminar", "value":v_info_id[0], "hora_i":hora_ini_e, "hora_f":hora_fin_e};
				mydata.push(info);
			}
		});
	}
	if (horas.length > 0) { // actualizar horas
		const horas_fil = [...new Set(horas)];//quita duplicados
		var control = [];
		$(horas_fil).each(function(key, value){
			var control_hora = value;
			var v_id_time = value.split("_");
			var order = v_id_time [3];
			var tip_time = v_id_time [1];
			var checks_num = 0
			var checks = $(".orderfhd_"+order).map(function() {
				if ($(this).prop('checked')) {checks_num++;}
				return $(this).attr("id");
			  }).get();
			if (checks_num > 0) {//verifica si hay dias chequeados para actualizar de lo contrario no hace nada
				var v_check = checks[0].split("_");
				var hora_new_time = $("#"+value).val();
				var hora_new_compara = getTwentyFourHourTimealter(hora_new_time);
				var hora_new_time = getTwentyFourHourTime(hora_new_time);
				if (tip_time == "ini") {
					if (hora_new_compara != v_check[1]) { //verifica si hay cambios en la hora de inicio para actualizar
						var hora_old=dospuntoshora(v_check[1]);
						var hora_ref_fin=dospuntoshora(v_check[2]);
						//console.log("usuario:"+docidmunber+" accion:actualizar_i"+" value:"+hora_new_time+" hora_i:"+hora_old+" hora_f:"+hora_ref_fin);
						if (!control.includes(v_check[1]+v_check[2])) {
							var hora_fin_val = getTwentyFourHourTime($("#hora_fin_doc_"+order).val());
							var horas_ac = [hora_new_time,hora_fin_val];
							var info = {"usuario":docidmunber, "accion":"actualizar", "value":horas_ac, "hora_i":hora_old, "hora_f":hora_ref_fin};
							mydata.push(info);
							control.push(v_check[1]+v_check[2]);
						}
					}
				} else if (tip_time == "fin") {
					if (hora_new_compara != v_check[2]) {//verifica si hay cambios en la hora fin para actualizar
						var hora_ref_ini=dospuntoshora(v_check[1]);
						var hora_old=dospuntoshora(v_check[2]);
						//console.log("usuario:"+docidmunber+" accion:actualizar_f"+" value:"+hora_new_time+" hora_i:"+hora_ref_ini+" hora_f:"+hora_old);
						if (!control.includes(v_check[1]+v_check[2])) {
							var hora_ini_val = getTwentyFourHourTime($("#hora_ini_doc_"+order).val());
							var horas_ac = [hora_ini_val,hora_new_time];
							var info = {"usuario":docidmunber, "accion":"actualizar", "value":horas_ac, "hora_i":hora_ref_ini, "hora_f":hora_old};
							mydata.push(info);
							control.push(v_check[1]+v_check[2]);
						}
					}
				}
			}
			
		});
		
	}
console.log(mydata);
	if (mydata.length > 0) { //comprueba si hay informacion para guardar
		var route = "/tunos/acdocentes";
		//var token = $("#token").val();
		$.ajax({ 
			url: route,
			headers: { 'X-CSRF-TOKEN' : token },
			type:'post',
			datatype: 'json',
			data:{mydata},
			cache: false,
			 beforeSend: function(xhr){
		  xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
		  //$("#wait").css("display", "block");
		},
	
			/*muestra div con mensaje de 'regristrado'*/
	
			success:function(res){
				consultar_horario(docidmunber);
				alert('¡Horario guardado con éxito!');
	
			},
	
		error:function(xhr, textStatus, thrownError){
			alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
		}
	
		});
	} else {
		console.log("no hay informacíon");
	}
});

$("#modalhcaso").click(function(){
	$("#mymodal-dinamyc-tittle").html("caso");
	ConsultaHistorialDatosCaso(exp,tipo);
	$("#mymodaljs").modal("show");
	
});
$("#modalresestudiante").click(function(){
	$("#mymodal-dinamyc-tittle").html("respuesta");
	ConsultaHistorialDatosCaso(exp,tipo);
	$("#mymodaljs").modal("show");

});

function ConsultaHistorialDatosCaso(exp,tipo) { 
fff	
}