import { FormatosService } from './services/formatos_documentos.js';
import { ExpedientesService } from './services/expedientes.js';
import { ConciliacionService } from './services/conciliaciones.js';
import { ReferenciasService } from './services/referencias.js';
const conciliacionService = new ConciliacionService();
const formatosService = new FormatosService();
const expedientesService = new ExpedientesService();
const referenciasService = new ReferenciasService();
$(document).ready(function () {

    var summernote = $(".summernote");
    summernote.summernote({
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            //['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
        height: 527,
    });

    $("#myFormCreatePdfReporte").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm('myFormCreatePdfReporte');
        if (errors.length <= 0) {
            var request = serializeSummernotePdf(
                "myFormCreatePdfReporte",
                "summernote_store"
            );
            $("#wait").show();
            var response = await formatosService.storePdfReporte(request);
            toastr.success("Creado con éxito", "", {
                timeOut: "4000",
            });
            window.location.reload(true);
        } else {
            toastr.error("Hay campos que son obligatorios!", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }

    });

    $("#myFormEditPdfReporte select[name='categoria_id']").on("change", async function (params) {
        var categoria = $(this).val();
        if (categoria != '') {
            var request = {
                'categoria_id': categoria
            }
            $("#wait").show();
            let response = await conciliacionService.getReportesByCategory(request);
            $("#wait").hide();
            $("#summernote_update").summernote("code", "");
            $("#myFormEditPdfReporte input[name='nombre_reporte']").val("");
            $("#myFormEditPdfReporte select[name='categorianew_id']").val("");
            if (response.errors && response.errors.length > 0) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });
                $("#summernote_update").summernote("code", "");
                $("#myFormEditPdfReporte input[name='nombre_reporte']").val("");
                $("#myFormEditPdfReporte select[name='categorianew_id']").val("");
                $("#myFormEditPdfReporte select[name='id']").val("");
                $("#sel_reporte_id").html("<option value=''>Primero seleccione una categoria...</option>");
            } else {
                var option = '<option value="">Primero seleccione una categoria...</option>';
                response.forEach(element => {
                    option += `
              <option value="${element.id}">${element.nombre_reporte}</option>
              `;
                });
                $("#sel_reporte_id").html(option);
                toastr.success("Ahora puede seleccionar el formato", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
            }
        } else {
            $("#summernote_update").summernote("code", "");
            $("#myFormEditPdfReporte input[name='nombre_reporte']").val("");
            $("#myFormEditPdfReporte select[name='categorianew_id']").val("");
            $("#myFormEditPdfReporte select[name='id']").val("");
        }
    });


    $("#sel_reporte_id").on("change", async function () {
        var id = $(this).val();
        let res = await formatosService.editPdfReporte(id);
        $("#myFormEditPdfReporte input[name=nombre_reporte]").val(res.nombre_reporte);
        $("#myFormEditPdfReporte select[name=categoria_id]").val(res.categoria_id);
        $("#myFormEditPdfReporte select[name=categorianew_id]").val(res.categoria_id);
        if (res.configuraciones != null) {
            $("#myFormEditPdfReporte input[name=top]").val(res.configuraciones.top);
            $("#myFormEditPdfReporte input[name=right]").val(res.configuraciones.right);
            $("#myFormEditPdfReporte input[name=bottom]").val(res.configuraciones.bottom);
            $("#myFormEditPdfReporte input[name=left]").val(res.configuraciones.left);
            $("#myFormEditPdfReporte select[name=tipo_papel]").val(res.configuraciones.tipo_papel);
            if (res.files.length > 0) {
                res.files.forEach(file => {
                    if (file.pivot.seccion == 'encabezado') {
                        $("#myModal_configuraciones_formato_pdf_update #encab_img-update")
                            .attr("src", file.temp_path);
                        $("#myModal_configuraciones_formato_pdf_update select[name=encabezado_align]")
                            .val(file.pivot.configuracion.encabezado_align);
                        $("#myModal_configuraciones_formato_pdf_update input[name=encab_width]")
                            .val(file.pivot.configuracion.encab_width);
                        $("#myModal_configuraciones_formato_pdf_update input[name=encab_height]")
                            .val(file.pivot.configuracion.encab_height);
                    }
                    if (file.pivot.seccion == 'pie') {
                        $("#myModal_configuraciones_formato_pdf_update #pie_img-update")
                            .attr("src", file.temp_path);
                        $("#myModal_configuraciones_formato_pdf_update select[name=pie_align]")
                            .val(file.pivot.configuracion.pie_align);
                        $("#myModal_configuraciones_formato_pdf_update input[name=pie_width]")
                            .val(file.pivot.configuracion.pie_width);
                        $("#myModal_configuraciones_formato_pdf_update input[name=pie_height]")
                            .val(file.pivot.configuracion.pie_height);
                    }
                });

            } else {
                $("#myModal_configuraciones_formato_pdf_update #encab_img-update")
                    .attr("src", "");
                $("#myModal_configuraciones_formato_pdf_update select[name=encabezado_align]")
                    .val("");
                $("#myModal_configuraciones_formato_pdf_update input[name=encab_width]")
                    .val("");
                $("#myModal_configuraciones_formato_pdf_update input[name=encab_height]")
                    .val("");
                $("#myModal_configuraciones_formato_pdf_update #pie_img-update")
                    .attr("src", "");
                $("#myModal_configuraciones_formato_pdf_update select[name=pie_align]")
                    .val("");
                $("#myModal_configuraciones_formato_pdf_update input[name=pie_width]")
                    .val("");
                $("#myModal_configuraciones_formato_pdf_update input[name=pie_height]")
                    .val("");
            }
        } else {
            $("#myFormEditPdfReporte input[name=top]").val("1,27");
            $("#myFormEditPdfReporte input[name=right]").val("1,27");
            $("#myFormEditPdfReporte input[name=bottom]").val("1,27");
            $("#myFormEditPdfReporte input[name=left]").val("1,27");
        }

        $("#summernote_update").summernote("code", res.reporte);
        $("#wait").hide();
    });

    $("#myFormEditPdfReporte").on("submit", async function (e) {
        e.preventDefault();
        var request = serializeSummernotePdf(
            "myFormEditPdfReporte",
            "summernote_update"
        );
        var id = $("#myFormEditPdfReporte select[name=id]").val();
        if (id == undefined) id = $("#myFormEditPdfReporte input[name=id]").val();
        $("#wait").show();
        let response = await formatosService.updatePdfReporte(request, id);
        toastr.success("Actualizado con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        $("#wait").hide();
    });

    $(".btn_generate_pdf_preview").on("click", async function (e) {
        e.preventDefault();
        // alert(55)
        var myForm = $(this).attr("data-form");
        var mySummernote = $(this).attr("data-summernote");
        var request = serializeSummernotePdf(myForm, mySummernote);

        if (request) {
            $("#wait").show();
            let response = await formatosService.createPdfPreview(request);
            var a = document.createElement("a");
            a.target = "_blank";
            a.href = response.url;
            a.click();
            $("#wait").hide();
        }

        // if (request) createPdfPreview(request);
    });

    $(".selec_confi_av").on("click", function (e) {
        var modal = $(this).attr('data-modal');
        $("#" + modal).modal('show');
    });

    $("#btnGuardarPdfTemp").on("click", async function (e) {
         //alert('Falta guardar') 
        var request = serializeSummernotePdf(
            "myFormEditPdfReporte",
            "summernote_update"
        );
        var id = $("#myFormEditPdfReporte select[name=id]").val();
        if (id == undefined) id = $("#myFormEditPdfReporte input[name=id]").val();
        if (request) {
            console.log(request);
            $("#wait").show();
            let response = await conciliacionService.updateConPdfTemporal(request, id);
            $("#wait").hide();
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });


        }
        e.preventDefault();
    });

    $("#btnCancelPdfTemp").on("click", function (e) {
        window.close();
        $("#bgtransparent").remove();
    });

    $("#myFormAsigReporte select[name='tabla_destino']").on("change", async function (params) {
        var categoria = $(this).val();
        if (categoria != '') {
            var request = {
                'categoria_id': categoria,
            }
            let response = await conciliacionService.getReportesByCategory(request);
            if (response.errors && response.errors.length > 0) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });

            } else {
                var li = '';
                response.forEach(reporte => {
                    li += `
              <li>
                <input class="checks_reportes" type="${categoria == 241 ? 'radio' : 'checkbox'}" id="chk_reporte_${reporte.id}" value="${reporte.id}" name="reporte_id[]" >
                 ${reporte.nombre_reporte}
              </li>
              `;
                });
                $("#checks_reportes").html(li);
                $("#myFormAsigReporte select[name='status_id']").prop("disabled", false).show().val("");
                $("#myFormAsigReporte select[name='status_id']").prev().show();
                $("#myFormAsigReporte select[name='categoria']").show().prop("disabled", false).val("");
                $("#myFormAsigReporte select[name='categoria']").prev().show()
                if (categoria == 241) {
                    $("#myFormAsigReporte select[name='status_id']").prop("disabled", true).hide();
                    $("#myFormAsigReporte select[name='status_id']").prev().hide();
                }
                if (categoria == '226' || categoria == 227) {
                    $("#myFormAsigReporte select[name='categoria']").hide().prop("disabled", true);
                    $("#myFormAsigReporte select[name='categoria']").prev().hide();
                }
            }
        }
    });
    $(".select_values").on("change", function (e) {
        $(".content_values_" + $(this).attr('data-view')).hide();
        $("#" + $(this).val()).show()
    });

    $(".content_values_update").on("click", "#btn_create_category", async function (e) {
        var request = {
            'conciliacion_id': $("#conciliacion_id").val(),
            'tabla_destino': 'conciliaciones_email',
            'status_id': 178
        }
        let response = await formatosService.getReportes(request);
        $("#myModal_create_category_report").modal("show")
    });
    $(".content_values_store").on("click", "#btn_create_category", async function (e) {
        var request = {
            'conciliacion_id': $("#conciliacion_id").val(),
            'tabla_destino': 'conciliaciones_email',
            'status_id': 178
        }
        let response = await formatosService.getReportes(request);
        $("#myModal_create_category_report").modal("show")
    });

    $("#myModal_create_category_report input[name='name']").on('keyup', function (e) {
        var cadena = $(this).val();
        var minusculas = cadena.toLowerCase();
        var espacios = minusculas.replace(/\s+/g, "_");
        var final = espacios.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        // var final = "[-"+sin_tildes+"-]";
        $("#myModal_create_category_report input[name='short_name']").val(final)

    });

    $("#myformCreateCategoryReport").on("submit", function (e) {
        var request = convertFormToJSON('myformCreateCategoryReport');

        let response = referenciasService.storeFromReports(request)
        e.preventDefault()
    });

    $("#myFormAsigReporte").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm('myFormAsigReporte');
        if (errors.length <= 0) {
            var request = convertFormToJSON("myFormAsigReporte");
            $("#wait").show();
            let response = await formatosService.asignarReporte(request);
            toastr.success("Asignado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            $("#wait").hide();
        } else {
            toastr.error("Hay campos", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }


    });


    $("#myFormAsigReporte .buscar_asignacion_re").on("change", function (e) {
        var tabla_destino = $("#myFormAsigReporte select[name=tabla_destino]").val();
        // var clave = $(this).attr("name");
        var status_id = $("#myFormAsigReporte select[name=status_id]").val();
        if (tabla_destino != "") {
            var request = {
                tabla_destino: tabla_destino,
                status_id: (status_id == null || status_id == "") ? 1 : status_id

            };
            // request[clave] =  status_id;
            if (tabla_destino == "241") {//Formato predefinidos
                var categoria = $("#myFormAsigReporte select[name=categoria]").val();
                if (categoria != "") {
                    request['categoria'] = categoria;
                    editAsignacionReporte(request);
                }
            } else {
                editAsignacionReporte(request);
            }

        }
    });

});

