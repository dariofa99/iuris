import { HorariosService } from "./services/turnos.js";
const horariosService = new HorariosService();
var dias = [];
var checks_bd = [];
var horas = [];
$(document).ready(function () {
	set_tab();
	getAsistenciaReport();
	$(".btn_habilityupdatecolor").on("click", function (e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		habilityEditColor(id);
	});
	$(".btn_cancelupdcolor").on("click", function (e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		hideEditColor(id);
	});
	$("#horariomas").click(function () {
		var order = $(this).val();
		var docidmunber = $("#select_doc_horario").val();
		filas_horario_docente(order, docidmunber, 0);

	});

	$(".btn_updatecolor").click(async function (e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		var estudiante_id = $("#estudiante_id" + id).val();
		var color_id = $("#color_id" + id).val();
		var horario_id = $("#horario_id" + id).val();
		var cursando_id = $("#cursando_id" + id).val();
		var trnid_oficina = $("#trnid_oficina" + id).val();
		var trnid_dia = $("#trnid_dia" + id).val();
		let request = {
			trnid_dia: trnid_dia,
			trnid_oficina: trnid_oficina,
			estudiante_id: estudiante_id,
			color_id: color_id,
			horario_id: horario_id,
			cursando_id: cursando_id,
		}
		$("#wait").show();
		let response = await horariosService.updateAsigTurno(request, id);
		hideEditColor(id);
		toastr.success("Actualizado con éxito", "", {
			positionClass: "toast-top-right",
			timeOut: "4000",
		});
		window.location.reload();
		//$("#wait").hide();
	});

	$(".btn_delete_turno").on("click", function () {
		var id = $(this).attr("data-id");
		Swal.fire({
			title: 'Esta seguro de eliminar la asignación del turno?',
			text: "Los cambios no podran ser revertidos!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, eliminar!',
			cancelButtonText: 'No, cancelar',
			html: `
					<div class="alert alert-warning" style="margin-top:10px; text-align:left;">
					<input type="checkbox" id="confirmCheck" checked />
					<label for="confirmCheck">¿Inactivar estudiante?</label>
					</div>
  				`
		}).then(async (result) => {
			if (result.value) {
				$("#wait").show();
				let inactivarEstudiante = document.getElementById("confirmCheck").checked;
				let estudiante_id = $(this).attr("data-estudiante");
				let response = await horariosService.deleteTurno(id, { inactivarEstudiante: inactivarEstudiante, estudiante_id: estudiante_id });
				toastr.success("Eliminado con éxito", "", {
					positionClass: "toast-top-right",
					timeOut: "4000",
				});
				window.location.reload(true);
			}
		});
	});
	$("#btn_del_all_turnos").click(async function (e) {
		Swal.fire({
			title: 'Esta seguro de eliminar la asignación de turnos?',
			text: "Los cambios no podran ser revertidos!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Si, eliminar!',
			cancelButtonText: 'No, cancelar'
		}).then(async (result) => {
			if (result.value) {
				$("#wait").show();
				let response = await horariosService.deleteAllTurnos();
				toastr.success("Eliminado con éxito", "", {
					positionClass: "toast-top-right",
					timeOut: "4000",
				});
				window.location.reload(true);
			}
		});


	});

	$("#select_doc_horario").change(function () {
		var docidmunber = $(this).val();
		$("#content-docentetr").hide();
		$("#name_doc_horairo").text("Seleccione un docente");
		if (docidmunber != "" && docidmunber != 0) {
			$("#content-docentetr").show();
			consultar_horario(docidmunber);
		}

	});

	$('#table_turnos_docentes').on('click', ".dias", function () {
		var id_check = $(this).attr('id');
		if (dias.includes(id_check)) {
			var i = dias.indexOf(id_check);
			dias.splice(i, 1);
		} else {
			dias.push(id_check);
		}

	});
	$('#table_turnos_docentes').on('click', ".timepicker", function () {
		var id_time = $(this).attr("id");
		var v_id_time = id_time.split("_");
		var order = v_id_time[3];
		if (!$("#Miercoles_" + order).attr('class')) { //verifica si es nuevo o esta en la bd
			horas.push(id_time);
		}
	});
	$("#guardar_horario_doc").click(async function () {
		var mydata = [];
		var docidmunber = $("#select_doc_horario").val();
		console.log(dias);
		if (dias.length > 0) { // insertar o eliminar dias
			$(dias).each(function (key, value) {
				console.log(value);

				var v_info_id = value.split("_");

				console.log(v_info_id);

				if ($("#" + value).prop('checked')) { //crea el registro
					if (v_info_id.length == 2) {
						var hora_ini_i = getTwentyFourHourTime($("#hora_ini_doc_" + v_info_id[1]).val());
						var hora_fin_i = getTwentyFourHourTime($("#hora_fin_doc_" + v_info_id[1]).val());
						//console.log("usuario:"+docidmunber+" accion:crear"+" value:"+v_info_id[0]+" hora_i:"+hora_ini_i+" hora_f:"+hora_fin_i);
						var info = { "usuario": docidmunber, "accion": "crear", "value": v_info_id[0], "hora_i": hora_ini_i, "hora_f": hora_fin_i };
						mydata.push(info);
					} else if (v_info_id.length == 3) {
						var hora_ini_i = dospuntoshora(v_info_id[1]);
						var hora_fin_i = dospuntoshora(v_info_id[2]);
						//console.log("usuario:"+docidmunber+" accion:crear"+" value:"+v_info_id[0]+" hora_i:"+hora_ini_i+" hora_f:"+hora_fin_i);
						var info = { "usuario": docidmunber, "accion": "crear", "value": v_info_id[0], "hora_i": hora_ini_i, "hora_f": hora_fin_i };
						mydata.push(info);
					}
				} else { //elimina el registro
					var hora_ini_e = dospuntoshora(v_info_id[1]);
					var hora_fin_e = dospuntoshora(v_info_id[2]);
					//console.log("usuario:"+docidmunber+" accion:eliminar"+" value:"+v_info_id[0]+" hora_i:"+hora_ini_e+" hora_f:"+hora_fin_e);
					var info = { "usuario": docidmunber, "accion": "eliminar", "value": v_info_id[0], "hora_i": hora_ini_e, "hora_f": hora_fin_e };
					mydata.push(info);
				}
			});
		}
		if (horas.length > 0) { // actualizar horas
			const horas_fil = [...new Set(horas)];//quita duplicados
			var control = [];
			$(horas_fil).each(function (key, value) {
				var control_hora = value;
				var v_id_time = value.split("_");
				var order = v_id_time[3];
				var tip_time = v_id_time[1];
				var checks_num = 0
				var checks = $(".orderfhd_" + order).map(function () {
					if ($(this).prop('checked')) { checks_num++; }
					return $(this).attr("id");
				}).get();
				if (checks_num > 0) {//verifica si hay dias chequeados para actualizar de lo contrario no hace nada
					var v_check = checks[0].split("_");
					var hora_new_time = $("#" + value).val();
					var hora_new_compara = getTwentyFourHourTimealter(hora_new_time);
					var hora_new_time = getTwentyFourHourTime(hora_new_time);
					if (tip_time == "ini") {
						if (hora_new_compara != v_check[1]) { //verifica si hay cambios en la hora de inicio para actualizar
							var hora_old = dospuntoshora(v_check[1]);
							var hora_ref_fin = dospuntoshora(v_check[2]);
							//console.log("usuario:"+docidmunber+" accion:actualizar_i"+" value:"+hora_new_time+" hora_i:"+hora_old+" hora_f:"+hora_ref_fin);
							if (!control.includes(v_check[1] + v_check[2])) {
								var hora_fin_val = getTwentyFourHourTime($("#hora_fin_doc_" + order).val());
								var horas_ac = [hora_new_time, hora_fin_val];
								var info = { "usuario": docidmunber, "accion": "actualizar", "value": horas_ac, "hora_i": hora_old, "hora_f": hora_ref_fin };
								mydata.push(info);
								control.push(v_check[1] + v_check[2]);
							}
						}
					} else if (tip_time == "fin") {
						if (hora_new_compara != v_check[2]) {//verifica si hay cambios en la hora fin para actualizar
							var hora_ref_ini = dospuntoshora(v_check[1]);
							var hora_old = dospuntoshora(v_check[2]);
							//console.log("usuario:"+docidmunber+" accion:actualizar_f"+" value:"+hora_new_time+" hora_i:"+hora_ref_ini+" hora_f:"+hora_old);
							if (!control.includes(v_check[1] + v_check[2])) {
								var hora_ini_val = getTwentyFourHourTime($("#hora_ini_doc_" + order).val());
								var horas_ac = [hora_ini_val, hora_new_time];
								var info = { "usuario": docidmunber, "accion": "actualizar", "value": horas_ac, "hora_i": hora_ref_ini, "hora_f": hora_old };
								mydata.push(info);
								control.push(v_check[1] + v_check[2]);
							}
						}
					}
				}

			});

		}
		console.log(mydata);
		if (mydata.length > 0) { //comprueba si hay informacion para guardar
			let res = await horariosService.updateTurnosDocente(mydata);
			dias = [];
			consultar_horario(docidmunber);
			Toast.fire({
				title: 'Actualizado con éxito.',
				icon: 'success',
				timer: 2000,
			});
		} else {
			Toast.fire({
				title: 'No se han hecho cambios.',
				icon: 'info',
				timer: 2000,
			});
		}
	});

	$('#table_turnos_docentes').on('click', ".horariomenos", function () {
		var id_btn = $(this).attr('id');
		//se determina la informacion a eliminar de la bd.
		var v_id_btn2 = id_btn.split("_");
		var order = v_id_btn2[2];
		var checks = $(".orderfhd_" + order).map(function () {
			return $(this).attr("id");
		}).get();
		$(checks).each(function (key, value) {
			if (dias.includes(value)) {
				var i = dias.indexOf(value);
				dias.splice(i, 1);
			}
			if (checks_bd.includes(value)) {
				dias.push(value);
			}
		});

		//se elimina grficamente la informacion.
		var v_id_btn = id_btn.split("-");
		var id_tr = v_id_btn[1];
		$('#' + id_tr).remove();

	});

	$("#asistencia-tab").on("click", async function () {
		$("#wait").show();

		let res = await horariosService.getReporteAsistenciaDocente();
		var datosasis = "";
		$("#contenrepasistenciadoc").html('');
		if (res != "") {
			$(res.docentes).each(function (key, value) {
				var asistencias = 0;
				var permisos = 0;
				var reposiciones = 0;
				var datasistencias = res.asistencia.find(datosasis => datosasis.docidnumber === value.idnumber);
				var datapermisos = res.permisos.find(datosper => datosper.docidnumber === value.idnumber);
				var datareposiciones = res.reposicion.find(datosrepo => datosrepo.docidnumber === value.idnumber);
				if (datasistencias) { asistencias = datasistencias.asistencia; }
				if (datapermisos) { permisos = datapermisos.permisos; }
				if (datareposiciones) { reposiciones = datareposiciones.reposicion; }


				datosasis += '<tr>' +
					'<td>' + parseInt(key + 1) + '</td>' +
					'<td>' + value.idnumber + '</td>' +
					'<td>' + value.full_name + '</td>' +
					'<td>' + round(asistencias / 60) + '</td>' +
					'<td>' + round(permisos / 60) + '</td>' +
					'<td>' + round(reposiciones / 60) + '</td>' +
					'<td>' + parseInt(parseInt(round(permisos / 60)) - parseInt(round(reposiciones / 60))) + '</td>' +
					'</tr>';


			});
			$("#contenrepasistenciadoc").append(datosasis);//coloca una nueva opcion
		}
		$("#wait").hide();
	});

	$("#table_list_model").on("click", ".btn_asig_turno", function (e) {
		e.preventDefault();
		var idnumber = ($(this).attr("data-idnumber"));
		$("#label_idnumber_estToAsig").text(idnumber);
		$("#est_idnumber").val(idnumber);
		$("#myModal_asig_turno_estudiante").modal("show");
	});

	$("#btn_asig_turno_est").on("click", async function (e) {
		e.preventDefault();
		var request = convertFormToJSON('myform_asig_turno_est');
		$("#wait").show();
		let response = await horariosService.asigTurnoEst(request);
		toastr.success("Se actualizó con éxito", "", {
			positionClass: "toast-top-center",
			timeOut: "4000",
		});
		window.location.reload(true);
	});

	$("#tableEstAsistencia").on("click", ".btn_det_rasis", async function () {

		var id = $(this).attr("data-idnumber");
		let response = await horariosService.detallesAsistencia(id);
		$("#ced_det_asis").text(' ' + id);
		$("#nom_det_asis").text($(this).attr('name'));
		var datosasis = "";
		var textlugar = [];
		textlugar["130"] = "Consultorios";
		textlugar["131"] = "C.J. Virtuales";
		textlugar["132"] = "Of. Desplazados";
		textlugar["133"] = "Externo";
		textlugar["134"] = "Otro";
		$(response).each(function (key, value) {
			var nombre = value.name + ' ' + value.lastname;
			// //console.log(value.idnumber);
			datosasis += '<tr>' +
				'<td>' + parseInt(key + 1) + '</td>' +
				'<td>' + value.ref_nombre + '</td>' +
				'<td>' + textlugar[value.astid_lugar] + '</td>' +
				'<td>' + value.astfecha + '</td>' +
				'<td><div class="textcor">' + value.astdescrip_asist + '</div></td>' +
				'</tr>';


		});
		$("#table-details-asistencia").html(datosasis);//coloca una nueva opcion		
		$("#estadp_det_asis").text('');
		$("#myModal_reporasis").modal("show");
	});

	$('#myFormBuscarEstudiante').on('keyup', '.select_data_users', async function (e) {
		let name = $(this).val();
		if (name != '' && name.length >= 3) {
			var opcion_busq = '<option value="' + name + '">' + name + '</option>';
			$("#select_data_users").html(opcion_busq);
			let request = {
				'name': name,
			}
			getAsistenciaReport(request);
		} else if (name == '') {
			getAsistenciaReport();
		}
	});

});/////////////////////////////////////////////////////

