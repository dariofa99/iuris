import { UserService } from "./services/users.js";
const userService = new UserService();

$(document).ready(function () {
    const codeInputs = document.querySelectorAll('.code-input');
    const fullCodeInput = document.getElementById('fullCode');
    const dataForm = document.getElementById('myFormRecoveryAccount');
    const codeForm = document.getElementById('myFormVerificationCode');
    const btnBackToForm = document.getElementById('btnBackToForm');

    $('select[required]').find('option').each(function () {
        const text = $(this).text().toLowerCase();
        if (text.includes('nit') || text.includes('tarjeta de identidad')) {
            $(this).remove();
        }
    });

    $("#myFormRecoveryAccount").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myFormRecoveryAccount");
        if (errors.length <= 0) {
            var request = convertFormToJSON("myFormRecoveryAccount");
            // $("#wait").show()
            const submitBtn = dataForm.querySelector('button[type="submit"]');
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
            //  submitBtn.textContent = 'Espere...';

            // Simular envío (reemplaza esto con tu AJAX real)
            /*      setTimeout(() => {
                   //  submitBtn.classList.remove('btn-loading');
                   //  submitBtn.disabled = false;
     
     
                 }, 5000); */
            let response = await userService.validateAccount(request);

            if (response.errors) {
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;

                let errores = response.errors;

                for (let campo in errores) {
                    errores[campo].forEach(mensaje => {
                        toastr.error(mensaje, "", {
                            timeOut: "4000",
                        });
                    });
                }
            } else
                if (!response.exists) {
                    toastr.error("No se encontró una cuenta con esos datos", "", {
                        timeOut: "4000",
                    });
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.disabled = false;
                } else {
                    codeForm.querySelector('input[name="idnumber"]').value = response.user.idnumber;
                    codeForm.querySelector('input[name="newemail"]').value = response.email;
                    codeForm.querySelector('input[name="newphone"]').value = response.phone;

                    dataForm.style.display = 'none';
                    codeForm.style.display = 'block';
                    codeInputs[0].focus();
                }

        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                timeOut: "4000",
            });
        }


    });


    // Enviar código
    $("#myFormVerificationCode").on("submit", async function (e) {
        e.preventDefault();
        const code = fullCodeInput.value;
        if (code.length === 6) {
            console.log('Código ingresado:', code);
            let request = convertFormToJSON("myFormVerificationCode");
            let response = await userService.validateCodeAccount(request);
            console.log(response);


            if (response.errors) {
                let errores = response.errors;

                for (let campo in errores) {
                    errores[campo].forEach(mensaje => {
                        toastr.error(mensaje, "", {
                            timeOut: "4000",
                        });
                    });
                }
            } else {

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/usuarios/reset/account';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = response.user.id;
                form.appendChild(idInput);
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();
            }


            // location.href = "/usuarios/reset/account?id=" + response.user.id + "&token=" + response.token;
        } else {
            //alert('Por favor ingresa todos los 6 dígitos');
            toastr.error("Por favor ingresa todos los 6 dígitos", "", {
                timeOut: "4000",
            });
        }
    });


    $("#myFormResetAccount").on("submit", async function (e) {
        e.preventDefault();


        let request = convertFormToJSON("myFormResetAccount");
        let response = await userService.resetPasswordAccount(request);
        if (response.success) {
            toastr.success("Contraseña restablecida con éxito", "", {
                timeOut: "4000",
            });
            setTimeout(function () {
                window.location.href = "/login";
            }, 2000);
        } else {
            toastr.error("Error al restablecer la contraseña", "", {
                timeOut: "4000",
            });
        }

    });


});