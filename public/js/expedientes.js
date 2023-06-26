import {UserService} from '../js/services/users.js';
import { ExpedientesService } from './services/expedientes.js';
const userService = new UserService();
const expedientesService = new ExpedientesService();
$(document).on("ready",function () {
  $("#btn_exp_user_carga").on("click",async function(){
    let request = {
      "tipodoc_id":$(this).attr('data-tipo_doc'),
      "idnumber":$(this).val(),
      "view":"myforms.components_exp.frm_user_register"
   }
   $("#wait").show();
    let response = await userService.findUserByIdnumber(request);
    if(response.encontrado){
      $("#content_user_exp_asig").html(response.view);
      toastr.success("Usuario encontrado", "", {
        positionClass: "toast-top-center",
        timeOut: "4000",
    });  
    $("#myFormUserEditExpediente input[name='idnumber']").prop('disabled',true);
    }
    $("#wait").hide()
  }); 

$("#btn_exp_user_carga_create").on("click",function(e){    
    $("#myFormUserEditExpediente").attr("id","myFormUserCreateExpediente");
    $("#actualizar_exp_us").attr("id","registrar_exp_us");    
    resetForm('myFormUserCreateExpediente');
    $("#myFormUserCreateExpediente select[name='tipopers_id']").val(237);
    $("#myFormUserCreateExpediente select[name='tipodoc_id']").val(2);
});

$("#content_user_exp_asig").on("blur","input[name='idnumber']",async function(e){
  var formulario =  $(this).closest('form');
  var formularioId = formulario.attr('id');
  $("#"+formularioId+" input[name='email']").val($(this).val()+"@mail.com")
  if($(this).val()!=''){
    let request = {
      "tipodoc_id":$("#"+formularioId+" select[name='tipodoc_id']").val(),
      "idnumber":$(this).val(),
      "view":"myforms.components_exp.frm_user_register"
   }
   $("#wait").show();
    let response = await userService.findUserByIdnumber(request);
    if(response.encontrado){
      $("#content_user_exp_asig").html(response.view);
      toastr.success("Usuario encontrado", "", {
        positionClass: "toast-top-center",
        timeOut: "4000",
    });  
    $("#myFormUserEditExpediente input[name='idnumber']").prop('disabled',true);
    }
    $("#wait").hide()
  }
    
});


$("#content_user_exp_asig #myFormUserCreateExpediente").on("focus","input[name='idnumber']",validateTypeDoc);
$("#content_user_exp_asig #myFormUserEditExpediente").on("focus","input[name='idnumber']",validateTypeDoc);

$("#content_user_exp_asig").on("click",'#registrar_exp_us',async function(e){
    var errors =validateForm("myFormUserCreateExpediente");      
    if(errors.length<=0){
    var request = convertFormToJSON("myFormUserCreateExpediente");
    var data = [];
    $("#myFormUserCreateExpediente .input_user_ad").each((index,obj)=>{          
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
    $("#wait").show();
    let response = await  userService.registrar(request);
    if(response.errors){                          
        response.errors.forEach(error => {            
            toastr.error(error, "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });          
      });            
    }else{
      resetForm('myFormUserEditExpediente');
      $("#myFormExpsStore input[name='expidnumber']").val(response.user.idnumber)
      $("#myModal_exp_user_edit").modal("hide");
    }
    $("#wait").hide();
  }
});

$("#content_user_exp_asig").on("click",'#actualizar_exp_us',async function(e){
  var errors =validateForm("myFormUserEditExpediente");      
  if(errors.length<=0){
  var request = convertFormToJSON("myFormUserEditExpediente");
  var data = [];
  $("#myFormUserEditExpediente .input_user_ad").each((index,obj)=>{          
    data.push({
      value : $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
      section : $(obj).attr("data-section"),
      type : $(obj).attr("data-type"),
      name :  $(obj).attr("data-name"),
      option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
      value_is_other:$("#value_other_text-"+$(obj).attr('data-id')).val(),
                 
    });        
  }); 
  request["data"] = (data);
 
  $("#wait").show();
  let response = await  userService.update(request);
  if(response.errors){                          
      response.errors.forEach(error => {            
          toastr.error(error, "", {
              positionClass: "toast-top-right",
              timeOut: "4000",
          });          
    });            
  }else{
    resetForm('myFormUserEditExpediente');
    $("#myFormExpsStore input[name='expidnumber']").val(response.user.idnumber)
    $("#myModal_exp_user_edit").modal("hide");
    toastr.success("Actualizado con éxito", "", {
      positionClass: "toast-top-right",
      timeOut: "4000",
  });  

  }
  $("#wait").hide();
}
});

$("#btn_cerrar_dr_caso").on("click",function(e){
  
  let request = {
    expidnumber : $("#expid").val(),
    ref_estado_id:2,
    ref_motivo_estado_id:8
  }
  Swal.fire({
    title: 'Cerrando caso',
    input: 'textarea',
    inputPlaceholder: '¿Por qué va a cerrar el caso?',
    inputAttributes: {
        rows: 100,  // Número de filas del textarea
        cols: 500  // Número de columnas del textarea
      },
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Cerrar caso',
    confirmButtonClass: 'btn-success', 
    allowEmpty: false, // Evita el valor vacío en el textarea
    preConfirm: (text) => {      
      if(text!==''){
        $("#wait").show();
        request["comentario"] = text;
        let response = expedientesService.cerrarCaso(request);
        toastr.success("Actualizado con éxito", "", {
          positionClass: "toast-top-right",
          timeOut: "4000",
      }); 
        window.location.reload(true)
    }else{
        Swal.showValidationMessage('La descripción no puede estar vacía'); // Muestra un mensaje de validación personalizado
     
    }
    }
  });
  $("#wait").hide();
   
});




});