import { PersonasExternasService } from "./services/personas_externas.js";
import { ConciliacionPersonasExternasService } from "./services/conciliacion_personas_externas.js";
const personasExternasService = new PersonasExternasService();
const conciliacionPersonasExternasService = new ConciliacionPersonasExternasService();
var encId;
$(document).ready(function () {

    $("#btnCreateEncuesta").on("click", function (e) {
        e.preventDefault();
        $("#myModal_persona_externa_create").modal("show")
    });

    $("#myFormCreatePersonaExterna").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myFormCreatePersonaExterna");
        if (errors <= 0) {
            $("#wait").show()
            var request = convertFormToJSON('myFormCreatePersonaExterna');
            request["categoria_id"] = 257;
            let response = await personasExternasService.store(request)
            if (response.view || response.view == '') {
                $("#tblListaEncuestas").html(response.view)
            }
            $("#wait").hide()
            $("#myModal_persona_externa_create").modal("hide")
            toastr.success(
                "La encuesta se creó con éxito",
                "",
                { positionClass: "toast-top-right", timeOut: "5000" }
            );
        }
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
        let response = await personasExternasService.getQuestionsById(encId);
        if (response.view || response.view == '') {
            $("#sortable_questions").html(response.view);
            $("#lblTestName").text($(this).closest("tr").attr("data-name") + "-" + encId)
        }
        $("#wait").hide()

    });

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

    $("#myModal_create_category").on("submit", "#myformCreateInEnCategory", async function (e) {
        e.preventDefault()
        //$("#wait").show();
        var request = convertFormToJSON('myformCreateInEnCategory');
        request['table'] = 'personas_externas_preguntas';
        request['encuesta_id'] = encId
        let response = await personasExternasService.storeReferencesData(request);
        if (response.view || response.view == '') {
            $("#sortable_questions").html(response.view);
            $("#lblTestName").text($(this).attr("data-name"))
        }
        toastr.success("La pregunta se creó con éxito", "",
            { positionClass: "toast-top-right", timeOut: "5000" }
        );
        $("#myModal_create_category").modal("hide");
    });

    $("#btn_load_categoryInExp").on("click", async function (e) {
        e.preventDefault();
        var request = {
            "table": "personas_externas_preguntas",
            "categories": "personas_externas_preguntas",
            "encuesta_id": encId
        }
        let response = await conciliacionPersonasExternasService.getByRefDataFilter(request);
        $("#list_preguntas_add_test").html(response.view);
        $("#myModal_encuesta_add_preguntas").modal("show");
    });


     $("#myFormAddPreguntasEncuestas").on("submit", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON('myFormAddPreguntasEncuestas'); 
        request['encuesta_id'] = encId
        $("#wait").show();
        let response = await conciliacionPersonasExternasService.addPreguntasForm(request);
        if (response.view || response.view == '') {
            $("#sortable_questions").html(response.view);
            $("#lblTestName").text($(this).closest("tr").attr("data-name"))
        }
        $("#wait").hide()
        toastr.success("Preguntas agregadas con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
    });

});

