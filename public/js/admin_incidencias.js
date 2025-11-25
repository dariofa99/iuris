import { IncidenciasService } from "./services/incidencias.js";
const incidenciasService = new IncidenciasService();
$(document).ready(function () {
    document.getElementById('archivo').addEventListener('change', function (e) {
        const file = e.target.files[0];

        if (file) {
            document.getElementById('archivo_label').innerText = file.name;
            document.getElementById('archivo_nombre').innerText = file.name;
            document.getElementById('preview').classList.remove('d-none');
        }
    });

    $("#check_file").on("click", function () {
        $("#archivo").click();
    });
    $("#btn_notificar_incidente").on("click", async function (e) {
        e.preventDefault();
        $("#myTab").hide();
        $("#myModal_notificar_incidencia").modal("show");
    });


    $("textarea[name='motivo']").on("keyup", function () {

        var charCount = $(this).val().length;
        $(".char_count").text(charCount + "/200");
    });

    /*  $("#form_incidencia").on("submit", async function (e) {
         e.preventDefault();
         var expid = $("#expid").val();
 
         var formData = new FormData(document.getElementById("form_incidencia"));
         var res = await incidenciasService.store(formData);
         $("#wait").show()
         toastr.success("Creado con éxito", "", {
             positionClass: "toast-top-right",
             timeOut: "4000",
         });
         location = "/incidencias/";
 
     }); */

    $("#form_incidencia").on("submit", async function (e) {
        e.preventDefault();
        var formData = new FormData(document.getElementById("form_incidencia"));
        formData.append('id_asig', $("#id_asig").val());
        var res = await incidenciasService.store(formData);
        toastr.success("Incidencia creada con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        $("#myModal_notificar_incidencia").modal("hide");
        location.reload();

    });

    $("#form_act_incidencia").on("submit", async function (e) {
        e.preventDefault();

        var request = convertFormToJSON("form_act_incidencia")
        request['id_asig'] = $("#id_asig").val();
        $("#wait").show()
        var res = await incidenciasService.update(request)
        $("#wait").hide()
        toastr.success("Creado con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        location.reload(true);

    });



    $("#tbl_incidencias").on("click", '.btn_inmostradetalles', function (e) {
        e.preventDefault()

        const id = $(this).data("id");
        const detalle = $("#deta-" + id);
        const icon = $(this).find("i");

        if (!detalle.is(":visible")) {
            //$(".row-incidencia").hide()
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
            $("#row-incidencia-" + id).show();
            detalle.show();

        } else {
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
            $(".row-incidencia").show()
            detalle.hide();
        }

    });

    $(".btn_act_incidencia").on("click", async function (e) {
        e.preventDefault();
        //Remover inputs previos
        $("#myModal_actualizar_incidencia form input[name='is_update']").remove();
        $("#myModal_actualizar_incidencia form input[name='hestado_id']").remove();
        $("#myModal_actualizar_incidencia input[name='estado_id']").val($(this).data("estado"))
        $("#myModal_actualizar_incidencia input[name='id']").val($(this).data("id"))
        if ($(this).data("estado") == 273) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, resolver',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    // Aquí puedes agregar la lógica para eliminar la incidencia
                    var request = convertFormToJSON("form_act_incidencia")
                    request['motivo'] = "Incidencia resuelta";
                    var res = await incidenciasService.update(request)
                    $("#wait").hide()
                    toastr.success("Creado con éxito", "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                }
            });
        } else if ($(this).data("estado") == 'update') {
            var old_motivo = $("#old_motivo-" + $(this).data("id")).val();
            $("#myModal_actualizar_incidencia textarea[name='motivo']").val(old_motivo);
            $(".char_count").text(old_motivo.length + "/200");
            var input = `<input type="hidden" name="is_update" value="true">`;
            var inputId = `<input type="hidden" name="hestado_id" value="${$(this).data("id")}">`;
            $("#myModal_actualizar_incidencia form").append(input);
            $("#myModal_actualizar_incidencia form").append(inputId);

            $("#myModal_actualizar_incidencia").modal("show");
        } else if ($(this).data("estado") == 272) {
            $("#myModal_actualizar_incidencia").modal("show");
        } else {
            $("#myModal_actualizar_incidencia").modal("show");
        }

    })
});