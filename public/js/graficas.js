  (function(){
    
    var options = {
        'expedientes':[
            {
                'value':'Rama del derecho',
                'option_value':'rama_derecho'
            },
            {
                'value':'Estado',
                'option_value':'estado'
            },
            { 
                'value':'Tipo Procedimiento',
                'option_value':'tipo_procedimiento'
            },
            {
                'value':'Tipo de Documento',
                'option_value':'tipo_doc',
                'option_id':'tipodoc_id', 
                'table':'users'       
            },
            {
                'value':'Género',
                'option_value':'genero',
                'option_id':'genero_id',
                'table':'users'   
            },
            {
                'value':'Departamento',
                'option_value':'departamento',
                'option_id':'expdepto_id',
                'table':'expedientes'   
            },
            {
              'value':'Municipio',
              'option_value':'municipio',
              'option_id':'expmunicipio_id',
              'table':'expedientes'   
            },
            {
              'value':'Tipo de Vivienda',
              'option_value':'tipo_vivienda',
                'option_id':'exptipovivien_id',
                'table':'expedientes'   
            },
            {
              'value':'Estrato',
              'option_value':'estrato',
                'option_id':'estrato_id',
                'table':'users'   
            },
            {
              'value':'Estado Civil',
              'option_value':'estado_civil',
              'option_id':'estadocivil_id',
              'table':'users'   
            }
            
        ],

        'conciliaciones':[
            {
                'value':'Estado',
                'option_value':'estado'
            }        
            
           
            
        ],

        'actuaciones':[
            {
                'value':'Estado',
                'option_value':'estado_act'
            }
        ],
        'requerimientos':[
            {
                'value':'Estado',
                'option_value':'estado_req'
            }
        ]
    };

    
    $("#select_table").on('change',function(){
        insert_options_select();
        insert_options_select_cruce();
    });
    $("#select_option_table").on('change',function(){
        insert_options_select_cruce();
    });

    $("#check_hab_cruce").on('change',function(){
        insert_options_select_cruce();

    });

    $("#check_hab_rango").on('change',function(){
        if ($(this).is(':checked')) {
            $("#fecha_ini").prop('disabled',false);
            $("#fecha_fin").prop('disabled',false);
        }else{
            $("#fecha_ini").prop('disabled',true);
            $("#fecha_fin").prop('disabled',true);
        } 
    });

    $(".generate_graf").on('change',search_data);

   

function insert_options_select(){
    var select_principal = $("#select_table");
    var select = $("#select_option_table");
    var option ='';
    $("#check_hab_cruce").prop('disabled',false);
    $("#check_hab_cruce").prop('checked',false);
    if (select_principal.val()=='expedientes') {     
            for (var i = 0; i <= options.expedientes.length - 1; i++) {
                option += '<option value="'+options.expedientes[i].option_value+'">'+options.expedientes[i].value+'</option>';
            }
            
    }
    if (select_principal.val()=='actuaciones') {               
                for (var i = 0; i <= options.actuaciones.length - 1; i++) {
                    option += '<option value="'+options.actuaciones[i].option_value+'">'+options.actuaciones[i].value+'</option>';
                }  
        $("#check_hab_cruce").prop('disabled',true);
        $("#check_hab_cruce").prop('checked',false);
                           
    }
    if (select_principal.val()=='requerimientos') {               
                for (var i = 0; i <= options.requerimientos.length - 1; i++) {
                    option += '<option value="'+options.requerimientos[i].option_value+'">'+options.requerimientos[i].value+'</option>';
                }
    $("#check_hab_cruce").prop('disabled',true);
    $("#check_hab_cruce").prop('checked',false);    

    }
    if (select_principal.val()=='conciliaciones') {               
        for (var i = 0; i <= options.conciliaciones.length - 1; i++) {
            option += '<option value="'+options.conciliaciones[i].option_value+'">'+options.conciliaciones[i].value+'</option>';
        }  
        $("#check_hab_cruce").prop('disabled',true);
        $("#check_hab_cruce").prop('checked',false);
                        
    }
    select.html('');
    select.append(option);
}
function insert_options_select_cruce(){
    var select_principal = $("#select_table");
    var select_sec = $("#select_option_table");
    var select = $("#select_option_table_cruce");
    var option ='';
    checked = $("#check_hab_cruce");
        if (checked.is(':checked')) {
            if (select_principal.val()=='expedientes') {               
                for (var i = 0; i <= options.expedientes.length - 1; i++) {
                    if (select_sec.val()!=options.expedientes[i].option_value) {
                        option += '<option value="'+options.expedientes[i].option_value+'">'+options.expedientes[i].value+'</option>';
                    }
                }                
            }
            if (select_principal.val()=='requerimientos') {               
                for (var i = 0; i <= options.requerimientos.length - 1; i++) {
                    if (select_sec.val()!=options.requerimientos[i].option_value) {
                     option += '<option value="'+options.requerimientos[i].option_value+'">'+options.requerimientos[i].value+'</option>';
                    }
                }                
            }

            select.html('');
            select.append(option);
            select.attr('disabled',false);
            select.show();

        }else{
               select.attr('disabled',true);
               select.html('');
               select.hide();
               
                
        } 
}



  })();



