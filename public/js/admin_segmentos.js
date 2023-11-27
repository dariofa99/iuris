import { SegmentosService } from "./services/segmentos.js";
const segmentosService = new SegmentosService();


$(document).ready(function () {

    $("#btn_create_segmento").on("click", function () {
        $("#myModal_create_segmento").modal("show");
    });
    $("#myform_create_segmento").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myform_create_segmento");
        if (errors.length <= 0) {
            var data = convertFormToJSON("myform_create_segmento");
            let response = await segmentosService.store(data);
            if (response.errors) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });
            } else {
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                $("#table_list_model").html(response);
                $("#myModal_create_segmento").modal("hide");
            }

        } else {

        }

        return false;
    });

    $("#table_list_model").on("click", ".btn_edit_seg", async function () {
        var id = $(this).attr("data-id");
        $("#wait").css("display", "block");
        let response = await segmentosService.edit(id);
        $("#myform_edit_segmento input[name='segnombre']").val(
            response.segnombre
        );
        $("#myform_edit_segmento input[name='fecha_inicio']").val(
            response.fecha_inicio
        );
        $("#myform_edit_segmento input[name='fecha_fin']").val(
            response.fecha_fin
        );
        $("#myform_edit_segmento input[name='segmento_id']").val(response.id);

        $("#wait").css("display", "none");
        $("#myModal_edit_segmento").modal("show");
    });
    $("#myform_edit_segmento").submit(async function (e) {
        e.preventDefault()
        var errors = validateForm("myform_edit_segmento");
        if (errors.length <= 0) {
            var data = convertFormToJSON('myform_edit_segmento');
            var id = $("#myform_edit_segmento input[name='segmento_id']").val();
            $("#wait").css("display", "block");
            let response = await segmentosService.update(data,id);
            $("#myModal_edit_segmento").modal("hide");
            $("#table_list_model").html(response);
            // window.history.replaceState( {} , '/periodos', route );
            $("#wait").css("display", "none");
        }
        return false;
    });
    $("#table_list_model").on("click", ".btn_cerrar_seg",async function (e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de cerrar el corte?',
            html:`Se asignaran notas con valor a 0 (cero) a los expedientes sin evaluar.
            <br>(No aplica para Expedientes abiertos en los ultimos 10 días, de acuerdo a la fecha final del corte).
            <br>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                var id = $(this).attr("data-id");
                let response = await segmentosService.closeSegmento(id);
                if (response.errors) {
                    response.errors.forEach(error => {
                      toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                      });
                    });
                  }else{
                    toastr.success("Cerrado con éxito", "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                    $("#table_list_model").html(response.view);
                  }
               
                $("#wait").hide();
            }
        });

    });
    $("#table_list_model").on("change", ".radio_state_segmento", async function () {
        var id = $(this).attr("data-id");
        $("#wait").show();
        let response = await segmentosService.changeState(id);
        $("#table_list_model").html(response);
        $("#wait").hide();
    });

    $("#table_list_model").on("click", ".btn_edit_per", async function () {
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
            let response = await segmentosService.update(data, data.id_periodo);
            if (response.errors) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });
            } else {
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