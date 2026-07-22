import { UserService } from '../js/services/users.js';
import { ConciliacionService } from '../js/services/conciliaciones.js';
import { SolicitudesService } from './services/solicitudes.js';

const userService = new UserService();
const conciliacionService = new ConciliacionService();
const solicitudesService = new SolicitudesService();
$(function () {
  //  ocultarCompDiscapUser();
  removeRequired("#myFormParteSolicitante", "tel2");
  toggleCampo('socio_economica', 'Ocupación', 'hide');

  $('[data-toggle="tooltip"]').tooltip({
    placement: 'top',
    container: 'body'
  });

  $('#estrato_id option[value="14"],#estrato_id option[value="15"]').remove();
  $('#estadocivil_id option[value="20"],#estadocivil_id option[value="21"]').remove();

  $("#myFormParteSolicitante")
    .on("change", "select[name='pbepersondiscap']", function (e) {
      if ($(this).val() == 1) {
        mostrarCompDiscapUser('myFormParteSolicitante')
      } else {
        ocultarCompDiscapUser('myFormParteSolicitante');
      }
    });



  const fechaInput = document.getElementById('fechanacimien');
  const hoy = new Date();
  hoy.setFullYear(hoy.getFullYear() - 18);
  const maxDate = hoy.toISOString().split('T')[0];
  if (fechaInput) fechaInput.setAttribute('max', maxDate);



  /* $("#myUserRepLegalForm-1")
    .on("change", "select[name='pbepersondiscap']", function (e) {
      if ($(this).val() == 1) {
        $("#myUserRepLegalForm-1 select[name='has_apoyo']").prop("disabled", false);
        mostrarCompDiscapUser()
      } else {
        ocultarCompDiscapUser();
      }
    }); */


  $(".myUserRepLegalForm").on("change", "select[name='pbepersondiscap']", function (e) {
    var formId = $(this).closest('.myUserRepLegalForm').attr('id');
    if ($(this).val() == 1) {
      $("#" + formId + " select[name='has_apoyo']").prop("disabled", false);
      mostrarCompDiscapUser(formId)
    } else {
      ocultarCompDiscapUser(formId);
    }
  })

  $(".myUserRepLegalForm")
    .on("change", "select[name='has_apoyo']", function (e) {
      var formId = $(this).closest('.myUserRepLegalForm').attr('id');
      if ($(this).val() == 1) {
        $(".has_apoyo").show()
        $("#" + formId + " input[name='acept_ter']").prop("disabled", false).addClass("required");
      } else {
        $(".has_apoyo").hide()
        $("#" + formId + " input[name='acept_ter']").prop("disabled", true).prop("checked", false)
      }
    });



  $("#myFormParteSolicitante")
    .on("change", "select[name='has_apoyo']", function (e) {
      if ($(this).val() == 1) {
        $(".has_apoyo").show()
        $("#acept_ter").prop("disabled", false)
      } else {
        $(".has_apoyo").hide()
        $("#acept_ter").prop("disabled", true).prop("checked", false)
      }
    });


  $("#myFormAsunto")
    .on("change", ".data_input_select", function (e) {
      let seccion = $(this).data('section');
      let nombre = $(this).data('name');
      let valor = $(this).val();

      if (seccion === 'asunto' && nombre === 'Cuantia') {

        if (valor === '245') { // Indeterminada
          $('#myModal_InfoInderminada').modal('show');
          console.log("Tolis");

        }

      }
    });

  $(".myFormParteConvocada").on("change", "select[name='pbepersondiscap']", function (e) {
    var formId = $(this).closest('form').attr('id');

    if ($(this).val() == 1) {
      $("#" + formId + " select[name='has_apoyo']").prop("disabled", false);
      mostrarCompDiscapUser(formId)
    } else {
      ocultarCompDiscapUser(formId);
    }
  })

  $(".myFormParteConvocada")
    .on("change", "select[name='has_apoyo']", function (e) {
      var formId = $(this).closest('form').attr('id');
      if ($(this).val() == 1) {
        $(".has_apoyo").show()
        $("#" + formId + " input[name='acept_ter']").prop("disabled", false).addClass("required");
      } else {
        $(".has_apoyo").hide()
        $("#" + formId + " input[name='acept_ter']").prop("disabled", true).prop("checked", false)
      }
    });


  if ($("#tipopersvalidate_id")) $("#myFormParteSolicitante select[name='tipopers_id']").val('237').prop('disabled', true)

  $("#myFormParteSolicitante select[name='tipodoc_id'] option").each(function () {
    var valor = $(this).val();

    if (valor == "236" || valor == "248") {
      $(this).remove(); // Elimina la opción
    }

  });

  $("#myFormApoderado select[name='tipodoc_id'] option").each(function () {
    var valor = $(this).val();

    if (valor == "236" || valor == "248") {
      $(this).remove(); // Elimina la opción
    }

  });

  $("#myFormParteConvocada select[name='tipodoc_id'] option").each(function () {
    var valor = $(this).val();

    if (valor == "248") {
      $(this).remove(); // Elimina la opción
    }

  });

  /* $(".myFormParteConvocada select[name='tipodoc_id'] option").each(function () {
    var valor = $(this).val();

    if (valor == "236" || valor == "248") {
      $(this).remove(); // Elimina la opción
    }

  }); */

  document.addEventListener("invalid", function (e) {
    e.preventDefault();
  }, true);

  $(document).on("click", "#btn_registrar_conc", function () {

    const form = document.getElementById("myFormParteSolicitante");
    if (!form) return;
    var isvalid = validateForms(form);
    if (isvalid) {
      form.requestSubmit();
    } else {
      toastr.error("Hay campos que son obligatorios", "Atención!", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
      form.reportValidity();
    }

  });


  $("#myFormParteSolicitante").on("submit", function (e) {
    e.preventDefault();
    registrarSolicitud();
  });

  async function registrarSolicitud() {

    var request = convertFormToJSON("myFormParteSolicitante");
    if (request.sede_id == '') {
      toastr.error("No hay una sede seleccionada", "Atención!", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
      return false;
    }
    let timerInterval
    Swal.fire({
      title: 'Espere por favor!',
      html: 'Estamos registrando su solicitud',
      timer: 10000,
      timerProgressBar: true,

      allowOutsideClick: false, // Permitir clic fuera del modal
      allowEscapeKey: false, // Permitir escape para cerrar el modal
      backdrop: true, // Mostrar el backdrop (fondo sombreado)

      didOpen: () => {
        Swal.showLoading()
        // const b = Swal.getHtmlContainer().querySelector('b')
        timerInterval = setInterval(() => {
          //b.textContent = Swal.getTimerLeft()
        }, 100)
      },
      willClose: () => {
        clearInterval(timerInterval)
      }
    }).then((result) => {
      /* Read more about handling dismissals below */
      if (result.dismiss === Swal.DismissReason.timer) {
        console.log('I was closed by the timer')
      }
    });
    var data = userService.getAditionalDataByForm('myFormParteSolicitante');
    request["data"] = (data);
    request['active'] = 1;
    request['tipopers_id'] = 237;
    let response = await solicitudesService.solicitar(request);
    if (response.errors) {
      Swal.close();
      Swal.fire({
        icon: "warning",
        title: "¡Cuenta ya registrada!",
        html: `
        <div style="font-size:15px; line-height:1.6">

            <p style="margin-bottom:10px">
                Los datos ingresados ya tienen una cuenta asociada.
            </p>

            <p style="color:#6c757d; font-size:14px">
                Puedes iniciar sesión o recuperar tu contraseña si la olvidaste.
            </p>

        </div>
    `,
        showCancelButton: true,
        confirmButtonText: "🔐 Iniciar sesión",
        cancelButtonText: "🔁 Recuperar contraseña",
        confirmButtonColor: "#0d6efd",
        cancelButtonColor: "#6c757d",
        width: 520,
        padding: "2rem",
        backdrop: `
        rgba(0,0,0,0.5)
    `,
        customClass: {
          popup: "rounded-4 shadow-lg",
          confirmButton: "btn btn-primary px-4 m-1",
          cancelButton: "btn btn-outline-secondary px-4 m1"
        },
        buttonsStyling: false,
        allowOutsideClick: true
      }).then((result) => {

        if (result.isConfirmed) {
          window.location.href = "/login";
        } else if (result.dismiss === Swal.DismissReason.cancel) {
          window.location.href = "/recovery/account";
        }

      });


      response.errors.forEach(error => {
        toastr.error(error, "", {
          positionClass: "toast-top-right",
          timeOut: "4000",
        });
      });
    } else {
      Swal.close();
      Swal.fire({
        title: "La solicitud se ha creado con éxito!",
        html: `<h5>Hemos enviado un correo electrónico con el enlace 
                  para que puedas seguir el proceso en caso de perder la conexión actual.</h5>`,
        type: "success",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Continuar..!",
        allowOutsideClick: false, // Permitir clic fuera del modal
        allowEscapeKey: false, // Permitir escape para cerrar el modal
        backdrop: true // Mostrar el backdrop (fondo sombreado)

      }).then((result) => {
        if (result.value) {
          window.location = "/solicitudes/recepcion/conciliacion/" + response.conciliacion.token + "/?id=" + response.conciliacion.id + "&paso=2";
        }
      });
    }



  }


  $("#myFormRepLegal").on("focus", "input[name='idnumber']", validateTypeDoc);

  $("#myFormApoderado").on("focus", "input[name='idnumber']", validateTypeDoc);

  $("#myFormParteConvocada").on("focus", "input[name='idnumber']", validateTypeDoc);

  $("#contentFormsParteCovocada").on("focus", "input[name='idnumber']", validateTypeDoc);

  $(".myUserRepLegalForm").on("focus", "input[name='idnumber']", validateTypeDoc);

  /*  let oldValueTipoDoc = null;
   $("#content_apoderado_solicitud").on("change", "select[name='tipodoc_id']", async function (e) {
 
     let value = $(this).val()
     if (oldValueTipoDoc != value && oldValueTipoDoc != null) {
       oldValueTipoDoc = value;
        resetForm("myFormApoderado")
     } else {
      
     }
 
 
   }); */

  $("#contentFormsParteCovocada").on("change", "select[name='tipodoc_id']", async function (e) {
    let value = $(this).val()
    var formId = $(this).closest('.myFormParteConvocada').attr('id');
    let idnumber = $("#" + formId + " input[name='idnumber']").val()
    if (idnumber != '') {
      resetForm(formId)
      $(this).val(value)
    }
  });

  $("#myFormRepLegal").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    userService.alertValidateUser(lastidnumber, "myFormRepLegal");
    $(this).val("");
  });

  $(".myUserRepLegalForm").on("blur", "input[name='idnumber']", async function () {
    var formId = $(this).closest('.myUserRepLegalForm').attr('id');
    var lastidnumber = $(this).val();
    userService.alertValidateUser(lastidnumber, formId);
    $(this).val("");
  });

  $("#myFormParteConvocada").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    userService.alertValidateUser(lastidnumber, "myFormParteConvocada");
    $(this).val("");
  });

  $("#myFormApoderado").on("blur", "input[name='idnumber']", async function () {
    var lastidnumber = $(this).val();
    await userService.alertValidateUser(lastidnumber, "myFormApoderado");


    $(this).val("");
  });

  $(".btn_add_replegal").on("click", async function () {
    var key = $(this).attr("data-key");
    var formId = "myUserRepLegalForm-" + key;
    var juridico_id = $("#" + formId).attr('data-juridico');
    var errors = validateForm(formId);
    if (errors.length <= 0) {
      await addUserByStep(formId, this, 7, juridico_id, false)
      await Swal.fire({
        title: "El representante legal ha sido agregado",
        icon: "success",
        confirmButtonText: "Aceptar"
      });
      window.location.reload();
    }
  });
  $("#btn_registrar_apod_sol").on("click", async function (e) {
    e.preventDefault();

    if ($("#chk_not_parte_apoderado").is(":checked")) {
      location.href = $(this).attr("href");
    }

    const form = document.getElementById("myFormApoderado");
    if (!form) return;
    var isvalid = validateForms(form);
    if (isvalid) {
      addUserByStep("myFormApoderado", this, 4)
    } else {
      toastr.error("Hay campos que son obligatorios", "Atención!", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
      form.reportValidity();
    }

    return false;

    var errors = validateForm("myFormApoderado");
    if (errors.length <= 0) {
      addUserByStep("myFormApoderado", this, 4)
    } else {
      toastr.error("Marque la casilla en caso de no contar con un apoderado", "Hay campos que son obligatorios", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
    }
  });
  $("#contentFormsParteCovocada input[name='idnumber']").on("blur", async function (e) {
    var formId = $(this).closest('.myFormParteConvocada').attr('id');
    console.log($(this).val())
    var lastidnumber = $(this).val();
    userService.alertValidateUser(lastidnumber, formId);
    $(this).val("");
  });

  $("#contentFormsParteCovocada").on("click", ".btn_disabled_email", async function (e) {
    var formId = $(this).closest('.myFormParteConvocada').attr('id');
    let idnumber = $("#" + formId + " input[name='idnumber']").val()
    if (idnumber != '') {
      if ($("#" + formId + " input[name='id']").val() == undefined) {
        $("#" + formId + " input[name='email']").val(idnumber + "@mail.com")
          .prop("readonly", true)
      }

    } else {
      toastr.error("", "Primero ingrese un número de documento valido", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
    }

  });
  let validEmail = (email, idnumber) => {
    var re = /\S+@\S+\.\S+/;
    var partsMail = email.split("@");
    if (partsMail[0] == idnumber && partsMail[1] == "mail.com") {
      return false;
    }
    return true;
  }

  let validatePhone = (phone) => {
    var re = /^[0-9]{10}$/;
    return re.test(phone);
  }

  $(".btn_save_parte_convocada").on("click", async function () {
    var formId = $(this).closest('.myFormParteConvocada').attr('id');
    const form = document.getElementById(formId);
    if (!form) return;
    var isvalid = validateForms(form);
    if (isvalid) {
      var request = convertFormToJSON(formId);
      var phone = request.tel1;
      var email = request.email;
      var address = request.address;
      var idnumber = request.idnumber;

      if (!validatePhone(phone) && address == '' && !validEmail(email, idnumber)) {
        toastr.error("", "Debe ingresar al menos un dato de contacto válido para la persona " + (index + 1) + ".<br>Teléfono, correo o dirección.", {
          positionClass: "toast-top-right",
          timeOut: "5000",
        });
        insert = false;
      } else {
        console.log(request);
        var request = convertFormToJSON(formId);
        request['conciliacion_id'] = $("#conciliacion_id").val()
        request['tipo_usuario'] = $(this).attr("data-type");
        request["data"] = userService.getAditionalDataByForm(formId);
        await guardarSolicitud(request);
        /* request['conciliacion_id'] = $("#conciliacion_id").val()
        request['tipo_usuario'] = typeId;
        request["data"] = userService.getAditionalDataByForm(form);
        response_ = await conciliacionService.addUser(request); */


      }
    } else {

    }



  });

  async function guardarSolicitud(request) {
    // 1️⃣ mostrar loading
    Swal.fire({
      title: 'Espere por favor',
      html: 'Estamos registrando su solicitud...',
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => Swal.showLoading()
    });

    try {

      // 2️⃣ backend REAL (aquí sí espera)
      const response_ = await conciliacionService.addDataPersona(request);

      // 3️⃣ cerrar loading
      Swal.close();

      // 4️⃣ success
      await Swal.fire({
        icon: 'success',
        title: '¡Registro exitoso!',
        text: 'La solicitud fue guardada correctamente',
        timer: 1200,
        showConfirmButton: false
      });

      // 5️⃣ recargar o redirigir
      // window.location.reload();
      // o:
      // window.location.href = `/solicitudes/recepcion/conciliacion/${response_.token}/?id=${response_.id}&paso=6`;

    } catch (e) {

      Swal.close();

      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo registrar la solicitud'
      });

      throw e;
    }
  }



  $("#btn_parte_convocada").on("click", async function (e) {

    e.preventDefault();

    window.location = this.href;

    console.log($(this).attr("href"));


    return;

    $("#wait").show();
    if (!$("#chk_not_parte").is(":checked")) {
      let insert = true;
      console.log("si por aqui");

      $(".myFormParteConvocada").each(async function (index, obj) {
        var form = $(obj).attr("id")
        var errors = validateForm(form);
        if (errors.length > 0) {
          insert = false;
          toastr.warning("", "Debe guardar al menos un dato de contacto válido para la persona " + (index + 1) + ".<br>Teléfono, correo o dirección.", {
            positionClass: "toast-top-right",
            timeOut: "5000",
          });
          // return
        } else {
          //validar que el telefono sea valido, el email y la direccion, si hay uno invalido no insertar
          var request = convertFormToJSON(form);
          var phone = request.tel1;
          var email = request.email;
          var address = request.address;
          var idnumber = request.idnumber;

          if (!validatePhone(phone) && address == '' && !validEmail(email, idnumber)) {
            toastr.error("", "Debe ingresar al menos un dato de contacto válido para la persona " + (index + 1) + ".<br>Teléfono, correo o dirección.", {
              positionClass: "toast-top-right",
              timeOut: "5000",
            });
            insert = false;
          }
        }
      });

      if (insert) {
        let timerInterval
        var response_
        Swal.fire({
          title: 'Espere por favor!',
          html: 'Estamos registrando su solicitud',
          timer: 1500,
          timerProgressBar: true,

          allowOutsideClick: false, // Permitir clic fuera del modal
          allowEscapeKey: false, // Permitir escape para cerrar el modal
          backdrop: true, // Mostrar el backdrop (fondo sombreado)

          didOpen: () => {
            Swal.showLoading()
            // const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
              //b.textContent = Swal.getTimerLeft()
            }, 100)
          },
          willClose: () => {
            clearInterval(timerInterval)
          }
        }).then((result) => {
          /* Read more about handling dismissals below */
          if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer')
            window.location = "/solicitudes/recepcion/conciliacion/" + response_.token + "/?id=" + response_.id + "&paso=" + 6;

          }
        });
        let typeId = $(this).attr("data-type")
        $(".myFormParteConvocada").each(async function (index, obj) {
          var form = $(obj).attr("id")
          var errors = validateForm(form);

          if (errors.length <= 0) {
            var request = convertFormToJSON(form);
            request['conciliacion_id'] = $("#conciliacion_id").val()
            request['tipo_usuario'] = typeId;
            request["data"] = userService.getAditionalDataByForm(form);
            response_ = await conciliacionService.addUser(request);
          }
        });

      }

    } else {

    }
    $("#wait").hide();
  });

  $("#btn_registrar_rep_legal").on("click", async function () {
    var key = $(this).attr("data-key");
    var formId = "myUserRepLegalForm-" + key;
    var juridico_id = $("#" + formId).attr('data-juridico');
    var errors = validateForm(formId);
    if (errors.length <= 0) {
      //addUserByStep(formId, this, 7, juridico_id)
    }
  });

  $("#btn_registrar_asunto").on("click", async function () {
    var errors = validateForm("myFormAsunto");

    const form = document.getElementById("myFormAsunto");
    if (!form) return;
    var isvalid = validateForms(form);
    if (isvalid) {
      var request = {};
      $("#wait").show();
      request["conciliacion_id"] = $("#conciliacion_id").val();
      var data = userService.getAditionalDataByForm('myFormAsunto');
      request["data"] = (data);
      let response_ = await conciliacionService.addAditionalData(request);
      window.location = "/solicitudes/recepcion/conciliacion/" + response_.token + "/?id=" + response_.id + "&paso=" + 5;
    } else {
      toastr.error("Hay campos que son obligatorios", "Atención!", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
      form.reportValidity();
    }
    return;
    var request = {};
    if (errors.length <= 0) {
      $("#wait").show();
      request["conciliacion_id"] = $("#conciliacion_id").val();
      var data = userService.getAditionalDataByForm('myFormAsunto');
      request["data"] = (data);
      let response_ = await conciliacionService.addAditionalData(request);
      window.location = "/solicitudes/recepcion/conciliacion/" + response_.token + "/?id=" + response_.id + "&paso=" + 5;
    } else {
      toastr.error("", "Hay campos que son obligatorios", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
    }
  });


  $(".btn_delete_usuario_conciliacion").on("click", function (e) {
    var data_pivot = $(this).attr("data-pivot");
    var request = { 'pivot': data_pivot }
    Swal.fire({
      title: "Esta seguro de eliminar la asignación?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
      cancelButtonText: "No, cancelar",
    }).then((result) => {
      if (result.value) {
        var response = conciliacionService.deleteConciliacionUser(request);
        window.location.reload(true);
      }
    });
  });

  $(".btn_add_conc_he_con").on("click", function (e) {
    e.preventDefault();
    $("#myformEditHechoPretension").attr('id', 'myformCreateHechoPretension');
    $("#myformCreateHechoPretension input[name=id]").val('')
    $("#myformCreateHechoPretension textarea").val('')
    $("#myformCreateHechoPretension input[name=tipo_id]").val($(this).attr('data-tipo'));
    $("#content_create_descrip_hepr").html("");
    $("#btn_add_he_pret_input").show()
    var key = $(".count_input_descrip_hepr_" + $(this).attr('data-tipo')).length + 1;
    var lbl = "";
    if ($(this).attr('data-tipo') == 206) lbl = "Descripción de los hechos";
    if ($(this).attr('data-tipo') == 207) lbl = "Descripción de la pretensión";
    if ($(this).attr('data-tipo') == 208) lbl = "Descripción del acuerdo";

    if ($(this).attr('data-tipo') == 206) $("#btn_add_he_pret_input").text("Agregar otro hecho");
    if ($(this).attr('data-tipo') == 207) $("#btn_add_he_pret_input").text("Agregar otra pretension");
    if ($(this).attr('data-tipo') == 208) $("#btn_add_he_pret_input").text("Agregar otro acuerdo");

    /*  var lbl = $(this).attr('data-tipo') == 206 ? "Descripción de los hechos" : "Descripción de las pretensiones"
     $(this).attr('data-tipo') == 206 ? $("#btn_add_he_pret_input").text("Agregar otro hecho") :
     $("#btn_add_he_pret_input").text("Agregar otra pretension") */

    /*  var row = `
       <div class="form-group content_input_descrip_hepr count_input_descrip_hepr_${$(this).attr('data-tipo')}">
         <label for="description" id="lbl_descrip_hepr">${lbl} ${key}</label>
         <textarea name="descripcion[]" class="form-control required" rows="2"></textarea>
       </div>`; */
    addHeprRow(lbl, key, $(this).attr('data-tipo'));

    //$("#content_create_descrip_hepr").html(row)

    $("#myModalCreateConcHechosPretensiones").modal('show');
    $("#lbl_title_modal").text($(this).attr('data-tipo') == 206 ? "Agregando hechos" : "Agregando pretensiones")
  });


  function addHeprRow(labelBase, key, tipo) {

    const row = `
        <div class="hepr-item content_input_descrip_hepr count_input_descrip_hepr_${tipo}">
            <button type="button" class="btn-remove-hepr">
                <i class="fas fa-times"></i>
            </button>

            <label class="font-weight-bold mb-2">
                ${labelBase} ${key}
            </label>

            <textarea
                name="descripcion[]"
                rows="3"
                class="form-control required"
                placeholder="Escriba aquí..."
            ></textarea>
        </div>
    `;

    $("#content_create_descrip_hepr").append(row);
  }


  /* =========================
     AGREGAR
  ========================= */
  /*   $("#btn_add_he_pret_input").on("click", function () {
  
      const tipo = $("input[name=tipo_id]").val();
  
      let label = "Descripción";
  
      if (tipo == 206) label = "Descripción del hecho";
      if (tipo == 207) label = "Descripción de la pretensión";
      if (tipo == 208) label = "Descripción del acuerdo";
  
      addHeprRow(label);
  
    }); */


  /* =========================
     ELIMINAR
  ========================= */
  $(document).on("click", ".btn-remove-hepr", function () {

    $(this).closest(".hepr-item").remove();

    renumerar();

  });


  /* =========================
     RENOMBRAR
  ========================= */
  function renumerar() {

    heprCount = 0;

    $(".hepr-item").each(function () {
      heprCount++;
      const lbl = $(this).find("label");
      const base = lbl.text().replace(/\d+$/, '').trim();
      lbl.text(base + " " + heprCount);
    });
  }


  $(".btn_create_document").on("click", async function (e) {
    $("#cont_files input[name=category_id]").remove();
    $("#cont_files").append(
      $("<input>", {
        type: 'hidden',
        value: $(this).attr("data-category"),
        name: "category_id",
        id: "anexo_category_id"
      })
    )

    $("#cont_files").append(
      $("<input>", {
        type: 'hidden',
        value: 'anexos_ajax',
        name: "view_template",
        id: "view_template"
      }));

    var request = {
      'conciliacion_id': $("#conciliacion_id").val(),
      'category_id': $(this).attr("data-category")
    }
    var files = await conciliacionService.getFilesByCategory(request);
    files.files.forEach(element => {
      if (element.pivot.concepto == 'Documento de identidad') {
        $("#actions_upload_logs span[id=documento_identidad]").remove();
      }
      if (element.pivot.concepto == 'Cert. de existencia y Rep. legal') {
        $("#actions_upload_logs span[id=registro]").remove();
      }
    });

    $("#myformCreateConciliacionAnexo button[type=submit]").text("Crear");
    $("#myModal_create_document .modal-title").text("Creando anexo");
    $("#myModal_create_document").modal("show");
  });

  $("#myModalCreateConcHechosPretensiones").on("submit", '#myformCreateHechoPretension', async function (e) {
    e.preventDefault()

    var errors = validateForm('myformCreateHechoPretension');

    if (errors.length <= 0) {
      $("#myModalCreateConcHechosPretensiones").modal('hide');
      $("#wait").show();
      var request = convertFormToJSON("myformCreateHechoPretension");
      request['conciliacion_id'] = $("#conciliacion_id").val()
      e.preventDefault();
      const response = await conciliacionService.addHechosPretensiones(request);
      if (response.view || response.view == "") {
        $("#content_hechos_pretensiones-" + response.tipo_id).html(response.view);
      }
      $("#wait").hide();
    }

  });

  $("#myModalCreateConcHechosPretensiones").on("click", '#btn_add_he_pret_input', async function (e) {
    e.preventDefault();
    var tipo = $("#myformCreateHechoPretension input[name='tipo_id']").val();
    console.log(tipo);
    var key = $(".count_input_descrip_hepr_" + tipo).length + 1
    var lbl = tipo == 206 ? "Descripción de los hechos" : "Descripción de las pretensiones";

    /*   var row = `
        <div class="form-group content_input_descrip_hepr count_input_descrip_hepr_${tipo}">
          <label for="description" id="lbl_descrip_hepr">${lbl} ${key}</label>
          <textarea name="descripcion[]" class="form-control required" rows="2"></textarea>
        </div>
     `
      $("#content_create_descrip_hepr").append(row) */
    addHeprRow(lbl, key, tipo);
  });

  $("#myModal_create_document").on("submit", "#myformCreateConciliacionAnexo", async function (e) {
    var request = new FormData($(this)[0]);
    e.preventDefault();
    $("#wait").show();
    request.append("conciliacion_id", $("#conciliacion_id").val());
    const response = await conciliacionService.addFile(request);
    if (response.view || response.view == "") {
      $("#table_anexos_list tbody").html(response.view);
    }
    $("#myModal_create_document").modal("hide")
    $("#wait").hide();
  }
  );

  $(".content_hechos_pretensiones").on("click", '.btn_eliminar_hepr', async function (e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    Swal.fire({
      title: 'Esta seguro que desea eliminar el registro?',
      text: "No se podrá revertir los cambios!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar!',
      cancelButtonText: 'No, mantener abierta!'
    }).then(async (result) => {
      if (result.value) {
        const response = await conciliacionService.deleteFile(id);
        if (response.view || response.view == "") {
          $("#content_hechos_pretensiones-" + response.tipo_id).html(response.view);
        }
        $("#myModalCreateConcHechosPretensiones").modal('hide');

      }
    });
  });


  $(".content_hechos_pretensiones").on("click", '.btn_editar_hepr', async function (e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    $("#wait").show();
    const response = await conciliacionService.editHechoPretension(id);
    $("#myformCreateHechoPretension").attr('id', 'myformEditHechoPretension');
    $("#myformEditHechoPretension input[name=id]").val(response.id)
    $("#myformEditHechoPretension input[name=tipo_id]").val(response.tipo_id)
    $("#btn_add_he_pret_input").hide()
    $("#content_create_descrip_hepr").html("");
    var lbl = "Actualizando";
    var row = `
      <div class="form-group content_input_descrip_hepr count_input_descrip_hepr_${$(this).attr('data-tipo')}">
        <label for="description" id="lbl_descrip_hepr">${lbl}</label>
        <textarea name="descripcion" class="form-control required" rows="2">${response.descripcion}</textarea>
      </div>`;
    $("#content_create_descrip_hepr").html(row);
    $("#wait").hide();
    $("#myModalCreateConcHechosPretensiones").modal('show');
  });

  $("#myModalCreateConcHechosPretensiones").on("submit", '#myformEditHechoPretension', async function (e) {
    e.preventDefault()
    $("#wait").show();
    var request = convertFormToJSON("myformEditHechoPretension");
    request['conciliacion_id'] = $("#conciliacion_id").val();
    var id = $("#myformEditHechoPretension input[name=id]").val();
    $("#wait").show();
    const response = await conciliacionService.updateHechosPretensiones(request, id);
    if (response.view || response.view == "") {
      $("#content_hechos_pretensiones-" + response.tipo_id).html(response.view);
    }
    $("#wait").hide();
    $("#myModalCreateConcHechosPretensiones").modal('hide');
    $("#myModal_create_estado_pretension").modal('hide')


  });


  $("#table_anexos_list").on("click", ".btn_delete_anxcon", function (e) {
    e.preventDefault();
    var request = {
      file_id: $(this).attr("data-file"),
      conciliacion_id: $("#conciliacion_id").val(),
      category_id: 232
    };
    Swal.fire({
      title: "Esta seguro de eliminar el archivo?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
      cancelButtonText: "No, cancelar",
    }).then(async (result) => {
      if (result.value) {
        $("#wait").show();
        const response = await conciliacionService.deleteAnexo(request);
        if (response.view || response.view == "") {
          $("#table_anexos_list tbody").html(response.view);
          Toast.fire({
            title: "Archivo eliminado con éxito.",
            icon: "success",
            timer: 2000,
          });
        }
        $("#wait").hide();

      }
    });
  });

  $("#chk_not_parte").on("change", function (e) {
    $("#content_solicitada").show();
    $("#content_detalles_solicitada").hide();
    $("#myFormParteConvocada textarea").prop('disabled', true);
    if ($(this).is(":checked")) {
      $("#myFormParteConvocada textarea").prop('disabled', false)
      $("#content_solicitada").hide();
      $("#content_detalles_solicitada").show()
    }
  });

  $("#btn_solicitar_conciliacion").on("click", function (e) {
    e.preventDefault();
    var validfile = true;

    var anexos = $("#table_required_anexos tr").length
    var message = "";
    $("#table_required_anexos tr").each((element, obj) => {
      if ($(obj).attr("data-required") == "true") {
        validfile = false;
        message += $(obj).attr("data-label") + "<br>";
      }

    })

    var cantidadHechos = $('#content_hechos_pretensiones-206 textarea').length;
    var cantidadPretensiones = $('#content_hechos_pretensiones-207 textarea').length;
  

    if (!validfile) {
      Swal.fire({
        title: "Recuerda subir los anexos requeridos!",
        html: message,
        icon: "warning",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ok",
      });

    } else if (cantidadHechos <= 0 || cantidadPretensiones <= 0) {
      Swal.fire({
        title: "Recuerda subir los hechos o pretensiones requeridos!",
        icon: "warning",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Ok",
      });

    } else {

      Swal.fire({
        title: "¿Está seguro envíar a revisión la solicitud de conciliación?",
        html: "<h4>No podrá realizar cambios hasta que sea revisada. <br> Debe estar pendiente del correo o número de teléfono suministrado.</h4>",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, envíar!",
        cancelButtonText: "No, cancelar",
      }).then(async (result) => {
        if (result.value) {
          $("#wait").show();
          var request = {
            'concepto': "Solicitud de revisión de conciliación",
            'type_status_id': 175,
            'conciliacion_id': $("#conciliacion_id").val(),
            'send_notification': true
          }
          const response = await conciliacionService.updateEstado(request);
          window.location.reload(true)
          $("#wait").hide();

        }
      });
    }
  });
  $("#chk_not_parte_apoderado").on("change", function (e) {
    if ($(this).is(":checked")) {
      $("#content_apoderado_solicitud input,select").prop("disabled", true);
      //$("#btn_registrar_apod_sol").hide();
      $("#btn_no_apoderado").show()
    } else {
      $("#content_apoderado_solicitud input,select").prop("disabled", false);
      resetForm("myFormApoderado");
      $("#btn_registrar_apod_sol").show();
      $("#btn_no_apoderado").hide()
    }
  });

  $(".btn_change_sede").on("click", function (e) {
    $(".btn_change_sede ").removeClass("btn-danger")
      .addClass('btn-primary').text("Seleccionar");
    $(this).addClass("btn-danger").removeClass('btn-primary').text("Seleccionada");
    $("#myFormParteSolicitante input[name='sede_id']").val($(this).attr('data-id'));
  });

  $(".btn_opaddrpl").on("click", function (e) {
    var key = $(this).attr("data-key");
    $("#user_rep_legal_form-" + key).toggle();
    $(".list_user_rep_legal_form-" + key).toggle();
  });
  ////////////////////////////
  $(".btn_add_document").on("click", function (e) {
    $("#selectedZone").hide();
    $(".radio_doc").hide();
    if ($(this).attr("data-type") == "documento_identidad") {
      $("#radio_doc_identidad").show();
      $("input[name='docType'][value='identidad']").prop("checked", true);
      $("#form_add_document input[name='concept']").val("Documento de identidad");
    }
    if ($(this).attr("data-type") == "certificado_existencia") {
      $("#radio_certificado_existencia").show();
      $("input[name='docType'][value='existencia']").prop("checked", true);
      $("#form_add_document input[name='concept']").val("Certificado de existencia jurídica");
    }
    if ($(this).attr("data-type") == "eva_socieconomica") {
      $("#radio_socieconomica").show();
      $("input[name='docType'][value='socieconomica']").prop("checked", true);
      $("#form_add_document input[name='concept']").val("Soporte evaluación socioeconómica");
    }
    if ($(this).attr("data-type") == "otros_documentos") {
      $("#radio_otros").show();
      $("input[name='docType'][value='otros']").prop("checked", true);
      $("#form_add_document input[name='concept']").val("Otros documentos");
    }
    $("#myModal_form_create_anexo").modal("show");

  });
  /////////////////////

});//fin document ready

