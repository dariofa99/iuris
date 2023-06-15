import {UserService} from '../js/services/users.js';
import {ConciliacionService} from '../js/services/conciliaciones.js';
import { SolicitudesService } from './services/solicitudes.js';

const userService = new UserService();
const conciliacionService = new ConciliacionService();
const solicitudesService = new SolicitudesService();
$(function () {
 
    $("#btn_registrar_conc").on("click", async function(e) {
        e.preventDefault();
        var errors = validateForm("myFormParteSolicitante");
        //console.log(errors)
        if(errors.length <= 0){   
          var request = convertFormToJSON("myFormParteSolicitante");
          if(request.sede_id==''){
            toastr.error("No hay una sede seleccionada", "Atención!", {
              positionClass: "toast-top-right",
              timeOut: "4000",
           }); 
          return false;
          }
          
          let timerInterval
          Swal.fire({
            title: 'Espere por favor!',
            html: 'Estamos registrando su solicitud',
            timer: 6000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading()
             // const b = Swal.getHtmlContainer().querySelector('b')
              timerInterval = setInterval(() => {
                //b.textContent = Swal.getTimerLeft()
              }, 100)
            },
            willClose: () => {
              clearInterval(timerInterval)
            }
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              console.log('I was closed by the timer')
            }
          })
             
      //  var request = new FormData(document.getElementById("myFormParteSolicitante"));  
        var data = [];
        $(".input_user_ad").each((index,obj)=>{          
          data.push({
            value : $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
            section : $(obj).attr("data-section"),
            type : $(obj).attr("data-type"),
            name :  $(obj).attr("data-name"),
            option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
            value_is_other:$("#value_other_text-"+$(obj).val()).val(),
            conciliacion_id:$("#conciliacion_id").val()            
          }) ;         
        }); 
        request["data"] = (data);
        let response = await  solicitudesService.solicitar(request);
        if(response.errors){    
          Swal.close();                      
            response.errors.forEach(error => {            
                toastr.error(error, "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });          
          });            
        }else{    
          window.location = "/solicitudes/recepcion/conciliacion/"+response.conciliacion.token+"/?id="+response.conciliacion.id+"&paso=2";
        }
    }else{
        toastr.error("Hay campos que son obligatorios", "Atención!", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        }); 
    }


    });

    $("#myFormRepLegal").on("focus","input[name='idnumber']",validateTypeDoc);

    $("#myFormApoderado").on("focus","input[name='idnumber']",validateTypeDoc);

    $("#myFormParteConvocada").on("focus","input[name='idnumber']",validateTypeDoc);


    
    $("#myFormRepLegal").on("blur","input[name='idnumber']",async function() {      
      var lastidnumber = $(this).val();
      userService.alertValidateUser(lastidnumber,"myFormRepLegal");
      $(this).val("");
    });

    $("#myFormParteConvocada").on("blur","input[name='idnumber']",async function() {      
      var lastidnumber = $(this).val();
      userService.alertValidateUser(lastidnumber,"myFormParteConvocada");
      $(this).val("");
    });

    $("#myFormApoderado").on("blur","input[name='idnumber']",async function() {      
      var lastidnumber = $(this).val();
      userService.alertValidateUser(lastidnumber,"myFormApoderado");
      $(this).val("");
    });

    $("#btn_registrar_replegal_sol").on("click",async function() {
      var errors = validateForm("myFormRepLegal");      
      if(errors.length<=0){
       addUserByStep("myFormRepLegal",this,$(this).attr("data-step"))
      }     
    });
    $("#btn_registrar_apod_sol").on("click",async function() {
      var errors = validateForm("myFormApoderado");      
      if(errors.length<=0){
        addUserByStep("myFormApoderado",this,4)
      }     
    });

    $("#btn_parte_convocada").on("click",async function() {
      $("#wait").show();
      if(!$("#chk_not_parte").is(":checked")){
        var errors = validateForm("myFormParteConvocada"); 
        if(errors.length<=0){
          addUserByStep("myFormParteConvocada",this,6)
        }
      }else{
        if($("#myFormParteConvocada textarea").val()!=''){
          validateForm("myFormParteConvocada");
          var request={};
          request["conciliacion_id"]  = $("#conciliacion_id").val();
          var data = [];
          $(".insert_adv").each((index,obj)=>{          
            data.push({
              value : $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
              section : $(obj).attr("data-section"),
              type : $(obj).attr("data-type"),
              name :  $(obj).attr("data-name"),
              option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
              value_is_other:"",
              conciliacion_id:$("#conciliacion_id").val()
            }) ;         
          }); 
          request["data"] = (data);
          console.log(request);
        let response_ = await conciliacionService.addAditionalData(request);
        window.location = "/solicitudes/recepcion/conciliacion/"+response_.token+"/?id="+response_.id+"&paso="+6;
        }else{
          var errors = validateForm("myFormParteConvocada");
        }
      }
      $("#wait").hide();
    });


    $("#btn_registrar_asunto").on("click",async function() {
      var errors = validateForm("myFormAsunto");   
      var request = {};
     
      if(errors.length<=0){
        $("#wait").show();
        request["conciliacion_id"]  = $("#conciliacion_id").val();
        var data = [];
        $(".input_cd").each((index,obj)=>{          
          data.push({
            value : $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
            section : $(obj).attr("data-section"),
            type : $(obj).attr("data-type"),
            name :  $(obj).attr("data-name"),
            option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
            value_is_other:"",
            conciliacion_id:$("#conciliacion_id").val()
          }) ;         
        }); 
        request["data"] = (data);
        let response_ = await conciliacionService.addAditionalData(request);
        window.location = "/solicitudes/recepcion/conciliacion/"+response_.token+"/?id="+response_.id+"&paso="+5;
  
     }     
    });


    $(".btn_delete_usuario_conciliacion").on("click", function (e) {
        var data_pivot = $(this).attr("data-pivot");
        var request = {'pivot':data_pivot}
        Swal.fire({
            title: "Esta seguro de eliminar la asignación?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, eliminar!",
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.value) {
                var response = conciliacionService.deleteConciliacionUser(request);
                window.location.reload(true);
            }
        });
    });

    $(".btn_add_conc_he_con").on("click",function (e) {
      e.preventDefault();
      $("#myformEditHechoPretencion").attr('id','myformCreateHechoPretencion');
      $("#myformCreateHechoPretencion input[name=id]").val('')
      $("#myformCreateHechoPretencion textarea[name=descripcion]").val('')
      $("#myformCreateHechoPretencion input[name=tipo_id]").val($(this).attr('data-tipo'))
      $("#myModalCreateConcHechosPretenciones").modal('show'); 
      $("#lbl_title_modal").text($(this).attr('data-tipo') == 206 ? "Agregando hechos": "Agregando pretensiones")
  });

  $(".btn_create_document").on("click", function (e) {
      
    $("#myformEditConciliacionAnexo").attr("id","myformCreateConciliacionAnexo");
    $("#myformCreateConciliacionAnexo")[0].reset();
    $("#myformCreateConciliacionAnexo input[name=concept]").val($(this).attr("data-concept"));
    $("#myformCreateConciliacionAnexo input[name=category_id]").remove();
    $("#myformEditConciliacionAnexo input[name=conciliacion_file]").prop(
        "required",
        true
    );
    $("#myformCreateConciliacionAnexo").append(
        $("<input>",{
            type:'hidden',
            value:$(this).attr("data-category"),
            name:"category_id"
        })
    )
    $("#myformCreateConciliacionAnexo button[type=submit]").text("Crear");
    $("#myModal_create_document .modal-title").text("Creando anexo");
    $("#myModal_create_document").modal("show");
});