var chart;

             chartData = {
                'consulta':[
                {
                    "label": "USA",
                    "value": 3025,
                    "color": "#FF0F00"
                },
                {
                    "label": "China",
                    "value": 1882,
                    "color": "#FF6600"
                },
                {
                    "label": "Japan",
                    "value": 1809,
                    "color": "#FF9E01"
                },
                {
                    "label": "Germany",
                    "value": 1322,
                    "color": "#FCD202"
                },
                {
                    "label": "UK",
                    "value": 1122,
                    "color": "#F8FF01"
                },
                {
                    "label": "France",
                    "value": 1114,
                    "color": "#B0DE09"
                },
                {
                    "label": "India",
                    "value": 984,
                    "color": "#04D215"
                },
                {
                    "label": "Spain",
                    "value": 711,
                    "color": "#0D8ECF"
                },
                {
                    "label": "Netherlands",
                    "value": 665,
                    "color": "#0D52D1"
                },
                {
                    "label": "Russia",
                    "value": 580,
                    "color": "#2A0CD0"
                },
                {
                    "label": "South Korea",
                    "value": 443,
                    "color": "#8A0CCF"
                },
                {
                    "label": "Canada",
                    "value": 441,
                    "color": "#CD0D74"
                }
            ]

            }
           
    var chartData2 = [
                {
                    "estado": "abierto",
                    "simple": 35,
                    "compleja": 356,
                    "defensa": 45,
                    //"color": "#FF0F00"
                },
                {
                    "estado": "cerrado",
                    "simple": 343,
                    "compleja": 214,
                    "defensa": 563,
                    //"color": "#CD0D74"
                },
                {
                    "estado": "aprobado",
                    "simple": 325,
                    "compleja": 456,
                    "defensa": 316,
                    //"color": "#8A0CCF"
                }
                ,
                {
                    "estado": "solicitud",
                    "simple": 375,
                    "compleja": 167,
                    "defensa": 336,
                    //"color": "#04D215"
                }
             
            ];

            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
               // chart.dataProvider = chartData;
                chart.categoryField = "estado";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 45; // this line makes category values to be rotated
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.dashLength = 5;
                valueAxis.title = "Visitors from pais";
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "defensa";
                /*graph.colorField = "#000000";
                graph.bulletColorR = "#000000";
                */
                graph.title = 'Defensas de Oficio';
                graph.balloonText = "<b>Defensas de Oficio: [[value]]</b>";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 1;
                graph.labelText = " ";
                 graph.labelFunction = function(item){

                      var total = 0;
                            for(var i = 0; i < chart.dataProvider.length; i++) {
                              total += chart.dataProvider[i][item.graph.valueField];
                            }
                            
      /**
       * Calculate percet value of this label
       */
                                 var percent = Math.round( ( item.values.value / total ) * 1000 ) / 10;
                            return percent + "%";
                }

                chart.addGraph(graph);

                var graph = new AmCharts.AmGraph();
                graph.valueField = "simple";
                graph.colorField = "[[color]]";
                graph.title = 'Consultas Simple';
                graph.balloonText = "<b>Consultas Simple: [[value]]</b>";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.labelText = " ";
                graph.fillAlphas = 1;
               graph.labelFunction = function(item){

                      var total = 0;
                            for(var i = 0; i < chart.dataProvider.length; i++) {
                              total += chart.dataProvider[i][item.graph.valueField];
                            }
                            
      /** 
       * Calculate percet value of this label
       */
                                 var percent = Math.round( ( item.values.value / total ) * 1000 ) / 10;
                            return percent + "%";
                }
                chart.addGraph(graph);

                var graph = new AmCharts.AmGraph();
                graph.valueField = "compleja";
                graph.colorField = "[[color]]";
                graph.title = 'Consultas Compleja';
                graph.balloonText = "<b>Consultas Compleja: [[value]]</b>";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.labelText = " ";
                graph.fillAlphas = 1;
                 graph.labelFunction = function(item){

                      var total = 0;
                            for(var i = 0; i < chart.dataProvider.length; i++) {
                              total += chart.dataProvider[i][item.graph.valueField];
                            }
                            
      /**
       * Calculate percet value of this label
       */
                                 var percent = Math.round( ( item.values.value / total ) * 1000 ) / 10;
                            return percent + "%";
                }
                chart.addGraph(graph);

                

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = true;
                chartCursor.categoryBalloonEnabled = false;
                //chart.addChartCursor(chartCursor);

                chart.creditsPosition = "top-left";

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.useGraphSettings = true;
                chart.addLegend(legend);

                // WRITE
                //chart.write("chartdiv");

                //console.log(chart);
            });


AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "estado";
                chart.startDuration = 0.5;
                chart.balloon.color = "#000000";

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridAlpha = 0;
                categoryAxis.axisAlpha = 0;
                categoryAxis.gridPosition = "start";
                categoryAxis.position = "bottom";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.title = "Expedientes";
                valueAxis.dashLength = 5;
                valueAxis.axisAlpha = 0;
                valueAxis.minimum = 1;
                valueAxis.maximum = 2000;
                valueAxis.integersOnly = true;
                valueAxis.gridCount = 100;
                valueAxis.reversed = false; // this line makes the value axis reversed
                chart.addValueAxis(valueAxis);

                // GRAPHS
                // Italy graph
                var graph = new AmCharts.AmGraph();
                /*graph.title = "Italy";
                graph.valueField = "italy";
                graph.hidden = true; // this line makes the graph initially hidden
                graph.balloonText = "place taken by Italy in [[category]]: [[value]]";
                graph.lineAlpha = 1;
                graph.bullet = "round";
                chart.addGraph(graph);*/

                graph.valueField = "defensa";
                graph.bulletColorR = "#000000";
                graph.title = 'Defensas de Oficio';
                //graph.hidden = true; // this line makes the graph initially hidden
                graph.balloonText = "<b>Defensas de Oficio: [[value]]</b>";
               // graph.type = "line";
                graph.lineAlpha = 1;
                graph.bullet = "round";
                graph.fillAlphas = 0;
                graph.labelText = " ";
                 graph.labelFunction = function(item){

                      var total = 0;
                            for(var i = 0; i < chart.dataProvider.length; i++) {
                              total += chart.dataProvider[i][item.graph.valueField];
                            }
                            
      /**
       * Calculate percet value of this label
       */
                                 var percent = Math.round( ( item.values.value / total ) * 1000 ) / 10;
                            return item.values.value + "";
                }
                chart.addGraph(graph);

                // Germany graph
                var graph = new AmCharts.AmGraph();
                graph.valueField = "simple";
                //graph.hidden = true; // this line makes the graph initially hidden
                graph.colorField = "#000000";
                graph.title = 'Consultas simple';
                graph.balloonText = "<b>Simples: [[value]]</b>";
               // graph.type = "line";
                graph.lineAlpha = 1;
                graph.bullet = "round";
                graph.fillAlphas = 0;
                graph.labelText = " ";
                 graph.labelFunction = function(item){

                      var total = 0;
                            for(var i = 0; i < chart.dataProvider.length; i++) {
                              total += chart.dataProvider[i][item.graph.valueField];
                            }
                            
      /**
       * Calculate percet value of this label
       */
                                 var percent = Math.round( ( item.values.value / total ) * 1000 ) / 10;
                            return item.values.value + "";
                }
                chart.addGraph(graph);

                // United Kingdom graph
                var graph = new AmCharts.AmGraph();
               // graph.hidden = true; // this line makes the graph initially hidden
                graph.valueField = "compleja";
                graph.bulletColorR = "color";
                graph.title = 'Consultas Compleja';
                graph.balloonText = "<b>Complejas: [[value]]</b>";
               // graph.type = "line";
                graph.lineAlpha = 1;
                graph.bullet = "round";
                graph.fillAlphas = 0;
                graph.labelText = " ";
                 graph.labelFunction = function(item){

                      var total = 0;
                            for(var i = 0; i < chart.dataProvider.length; i++) {
                              total += chart.dataProvider[i][item.graph.valueField];
                            }
                            
      /**
       * Calculate percet value of this label
       */
                                 var percent = Math.round( ( item.values.value / total ) * 1000 ) / 10;
                            return item.values.value + "";
                }
                chart.addGraph(graph);

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorPosition = "mouse";
                chartCursor.zoomable = false;
                chartCursor.cursorAlpha = 0;
                chart.addChartCursor(chartCursor);

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.useGraphSettings = true;
                chart.addLegend(legend);

                // WRITE
               // chart.write("chartdiv2");
            });

