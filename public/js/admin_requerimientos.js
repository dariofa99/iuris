import { UserService } from './services/users.js';
import { ExpedientesService } from './services/expedientes.js';
const userService = new UserService();
const expedientesService = new ExpedientesService();
$(document).ready(function () {
    $(".btn_editar_req").on("click", async function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        let modal = $(this).attr("data-modal");
        $("#wait").show();
        const response = await expedientesService.editRequerimiento(id);
        llenarFormEditReq(response);
        llenarModalDetailsReq(response);
        llenarModalUpdateReq(response);
        $(modal).modal("show");
        $("#wait").hide();
    });
    $(".btn_cambiar_estado_requerimiento").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var reqentregado = ($(this).attr('data-estado') == 0) ? 1 : 0;
        Swal.fire({
            title: 'Esta seguro de cambiar el estado del requerimiento?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, cambiar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let request = {
                    'reqentregado': reqentregado
                }
                let response = await expedientesService.updateRequerimiento(request, id);
                toastr.success("Cambiado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        });
    });
    $("#btn_update_requerimiento").on("click", async function (e) {
        e.preventDefault();
        var errors = validateForm('myformUpdateReq');
        if (errors.length <= 0) {
            var id = $("#req_id").val();;
            var request = convertFormToJSON('myformUpdateReq');
            $("#wait").show()
            await expedientesService.updateRequerimiento(request, id);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            location.reload(true);
        }
    });
    $("#tipo_busqueda").on("change", function(event) {
        event.preventDefault();
        if($(this).val()=='codido_exp') {
            $("#input_data").attr('type', 'text');
        }else{
            $("#input_data").attr('type', 'date');
        }

    });
});
/////////////////////////////////////////////
function hideButtReasCaso() {
    hideElement("btnReasignar");
    hideElement("btnCancReasig");
    hideElement("cont_anotacion");
    showElement("btnOpReasig");
    $(".disabled-fun4").prop("disabled", true);
    $(".disabled-fun4").selectpicker("refresh");
}
function fillModalHistoryDataCase(response) {
    if (response == "") {
        $("#modal-conten-js").html('No hay información registrada');
    } else {
        var inforhis = "";
        $(response).each(function (key, value) {
            var fecha1 = moment($("#expediente_fecha_asig").val()).startOf('day');
            var fecha2 = moment(value.created_at).startOf('day');
            var fecha = fecha1.diff(fecha2, 'days') * -1;
            inforhis += `
            <div class="row">   
                <div class="col-md-7">
                    <label title="C.C. ${value.hisdc_idnumberest_id}">` + value.name + ' ' + value.lastname + ` </label>
                </div> 
                <div class="col-md-5">
                <label> Días después de la asignación: 
                <span class="badge ${fecha > 5 ? 'bg-red' : 'bg-green'} ">  ${fecha} </span>
                </label>
                </div>
                <div class="col-md-1">
                           
                </div>                        
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="cont-text">                                     
                        <textarea class="form-control textarea-asesorias-docente" readonly="" name="asesorias_docente" cols="50" rows="10">`+ value.hisdc_datos_caso + `</textarea>
                    </div>                                        
                    <div class="cont-fecha">
                    <i>	`+ value.created_at + `</i>
                </div>
                </div>
            </div><hr>`;



        });
        $("#modal-conten-js").html(inforhis);
        $("#mymodaljs").modal("show");
    }
}
function validarNotas(errors, form) {
    var notaapl = $("#" + form + " input[name=ntaaplicacion]").val();
    var notacon = $("#" + form + " input[name=ntaconocimiento]").val();
    var notaet = $("#" + form + " input[name=ntaetica]").val();
    if (notaapl > 5 || notacon > 5 || notaet > 5) {
        toastr.error("Por favor verifíque que no haya notas superiores a 5.0", "", {
            positionClass: "toast-top-right",
            timeOut: "6000",
        });
        errors.push("1");
    }
    console.log(notaet, form);
    if (isNaN(notaapl) || isNaN(notacon) || isNaN(notaet)) {
        toastr.error("Por favor verifíque que no haya notas con espacios o caracteres extraños", "", {
            positionClass: "toast-top-right",
            timeOut: "6000",
        });
        errors.push("1");
    }
    return errors;
}
function openCamNotas() {
    $("#myform_update_notas input[type='text']").prop("disabled", false);
    $("#myform_update_notas #nota_concepto").prop("disabled", false);
    $("#btn_cambiar_notas").hide();
    $("#btn_update_notas").show();
    $("#btn_cancelar_notas").show();
}
function lleFormEditNotas(res, origen, tbl_id) {
    $("#myform_update_notas #nota_conocimiento").val(
        res.nota_conocimiento
    );
    $("#myform_update_notas #nota_conocimientoid").val(
        res.nota_conocimientoid
    );

    $("#myform_update_notas #nota_etica").val(res.nota_etica);
    $("#myform_update_notas #nota_eticaid").val(res.nota_eticaid);

    $("#myform_update_notas #nota_aplicacion").val(res.nota_aplicacion);
    $("#myform_update_notas #nota_aplicacionid").val(
        res.nota_aplicacionid
    );

    $("#myform_update_notas #nota_concepto").val(res.nota_concepto);
    $("#myform_update_notas #nota_conceptoid").val(res.nota_conceptoid);
    $("#myform_update_notas #lbl_nota_gen_caso").text(res.nota_final);

    //$("#myform_update_notas input[name='tbl_org_id']").val(res.nota_conceptoid);
    $("#myform_update_notas #origen").val(origen);
    $("#myform_update_notas #tbl_org_id").val(tbl_id);
    $("#myform_update_notas #lbldocevname").text(res.docevname);

    $("#myModal_edit_notas #btns_edit_notas").hide();
    $("#wait").css("display", "none");
    if (res.encontrado) {
        $("#myModal_edit_notas #lbl_periodo").text(res.periodo);
        $("#myModal_edit_notas #lbl_segmento").text(res.segmento);
        $("#myModal_edit_notas #lbl_tipo").text(res.tipo);
        $("#myModal_edit_notas #tipo_nota_id").val(res.tipo_id);
        var tipo = res.tipo_id == "1" ? "Parcial" : "Definitiva";
        $("#btn_tipo_update").text("Cambiar notas a: " + tipo);
        var tipo_id = res.tipo_id == "1" ? "2" : "1";

        if (res.can_edit) {
            if (origen == 1 && $("#expestado_id").val() == "4") {
                $("#btn_tipo_update").attr("data-value", tipo_id);
                $("#btn_tipo_update").show();
                $("#btn_tipo_update").attr(
                    "id",
                    "btn_tipo_nota_update"
                );
            }

            $("#myModal_edit_notas #btns_edit_notas").show();
            $("#btn_cambiar").attr("id", "btn_cambiar_notas");
            $("#btn_delete").attr("id", "btn_delete_notas");
            $("#btn_update").attr("id", "btn_update_notas");
        } else {
            $("#btn_cambiar_notas").attr("id", "btn_cambiar");
            $("#btn_delete_notas").attr("id", "btn_delete");
            $("#btn_update_notas").attr("id", "btn_update");
            //$("#btn_tipo_nota_update").attr('id', 'btn_update_tipo');
        }
        $("#myModal_edit_notas").modal("show");
    }

    if (origen == 3) {
        $("#myModal_edit_notas .fil_nt_co input[type='text']")
            .attr("type", "hidden")
            .prop("disabled", true);
        $("#myModal_edit_notas .fil_nt_co").hide();
        // hideElement('btn_delete_notas');
    } else {
        $("#myModal_edit_notas .fil_nt_co input[type='hidden']")
            .attr("type", "text")
            .prop("disabled", false);
        $("#myModal_edit_notas .fil_nt_co").show();
        showElement("btn_delete_notas");
        //if(origen == 2)   hideElement('btn_delete_notas');
    }
    hideEditNotas();
}
function hideEditNotas() {
    $("#myform_update_notas input[type='text']").prop("disabled", true);
    $("#myform_update_notas #nota_concepto").prop("disabled", true);
    $("#btn_cambiar_notas").show();
    $("#btn_update_notas").hide()
    $("#btn_cancelar_notas").hide()

}
function llenarFormEditReq(res) {
    $("#reqcreated_at").val(res.requerimiento.fecha_corta)
    $("#reqid").val(res.requerimiento.id);
    $("#reqfecha_ed").val(res.requerimiento.reqfecha);
    $("#reqhora_ed").val(res.requerimiento.reqhora);
    $("#reqmotivo").val(res.requerimiento.reqmotivo);
    $("#reqdescrip").val(res.requerimiento.reqdescrip);
}
function llenarModalUpdateReq(res) {
    $("#reqcreated_at").val(res.requerimiento.fecha_corta)
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

function llenarModalDetailsReq(res) {
    $("#cont_notas_req").hide()
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
    $("#btn_cam_nt_req").hide();
    var segmento_id = $("#segmento_id").val();
    $("#btn_cam_nt_req").hide();
    if (res.requerimiento.notas_f.encontrado) {
        $("#lbl_not_etireq").text(res.requerimiento.notas_f.nota_etica);
        $("#ntaconcepto_req").text(res.requerimiento.notas_f.nota_concepto);
        $("#cont_notas_req #lbldocevname").text(res.requerimiento.notas_f.docevname);
        $("#cont_notas_req").show();
        if (segmento_id && res.requerimiento.notas_f.segmento_id && res.requerimiento.notas_f.can_edit) {
            $("#btn_cam_nt_req").show();
        }
    } else {
        if (res.requerimiento.notas != null && res.requerimiento.notas != '') {
            var notas = JSON.parse(res.requerimiento.notas);
            $("#lbl_not_etireq").text(notas.ntaetica);
            $("#ntaconcepto_req").text(notas.ntaconcepto);
            $("#cont_notas_req").show();
        }
    }
}

function llenarModalDetailsAct(res) {
    var name = res.user_created.name + " " + res.user_created.lastname
    $("#fullnameest").val(name)
    $("#actfecha_det").val(res.created_at);
    $("#actnombre_det").val(res.actnombre);
    $("#actdescrip_det").val(res.actdescrip);
    $("#actestado_det").val(res.actestado_id);
    if (res.actestado_id == 176) {//$("#actestado").attr('selected',true);
        $("#actestado_det").prop('disabled', true).val(102);
    }
    $("#fecha_limit_d").val(res.fecha_limit);

    $("#label_nombre_docente").text(res.docente_update.name + ' ' + res.docente_update.lastname);
    var rutadescarga = "/actpdfdownload/" + res.id + "/estudiante";
    if (res.actdocnompropio != '' && res.actdocruta != "" && res.actdocnompropio != null && res.actdocruta != null) {
        $("#lab-nombre-est").html('<a href="' + rutadescarga + '" target="_blank">' + res.actdocnompropio + '</a>');
    } else {
        $("#lab-nombre-est").html('<i>Sin archivo</i>');

    }

    var rutadescarga = "/actpdfdownload/" + res.id + "/docente";
    if (res.actdocnompropio_docente != '' && res.actdocnompropio_docente != null) {
        $("#lab-nombre-doc").html('<a href="' + rutadescarga + '" target="_blank">' + res.actdocnompropio_docente + '</a>');
    } else {
        $("#lab-nombre-doc").html('<i>Sin archivo</i>');

    }

    var segmento_id = $("#segmento_id").val();

    if (res.notas_f.encontrado) {
        $("#lbl_not_conac").text(res.notas_f.nota_conocimiento);
        $("#lbl_not_aplac").text(res.notas_f.nota_aplicacion);
        $("#lbl_not_etiac").text(res.notas_f.nota_etica);
        $("#ntaconcepto_text").val(res.notas_f.nota_concepto);
        $("#cont_notas_ac #lbldocevname").text(res.notas_f.docevname);

        /* showElement('cont_notas_ac'); */
        console.log('ids', segmento_id, res.notas_f.segmento_id, res.notas_f.can_edit)
        if (segmento_id == res.notas_f.segmento_id && res.notas_f.can_edit) {
            /* showElement('btn_cam_nt_act'); */

        }

    } else {
        if (res.notas != null && res.notas != '') {
            var notas = JSON.parse(res.notas);
            $("#lbl_not_conac").text(notas.ntaconocimiento);
            $("#lbl_not_aplac").text(notas.ntaaplicacion);
            $("#lbl_not_etiac").text(notas.ntaetica);
            $("#ntaconcepto_text").val(notas.ntaconcepto);
            /* showElement('cont_notas_ac'); */
        }
    }

    $("#actuacion_id").val(res.id);

    $("#actdocenrecomendac_det").val(res.actdocenrecomendac);

}
function habilityButtReasCaso() {
    showElement("btnReasignar");
    showElement("btnCancReasig");
    showElement("cont_anotacion");
    hideElement("btnOpReasig");

    $(".disabled-fun4").prop("disabled", false);
    $(".disabled-fun4").selectpicker("refresh");
}

async function changeSelectSearchExp(value) {
    var placeholder = "";
    $("#myformExpFilter input").prop("disabled", true).hide();
    $("#myformExpFilter select[name='data']").prop("disabled", true).selectpicker('hide');
    $("#myformExpFilter table").hide();
    $("#select_data_users").selectpicker('refresh');;;

    switch (value) {
        case "idnumber_doc":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el nombre de un docente');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            break;

        case "solicitante":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el nombre de un solicitante');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();

            break;
        case "solicitante_num":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el número de documento de un consultante');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            break
        case "codido_exp":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el número de expediente');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();

            break;
        case "estado":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Seleccione un estado');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            var ref_estados = JSON.parse($("#ref_estados").val());
            $(".select_data_users").selectpicker('render');
            var opcion_busq = '';
            $(ref_estados).each(function (key, value) {
                opcion_busq += '<option value="' + value.id + '">' + value.nombre_estado + '</option>';
            });
            $("#select_data_users").append(opcion_busq);
            $(".select_data_users").selectpicker("refresh");
            break;
        case "tipo_consulta":
            $("#myformExpFilter select[name='data']").prop("disabled", false).show();
            $("#select_data_users").attr('title', 'Seleccione un tipo de consulta');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            var ref_estados = JSON.parse($("#ref_tipoproceso").val());
            $(".select_data_users").selectpicker('render');
            var opcion_busq = '';
            $(ref_estados).each(function (key, value) {
                opcion_busq += '<option value="' + value.id + '">' + value.ref_tipproceso + '</option>';
            });
            $("#select_data_users").append(opcion_busq);
            $(".select_data_users").selectpicker("refresh");
            break;
        case "fecha_creacion":

            $("#myformExpFilter input[id='data_date']").prop("disabled", false).show().val("");

            break;
        case "fecha_cita":
            $("#date_data").attr("placeholder", "yyyy/mm/dd");
            break;
        case "rama_derecho":
            $("#myformExpFilter select[name='data']").prop("disabled", false).show();
            $("#select_data_users").attr('title', 'Seleccione una rama del derecho');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            var ref_estados = JSON.parse($("#ref_ramaderecho").val());
            $(".select_data_users").selectpicker('render');
            var opcion_busq = '';
            $(ref_estados).each(function (key, value) {
                opcion_busq += '<option value="' + value.id + '">' + value.ramadernombre + '</option>';
            });
            $("#select_data_users").append(opcion_busq);
            $(".select_data_users").selectpicker("refresh");

            break;
        case "fecha_rango":
            $("#myformExpFilter table").show();
            $("#myformExpFilter input[name='dataIni']").prop("disabled", false).show().val("");
            $("#myformExpFilter input[name='dataFin']").prop("disabled", false).show().val("");

            break;
        case "all":
            $("#wait").show();
            window.location = '/expedientes'
            // $("#wait").hide();
            break;
        default:
    }
}