$("#myModalCreateConcHechosPretenciones").on("submit",'#myformCreateHechoPretencion', async function (e) {
  $("#wait").show();
  var request = convertFormToJSON("myformCreateHechoPretencion");
  request['conciliacion_id'] = $("#conciliacion_id").val()
  e.preventDefault()
  const response = await conciliacionService.addHechosPretenciones(request);
  
   if (response.view || response.view == "") {
    $("#content_hechos_pretensiones-"+response.tipo_id).html(response.view);
  }
  $("#myModalCreateConcHechosPretenciones").modal('hide');
  $("#wait").hide();
});

$("#myModal_create_document").on("submit","#myformCreateConciliacionAnexo",async function (e) {
  var request = new FormData($(this)[0]);
  e.preventDefault();
  $("#wait").show();
  request.append("conciliacion_id", $("#conciliacion_id").val());
  const response = await conciliacionService.addFile(request);
  if (response.view || response.view == "") {
  $("#table_anexos_list tbody").html(response.view);
  }
  $("#myModal_create_document").modal("hide")
  $("#wait").hide();
}
);

$(".content_hechos_pretensiones").on("click",'.btn_eliminar_hepr', async function (e) {
  e.preventDefault();
  var id = $(this).attr('data-id');
  Swal.fire({
      title: 'Esta seguro que desea eliminar el registro?',
      text: "No se podrá revertir los cambios!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar!',
      cancelButtonText: 'No, mantener abierta!'
    }).then(async (result) => {
      if (result.value) {              
          const response = await conciliacionService.deleteFile(id);
          if (response.view || response.view == "") {
            $("#content_hechos_pretensiones-"+response.tipo_id).html(response.view);
        }
        $("#myModalCreateConcHechosPretenciones").modal('hide');
                  
      }
    });   
});