////console.log(chart)
function search_data(){
    var route = '/graficas/search';
     var table = $("#select_table");
     var option_table = $("#select_option_table");
     var option_table_cruce = $("#select_option_table_cruce");
     var fecha_ini = $("#fecha_ini");
     var fecha_fin = $("#fecha_fin");
     if (fecha_ini.attr('disabled')=='disabled') {
         fecha_ini = '';
         fecha_fin = '';
     }else{
        fecha_ini =  $("#fecha_ini").val();
        fecha_fin = $("#fecha_fin").val();
     }
    if (table.val()!='') {
        $.ajax({ 
        url: route,
        headers: { 'X-CSRF-TOKEN' : token },
        type:'post',
        datatype: 'json',
        data:{'table':table.val(),'option_table':option_table.val(),'option_table_cruce':option_table_cruce.val(),'fecha_ini':fecha_ini,'fecha_fin':fecha_fin},
        cache: false,
         beforeSend: function(xhr){ 
          xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
          $("#preloader_chart").css("display", "block");  
          $("#chartdiv").html(''); 
        },
            /*muestra div con mensaje de 'regristrado'*/
        success:function(res){
          //console.log(res);
          generate_grafica(res)
         //window.location.reload(true);
         $("#preloader_chart").css("display", "none");
          
        },
        error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
        $("#wait").css("display", "none");
        }
    });
     }else{
        $("#chartdiv").html('');
        $("#check_hab_cruce").prop('disabled',true);
        $("#check_hab_cruce").prop('checked',false);
     }
    
    
}

function generate_grafica(res){
    // PIE CHART
    var input;
     table = $("select[name='select_table'] option:selected").text();
     option_table = $("select[name='select_option_table'] option:selected").text();
    var option_table_cruce = $("#select_option_table_cruce");
    $("input[name='type_grap']").each(function(index,obj){
        
        if ($(this).is(':checked')) {
            input = $(this);
        }
        
    }); 
    

if (option_table_cruce.val()!=null) {
   
    $("input[id='pie']").prop('disabled',true);
    $("input[id='pie']").prop('checked',false);
    if (input.val()=='pie') {
        $("input[id='line']").prop('checked',true);
        input = $("input[id='line']");
    }
    
   
    if (input.val()=='line') multi_line_chart(res)
    if (input.val()=='bar') multi_column_chart(res);    
}else{
    if (input.val()=='pie') pie_chart(res);
    if (input.val()=='bar') column_chart(res);
    if (input.val()=='line') line_chart(res);
    $("input[id='pie']").prop('disabled',false);
}    

   // //console.log(input.val());         

                   ///////////////////////////////////////////

               




}

