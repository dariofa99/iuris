import { UserService } from './services/users.js';

const userService = new UserService();

$(document).ready(function () {
  set_tab();
  $(".select2_ramas").selectpicker();
  $(".select2_ramas").selectpicker("refresh");
  $("#myFormUserEdit input[name='idnumber']").prop("disabled", true).removeAttr('name');
 
 
  $("#table_list_model").on("click", ".btn_switch_estdoc", async function (e) {
    e.preventDefault();
    var id = $(this).attr("id");
    var email = $("#useremail-"+id).text().trim();
    var estado = $(this).attr('data-estado') == 0 ? 1 : 0;
    let request = {
      'id':id,
      'active':estado,
      'email':email
    }
    $("#wait").show();
    let response = await userService.update(request);
    var path = window.location.href;  
    await index_pagination(path);
    toastr.success("Usuario actualizado con éxito", "", {      
      timeOut: "4000",
    });
    $("#wait").hide()
    //changeStateUser(id);
  });

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
          value_is_other: $("#value_other_text-" + $(obj).attr('data-reference_id')).val(),
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

  $("#criterio").on("change", function () {
    var valor = $(this).val();
    console.log(valor);
    $(".selectpicker").selectpicker("refresh");
    switch (valor) {
      case "name":
        $("#myFormSearchUsers select[name='data_search']").prop("disabled", false).selectpicker('show');
        $("#select_data_users").attr('title', 'Ingrese el nombre de usuario');
        $('#select_data_users').selectpicker('destroy').html('').selectpicker();
        break;
      case "idnumber":
        $("#myFormSearchUsers select[name='data_search']").prop("disabled", false).selectpicker('show');
        $("#select_data_users").attr('title', 'Ingrese el número de documento');
        $('#select_data_users').selectpicker('destroy').html('').selectpicker();
        break;
      case "rol":
        var ref_estados = JSON.parse($("#myFormSearchUsers input[id='rolesapi']").val());
        console.log(ref_estados);
        $(".select_data_users").selectpicker('render');
        var opcion_busq = '';
        for (const key in ref_estados) {
          if (ref_estados.hasOwnProperty(key)) {
            const value = ref_estados[key];
            if (key != 1) {
              opcion_busq += '<option value="' + key + '">' + value + '</option>';
            }

          }
        }

        $("#select_data_users").append(opcion_busq);
        $(".select_data_users").selectpicker("refresh");

        break;
    }
  });

  $('#myFormSearchUsers').on('keyup', 'div.select_data_users input', async function (e) {
    let name = $(this).val();

    var opselected = $("#myFormSearchUsers select[name='criterio']").val();
    if (opselected != '' && (opselected == 'name' || opselected == 'idnumber')) {
      $('div.select_data_users li.no-results').text('Buscando...');
      $(".select_data_users").selectpicker('render');//refresca el select
      if (opselected == 'idnumber') {
        if (isNaN(name)) {
          $('div.select_data_users li.no-results').text('Debe ser númerico');
        } else {
          var opcion_busq = '<option value="' + name + '">' + name + '</option>';
          $("#select_data_users").html(opcion_busq);
        }
      } else {

        var opcion_busq = '<option value="' + name + '">' + name + '</option>';
        $("#select_data_users").html(opcion_busq);

      }
      $(".select_data_users").selectpicker("refresh")
    } else {
      $('div.select_data_users li.no-results').text('Ingrese un tipo de busqueda');
      var roles = $("#myFormSearchUsers select[name='rolesapi']").val();
      console.log(roles);
    }
  });


  $("#myFormChangeEmailAccount").on("submit", async function (e) {
    e.preventDefault()
    var errors = validateForm("myFormChangeEmailAccount");

    if (errors.length <= 0) {
      var request = convertFormToJSON("myFormChangeEmailAccount");
      
      $("#wait").show();
      let response = await userService.updateEmail(request);
      if (response.errors) {
        response.errors.forEach(error => {
          toastr.error(error, "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
          });
        });
      } else {
        Swal.fire({
          title: "La solicitud se ha creado con éxito!",
          html: `<h5>Hemos enviado un correo electrónico con el enlace 
        para activar la cuenta.</h5>`,
          type: "success",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Continuar..!",
          allowOutsideClick: false, // Permitir clic fuera del modal
          allowEscapeKey: false, // Permitir escape para cerrar el modal
          backdrop: true // Mostrar el backdrop (fondo sombreado)

      }).then((result) => {
          if (result.value) {
              // console.log(response);
              window.location = "/login";
          }
      });
      }
     // window.location.reload();
    } else {
      toastr.error("Revisa en los demas formularios que no hayan campos obligatorios sin registrar", "", {
        positionClass: "toast-top-right",
        timeOut: "4000",
      });
    }
    $("#wait").hide();
  });

  $("#btn_actualizar_usuario").on("click", async function (e) {

    var errors = validateForm("myFormUserEdit");

    if (errors.length <= 0) {
      var request = convertFormToJSON("myFormUserEdit");
      var data = userService.getAditionalDataByForm('myFormUserEdit');
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
      window.location.reload();
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

  $(".show_password").on("mousedown", function (e) {
    e.preventDefault();
    $("input[name='password']").attr("type", "text");
  });
  $(".show_password").on("mouseup", function (e) {
    e.preventDefault();
    $("input[name='password']").attr("type", "password");
  });

  $("#myFormRegisterStudent").on("submit", async function (e) {
    e.preventDefault();
    var request = convertFormToJSON("myFormRegisterStudent");
    $("#wait").show();
    var response = await userService.findUserByJson(request);
    if (response) {
      console.log(response);
      var user = response.find((user) => (user.identificacion == request.idnumber && user.codigo_alumno == request.codigo_estudiantil));
      if (user != undefined) {
        let timerInterval
        Swal.fire({
          title: 'Registrando!',
          html: 'Se esta registrando al estudiante<br><b>' + user.nombres + " " + user.apellidos + "</b>",
          timer: 10000,
          timerProgressBar: true,
          allowOutsideClick: false,
          didOpen: async () => {
            Swal.showLoading()
            //const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
              //b.textContent = Swal.getTimerLeft()
            }, 100);

            request['idrol'] = 6;
            //request['active'] = 0;
            request['name'] = user.nombres;
            request['lastname'] = user.apellidos;
            request['password'] = 'udenarcj'
            let response = await userService.registrar(request);
            if (response.errors && response.errors.length > 0) {
              response.errors.forEach(error => {
                toastr.error(error, "", {
                  positionClass: "toast-top-right",
                  timeOut: "4000",
                });
              });
              Swal.close();
            } else if (response.user && response.user.id != undefined) {
              window.location = "/expedientes"
            }
            //console.log(userRe);
            // window.location = "/expedientes"

          },
          willClose: () => {
            clearInterval(timerInterval)
          }
        }).then((result) => {
          /* Read more about handling dismissals below */
          if (result.dismiss === Swal.DismissReason.timer) {
            console.log('I was closed by the timer')
          }
        })
      } else {
        toastr.error("Si creés que esto es un error comunícate con el administrador.", "Ups! Al parecer no estas matriculado.", {
          positionClass: "toast-top-right",
          timeOut: "4000",
        });
      }
    }
    $("#wait").hide();

  })

});//////////////////////////////////////////////


/* const fileInput = document.getElementById('file_picture');
fileInput.addEventListener('change', async (event) => {

}); */