async function getAsistenciaReport(request = {}) {
	$("#loader_inidiv").show()
	let response = await horariosService.getAsistenciaReport(request);
	if (response.length > 0) {
		var datosasis = '';
		response.forEach((value, key) => {
			let asistenciasNum = parseInt(value.asistencia) || 0;
			let badgeClass = "high";// asistenciasNum >= 80 ? 'high' : (asistenciasNum >= 60 ? 'medium' : 'low');

			// Convertir nota proporcional a número
			let notaProporcional = parseFloat(value.nota_proporcional) || 0;
			let notaBadgeClass = notaProporcional >= 4 ? 'high' : (notaProporcional >= 3 ? 'medium' : 'low');
			datosasis += `
                        <tr>
                            <td>
                                <span class="font-weight-bold text-primary">${parseInt(key + 1)}</span>
                            </td>
                            <td>
                                <span class="font-monospace">${value.idnumber}</span>
                            </td>
                            <td>
                                <strong>${value.name} ${value.lastname}</strong>
                            </td>
                            <td>
                                <span class="badge badge-light" style="background: #f0f2f5; color: #2c3e50; font-weight: 600;">
                                    ${value.ref_nombre}
                                </span>
                            </td>
                            <td>
                                <span class="badge-asistencia ${badgeClass}">
                                    ${value.asistencia}
                                </span>
                            </td>
                            <td>
                                <span style="color: #dc3545; font-weight: 700;">
                                    ${parseInt(value.total_faltas)}
                                </span>
                            </td>
                            <td>
                                <span style="color: #11998e; font-weight: 700;">
                                    ${value.reposicion}
                                </span>
                            </td>
                            <td>
                                <span class="badge-asistencia ${notaBadgeClass}">
                                    ${notaProporcional.toFixed(1)}
                                </span>
                            </td>
                            <td>
                                <button type="button" 
                                    class="btn btn-details-modern btn_det_rasis" 
                                    data-idnumber="${value.idnumber}" 
                                    id="dt_rasis-${value.idnumber}" 
									name="${value.name} ${value.lastname}"
                                    title="Ver detalles de ${value.name} ${value.lastname}">
                                    <i class="fas fa-eye mr-1"></i> Detalles
                                </button>
                            </td>
                        </tr>
                    `;
		});
		$("#tableEstAsistencia tbody").html(datosasis);
	} else {
		$("#tableEstAsistencia tbody").html('<tr><td colspan="8">No se encontraron resultados</td></tr>');
	}
	$("#loader_inidiv").hide()
}