$(".content_hechos_pretensiones").on("click",'.btn_editar_hepr',async function (e) {
  e.preventDefault();
  var id = $(this).attr('data-id');
  $("#wait").show();
  const response = await conciliacionService.editHechoPretension(id);
  $("#myformCreateHechoPretencion").attr('id','myformEditHechoPretencion');
  $("#myformEditHechoPretencion input[name=id]").val(response.id)
  $("#myformEditHechoPretencion input[name=tipo_id]").val(response.tipo_id)
  $("#myformEditHechoPretencion textarea[name=descripcion]").val(response.descripcion);
  $("#wait").hide();
  $("#myModalCreateConcHechosPretenciones").modal('show');
});

$("#myModalCreateConcHechosPretenciones").on("submit",'#myformEditHechoPretencion',async function (e) {
  e.preventDefault()
  $("#wait").show();
  var request = convertFormToJSON("myformEditHechoPretencion");
  request['conciliacion_id'] = $("#conciliacion_id").val();  
  var id = $("#myformEditHechoPretencion input[name=id]").val();
  $("#wait").show();
  const response = await conciliacionService.updateHechosPretensiones(request,id);
  if (response.view || response.view == "") {
    $("#content_hechos_pretensiones-"+response.tipo_id).html(response.view);
}
$("#wait").hide();
$("#myModalCreateConcHechosPretenciones").modal('hide');  
$("#myModal_create_estado_pretension").modal('hide')

  
});


$("#table_anexos_list").on("click", ".btn_delete_anxcon", function (e) {
  var request = {
      file_id: $(this).attr("data-file"),
      conciliacion_id: $("#conciliacion_id").val(),
      category_id:233
  };
  Swal.fire({
      title: "Esta seguro de eliminar el archivo?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar!",
      cancelButtonText: "No, cancelar",
  }).then(async (result) => {
      if (result.value) {
        $("#wait").show();
        const response = await conciliacionService.deleteAnexo(request);
        if (response.view || response.view == "") {
          $("#table_anexos_list tbody").html(response.view);
          Toast.fire({
              title: "Archivo eliminado con éxito.",
              icon: "success",
              timer: 2000,
          });
      }
        $("#wait").hide();
      
      }
  });
});

