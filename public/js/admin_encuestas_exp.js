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

   

    $("#myEvaNivSatForm").on("submit", async function (e) {
        e.preventDefault();
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
                <div class="alert alert-success" role="alert">
                    ¡El registro se ha completado con éxito!
               
               <br>
               <a href="/login"> Regresar </a>
                    </div>
                
                `
                $("#renderQuestion").html(html);
                var url = window.location.hostname;
                 history.pushState({}, "", "/login")
                $("#wait").hide();
                $("#wait").hide();
                

            }
        });
       
    })

    $("#myFormBuscarConciliacion").on("submit", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON("myFormBuscarConciliacion");
        $("#wait").show();
        var response = await encuestasService.findUser(request);
        if(response.errors && response.errors.length > 0){
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

