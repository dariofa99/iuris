import { UserService } from './services/users.js';
import { ExpedientesService } from './services/expedientes.js';
const userService = new UserService();
const expedientesService = new ExpedientesService();

$(document).ready(function () {

    $("#content_user_exp_asig").on("click", '#registrar_exp_us', async function (e) {
        e.preventDefault();

        const form = document.getElementById("myFormUserCreateExpediente");
        if (!form) return;
        var isvalid = validateForms(form);
        if (isvalid) {
            var request = convertFormToJSON("myFormUserCreateExpediente");
            var data = userService.getAditionalDataByForm("myFormUserCreateExpediente")
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
                resetForm('myFormUserEditExpediente');
                $("#myFormDefOfiStore input[name='expidnumber']").val(response.user.idnumber)
                $("#myModal_exp_user_edit").modal("hide");
            }
            $("#wait").hide();
        } else {
            toastr.error("Hay campos que son obligatorios", "Atención!", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            form.reportValidity();
        }

    
    });

    $("#content_user_exp_asig").on("click", '#actualizar_exp_us', async function (e) {
        e.preventDefault();
        const form = document.getElementById("myFormUserEditExpediente");
        if (!form) return;
        var isvalid = validateForms(form);
        if (isvalid) {
            var request = convertFormToJSON("myFormUserEditExpediente");
            var data = userService.getAditionalDataByForm('myFormUserEditExpediente');
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
                resetForm('myFormUserEditExpediente');
                $("#myFormDefOfiStore input[name='expidnumber']").val(response.user.idnumber)
                $("#myModal_exp_user_edit").modal("hide");
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });

            }
            $("#wait").hide();
        } else {
            toastr.error("Hay campos que son obligatorios", "Atención!", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            form.reportValidity();
        }
        return;
        var errors = validateForm("myFormUserEditExpediente");
        if (errors.length <= 0) {
            var request = convertFormToJSON("myFormUserEditExpediente");
            var data = userService.getAditionalDataByForm('myFormUserEditExpediente');
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
                resetForm('myFormUserEditExpediente');
                $("#myFormExpsStore input[name='expidnumber']").val(response.user.idnumber)
                $("#myModal_exp_user_edit").modal("hide");
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });

            }
            $("#wait").hide();
        }
    });

    $("#content_user_exp_asig").on("blur", "input[name='idnumber']", async function (e) {
        var formulario = $(this).closest('form');
        var formularioId = formulario.attr('id');
        $("#" + formularioId + " input[name='email']").val($(this).val() + "@mail.com")
        if ($(this).val() != '') {
            let request = {
                "tipodoc_id": $("#" + formularioId + " select[name='tipodoc_id']").val(),
                "idnumber": $(this).val(),
                "view": "myforms.components_exp.frm_user_register"
            }
            $("#wait").show();
            let response = await userService.findUserWithFilter(request);
            if (response.encontrado) {
                $("#content_user_exp_asig").html(response.view);
                toastr.success("Usuario encontrado", "", {
                    positionClass: "toast-top-center",
                    timeOut: "4000",
                });
                var msg = `Se encontró al usuario ${response.user.name} 
                ${response.user.lastname} con el rol: ${response.user.roles[0].display_name}.`
                $("#rl_user_solicitud").text(msg)
                if (response.expedientes && response.expedientes.length > 0) {

                    var li = '';
                    response.expedientes.forEach(exp => {
                        li += `
                       <li>
                            ${exp.count} en estado ${exp.nombre_estado}
                        </li>
                       ` ;
                    });
                    $("#lbl_text_casosasig").text("Tiene los siguientes casos en calidad de solicitante")
                    $("#list_casos_asignados").html(li)
                } else {
                    $("#lbl_text_casosasig").text("NO tiene casos")
                    $("#list_casos_asignados").html("")
                }
                $("#content_infoexp").show()
                $("#myFormUserEditExpediente input[name='idnumber']").prop('disabled', true);
            }
            $("#wait").hide()
        }
    });

    $("#myFormDefOfiStore").on("submit", async function (e) {
        e.preventDefault();


        var errors = validateForm('myFormDefOfiStore');
        if (errors.length <= 0) {
            var request = convertFormToJSON('myFormDefOfiStore');
            $("#wait").show();
            var response = await expedientesService.defensasOfiStore(request);
            if (response.errors) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });
                $("#wait").hide();
            } else {
                resetForm('myFormDefOfiStore')
                $("#wait").hide();
                Swal.fire({
                    title: 'El caso se ha creado con éxito!',
                    icon: 'success',
                    text: "¿Qué desea hacer?",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ver el nuevo expediente',
                    cancelButtonText: 'Quedarme en esta página'
                }).then(async (result) => {
                    if (result.value) {
                        $("#wait").show();
                        window.location = '/expedientes/' + response.expid + '/edit';
                    } else {
                        if (result.dismiss === Swal.DismissReason.cancel || result.dismiss === Swal.DismissReason.overlay) {
                            // El usuario hizo clic en el botón cancel o fuera del swal
                            window.location.reload(true)

                        } else {
                            // El usuario hizo clic en el botón confirmar
                            window.location.reload(true)
                        }
                    }
                });
            }

        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });

});