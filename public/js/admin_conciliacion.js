import {UserService} from './services/users.js';
import {ConciliacionService} from '../services/conciliaciones.js';
import { FormatosService } from './services/formatos_documentos.js';

const userService = new UserService();
const conciliacionService = new ConciliacionService();
const formatosService = new FormatosService();
$(document).ready(function () {
 
    $("#myUserSolicitanteForm").on("focus","input[name='idnumber']",validateTypeDoc);
    $("#myUserRepLegalForm").on("focus","input[name='idnumber']",validateTypeDoc);
    $("#myUserApoderadoForm").on("focus","input[name='idnumber']",validateTypeDoc);
    $("#myUserParteSolicitadaForm").on("focus","input[name='idnumber']",validateTypeDoc);
    $("#myUserRepLegalSolicitadaForm").on("focus","input[name='idnumber']",validateTypeDoc);

    $("#myUserRepLegalSolicitadaForm").on("blur","input[name='idnumber']",async function() {      
      var lastidnumber = $(this).val();
      alertValidateUser(lastidnumber,"myUserRepLegalSolicitadaForm");
      $(this).val("");
    });
    $("#myUserSolicitanteForm").on("blur","input[name='idnumber']",async function() {      
        var lastidnumber = $(this).val();
        alertValidateUser(lastidnumber,"myUserSolicitanteForm");
        $(this).val("");
      });
      $("#myUserParteSolicitadaForm").on("blur","input[name='idnumber']",async function() {      
        var lastidnumber = $(this).val();
        alertValidateUser(lastidnumber,"myUserParteSolicitadaForm");
        $(this).val("");
      });
      $("#myUserRepLegalForm").on("blur","input[name='idnumber']",async function() {    
        var lastidnumber = $(this).val();      
        alertValidateUser(lastidnumber,"myUserRepLegalForm");
        $(this).val("");
      });
      $("#myUserApoderadoForm").on("blur","input[name='idnumber']",async function() {    
        var lastidnumber = $(this).val();        
        alertValidateUser(lastidnumber,"myUserApoderadoForm");
        $(this).val("");
      });


    $(".btn_asinar_usuario_conciliacion").on("click", function (e) {
        var data_type = $(this).attr("data-type");
        var form = $(this).attr("data-form");
        if(data_type==197){
          $("#content_detalles_solicitada").hide();
          $("#content_solicitada").show();
        }      
        $("#fondo_background").addClass("fondo_background")
        $("#ctbotones-"+data_type).show()
        $("#"+form).addClass("form_active");
        $("#"+form+" input").prop("disabled",false);
        $("#"+form+" select").prop("disabled",false);            

    });

    $(".btn_asinar_usuario_gen_conciliacion").on("click", function (e) {
      var data_type = $(this).attr("data-type");
      $("#myModal_conc_user_create input[name=tipo_usuario_id]").val(data_type);
      $("#myModal_conc_user_create input[name=section]").val($(this).attr('data-section'));
      var request = {          
          'conciliacion_id':$("#conciliacion_id").val(),          
          'data_type':data_type,
          'section':$(this).attr('data-section'),
      }
      if ($(this).attr('data-user')!=undefined) {
          request['idnumber'] = $(this).attr('data-user');
          request['tipodoc_id'] = $("#myModal_conc_user_create select[name=tipodoc_id]").val();  
          request['is_edit'] = true;  
      }else{
          request['idnumber'] = '0';
          request['tipodoc_id'] = 1;          
      }        
      getUser(request,request.idnumber);
      $("#myModal_conc_user_create").modal("show"); 
  });

    $(".btn_agregar_usuario_conciliacion").on("click", async function (e) {
      var form = $(this).attr("data-form")
     var user_id = $("#"+form+" input[name='id']").val();    
      if(user_id!=''){       
        var request = {
          "user_id":user_id,
          "conciliacion_id":$("input[name='conciliacion_id']").val(),
          "tipo_usuario":$(this).attr("data-type")
        };   
       $("#wait").show();
       let response_ = await  conciliacionService.addUser(request);       
        Toast.fire({
          title: "Asignado con éxito.",
          icon: "success",
          timer: 2000,
        });
       window.location.reload(true)
      }else{
        var errors = validateForm("myFormAsunto");   
        var request = {};
       
        if(errors.length<=0){

        }
      }
      $("#wait").hide();
    });

    $(".btn_cancel_usuario_conciliacion").on("click",function (e) {
      var data_type = $(this).attr("data-type");
      var form = $(this).attr("data-form");
      notEdit(data_type,form)
    });

    $("#myFormAsigReporte .select").on("change", function (e) {
      
      var tabla_destino = $("#myFormAsigReporte select[name=tabla_destino]").val();
     // var clave = $(this).attr("name");
      var status_id = $("#myFormAsigReporte select[name=status_id]").val();
      if (tabla_destino != "" && status_id != "") {
          var request = {
              tabla_destino: tabla_destino,
              status_id:status_id
              
          };
         // request[clave] =  status_id;
          if(tabla_destino=="227"){
            var categoria = $("#myFormAsigReporte select[name=categoria]").val();      
            if (categoria != "" && status_id != "") {
              request['categoria'] =  categoria;
              editAsignacionReporte(request);
            }
          }else{
            editAsignacionReporte(request);
          }
          
      }
  });

  $("#myFormEditPdfReporte select[name='categoria_id']").on("change",async function(params) {
    var categoria = $(this).val();
    if(categoria!=''){
      var request = {
        'categoria_id':categoria
      }
      let response = await conciliacionService.getReportesByCategory(request);
      $("#summernote_update").summernote("code", "");
      $("#myFormEditPdfReporte input[name='nombre_reporte']").val("");
      if(response.errors && response.errors.length >0){
        response.errors.forEach(error => {            
          toastr.error(error, "", {
              positionClass: "toast-top-right",
              timeOut: "4000",
          });          
        });    
      }else{
        var option = '<option value="">Seleccione...</option>';
        response.forEach(element => {
          option += `
          <option value="${element.id}">${element.nombre_reporte}</option>
          `;
        });
        $("#sel_reporte_id").html(option)
      }
     
    }
  });

  $("#myFormAsigReporte select[name='tabla_destino']").on("change",async function(params) {
    var categoria = $(this).val();
    if(categoria!=''){
      var request = {
        'categoria_id':categoria,

      }
      let response = await conciliacionService.getReportesByCategory(request);
      if(response.errors && response.errors.length >0){
        response.errors.forEach(error => {            
          toastr.error(error, "", {
              positionClass: "toast-top-right",
              timeOut: "4000",
          });          
        });    
      }else{
        var li = '';
        response.forEach(reporte => {
          li += `
          <li>
            <input class="checks_reportes" type="checkbox" id="chk_reporte_${reporte.id}" value="${reporte.id}" name="reporte_id[]" >
             ${reporte.nombre_reporte}
          </li>
          `;
        });
        $("#checks_reportes").html(li);
         if(categoria=='226'){
          $("#myFormAsigReporte select[name='categoria']").hide().prop("disabled",true);         
        }else{
          $("#myFormAsigReporte select[name='categoria']").show().prop("disabled",false);;         
        } 
      }
      console.log(response);
    }
  });

  
  $("#myformCreateEstado select[name=type_status_id]").on("change",async function (e) {
    if ($(this).val() != "") {
        var request = {
            tabla_destino: "226",
            status_id: $(this).val(),
            conciliacion_id:$("#conciliacion_id").val()
        };
      const response = await  conciliacionService.getPdfReportForStatus(request);
      $("#alertmyReportList").hide();
        if(response.length > 0){
          var tr = '';
          response.forEach(destino => {
            tr += `
            <tr>
              <td>
              ${destino.reporte.nombre_reporte}
              </td>
            </tr>
            `
          });
          $("#alertmyReportList").show()
          $("#myReportList tbody").html(tr)
        }
    }
});

$("#myformCreateEstado").on("submit",function (e) {
  //var request = $(this).serialize() +  "&conciliacion_id=" +   $("#conciliacion_id").val();
  var request = new FormData($(this)[0]);
  request.append("conciliacion_id", $("#conciliacion_id").val());
  var type_status_id = $("#myformCreateEstado select[name=type_status_id]").val()
  if(type_status_id == 181){
      var audiencia = $("#conciliacion_audiencia_id").val()
      if(audiencia==undefined){
          toastr.error(
              "No se puede admitir la conciliación porque no hay una audiencia habilitada",
              "Error",
              { positionClass: "toast-top-right", timeOut: "50000" }
          );
      }else{
          storeConciliacionEstado(request);
      }
  }else{
      storeConciliacionEstado(request);
  }
  //
  e.preventDefault();
}
);

$("#btn_cambiar_estado").on("click", function (e) {
  $("#myformEditEstado").attr("id", "myformCreateEstado");
  $("#myformCreateEstado textarea[name=comentario]").val("");
  $("#myformCreateEstado button[type=submit]").text("Confirmar nuevo estado");
  /* $("#myModal_create_estado .modal-title").text("Creando estado");
  $("#myModal_create_estado").modal("show"); */
  $("#content_form_estado_c").show();
  $("#content_list_estado_c").hide();
  $("#btn_cancelar_estado").show();
  $("#btn_cambiar_estado").hide();

});

$("#btn_cancelar_estado").on("click",function () {
  $("#btn_cancelar_estado").hide();
  $("#btn_cambiar_estado").show();
  $("#content_form_estado_c").hide();
  $("#content_list_estado_c").show();
});
$("#categoria_notifica__id").on("change",async function(e){
  var request = {
    'conciliacion_id':$("#conciliacion_id").val(),
    'tabla_destino':'227',
    'status_id':$("#estado_conciliacion_id").val(),
   // 'categoria':'mensaje_radicado',
    'reporte_id':$(this).val()
}
let response = await formatosService.getReportes(request);
//getReportes(request,'content_form_correo_est_responder');
if(response.body){
  $("#content_notificacion_correo").summernote("code", response.body);
}else if(response.error){
  toastr.error(response.error, "Algo falló!", {
      positionClass: "toast-bottom-right",
      timeOut: "4000",
  });
  $("#content_notificacion_correo").summernote("code", "Escriba su mensaje aquí!");
}});
$(".fila_usuarios_not").on("click",function(e){
 
if(!$(this).hasClass("fila_usuarios_not_selected") )
{
  $(this).removeClass("fila_usuarios_not").addClass("fila_usuarios_not_selected");
  var mail = $(this).attr("data-email")
  var id = $(this).attr("data-id")
 
  var mail =`
        <div class="rows_mails" id="row-${id}">
        <input type="hidden" value=" ${mail}" name="notify_mail[]" data-row="${id}">                      
          <label id="btn_delete_mail-${id}" type="button" data-id="${id}" data-row="${id}" class="btn_delete_not_mail label label-default">
              ${mail} <span class="badge">x</span>
          </label>                                 
      </div>`;

  $("#row_mail_not").append(mail);
  var length = $(".rows_mails").length
  $("#btn_env_not").prop("disabled",true)
  if(length>=0){
    $("#btn_env_not").prop("disabled",false)
  }
}
  
})
$("#row_mail_not").on("click",".btn_delete_not_mail",function(e){
  var id = $(this).attr("data-id");
  $("#row-"+id).remove();
  $("#user_"+id).removeClass("fila_usuarios_not_selected").addClass("fila_usuarios_not");
  $("#btn_env_not").prop("disabled",true);
  var length = $(".rows_mails").length
  if(length>0){
    $("#btn_env_not").prop("disabled",false)
  }
});
getActas();
getReportesForDestiny()
});//fin document ready
async function getReportesForDestiny(){
  var request = {
   
    'tabla_destino': "227",
    'status_id': $("#estado_conciliacion_id").val(),
  }
  let response = await conciliacionService.getDestinyForReport(request);
  console.log(response);
   var option = '<option value="">Seleccione...</option>';
        response.forEach(element => {
          option += `
          <option value="${element.id}">${element.nombre_reporte}</option>
          `;
        });
        $("#categoria_notifica__id").html(option)

}
async function getActas(){
  var request = {
    //conc_estado_id: $(this).attr("data-id"),
    tabla_destino: "conciliaciones",
    status_id: $("#estado_conciliacion_id").val(),
    conciliacion_id:$("#conciliacion_id").val()
};
let response = await conciliacionService.getPdfReportesConciliacion(request);
$("#myFormatosActasList tbody").html("");
$("#myFormatosActasList tbody").html(response.view);

}
function alertValidateUser(lastidnumber,form) {  
        var view = $("#"+form).attr("data-view");
        var content = $("#"+form).attr("data-content");
        if(lastidnumber!='' && $("#"+form+" select[name='tipodoc_id']").val()!='' ){          
            Swal.fire({
            title: 'Vuelve a ingresar el número de documento',
            input: 'text',
            inputAttributes: {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Validar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: async (idnumber) => {
              if(lastidnumber==idnumber){
               let request = {
                  "tipodoc_id":$("#"+form+" select[name='tipodoc_id']").val(),
                  "idnumber":idnumber,
                  "view":view,
                  "conciliacion_id":$("input[name='conciliacion_id']").val()
               }
                let response = await conciliacionService.editUser(idnumber,request);
                $("#wait").show();
                if(response.encontrado){
                    $("#"+content).html(response.view);      
                   
                }else{
                    $("#"+form+" input[name='idnumber']").val(lastidnumber);
                }
              }else{
                toastr.info("Los valores no coinciden", "!Atención", {
                  positionClass: "toast-top-right",
                  timeOut: "4000"});
                  $("#"+form+" input[name='id']").remove();
                  $("#"+form+" input[name='name']").val("");
                  $("#"+form+" input[name='lastname']").val("");
                  $("#"+form+" input[name='tel1']").val("");
                  $("#"+form+" input[name='address']").val("");
              }

              $("#wait").hide();
              
            },
            allowOutsideClick: () => !Swal.isLoading()
          });
        }
    }

    function notEdit(data_type,form){
      if(data_type==197){
        $("#content_detalles_solicitada").show();
        $("#content_solicitada").hide();
      }
      $("#ctbotones-"+data_type).hide()
        $("#fondo_background").removeClass("fondo_background")
        $("#"+form).removeClass("form_active");
        $("#"+form+" input").prop("disabled",true);
        $("#"+form+" select").prop("disabled",true);
        $("#"+form+" input").val("");
        $("#"+form+" select").val("");
            
       
    }