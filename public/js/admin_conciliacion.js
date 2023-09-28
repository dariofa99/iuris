import { UserService } from './services/users.js';
import { ConciliacionService } from './services/conciliaciones.js';
import { FormatosService } from './services/formatos_documentos.js';

const userService = new UserService();
const conciliacionService = new ConciliacionService();
const formatosService = new FormatosService();
$(document).ready(function () {
  var users_delete = {};
  var conc_estado_id = 0;
  var partesConciliacionMail = []
  if ($("#conciliacion_id").val() != undefined) {
    set_tab();
    var date = $("#audiencia_fecha").val()
    var color = getColorTurno(date);
    if (color.namecolors != undefined && color.daycolors != undefined) {

      $("#audiencia_label_color_day").css("background-color", color.daycolors)
        .html(color.namecolors);
    }

  }
  $("#myUserSolicitanteForm").on("focus", "input[name='idnumber']", validateTypeDoc);
  $("#myUserRepLegalForm").on("focus", "input[name='idnumber']", validateTypeDoc);
  $("#myUserApoderadoForm").on("focus", "input[name='idnumber']", validateTypeDoc);
  $("#myUserParteSolicitadaForm").on("focus", "input[name='idnumber']", validateTypeDoc);
  $("#myUserRepLegalSolicitadaForm").on("focus", "input[name='idnumber']", validateTypeDoc);
  $("#user_gen_conciliacion_form").on("focus", "#myUserConciliacionesForm input[name='idnumber']", validateTypeDoc);

  /*  $("#user_gen_conciliacion_form").on("blur", "#myUserConciliacionesForm input[name='idnumber']", async function () {
     var lastidnumber = $(this).val();
     alertValidateUser(lastidnumber, "myUserConciliacionesForm");
     $(this).val("");
   }); */

  $("#myUserRepLegalSolicitadaForm").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    alertValidateUser(lastidnumber, "myUserRepLegalSolicitadaForm");
    $(this).val("");
  });
  $("#myUserSolicitanteForm").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    alertValidateUser(lastidnumber, "myUserSolicitanteForm");
    $(this).val("");
  });
  $("#myUserParteSolicitadaForm").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    alertValidateUser(lastidnumber, "myUserParteSolicitadaForm");
    $(this).val("");
  });
  $("#myUserRepLegalForm").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    alertValidateUser(lastidnumber, "myUserRepLegalForm");
    $(this).val("");
  });
  $("#myUserApoderadoForm").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    alertValidateUser(lastidnumber, "myUserApoderadoForm");
    $(this).val("");
  });

  $("#user_gen_conciliacion_form").on("blur", "input[name='idnumber']", async function (e) {
    var formulario = $(this).closest('form');
    var formularioId = formulario.attr('id');
    $("#" + formularioId + " input[name='email']").val($(this).val() + "@mail.com")
    if ($(this).val() != '') {
      let request = {
        "tipodoc_id": $("#" + formularioId + " select[name='tipodoc_id']").val(),
        "idnumber": $(this).val(),
        "view": "myforms.conciliaciones.componentes.user_general_form"
      }
      $("#wait").show();
      let response = await userService.findUserWithFilter(request);
      if (response.encontrado) {
        $("#user_gen_conciliacion_form").html(response.view);
        toastr.success("Usuario encontrado", "", {
          positionClass: "toast-top-center",
          timeOut: "4000",
        });
        $("#myFormUserEditExpediente input[name='idnumber']").prop('disabled', true);
      }
      $("#wait").hide()
    }

  });

  $(".btn_asinar_usuario_conciliacion").on("click", function (e) {
    var data_type = $(this).attr("data-type");
    var form = $(this).attr("data-form");
    if (data_type == 197) {
      $("#content_detalles_solicitada").hide();
      $("#content_solicitada").show();
    }
    $("#fondo_background").addClass("fondo_background")
    $("#ctbotones-" + data_type).show()
    $("#" + form).addClass("form_active");
    $("#" + form + " input").prop("disabled", false);
    $("#" + form + " select").prop("disabled", false);

  });

  $(".btn_asinar_usuario_gen_conciliacion").on("click", function (e) {
    resetForm('myUserConciliacionesForm');
    $("#myModal_conc_user_create").modal("show");
  });

  $(".btn_agregar_usuario_conciliacion").on("click", async function (e) {
    var form = $(this).attr("data-form")
    var user_id = $("#" + form + " input[name='id']").val();
    if (user_id != '') {
      var request = {
        "user_id": user_id,
        "conciliacion_id": $("input[name='conciliacion_id']").val(),
        "tipo_usuario": $(this).attr("data-type")
      };
      $("#wait").show();
      let response_ = await conciliacionService.addUser(request);
      Toast.fire({
        title: "Asignado con éxito.",
        icon: "success",
        timer: 2000,
      });
      window.location.reload(true)
    } else {
      var errors = validateForm(form);
      var request = {};

      if (errors.length <= 0) {

      }
    }
    $("#wait").hide();
  });

  $(".btn_cancel_usuario_conciliacion").on("click", function (e) {
    var data_type = $(this).attr("data-type");
    var form = $(this).attr("data-form");
    notEdit(data_type, form)
  });

  $("#btm_cancel_date_audiencia").on('click', function () {
    $(this).css("display", "none")
    $("#btm_edit_date_audiencia").css("display", "block")
    $(".edit_audiencia").css("display", "none")
    $(".edit_audiencia_existe").css("display", "block");
    $("#audiencia_hora").addClass("input_time").prop("disabled", true)

  })

  $("#myformCreateEstado select[name=type_status_id]").on("change", async function (e) {
    if ($(this).val() != "") {
      var request = {
        tabla_destino: "226",
        status_id: $(this).val(),
        conciliacion_id: $("#conciliacion_id").val()
      };
      const response = await conciliacionService.getPdfReportForStatus(request);
      $("#alertmyReportList").hide();
      if (response.length > 0) {
        var tr = '';
        response.forEach(destino => {
          tr += `
            <tr>
              <td>
              ${destino.reporte.nombre_reporte}
              </td>
            </tr>
            `
        });
        $("#alertmyReportList").show()
        $("#myReportList tbody").html(tr)
      }
    }
  });

  $("#myformCreateEstado").on("submit", async function (e) {
    e.preventDefault();
    var request = new FormData($(this)[0]);
    request.append("conciliacion_id", $("#conciliacion_id").val());
    var type_status_id = $("#myformCreateEstado select[name=type_status_id]").val()
    if (type_status_id == 181) {
      var audiencia = $("#conciliacion_audiencia_id").val()
      if (audiencia == undefined) {
        toastr.error(
          "No se puede admitir la conciliación porque no hay una audiencia habilitada",
          "Error",
          { positionClass: "toast-top-right", timeOut: "50000" }
        );
      } else {

        const result = await conciliacionService.storeConciliacionEstado(request)
          .then((response) => {
            Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: "Actualizado con éxito!",
              showConfirmButton: false,
              timer: 2500
            });
            window.location.reload(true);
            e.preventDefault()
          });
      }
    } else {
      const result = await conciliacionService.storeConciliacionEstado(request)
        .then((response) => {
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: "Actualizado con éxito!",
            showConfirmButton: false,
            timer: 2500
          });
          window.location.reload(true);
          e.preventDefault()
        });
    }
    //
    e.preventDefault();
  }
  );

  $("#btn_cambiar_estado").on("click", function (e) {
    $("#myformEditEstado").attr("id", "myformCreateEstado");
    $("#myformCreateEstado textarea[name=comentario]").val("");
    $("#myformCreateEstado button[type=submit]").text("Confirmar nuevo estado");
    /* $("#myModal_create_estado .modal-title").text("Creando estado");
    $("#myModal_create_estado").modal("show"); */
    $("#content_form_estado_c").show();
    $("#content_list_estado_c").hide();
    $("#btn_cancelar_estado").show();
    $("#btn_cambiar_estado").hide();

  });
  $(".btn_create_document").on("click", function (e) {

    $("#myformEditConciliacionAnexo").attr("id", "myformCreateConciliacionAnexo");
    $("#myformCreateConciliacionAnexo")[0].reset();
    $("#myformCreateConciliacionAnexo input[name=concept]").val($(this).attr("data-concept"));
    $("#myformCreateConciliacionAnexo input[name=category_id]").remove();
    $("#myformEditConciliacionAnexo input[name=conciliacion_file]").prop("required", true);

    $("#myformCreateConciliacionAnexo").append(
      $("<input>", {
        type: 'hidden',
        value: $(this).attr("data-category"),
        name: "category_id"
      })
    )
    $("#myformCreateConciliacionAnexo button[type=submit]").text("Crear");
    $("#myModal_create_document .modal-title").text("Creando anexo");
    $("#myModal_create_document").modal("show");
  });
  $("#btn_cancelar_estado").on("click", function () {
    $("#btn_cancelar_estado").hide();
    $("#btn_cambiar_estado").show();
    $("#content_form_estado_c").hide();
    $("#content_list_estado_c").show();
  });

  $("#categoria_notifica__id").on("change", async function (e) {
    $("#content_notificacion_correo").summernote("code", "");
    if ($(this).val() == 1) {
      $("#content_notificacion_correo").summernote("code", "Escriba su mensaje aquí!");
    } else if ($(this).val() != "") {
      var request = {
        'conciliacion_id': $("#conciliacion_id").val(),
        'tabla_destino': '227',
        'status_id': $("#estado_conciliacion_id").val(),
        // 'categoria':'mensaje_radicado',
        'reporte_id': $(this).val()
      }
      let response = await formatosService.getReportes(request);
      //getReportes(request,'content_form_correo_est_responder');
      if (response.body) {
        $("#content_notificacion_correo").summernote("code", response.body);
      } else if (response.error) {
        toastr.error(response.error, "Algo falló!", {
          positionClass: "toast-bottom-right",
          timeOut: "4000",
        });
      }
    }
  });

  $("#myModal_create_document").on("submit", "#myformCreateConciliacionAnexo", async function (e) {
    e.preventDefault()
    var request = new FormData($(this)[0]);
    request.append("conciliacion_id", $("#conciliacion_id").val());
    $("#wait").show()
    let response = await conciliacionService.addFile(request);
    Swal.fire({
      position: 'top-end',
      icon: 'success',
      title: "Actualizado con éxito!",
      showConfirmButton: false,
      timer: 2500
    });
    window.location.reload(true);
    e.preventDefault();
  }
  );

  $(".fila_usuarios_not").on("click", function (e) {

    if (!$(this).hasClass("fila_usuarios_not_selected")) {
      $(this).removeClass("fila_usuarios_not").addClass("fila_usuarios_not_selected");
      var mail = $(this).attr("data-email")
      var id = $(this).attr("data-id")

      var mail = `
        <div class="rows_mails" id="row-${id}">
        <input type="hidden" value="${mail}" name="correo_send[]" data-row="${id}">                      
          <label id="btn_delete_mail-${id}" type="button" data-id="${id}" data-row="${id}" class="btn_delete_not_mail label label-default">
              ${mail} <span style="cursor:pointer" class="badge">x</span>
          </label>                                 
      </div>`;

      $("#row_mail_not").append(mail);
      var length = $(".rows_mails").length
      $("#btn_env_not").prop("disabled", true)
      if (length >= 0) {
        $("#btn_env_not").prop("disabled", false)
      }
    }

  })
  $("#row_mail_not").on("click", ".btn_delete_not_mail", function (e) {
    var id = $(this).attr("data-id");
    $("#row-" + id).remove();
    $("#user_" + id).removeClass("fila_usuarios_not_selected").addClass("fila_usuarios_not");
    $("#btn_env_not").prop("disabled", true);
    var length = $(".rows_mails").length
    if (length > 0) {
      $("#btn_env_not").prop("disabled", false)
    }
  });
  $("#btn_crear_usuario_conciliacion").on("click", async function (e) {
    e.preventDefault();
    var errors = validateForm("myUserConciliacionesForm");
    if (errors.length <= 0) {
      var user_id = $("#myUserConciliacionesForm input[name='id']").val();
      if (user_id != '') {
        var request = {
          "user_id": user_id,
          "conciliacion_id": $("input[name='conciliacion_id']").val(),
          "tipo_usuario": $("#myUserConciliacionesForm select[name='tipo_usuario_id']").val()
        };
        $("#wait").show();
        let response_ = await conciliacionService.addUser(request);
        toastr.success("Agregado correctamente!", "", {
          positionClass: "toast-bottom-right",
          timeOut: "4000",
        });
        window.location.reload(true);
      } else {

        var request = convertFormToJSON("myUserConciliacionesForm");
        var data = [];
        $("#myUserConciliacionesForm .input_user_ad").each((index, obj) => {
          data.push({
            value: $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
            section: $(obj).attr("data-section"),
            type: $(obj).attr("data-type"),
            name: $(obj).attr("data-name"),
            option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
            value_is_other: $("#value_other_text-" + $(obj).val()).val(),
            conciliacion_id: $("#conciliacion_id").val()
          });
        });
        request["data"] = (data);
        $("#wait").show();
        let response = await userService.registrar(request);
        if (response.errors) {
          $("#wait").hide();
          response.errors.forEach(error => {
            toastr.error(error, "", {
              positionClass: "toast-top-right",
              timeOut: "4000",
            });
          });
        } else {
          var request = {
            "user_id": response.user.id,
            "conciliacion_id": $("input[name='conciliacion_id']").val(),
            "tipo_usuario": $("#myUserConciliacionesForm select[name='tipo_usuario_id']").val()
          };
          $("#wait").show();
          let response_ = await conciliacionService.addUser(request);
          toastr.success("Agregado correctamente!", "", {
            positionClass: "toast-bottom-right",
            timeOut: "4000",
          });
          window.location.reload(true);
        }
      }
    }
  });

  $(".btn_delete_usuario_conciliacion").on("click", function (e) {
    var data_pivot = $(this).attr("data-pivot");
    var request = { 'pivot': data_pivot }
    Swal.fire({
      title: "Esta seguro de eliminar la asignación?",
      html: "<h3>Solo se eliminará al usuario de la conciliación</h3>",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
      cancelButtonText: "No, cancelar",
    }).then(async (result) => {
      if (result.value) {
        $("#wait").show();
        await conciliacionService.deleteConciliacionUser(request);
        toastr.success("Usuario eliminado con éxito", "", {
          positionClass: "toast-top-right",
          timeOut: "4000",
        });
        window.location.reload(true);
      }
    });
  });
  $("#table_list_pdf_users").on("click", '.check_selusvolverfirm', function (e) {
    $("#inusre-" + $(this).attr("data-input_id")).prop('disabled', true)
    if ($(this).is(":checked")) {
      $("#inusre-" + $(this).attr("data-input_id")).prop('disabled', false)
    }
  });
  $("#content_user_pdf_list").on("click", ".btn_gene_pdf", function (e) {
    var status_id = $(this).attr('data-status_id');
    var reporte_id = $(this).attr('data-reporte_id');
    Swal.fire({
      title: 'Esta seguro que desea generar los documentos?',
      text: "No se podrá revertir los cambios!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, generar!',
      cancelButtonText: 'No, cancelar!'
    }).then(async (result) => {
      if (result.value) {
        var request = {
          "reporte_id": reporte_id,
          "conc_estado_id": conc_estado_id,
          "status_id": status_id,
          "conciliacion_id": $("#conciliacion_id").val()
        }
        $("#wait").show();
        await conciliacionService.generarPdfs(request);
        Toast.fire({
          title: 'Generado con éxito.',
          type: 'success',
          timer: 2000,
        });
        $("#content_user_pdf_firmas").hide();
        $("#content_user_pdf_list").show();
        $("#myModal_reportes_pdf_estados").modal("hide")
        $("#wait").hide();
      }
    });
  });
  $(".btn_detalles_us_con").on("click", async function (e) {
    var data_type = $(this).attr("data-type");
    $("#myModal_conc_user_create input[name=tipo_usuario_id]").val(data_type);
    $("#myModal_conc_user_create input[name=section]").val($(this).attr('data-section'));
    var request = {
      'conciliacion_id': $("#conciliacion_id").val(),
      'data_type': data_type,
      'section': $(this).attr('data-section')
    }
    if ($(this).attr('data-user') != undefined) {
      request['idnumber'] = $(this).attr('data-user');
      request['tipodoc_id'] = $("#myModal_conc_user_create select[name=tipodoc_id]").val();
      request['is_edit'] = true;
    } else {
      request['idnumber'] = '0';
      request['tipodoc_id'] = 1;
    }
    $("#wait").show();
    let res = await conciliacionService.getDetallesUser(request, request.idnumber);
    $("#content_detalles_user").html(res.view)
    $("#myModal_conc_user_detalles").modal("show");
    $("#wait").hide();

    // $("#myModal_conc_user_create").modal("show"); 
  });

  $("#table_list_user_asig").on("click", ".btn_editar_usuario_conciliacion", async function (e) {
    let request = {
      "tipodoc_id": $(this).attr('data-doc'),
      "idnumber": $(this).attr('data-user'),
      "view": "user_general_form",
      "conciliacion_id": $("input[name='conciliacion_id']").val()
    }
    $("#wait").show();
    let response = await conciliacionService.editUser($(this).attr('data-user'), request);
    if (response.encontrado) {
      resetForm('myUserConciliacionesForm');
      $("#user_gen_conciliacion_form").html(response.view);
      $("#myUserConciliacionesForm select[name='tipo_usuario_id']").val($(this).attr('data-type'));
      resetDisabledForm("myUserConciliacionesForm")
      $("#myModal_conc_user_create").modal("show");
      $("#wait").hide();
    }
  });


  $("#myFormNotificationSend").on("submit", async function (e) {
    e.preventDefault();
    var errors = validateForm("myFormNotificationSend");
    var formatVal = $("#content_notificacion_correo")
      .summernote("code")
      .trim();
    if (errors.length <= 0 && formatVal != "<p><br></p>" && formatVal != "") {
      $("#myFormNotificationSend input[name=cuerpo_correo]").val(formatVal);
      var request = convertFormToJSON("myFormNotificationSend");
      request['conciliacion_id'] = $("input[name='conciliacion_id']").val();
      $("#wait").show();
      let response = await conciliacionService.sendNotification(request);
      let comentarios = await conciliacionService.getComentarios({ "conciliacion_id": $("input[name='conciliacion_id']").val() });
      $("#table_list_comentarios tbody").html(comentarios.view);
      $("#btn_cancelar_conc_not").hide();
      $("#btn_conciliacion_notificacion").show();
      $("#content_create_notification").hide();
      $("#content_conc_notif").show();
      e.preventDefault();
      $("#wait").hide();
      // window.location.reload(true)

    }
    e.preventDefault();
  });

  $("#btn_conciliacion_notificacion").on("click", function (e) {
    e.preventDefault()
    $(this).hide();
    $("#btn_cancelar_conc_not").show();
    $("#content_create_notification").show();
    $("#myFormNotificationSend select[name=reporte_id]").val('');
    $("#myFormNotificationSend input[name=asunto]").val('');
    $("#myFormNotificationSend div[id=row_mail_not]").html('');
    $("#content_notificacion_correo").summernote("code", "");
    $("#content_conc_notif").hide();
  });
  $("#btn_cancelar_conc_not").on("click", function (e) {
    e.preventDefault()
    $(this).hide();
    $("#btn_conciliacion_notificacion").show();
    $("#content_create_notification").hide();
    $("#content_conc_notif").show();
  });

  $("#table_list_comentarios").on("click", ".btn_edit_com_con", async function (e) {
    var request = {
      comentario_id: $(this).attr("data-id"),
      conciliacion_id: $("#conciliacion_id").val(),
    };
    $("#wait").show();
    let response = await conciliacionService.editConciliacionComentario(request);
    $("#myformCreateComentario").attr("id", "myformEditComentario");
    $("#myformEditComentario input[name=comentario_id]").val(response.id);
    $("#myformEditComentario input[name=compartido]").prop("checked", false);
    if (response.compartido == 1) $("#myformEditComentario input[name=compartido]").prop("checked", true);
    $("#myformEditComentario div[id=comentario]").html(response.comentario);
    $("#myformEditComentario div[id=asunto]").html(response.asunto);
    $("#myModal_create_comentario .modal-title").text("Detalles");
    $("#myModal_create_comentario").modal("show");
    $("#wait").hide();

  });

  $("#table_list_estudiantes_aud").on("click", ".btn_asignar_estudiante_audiencia", async function (e) {
    var idnumber = $(this).attr("data-id");
    var idrol = $(this).attr("data-rol");
    $("#wait").show();
    let response = await conciliacionService.getRolesEstudentAudiencia();
    var li = '<option value="">Seleccione...</option>';
    response.rollist.forEach(element => {
      var stateoption = "";
      if (idrol == element.id) { stateoption = "selected" };
      li += '<option ' + stateoption + '  value="' + element.id + '" ' + true + '>' + element.ref_nombre + '</option>';
    });
    $("#label_rol_est_conciliacion" + idnumber).hide();
    $("#btn_habilityEditRol_Est" + idnumber).hide();
    $("#select_rol_est_conciliacion" + idnumber).show();
    $("#btn_hide_edit_rol_conciliacion_est" + idnumber).show();
    $("#btn_UpdateRol_est" + idnumber).show();
    $("#select_rol_est_conciliacion" + idnumber).html(li);
    $("#wait").hide();

    /*    (response.rollist, function (key, value) {
         stateoption = ""
         if (idrol == value.id) { stateoption = "selected" }
         $("#select_rol_est_conciliacion"+idnumber).append('<option value="' + value.id + '" '+ stateoption +'>' + value.ref_nombre + '</option>');
     }); */
  });

  $("#btn_notificarse_cancelar").on("click", async function () {
    var request = {
      'tabla_destino': "241",
      'status_id': 1,
      'categoria': 'mensaje_notificarse_cancelar'
    }
    let response = await conciliacionService.getDestinyForReport(request);


/*     var request = {
      'conciliacion_id': $("#conciliacion_id").val(),
      'tabla_destino': '241',
      'status_id': $(this).attr("data-estado"),
      'categoria': 'mensaje_notificarse_cancelar'
    }
    let res = await conciliacionService.getPdfReportesConciliacion(request);
    */ $("#wait").hide();
    /*  if(res.body){
         $("#content_form_correo_est_responder"+idform).summernote("code", res.body);
     }else if(res.errors){
         toastr.error(res.error, "Algo falló!", {
             positionClass: "toast-bottom-right",
             timeOut: "4000",
         });
         $("#"+idform).summernote("code", "Escriba su mensaje aquí!");
     } */
    $("#content_form_correo_est_responder").summernote("code", response[0].reporte);

    $("#myFormResponderCorreo input[name=user_estado_id]").val($(this).attr('data-user_estado'))
    $("#myFormResponderCorreo input[name=pivot_id]").val($(this).attr('data-pivot_id'))

    $("#myModal_respuestas_asignaciones").modal("show");
  });

  $("#table_list_estudiantes_aud").on("click", '.btn_hide_edit_rol_conciliacion_est', function (e) {
    var idnumber = $(this).attr("data-id");
    $("#label_rol_est_conciliacion" + idnumber).show();
    $("#btn_habilityEditRol_Est" + idnumber).show();
    $("#select_rol_est_conciliacion" + idnumber).hide();
    $("#btn_hide_edit_rol_conciliacion_est" + idnumber).hide();
    $("#btn_UpdateRol_est" + idnumber).hide();

  });

  $("#table_list_estados").on("click", ".btn_descargar_rep_pdf", async function (e) {
    conc_estado_id = $(this).attr("data-id")
    var request = {
      tabla_destino: "226",
      status_id: $(this).attr("data-estado_id"),
      conciliacion_id: $("#conciliacion_id").val()
    };
    $("#wait").show();
    let response = await conciliacionService.getPdfReportesConciliacion(request);
    $("#myReportPdfList tbody").html("");
    $("#alertmyReportList").hide();
    $("#content_user_pdf_firmas").hide();
    $("#content_personalized_values_pdf").hide();
    $("#content_user_pdf_list").show();
    $("#myReportPdfList tbody").html(response.view);
    $("#alertmyReportList").show();
    $("#myModal_reportes_pdf_estados").modal("show");
    $("#wait").hide();
  });

  var myPopupWindow = window;
  $("#myReportPdfList").on("click", ".btn_edit_con_pdf", function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    var bgdiv = $("<div>").attr({
      className: "bgtransparent",
      id: "bgtransparent",
    });
    // agregamos nuevo div a la pagina       
    $("body").append(bgdiv);
    // obtenemos ancho y alto de la ventana del explorer
    var wscr = $(window).width();
    var hscr = $(window).height();
    //establecemos las dimensiones del fondo
    $("#bgtransparent").css("width", wscr);
    $("#bgtransparent").css("height", hscr);
    myPopupWindow = window.open(url,
      "popup",
      "toolbar=no,width=" + (window.screen.width - 10) + ", height= " + (window.screen.height - 10) +
      ",left=10, top=15,resizable=no,scrollbars=NO");
    myPopupWindow.addEventListener('beforeunload', function (e) {
      $("#bgtransparent").remove();
    });

  });

  $("body").on("click", "#bgtransparent", function (e) {
    e.preventDefault();

    myPopupWindow.resizeTo(window.screen.width, window.screen.height);
    myPopupWindow.moveTo(0, 0);
    var confirmClose = confirm("Tienes una ventana emergente abierta \nDeseas cerrarla? \nNo se guardaran los cambios!");
    if (confirmClose) {
      myPopupWindow.close();
      $("#bgtransparent").remove();
    }
  });

  $("#table_list_estados").on("click", '.btn_compartir_rep_pdf', async function (e) {
    var request = {
      conc_estado_id: $(this).attr("data-id"),
      tabla_destino: "conciliaciones",
      status_id: $(this).attr("data-estado_id"),
      conciliacion_id: $("#conciliacion_id").val()
    };
    $("#wait").show();
    let res = await conciliacionService.getStatusFiles(request);
    partesConciliacionMail = []
    $("#tbl_list_mail_partes").html("")
    $("#tbl_list_archivos_comp").html("")
    $("#content_shmail").hide();
    $(".shared_mail").prop("disabled", true);
    $("#btn_compcon_file").prop("disabled", true)
    $("#myFormCompartirDocumento select[name=means_id]").prop("disabled", true)
    $("#myFormCompartirDocumento select[name=means_id]").val(218);
    $("#myFormCompartirDocumento select[name=category_id]").val(214);
    var mail = "";
    var count = 0;
    res.partes.forEach((user, key) => {
      if (!partesConciliacionMail.includes(user.email)) {
        mail += createRowMail(count,user.email);
        partesConciliacionMail.push(user.email);
        count++;
      }

    });
    $("#tbl_list_mail_partes").html(mail)
    $("#tbl_list_archivos_comp").append(res.view);
    /* $(".rows_mails").each((key, element) => {
      $(element).attr("id", "row-" + key)
      $(element).children().attr("data-row", key)

    }); */
    $("#myFormCompartirDocumento input[name=status_id]").val(res.estado.type_status_id)
    $("#content_compartidos").html(res.view_compartidos)
    $("#myModal_reportes_archivos_compartidos").modal("show")
    $("#wait").hide();

    if (res.view == '') {
      $("#content_msg_info").show();
      $("#myFormCompartirDocumento").hide()
    } else {
      $("#content_msg_info").hide();
      $("#myFormCompartirDocumento").show()
    }
  });

  $("#myReportPdfList").on("click", ".btn_asignar_firmantes", async function (e) {
    var id = $(this).attr("data-estado_id");
    var request = {
      "id": id,
      "conciliacion_id": $("#conciliacion_id").val()
    }
    users_delete = [];
    $("#wait").show();
    let res = await conciliacionService.getFirmantes(request);
    $(".volver_enviar_mail").hide();
    $("#btn_volver_enviar_email").hide();
    $("#btn_enviar_email").show();
    $(".check_selusfirm").prop("disabled", true);
    var revocarFirmas = false;
    $("#btn_revocar_firmas").hide()
      .attr("data-status_id", "0")
      .attr("data-reporte_id", "0");
    if (res.all_firmas == true) {
      $("#btn_generar_pdf").show()
        .attr("data-status_id", res.data.status_id)
        .attr("data-reporte_id", res.data.reporte_id);
    } else {
      $("#btn_generar_pdf").hide()
        .attr("data-status_id", "0")
        .attr("data-reporte_id", "0")
    }
    if (res.data.users.length > 0) {
      $("#btn_select_volver_enviar_email").show();
      res.data.users.forEach(user => {
        if (user.pivot.tipo_firma_id == 209 && user.pivot.firmado == 1) {
          revocarFirmas = true;
          $("#btn_revocar_firmas").show()
            .attr("data-status_id", res.data.status_id)
            .attr("data-reporte_id", res.data.id);
        }
      });
    } else {
      $("#btn_select_volver_enviar_email").hide();
    }
    $("#lbl_pfd_report_name").text(res.data.reporte.nombre_reporte);
    $("#table_list_pdf_users tbody").html(res.view);
    $("#myFormAsigFirmaPdf input[name=estado_id]").val(res.data.id);
    $("#content_user_pdf_firmas").show();
    $("#content_user_pdf_list").hide();
    $("#wait").hide();
  });

  $("#myFormAsigFirmaPdf").on("submit", async function (e) {
    e.preventDefault()
    var request = convertFormToJSON('myFormAsigFirmaPdf');
    request['conciliacion_id'] = $("#conciliacion_id").val();
    if (users_delete.length > 0) request['delete_users_id'] = users_delete;
    $("#wait").show();
    let res = await conciliacionService.setFirmantes(request);
    Toast.fire({
      title: 'Asignado con éxito.',
      icon: 'success',
      timer: 2000,
    });
    $("#content_user_pdf_firmas").hide();
    $("#content_user_pdf_list").show();
    $("#myModal_reportes_pdf_estados").modal("hide");
    //  window.location.reload(true);
    $("#wait").hide();
  });
  $("#btn_select_volver_enviar_email").on("click", function (e) {
    $(".volver_enviar_mail").show();
    $(".check_selusfirm").prop("disabled", true);
    $("#btn_select_volver_enviar_email").hide();
    $("#btn_volver_enviar_email").show();
    $("#btn_enviar_email").hide()
  });
  $("#btn_volver_enviar_email").on("click", async function (e) {
    e.preventDefault()
    var request = convertFormToJSON('myFormAsigFirmaPdf');
    request['conciliacion_id'] = $("#conciliacion_id").val();
    $("#wait").show();
    let res = await conciliacionService.reenviarMails(request);
    Toast.fire({
      title: 'Enviado con éxito.',
      icon: 'success',
      timer: 2000,
    });
    $("#content_user_pdf_firmas").hide();
    $("#content_user_pdf_list").show();
    $("#wait").hide();
  });
  $("#btn_cancelar_asig_user").on("click", function (e) {
    $("#content_user_pdf_firmas").hide();
    $("#content_user_pdf_list").show();
  });
  $("#table_list_pdf_users").on("change", '.select_type_firma', function (e) {
    if ($(this).val() != '209' && $(this).val() != '') {
      $("#check_selusfirm-" + $(this).attr("data-userid")).prop("disabled", true);
      $("#check_selusfirm-" + $(this).attr("data-userid")).prop("checked", false);
      $("#input_selusfirm-" + $(this).attr("data-userid")).val($(this).attr("data-id"));
      $("#input_selusfirm-" + $(this).attr("data-userid")).prop("disabled", false);
      $("#input_selustipofirm-" + $(this).attr("data-userid")).val($(this).val());
      $("#input_selustipofirm-" + $(this).attr("data-userid")).prop("disabled", false);
      $("#input_tipouser-" + $(this).attr("data-userid")).prop("disabled", false);
    } else if ($(this).val() == '') {
      $("#check_selusfirm-" + $(this).attr("data-userid")).prop("disabled", true);
      $("#input_selusfirm-" + $(this).attr("data-userid")).val("");
      $("#input_selusfirm-" + $(this).attr("data-userid")).prop("disabled", true);
      $("#check_selusfirm-" + $(this).attr("data-userid")).prop("checked", false);
      $("#input_selustipofirm-" + $(this).attr("data-userid")).val("");
      $("#input_selustipofirm-" + $(this).attr("data-userid")).prop("disabled", true);
      $("#input_tipouser-" + $(this).attr("data-userid")).prop("disabled", true);
    } else {
      $("#check_selusfirm-" + $(this).attr("data-userid")).prop("disabled", false);
      $("#check_selusfirm-" + $(this).attr("data-userid")).prop("checked", true);
      $("#input_selusfirm-" + $(this).attr("data-userid")).val($(this).attr("data-id"));
      $("#input_selusfirm-" + $(this).attr("data-userid")).prop("disabled", false);
      $("#input_selustipofirm-" + $(this).attr("data-userid")).val($(this).val());
      $("#input_selustipofirm-" + $(this).attr("data-userid")).prop("disabled", false);
      $("#input_tipouser-" + $(this).attr("data-userid")).prop("disabled", false);
    }
    if ($(this).val() == '' && $(this).attr("data-oldnew") == 'old' && users_delete.indexOf($(this).attr("data-userid")) == -1) {
      users_delete.push($(this).attr("data-userid"));
      $("#input_tipouser-" + $(this).attr("data-userid")).prop("disabled", false)
    } else if ($(this).attr("data-oldnew") == 'old' && users_delete.indexOf($(this).attr("data-userid")) != -1) {
      users_delete.splice(users_delete.indexOf($(this).attr("data-userid")), 1);
      $("#input_tipouser-" + $(this).attr("data-userid")).prop("disabled", false)
    }
  });

  $("#btnCancelPdfTemp").on("click", function (e) {
    myPopupWindow.close();
    $("#bgtransparent").remove();
  });
  $("#table_list_estudiantes_aud").on("click", '.btn_update_rol_est', async function (e) {
    var idnumber = $(this).attr("data-id");
    var idrol = $("#select_rol_est_conciliacion" + idnumber).val()
    var idconciliacion = $("#select_rol_est_conciliacion" + idnumber).attr('data-id')
    var textselect = $('select[id="select_rol_est_conciliacion' + idnumber + '"] option:selected').text()
    var email_categoria = '';
    if (idrol == 203) {//conciliador
      email_categoria = 'mensaje_sol_conciliador'
    }

    if (idrol == 204) {//asistente
      email_categoria = 'mensaje_sol_asistente'
    }


    if (idrol == '') {
      Toast.fire({
        title: 'Error en la asignación, contactar al administrador.',
        icon: 'danger',
        timer: 5000,
      });
      return false;
    }
    $("#wait").css("display", "block");
    let res = await conciliacionService.updateUserConciliacion({
      id: idnumber,
      idrol: idrol,
      idconciliacion: idconciliacion,
      categoria: email_categoria,
      tabla_destino: 241
    })
    if (res.state == 1 || res.state == true) {
      Toast.fire({
        title: 'La asignación se ha actualizado con exito.',
        icon: 'success',
        timer: 5000,
      });
      if (res.action == 'delete') {
        $("#label_rol_est_conciliacion" + idnumber).html('sin asignar')
          .css({ "font-weight": "100", "font-size": "13px" });
        $("#btn_habilityEditRol_Est" + idnumber).attr("data-state", "")
        $("#label_num_conciliacion_est" + idnumber).html($("#label_num_conciliacion_est" + idnumber).html() - 1)
          .css("font-weight", "100");
      } else {
        $("#label_rol_est_conciliacion" + idnumber).html(textselect)
          .removeAttr("style");
        $("#btn_habilityEditRol_Est" + idnumber).attr("data-state", idrol)
        if (res.action == 'insert') {
          $("#label_num_conciliacion_est" + idnumber).html(parseInt($("#label_num_conciliacion_est" + idnumber).html()) + 1)
            .removeAttr("style");
        }

      }
    } else {
      Toast.fire({
        title: 'Error en la asignación, contactar al administrador.',
        icon: 'danger',
        timer: 5000,
      });
    }
    //location.reload();
    $("#wait").css("display", "none");

  });

  $("#myFormAsigFirmaPdf").on("click", ".btn_revocar_firmas", function (e) {
    var status_id = $(this).attr('data-status_id');
    var reporte_id = $(this).attr('data-reporte_id');
    Swal.fire({
      title: "Esta seguro que desea solicitar revocación de firmas?",
      text: "Esta acción solicitará mediante correo electrónico a las partes con firma electrónica la anulación de la firma.\nDeberá esperar la aceptación.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, solicitar revocación!',
      cancelButtonText: 'No, cancelar!'
    }).then(async (result) => {
      if (result.value) {
        var request = {
          "reporte_id": reporte_id,
          "conc_estado_id": conc_estado_id,
          "status_id": status_id,
          "conciliacion_id": $("#conciliacion_id").val()
        }
        let res = await conciliacionService.revocarFirmas(request);
        Toast.fire({
          title: 'Se ha enviado la solicitud.',
          icon: 'success',
          timer: 2000,
        });
        $("#wait").hide();
      }
    });
  })

  $("#table_list_comentarios").on(
    "click",
    ".btn_delete_com_con",
    function (e) {
      var request = {
        comentario_id: $(this).attr("data-id"),
        conciliacion_id: $("#conciliacion_id").val(),
      };
      Swal.fire({
        title: "Esta seguro de eliminar el comentario?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar",
      }).then(async (result) => {
        if (result.value) {
          $("#wait").show();
          let response = await conciliacionService.deleteConciliacionComentario(request);
          if (response.view || response.view == "") {
            $("#table_list_comentarios tbody").html(response.view);
          }
          $("#wait").hide();

        }
      });
    }
  );

  $("#btm_edit_date_audiencia").on('click', function () {
    $(this).css("display", "none")
    $(".edit_audiencia").css("display", "block")
    $(".edit_audiencia_existe").css("display", "none");
    $("#audiencia_hora").removeClass("input_time").prop("disabled", false);
  });

  $("#audiencia_fecha").change(function () {
    var color = getColorTurno(this.value);
    $("#audiencia_label_color_day").css("background-color", color.daycolors)
      .html(color.namecolors);


  });

  $("#myFormCompartirDocumento select[name=category_id]").on("change", function (e) {
    e.preventDefault();
    $("#content_files").show()
    $("#content_datashared").hide()
    if ($(this).val() != 214) {
      $("#myFormCompartirDocumento select[name=means_id]").prop("disabled", false)
      var means_id = $("#myFormCompartirDocumento select[name=means_id]").val()
      if (means_id == 218) {
        $("#content_shmail").show();
        $(".shared_mail").prop("disabled", false);
        $("#tbl_list_mail_partes").html("")
      } else {
        $("#content_shmail").hide();
        $(".shared_mail").prop("disabled", true);
      }

    } else {
      $("#tbl_list_mail_partes").html("")
      $("#myFormCompartirDocumento select[name=means_id]").prop("disabled", true)
      $("#myFormCompartirDocumento select[name=means_id]").val(218);
      $("#content_shmail").hide();
      $(".shared_mail").prop("disabled", true);
      var mail = '';
      partesConciliacionMail.forEach((element,key) => {
        mail += createRowMail(key,element);
      });
      $("#tbl_list_mail_partes").html(mail)
      /* $(".rows_mails").each((key, element) => {
        $(element).attr("id", "row-" + key)
        $(element).children().find('span').attr("data-row", key)       
      }); */
    }

  });

  $("#myFormCompartirDocumento select[name=means_id]").on("change", function (e) {
    e.preventDefault();
    $("#content_files").show()
    $("#content_datashared").hide()
    $("#tbl_list_mail_partes").html("")
    if ($(this).val() == 218) {
      $("#content_shmail").show();
      $(".shared_mail").prop("disabled", false);
    } else {
      $("#content_shmail").hide();
      $(".shared_mail").prop("disabled", true);
    }

  });

  $("#tbl_list_mail_partes").on("click", '.btn_delete_mail', function (e) {
    $("#row-" + $(this).attr("data-row")).remove()
    $(".rows_mails").each((key, element) => {
      $(element).attr("id", "row-" + key)
      $(element).children().attr("data-row", key);
    });
  });

  $("#btm_save_date_audiencia").on('click', async function () {
    var id = $(this).attr("data-id")
    var fecha = $("#audiencia_fecha").val()
    var hora = $("#audiencia_hora").val()
    if (fecha != "" & hora != "" & id != "") {
      var request = {
        id: id, fecha: fecha, hora: hora
      }
      $("#wait").show();
      await conciliacionService.storeAudiencia(request);
      toastr.success("Audiencia guardada con éxito", "", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
      window.location.reload(true)

    } else {
      toastr.error("Hay campos requeridos", "", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
    }

  });

  getActas();
  getReportesForDestiny()
});//fin document ready

