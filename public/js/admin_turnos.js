import { PeriodosService } from "./services/periodos.js";
const periodoService = new PeriodosService();


$(document).ready(function () { 
    $(".btn_habilityupdatecolor").on("click", function(e){
        e.preventDefault();
        var id = $(this).attr("data-id");
        habilityEditColor(id);
    })
});/////////////////////////////////////////////////////
function habilityEditColor(turno_id) {
    showElement("color_id" + turno_id);
    showElement("cursando_id" + turno_id);
    showElement("horario_id" + turno_id);
    showElement("trnid_oficina" + turno_id);
    showElement("trnid_dia" + turno_id);
    showElement("btn_hideupdatecolor" + turno_id);
    showElement("btnUpdatecolor_" + turno_id);
    hideElement("btn_habilityupdatecolor" + turno_id);
    hideElement("label_color" + turno_id);
    hideElement("label_cursando" + turno_id);
    hideElement("label_horario" + turno_id);
    hideElement("label_trnid_oficina" + turno_id);
    hideElement("label_trnid_dia" + turno_id);
    hideElement("btn_delete_turno-" + turno_id);
}