async function addUserByStep(form, obj, step, userJuridico = null, redirect = true) {
  //  $("#wait").show();
  //alert(form)
  if ($("#" + form + " input[name='id']").val() != undefined && $("#" + form + " input[name='id']").val() != "") {
    console.log("form")
    console.log(form)
    var request = convertFormToJSON(form);
    request["data"] = userService.getAditionalDataByForm(form);
    request["conciliacion_id"] = $("input[name='conciliacion_id']").val();
    request["tipo_usuario"] = $(obj).attr("data-type")
    request["user_id"] = $("#" + form + " input[name='id']").val();
    /*  var request = {
       "user_id": $("#" + form + " input[name='id']").val(),
       "conciliacion_id": $("input[name='conciliacion_id']").val(),
       "tipo_usuario": $(obj).attr("data-type") 
     }; */
    // request["data"] = userService.getAditionalDataByForm(form);

    if (userJuridico != null) {
      request["user_judirico_id"] = userJuridico;
    }
    console.log(request);

    let response_ = await conciliacionService.addDataPersona(request);
    if (response_) {
      //if (redirect) window.location = "/solicitudes/recepcion/conciliacion/" + response_.token + "/?id=" + response_.id + "&paso=" + step;
    }
  } else {
    var request = convertFormToJSON(form);
    //request["data"] = userService.getAditionalDataByForm(form);
    request["conciliacion_id"] = $("input[name='conciliacion_id']").val();
    request["tipo_usuario"] = $(obj).attr("data-type")
    request["user_id"] = $("#" + form + " input[name='id']").val();


    request["data"] = userService.getAditionalDataByForm(form);
    if (userJuridico != null) {
      request["user_judirico_id"] = userJuridico;
    }
    let response_ = await conciliacionService.addDataPersona(request);
    if (response_.errors) {
      response_.errors.forEach(error => {
        toastr.error(error, "", {
          positionClass: "toast-top-right",
          timeOut: "4000",
        });
      });
    } else {
      console.log(redirect, response_);

      if (redirect) window.location = "/solicitudes/recepcion/conciliacion/" + response_.token + "/?id=" + response_.id + "&paso=" + step;

    }


    return;
    var request = convertFormToJSON(form);
    if (!request.hasOwnProperty("email")) {
      var email = $("#" + form + " input[name=idnumber]").val() + "@mail.com";
      request['email'] = email;
    }
    request["data"] = userService.getAditionalDataByForm(form);
    let response = await userService.registrar(request);
    if (response.errors) {
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
        "tipo_usuario": $(obj).attr("data-type")
      };
      if (userJuridico != null) {
        request["user_judirico_id"] = userJuridico;
      }
      let response_ = await conciliacionService.addUser(request);
      if (redirect) window.location = "/solicitudes/recepcion/conciliacion/" + response_.token + "/?id=" + response_.id + "&paso=" + step;
    }
  }
  $("#wait").hide();

}

