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
            limpiarForm("myFormResetAccount")
            Swal.fire({
                icon: "success",
                title: "¡Cuenta restablecida con éxito!",
                html: `
                        <div class="text-left">
                            <p class="text-center mb-4">
                                Tu contraseña fue actualizada. Inicia sesión para acceder a tu cuenta.
                            </p>

                            <form id="swalLoginForm">
                                <div class="form-group">
                                    <label for="swalLoginUsername">Usuario</label>
                                    <input id="swalLoginUsername" type="text" class="form-control"
                                        placeholder="Correo electrónico o número de cédula"
                                        autocomplete="username" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="swalLoginPassword">Contraseña</label>
                                    <input id="swalLoginPassword" type="password" class="form-control"
                                        placeholder="Ingresa tu contraseña nueva"
                                        autocomplete="current-password" required>
                                </div>
                            </form>

                            <div class="text-right">
                                <a href="/password/reset" class="small">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>`,
                showCancelButton: false,
                confirmButtonText: "Iniciar sesión",
                width: 480,
                padding: "1.75rem",
                backdrop: `rgba(0,0,0,0.5)`,
                customClass: {
                    popup: "rounded-4 shadow-lg",
                    confirmButton: "btn btn-primary px-4"
                },
                buttonsStyling: false,
                allowOutsideClick: false,
                didOpen: () => {
                    document.getElementById("swalLoginUsername").focus();
                },
                preConfirm: () => {
                    const username = document.getElementById("swalLoginUsername").value.trim();
                    const password = document.getElementById("swalLoginPassword").value;
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

                    if (!username || !password) {
                        Swal.showValidationMessage("Ingresa tu usuario y contraseña para continuar.");
                        return false;
                    }

                    if (!csrfMeta || !csrfMeta.content) {
                        Swal.showValidationMessage("No fue posible iniciar sesión. Recarga la página e inténtalo de nuevo.");
                        return false;
                    }

                    return { username, password, csrfToken: csrfMeta.content };
                }
            }).then((result) => {

                if (!result.isConfirmed || !result.value) {
                    return;
                }
               

                 const loginForm = document.createElement("form");
                loginForm.method = "POST";
                loginForm.action = "/login";

                const formFields = {
                    _token: result.value.csrfToken,
                    user_name: result.value.username,
                    password: result.value.password
                };

                Object.keys(formFields).forEach((name) => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = name;
                    input.value = formFields[name];
                    loginForm.appendChild(input);
                });

                document.body.appendChild(loginForm);
                 console.log(formFields);
                
                loginForm.submit(); 

            });




            /*  setTimeout(function () {
                 window.location.href = "/login";
             }, 2000); */
        } else {
            toastr.error("Error al restablecer la contraseña", "", {
                timeOut: "4000",
            });
        }

    });


});