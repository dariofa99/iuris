import { EncuestasService } from './services/encuestas.js';
const encuestasService = new EncuestasService();
import { ReferenciasService } from "./services/referencias.js";
const referenciasService = new ReferenciasService();
let encId = 0;
$(document).ready(async function () {
    set_tab()
    await getChart();
    
    $("#btn_new_category").on("click", function (e) {
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

        // Accede al elemento padre con la clase 'form-group'
        var formGroup = inputElement.parentElement;
        formGroup.remove()

        var inputElement = document.getElementById("table");

        // Accede al elemento padre con la clase 'form-group'
        var formGroup = inputElement.parentElement;
        formGroup.remove()
        $("#lbl_modal_title").text("Creando categoria");
        $("#myModal_create_category input[name='short_name']").prop('readonly', true);
        $("#myModal_create_category").modal("show");
    });

  $("#btn_load_categoryInExp").on("click", async function (e) {
        e.preventDefault();
        var request = {
            "table": "conc_encuesta_satisf",
            "categories": "conc_encuesta_satisf",
            "encuesta_id": encId
        }
        let response = await referenciasService.getByRefDataFilter(request);
        $("#list_preguntas_add_test").html(response.view);
        $("#myModal_encuesta_add_preguntas").modal("show");
    })

      $("#myFormAddPreguntasEncuestas").on("submit", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON('myFormAddPreguntasEncuestas');
        request['encuesta_id'] = encId
        let response = await encuestasService.addPreguntasEncuesta(request);


    })

         $("#myModal_create_category").on("submit", "#myformCreateInEnCategory", async function (e) {
        e.preventDefault()
        //$("#wait").show();
        var request = convertFormToJSON('myformCreateInEnCategory');
        request['table'] = 'conc_encuesta_satisf'
        request['encuesta_id'] = encId
        let response = await encuestasService.storeReferencesData(request);
        if (response.view || response.view == '') {
            $("#sortable_questions").html(response.view);
            $("#lblTestName").text($(this).attr("data-name"))
        }
        toastr.success("La pregunta se creó con éxito", "",
            { positionClass: "toast-top-right", timeOut: "5000" }
        );
        $("#myModal_create_category").modal("hide");
    });

    $("#myModal_create_category").on("submit", "#myformCreateCategory", async function (e) {
        e.preventDefault()
        $("#wait").show();
        var request = convertFormToJSON('myformCreateCategory');
        //let response = await referenciasService.storeReferencesData(request)
        Toast.fire({
            title: "Categoría creada con éxito.",
            icon: "success",
            timer: 2000,
        });
        $("#myModal_create_category").modal("hide");
        // $("#content_categories_list").html(response.render_view);
        $("#wait").hide();
    });

    
    $("#btnCreateEncuesta").on("click", function (e) {
        e.preventDefault();
        $("#myModal_encuesta_create").modal("show")
    });
    
    $("#myFormCreateEncuestaExp").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myFormCreateEncuestaExp");

        if (errors <= 0) {
            $("#wait").show()
            var request = convertFormToJSON('myFormCreateEncuestaExp');
            request["categoria_id"] = 257;
            let response = await encuestasService.store(request)
            if (response.view || response.view == '') {
                $("#tblListaEncuestas").html(response.view)
            }
            $("#wait").hide()
            $("#myModal_encuesta_create").modal("hide")
            toastr.success(
                "La encuesta se creó con éxito",
                "",
                { positionClass: "toast-top-right", timeOut: "5000" }
            );
        }
    })

    $("#list_encuind").on('click', '.pagination a', async function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        $("#wait").show()
        let response = await index_page(url);
        $(".list_encuind").html(response.view);
        $("#wait").hide()
        url += "#" + get_tab()
        window.history.pushState(null, '', url);
    });

 

    $("#edit_form_tab").on("click", ".btn_delete_category", async function (e) {
        let id = $(this).attr("data-id");
        e.preventDefault();
        Swal.fire({
            title: '¡Atención!',
            text: "¿Esta seguro de eliminar la pregunta?\nSe eliminará toda la información asociada",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let response = await referenciasService.deleteReferencesData(id);
                window.location.reload()
                toastr.success(
                    "La pregunta se eliminó con éxito",
                    "",
                    { positionClass: "toast-top-right", timeOut: "50000" }
                );
               

            }
        });
    });
});
var getChart = async () => {
    let response = await encuestasService.getChartData({});
    if (response.length > 0) {
        var card = "";
        response.forEach(pregunta => {
            card = `
        <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                ${pregunta.pregunta}
            </div>
            <div class="card-body">
                <div style="min-height:400px" class="graf" id="graf-${pregunta.id}">

                </div>
            </div>
        </div>
    </div>
        `
            $("#content-grafs").append(card);
            pie_chart(pregunta, "graf-" + pregunta.id)
        });


    }

}
function pie_chart(res, content) {
    var chart = new AmCharts.AmPieChart();
    // title of the chart
    //chart.addTitle("table" + ' - ' + "option_table", 16);
    var colorsArray = [
        "#FF5733", "#33FF57", "#3357FF", "#FF33A1", "#A133FF",
        "#FF8C33", "#33FF8C", "#8C33FF", "#FF5733", "#33A1FF",
        "#A1FF33", "#FFA133", "#33FFA1", "#FF33FF", "#A1A1FF",
        "#FFA1A1", "#A1FFA1", "#A1A1A1", "#FF8C8C", "#8CFF8C",
        "#8C8CFF", "#FFC08C", "#C08CFF", "#8CFFC0", "#C08C8C",
        "#FFC0C0", "#C0C0FF", "#C0FFC0", "#8CC0FF", "#FFC08C"
    ];
    // Assign the array of colors to the chart
    chart.colors = colorsArray;
    chart.dataProvider = res.resultados;
    chart.titleField = "label";
    chart.valueField = "value";
    chart.sequencedAnimation = true;
    chart.startEffect = "elastic";
    chart.innerRadius = "30%";
    chart.startDuration = 2;
    chart.labelRadius = 15;
    chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
    // the following two lines makes the chart 3D
    // chart.depth3D = 10;
    // chart.angle = 15;

    var legend = new AmCharts.AmLegend();
    legend.markerBorderColor = "#000000";
    legend.switchType = undefined;
    legend.align = "left";
    chart.addLegend(legend);

    // WRITE
    chart.write(content);
}

async function index_page(route, request) {
    const page = route;
    const response = await fetch(page, {
        method: 'GET',
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-Token": $("#token").attr("content"),
        },

    });
    if (!response.ok) {
        const message = `An error has occured: ${response.status}`;
        console.log(response);
        throw new Error(message);
    }
    const topics = await response.json();
    return topics;
}