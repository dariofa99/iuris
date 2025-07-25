import { EncuestasService } from './services/encuestas.js';
const encuestasService = new EncuestasService();
import { ReferenciasService } from "./services/referencias.js";
const referenciasService = new ReferenciasService();
let encId = 0;
var dataChart = {};
$(document).ready(async function () {
    set_tab()
    await getChart();


    $("#btn_load_categoryInExp").on("click", async function (e) {
        e.preventDefault();
        var request = {
            "table": "exp_encuesta_satisf",
            "categories": "exp_encuesta_satisf",
            "encuesta_id": encId
        }
        let response = await referenciasService.getByRefDataFilter(request);
        $("#list_preguntas_add_test").html(response.view);
        $("#myModal_encuesta_add_preguntas").modal("show");
    })

    $("#myModal_create_category").on("submit", "#myformCreateInEnCategory", async function (e) {
        e.preventDefault()
        //$("#wait").show();
        var request = convertFormToJSON('myformCreateInEnCategory');
        request['table'] = 'exp_encuesta_satisf'
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
   



    $("#tblListaEncuestas").on("change", ".radioChangeActiveEncuesta", async function (e) {
        e.preventDefault();
        encId = $(this).closest("tr").attr("data-id")
        var request = {
            activo: 1,
            categoria_id: 256
        }
        $("#wait").show()
        let response = await encuestasService.update(request, encId);
        $("#wait").hide()
        toastr.success("Asignado con éxito", "",
            { positionClass: "toast-bottom-right", timeOut: "5000" }
        );
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
            request["categoria_id"] = 256;
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
    });
});

$("select[name='select_periodo']").on("change", async function (e) {
    e.preventDefault();
    $("#content-grafs").html("");
    let url = '/expedientes/evaluar/reportes/?periodo=' + $(this).val();
    $("#wait").show()
    let response = await index_page(url);
    $(".list_encuind").html(response.view);
    $("#wait").hide()
    url += "#" + get_tab()
    window.history.pushState(null, '', url);
    $("#wait").hide();
    await getChart($(this).val());
    $("select[name='select_periodo']").val($(this).val());
});

$(".changeToPieChart").on("click", async function (e) {
    e.preventDefault();
    if (dataChart.length > 0) {
        $("#content-grafs").html("");
        $("#wait").show();
        var card = "";
        dataChart.forEach(pregunta => {
            card = getCardToChart(pregunta);
            $("#content-grafs").append(card);
            pie_chart(pregunta, "graf-" + pregunta.id);
        });
    } else {
        $("#content-grafs").html("<h3 class='text-center ml-5 mt-5'>No hay datos para mostrar</h3>");
    }
    $("#wait").hide();

});


$(".changeToBarChart").on("click", async function (e) {
    e.preventDefault();
    if (dataChart.length > 0) {
        $("#content-grafs").html("");
        $("#wait").show();
        var card = "";
        dataChart.forEach(pregunta => {
            card = getCardToChart(pregunta);
            $("#content-grafs").append(card);
            bar_chart(pregunta, "graf-" + pregunta.id);
        });
    } else {
        $("#content-grafs").html("<h3 class='text-center ml-5 mt-5'>No hay datos para mostrar</h3>");
    }
    $("#wait").hide();

});

var getChart = async (id) => {

    var periodo = id;
    $("#wait").show();

    let response = await encuestasService.getChartDataExp({ "periodo": periodo });
    if (response.length > 0) {
        dataChart = response;
        var card = "";
        response.forEach(pregunta => {
            card = getCardToChart(pregunta);
            $("#content-grafs").append(card);
            bar_chart(pregunta, "graf-" + pregunta.id);
        });


    } else {
        $("#content-grafs").html("<h3 class='text-center ml-5 mt-5'>No hay datos para mostrar</h3>");
    }
    $("#wait").hide();
}

function getCardToChart(pregunta) {
    return `
                <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                    <div class="row">
                    <div class="col-md-10">
                        <h4 class="card-title">
                        ${pregunta.pregunta}
                        </h4>
                    </div>
                        <div class="col-md-2">
                
                            
                        </div>
                    </div>                
                    </div>
                    <div class="card-body">
                        <div style="min-height:400px" class="graf" id="graf-${pregunta.id}">

                        </div>
                    </div>
                </div>
            </div>`;
}


function bar_chart(res, content) {
    var chart = new AmCharts.AmSerialChart();
    chart.categoryField = "label";
    chart.startDuration = 1;

    // Eje X
    var categoryAxis = chart.categoryAxis;
    categoryAxis.labelRotation = 45;
    categoryAxis.gridPosition = "start";

    // Eje Y
    var valueAxis = new AmCharts.ValueAxis();
    var wordsPerLine = 6;
    var words = res.pregunta.split(" ");
    var formattedQuestion = "";
    for (var i = 0; i < words.length; i++) {
        formattedQuestion += words[i] + " ";
        if ((i + 1) % wordsPerLine === 0) {
            formattedQuestion += "\n";
        }
    }
    valueAxis.title = formattedQuestion;
    valueAxis.titleFontSize = 11;
    valueAxis.minimum = 0;
    valueAxis.titleMargin = 20;
    chart.addValueAxis(valueAxis);

    var colorsArray = [
        "#FF5733", "#33FF57", "#3357FF", "#FF33A1", "#A133FF",
        "#FF8C33", "#33FF8C", "#8C33FF", "#33A1FF", "#A1FF33"
    ];

    // Calcular total
    var total = res.resultados.reduce((sum, item) => sum + item.value, 0);

    // Crear un solo objeto en dataProvider
    var dataItem = { label: "Total" };

    for (var i = 0; i < res.resultados.length; i++) {
        var resultado = res.resultados[i];
        var valueField = "value_" + i;
        var percentage = ((resultado.value / total) * 100).toFixed(2);

        dataItem[valueField] = resultado.value;

        var graph = new AmCharts.AmGraph();
        graph.valueField = valueField;
        graph.title = resultado.label + " (" + resultado.value + ")";
        graph.type = "column";
        graph.fillAlphas = 0.8;
        graph.lineAlpha = 0.2;
        graph.balloonText = resultado.label + ": <b>[[value]]</b> (" + percentage + "%)";
        graph.labelText = percentage + "%"; // 👈 Aquí mostramos el porcentaje
        graph.labelPosition = "top";
        graph.fontSize = 12;
        graph.labelColor = "#000000";
        graph.color = "#000000";
        graph.fillColors = colorsArray[i % colorsArray.length];

        chart.addGraph(graph);
    }

    chart.dataProvider = [dataItem];

    // Leyenda
    var legend = new AmCharts.AmLegend();
    legend.useGraphSettings = true;
    chart.addLegend(legend);

    // Exportar
    chart.export = {
        enabled: true,
        menu: [{
            class: "export-main",
            menu: [
                { label: "Descargar como PNG", format: "png" },
                { label: "Descargar como JPG", format: "jpg" },
                { label: "Descargar como SVG", format: "svg" },
                { label: "Descargar como CSV", format: "csv" }
            ]
        }]
    };

    chart.write(content);
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
    // Configuración de exportación
    chart.export = {
        enabled: true,
        menu: [{
            class: "export-main",
            menu: [{
                label: "Descargar como PNG",
                format: "png"
            }, {
                label: "Descargar como JPG",
                format: "jpg"
            }, {
                label: "Descargar como SVG",
                format: "svg"
            }, {
                label: "Descargar como CSV",
                format: "csv"
            }]
        }]
    };
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