function getColorTurno(value) {
  let namecolors = ['Amarrillo', 'Azul', 'Verde', 'Gris', 'Rojo'];
  let daycolors = ["#fdd835", "#0073b7", "#00a65a", "#a0afb3", "#f56954"];
  var fecha1 = moment($("#prdfecha_inicio").val());//moment("2022-10-18");
  var day_fecha_ini = fecha1.day() - 1;// dia de la smeana de la fecha inicial lunes 0
  var fecha1 = fecha1.subtract(day_fecha_ini, "days");//inicia la semana siempre en lunes
  var fecha2 = moment(value);
  var semday = fecha2.day(); // dia de la semana, lunes inicia en 1
  var day = semday - 1;//lunes inicia en 0
  var semanas = fecha2.diff(fecha1, 'weeks');
  var y = 0;
  for (var i = 0; i < semanas; i++) {
    y++
    if (y == 5) { y = 0 }
  }
  var daysemcolor = day + y;
  if (daysemcolor > 4) { daysemcolor = daysemcolor - 5; }
  return {
    "namecolors": namecolors[daysemcolor],
    "daycolors": daycolors[daysemcolor]
  }
}
async function getReportesForDestiny() {
  var request = {
    'tabla_destino': "227",
    'status_id': $("#estado_conciliacion_id").val(),
  }
  let response = await conciliacionService.getDestinyForReport(request);
  if (response.errors && response.errors.length > 0) {
    console.log(response);
  } else {
    var option = '<option value="">Seleccione...</option>';
    response.forEach(element => {
      option += `
              <option value="${element.id}">${element.nombre_reporte}</option>
           `;
    });
    option += `
         <option value="1">En blanco</option>
      `;
    $("#categoria_notifica__id").html(option)
  }


}
async function getActas() {
  var request = {
    //conc_estado_id: $(this).attr("data-id"),
    tabla_destino: "conciliaciones",
    status_id: $("#estado_conciliacion_id").val(),
    conciliacion_id: $("#conciliacion_id").val()
  };
  let response = await conciliacionService.getPdfReportesConciliacion(request);
  $("#myFormatosActasList tbody").html("");
  $("#myFormatosActasList tbody").html(response.view);

}
function alertValidateUser(lastidnumber, form) {
  var view = $("#" + form).attr("data-view");
  var content = $("#" + form).attr("data-content");
  if (lastidnumber != '' && $("#" + form + " select[name='tipodoc_id']").val() != '') {


    Swal.fire({
      title: 'Vuelve a ingresar el número de documento',
      input: 'text',
      inputAttributes: {
        autocapitalize: 'off',
        className: 'form-control',

      },
      showCancelButton: true,
      confirmButtonText: 'Validar',
      cancelButtonText: 'Cancelar',
      showLoaderOnConfirm: true,
      preConfirm: async (idnumber) => {
        if (lastidnumber == idnumber) {
          let request = {
            "tipodoc_id": $("#" + form + " select[name='tipodoc_id']").val(),
            "idnumber": idnumber,
            "view": view,
            "conciliacion_id": $("input[name='conciliacion_id']").val()
          }
          $("#wait").show();
          let response = await conciliacionService.editUser(idnumber, request);
          if (response.encontrado) {
            $("#" + content).html(response.view);

          } else {
            $("#" + form + " input[name='idnumber']").val(lastidnumber);
          }
        } else {
          toastr.info("Los valores no coinciden", "!Atención", {
            positionClass: "toast-top-right",
            timeOut: "4000"
          });
          $("#" + form + " input[name='id']").remove();
          $("#" + form + " input[name='name']").val("");
          $("#" + form + " input[name='lastname']").val("");
          $("#" + form + " input[name='tel1']").val("");
          $("#" + form + " input[name='address']").val("");
        }

        $("#wait").hide();

      },
      allowOutsideClick: () => !Swal.isLoading()
    });
  }
}

function notEdit(data_type, form) {
  if (data_type == 197) {
    $("#content_detalles_solicitada").show();
    $("#content_solicitada").hide();
  }
  $("#ctbotones-" + data_type).hide()
  $("#fondo_background").removeClass("fondo_background")
  $("#" + form).removeClass("form_active");
  $("#" + form + " input").prop("disabled", true);
  $("#" + form + " select").prop("disabled", true);
  $("#" + form + " input").val("");
  $("#" + form + " select").val("");


}
function createRowMail(key,usermail) {

  var tr = `<div class="rows_mails" id="row-${key}">
    <input type="hidden" value="${usermail}" name="shared_mail[]">                      
      <label  style="cursor: default;" data-row="${key}" class="btn btn-warning btn-sm label p-2 m-1">
          ${usermail} <span data-row="${key}" style="cursor: pointer;" class="badge badge-light btn_delete_mail">X</span>
      </label>                                 
   </div>`;
  return tr;
}