function serializeSummernotePdf(myForm, mySummernote) {
    var formatVal = $("#" + mySummernote)
        .summernote("code")
        .trim();
    $("#" + myForm + " input[name=reporte]").val('');
    $("#" + myForm + " input[name=reporte]").val(formatVal);
    var items_ = [];
    $("#report_keys").val("");
    if (formatVal != "") {
        $("#" + myForm + " .note-editable .item_sp").each((index, element) => {
            var it = $(element).attr("user-type");
            var dtn = $(element).attr("data-name");
            items_[index] = {
                user_type: it,
                name: dtn,
                table: $(element).attr("data-table"),
                short_name: $(element).attr("data-short_name"),
            };
            //  $(element).css('border','1px solid red')

        });

        var json = JSON.stringify(items_);
        $("#" + myForm + " input[name=report_keys]").val(json);
        return (new FormData(document.getElementById(myForm)));// $(myForm).serialize());
    } else {
        Swal.fire({
            title: "El formato no puede estar vacío",
            icon: "warning",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Aceptar",
        });
    }
    return false;
}

$(".item_con").on("mousedown", function (e) {
    var space = "&nbsp;";
    var mySummernote = $(this).attr("data-summernote");
    var clasehechopre = '';
    var salto = '';
    if ($(this).attr("user-type") == 'hepr') clasehechopre = 'hecho_pret'; salto = '<br>'
    $("#" + mySummernote).summernote(
        "pasteHTML",
        `<span data-table="${$(this).attr(
            "data-table"
        )}" data-short_name="${$(this).attr(
            "data-short_name"
        )}" user-type="${$(this).attr("user-type")}" data-name="[-${$(
            this
        ).attr("data-name")}-]" class="item_sp ${clasehechopre}">[-` +
        $(this).attr("data-name") +
        `-]</span>${space}`
    );

    //  $('.note-editable').trigger('focus');
    //  summernote.summernote('focus');
    //$(".note-editable p").focus()
    //  $(".item_sp").prop('disabled',true).css('color','blue')
    //document.getElementById("dcalc").disabled = true;
});

function editAsignacionReporte(request) {
    var route = "/pdf/reportes/editar/asignacion";
    $.ajax({
        url: route,
        type: "GET",
        datatype: "json",
        data: request,
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
            $("#wait").show();
        },
        /*muestra div con mensaje de 'regristrado'*/
        success: function (response) {
            $(".checks_reportes").prop("checked", false);
            response.forEach((element) => {
                $("#chk_reporte_" + element.reporte_id).prop("checked", true);
            });

            $("#wait").hide();
        },
        error: function (xhr, textStatus, thrownError) {
            /* alert(
                "Hubo un error con el servidor ERROR::" + thrownError,
                textStatus
            ); */
            $("#wait").hide();
        },
    });
}