$("#chk_not_parte").on("change",function (e) {
  $("#content_solicitada").show();
  $("#content_detalles_solicitada").hide()
  if($(this).is(":checked")){
    $("#content_solicitada").hide();
    $("#content_detalles_solicitada").show()
  }  
});

$("#btn_solicitar_conciliacion").on("click",function (e) {
  e.preventDefault();
  var files = $(".files").length;
  var anexos = $(".content_he_pret").length
  if(files<=0){
    Swal.fire({
      title: "Recuerda subir los anexos requeridos!",
      icon: "warning",
      confirmButtonColor: "#3085d6",
      confirmButtonText: "Ok",   
  });

  }else if(anexos<=0){
    Swal.fire({
      title: "Recuerda subir los hechos o pretensiones requeridos!",
      icon: "warning",
      confirmButtonColor: "#3085d6",
      confirmButtonText: "Ok",   
  });
  
  }else{
   
    Swal.fire({
      title: "¿Está seguro envíar a revisión la solicitud de conciliación?",
      html:"<h4>No podrá realizar cambios hasta que sea revisada. <br> Debe estar pendiente del correo o número de teléfono suministrado.</h4>",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, envíar!",
      cancelButtonText: "No, cancelar",
  }).then(async (result) => {
      if (result.value) {
        $("#wait").show();
        var request = {
          'concepto':"Solicitado por externo",
          'type_status_id':175,
          'conciliacion_id':$("#conciliacion_id").val(),
          'send_notification':true
        }
        const response = await conciliacionService.updateEstado(request);
        window.location.reload(true)     
        $("#wait").hide();
      
      }
  });
  }
});
$("#chk_not_parte_apoderado").on("change",function(e){
  if($(this).is(":checked")){
    $("#content_apoderado_solicitud").hide();
    $("#btn_registrar_apod_sol").hide();
    $("#btn_no_apoderado").show()
  }else{
    $("#content_apoderado_solicitud").show();
    $("#btn_registrar_apod_sol").show();
    $("#btn_no_apoderado").hide()
  }
});

$(".btn_change_sede").on("click",function(e){
  $(".btn_change_sede ").removeClass("btn-danger")
  .addClass('btn-primary').text("Seleccionar");
  $(this).addClass("btn-danger").removeClass('btn-primary').text("Seleccionada");
  $("#myFormParteSolicitante input[name='sede_id']").val($(this).attr('data-id'));
});

});//fin document ready

async function addUserByStep(form,obj,step) {
  $("#wait").show();

  if($("#"+form+" input[name='id']").val()!=undefined && $("#"+form+" input[name='id']").val()!=""){
    
    var request = {
      "user_id":$("#"+form+" input[name='id']").val(),
      "conciliacion_id":$("input[name='conciliacion_id']").val(),
      "tipo_usuario":$(obj).attr("data-type")
    };    
 
    let response_ = await  conciliacionService.addUser(request);    
    if(response_){
      window.location = "/solicitudes/recepcion/conciliacion/"+response_.token+"/?id="+response_.id+"&paso="+step;
    }
  }else{
   
    var request = convertFormToJSON(form);
    if(!request.hasOwnProperty("email")){
      var email = $("#"+form + " input[name=idnumber]").val() + "@mail.com";
      request['email'] = email;
    }
    let response = await  userService.registrar(request);
      if(response.errors){                          
          response.errors.forEach(error => {            
              toastr.error(error, "", {
                  positionClass: "toast-top-right",
                  timeOut: "4000",
              });          
          });            
      }else{
        var request = {
          "user_id":response.user.id,
          "conciliacion_id":$("input[name='conciliacion_id']").val(),
          "tipo_usuario":$(obj).attr("data-type")
        };   
       
       let response_ = await  conciliacionService.addUser(request);
       window.location = "/solicitudes/recepcion/conciliacion/"+response_.token+"/?id="+response_.id+"&paso="+step;
 
      }
      }
  $("#wait").show();

}