function habilityEditColor(turno_id) {
	showElement("color_id" + turno_id);
	showElement("cursando_id" + turno_id);
	showElement("horario_id" + turno_id);
	showElement("trnid_oficina" + turno_id);
	showElement("trnid_dia" + turno_id);
	showElement("btn_hideupdatecolor" + turno_id);
	showElement("btnUpdatecolor_" + turno_id);
	hideElement("btn_habilityupdatecolor" + turno_id);
	hideElement("label_color" + turno_id);
	hideElement("label_cursando" + turno_id);
	hideElement("label_horario" + turno_id);
	hideElement("label_trnid_oficina" + turno_id);
	hideElement("label_trnid_dia" + turno_id);
	hideElement("btn_delete_turno-" + turno_id);
}

function filas_horario_docente(order, cedula, hora_concate) {

	if (hora_concate == 0) {
		var id_dias = parseInt(order) + parseInt(1);
		var h_i = "";
		var h_f = "";
	} else {
		console.log(order + " " + cedula + " " + hora_concate);

		var horas = hora_concate.split("_");
		var h_i = (horas[0]);
		var h_f = (horas[1]);
		var id_dias = hora_concate.replace(/:/g, "");
	}
	order = parseInt(order) + parseInt(1);
	var new_tr = `<tr id="tr_${cedula}_${order}" class="docente-fila-horario">

					<td>
					<input type="time" id="hora_ini_doc_${order}" class="form-control timepicker" value="${h_i}">
														
					</td>
					<td>
					<input type="time" id="hora_fin_doc_${order}" class="form-control timepicker" value="${h_f}">
												
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
					<button id="id-tr_${cedula}_${order}" class="btn btn-danger horariomenos" data-toggle="tooltip" data-placement="top" title="Quitar horario"><span class="fas fa-minus"></span></button>				
					</td>
				</tr>
				`;
	$("#table_turnos_docentes").append(new_tr);
	$("#horariomas").val(order);
	/* $('.timepicker').timepicker({
			showInputs: false
		  }); */

}

