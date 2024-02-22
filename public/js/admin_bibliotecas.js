import { BibliotecasService } from './services/bibliotecas.js';

const bibliotecasService = new BibliotecasService();


$(document).ready(function () {

    $("#myformCreateBiblioteca").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myformCreateBiblioteca");
        if (errors.length <= 0) {
            var input = document.getElementById("doc_file");
            if (input.files && input.files.length >= 1) {
                var request = new FormData(document.getElementById("myformCreateBiblioteca"));
                try {
                    $("#loader-container").show().css({ 'display': 'flex' })
                    $("#wait").show();
                    const result = await bibliotecasService.store(request)
                        .then((response) => {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: "Creado con éxito!",
                                showConfirmButton: false,
                                timer: 2500
                            });
                            e.preventDefault()
                            $("#wait").hide();
                        })
                        .catch((error) => {
                            Swal.fire({
                                position: 'top-end',
                                type: 'error',
                                title: 'Ups! Algo fallo',
                                html: error,
                                showConfirmButton: false,
                                timer: 5500
                            });
                            console.error('Error al cargar el archivo:', error);
                            $("#wait").hide();
                            e.preventDefault()
                        });
                } catch (error) {
                    // Manejar el error
                    $("#wait").hide();
                    console.error(error);
                    e.preventDefault()
                } finally {
                    // Restablecer el estado de la barra de progreso
                    const result = expedientesService.showProgress(0)
                    const progressDiv = document.getElementById('progressbarwait');
                    $(progressDiv).hide();
                    $("#wait").hide();
                    e.preventDefault();
                }
                $("#wait").hide();
            } else {
                showElement("label-alert", "class");
            }
        } else {
            toastr.success("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-center",
                timeOut: "4000",
            });
        }
    });

    $(".btn_buscar_biblioteca").on("click", async function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#wait").show();
        let response = await bibliotecasService.edit(id);
        llenarFormEditBiblio(response);
        llenarTablaDetails(response);
        $("#wait").hide();
        hideElement("label-alert", "class");

        console.log(id);
    });

    $("#myModal_act_edit_doc").on("submit", "#myformUpdateBiblioteca", async function (e) {
        e.preventDefault();
        var errors = validateForm("myformUpdateBiblioteca");
        if (errors.length <= 0) {
            var request = new FormData(document.getElementById("myformUpdateBiblioteca"));
            try {
                $("#loader-container").show().css({ 'display': 'flex' })
                $("#wait").show();
                const result = await bibliotecasService.update(request)
                    .then((response) => {
                        Swal.fire({
                            position: 'top-end',
                            type: 'success',
                            title: "Actualizado con éxito!",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        e.preventDefault()
                        window.location.reload();
                    })
                    .catch((error) => {
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Ups! Algo fallo',
                            html: error,
                            showConfirmButton: false,
                            timer: 5500
                        });
                        console.error('Error al cargar el archivo:', error);
                        $("#wait").hide();
                        e.preventDefault()
                    });
            } catch (error) {
                // Manejar el error
                $("#wait").hide();
                console.error(error);
                e.preventDefault()
            } finally {
                // Restablecer el estado de la barra de progreso
                const result = expedientesService.showProgress(0)
                const progressDiv = document.getElementById('progressbarwait');
                $(progressDiv).hide();
                $("#wait").hide();
                e.preventDefault();
            }
            $("#wait").hide();

        } else {
            toastr.success("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-center",
                timeOut: "4000",
            });
        }
    });

    $(".btn_delete_biblioteca").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        Swal.fire({
            title: '¿Esta seguro de eliminar el documento?',
            type: 'warning',
            showCancelButton: true,
            /* confirmButtonColor: '#3085d6', */
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {           
                var url = "bibliotecas/change/" + id;
                window.location.href = url;
                console.log(a);                 
            }
        }); 
    })


  


});

function llenarFormEditBiblio(data) {
    $("#biblinombre").val(data.biblinombre);
    $("#bibliid_ramaderecho_id").val(data.bibliid_ramaderecho);
    $("#bibliid_tipoarchivo_id").val(data.bibliid_tipoarchivo);
    $("#biblidescrip").val(data.biblidescrip);
    $("#biblioteca_id").val(data.id);
    $("#lab_doc_file i").text(data.biblidocnompropio);
    $("#link_doc").attr("href", "/bibliotecas/pdf/" + data.id);
    $("#myformUpdateBiblioteca #doc_file").prop("required", false);
    //$("#biblinombre").val(data.biblinombre);
}
function llenarTablaDetails(data) {
    var tamanio = "";
    if (data.biblidoctamano / 1024 >= 1000) {
        tamanio = (data.biblidoctamano / 1024 / 1024).toFixed(2) + " Mb";
    } else {
        tamanio = (data.biblidoctamano / 1024).toFixed(0) + " Kb";
    }
    var texto = data.biblidescrip.replace(/\n/g, "<br />");
    $("#label_biblinombre").text(data.biblinombre);
    $("#label_user_create").text(data.user.name + " " + data.user.lastname);
    $("#label_biblidocnompropio").text(data.biblidocnompropio);
    $("#label_biblidoctamano").text(tamanio);
    $("#label_bibliuserupdated").text(data.user_update.name);

    $("#label_bibliid_ramaderecho").text(data.rama_derecho.ramadernombre);
    $("#label_bibliid_tipoarchivo").text(data.categoria.tiparchinombre);
    $("#label_biblidescrip").html(texto);
}