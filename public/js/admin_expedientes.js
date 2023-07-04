import { UserService } from './services/users.js';
import { ExpedientesService } from './services/expedientes.js';
const userService = new UserService();
const expedientesService = new ExpedientesService();
function init(){
    if($("expid")){
        var url = window.location.href;
        var activeTab = url.substring(url.indexOf("#") + 1);
        var elementoA = $("a[href='#"+activeTab+"']");      
        if(activeTab) elementoA.click();
    }
}
$(document).ready(function () {
    init();
    $("#btnCancelar").click(function () {
        $("#btnActualizar").hide();
        $("#btnCancelar").hide();
        $("#btnEditar").show();
        $(".disabled").prop('disabled',true);
        $(".disabled-fun3").prop("disabled", true);
        $(".disabled-fun3").selectpicker("refresh");
    });
    $("#btnActualizar").on("click",async function(e){
        var form = convertFormToJSON("form_expediente_edit");
        var request = {
            'id':form.expediente_id,
            'expramaderecho_id':form.expramaderecho_id,
            'exptipoproce_id':form.exptipoproce_id,
            'expidnumberest':form.expidnumberest,
            'oldexpidnumberest':form.oldexpidnumberest
        }
        $("#wait").show()
        let response = await expedientesService.update(form.expediente_id,request);
        
        toastr.success("Se actalizó con éxito", "", {
            positionClass: "toast-top-center",
            timeOut: "4000",
        });
          window.location.reload(true);

    });
    $("#btnEditar").click(function () {
        $("#btnActualizar").show();
        $("#btnCancelar").show();
        $("#btnEditar").hide();
        $(".disabled").prop("disabled",false);       
        $(".disabled-fun3").prop("disabled", false);
        $(".disabled-fun3").selectpicker("refresh");
        if ($("#oldexpidnumberest").val() == "") {
            $("#oldexpidnumberest").val($("#expidnumberest").val());
        }
    });
    $(".urlactive").on("click", function(){
        let stateObj = {
            foo: "nav",
        }
        history.pushState(stateObj, "menu", "edit"+$(this).attr("href"))
    });
    // $(".buscar_usuario").selectpicker();
    $('#table_list_model').on('click', "a.btn-edit-le", function () {
        var url = window.location;
        // Check browser support
        if (typeof (Storage) !== "undefined") {
            // Store
            localStorage.setItem("dirreg", url);
            // Retrieve
        }
    });
    $("a.btn-atrasexed").click(function(){
        window.location.href = localStorage.getItem("dirreg");
        });
    $("#myformExpFilter").submit(function (e) {
        e.preventDefault();
        var errors = validateForm("myformExpFilter");
        if (errors.length <= 0) {
            var page = $(this).attr("action");
            var data = $(this).serialize();
            index_page(page, data);
            window.history.pushState(null, "", page + "?" + data);
            //return false;
        }
        return false;
    });
    $("#myFormBsExpAdv").submit(function () {
        var errors = validateForm("myFormBsExpAdv");
        if (errors.length <= 0) {
            var page = $(this).attr("action");
            var data = $(this).serialize();
            index_page(page, data);
            window.history.pushState(null, "", page + "?" + data);
            $("#mymodalBuscarExpAvanzadas").modal("hide")
            //return false;
        }
        return false;
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
                    $("#expidnumberest").append('<option value="000000" data-content="<span class=\'label label-danger \'>ERROR AL CARGAR LOS DATOS</span> ">ERROR AL CARGAR LOS DATOS</option>');//coloca una nueva opcion
                    $(".estselect1").selectpicker("refresh");//refresca el select
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

    $('#myFormBsExpAdv').on('keyup', 'div.buscar_usuario input', async function (e) {
        let name = $(this).val();
        if (name.length >= 3) {

            $('div.buscar_usuario li.no-results').text('Buscando...');
            const response = await userService.findUserByNameOrLastNameAndRole({ 'name': name, 'role': 'estudiante' })
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
        var opselected = $("#myformExpFilter select[name='tipo_busqueda']").val();
        if (opselected != '' && (opselected == 'codido_exp' || opselected == 'solicitante_num')) {
            $(".select_data_users").selectpicker('render');//refresca el select
            opcion_busq = '<option value="' + name + '">' + name + '</option>';
            $("#select_data_users").html(opcion_busq);
            $(".select_data_users").selectpicker("refresh")
        } else if (opselected != '' && (opselected == 'solicitante' || opselected == 'idnumber_doc')) {
            if (name.length >= 3) {
                $('div.select_data_users li.no-results').text('Buscando...');
                var role = '';

                if (opselected == 'idnumber_doc') role = 'docente';
                if (opselected == 'solicitante') role = 'solicitante';
                if (opselected == 'solicitante_num') role = 'solicitante_num';
                let response;
                if (role == 'solicitante_num') {
                    let request = {
                        "idnumber": $(this).val(),
                        "validate_active": 0
                    }
                    // $("#wait").show();
                    response = await userService.findUsersByIdnumber(request);
                } else {
                    let request = {
                        'name': name,
                        'role': role
                    }
                    if (role == 'solicitante') request['validate_active'] = 0;
                    response = await userService.findUserByNameOrLastNameAndRole(request);

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
        $("#mymodalBuscarExpAvanzadas").modal("show")

    });


    $("#btn_exp_user_carga").on("click", async function () {
        let request = {
            "tipodoc_id": $(this).attr('data-tipo_doc'),
            "idnumber": $(this).val(),
            "view": "myforms.components_exp.frm_user_register"
        }
        $("#wait").show();
        let response = await userService.findUser(request);
        if (response.encontrado) {
            $("#content_user_exp_asig").html(response.view);
            toastr.success("Usuario encontrado", "", {
                positionClass: "toast-top-center",
                timeOut: "4000",
            });
            $("#myFormUserEditExpediente input[name='idnumber']").prop('disabled', true);
        }
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
            let response = await userService.findUser(request);
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

    $("#content_user_exp_asig").on("click", '#actualizar_exp_us', async function (e) {
        var errors = validateForm("myFormUserEditExpediente");
        if (errors.length <= 0) {
            var request = convertFormToJSON("myFormUserEditExpediente");
            var data = [];
            $("#myFormUserEditExpediente .input_user_ad").each((index, obj) => {
                data.push({
                    value: $(obj).attr("data-option") != undefined ? $(obj).val() : $(obj).find(":selected").text(),
                    section: $(obj).attr("data-section"),
                    type: $(obj).attr("data-type"),
                    name: $(obj).attr("data-name"),
                    option_id: $(obj).attr("data-option") != undefined ? $(obj).attr("data-option") : $(obj).val(),
                    value_is_other: $("#value_other_text-" + $(obj).attr('data-id')).val(),

                });
            });
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
            input: 'textarea',
            inputPlaceholder: '¿Por qué va a cerrar el caso?',
            inputAttributes: {
                rows: 100,  // Número de filas del textarea
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

    $("#btnTomarCaso").on("click", function (e) {
        //cabecera = '<h1><i class="fa fa-info"> </i> Atención </h1>';

        Swal.fire({
            title: 'Esta seguro de tomar el caso?',
            text: "Se asignará automaticamente!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, tomarlo!',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.value) {
              var  form = convertFormToJSON("form_expediente_edit");
             var   data = {
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
      
        e.preventDefault();
    });

    $("#myform_change_docente_exp").on("submit",async function (e) {
        e.preventDefault();
        let request = convertFormToJSON("myform_change_docente_exp");
        request['expid'] = $("#form_expediente_edit input[name='expediente_id']").val()
        $("#wait").show();
        let response = await expedientesService.gestionDocente(request);
        toastr.success("Actualizado con éxito", "", {
            positionClass: "toast-top-right",
            timeOut: "4000",
        });
        window.location.reload(true)
     });

    $("#btn_change_doc_exp").on("click",async function (e) {
        e.preventDefault();
        $("#titulo_modal").text("Cambiando docente");
        $("#myform_change_docente_exp>#tipo_cambio").val(1);       

        let response = await userService.getUsersByRole({'role':'docente'});
        if(response.encontrado){
            var opcion_busq = '';
            $("#new_docente_id").html('')
            $(response.users).each(function (key, value) {
                opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name + '</option>';
            });
            $("#new_docente_id").html(opcion_busq).selectpicker("refresh");;
            $("#myModal_change_docente_exp").modal("show");
        }
    });

    $("#btn_delete_doc_exp").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de eliminar la asignación del docente?',
            type: 'warning',
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

    $("#btn_send_exp_change").on("click",async function (e) {
        e.preventDefault();
        $("#titulo_modal").text("Solicitando cambio");
        $("#myform_change_docente_exp>#tipo_cambio").val(0);
        let response = await userService.getUsersByRole({'role':'docente'});
        if(response.encontrado){
            var opcion_busq = '';
            $("#new_docente_id").html('')
            $(response.users).each(function (key, value) {
                opcion_busq += '<option value="' + value.idnumber + '">' + value.full_name + '</option>';
            });
            $("#new_docente_id").html(opcion_busq).selectpicker("refresh");;
            $("#myModal_change_docente_exp").modal("show");
        }
    });

    $("#btn_cancel_change_doc_exp").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de eliminar la solicitud del docente?',
            type: 'warning',
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
            "exp_id":$("#expid").val()
        }
        Swal.fire({
            title: "Esta seguro de dar de baja el expediente?",
            text: "Se asignará un docente de pruebas!",
            type: "info",
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
                type: "info",                    
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

    $("#switch_shared_asesoria_caso").on("click",function(e){
    var check = $("#apl_shared");
    if (check.val() == "1") {
        check.val(0);
        $(this).removeClass('switch-on').addClass("switch-off");
    } else if (check.val() == "0") {
        check.val(1);
        $(this).removeClass('switch-off').addClass("switch-on");
    }
    })
});//////////////////////////////////////////////

async function changeSelectSearchExp(value) {
    var placeholder = "";
    $("#myformExpFilter input").prop("disabled", true).hide();
    $("#myformExpFilter select[name='data']").prop("disabled", true).selectpicker('hide');
    $("#myformExpFilter table").hide();
    $("#select_data_users").selectpicker('refresh');;;

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

