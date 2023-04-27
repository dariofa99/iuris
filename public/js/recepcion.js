$(function () {
    /* $('input').iCheck({
       checkboxClass: 'icheckbox_square-blue',
       radioClass: 'iradio_square-blue',
       increaseArea: '20%' // optional 
     });*/
 

     $("#btn_going_chat").on("click",function(e){
       e.preventDefault(); 
       var value = $(this).val();
       var view = 'coord';
       $("#myform_expediente_user_edit").attr('id','myform_exp_user_create');
       $("#myform_exp_user_create")[0].reset();
       $("#myform_exp_user_create").append($('<input>',{
           "type":"hidden",
           'value':$("#myformEditSolicitud input[name=id]").val(),
           'name':"solicitud_id"
        }));
       comprIdnumber(value, view,2)
      //$("#myModal_exp_user_edit").modal('show')
     });
 
      $("#content_solicitud_espera").on("click",'#btn_open_login',function(e){
       e.preventDefault();
       $("#myLoginForm input[name=solicitud_id]").val($("#solicitud_id").val());
       $("#myModal_login").modal("show");
     });
 
     $("#myform_recp_user_create").on("submit",function(e){
       let request = $(this).serialize()+"&solicitud_id="+$("#solicitud_id").val();
      userStore(request)
       e.preventDefault();
     });
 
     $("#myform_recp_user_create input[name=type_register]").on("change",function(e){
       if($(this).val()=='cc'){
           var idnumber = $("#myformEditSolicitud input[name=idnumber]").val()
           if(idnumber ==null){
            idnumber = $("#user_session_idnumber").val();
           }
         $("#myform_recp_user_create input[name=user_name]").attr('type','number')
         .val(idnumber).prop('readonly',true);     
       }else{
          $("#myform_recp_user_create input[name=user_name]").attr('type','email').prop('readonly',false).val("");
       }
       e.preventDefault();
     });
 
 
      $("#content_solicitud_espera").on("click",'#btn_cancel_solicitud',function(e){
        var id = $(this).attr('data-id');
         Swal.fire({
           title: 'Esta seguro de cancelar la solicitud?',
           text: "Se inhabilitará las consultas por tres días!",
           type: 'info',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Si, cancelar!',
           cancelButtonText: 'No, mantener'
         }).then((result) => {
           if (result.value) {
               let request = {'type_status_id':158,'type_category_id':161}
               updateSolicitud(request,id)            
           }
         }); 
       e.preventDefault();
     });
   
    $(".btn_menu").on("mouseover",function(e) {
        var option = parseInt($(this).text());     
        if($("#innumdv").val()!=option) change_message_recp(option);
    });
    $(".btn_menu").on("mousedown",function(e) {
      var option = parseInt($(this).text());
      if($("#innumdv").val()!=option)change_message_recp(option);
    });

    $(".btn_menu").on("mouseleave",function(e) {
        var option = parseInt($("#innumdv").val());      
        change_message_recp(option);
    });
 /*   $(".btn_menu").on("mouseout",function(e) {
    var option = parseInt($("#innumdv").val());
    console.log(option) 
    change_message_recp(option);
 }); */
     
   });
 
 
   function updateSolicitud(request,id) {   
   var route = "/solicitudes/"+id;
   $.ajax({
     url: route,
     type: 'PUT',
     datatype: 'json',
     data: request,
     cache: false,
     beforeSend: function (xhr) {
       xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
       $("#wait").css("display", "block");
     },
     success: function (res) {   
       if(res.status==200 && request.type_category_id==161){
        // window.location = '/recepcion';    
         window.location.reload();  
       }else{
         $("#btn_open_login").remove();
         $("#btn_going_chat").remove();
         window.location.reload();
       }
       $("#wait").css("display", "none");
   
     },
     error: function (xhr, textStatus, thrownError) {
       alert("Hubo un error con el servidor, consulte con el administrador");
       $("#wait").css("display", "none");
     }
   });
 
 }
 
   function showPassword(input){
     atributo = $("#"+input).attr('type');
     if (atributo=='password') {
       $("#"+input).attr('type','text');
     }else{
       $("#"+input).attr('type','password');
     }  
   }
 
   function userStore(request){
         var route = '/solicitudes/user/register';
         $.ajax({  
         url: route,
         type:'POST',
         datatype: 'json',
         data:request,
         cache: false,
         beforeSend: function(xhr){
           xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
           $("#wait").css("display", "block"); 
           $("#myform_recp_user_create input[type=button]").prop("disabled",true)  
         },
         success:function(res){
          if(res.errors){
              var cadena = "";
            res.errors.forEach(element => {
                cadena += element;
            });
            alert(cadena);
          }
          if(res.status == 200){
              if(res.url!=''){
                window.location = res.url; 
              }  else {
                  window.location.reload(true);
              }
           
          }
          
           $("#wait").css("display", "none"); 
         
         },
         error:function(xhr, textStatus, thrownError){
             alert("Hubo un error con el servidor ERROR: "+thrownError,textStatus);
             $("#wait").css("display", "none"); 
             $("#myform_recp_user_create input[type=button]").prop("disabled",false)
         }
       });
   }
 
   function change_message_recp(option) {
     var options = [
      `Paso 1 de 4: Aquí tienes que diligenciar toda la información del formulario para
       poder acceder a la consulta, si tienes una solicitud pendiente utiliza el cuadro
       de la derecha “recuperar turno en espera” con el número de solicitud entregado
       por el sistema.'`,
       `Paso 2 de 4: Tienes que esperar tu turno hasta recibir atención, recuerda
       anotar el número de la solicitud para que puedas retomar la consulta en
       caso de algún problema de conexión.`,
       `Paso 3 de 4: Seguir las instrucciones del asesor.`,
       `Paso 4 de 4: Iniciar sesión o registrarse.`

     ];

    var text = options[option-1];
    $(".content_message").text(text)

   }
 
 