function column_chart(res){
          // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = res.consulta;
                chart.categoryField = "label";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90; // this line makes category values to be rotated
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.dashLength = 5;
                valueAxis.title = table+' - '+option_table;
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "value";
                graph.title = 'value';
                graph.balloonText = "<b>[[category]]: [[value]]</b>";
                graph.type = "column";
                graph.lineAlpha = 1;
               // graph.fillColorsField = 'color';
                graph.colorField = "color";
                graph.fillAlphas = 1;
                //graph.bullet = "round";
                graph.labelText = "[[value]]";
                 
                chart.addGraph(graph);

              

                

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = true;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);

                chart.creditsPosition = "bottom-left";

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.useGraphSettings = true;
                chart.addLegend(legend);

                // WRITE
                chart.write("chartdiv");

                 
}

function pie_chart(res){
    chart = new AmCharts.AmPieChart();

                // title of the chart
                chart.addTitle(table+' - '+option_table, 16);

                chart.dataProvider = res.consulta;
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
                chart.write("chartdiv");
}
function line_chart(res){
    // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = res.consulta;
                chart.categoryField = "label";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90; // this line makes category values to be rotated
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.dashLength = 5;
                valueAxis.title = table+' - '+option_table;
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "value";
                graph.title = 'value';
                graph.balloonText = "<b>[[category]]: [[value]]</b>";
                //graph.type = "column";
                graph.lineAlpha = 1;
                graph.colorField = "color";
                graph.fillColorsField = 'color';
                graph.fillAlphas = 0;
                graph.bullet = "round";
                graph.labelText = " ";
                 
                chart.addGraph(graph);

              

                

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = true;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);

                chart.creditsPosition = "top-left";

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.useGraphSettings = true;
                chart.addLegend(legend);

                // WRITE
                chart.write("chartdiv");

              
}

function multi_line_chart(res){
    // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = res.consulta.data;
                chart.categoryField = "encabezado";
                chart.startDuration = 1;

                // AXES
                // category 
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90; // this line makes category values to be rotated
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.dashLength = 5;
                valueAxis.title = table+' - '+option_table;
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                for (var i = res.consulta.graph.length - 1; i >= 0; i--) {
                    var graph = new AmCharts.AmGraph();
                    graph.valueField = res.consulta.graph[i].value_graph;
                    graph.title = res.consulta.graph[i].value_graph;
                    graph.balloonText = "<b>[[category]]: "+res.consulta.graph[i].value_graph+": [[value]] ";
                    //graph.type = "column";
                    graph.lineAlpha = 1;
                    //graph.fillColorsField = 'color';
                    graph.fillAlphas = 0;
                    graph.bullet = "round";
                    graph.labelText = " ";
                     
                    chart.addGraph(graph);
                }
                

              

                

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = true;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);

                chart.creditsPosition = "top-left";

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.useGraphSettings = true;
                chart.addLegend(legend);

                // WRITE
                chart.write("chartdiv");

                
}

function multi_column_chart(res){
          // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = res.consulta.data;
                chart.categoryField = "encabezado";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 90; // this line makes category values to be rotated
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.dashLength = 5;
                valueAxis.title = table+' - '+option_table;
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                 for (var i = res.consulta.graph.length - 1; i >= 0; i--) {
                    var graph = new AmCharts.AmGraph();
                    graph.valueField = res.consulta.graph[i].value_graph;
                    graph.title = res.consulta.graph[i].value_graph;
                    graph.balloonText = "<b>[[category]]: "+res.consulta.graph[i].value_graph+": [[value]] ";
                    graph.type = "column";
                    graph.lineAlpha = 1;
                    //graph.fillColorsField = 'color';
                    graph.fillAlphas = 1;
                    //graph.bullet = "round";
                    graph.labelText = " ";
                     
                    chart.addGraph(graph);
                }

              

                

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = true;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);

                chart.creditsPosition = "bottom-left";

                // LEGEND
                var legend = new AmCharts.AmLegend();
                legend.useGraphSettings = true;
                chart.addLegend(legend);

                // WRITE
                chart.write("chartdiv");

                
}