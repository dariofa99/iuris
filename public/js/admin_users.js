import { UserService } from './services/users.js';

const userService = new UserService();

$(document).ready(function () {
  set_tab();
  $(".select2_ramas").selectpicker();
  $(".select2_ramas").selectpicker("refresh");
  let id = $("#myFormUserEdit input[name='idnumber']")
    .prop("disabled", true).removeAttr('name');
  $("#registrar_gen_us").on("click", async function (e) {
    var errors = validateForm("myFormUserCreate");
    if (errors.length <= 0) {
      var request = convertFormToJSON("myFormUserCreate");
      var data = [];
      $(".input_user_ad").each((index, obj) => {
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
        response.errors.forEach(error => {
          toastr.error(error, "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
          });
        });
      } else {
        resetForm('myFormUserCreate');
        Swal.fire({
          position: 'top-center',
          icon: 'success',
          title: 'Cambios registrados exitosamente!',
          showConfirmButton: false,
          timer: 1500
        });
      }
      $("#wait").hide();
    }
    $("#wait").hide();
  });
  $("#content_user_gen_form").on("blur", "input[name='idnumber']", async function (e) {
    var formulario = $(this).closest('form');
    var formularioId = formulario.attr('id');
    $("#" + formularioId + " input[name='email']").val($(this).val() + "@mail.com")
    if ($(this).val() != '') {
      let request = {
        "tipodoc_id": $("#" + formularioId + " select[name='tipodoc_id']").val(),
        "idnumber": $(this).val(),
        "view": "myforms.frm_myusers_gen_form"
      }
      $("#wait").show();
      let response = await userService.findUserWithFilter(request);
      if (response.encontrado) {
        $("#content_user_gen_form").html(response.view);
        toastr.success("Usuario encontrado", "", {
          positionClass: "toast-top-center",
          timeOut: "4000",
        });
      }

    }
    $("#wait").hide()
  });

  $("#content_user_gen_form").on("click", ".add_or_change_sede_usuario", async function (e) {
    var formulario = $(this).closest('form');
    var action = $(this).attr('data-action');
    var formularioId = formulario.attr('id');
    var id = $("#" + formularioId + " input[name='id']").val()
    let request = {
      "id": id,
      "action": action,
      "view": "myforms.frm_myusers_gen_form"
    }
    let response = await userService.addSede(request)
    if (response.agregado) {
      $("#content_user_gen_form").html(response.view);
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Cambios registrados exitosamente!',
        showConfirmButton: false,
        timer: 1500
      });
    }
  });

  $("#btn_actualizar_usuario").on("click", async function (e) {

    var errors = validateForm("myFormUserEdit");

    if (errors.length <= 0) {
      var request = convertFormToJSON("myFormUserEdit");
      var data = [];
      $("#myFormUserEdit .input_user_ad").each((index, obj) => {
        data.push({
          value: $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
          section: $(obj).attr("data-section"),
          type: $(obj).attr("data-type"),
          name: $(obj).attr("data-name"),
          option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
          value_is_other: $("#value_other_text-" + $(obj).attr('data-id')).val(),

        });
      });
      request["data"] = (data);

      $("#wait").show();
      let response = await userService.update(request);
      if (response.errors) {
        response.errors.forEach(error => {
          toastr.error(error, "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
          });
        });
      } else {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Cambios registrados exitosamente!',
          showConfirmButton: false,
          timer: 1500
        });
      }
      $("#wait").hide();
    } else {
      toastr.error("Revisa en los demas formularios que no hayan campos obligatorios sin registrar", "", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
    }
  });
  $("#update_profile_picture").on("click", function (e) {
    $("#file_picture").trigger("click");

  });
  $("#file_picture").on("change", async function (e) {
    const file = e.target.files[0];
    let id = $("#myFormUserEdit input[name='id']").val();
    const body = new FormData();
    body.append('image', file);
    body.append('id', id);
    try {
      $("#loader-container").show().css({ 'display': 'flex' })
      const result = await userService.uploadFile(body)
        .then((response) => {
          $("#img_profile").attr("src", "/thumbnails/" + response.user.image)
          Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: "Actualizado con éxito!",
            showConfirmButton: false,
            timer: 2500
          });
        })
        .catch((error) => {
          Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Ups! Algo fallo',
            html: error,
            showConfirmButton: false,
            timer: 5500
          });
          console.error('Error al cargar el archivo:', error);
        });
    } catch (error) {
      // Manejar el error
      console.error(error);
    } finally {
      // Restablecer el estado de la barra de progreso
      const result = userService.showProgress(0)
      $("#loader-container").hide()
    }
  });

  $(".show_password").on("mousedown",function (e) {
    e.preventDefault();
    $("input[name='password']").attr("type", "text");
  });
  $(".show_password").on("mouseup",function (e) {
    e.preventDefault();
    $("input[name='password']").attr("type", "password");
  });

});//////////////////////////////////////////////


/* const fileInput = document.getElementById('file_picture');
fileInput.addEventListener('change', async (event) => {

}); */