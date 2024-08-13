import { EncuestasService } from './services/encuestas.js';
const encuestasService = new EncuestasService();
import { ReferenciasService } from "./services/referencias.js";
const referenciasService = new ReferenciasService();

$(document).ready(async function () {
    set_tab()
    await getChart();
    
    

    $("#myModal_create_category").on("submit", "#myformCreateInCategory", async function (e) {
        e.preventDefault()
        $("#wait").show();
        var request = convertFormToJSON('myformCreateInCategory');
        request['table'] = 'exp_encuesta_satisf'
        let response = await referenciasService.storeReferencesData(request)
        window.location.reload()
        toastr.success(
            "La pregunta se creó con éxito",
            "",
            { positionClass: "toast-top-right", timeOut: "50000" }
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
            text: "¿Esta seguro de eliminar la pregunta?\nSe eliminará toda la información asociada.",
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
    let response = await encuestasService.getChartDataExp({});
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