function consultar_horario(docidmunber) {
	if (docidmunber != 0) {
		var dias = [];
		var horas = [];
		var checks_bd = [];
		$('.docente-fila-horario').remove();
		var name_docente = $("#select_doc_horario option:selected").text();
		$("#name_doc_horairo").text(name_docente);
		var route = "/turnos/docentes/" + docidmunber;
		$("#wait").show();
		$.get(route, function (res) {
			if (res == "") {
				filas_horario_docente(1, docidmunber, 0);
			} else {
				var horas_primary = [];
				$(res).each(function (key, value) {
					var hora_concate = value.trnd_hora_inicio + '_' + value.trnd_hora_fin;
					if (!horas_primary.includes(hora_concate)) {
						horas_primary.push(hora_concate);
					}
				});
				info_filas(res, horas_primary, docidmunber);

			}
			$("#wait").hide();
		});
	} else {
		$('.docente-fila-horario').remove();
		$("#name_doc_horairo").text('Seleccione un docente');
	}

}


function info_filas(info, horas_primary, docidnumber) {
	var order = 0;
	console.log(info);
	$(horas_primary).each(function (key, hora_value) {
		filas_horario_docente(order, docidnumber, hora_value);
		$(info).each(function (key, value) {
			var hora_concate = value.trnd_hora_inicio + '_' + value.trnd_hora_fin;
			if (hora_value == hora_concate) {
				var id_dia = value.trnd_dia + "_" + hora_concate;
				id_dia = id_dia.replace(/:/g, "");
				checks_bd.push(id_dia);
				$("#" + id_dia).prop('checked', true);
				//console.log($("#"+id_dia).val());
			}
		});
		order = order + 1;
	});

}
function getTwentyFourHourTime(amPmString) {
	var d = new Date("1/1/2013 " + amPmString);
	var horas_fun = d.getHours();
	var minutes = d.getMinutes();
	if (horas_fun < 10) { horas_fun = "0" + horas_fun; }
	if (minutes < 10) { minutes = "0" + minutes; }
	return horas_fun + ":" + minutes + ":00";
}