document.addEventListener('DOMContentLoaded', async function () {

  var files = await conciliacionService.getFilesByCategory({
    'conciliacion_id': $("#conciliacion_id").val(),
    'category_id': 233
  });
  files.files.forEach(element => {
    if (element.pivot.concepto == 'Documento de identidad') {
      $("#row_doc_identidad").remove();
    }
    if (element.pivot.concepto == 'Certificado de existencia jurídica') {
      $("#row_certificado_existencia").remove();
    }
    if (element.pivot.concepto == 'Soporte evaluación socioeconómica') {
      $("#row_eva_socioeconomica").remove();
    }
    if (element.pivot.concepto == 'Otros documentos') {
      $("#row_otros_documentos").remove();
    }
  });
  console.log(files)

  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const filePreview = document.getElementById('filePreview');
  const fileError = document.getElementById('fileError');
  const removeFileBtn = document.getElementById('removeFile');

  let selectedFile = null;
  const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10 MB
  const ALLOWED_TYPES = ['application/pdf'];

  // Click en zona de carga
  dropZone.addEventListener('click', () => fileInput.click());

  // Cambio de archivo
  fileInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
      handleFileSelect(e.target.files[0]);
    }
  });

  function handleFileSelect(file) {
    hideError();
    selectedFile = null;

    // Validar tipo
    if (!ALLOWED_TYPES.includes(file.type)) {
      showError('❌ Tipo de archivo no permitido. Usa: PDF');
      return;
    }

    // Validar tamaño
    if (file.size > MAX_FILE_SIZE) {
      showError('❌ El archivo es muy grande. Máximo 10 MB.');
      return;
    }

    selectedFile = file;
    showPreview(file);
    $("#dropZone").hide();
    $("#selectedZone").show();
  }

  function showPreview(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent =
      'Tamaño: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB';
    filePreview.classList.remove('d-none');
  }

  function showError(message) {
    document.getElementById('errorMessage').textContent = message;
    fileError.classList.remove('d-none');
    filePreview.classList.add('d-none');
    selectedFile = null;
  }

  function hideError() {
    fileError.classList.add('d-none');
  }

  // Remover archivo
  removeFileBtn.addEventListener('click', () => {
    selectedFile = null;
    fileInput.value = '';
    filePreview.classList.add('d-none');
    hideError();
    $("#dropZone").show();
    $("#selectedZone").hide();
  });

  // Verificar si hay un botón de envío en el footer del modal
  const submitBtn = document.querySelector('.btn-submit-anexo');
  if (submitBtn) {
    submitBtn.addEventListener('click', uploadFile);
  }

  async function uploadFile() {
    if (!selectedFile) {
      showError('❌ Por favor, selecciona un archivo');
      return;
    }

    const docType = document.querySelector('input[name="docType"]:checked');
    if (!docType) {
      showError('❌ Por favor, selecciona el tipo de documento');
      return;
    }

    const formData = new FormData();
    formData.append('conciliacion_file', selectedFile);
    //formData.append('doc_type', docType.value);
    formData.append("concept", $("#form_add_document input[name='concept']").val());
    formData.append("conciliacion_id", $("#conciliacion_id").val());
    // formData.append("category_id", $("#anexo_category_id").val())
    formData.append("view_template", "anexos_ajax")
    formData.append("category_id", 233);

    // Mostrar progreso
    const uploadProgress = document.getElementById('uploadProgress');
    uploadProgress.classList.remove('d-none');
    let data = await conciliacionService.uploadFile(formData, '/conciliaciones/store/anexo');
    if (data.success) {
      // Éxito
      fileInput.value = '';
      filePreview.classList.add('d-none');
      selectedFile = null;

      Toast.fire({
        title: "Archivo agregado con éxito.",
        icon: "success",
        timer: 2000,
      });

      location.reload();

      $("#table_anexos_list tbody").html(data.view);
      //$("#uploadProgress").addClass('d-none');

    } else {
      showError('❌ ' + (data.message || 'Error al cargar el archivo'));
    }
    /*  fetch('/conciliaciones/store/anexo', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
        .then(response => response.json())
        .then(data => {
          uploadProgress.classList.add('d-none');
          if (data.success) {
            // Éxito
            fileInput.value = '';
            filePreview.classList.add('d-none');
            selectedFile = null;
         
            Toast.fire({
              title: "Archivo agregado con éxito.",
              icon: "success",
              timer: 2000,
            });
  
            $("#table_anexos_list tbody").html(data.view);
  
          } else {
            showError('❌ ' + (data.message || 'Error al cargar el archivo'));
          }
        })
        .catch(error => {
          uploadProgress.classList.add('d-none');
          showError('❌ Error: ' + error.message);
        });*/
  }
});