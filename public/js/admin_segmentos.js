import { SegmentosService } from "./services/segmentos.js";
const segmentosService = new SegmentosService();


$(document).ready(function () {
 
    $("#btn_create_periodo").on("click", function () {
        $("#myModal_create_periodo").modal("show");
    });
    $("#myform_create_periodo").on("submit", async function (e) {
        e.preventDefault();
       var errors = validateForm("myform_create_periodo");
        if (errors.length <= 0) {
            var data = convertFormToJSON("myform_create_periodo");
            let response = await segmentosService.store(data);
            if (response.errors) {
                response.errors.forEach(error => {
                  toastr.error(error, "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                  });
                });
            }else{
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                $("#table_list_model").html(response);
                $("#myModal_create_periodo").modal("hide");
            }
           
        }else{
         
        }

        return false;
    });

    $("#table_list_model").on("change", ".radio_state_segmento", async function () {
        var id = $(this).attr("data-id");
        $("#wait").show();
        let response = await segmentosService.changeState(id);
        $("#table_list_model").html(response);
        $("#wait").hide();
    });
  
    $("#table_list_model").on("click", ".btn_edit_per",async function () {
        var id = $(this).attr("data-id");
        $("#wait").show();
        let res = await segmentosService.edit(id);
        $("#myform_edit_periodo input[name='prdfecha_inicio']").val(res.prdfecha_inicio);
        $("#myform_edit_periodo input[name='prdfecha_fin']").val(res.prdfecha_fin);
        $("#myform_edit_periodo input[name='prddes_periodo']").val(res.prddes_periodo);
        $("#myform_edit_periodo input[name='id_periodo']").val(res.id);
        $("#wait").hide();
        $("#myModal_edit_periodo").modal("show");
    });
    $("#myform_edit_periodo").submit(async function (e) {
        e.preventDefault();
       var errors = validateForm("myform_edit_periodo");
        if (errors.length <= 0) {
            var data = convertFormToJSON("myform_edit_periodo");
            $("#wait").show();
            let response = await segmentosService.update(data,data.id_periodo);
            if (response.errors) {
                response.errors.forEach(error => {
                  toastr.error(error, "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                  });
                });
            }else{
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                $("#myModal_edit_periodo").modal("hide");
                $("#table_list_model").html(response);
            }  
            $("#wait").hide();          
        }
        return false;
    });
});/////////////////////////////////////////////////////