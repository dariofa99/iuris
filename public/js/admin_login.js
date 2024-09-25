$(document).ready(function () {
    
    $("#btn_solicitar_conciliacion").on("click",function(e) {
        e.preventDefault();
        $("#myModal_detalles_login_conc").modal("show")
    });
    $("#btn_continuar_conciliacion").on("click",function(e) {
        e.preventDefault();
        window.location = '/solicitudes/conciliacion/recepcion?paso=1'
    });
    $("#btn_continuar_expedientes").on("click",function(e) {
        e.preventDefault();
        window.location = '/solicitudes/expedientes/recepcion?paso=1'
    });
    $("#valida_regla").on("change",function(e) {
        e.preventDefault();
        $("#btn_continuar_conciliacion").prop("disabled",true)
        $("#btn_continuar_expedientes").prop("disabled",true)
        if($(this).is(":checked")){
            $("#btn_continuar_conciliacion").prop("disabled",false);
            $("#btn_continuar_expedientes").prop("disabled",false);
        }
    })
    $(".btn_login").on("click",function(e) {
        $("#myModal_iniciar_sesion").modal("show")
    })
    $("#tramites-continuar").on("click",function(e) {
        $("#tramites-tab").trigger("click")
    })
});