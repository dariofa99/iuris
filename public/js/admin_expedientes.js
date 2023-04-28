import {UserService} from './services/users.js';

const userService = new UserService();

$(document).ready(function () {
 


    $('#myFormBsExpAdv').on('keyup','div.buscar_usuario input', async function(e) {
        let name = $(this).val();
        if(name.length>=3){
            $('div.buscar_usuario li.no-results').text('Buscando...');
            const response = await userService.findUserByNameOrLastNameAndRole({'name':name,'role':'estudiante'})
           if(response.encontrado){
                $("#select_data_estudiantes").find('option').remove().end();//elimina opciones existentes
                $(".buscar_usuario").selectpicker('render');
                opcion_busq = '';
                 $(response.users).each(function(key, value){
                    opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name.toUpperCase() + '</option>';
                });
                $("#select_data_estudiantes").append(opcion_busq);
                $(".buscar_usuario").selectpicker("refresh");//refresca el select
            }
        }else{
            $('div.buscar_usuario li.no-results').text('Ingresa más caracteres...');
        }
      	
    });

    $("#btn_desc_exp_us").on("click",function(e){
        if($("#select_data_estudiantes").val()!='' && $("#select_data_estudiantes").val()!=null){
            console.log($("#select_data_estudiantes").val());
            let a = document.createElement("a");
            let req = $('#myFormBsExpAdv').serialize();
            a.setAttribute('href','excel/exp/user/download?'+req) ;
            a.setAttribute('target','_blank');
            a.click();
        }else{
            toastr.error("Ingrese un nombre de estudiante!", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            }); 
        }
        
      
    })

});//////////////////////////////////////////////
          
