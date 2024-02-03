$(document).ready(function () {
    $("#btn_solicitar_conciliacion").on("click",function(e) {
        e.preventDefault();
        $("#myModal_detalles_login_conc").modal("show")
    });
    $("#btn_continuar_conciliacion").on("click",function(e) {
        e.preventDefault();
        window.location = '/solicitudes/conciliacion/recepcion?paso=1'
    });
    $("#valida_regla").on("change",function(e) {
        e.preventDefault();
        $("#btn_continuar_conciliacion").prop("disabled",true)
        if($(this).is(":checked")){
            $("#btn_continuar_conciliacion").prop("disabled",false);
        }
    })
});