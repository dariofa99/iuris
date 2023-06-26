(function(){
$("#select_table_excel").on('change',function(){
        insert_options_select_excel();
        //insert_options_select_cruce();
});
$("#select_option_table_excel").on('change',search_options_excel);

$("#cont-opt-excel").on('change','.btn_check',function(){
    var id = $(this).val();
    if ($(this).is(':checked')) {
      $("#"+id).prop('disabled',false);
    }else{
      $("#"+id).prop('disabled',true);
    }
  var bandera = false;
  $("#btn_download_excel").prop('disabled',true);
  $(".btn_check").each(function(){
    if ($(this).is(':checked')) {
      bandera = true;
    }
  });
   if (bandera) {
      $("#btn_download_excel").prop('disabled',false);
    }

});
$("#llenar_tabla_cols").on('change','#btn_check_all',function(){

  if ($(this).is(':checked')) {
    $(".input_cabecera").prop('disabled',false);
    $(".btn_check").prop('checked',true);
    $("#btn_download_excel").prop('disabled',false);
  }else{
    $(".input_cabecera").prop('disabled',true);
    $(".btn_check").prop('checked',false);
    $("#btn_download_excel").prop('disabled',true);
  }

});

$(".generate_excel").on('change',search_data_excel);



})();
var options_excel = {
        'expedientes':[
            {
                'value':'Todo',
                'option_value':'todo',
                'option_id':'',
                'table':''
            },
            {
                'value':'Rama del derecho',
                'option_value':'rama_derecho',
                'option_id':'expramaderecho_id',
                'table':'expedientes'           
            },
            {
                'value':'Estado',
                'option_value':'estado',
                'option_id':'expestado_id',
                'table':'expedientes'       
            },
            {
                'value':'Tipo Procedimiento',
                'option_value':'tipo_procedimiento',
                'option_id':'exptipoproce_id',
                'table':'expedientes'       
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
        'actuaciones':[
        {
          'value':'Todo',
          'option_value':'todo'
        },
        {
                'value':'Estado',
                'option_value':'estado_act',
                'option_id':'actestado_id',
            }
        ],
        'requerimientos':[
            {
                'value':'Todo',
                'option_value':'todo'
            },
            {
                'value':'Estado',
                'option_value':'estado_req',
                'option_id':'reqentregado'
            }
        ]
    };

function insert_options_select_excel(){
    var select_principal = $("#select_table_excel");
    var select = $("#select_option_table_excel");
    var option ='';
    if (select_principal.val()=='expedientes') {     
            for (var i = 0; i <= options_excel.expedientes.length - 1; i++) {
                option += '<option value="'+options_excel.expedientes[i].option_value+'">'+options_excel.expedientes[i].value+'</option>';
            }
            
    }
    if (select_principal.val()=='actuaciones') {               
                for (var i = 0; i <= options_excel.actuaciones.length - 1; i++) {
                    option += '<option value="'+options_excel.actuaciones[i].option_value+'">'+options_excel.actuaciones[i].value+'</option>';
                }                
    }
    if (select_principal.val()=='requerimientos') {               
                for (var i = 0; i <= options_excel.requerimientos.length - 1; i++) {
                    option += '<option value="'+options_excel.requerimientos[i].option_value+'">'+options_excel.requerimientos[i].value+'</option>';
                }                
    }
    select.html('');
    select.append(option);
}

function search_data_excel(){
    var route = '/excel/search';
     var table = $("#select_table_excel");
     if (table.val()!='') {
      clear_tables();
        $.ajax({ 
            url: route,
            headers: { 'X-CSRF-TOKEN' : token },
            type:'post',
            datatype: 'json',
            data:{'table':table.val()},
            //,'option_table':option_table.val(),'option_table_cruce':option_table_cruce.val(),'fecha_ini':fecha_ini,'fecha_fin':fecha_fin},
            cache: false,
             beforeSend: function(xhr){ 
              xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content')); 
              $("#wait").show();        
            },           
            success:function(res){
              
              if (table.val()=='expedientes') {
                llenar_tabla_cols(res.expedientes);
                llenar_tabla_cols_users(res);
              }
              if (table.val()=='actuaciones') {
                llenar_tabla_cols(res.actuaciones);
              }
              if (table.val()=='requerimientos') {
                llenar_tabla_cols(res.requerimientos);
              }
              $("#wait").hide(); 
              $('#btn_download_excel').prop('disabled',false);
            },
            error:function(xhr, textStatus, thrownError){
            alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
            $("#wait").css("display", "none");
            }
      });
    }else{
      clear_tables();
      $('#btn_download_excel').prop('disabled',true);
    }
       
}
function search_options_excel(){
    var route = '/excel/search/options';
     var select = $("#select_option_table_excel");
     if (select.val()!='') {
        $.ajax({ 
            url: route,
            headers: { 'X-CSRF-TOKEN' : token },
            type:'post',
            datatype: 'json',
            data:{'option':select.val()},
            //,'option_table':option_table.val(),'option_table_cruce':option_table_cruce.val(),'fecha_ini':fecha_ini,'fecha_fin':fecha_fin},
            cache: false,
             beforeSend: function(xhr){ 
              xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));         
            },           
            success:function(res){
              //console.log(res)
             llenar_options_filtro_select(res);
             if ($("#select_table_excel").val()=='expedientes') {
              set_filter(options_excel.expedientes,select.val())
             }
             if ($("#select_table_excel").val()=='actuaciones') {
              set_filter(options_excel.actuaciones,select.val())
             }
             if ($("#select_table_excel").val()=='requerimientos') {
              set_filter(options_excel.requerimientos,select.val())
             }
              
            },
            error:function(xhr, textStatus, thrownError){
            alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
            $("#wait").css("display", "none");
            }
      });
    }else{
      $("#select_options_filtro_excel").html('');
    }
       
}
function set_filter(res,val){
   for (var i = res.length - 1; i >= 0; i--) {               
                if (res[i].option_value==val) {
                  $("#filter").val(res[i].option_id);
                  
                }

            }
}
function llenar_tabla_cols(res){
var table = $("#llenar_tabla_cols tbody");
table.html('');
var row ='';
var cont = 1;
  row+='<tr>';
  row+='<td width="12.5%" align="center">';
  row+= '<b>Marcar Todo</b> <br> <input id="btn_check_all" checked type="checkbox">';
  row+='</td>';
  row+='<tr>';
  for (var i = 0; i <= res.length -1; i++) {
      if (cont==1 ){
        row+='<tr>';
      }    
        row+='<td width="12.5%" align="center">';
        row+='<input name="cabecera[]" class="input_cabecera" type="hidden" id="'+res[i].value+'" value="'+res[i].label+'">'
        row+=''+res[i].label+'<br> <input class="btn_check" checked type="checkbox" name="data[]" value="'+res[i].value+'">';
        row+='</td>';
          cont++; 
      if (cont==9 ) {
        row+='</tr>';
        cont = 1;
      }   
  }
  
  table.append(row);
}


