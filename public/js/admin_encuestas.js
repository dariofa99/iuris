import { EncuestasService } from './services/encuestas.js';
import { ConciliacionService } from './services/conciliaciones.js';
import { UserService } from './services/users.js'
const conciliacionService = new ConciliacionService();
const encuestasService = new EncuestasService();
const userService = new UserService();
let encId = 0;

$(document).ready(function () {
    
    
    $("#btn_new_categoryInExp").on("click", function (e) {
        e.preventDefault()
        $("#myformEditRCategory").attr("id", "myformCreateCategory");
        $("#myformCreateCategory").attr("id", "myformCreateInEnCategory");
        $("#myformCreateInEnCategory")[0].reset();
        $("#aditional_options_table tbody").html("");
        $("#content_aditional_options").hide();
        $("#myformCreateInEnCategory button[type=submit]")
            .text("Guardar")
            .removeClass("btn-warning")
            .addClass("btn-primary");
        //$(".select2").select2();
        var inputElement = document.getElementById("short_name");
        if (inputElement) {
            var formGroup = inputElement.parentElement;
            formGroup.remove();
            var inputElement = document.getElementById("table");
            // Accede al elemento padre con la clase 'form-group'
            var formGroup = inputElement.parentElement;
            formGroup.remove()
        }
        $("#lbl_modal_title").text("Creando categoria");
        $("#myModal_create_category input[name='short_name']").prop('readonly', true);
        $("#myModal_create_category").modal("show");
    });
    
       
    $("#tblListaEncuestas").on("click", ".btnIconSelEnc", async function (e) {
        e.preventDefault();
        encId = $(this).closest("tr").attr("data-id")
        $(".btnRowSelEnc").removeClass("row_esc_act")
        $(this).closest("tr").addClass("row_esc_act")
        if (encId != null) {
            $("#btn_new_categoryInExp").show()
            $("#btn_load_categoryInExp").show()
        }
        $("#wait").show()
        let response = await encuestasService.getQuestionsById(encId);
        if (response.view || response.view == '') {
            $("#sortable_questions").html(response.view);
            $("#lblTestName").text($(this).closest("tr").attr("data-name"))
        }
        $("#wait").hide()

    });

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

