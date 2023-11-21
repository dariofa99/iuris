import { ReferenciasService } from "./services/referencias.js";
const referenciasService = new ReferenciasService();
var items_delete = [];
const dataSections = {
    'conciliaciones': [
        { id: 'parte_solicitante', value: 'Parte solicitante' },
        { id: 'rep_legal_solicitante', value: 'Representante legal - solicitante' },
        { id: 'apoderado_solicitante', value: 'Apoderado de la parte solicitante' },
        { id: 'parte_solicitada', value: 'Parte solicitada' },
        { id: 'rep_legal_solicitada', value: 'Representante legal - solicitada' },
        { id: 'elementos_juridicos', value: 'Elementos jurídicos' },
        { id: 'asunto', value: 'Asunto' }
    ],
    'users': [
        { id: 'datos_personales', value: 'Datos personales' },
        { id: 'enfoque_diferencial', value: 'Enfoque diferencial' },
        { id: 'discapacidad', value: 'Discapacidad' },
        { id: 'socio_economica', value: 'Inf. Socio-económica' },
        { id: 'grupo_etnico', value: 'Grupo etnico' }
    ]
};

$(document).ready(function () {
    $("#btn_new_category").on("click", function (e) {
        $("#myformEditRCategory").attr("id", "myformCreateCategory");
        $("#myformCreateCategory")[0].reset();
        $("#aditional_options_table tbody").html("");
        $("#content_aditional_options").hide();
        $("#myformCreateCategory button[type=submit]")
            .text("Guardar")
            .removeClass("btn-warning")
            .addClass("btn-primary");
        //$(".select2").select2();
        $("#lbl_modal_title").text("Creando categoria");
        $("#myModal_create_category input[name='short_name']").prop('readonly', true);
        $("#myModal_create_category").modal("show");
    });

    $("#myformCreateCategory select[name=table]").on("change", function (e) {
        var options = '<option value="">Seleccione...</option> ';
        $("#myModal_create_category select[name='section']").prop('disabled', true);
        $("#content_section").hide();
        if ($(this).val() == "users") {
            dataSections.users.forEach(element => {
                options += `<option value="${element.id}">${element.value}</option>`;
            });
            $("#myModal_create_category select[name='section']").prop('disabled', false);
            $("#content_section").show();
        } else if ($(this).val() == "conciliaciones") {
            dataSections.conciliaciones.forEach(element => {
                options += `<option value="${element.id}">${element.value}</option>`;
            });
            $("#myModal_create_category select[name='section']").prop('disabled', false);
            $("#content_section").show();
        }
        $("#myModal_create_category select[name='section']").html(options);

    });

    $("#myformCreateCategory select[name=type_data_id]").on("change", function (e) {
        if ($(this).val() == "169" || $(this).val() == "170") {
            var item = $(".option_row").length;
            if (item == 0) {
                addOptionTable(item);
            }
            $("#content_aditional_options").show();
            $("#content_aditional_options input").prop("disabled", false);
        } else {
            $("#content_aditional_options").hide();
            $("#content_aditional_options input").prop("disabled", true);
        }
    });

    $("#myModal_create_category input[name='name']").on('keyup', function (e) {
        var cadena = $(this).val();
        var minusculas = cadena.toLowerCase();
        var espacios = minusculas.replace(/\s+/g, "_");
        var final = espacios.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        $("#myModal_create_category input[name='short_name']").val(final);

    });

    $("#myModal_create_category").on("submit", "#myformCreateCategory", async function (e) {
        e.preventDefault()
        $("#wait").show();
        var request = convertFormToJSON('myformCreateCategory');
        let response = await referenciasService.storeReferencesData(request)
        Toast.fire({
            title: "Categoría creada con éxito.",
            icon: "success",
            timer: 2000,
        });
        $("#myModal_create_category").modal("hide");
        $("#content_categories_list").html(response.render_view);
        $("#wait").hide();
    });

    
    $("#content_aditional_options").on("click",".chk_active_other_input",  function (e) {
            var item = $(this).attr("id").split("-")[1];
            if ($(this).is(":checked")) {
                $("#active_other_input-" + item).val(1);
            } else {
                $("#active_other_input-" + item).val(0);
            }
        });
    $(".btn_add_field").on("click", function (e) {
        var item = $(".option_row").length;
        addOptionTable(item);
    });
    $("#content_aditional_options").on("click", ".btn_delete_option_row", function (e) {
        var older_row = $(this).attr("data-item");
        items_delete.push({ id: $(this).attr("data-id") });
        $("#option_row-" + older_row).remove();
        $(".option_row").each((row, obj) => {
            var current_row = $(obj).attr("data-item") - 1;
            if (current_row == older_row) {
                $(obj).attr("data-item", older_row);
                $(obj).attr("id", "option_row-" + older_row);
                $(obj).find("button").attr("data-item", older_row);
                $(obj).find("button").attr("id", "option_row-" + older_row);
                older_row = parseInt(older_row) + 1;
                $(obj).find("input[id=active_other_input-" + older_row + "]")
                    .attr("id", "active_other_input-" + current_row);
                $(obj).find("input[id=active-" + older_row + "]")
                    .attr("id", "active-" + current_row);
            }
        });
        if (older_row <= 0) {
            $("#chk_add_option").prop("checked", false);
            $(".adoptions").hide();
            $("#type_data_id").val(26);
        }
    });





























    $("#btn_new_static_category").on("click", function (e) {
        $("#myformEditRStaticCategory").attr("id", "myformCreateStaticCategory");
        $("#myformCreateStaticCategory")[0].reset();
        $("#aditional_options_table tbody").html("");
        $("#content_aditional_options").hide();
        $("#myformCreateStaticCategory button[type=submit]")
            .text("Guardar")
            .removeClass("btn-warning")
            .addClass("btn-primary");
        $("#lbl_modal_title").text("Creando categoria");
        $("#myModal_create_static_category").modal("show");
    });


    $("#myModal_create_static_category input[name='display_name']").on('keyup', function (e) {
        var cadena = $(this).val();
        var minusculas = cadena.toLowerCase();
        var espacios = minusculas.replace(/\s+/g, "_");
        var final = espacios.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        // var final = "[-"+sin_tildes+"-]";
        $("#myModal_create_static_category input[name='name']").val(final)

    });

    $("#myModal_create_static_category").on(
        "submit",
        "#myformCreateStaticCategory",
        async function (e) {
            e.preventDefault();
            $("#wait").show();
            var request = convertFormToJSON('myformCreateStaticCategory');
            let response = await referenciasService.storeStatic(request)
            Toast.fire({
                title: "Categoria creada con éxito.",
                type: "success",
                timer: 2000,
            });
            $("#myModal_create_static_category").modal("hide");
            $("#content_categories_list").html(response.render_view);
            $("#wait").hide();

        }
    );




});

function addOptionTable(item) {
    var row = `<tr class="option_row" data-item="${item}" id="option_row-${item}">
                <td>
                    <input  type="text" required name="option_name[]" class="form-control form-control-sm">
                </td>
                <td>
                    <input type="hidden" id="active_other_input-${item}" name="active_other_input[]" value="0">
                    <input type="hidden"  name="options_id[]" value="null">
                    <input type="checkbox" id="active-${item}" class="chk_active_other_input" data-size="xs" data-style="order-check" data-width="60" data-toggle="toggle" data-on="1" data-off="0" data-onstyle="primary" data-offstyle="warning">
                </td>
                <td>
                    <button type="button" id="btn_delete_option_row-${item}" data-item="${item}" class="btn btn-danger btn-sm btn_delete_option_row">
                    <i class="fa fa-times"></i></button>
                </td>               
            </tr>`;
    $("#aditional_options_table tbody").append(row);
}