function llenar_options_filtro_select(res){
    var select = $("#select_options_filtro_excel");
    var option ='';
       
            for (var i = 0; i <= res.options.length - 1; i++) {
                option += '<option value="'+res.options[i].id+'">'+res.options[i].value+'</option>';
            }
            
    
    
    select.html('');
    select.append(option);
}

function llenar_tabla_cols_users(res){
var table = $("#llenar_tabla_cols_users tbody");
table.html('');
var row ='';
var cont = 1;
  
  for (var i = 0; i <= res.usuarios.length -1; i++) {
  if (cont==1 ){
    row+='<tr>';  
  } 
    
    row+='<td width="12.5%" align="center">';
    row+='<input name="cabecera[]" class="input_cabecera" id="'+res.usuarios[i].value+'" type="hidden" value="'+res.usuarios[i].label+'">'
    row+=''+res.usuarios[i].label+'<br> <input class="btn_check" checked type="checkbox" name="data[]" value="'+res.usuarios[i].value+'">';
    row+='</td>';
  cont++; 
  if (cont==9 ) {
    row+='</tr>';
    cont = 1;
  }   
  }
  
  table.append(row);
}
 function clear_tables(){
  var table = $("#llenar_tabla_cols_users tbody");
  table.html('');
  var select = $("#select_options_filtro_excel");
  select.html('')
  var option ='';
  var table = $("#llenar_tabla_cols tbody");
  table.html('');
  var select_principal = $("#select_table_excel");
  var select = $("#select_option_table_excel");

 }


