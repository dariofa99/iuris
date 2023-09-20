import { UserService } from './services/users.js';
import { ExpedientesService } from './services/expedientes.js';
const userService = new UserService();
const expedientesService = new ExpedientesService();

$(document).ready(function () {
 
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