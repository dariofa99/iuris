import {UserService} from './services/users.js';

const userService = new UserService();

$(document).on("ready",function () {
 
$("#registrar_gen_us").on("click",async function(e){
    var errors = validateForm("myFormUserCreate"); 
    if(errors.length<=0){        
        var request = convertFormToJSON("myFormUserCreate");
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
        let response = await  userService.registrar(request);
        if(response.errors){                          
            response.errors.forEach(error => {            
                toastr.error(error, "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });          
        });            
        }else{
            resetForm('myFormUserCreate');
            Swal.fire({
                position: 'top-center',
                icon: 'success',
                title: 'Cambios registrados exitosamente!',
                showConfirmButton: false,
                timer: 1500
            }); 
        }
    }
});
$("#content_user_gen_form").on("blur","input[name='idnumber']",async function(e){
    var formulario =  $(this).closest('form');
    var formularioId = formulario.attr('id');
    $("#"+formularioId+" input[name='email']").val($(this).val()+"@mail.com")
    if($(this).val()!=''){
      let request = {
        "tipodoc_id":$("#"+formularioId+" select[name='tipodoc_id']").val(),
        "idnumber":$(this).val(),
        "view":"myforms.frm_myusers_gen_form"
     }
     $("#wait").show();
      let response = await userService.findUserByIdnumber(request);
      if(response.encontrado){
        $("#content_user_gen_form").html(response.view);
         toastr.success("Usuario encontrado", "", {
          positionClass: "toast-top-center",
          timeOut: "4000",
      });  
    }
     
    }
    $("#wait").hide()
  });

  $("#content_user_gen_form").on("click",".add_or_change_sede_usuario",async function(e){
    var formulario =  $(this).closest('form');
    var action =  $(this).attr('data-action');
    var formularioId = formulario.attr('id');
    var id = $("#"+formularioId+" input[name='id']").val()
    let request = {
    "id":id,
    "action":action,
    "view":"myforms.frm_myusers_gen_form"
   }   
   let response = await userService.addSede(request)
   if(response.agregado){
    $("#content_user_gen_form").html(response.view);
        Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Cambios registrados exitosamente!',
        showConfirmButton: false,
        timer: 1500
        }); 
    }   
  });

  $("#btn_actualizar_usuario").on("click",async function(e){
    var errors =validateForm("myFormUserEdit");      
    if(errors.length<=0){
    var request = convertFormToJSON("myFormUserEdit");
    var data = [];
    $("#myFormUserEdit .input_user_ad").each((index,obj)=>{          
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
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Cambios registrados exitosamente!',
            showConfirmButton: false,
            timer: 1500
            });
    }
    $("#wait").hide();
  }
  });


});//////////////////////////////////////////////
          
