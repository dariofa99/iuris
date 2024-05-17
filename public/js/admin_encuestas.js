import { EncuestasService } from './services/encuestas.js';
import { ConciliacionService } from './services/conciliaciones.js';
const conciliacionService = new ConciliacionService();
const encuestasService = new EncuestasService();


$(document).ready(function () {
    $("#myEvaNivSatForm").on("submit", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON("myEvaNivSatForm");
       
        var data = conciliacionService.getAditionalDataByForm('myEvaNivSatForm');
        request["data"] = (data);
         //var response = await encuestasService.buscarConciliaciones(request);
        console.log(request);
    })

});