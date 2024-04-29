import { EncuestasService } from './services/encuestas.js';

const encuestasService = new EncuestasService();


$(document).ready(function () {
    $("#myFormBuscarConciliacion").on("submit",async function (e) {
        e.preventDefault();
        var request = convertFormToJSON("myFormBuscarConciliacion");
        var response = await encuestasService.buscarConciliaciones(request);
        console.log(response);
    })

});