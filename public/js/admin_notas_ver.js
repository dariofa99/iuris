import { UserService } from './services/users.js';
import { ExpedientesService } from './services/expedientes.js';
const userService = new UserService();
const expedientesService = new ExpedientesService();
$(document).ready(function () {
    $(".btn_eliminar_notas_ver").on("click",function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#btn_delete_notas_ver-"+id).show();
        $("#btn_cancel_notas_ver-"+id).show();
        $(this).hide();

        $(".chk_notas-"+id).prop("checked",true).prop("disabled",false).attr("type","checkbox")
    });

    $(".btn_cancel_notas_ver").on("click",function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $(".btn_delete_notas_ver").hide();
        $(".btn_cancel_notas_ver").hide();
        $(".btn_eliminar_notas_ver").show();
        $(".chk_notas-"+id).prop("checked",false).prop("disabled",true).attr("type","hidden")
    });

    $(".btn_delete_notas_ver").on("click",async function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var data = [];
        $(".chk_notas-"+id).each((key, element) => {
            if($(element).is(":checked")){
                data.push($(element).val())
            }
        });
        if(data.length>0){
            Swal.fire({
                title: 'Esta seguro de eliminar las notas del caso?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'No, cancelar'
            }).then(async (result) => {
                if (result.value) {
                    var request = {};
                    request['nota_id'] = data;
                    $("#wait").show()
                    let response = await expedientesService.deleteNotas(request);
                    toastr.success("Eliminado con éxito", "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                    window.location.reload(true);
                  
                }
            });
            
        }else{
            toastr.error("Debe seleccionar al menos una nota", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
        
    });
});//////////////////////////////////////////////