function getTwentyFourHourTimealter(amPmString) {
	var d = new Date("1/1/2013 " + amPmString);
	var horas_fun = d.getHours();
	var minutes = d.getMinutes();
	if (horas_fun < 10) { horas_fun = "0" + horas_fun; }
	if (minutes < 10) { minutes = "0" + minutes; }
	return horas_fun + "" + minutes + "00";
}

function dospuntoshora(cadena) {
	var horas_fun = cadena.substring(0, 2);
	var minutes = cadena.substring(2, 4);
	return horas_fun + ":" + minutes + ":00";
}
function tConvert(time) {
	// Check correct time format and split into components
	time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

	if (time.length > 1) { // If time format correct
		time = time.slice(1);  // Remove full string match value
		time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
		time[0] = +time[0] % 12 || 12; // Adjust hours
	}
	time[3] = " ";
	if (time[0] < 10) {
		time[0] = "0" + time[0];
	}
	//time = time.replace(":00PM", " PM");
	//time = time.replace(":00AM", " AM");
	return time.join(''); // return adjusted time or original string
}
function hideEditColor(turno_id) {
	hideElement("color_id" + turno_id);
	hideElement("cursando_id" + turno_id);
	hideElement("horario_id" + turno_id);
	hideElement("trnid_oficina" + turno_id);
	hideElement("trnid_dia" + turno_id);
	hideElement("btn_hideupdatecolor" + turno_id);
	hideElement("btnUpdatecolor_" + turno_id);
	showElement("btn_habilityupdatecolor" + turno_id);
	showElement("label_color" + turno_id);
	showElement("label_cursando" + turno_id);
	showElement("label_horario" + turno_id);
	showElement("label_trnid_oficina" + turno_id);
	showElement("label_trnid_dia" + turno_id);
	showElement("btn_delete_turno-" + turno_id);
}