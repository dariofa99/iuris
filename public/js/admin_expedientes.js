import { UserService } from './services/users.js';
import { ExpedientesService } from './services/expedientes.js';
const userService = new UserService();
const expedientesService = new ExpedientesService();
$(document).ready(function () {
    if ($("#expediente_id").val() != undefined) {
        $(":input").inputmask();
        set_tab();
    }
    $("#search_onlyMy_exp").on("change", async function () {
        buscarExp()
    });

    $("#content_count_asesorias_inlist").on("click", ".btn_search_color", async function (e) {
        var request = {
            tipo_busqueda: "color",
            data: $(this).attr("id")
        };

        if ($("#search_onlyMy_exp").is(":checked")) {
            request['search_onlyMy_exp'] = 'search_onlyMy_exp';
        }
        if ($("#search_onlyProJur").is(":checked")) {
            request['search_onlyProJur'] = 'search_onlyProJur';
        }
        $("#wait").show();
        var page = "expedientes";
        console.log(page);
        let res = await index_page(page, request);
        $("#wait").hide();
    });

    async function buscarExp() {
        var request = {}//convertFormToJSON('myformExpFilter');
        request['search_onlyMy_exp'] = 'off';
        if ($("#search_onlyMy_exp").is(":checked")) {
            request['search_onlyMy_exp'] = 'search_onlyMy_exp';
        }
        if ($("#search_onlyProJur").is(":checked")) {
            request['search_onlyProJur'] = 'search_onlyProJur';
        }
        var opselected = $("#myformExpFilter select[name='tipo_busqueda']").val();
        var dataselected = $("#myformExpFilter select[name='data']").val();;
        var fechaselected = $("#myformExpFilter input[id='data_date']").val();;
        if (fechaselected != '' && fechaselected != null) request['data'] = fechaselected;
        if (opselected != '' && opselected != null) request['tipo_busqueda'] = opselected;
        if (dataselected != '' && dataselected != null) request['data'] = dataselected;
        $("#wait").show();
        var page = "expedientes";
        console.log(page);
        let res = await index_page(page, request);
        $("#wait").hide();
    }
    $("#search_onlyProJur").on("change", async function () {
        buscarExp()
    });

    $("#btnCancelar").click(function () {
        $("#btnActualizar").hide();
        $("#btnCancelar").hide();
        $("#btnEditar").show();
        $(".disabled").prop('disabled', true);
        $(".disabled-fun3").prop("disabled", true);
        $(".disabled-fun3").selectpicker("refresh");
    });


    $("#btn_asig_exp_doc").on("click", async function (e) {
        e.preventDefault();
        $("#titulo_modal").text("Asignando docente");
        $("#myform_change_docente_exp>#tipo_cambio").val(4);
        $("#myform_change_docente_exp input[type='submit']").val("Asignar docente");
        var name = $(this).attr("data-name");
        var lastname = $(this).attr("data-lastname");
        var idnumber = $(this).attr("data-idnumber");
        var option =
            '<option value="' +
            idnumber +
            '">' +
            name.toUpperCase() +
            " " +
            lastname.toUpperCase() +
            "</option>";
        let response = await userService.getUsersByRole({ 'role': 'docente', 'active': 1 });
        abrirModalDocentes(response.users, option);
    });

    $("#btnActualizar").on("click", async function (e) {
        var errors = validateForm("form_expediente_edit");
        if (errors.length <= 0) {
            var form = convertFormToJSON("form_expediente_edit");
            var request = {
                'id': form.expediente_id,
                'expramaderecho_id': form.expramaderecho_id,
                'exptipoproce_id': form.exptipoproce_id,
                'expidnumberest': form.expidnumberest,
                'oldexpidnumberest': form.oldexpidnumberest
            }
            $("#wait").show()
            let response = await expedientesService.update(request, form.expediente_id);
            toastr.success("Se actalizó con éxito", "", {
                timeOut: "4000",
            });
            window.location.reload(true);
        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                timeOut: "4000",
            });
        }

    });
    $("#btnEditar").click(function () {
        $("#btnActualizar").show();
        $("#btnCancelar").show();
        $("#btnEditar").hide();
        $(".disabled").prop("disabled", false);
        $(".disabled-fun3").prop("disabled", false);
        $(".disabled-fun3").selectpicker("refresh");
        if ($("#oldexpidnumberest").val() == "") {
            $("#oldexpidnumberest").val($("#expidnumberest").val());
        }
    });

    $("#btn_nueva_cita").on("click", function (e) {
        e.preventDefault();
        $("#mymodalNuevaCitacion").modal("show");
    });

    $('#table_list_model').on('click', "a.btn-edit-le", function () {
        var url = window.location;
        // Check browser support
        if (typeof (Storage) !== "undefined") {
            // Store
            localStorage.setItem("dirreg", url);
            // Retrieve
        }
    });
    $("a.btn-atrasexed").click(function () {
        window.location.href = localStorage.getItem("dirreg");
    });
    $("#myformExpFilter").submit(async function (e) {
        e.preventDefault();
        var errors = validateForm("myformExpFilter");
        if (errors.length <= 0) {
            var data = convertFormToJSON("myformExpFilter");
            if (data.search_onlyMy_exp === undefined || data.search_onlyMy_exp === null) {
                data['search_onlyMy_exp'] = 'off'
            }
            if ($("#search_onlyProJur").is(":checked")) {
                data['search_onlyProJur'] = 'search_onlyProJur';
            }
            var page = "expedientes";
            $("#wait").show();
            let res = await index_page(page, data);
            $("#wait").hide();
            /* 
            index_page(page, data);
            window.history.pushState(null, "", page + "?" + data);
             *///return false;
        }
        return false;
    });
    $("#myFormBsExpAdv").submit(async function () {
        var errors = validateForm("myFormBsExpAdv");
        if (errors.length <= 0) {
            var page = "expedientes";
            var data = $(this).serialize();
            let res = await index_page(page, data);
            window.history.pushState(null, "", page + "?" + data);
            $("#mymodalBuscarExpAvanzadas").modal("hide")
            //return false;
        }
        return false;
    });

    $("#btn_act_pausa_exp").on("click", function (e) {
        e.preventDefault();
        $("#mymodalPausarExpediente").modal("show")
    });
    $("#btn_quit_pausa_exp").on("click", async function (e) {
        e.preventDefault();
        var request = {
            'expediente_id': $("#expediente_id").val()
        }
        $("#wait").show();
        let response = await expedientesService.getPausasExpediente(request);
        if (response.length > 0) {
            var tr = '';
            response.forEach((element, key) => {
                tr += `
                <tr>
                    <td>
                        ${key + 1}
                    </td>
                    <td>
                        ${element.fecha_initxt}
                    </td>
                    <td>
                    <input type="hidden" value="${element.fecha_final}" data-id="${element.id}" id="fecha_final-${element.id}" name="fecha_final" class="form-control form-control-sm" >
                      <span id="lbl-${element.id}"> ${element.fecha_fintxt} </span>
                    </td>
                    <td width="5%">
                        <button aria-label="Editar pausa" title="Editar pausa" data-id="${element.id}" id="btn_edit_pausa-${element.id}" class="btn btn-sm btn-block btn-primary btn_edit_pausa">
                            <i class="fa fa-edit"></i>
                            <span class="sr-only">Editar pausa</span>
                        </button>
                        <button aria-label="Eliminar pausa" title="Eliminar pausa" data-id="${element.id}" id="btn_delete_pausa-${element.id}" class="btn btn-sm btn-block btn-danger btn_delete_pausa">
                            <i class="fa fa-trash"></i>
                            <span class="sr-only">Eliminar pausa</span>
                        </button>
                        <button aria-label="Actualizar pausa" title="Actualizar pausa" style="display:none" id="btn_update_pausa-${element.id}" data-id="${element.id}" class="btn btn-sm btn-block btn-success btn_update_pausa">
                            <i class="fa fa-check-square"></i>
                            <span class="sr-only">Actualizar pausa</span>
                        </button>
                        <button aria-label="Cancelar" title="Cancelar" style="display:none" id="btn_cancel_pausa-${element.id}" data-id="${element.id}" class="btn btn-sm btn-block btn-default btn_cancel_pausa">
                            <i class="fa fa-minus"></i>
                            <span class="sr-only">Cancelar</span>
                        </button>

                    </td>
                </tr>`;
            });
            $("#tblListPausasExp tbody").html(tr);
        } else {
            $("#tblListPausasExp tbody").html("<tr><td>No hay datos</td></tr>");
        }
        $("#wait").hide();
        $("#mymodalPausasExpediente").modal("show")
    });

    $("#tblListPausasExp").on("click", ".btn_edit_pausa", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#fecha_final-" + id).attr("type", 'date');
        $("#btn_edit_pausa-" + id).hide();
        $("#lbl-" + id).hide();
        $("#btn_delete_pausa-" + id).hide();
        $("#btn_update_pausa-" + id).show();
        $("#btn_cancel_pausa-" + id).show();
    });
    $("#tblListPausasExp").on("click", ".btn_cancel_pausa", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#fecha_final-" + id).attr("type", 'hidden');
        $("#lbl-" + id).show();
        $("#btn_delete_pausa-" + id).show();
        $("#btn_edit_pausa-" + id).show();
        $("#btn_update_pausa-" + id).hide();
        $("#btn_cancel_pausa-" + id).hide();
    });

    $("#tblListPausasExp").on("click", ".btn_update_pausa", async function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        $("#wait").show();
        var request = {
            'expediente_id': $("#expediente_id").val(),
            'pausa_id': id,
            'fecha_final': $("#fecha_final-" + id).val()
        }
        let response = await expedientesService.updatePausa(id, request);
        toastr.success("Actualizado con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        $("#mymodalPausasExpediente").modal("hide")
        window.location.reload(true);
    });

    $("#tblListPausasExp").on("click", ".btn_delete_pausa", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        Swal.fire({
            title: 'Esta seguro de eliminar la pausa?',
            text: "Se abrirá nuevamente el caso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                var request = {
                    'expediente_id': $("#expediente_id").val()
                }
                let response = await expedientesService.deletePausa(id, request);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true);
            }
        });


    })
    $("#myformPausarExpediente").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm('myformPausarExpediente');
        if (errors.length <= 0) {
            var request = convertFormToJSON("myformPausarExpediente");
            request['expediente_id'] = $("#expediente_id").val();
            $("#wait").show();
            let response = await expedientesService.pausarExpediente(request);
            if (response) {
                toastr.success("Se actualizó con éxito", "", {
                    timeOut: "4000",
                });
                window.location.reload(true);
            }
        }
    });

    $("#exptipoproce_id2").change(async function () {
        var idconsul = $("#exptipoproce_id2").val();
        var optionest = "";
        if (idconsul >= 1) {
            var prsimpes = 0;
            var prconplejas = 0;
            var colortext = "\'label label-danger \'";
            var colortext2 = "\'label label-danger \'";

            $("#wait").css("display", "block");
            let response = await expedientesService.getStudentsCases(idconsul);
            if (response.error) {
                toastr.error('A ocurrido un error: ' + response.error, 'Error',
                    { "positionClass": "toast-top-right", "timeOut": "50000" });
            } else {
                if (response == "") {
                    $(".estselect1").selectpicker("destroy");//refresca el select
                    $("#expidnumberest").append('<option value="000000" data-content="<span class=\'label label-danger \'>ERROR AL CARGAR LOS DATOS</span> ">ERROR AL CARGAR LOS DATOS</option>');//coloca una nueva opcion

                    $("#wait").css("display", "none");
                } else {
                    $("#expidnumberest").find('option').remove().end();//elimina opciones existentes
                    var optionest = '';
                    $(response).each(function (key, value) {
                        if (value.simples != 0) {
                            prsimpes = (value.simples_cerradas * 100) / value.simples;
                        }
                        if (value.complejas != 0) {
                            prconplejas = (value.complejas_cerradas * 100) / value.complejas;
                        }
                        if (prsimpes < 40) {
                            colortext = "\'label label-success \'";
                        }
                        if (prsimpes >= 40 && prsimpes <= 60) {
                            colortext = "\' label label-warning \'";
                        }
                        if (prsimpes > 60) {
                            colortext = "\' label label-danger \'";
                        }
                        if (prconplejas < 40) {
                            colortext2 = "\' label label-success \'";
                        }
                        if (prconplejas >= 40 && prconplejas <= 60) {
                            colortext2 = "\' label label-warning \'";
                        }
                        if (prconplejas > 60) {
                            colortext2 = "\' label label-danger \'";
                        }
                        var nombre_com = value.name + ' ' + value.lastname;
                        optionest += '<option value="' + value.astid_estudent + '" data-content="' + nombre_com.toUpperCase() + ' <span class=' + colortext + '>A.' + value.simples + '</span> <span class=' + colortext2 + '>S.' + value.complejas + '</span>">' + value.name + ' ' + value.lastname + '</option>';

                    });

                    $("#expidnumberest").attr('title', 'Seleccione un estudiante')
                    //$("#expidnumberest").append('<option value="0000000" data-content="luis carlos <span class=\'label label-danger \'>S.11</span> ">luis carlos</option>');//coloca una nueva opcion
                    $("#expidnumberest").append(optionest);//coloca una nueva opcion
                    //$('#contencalendarid').append('<tr><td>'+parseInt(key+1)+'</td><td>'+value.name+' '+value.lastname+'</td><td>'+textcurso+'</td></tr>');
                    $(".estselect1").selectpicker("refresh");//refresca el select
                }
            }
            $("#wait").css("display", "none");

        }
    });

    $("#tipo_busqueda").change(function () {
        var value = $(this).val();
        changeSelectSearchExp(value);
    });

    $("#btn_act_proc_jur").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Esta seguro de marcar el caso como proceso judicial?',
            icon: 'info',
            text: "Recuerde que solo el Director general podra revertir los cambios.",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Si, marcar',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                const body = new FormData();
                body.append('expid', $("#expediente_id").val());
                body.append('estado_id', 245);
                body.append('comentario', "Solicitud de docente");
                let response = await expedientesService.storeProcJudicial(body);
                toastr.success("Se actalizó con éxito", "", {
                    timeOut: "4000",
                });
                $("#wait").hide();
                window.location.reload(true);
            }
        });

    });
    $("#btn_ges_judexp").on("click", function (e) {
        e.preventDefault();
        var estado = $(this).attr("data-estado");
        $(".datos_genpj").hide();
        $(".datos_genpj input,select").prop("disabled", true);
        $(".content_detalles_exprocju").html("");
        $(".content_formulario_exprocju").show();
        if (estado == 245) {
            $("#input_estado_id").remove()
            $("#myFormGestionProcJudicialExp").append($('<input>', {
                type: 'hidden',
                name: 'estado_id',
                value: 246,
                id: "input_estado_id"
            }));
            $("#row_fileproex").show().find('input').prop("disabled", false)
                .prop("required", true).addClass("required");;
            $("#row_comentarioproex").show().find('textarea').prop("disabled", false)
                .prop("required", true).addClass("required").val("Demanda presentada por estudiante.");;
        }
        if (estado == 244) {//subsanacion
            $("#input_estado_id").remove()
            $("#myFormGestionProcJudicialExp").append($('<input>', {
                type: 'hidden',
                name: 'estado_id',
                value: 246,
                id: "input_estado_id"
            }));
            $("#row_fileproex").show().find('input').prop("disabled", false)
                .prop("required", true).addClass("required");;
            $("#row_comentarioproex").show().find('textarea').prop("disabled", false)
                .prop("required", true).addClass("required").val("Subsanación de demanda presentada por estudiante.");;
        }
        if (estado == 246) {
            $("#row_estadoid").show().find('select').prop("disabled", false)
                .prop("required", true).addClass("required");;
        }
        if (estado == 247) {
            $("#input_estado_id").remove()
            $("#myFormGestionProcJudicialExp").append($('<input>', {
                type: 'hidden',
                name: 'estado_id',
                value: 246,
                id: "input_estado_id"
            }));
            $("#row_fileproex").show().find('input').prop("disabled", false)
                .prop("required", true).addClass("required");;
            $("#row_comentarioproex").show().find('textarea').prop("disabled", false)
                .prop("required", true).addClass("required").val("Respuesta de demanda rechazada presentada por estudiante.");;
        }

        $("#myModal_gestion_judicial").modal("show");
    });

    $("#pj_estadoid").on("change", function (e) {
        e.preventDefault();
        $(".datos_genpj").hide();
        $(".datos_genpj input").prop("disabled", true);
        $("#row_estadoid").show();
        $("#row_fileproex").show().find('input').prop("disabled", false)
            .prop("required", true).addClass("required");;
        $("#row_comentarioproex").show().find('textarea').prop("disabled", false)
            .prop("required", true).addClass("required")
        if ($(this).val() == 244) {//Autoinadmisorio
            $("#row_fechaauto").show();
            $("#row_fechaauto input").prop("disabled", false).prop("required", true).addClass("required");
            $("#row_fechaauto #lbl_fechaprocju").text("Fecha de inadmisorio");
            $("#row_comentarioproex").show().find('textarea').val("Inadmisorio presentado por estudiante.");;

        }
        if ($(this).val() == 243) {//Autoadmisorio

            $("#row_fechahoaudiencia").show().find('input').prop("disabled", false)
                .prop("required", true).addClass("required");;
            $("#row_comentarioproex").show().find('textarea').val("Autoadmisorio presentado por estudiante.");;
        }

        if ($(this).val() == 247) {//Rechazado


            $("#row_comentarioproex").show().find('textarea').val("Proceso rechazado presentado por estudiante.");;
        }

    });

    $("#fecha_proj").on("change", function (e) {
        e.preventDefault();
        var fecha_ini = $(this).val();
        var dias = calcularProximosDiasHabiles(fecha_ini, 5);
        $("#row_fechaauto").show();
        $("#row_fechaauto input").prop("disabled", false).prop("required", true).addClass("required");
        $("#row_fechaauto #lbl_fechaprocju").text("Fecha de inadmisorio");
        $("#lbl_fechaaproxprcj").text(dias[dias.length - 1]);
    });

    $(".btn_detallesprjex").on("click", async function (e) {
        e.preventDefault(); //
        var id = $(this).attr('data-id');
        $("#wait").show();
        let response = await expedientesService.editExpProcJudicial(id);
        if (response.view) {
            $(".content_detalles_exprocju").html(response.view);
            $("#myModal_gestion_judicial").modal('show');
            $(".content_formulario_exprocju").hide();
            var fecha_ini = response.procjudi.fecha;
            if (fecha_ini != null) {
                var dias = calcularProximosDiasHabiles(fecha_ini, 5);
                $("#row_fechaauto #lbl_fechaprocju").text("Fecha de inadmisorio");
                $("#lbl_fechaaproxprcj").text(dias[dias.length - 1]);
            }


        }
        $("#wait").hide();

    });
    $('#myFormBsExpAdv').on('keyup', 'div.buscar_usuario input', async function (e) {
        let name = $(this).val();
        if (name.length >= 3) {
            $('div.buscar_usuario li.no-results').text('Buscando...');
            const response = await userService.findUserWithFilterByNameOrLastNameAndRole({ 'name': name, 'role': 'estudiante' })
            if (response.encontrado) {
                $("#select_data_estudiantes").find('option').remove().end();//elimina opciones existentes
                $(".buscar_usuario").selectpicker('render');
                var opcion_busq = '';
                $(response.users).each(function (key, value) {
                    opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name.toUpperCase() + '</option>';
                });
                $("#select_data_estudiantes").append(opcion_busq);
                $(".buscar_usuario").selectpicker("refresh");//refresca el select
            }
        } else {
            $('div.buscar_usuario li.no-results').text('Ingresa más caracteres...');
        }
    });

    $('#myformExpFilter').on('keyup', 'div.select_data_users input', async function (e) {
        let name = $(this).val();
        var role = '';
        let request = {}
        var opselected = $("#myformExpFilter select[name='tipo_busqueda']").val();
        if (opselected == 'idnumber_doc') role = 'docente';
        if (opselected == 'solicitante' || opselected == 'solicitante_num') role = 'solicitante';
        if (opselected == 'estudiante' || opselected == 'estudiante_num') role = 'estudiante';

        if (opselected != '' && (opselected == 'codido_exp'
            || opselected == 'solicitante_num' || opselected == 'estudiante_num')) {

            if (e.which === 13) {

                $(".select_data_users").val(name).trigger("change");
                $(".select_data_users").selectpicker("refresh");
                $("#myformExpFilter").trigger("submit");
                $(".select_data_users").selectpicker("toggle");


            } else {
                $(".select_data_users").selectpicker('render');//refresca el select
                var opcion_busq = '<option value="' + name + '">' + name + '</option>';
                $("#select_data_users").html(opcion_busq);
                $(".select_data_users").selectpicker("refresh");
            }

        } else if (opselected != '' && (opselected == 'estudiante' || opselected == 'solicitante' || opselected == 'idnumber_doc')) {
            if (name.length >= 3) {
                $('div.select_data_users li.no-results').text('Buscando...');

                let response;
                if (role == 'solicitante_num') {
                    let request = {
                        "idnumber": $(this).val(),
                        "active": 0
                    }
                    response = await userService.getUsersByIdnumber(request);
                } else {
                    let request = {
                        'name': name,
                        'role': role
                    }
                    if (role == 'solicitante' || role == 'estudiante') request['active'] = 0;
                    response = await userService.findUserWithFilterByNameOrLastNameAndRole(request);

                }
                if (response.encontrado) {
                    $("#select_data_users").find('option').remove().end();//elimina opciones existentes
                    $(".select_data_users").selectpicker('render');//refresca el select
                    var opcion_busq = '';
                    $(response.users).each(function (key, value) {
                        if (value.full_name) {
                            opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name.toUpperCase() + '</option>';
                        } else {
                            opcion_busq += '<option value="' + value.idnumber + '">' + value.name.toUpperCase() + ' ' + value.lastname.toUpperCase() + '</option>';
                        }
                    });
                    $("#select_data_users").append(opcion_busq);
                    $(".select_data_users").selectpicker("refresh");//refresca el select
                }

            } else {
                $('div.select_data_users li.no-results').text('Ingresa más caracteres...');
            }
        }
    });

    $("#btn_desc_exp_us").on("click", function (e) {
        if ($("#select_data_estudiantes").val() != '' && $("#select_data_estudiantes").val() != null) {
            console.log($("#select_data_estudiantes").val());
            let a = document.createElement("a");
            let req = $('#myFormBsExpAdv').serialize();
            a.setAttribute('href', 'excel/exp/user/download?' + req);
            a.setAttribute('target', '_blank');
            a.click();
        } else {
            toastr.error("Ingrese un nombre de estudiante!", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });

    $("#btn_exp_bus_avz").on("click", function (e) {
        $("#mymodalBuscarExpAvanzadas").modal("show");
    });

    $("#myFormGestionProcJudicialExp").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm('myFormGestionProcJudicialExp');
        var archivo = $('#fileid')[0].files[0];
        if (archivo != null && archivo != undefined) {
            var extension = archivo.name.split('.').pop().toLowerCase();
            if (extension !== 'pdf') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Ups! El archivo no es pdf',
                    showConfirmButton: false,
                    timer: 5500
                });
                return;
            }
        }


        if (errors.length <= 0) {
            const body = new FormData(document.getElementById('myFormGestionProcJudicialExp'));
            body.append('expid', $("#expediente_id").val());
            try {
                $("#loader-container").show().css({ 'display': 'flex' })
                $("#wait").show();
                const result = await expedientesService.storeProcJudicial(body)
                    .then((response) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Actualizado con éxito!",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        //  window.location.reload(true);
                        e.preventDefault()
                    })
                    .catch((error) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
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
                e.preventDefault()
            }
        } else {
            toastr.error("Hay campos que son obligatorios!", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });


    $("#btn_exp_user_carga").on("click", async function () {
        $("#wait").show();
        $("#myFormUserEditExpediente input[name='idnumber']").prop('disabled', true).removeAttr('name');
        $("#wait").hide()
    });

    $("#btn_exp_user_carga_create").on("click", function (e) {
        $("#myFormUserEditExpediente").attr("id", "myFormUserCreateExpediente");
        $("#actualizar_exp_us").attr("id", "registrar_exp_us");
        resetForm('myFormUserCreateExpediente');
        $("#myFormUserCreateExpediente select[name='tipopers_id']").val(237);
        $("#myFormUserCreateExpediente select[name='tipodoc_id']").val(2);
    });

    $("#content_user_exp_asig").on("blur", "input[name='idnumber']", async function (e) {
        var formulario = $(this).closest('form');
        var formularioId = formulario.attr('id');
        $("#" + formularioId + " input[name='email']").val($(this).val() + "@mail.com")
        if ($(this).val() != '') {
            let request = {
                "tipodoc_id": $("#" + formularioId + " select[name='tipodoc_id']").val(),
                "idnumber": $(this).val(),
                "view": "myforms.components_exp.frm_user_register"
            }
            $("#wait").show();
            let response = await userService.findUserWithFilter(request);
            if (response.encontrado) {
                $("#content_user_exp_asig").html(response.view);
                toastr.success("Usuario encontrado", "", {
                    positionClass: "toast-top-center",
                    timeOut: "4000",
                });
                $("#myFormUserEditExpediente input[name='idnumber']").prop('disabled', true);
            }
            $("#wait").hide()
        }

    });


    $("#content_user_exp_asig #myFormUserCreateExpediente").on("focus", "input[name='idnumber']", validateTypeDoc);
    $("#content_user_exp_asig #myFormUserEditExpediente").on("focus", "input[name='idnumber']", validateTypeDoc);

    $("#content_user_exp_asig").on("click", '#registrar_exp_us', async function (e) {
        var errors = validateForm("myFormUserCreateExpediente");
        if (errors.length <= 0) {
            var request = convertFormToJSON("myFormUserCreateExpediente");
            var data = [];
            $("#myFormUserCreateExpediente .input_user_ad").each((index, obj) => {
                data.push({
                    value: $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
                    section: $(obj).attr("data-section"),
                    type: $(obj).attr("data-type"),
                    name: $(obj).attr("data-name"),
                    option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
                    value_is_other: $("#value_other_text-" + $(obj).val()).val(),
                    conciliacion_id: $("#conciliacion_id").val()
                });
            });
            request["data"] = (data);
            $("#wait").show();
            let response = await userService.registrar(request);
            if (response.errors) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });
            } else {
                resetForm('myFormUserEditExpediente');
                $("#myFormExpsStore input[name='expidnumber']").val(response.user.idnumber)
                $("#myModal_exp_user_edit").modal("hide");
            }
            $("#wait").hide();
        }
    });


    $("#myModal_exp_user_edit").on("click", '#btnActualizarUserForEstudiante', async function (e) {
        var errors = validateForm("myFormUserEditExpediente");
        if (errors.length <= 0) {
            var request = convertFormToJSON("myFormUserEditExpediente");
            var data = userService.getAditionalDataByForm('myFormUserEditExpediente');
            request["data"] = (data);
            $("#wait").show();
            let response = await userService.update(request);
            if (response.errors) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });
            } else {
                $("#myModal_exp_user_edit").modal("hide");
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });

            }
            $("#wait").hide();
        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });

    $("#content_user_exp_asig").on("click", '#actualizar_exp_us', async function (e) {
        var errors = validateForm("myFormUserEditExpediente");
        if (errors.length <= 0) {
            var request = convertFormToJSON("myFormUserEditExpediente");
            var data = userService.getAditionalDataByForm('myFormUserEditExpediente');
            request["data"] = (data);
            $("#wait").show();
            let response = await userService.update(request);
            if (response.errors) {
                response.errors.forEach(error => {
                    toastr.error(error, "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                });
            } else {
                resetForm('myFormUserEditExpediente');
                $("#myFormExpsStore input[name='expidnumber']").val(response.user.idnumber)
                $("#myModal_exp_user_edit").modal("hide");
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });

            }
            $("#wait").hide();
        }
    });

    $("#btn_cerrar_dr_caso").on("click", function (e) {
        let request = {
            expidnumber: $("#expid").val(),
            ref_estado_id: 2,
            ref_motivo_estado_id: 8
        }
        Swal.fire({
            title: 'Cerrando caso',
            text: "No se asignarán notas!",
            input: 'textarea',
            inputPlaceholder: '¿Por qué va a cerrar el caso?',
            inputAttributes: {
                rows: 90,  // Número de filas del textarea
                cols: 500  // Número de columnas del textarea
            },
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Cerrar caso',
            confirmButtonClass: 'btn-success',
            allowEmpty: false, // Evita el valor vacío en el textarea
            preConfirm: (text) => {
                if (text !== '') {
                    $("#wait").show();
                    request["comentario"] = text;
                    let response = expedientesService.cerrarCaso(request);
                    toastr.success("Actualizado con éxito", "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                    window.location.reload(true)
                } else {
                    Swal.showValidationMessage('La descripción no puede estar vacía'); // Muestra un mensaje de validación personalizado

                }
            }
        });
        $("#wait").hide();

    });
    $("#myFormExpsStore").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm('myFormExpsStore');
        if (errors.length <= 0) {
            var request = convertFormToJSON('myFormExpsStore');
            $("#wait").show();
            var response = await expedientesService.store(request);
            resetForm('myFormExpsStore')
            $("#wait").hide();
            Swal.fire({
                title: 'El caso se ha creado con éxito!',
                icon: 'success',
                text: "¿Qué desea hacer?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ver el nuevo expediente',
                cancelButtonText: 'Quedarme en esta página'
            }).then(async (result) => {
                if (result.value) {
                    $("#wait").show();
                    window.location = '/expedientes/' + response.expid + '/edit';
                } else {
                    if (result.dismiss === Swal.DismissReason.cancel || result.dismiss === Swal.DismissReason.overlay) {
                        // El usuario hizo clic en el botón cancel o fuera del swal
                        window.location.reload(true)

                    } else {
                        // El usuario hizo clic en el botón confirmar
                        window.location.reload(true)
                    }
                }
            });
        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });
    $("#btnTomarCaso").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de tomar el caso?',
            text: "Se asignará automaticamente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, tomarlo!',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.value) {
                var form = convertFormToJSON("form_expediente_edit");
                var data = {
                    exp_idnumberest: form.exp_idnumberest,
                    expid: form.expid,
                };
                $("#wait").show();
                let response = expedientesService.tomarCaso(data);
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        })


    });

    $("#myform_change_docente_exp").on("submit", async function (e) {
        e.preventDefault();
        let request = convertFormToJSON("myform_change_docente_exp");
        request['expid'] = $("#form_expediente_edit input[name='expediente_id']").val()
        $("#wait").show();
        let response = await expedientesService.gestionDocente(request);
        if (response.errors && response.errors.length > 0) {
            toastr.error(errors[0], "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        } else {
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true)
        }

    });

    $("#btn_change_doc_exp").on("click", async function (e) {
        e.preventDefault();
        $("#titulo_modal").text("Cambiando docente");
        $("#myform_change_docente_exp>#tipo_cambio").val(1);
        $("#myform_change_docente_exp input[type='submit']").val("Cambiar docente");
        $("#wait").show();
        let response = await userService.getUsersByRole({ 'role': 'docente', 'active': 1 });
        $("#wait").hide();
        if (response.encontrado) {
            var opcion_busq = '';
            $("#new_docente_id").html('')
            $(response.users).each(function (key, value) {
                opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name + '</option>';
            });
            var userauth = JSON.parse($("#authdata").val())
            opcion_busq += '<option value="' + userauth.idnumber + '">' + userauth.name + ' ' + userauth.lastname + '</option>'

            $("#new_docente_id").html(opcion_busq).selectpicker("refresh");;
            $("#myModal_change_docente_exp").modal("show");
        }
    });

    $("#btn_delete_doc_exp").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de eliminar la asignación del docente?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                var request = { tipo_cambio: 5 };
                request['expid'] = $("#form_expediente_edit input[name='expediente_id']").val()
                $("#wait").show();
                let response = await expedientesService.gestionDocente(request);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        });
    });

    $("#btn_send_exp_change").on("click", async function (e) {
        e.preventDefault();
        $("#titulo_modal").text("Solicitando cambio");
        $("#myform_change_docente_exp>#tipo_cambio").val(0);
        $("#myform_change_docente_exp input[type='submit']").val("Solicitar cambio");
        $("#wait").show();
        let response = await userService.getUsersByRole({ 'role': 'docente', 'active': 1 });
        $("#wait").hide();
        if (response.encontrado) {
            var opcion_busq = '';
            $("#new_docente_id").html('')
            $(response.users).each(function (key, value) {
                opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name + '</option>';
            });
            var userauth = JSON.parse($("#authdata").val())
            opcion_busq += '<option value="' + userauth.idnumber + '">' + userauth.name + ' ' + userauth.lastname + '</option>'

            $("#new_docente_id").html(opcion_busq).selectpicker("refresh");;
            $("#myModal_change_docente_exp").modal("show");
        }
    });

    $("#btn_accept_change_doc_exp").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de Aceptar la solicitud de cambio?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, cambiar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                var request = { tipo_cambio: 3 };
                request['expid'] = $("#form_expediente_edit input[name='expediente_id']").val()
                $("#wait").show();
                let response = await expedientesService.gestionDocente(request);
                toastr.success("Asignado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        });

        /*   var confirm = alertify.confirm(
              "¿Está seguro de <b>Aceptar</b> la solicitud de cambio?"
          );
          confirm.set("onok", function () {
              var data = { tipo_cambio: 3 };
              var exp_id = $("#expediente_id").val();
              changeDocenteExp(data, exp_id);
          }); */
    });

    $("#btn_cancel_change_doc_exp").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de eliminar la solicitud del docente?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                var request = { tipo_cambio: 2 };
                request['expid'] = $("#form_expediente_edit input[name='expediente_id']").val()
                $("#wait").show();
                let response = await expedientesService.gestionDocente(request);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                //  window.location.reload(true)
            }
        });
    });
    $("#btn_dar_baja_exp").on("click", function (e) {
        e.preventDefault();
        var request = {
            "exp_id": $("#expid").val()
        }
        Swal.fire({
            title: "Esta seguro de dar de baja el expediente?",
            text: "Se asignará un docente de pruebas!",
            icon: "info",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, dar de baja!",
            cancelButtonText: "No, cancelar",
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let response = await expedientesService.darDeBaja(request);
                $("#wait").hide();
                Swal.fire({
                    title: response.message,
                    html: "<h4>De clic en OK para cargar los cambios o refresque la página</h4>",
                    icon: "info",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "OK",
                }).then((result) => {
                    if (result.value) {
                        window.location.reload(true)
                    }
                });
            }
        });
    });

    $("#switch_shared_asesoria_caso").on("click", function (e) {
        var check = $("#apl_shared");
        if (check.val() == "1") {
            check.val(0);
            $(this).removeClass('switch-on').addClass("switch-off");
        } else if (check.val() == "0") {
            check.val(1);
            $(this).removeClass('switch-off').addClass("switch-on");
        }
    });

    $("#myform_add_asesoria_docente").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myform_add_asesoria_docente");
        if (errors <= 0) {
            var request = {
                'comentario': $("#asesoria_docente").val(),
                'expid': $("#expid").val(),
                'apl_shared': $("#apl_shared").val()
            }
            $("#wait").show();
            let response = await expedientesService.addAsesoria(request);
            toastr.success("Agregado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true)

        }

    });
    $(".btn_edit_asesoria_caso").on("click", async function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        $("#wait").show();
        let response = await expedientesService.editAsesoria(id);
        $("#myModal_update_asesoria_docente").modal("show");
        $("#asesoria_docente_update").val(response.comentario);
        $("#myModal_update_asesoria_docente input[name='id']").val(response.id);
        $("#wait").hide();
    });

    $("#myform_update_asesoria_docente").on("submit", async function (e) {
        var errors = validateForm("myform_update_asesoria_docente");
        if (errors <= 0) {
            var form = convertFormToJSON('myform_update_asesoria_docente');
            var request = {
                comentario: form.asesoria_docente_update,
                id: form.id
            }
            $("#wait").show();
            let response = await expedientesService.updateAsesoria(request, request.id);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true)
        }
        e.preventDefault();
    });

    $(".btn_delete_asesoria_caso").on("click", async function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        Swal.fire({
            title: 'Esta seguro de eliminar el comentario del docente?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let response = await expedientesService.deleteAsesoria(id);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        });
    });

    $(".chk_change_shared").on("click", async function (e) {
        var id = $(this).attr('data-id');
        var status = $(this).attr('data-status') == 0 ? 1 : 0;
        var request = {
            apl_shared: status,
            id: id
        }
        $("#wait").show();
        let response = await expedientesService.updateAsesoria(request, id);
        toastr.success("Actualizado con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        if (status) {
            $("#switch_edit" + id)
                .removeClass("switch-off")
                .addClass("switch-on");
        } else if (!status) {
            $("#switch_edit" + id)
                .removeClass("switch-on")
                .addClass("switch-off");
        }

        $("#wait").hide();
    });

    $("#myformCreateActButton").on("click", async function (e) {
        var errors = validateForm('myformCreateAct')
        if (errors.length <= 0) {
            const body = new FormData(document.getElementById('myformCreateAct'));
            try {
                $("#loader-container").show().css({ 'display': 'flex' })
                $("#wait").show();
                const result = await expedientesService.addActuacion(body)
                    .then((response) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Actualizado con éxito!",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        window.location.reload(true)
                        e.preventDefault()
                    })
                    .catch((error) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
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
                /*  const result = userService.showProgress(0)
                 $("#loader-container").hide() */
                $("#wait").hide();
                e.preventDefault()
            }
        }
        $("#wait").hide();
        e.preventDefault();
    });

    $(".buscar_actuacion").on("click", async function (e) {
        e.preventDefault();
        var id = $(this).val();
        var modal = $(this).attr('data-modal');
        $("#wait").show();
        let response = await expedientesService.getActuaciones(id);
        llenarModalDetailsAct(response);
        $(modal).modal("show");
        $("#actfecha_edit").val(response.created_at);
        $("#actnombre_edit").val(response.actnombre);
        $("#actdescrip_edit").val(response.actdescrip);
        $("#lbl_nom_archivo_est").text(response.actdocnompropio);
        $("#idact").val(response.id);

        $("#actnombre_cr").val(response.actnombre);
        $("#actdescrip").val(response.actdescrip);
        $("#myform_act_edit_docente input[name='actfecha']").val(response.created_at);
        $("#parent_actuacion_id").val(response.parent.parent_rev_actid);
        $("#act_id").val(response.id);
        if (response.actestado_id == 102) {
            $("#actestado option[value='" + response.actestado_id + "']").attr('selected', true);
            $("#fecha_limit_doc").prop('disabled', false).val(response.fecha_limit);
            $("#actdocenrecomendac").val(response.actdocenrecomendac);

        }
        if ($(this).attr('data-status') == '136') {
            var label = 'Agregando Anexo a Actuación';
            $("#actestado_id2").val(136);
            $("#myformCreateCorreccionActButton").text('Agregar Anexo');
            $("#lbl_tip_act").text('Motivo');
            $("#lbl_type_actadd").text('Agregar Anexo');
        }
        $(".lab_id_act").text(label);
        $("#wait").hide();
    });

    $("#myformEditActButton").on("click", async function (e) {
        var errors = validateForm('myform_act_edit')
        if (errors.length <= 0) {
            const body = new FormData(document.getElementById('myform_act_edit'));
            try {
                var id = $("#idact").val();
                $("#wait").show();
                const result = await expedientesService.updateActuacion(body, id)
                    .then((response) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Actualizado con éxito!",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        window.location.reload(true)
                        e.preventDefault()
                    })
                    .catch((error) => {
                        /*  Swal.fire({
                             position: 'top-end',
                             icon: 'error',
                             title: 'Ups! Algo fallo',
                             html: error,
                             showConfirmButton: false,
                             timer: 5500
                         }); */
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
                /*  const result = userService.showProgress(0)
                 $("#loader-container").hide() */
                $("#wait").hide();
                e.preventDefault()
            }
        }
        $("#wait").hide();
        e.preventDefault();
    });

    $("#actestado").on("change", function () {
        if ($(this).val() == "104") {
            $(".addNotasAct").show();
            $(".addNotasAct .required").prop("disabled", false);

            $("#myform_act_edit_docente #fecha_limit_doc").prop(
                "disabled",
                true
            );
        } else if ($(this).val() == "234") {

            $(".addNotasAct").hide();
            $("#myform_act_edit_docente #fecha_limit_doc").prop(
                "disabled",
                true
            );
        } else {
            $(".addNotasAct").hide();
            $(".addNotasAct .required").prop("disabled", true);
            $("#myform_act_edit_docente #fecha_limit_doc").prop(
                "disabled",
                false
            );

        }
        if ($(this).val() == "") {

            $(".addNotasAct").hide();
            $(".addNotasAct .required").prop("disabled", true);
            $("#myform_act_edit_docente #fecha_limit_doc").prop(
                "disabled",
                true
            );
        }
    });

    $("#btn_act_edit_docen").click(async function (e) {
        e.preventDefault();
        if ($("#actestado").val() != 104) {
            $("#formAddNotas .form-control").removeClass('required');
        }
        var errors = validateForm('myform_act_edit_docente');
        var notaapl = $("#myform_act_edit_docente input[name='ntaaplicacion']").val();
        var notacon = $("#myform_act_edit_docente input[name='ntaconocimiento']").val();
        var notaet = $("#myform_act_edit_docente input[name='ntaetica']").val();
        var fecha_limit = $("#myform_act_edit_docente input[name='fecha_limit_doc']").val();
        if (!existeFecha(fecha_limit) && $("#actestado").val() == 102) {
            toastr.error("Por favor, verifíque que el año de fecha limite no sea inferior o superior a un año con respecto al año actual.", "", {
                positionClass: "toast-top-right",
                timeOut: "6000",
            });
            errors = 1;
        }
        if (notaapl > 5 || notacon > 5 || notaet > 5) {
            toastr.error("Por favor, verifíque que no haya notas superiores a 5.0", "", {
                positionClass: "toast-top-right",
                timeOut: "6000",
            });
            errors = 1;
        }

        if (isNaN(notaapl) || isNaN(notacon) || isNaN(notaet)) {
            toastr.error("Por favor, verifíque que no haya notas con espacios o caracteres extraños", "", {
                positionClass: "toast-top-right",
                timeOut: "6000",
            });
            errors = 1;
        }
        if (errors <= 0) {

            try {
                var id = $("#idact").val();
                $("#wait").show();
                const body = new FormData(document.getElementById('myform_act_edit_docente'));
                let response = await expedientesService.updateActuacionDocente(body)
                    .then((response) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Actualizado con éxito!",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        window.location.reload(true)
                        e.preventDefault()
                    })
                    .catch((error) => {
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
                /*  const result = userService.showProgress(0)
                 $("#loader-container").hide() */
                $("#wait").hide();
                e.preventDefault()
            }
        }
    });

    $("#myformCreateCorreccionActButton").on("click", async function (e) {
        var errors = validateForm('myformAddActuacion')
        if (errors.length <= 0) {
            const body = new FormData(document.getElementById('myformAddActuacion'));
            try {
                $("#loader-container").show().css({ 'display': 'flex' })
                $("#wait").show();
                const result = await expedientesService.addActuacion(body)
                    .then((response) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: "Actualizado con éxito!",
                            showConfirmButton: false,
                            timer: 2500
                        });
                        window.location.reload(true)
                        e.preventDefault()
                    })
                    .catch((error) => {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
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
                /*  const result = userService.showProgress(0)
                 $("#loader-container").hide() */
                $("#wait").hide();
                e.preventDefault()
            }
        }
        $("#wait").hide();
        e.preventDefault();
    });

    $(".delete_act").on("click", function (e) {
        e.preventDefault();
        var id = $(this).val()
        Swal.fire({
            title: 'Esta seguro de eliminar la actuación?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let response = await expedientesService.deleteActuacion(id);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        });
    });

    $(".btn_new_act").on("click", function () {
        $("#actestado_id").val(101);
        $("#lbl_title_fract").text("Crear Actuación");
        $("#lbl_type_actuacion").text($(this).attr("data-titulo_modal"));
        $("#myformCreateAct input[name=fecha_limit]")
            .prop("disabled", false)
            .show();
        $("#myformCreateActButton").text("Crear actuación");
        if ($(this).attr("id") == "btn_new_anex") {
            $("#actestado_id").val(136);
            $("#myformCreateAct input[name=fecha_limit]")
                .prop("disabled", true)
                .hide();
            $("#myformCreateActButton").text("Crear anexo");
            $("#lbl_title_fract").text("Crear Anexo");
            $("#lbl_type_actuacion").text($(this).attr("data-titulo_modal"));
        }
        if ($(this).attr("id") == "btn_new_act_doct") {
            $("#actestado_id").val(140);
            $("#lbl_title_fract").text("Crear Actuación Docente");
            $("#lbl_type_actuacion").text($(this).attr("data-titulo_modal"));
        }
    });

    $("#tbl_ajax").on("click", ".btn_change_status", async function () {
        var id = $(this).val();
        var estado = $(this).attr("id");
        var request = {
            "id": id,
            "new_estado": $(this).attr("data-estado")
        }

        if (estado == 139) {
            var msj = alertify.confirm(
                "¿Esta seguro de cambiar el estado?\nSe eliminaran las notas"
            );
            msj.set("onok", async function () {
                $("#wait").show()
                await expedientesService.changeStateActuacion(request);
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                location.reload(true);
            });

            return false;
        } else if (request.new_estado == 136) {
            request["actdocenrecomendac"] = '';
            $("#wait").show()
            await expedientesService.changeStateActuacion(request);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            location.reload(true);
            return false;
        } else if (request.new_estado == 235) {
            Swal.fire({
                title: 'Anulando anexo',
                input: 'textarea',
                inputPlaceholder: '¿Por qué va anular el anexo?',
                inputAttributes: {
                    rows: 100,  // Número de filas del textarea
                    cols: 500  // Número de columnas del textarea
                },
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Enviar',
                confirmButtonClass: 'btn-success',
                allowEmpty: false, // Evita el valor vacío en el textarea
                preConfirm: async (text) => {
                    if (text !== '') {
                        request["actdocenrecomendac"] = text;
                        $("#wait").show()
                        await expedientesService.changeStateActuacion(request);
                        toastr.success("Actualizado con éxito", "", {
                            positionClass: "toast-top-right",
                            timeOut: "4000",
                        });
                        location.reload(true);
                    } else {
                        Swal.showValidationMessage('La descripción no puede estar vacía'); // Muestra un mensaje de validación personalizado

                    }

                }
            });
        } else {
            $("#wait").show()
            await expedientesService.changeStateActuacion(request);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            location.reload(true);
        }
    });

    $("#search_previous_act").on("click", function () {
        if ($("#search_previous_act i").attr("class") == "fa fa-plus") {
            $("#search_previous_act i").attr("class", "fa fa-minus");
        } else {
            $("#search_previous_act i").attr("class", "fa fa-plus");
        }
        $(".cont_act_prev").toggle();
    });

    $("#btn_enviar_req").on("click", async function (e) {
        e.preventDefault();
        var errors = validateForm('myform_req');
        if (errors.length <= 0) {
            var request = convertFormToJSON('myform_req');
            $("#wait").show()
            await expedientesService.storeRequerimiento(request);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            location.reload(true);
        }
    });
    $(".btn_editar_req").on("click", async function (e) {
        e.preventDefault();
        let id = $(this).attr("data-id");
        let modal = $(this).attr("data-modal");
        $("#wait").show();
        const response = await expedientesService.editRequerimiento(id);
        llenarFormEditReq(response);
        llenarModalDetailsReq(response);
        llenarModalUpdateReq(response);
        $(modal).modal("show");
        $("#wait").hide();
    });
    $("#btn_act_req").on("click", async function (e) {
        e.preventDefault();
        var errors = validateForm('myform_req_edit');
        if (errors.length <= 0) {
            var id = $("#reqid").val();;
            var request = convertFormToJSON('myform_req_edit');
            $("#wait").show()
            await expedientesService.updateRequerimiento(request, id);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            location.reload(true);
        }
    });
    $("#btn_update_requerimiento").on("click", async function (e) {
        e.preventDefault();
        var errors = validateForm('myformUpdateReq');
        if (errors.length <= 0) {
            var id = $("#reqid").val();;
            var request = convertFormToJSON('myformUpdateReq');
            $("#wait").show()
            await expedientesService.updateRequerimiento(request, id);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            location.reload(true);
        }
    });
    $(".btn_delete_requerimiento").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id')
        Swal.fire({
            title: 'Esta seguro de eliminar el requerimiento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let response = await expedientesService.deleteRequerimiento(id);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        });
    });

    $(".btn_cambiar_estado_requerimiento").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id');
        var reqentregado = ($(this).attr('data-estado') == 0) ? 1 : 0;
        Swal.fire({
            title: 'Esta seguro de cambiar el estado del requerimiento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, cambiar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let request = {
                    'reqentregado': reqentregado
                }
                let response = await expedientesService.updateRequerimiento(request, id);
                toastr.success("Cambiado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true)
            }
        });
    });

    $("#btn_cam_nt_req").on("click", async function () {
        $("#myModal_req_details").modal("hide");
        var actuacion_id = $("#req_id_det").val();
        var request = {
            "origen": 3
        }
        $("#wait").show();
        let response = await expedientesService.getNotas(request, actuacion_id);
        lleFormEditNotas(response, 3, actuacion_id);
        $("#wait").hide();
    });

    $("#btns_edit_notas").on("click", "#btn_delete_notas", async function () {
        Swal.fire({
            title: 'Esta seguro de eliminar las notas?',
            text: "Los cambios no podran ser revertidos!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#myModal_edit_notas").modal("hide");
                openCamNotas();
                let request = convertFormToJSON('myform_update_notas');
                $("#wait").show();
                let response = await expedientesService.deleteNotas(request);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true);
            }
        });
    });

    $("#btns_edit_notas").on("click", "#btn_cambiar_notas", function (e) {
        e.preventDefault();
        openCamNotas();
    });
    $("#btn_cancelar_notas").on("click", hideEditNotas);


    $("#myform_update_notas").on('submit', async function (e) {
        e.preventDefault();
        var errors = validateForm('myform_update_notas')
        errors = validarNotasUpdate(errors, 'myform_update_notas');

        if (errors.length <= 0) {
            var data = convertFormToJSON('myform_update_notas');
            $("#wait").show();
            await expedientesService.updateNotas(data);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true);
            return false;
        } else {

        }

    });

    $("#form_expediente_edit").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("form_expediente_edit");
        if (errors.length <= 0) {
            var request = convertFormToJSON('form_expediente_edit');
            var id = $("#expediente_id").val();
            var textarea = document.getElementById('exp_hechos');
            var contenido = textarea.value.trim();
            var palabras = contenido.split(/\s+/);
            if (palabras.length < 100) {
                toastr.error("Los hechos deben tener al menos 100 palabras", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                e.preventDefault();
                return
            }
            if ($("#exptipoproce_id").val() != 3) {
                var textarea = document.getElementById('exp_resp_est');
                var contenido = textarea.value.trim();
                var palabras = contenido.split(/\s+/);
                if (palabras.length < 100) {
                    toastr.error("La respuesta debe tener al menos 100 palabras", "", {
                        positionClass: "toast-top-right",
                        timeOut: "4000",
                    });
                    e.preventDefault();
                    return
                }
            }
            $("#wait").show();
            let response = await expedientesService.update(request, id);
            window.location.reload();
            toastr.success("Se actualizó con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });

    $("#myform_exp_edit_cierre_caso").on("submit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myform_exp_edit_cierre_caso");
        if (errors.length <= 0) {
            var request = convertFormToJSON('myform_exp_edit_cierre_caso');
            request['expid'] = $("#expid").val();
            request['hechos'] = $("#exp_hechos").val();
            request['exp_resp_est'] = $("#exp_resp_est").val();
            $("#wait").show();
            let response = await expedientesService.storeEstadoCaso(request);
            if (!response.guardado) {
                $("#wait").hide();
                toastr.error(response.mensaje, "", {
                    positionClass: "toast-top-right",
                    timeOut: "8000",
                });
            } else {
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true);
            }
            return false;
        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }

    });
    $("#btn_add_nota").on("click", async function () {
        var errors = validateForm("myform_add_nota_final_expedientes");
        errors = validarNotas(errors, 'myform_add_nota_final_expedientes');
        if (errors.length <= 0) {
            let request = convertFormToJSON("myform_add_nota_final_expedientes");
            $("#wait").show();
            let response = await expedientesService.storeNotas(request);
            toastr.success("Notas agregadas con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true);
        } else {
            toastr.error("Hay campos que son obligatorios", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });

    $("#btn_edit_nt_exp").on("click", async function () {
        var actuacion_id = $("#form_expediente_edit #expediente_id").val();
        var request = { "origen": 1 };
        $("#wait").show();
        let response = await expedientesService.getNotas(request, actuacion_id);
        lleFormEditNotas(response, 1, actuacion_id);
        $("#wait").hide();
    });


    $("#btns_edit_notas").on("click", "#btn_tipo_nota_update", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de cambiar las notas del caso?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, cambiar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#tipo_nota_id").attr("disabled", false);
                $("#tipo_nota_id").val($("#btn_tipo_nota_update").attr("data-value"));
                $("#myModal_edit_notas").modal("hide");
                openCamNotas();
                let request = convertFormToJSON('myform_update_notas');
                $("#wait").show();
                let response = await expedientesService.updateNotas(request);
                toastr.success("Actualizado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                window.location.reload(true);
            }
        });

    });

    $("#modalhcaso").on("click", async function (e) {
        e.preventDefault();
        $("#modal-conten-js").html('');//limpia modal antes de mostrar
        $("#mymodal-dinamyc-tittle").html("Hechos caso");
        var expid = $(this).attr('data-name');
        var tipo = "141"//hechos del caso
        $("#wait").show();
        let response = await expedientesService.getHistoryDataCase(expid, tipo);
        fillModalHistoryDataCase(response);
        $("#wait").hide();

    });
    $("#modalresestudiante").on("click", async function () {
        $("#modal-conten-js").html('');//limpia modal antes de mostrar
        $("#mymodal-dinamyc-tittle").html("Respuesta estudiante");
        var expid = $(this).attr('data-name');
        var tipo = "142"//respuesta estudiante
        $("#wait").show();
        let response = await expedientesService.getHistoryDataCase(expid, tipo);
        fillModalHistoryDataCase(response);
        $("#wait").hide();
    });

    $("#btn_mod_expfecha_res").on("click", async function (e) {
        e.preventDefault();
        var request = {
            expfecha_res: $("#expfecha_res").val(),
            exp_id: $("#expid").val(),
            expediente_id: $("#expediente_id").val(),
        };
        $("#wait").show();
        let response = await expedientesService.update(request, request.expediente_id);
        $("#lbl_expfecha_res").text(request.expfecha_res);
        $("#fechalimitres").modal("hide");
        $("#wait").hide();
        toastr.success("Actualizado con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        e.preventDefault();
    });

    $("#btn_reabrir_caso").on("click", function (e) {
        var estado = $(this).attr("data-estado");
        /*     $("#myform_addnew_nota_final_expedientes").append($('<input>',{
                type:'text',
                value:estado,
                name:"estado_casoid"
            })) */
        $("#myModal_addnew_nota_final_expedientes").modal("show");
    });
    $("#btn_addnew_nota_exp").on("click", async function () {
        var errors = validateForm("myform_addnew_nota_final_expedientes");
        errors = validarNotas(errors, 'myform_addnew_nota_final_expedientes');
        if (errors <= 0) {
            var request = convertFormToJSON('myform_addnew_nota_final_expedientes');
            $("#wait").show();
            let response = await expedientesService.reabrirCaso(request);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true)
        }
    });

    $("#myformCitarEstudiante").on("change", "#fecha", async function () {
        var request = { expid: $("#expid").val(), fecha: $(this).val() };
        if (request.expid !== undefined) {
            $("#wait").show();
            let res = await expedientesService.searchCitasForDay(request);
            var li = "";
            if (res.length <= 0) {
                li += `<tr><td colspan="4">No se encontraron citas...</td> </tr>`;
            } else {
                res.forEach((element) => {
                    li += `<tr>
                                <td>${element.hora} </td> 
                                <td>${element.motivo} </td> 
                                <td>${element.asignacion.estudiante.name} ${element.asignacion.estudiante.lastname}</td> 
                                <td>${element.asignacion.asigexp_id} </td>                            
                            </tr>`;
                });
            }
            $("#menu_details_citas").show();
            $("#menu_details_citas tbody").html(li);
            $("#wait").hide();
        }
    });
    $("#mymodalNuevaCitacion").on("submit", "#myformCitarEstudiante", async function (e) {
        e.preventDefault();
        var errors = validateForm("myformCitarEstudiante");
        if (errors <= 0) {
            var request = convertFormToJSON("myformCitarEstudiante");
            request.exp_id = $("#expid").val();
            $("#wait").show();
            let response = await expedientesService.storeCitacionEstudiante(request);
            toastr.success("Creado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            $("#mymodalNuevaCitacion").modal("hide");
            window.location.reload(true)
        }
        return false;

    });
    $("#table_list_citaciones").on("click", ".btn_edit_citacion", async function () {
        var id = $(this).attr("id");
        $("#wait").show();
        let response = await expedientesService.editCitacionEstudiante(id);
        $("#myformCitarEstudiante").attr("id", "myformCitarEstudianteEdit");
        $("#myformCitarEstudianteEdit #id").val(response.id);
        $("#myformCitarEstudianteEdit #hora").val(response.hora);
        $("#myformCitarEstudianteEdit #fecha").val(response.fecha_corta);
        $("#myformCitarEstudianteEdit #motivo").val(response.motivo);
        $("#mymodalNuevaCitacion").modal("show");
        $("#wait").hide();
    });
    $("#ct_forcitaest").on("submit", "#myformCitarEstudianteEdit", async function (e) {
        e.preventDefault();
        var errors = validateForm("myformCitarEstudianteEdit");
        if (errors <= 0) {
            var request = convertFormToJSON("myformCitarEstudianteEdit");
            request.exp_id = $("#expid").val();
            $("#wait").show();
            await expedientesService.updateCitacionEstudiante(request, request.id);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true);
            return false;
        }
    });
    $("#btn_nueva_autorizacion").on("click", function () {
        $("#myformEditAutorizacion").attr("id", "myformCreateAutorizacion");
        $("#myformCreateAutorizacion button")
            .removeClass("btn-warning")
            .addClass("btn-primary")
            .text("Crear").show();
        resetForm('myformCreateAutorizacion');
        $("#mymodalCreateAutorizacion").modal("show");
    });


    $("#mymodalCreateAutorizacion").on("submit", "#myformCreateAutorizacion", async function (e) {
        e.preventDefault();
        var errors = validateForm("myformCreateAutorizacion");
        if (errors <= 0) {
            var request = convertFormToJSON("myformCreateAutorizacion");
            request.exp_id = $("#expid").val();
            $("#wait").show();
            let response = await expedientesService.storeAutorizacion(request);
            $("#table_list_autorizaciones tbody").html(response.view);
            $("#mymodalCreateAutorizacion").modal("hide");
            $("#wait").hide();
            toastr.success("Creado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
        return false;
    });
    $("#table_list_autorizaciones").on("click", ".btn_editar_autorizacion", async function (e) {
        var id = $(this).attr("data-id");
        $("#wait").show();
        let res = await expedientesService.editAutorizacion(id);
        $("#myformCreateAutorizacion").attr("id", "myformEditAutorizacion");
        $("#myformEditAutorizacion input[name=id]").val(res.id);
        $("#myformEditAutorizacion input[name=nombre_estudiante]").val(
            res.nombre_estudiante
        );
        $("#myformEditAutorizacion input[name=num_identificacion]").val(
            res.num_identificacion
        );
        $("#myformEditAutorizacion input[name=doc_expedicion]").val(
            res.doc_expedicion
        );
        $("#myformEditAutorizacion input[name=num_carne]").val(
            res.num_carne
        );
        $("#myformEditAutorizacion input[name=calidad_de]").val(
            res.calidad_de
        );
        $("#myformEditAutorizacion input[name=tipo_proceso]").val(
            res.tipo_proceso
        );
        $("#myformEditAutorizacion input[name=num_radicado]").val(
            res.num_radicado
        );
        $("#myformEditAutorizacion input[name=juzgado]").val(res.juzgado);
        $("#myformEditAutorizacion select[name=genero]").val(res.genero);
        $("#myformEditAutorizacion button")
            .removeClass("btn-primary")
            .addClass("btn-warning")
            .text("Actualizar").show();
        resetDisabledForm('myformEditAutorizacion');
        $("#mymodalCreateAutorizacion").modal("show");
        $("#wait").hide();
    });

    $("#table_list_autorizaciones").on("click", ".btn_detalles_autorizacion", async function (e) {
        var id = $(this).attr("data-id");
        $("#wait").show();
        let res = await expedientesService.editAutorizacion(id);
        $("#myformCreateAutorizacion").attr("id", "myformEditAutorizacion");
        //$("#myformEditAutorizacion input[name=id]").val(res.id);
        $("#myformEditAutorizacion input[name=nombre_estudiante]").val(
            res.nombre_estudiante
        );
        $("#myformEditAutorizacion input[name=num_identificacion]").val(
            res.num_identificacion
        );
        $("#myformEditAutorizacion input[name=doc_expedicion]").val(
            res.doc_expedicion
        );
        $("#myformEditAutorizacion input[name=num_carne]").val(
            res.num_carne
        );
        $("#myformEditAutorizacion input[name=calidad_de]").val(
            res.calidad_de
        );
        $("#myformEditAutorizacion input[name=tipo_proceso]").val(
            res.tipo_proceso
        );
        $("#myformEditAutorizacion input[name=num_radicado]").val(
            res.num_radicado
        );
        $("#myformEditAutorizacion input[name=juzgado]").val(res.juzgado);
        $("#myformEditAutorizacion select[name=genero]").val(res.genero);
        $("#myformEditAutorizacion button").hide();
        disabledForm('myformEditAutorizacion')
        $("#mymodalCreateAutorizacion").modal("show");
        $("#wait").hide();
    });

    $("#mymodalCreateAutorizacion").on("submit", "#myformEditAutorizacion", async function (e) {
        e.preventDefault();
        var errors = validateForm("myformEditAutorizacion");
        if (errors <= 0) {
            var request = convertFormToJSON("myformEditAutorizacion");
            $("#wait").show();
            let res = await expedientesService.updateAutorizacion(request, request.id);
            $("#table_list_autorizaciones tbody").html(res.view);
            $("#mymodalCreateAutorizacion").modal("hide");
            $("#myformEditAutorizacion")[0].reset();
            $("#myformEditAutorizacion button").removeClass("btn-warning").addClass("btn-primary").text("Crear");
            $("#myformEditAutorizacion").attr("id", "myformCreateAutorizacion");
            $("#wait").hide();
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
        }
    });
    $("#table_list_autorizaciones").on("click", ".btn_eliminar_autorizacion", function (e) {
        var id = $(this).attr("data-id");
        Swal.fire({
            title: 'Esta seguro de eliminar la autorización?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                let res = await expedientesService.deleteAutorizacion(id);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                $("#table_list_autorizaciones tbody").html(res.view);
                $("#wait").hide();
            }
        });
    });
    $("#table_list_autorizaciones").on("click", ".btn_change_estado_autorizacion", async function (e) {
        var id = $(this).attr("data-id");
        var request = { estado: $(this).attr("data-estado") == 0 ? 1 : 0, vista: "expedientes" };
        $("#wait").show();
        let res = await expedientesService.updateAutorizacion(request, id);
        $("#table_list_autorizaciones tbody").html(res.view);
        $("#wait").hide();
        toastr.success("Actualizado con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        return false;
    });
    $("#btnOpReasig").on("click", async function (e) {
        e.preventDefault();
        habilityButtReasCaso()
    });
    $("#btnReasignar").on("click", async function (e) {
        e.preventDefault();
        var request = {
            new_user_id: $("#numberest_id").val(),
            expid: $("#expid").val(),
            anotacion: $("#anotacion").val(),
            motivo_asig_id: $("#motivo_asig_id").val()
        }
        if ($("#expidnumberest").val() == request.new_user_id) {
            toastr.error("No puede ser el mismo estudiante", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            return;
        }
        var errors = validateForm("cont_anotacion");
        if (errors <= 0) {
            $("#wait").show();
            let res = await expedientesService.reasigCaso(request);
            toastr.success("Actualizado con éxito", "", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            window.location.reload(true);
        }
    });
    $("#btnCancReasig").on("click", function (e) {
        e.preventDefault();
        hideButtReasCaso();
    });

    $("#btn_cam_nt_act").on("click", function () {
        $("#myModal_act_details").modal("hide");
        //$("#myModal_edit_notas").modal("show");
        var actuacion_id = $("#actuacion_id").val();
        get_notas(actuacion_id, 2);
    });

});//////////////////////////////////////////////
function get_notas(tbl_id, origen) {
    var route = "/notas/" + tbl_id + "/edit";
    $.ajax({
        url: route,
        type: "GET",
        datatype: "json",
        data: { origen: origen },
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
            $("#wait").css("display", "block");
        },
        success: function (res) {
            // console.log(res);

            $("#myform_update_notas #nota_conocimiento").val(
                res.nota_conocimiento
            );
            $("#myform_update_notas #nota_conocimientoid").val(
                res.nota_conocimientoid
            );

            $("#myform_update_notas #nota_etica").val(res.nota_etica);
            $("#myform_update_notas #nota_eticaid").val(res.nota_eticaid);

            $("#myform_update_notas #nota_aplicacion").val(res.nota_aplicacion);
            $("#myform_update_notas #nota_aplicacionid").val(
                res.nota_aplicacionid
            );

            $("#myform_update_notas #nota_concepto").val(res.nota_concepto);
            $("#myform_update_notas #nota_conceptoid").val(res.nota_conceptoid);
            $("#myform_update_notas #lbl_nota_gen_caso").text(res.nota_final);

            //$("#myform_update_notas input[name='tbl_org_id']").val(res.nota_conceptoid);
            $("#myform_update_notas #origen").val(origen);
            $("#myform_update_notas #tbl_org_id").val(tbl_id);
            $("#myform_update_notas #lbldocevname").text(res.docevname);

            $("#myModal_edit_notas #btns_edit_notas").hide();
            $("#wait").css("display", "none");

            if (res.encontrado) {
                $("#myModal_edit_notas #lbl_periodo").text(res.periodo);
                $("#myModal_edit_notas #lbl_segmento").text(res.segmento);
                $("#myModal_edit_notas #lbl_tipo").text(res.tipo);
                $("#myModal_edit_notas #tipo_nota_id").val(res.tipo_id);
                var tipo = res.tipo_id == "1" ? "Parcial" : "Definitiva";
                $("#btn_tipo_update").text("Cambiar notas a: " + tipo);
                var tipo_id = res.tipo_id == "1" ? "2" : "1";

                if (res.can_edit) {
                    if (origen == 1 && $("#expestado_id").val() == "4") {
                        $("#btn_tipo_update").attr("data-value", tipo_id);
                        $("#btn_tipo_update").show();
                        $("#btn_tipo_update").attr(
                            "id",
                            "btn_tipo_nota_update"
                        );
                    }

                    $("#myModal_edit_notas #btns_edit_notas").show();
                    $("#btn_cambiar").attr("id", "btn_cambiar_notas");
                    $("#btn_delete").attr("id", "btn_delete_notas");
                    $("#btn_update").attr("id", "btn_update_notas");
                } else {
                    $("#btn_cambiar_notas").attr("id", "btn_cambiar");
                    $("#btn_delete_notas").attr("id", "btn_delete");
                    $("#btn_update_notas").attr("id", "btn_update");
                    //$("#btn_tipo_nota_update").attr('id', 'btn_update_tipo');
                }

                $("#myModal_edit_notas").modal("show");
            }

            if (origen == 3) {
                $("#myModal_edit_notas .fil_nt_co input[type='text']")
                    .attr("type", "hidden")
                    .prop("disabled", true);
                $("#myModal_edit_notas .fil_nt_co").hide();
                // hideElement('btn_delete_notas');
            } else {
                $("#myModal_edit_notas .fil_nt_co input[type='hidden']")
                    .attr("type", "text")
                    .prop("disabled", false);
                $("#myModal_edit_notas .fil_nt_co").show();
                showElement("btn_delete_notas");
                //if(origen == 2)   hideElement('btn_delete_notas');
            }
            hideEditNotas();
        },
        error: function (xhr, textStatus, thrownError) {
            alert(
                "Hubo un error con el servidor ERROR: " + thrownError,
                textStatus
            );
            $("#wait").css("display", "none");
        },
    });
}


function hideButtReasCaso() {
    hideElement("btnReasignar");
    hideElement("btnCancReasig");
    hideElement("cont_anotacion");
    showElement("btnOpReasig");
    $(".disabled-fun4").prop("disabled", true);
    $(".disabled-fun4").selectpicker("refresh");
}
function fillModalHistoryDataCase(response) {
    if (response == "") {
        $("#modal-conten-js").html('No hay información registrada');
    } else {
        var inforhis = "";
        $(response).each(function (key, value) {
            var fecha1 = moment($("#expediente_fecha_asig").val()).startOf('day');
            var fecha2 = moment(value.created_at).startOf('day');
            var fecha = fecha1.diff(fecha2, 'days') * -1;
            inforhis += `
            <div class="row">   
                <div class="col-md-7">
                    <label title="C.C. ${value.hisdc_idnumberest_id}">` + value.name + ' ' + value.lastname + ` </label>
                </div> 
                <div class="col-md-5">
                <label> Días después de la asignación: 
                <span class="badge ${fecha > 5 ? 'bg-red' : 'bg-green'} ">  ${fecha} </span>
                </label>
                </div>
                <div class="col-md-1">
                           
                </div>                        
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="cont-text">                                     
                        <textarea class="form-control textarea-asesorias-docente" readonly="" name="asesorias_docente" cols="50" rows="10">`+ value.hisdc_datos_caso + `</textarea>
                    </div>                                        
                    <div class="cont-fecha">
                    <i>	`+ value.created_at + `</i>
                </div>
                </div>
            </div><hr>`;



        });
        $("#modal-conten-js").html(inforhis);
        $("#mymodaljs").modal("show");
    }
}
function validarNotasUpdate(errors, form) {
    var notaapl = $("#" + form + " input[id=nota_aplicacion]").val();
    var notacon = $("#" + form + " input[id=nota_conocimiento]").val();
    var notaet = $("#" + form + " input[id=nota_etica]").val();
    if (notaapl > 5 || notacon > 5 || notaet > 5) {
        toastr.error("Por favor verifíque que no haya notas superiores a 5.0", "", {
            positionClass: "toast-top-right",
            timeOut: "6000",
        });
        errors.push("1");
    }
    if (isNaN(notaapl) || isNaN(notacon) || isNaN(notaet)) {
        toastr.error("Por favor verifíque que no haya notas con espacios o caracteres extraños", "", {
            positionClass: "toast-top-right",
            timeOut: "6000",
        });
        errors.push("1");
    }
    return errors;
}
function validarNotas(errors, form) {
    var notaapl = $("#" + form + " input[name=ntaaplicacion]").val();
    var notacon = $("#" + form + " input[name=ntaconocimiento]").val();
    var notaet = $("#" + form + " input[name=ntaetica]").val();
    if (notaapl > 5 || notacon > 5 || notaet > 5) {
        toastr.error("Por favor verifíque que no haya notas superiores a 5.0", "", {
            positionClass: "toast-top-right",
            timeOut: "6000",
        });
        errors.push("1");
    }
    if (isNaN(notaapl) || isNaN(notacon) || isNaN(notaet)) {
        toastr.error("Por favor verifíque que no haya notas con espacios o caracteres extraños", "", {
            positionClass: "toast-top-right",
            timeOut: "6000",
        });
        errors.push("1");
    }
    return errors;
}
function openCamNotas() {
    $("#myform_update_notas input[type='text']").prop("disabled", false);
    $("#myform_update_notas #nota_concepto").prop("disabled", false);
    $("#btn_cambiar_notas").hide();
    $("#btn_update_notas").show();
    $("#btn_cancelar_notas").show();
}
function lleFormEditNotas(res, origen, tbl_id) {
    $("#myform_update_notas #nota_conocimiento").val(
        res.nota_conocimiento
    );
    $("#myform_update_notas #nota_conocimientoid").val(
        res.nota_conocimientoid
    );

    $("#myform_update_notas #nota_etica").val(res.nota_etica);
    $("#myform_update_notas #nota_eticaid").val(res.nota_eticaid);

    $("#myform_update_notas #nota_aplicacion").val(res.nota_aplicacion);
    $("#myform_update_notas #nota_aplicacionid").val(
        res.nota_aplicacionid
    );

    $("#myform_update_notas #nota_concepto").val(res.nota_concepto);
    $("#myform_update_notas #nota_conceptoid").val(res.nota_conceptoid);
    $("#myform_update_notas #lbl_nota_gen_caso").text(res.nota_final);

    //$("#myform_update_notas input[name='tbl_org_id']").val(res.nota_conceptoid);
    $("#myform_update_notas #origen").val(origen);
    $("#myform_update_notas #tbl_org_id").val(tbl_id);
    $("#myform_update_notas #lbldocevname").text(res.docevname);

    $("#myModal_edit_notas #btns_edit_notas").hide();
    $("#wait").css("display", "none");
    if (res.encontrado) {
        $("#myModal_edit_notas #lbl_periodo").text(res.periodo);
        $("#myModal_edit_notas #lbl_segmento").text(res.segmento);
        $("#myModal_edit_notas #lbl_tipo").text(res.tipo);
        $("#myModal_edit_notas #tipo_nota_id").val(res.tipo_id);
        var tipo = res.tipo_id == "1" ? "Parcial" : "Definitiva";
        $("#btn_tipo_update").text("Cambiar notas a: " + tipo);
        var tipo_id = res.tipo_id == "1" ? "2" : "1";

        if (res.can_edit) {
            if (origen == 1 && $("#expestado_id").val() == "4") {
                $("#btn_tipo_update").attr("data-value", tipo_id);
                $("#btn_tipo_update").show();
                $("#btn_tipo_update").attr(
                    "id",
                    "btn_tipo_nota_update"
                );
            }

            $("#myModal_edit_notas #btns_edit_notas").show();
            $("#btn_cambiar").attr("id", "btn_cambiar_notas");
            $("#btn_delete").attr("id", "btn_delete_notas");
            $("#btn_update").attr("id", "btn_update_notas");
        } else {
            $("#btn_cambiar_notas").attr("id", "btn_cambiar");
            $("#btn_delete_notas").attr("id", "btn_delete");
            $("#btn_update_notas").attr("id", "btn_update");
            //$("#btn_tipo_nota_update").attr('id', 'btn_update_tipo');
        }
        $("#myModal_edit_notas").modal("show");
    }

    if (origen == 3) {
        $("#myModal_edit_notas .fil_nt_co input[type='text']")
            .attr("type", "hidden")
            .prop("disabled", true);
        $("#myModal_edit_notas .fil_nt_co").hide();
        // hideElement('btn_delete_notas');
    } else {
        $("#myModal_edit_notas .fil_nt_co input[type='hidden']")
            .attr("type", "text")
            .prop("disabled", false);
        $("#myModal_edit_notas .fil_nt_co").show();
        showElement("btn_delete_notas");
        //if(origen == 2)   hideElement('btn_delete_notas');
    }
    hideEditNotas();
}
function hideEditNotas() {
    $("#myform_update_notas input[type='text']").prop("disabled", true);
    $("#myform_update_notas #nota_concepto").prop("disabled", true);
    $("#btn_cambiar_notas").show();
    $("#btn_update_notas").hide()
    $("#btn_cancelar_notas").hide()

}
function llenarFormEditReq(res) {
    $("#reqcreated_at").val(moment(res.requerimiento.created_at, "YYYY-MM-DD").format("YYYY-MM-DD"))
    $("#reqid").val(res.requerimiento.id);
    $("#reqfecha_ed").val(res.requerimiento.reqfecha);
    $("#reqhora_ed").val(res.requerimiento.reqhora);
    $("#reqmotivo").val(res.requerimiento.reqmotivo);
    $("#reqdescrip").val(res.requerimiento.reqdescrip);
}
function llenarModalUpdateReq(res) {
    $("#reqcreated_at").val(moment(res.requerimiento.created_at, "YYYY-MM-DD").format("YYYY-MM-DD"))
    $("#req_id").val(res.requerimiento.id);
    $("#lab_cod_exp").text(res.requerimiento.expediente.expid);
    $("#lab_fech_crea").text(res.requerimiento.created_at);
    $("#lab_ced_solic").text(res.requerimiento.expediente.solicitante.idnumber);
    $("#lab_nom_solic").text(res.requerimiento.expediente.solicitante.name);
    $("#lab_apell_solic").text(res.requerimiento.expediente.solicitante.lastname);
    $("#lab_fech_cita").text(res.requerimiento.reqfecha);
    $("#lab_hora_cita").text(res.requerimiento.reqhora);
    $("#reqcomentario_est").val(res.requerimiento.reqcomentario_est);
    $("#reqcomentario_coorprac").val(res.requerimiento.reqcomentario_coorprac);
    $("#reqid_asistencia").val(res.requerimiento.reqid_asistencia);
}

function llenarModalDetailsReq(res) {
    $("#cont_notas_req").hide()
    $("#req_id_det").val(res.requerimiento.id);
    $("#lab_cod_exp_det").text(res.requerimiento.expediente.expid);
    $("#lab_fech_crea_det").text(res.requerimiento.created_at);
    $("#lab_ced_solic_det").text(res.requerimiento.expediente.solicitante.idnumber);
    $("#lab_nom_solic_det").text(res.requerimiento.expediente.solicitante.name);
    $("#lab_apell_solic_det").text(res.requerimiento.expediente.solicitante.lastname);
    $("#lab_fech_cita_det").text(res.requerimiento.reqfecha);
    $("#lab_hora_cita_det").text(res.requerimiento.reqhora);
    $("#lab_req_motivo_det").text(res.requerimiento.reqmotivo);
    $("#lab_req_descrip_det").text(res.requerimiento.reqdescrip);
    $("#lab_req_asistencia_det").text(res.requerimiento.req_asistencia.ref_reqasistencia);
    $("#lab_req_comcoor_det").text(res.requerimiento.reqcomentario_coorprac);
    $("#lab_req_comest_det").text(res.requerimiento.reqcomentario_est);
    $("#btn_cam_nt_req").hide();
    var segmento_id = $("#segmento_id").val();
    $("#btn_cam_nt_req").hide();
    if (res.requerimiento.notas_f.encontrado) {
        $("#lbl_not_etireq").text(res.requerimiento.notas_f.nota_etica);
        $("#ntaconcepto_req").text(res.requerimiento.notas_f.nota_concepto);
        $("#cont_notas_req #lbldocevname").text(res.requerimiento.notas_f.docevname);
        $("#cont_notas_req").show();
        if (segmento_id && res.requerimiento.notas_f.segmento_id && res.requerimiento.notas_f.can_edit) {
            $("#btn_cam_nt_req").show();
        }
    } else {
        if (res.requerimiento.notas != null && res.requerimiento.notas != '') {
            var notas = JSON.parse(res.requerimiento.notas);
            $("#lbl_not_etireq").text(notas.ntaetica);
            $("#ntaconcepto_req").text(notas.ntaconcepto);
            $("#cont_notas_req").show();
        }
    }
}

function llenarModalDetailsAct(res) {
    var name = res.user_created.name + " " + res.user_created.lastname
    $("#fullnameest").val(name)
    $("#myform_act_edit_docen input[name='actfecha']").val(res.created_at)
    $("#actfecha_det").val(res.created_at);
    $("#actnombre_det").val(res.actnombre);
    $("#actdescrip_det").val(res.actdescrip);
    $("#actestado_det").val(res.actestado_id);
    if (res.actestado_id == 176) {//$("#actestado").attr('selected',true);
        $("#actestado_det").prop('disabled', true).val(102);
    }
    $("#fecha_limit_d").val(res.fecha_limit);
    var fecha = moment(res.updated_at);
    var fechaFormateada = fecha.format('D [de] MMMM [de] YYYY');
    var text = `<br><small>${res.docente_update.name} ${res.docente_update.lastname} - ${fechaFormateada}</small>`;
    $("#label_nombre_docente").html(text);
    var rutadescarga = "/actpdfdownload/" + res.id + "/estudiante";
    if (res.actdocnompropio != '' && res.actdocruta != "" && res.actdocnompropio != null && res.actdocruta != null) {
        $("#lab-nombre-est").html('<a href="' + rutadescarga + '" target="_blank">' + res.actdocnompropio + '</a>');
    } else {
        $("#lab-nombre-est").html('<i>Sin archivo</i>');

    }

    var rutadescarga = "/actpdfdownload/" + res.id + "/docente";
    if (res.actdocnompropio_docente != '' && res.actdocnompropio_docente != null) {
        $("#lab-nombre-doc").html('<a href="' + rutadescarga + '" target="_blank">' + res.actdocnompropio_docente + '</a>');
    } else {
        $("#lab-nombre-doc").html('<i>Sin archivo</i>');

    }

    var segmento_id = $("#segmento_id").val();
    hideElement('btn_cam_nt_act');
    $("#cont_notas_ac").hide();
    if (res.notas_f.encontrado) {
        $("#lbl_not_conac").text(res.notas_f.nota_conocimiento);
        $("#lbl_not_aplac").text(res.notas_f.nota_aplicacion);
        $("#lbl_not_etiac").text(res.notas_f.nota_etica);
        $("#ntaconcepto_text").val(res.notas_f.nota_concepto);
        $("#cont_notas_ac #lbldocevname").text(res.notas_f.docevname);

        showElement('cont_notas_ac');
        console.log('ids', segmento_id, res.notas_f.segmento_id, res.notas_f.can_edit)
        if (segmento_id == res.notas_f.segmento_id && res.notas_f.can_edit) {
            showElement('btn_cam_nt_act');
        }

    } else {
        if (res.notas != null && res.notas != '') {
            var notas = JSON.parse(res.notas);
            $("#lbl_not_conac").text(notas.ntaconocimiento);
            $("#lbl_not_aplac").text(notas.ntaaplicacion);
            $("#lbl_not_etiac").text(notas.ntaetica);
            $("#ntaconcepto_text").val(notas.ntaconcepto);
            showElement('cont_notas_ac');
        }
    }

    $("#actuacion_id").val(res.id);

    $("#actdocenrecomendac_det").val(res.actdocenrecomendac);

}
function habilityButtReasCaso() {
    showElement("btnReasignar");
    showElement("btnCancReasig");
    showElement("cont_anotacion");
    hideElement("btnOpReasig");

    $(".disabled-fun4").prop("disabled", false);
    $(".disabled-fun4").selectpicker("refresh");
}

function abrirModalDocentes(res, option) {
    var options = "";
    $("#new_docente_id").selectpicker('destroy');;;
    options = '<option value="">Seleccione...</option>';
    for (var i = res.length - 1; i >= 0; i--) {
        if ($("#doc_id_number").val() != res[i].idnumber)
            options +=
                '<option value="' +
                res[i].idnumber +
                '">' +
                res[i].full_name.toUpperCase() +
                "</option>";
    }
    $("#new_docente_id").html(options);
    if (option != "") $("#myform_change_docente_exp #new_docente_id").append($(option));
    $("#myModal_change_docente_exp").modal("show");
    $("#new_docente_id").selectpicker('refresh');;;
}

async function changeSelectSearchExp(value) {
    var placeholder = "";
    $("#myformExpFilter input").prop("disabled", true).hide().val("");
    $("#myformExpFilter select[name='data']").prop("disabled", true).selectpicker('hide').val("");
    $("#myformExpFilter table").hide();
    $("#select_data_users").selectpicker('refresh');;;
    $("#myformExpFilter input[type='checkbox']").prop("disabled", false).show();
    switch (value) {
        case "idnumber_doc":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el nombre de un docente');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            break;

        case "solicitante":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el nombre de un solicitante');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();

            break;
        case "estudiante":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el nombre de un estudiante');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();

            break;
        case "estudiante_num":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el número de documento de un estudiante');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            break
        case "solicitante_num":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el número de documento de un consultante');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            break
        case "codido_exp":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Ingrese el número de expediente');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();

            break;
        case "estado":
            $("#myformExpFilter select[name='data']").prop("disabled", false).selectpicker('show');
            $("#select_data_users").attr('title', 'Seleccione un estado');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            var ref_estados = JSON.parse($("#ref_estados").val());
            $(".select_data_users").selectpicker('render');
            var opcion_busq = '';
            $(ref_estados).each(function (key, value) {
                opcion_busq += '<option value="' + value.id + '">' + value.nombre_estado + '</option>';
            });
            $("#select_data_users").append(opcion_busq);
            $(".select_data_users").selectpicker("refresh");
            break;
        case "tipo_consulta":
            $("#myformExpFilter select[name='data']").prop("disabled", false).show();
            $("#select_data_users").attr('title', 'Seleccione un tipo de consulta');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            var ref_estados = JSON.parse($("#ref_tipoproceso").val());
            $(".select_data_users").selectpicker('render');
            var opcion_busq = '';
            $(ref_estados).each(function (key, value) {
                opcion_busq += '<option value="' + value.id + '">' + value.ref_tipproceso + '</option>';
            });
            $("#select_data_users").append(opcion_busq);
            $(".select_data_users").selectpicker("refresh");
            break;
        case "fecha_creacion":

            $("#myformExpFilter input[id='data_date']").prop("disabled", false).show().val("");

            break;
        case "fecha_cita":
            $("#date_data").attr("placeholder", "yyyy/mm/dd");
            break;
        case "rama_derecho":
            $("#myformExpFilter select[name='data']").prop("disabled", false).show();
            $("#select_data_users").attr('title', 'Seleccione una rama del derecho');
            $('#select_data_users').selectpicker('destroy').html('').selectpicker();
            var ref_estados = JSON.parse($("#ref_ramaderecho").val());
            $(".select_data_users").selectpicker('render');
            var opcion_busq = '';
            $(ref_estados).each(function (key, value) {
                opcion_busq += '<option value="' + value.id + '">' + value.ramadernombre + '</option>';
            });
            $("#select_data_users").append(opcion_busq);
            $(".select_data_users").selectpicker("refresh");

            break;
        case "fecha_rango":
            $("#myformExpFilter table").show();
            $("#myformExpFilter input[name='dataIni']").prop("disabled", false).show().val("");
            $("#myformExpFilter input[name='dataFin']").prop("disabled", false).show().val("");

            break;
        case "all":
            $("#wait").show();
            window.location = '/expedientes'
            // $("#wait").hide();
            break;
        default:
    }
}