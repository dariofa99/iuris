import { EncuestasService } from './services/encuestas.js';
const encuestasService = new EncuestasService();
$(document).ready(async function () {
    await getChart()
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
        pie_chart(pregunta,"graf-"+pregunta.id) 
        });
        

    }
    console.log(response);
}
function pie_chart(res,content) {
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