import { SegmentosService } from "./services/segmentos.js";
const segmentosService = new SegmentosService();
$(document).ready(function () {
    $("#myFormNotasSearch select[name=periodo_id]").on("change", async function () {
        var periodo_id = $(this).val();
        if (periodo_id != "") {
            $("#wait").css("display", "block");
            let response = await segmentosService.searchSegmentos(periodo_id);
            var option = "";
            if (response.length > 0) {
                response.forEach((element) => {
                    option += `<option value="${element.id}">${element.segnombre}</option>`;
                });
                $("#segmento_id").html(option);
            } else {
                var option = '<option value="">El periodo no tiene cortes</option>';
            }
            $("#wait").hide();
            $("#myFormNotasSearch select[name=segmento_id]").html(option);
        }
    });

    $("#myFormNotasSearch input[name=type_repor]").on("change", function (e) {
        console.log($(this).val());
        if ($(this).val() == "periodo") {
            $("#myFormNotasSearch select[name=segmento_id]").prop(
                "disabled",
                true
            );
        } else {
            $("#myFormNotasSearch select[name=segmento_id]").prop(
                "disabled",
                false
            );
        }
    });
})