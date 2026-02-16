import { EncuestasService } from './services/encuestas.js';
import { ConciliacionService } from './services/conciliaciones.js';
import { UserService } from './services/users.js'
const conciliacionService = new ConciliacionService();
const encuestasService = new EncuestasService();
const userService = new UserService();


$(document).ready(function () {

    $("#myEvaNivSatForm").on("click", ".btn_pagq", async function (e) {
        e.preventDefault();
        /*  //var request = convertFormToJSON("myEvaNivSatForm");
         var data = conciliacionService.getAditionalDataByForm('myEvaNivSatForm');
         data = encuestasService.setQuestion(data)
         var url = $(this).attr("href");
         history.pushState({}, "", url)
         let response = await encuestasService.index_pagination(url)
         $("#renderQuestion").html(response.view)
         console.log(response); */
    });



    $("#btn_llenarForm").on("click", async function (e) {
        e.preventDefault();



      
        const form = document.getElementById("myEvaNivSatForm");
        if (!form) return;
        var isvalid = validateForms(form);
        if (isvalid) {
            Swal.fire({
                title: 'Envío encuesta de satisfacción',
                html: `¿Está seguro de enviar la encuesta de satisfacción?
              
              `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                /* cancelButtonColor: '#d33', */
                confirmButtonText: 'Si, Continuar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.value) {
                    $("#wait").show();
                    var request = convertFormToJSON("myEvaNivSatForm");
                    var data = conciliacionService.getAditionalDataByForm('myEvaNivSatForm');
                    request["data"] = (data);
                    $("#wait").show();
                    console.log(request);

                    var response = await encuestasService.updateEncuSatisfExp(request);
                    Swal.fire({
                        title: 'Registrado con éxito',
                        text: "Gracias por su evaluación...",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Continuar',
                        cancelButtonText: 'Cancelar'
                    });
                 let html = `
<div class="container d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="col-12 col-sm-10 col-md-6 col-lg-5">
        
        <div class="card shadow border-0 text-center">
            <div class="card-body p-4">
                
                <div class="mb-3">
                    <i class="fa fa-check-circle text-success" style="font-size: 60px;"></i>
                </div>

                <h4 class="text-success font-weight-bold">
                    ¡Registro completado!
                </h4>

                <p class="text-muted mb-4">
                    Gracias por completar la encuesta de satisfacción.
                </p>

                <a href="/login" class="btn btn-success btn-block">
                    Ir al inicio de sesión
                </a>

            </div>
        </div>

    </div>
</div>
`;

                
                 $("#renderQuestion").html(html);
                      var url = window.location.hostname;
                      history.pushState({}, "", "/login") 
                    $("#wait").hide();
                    $("#wait").hide();


                }
            });
            $("#wait").hide();
        } else {
            toastr.error("Hay campos que son obligatorios", "Atención!", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            form.reportValidity();
        }





    })

    $("#myFormBuscarConciliacion").on("submit", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON("myFormBuscarConciliacion");
        $("#wait").show();
        var response = await encuestasService.findUser(request);
        if (response.errors && response.errors.length > 0) {
            toastr.error("No se encontró al usuario", "", {
                timeOut: "4000",
            });
            $("#wait").hide();
        }
        if (response.user != null) {
            window.location = "/expedientes/evaluar/buscar"
        }
    });


    $(".btn_start_test").on("click", async function (e) {
        e.preventDefault();

        const exp_id = $(this).attr("data-expediente")
        Swal.fire({
            title: 'Inicio encuesta de satisfacción',
            html: `Gracias por realizar la encuesta, 
            recuerde que para Consultorios Jurídicos y el Centro de Conciliación 
            'Eduardo Alvarado Hurtado' es muy importante 
            su opinión sobre el acceso y la atención brindados.
             Por ello, a continuación encontrará algunos criterios 
             que nos ayudarán a establecer la evaluación y
              mejora continua del servicio. Recuerde que su 
              participación es voluntaria y muy valiosa.<br>
              
              `,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let request = {
                    exp_id: exp_id
                }
                let response = await encuestasService.storeExpEncuSatisf(request);
                window.location = `/expediente/evaluar/encuesta/?token=${response.token}&expid=${exp_id}`
                $("#wait").hide();
                console.log(response);

            }
        });
        // var response = await encuestasService.buscarConciliaciones(request);
    })

});

