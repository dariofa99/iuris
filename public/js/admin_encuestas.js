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
        var request = convertFormToJSON("myEvaNivSatForm");
        var data = conciliacionService.getAditionalDataByForm('myEvaNivSatForm');
        request["data"] = (data);
        $("#wait").show();
        var response = await encuestasService.updateEncuSatisf(request);
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
        </div>
        `
        $("#renderQuestion").html(html);
        var url = window.location.hostname;
         history.pushState({}, "", "/login")
        $("#wait").hide();
    })

    $("#myFormBuscarConciliacion").on("submit", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON("myFormBuscarConciliacion");
        $("#wait").show();
        var response = await encuestasService.findUser(request);
        if (response.user != null) {
            window.location = "/conciliacion/evaluar/buscar"
        }
        // var response = await encuestasService.buscarConciliaciones(request);
        console.log(response);
    });


    $(".btn_start_test").on("click", async function (e) {
        e.preventDefault();
        const tipo_usuario_id = $(this).attr("data-usertype")
        const conciliacion_id = $(this).attr("data-conciliacion")
        Swal.fire({
            title: 'Inicio encuesta de satisfacción',
            text: "Gracias por realizar la encuesta, recuerde que para el Centro de Conciliación 'Eduardo Alvarado Hurtado' es muy importante su opinión sobre el acceso y la atención brindados. Por ello, a continuación encontrará algunos criterios que nos ayudarán a establecer la evaluación y mejora continua del servicio. Recuerde que su participación es voluntaria y muy valiosa.",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let request = {
                    tipo_usuario_id: tipo_usuario_id,
                    conciliacion_id: conciliacion_id
                }
                let response = await encuestasService.storeEncuSatisf(request);
                window.location = `/conciliacion/evaluar/encuesta/?token=${response.token}&cid=${conciliacion_id}`
                $("#wait").hide();
                console.log(response);

            }
        });
        // var response = await encuestasService.buscarConciliaciones(request);
    })

});

