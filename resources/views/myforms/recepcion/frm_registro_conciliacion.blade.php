@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card">
            <div class="card-header">
            

            </div>

            <div class="card-body" >
              
                @include('myforms.conciliaciones.conciliacion_form')
                 
            </div>
          </div>
          </div>
        </div>
</div>
@include('myforms.conciliaciones.componentes.modal_create_user')
@endsection

@push('scripts')
<!-- aqui van los scripts de cada vista -->
<script>
$(document).ready(function(){
    $(".btn_asinar_usuario_conciliacion").on("click", function (e) {
        
        var data_type = $(this).attr("data-type");
        $("#myModal_conc_user_create input[name=tipo_usuario_id]").val(data_type);
        $("#myModal_conc_user_create input[name=section]").val($(this).attr('data-section'));
        var request = {          
            'conciliacion_id':$("#conciliacion_id").val(),          
            'data_type':data_type,
            'section':$(this).attr('data-section'),
        }
        if ($(this).attr('data-user')!=undefined) {
            request['idnumber'] = $(this).attr('data-user');
            request['tipodoc_id'] = $("#myModal_conc_user_create select[name=tipodoc_id]").val();  
            request['is_edit'] = true;  
        }else{
            request['idnumber'] = '0';
            request['tipodoc_id'] = 1;          
        }        
        getUser(request,request.idnumber);
        $("#myModal_conc_user_create").modal("show"); 
    });

    $("#myModal_conc_user_create").on("blur", ".findUser", function (e) {
        
        if (
            this.value != "" &&
            $("#myformCreateConciliacionUser select[name=tipodoc_id]").val() != ""
        ) {
            var request = {
                idnumber: $(this).val(),
                tipodoc_id: $("#myformCreateConciliacionUser select[name=tipodoc_id]").val(),
            };
            findUser(request);
        }
        
    });


});/////

function getUser(request,idnumber) {
    var route = "/conciliacion/user/"+idnumber+"";
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
        success: function (res) {
       
            $("#content_form_user").html(res.view)
            $("#myModal_conc_user_create #type_solicitud_user").val(request.data_type);
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
    
function findUser(request) {
    var route = "/users/find/us";
    $.ajax({
        url: route,
        type: "GET",
        datatype: "json",
        data: request,
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
            $("#wait").css("display", "block");
        },
        success: function (res) {
            // $("#table_list_autorizaciones tbody").html(res.view)
            if (res.encontrado) {
                $("#myformCreateConciliacionUser input[name=id]")
                    .val(res.user.id)
                    .prop("disabled", false);
                $("#myformCreateConciliacionUser input[name=idnumber]").val(
                    res.user.idnumber
                );
                $("#myformCreateConciliacionUser input[name=name]")
                    .val(res.user.name)
                    .prop("disabled", true);
                $("#myformCreateConciliacionUser input[name=lastname]")
                    .val(res.user.lastname)
                    .prop("disabled", true);
                $("#myformCreateConciliacionUser input[name=email]")
                    .val(res.user.email)
                    .prop("disabled", true);
                $("#myformCreateConciliacionUser input[name=tel1")
                    .val(res.user.tel1)
                    .prop("disabled", true);
                if (res.user.roles.length > 0) {
                    $("#myformCreateConciliacionUser #lbl_role_name").text(
                        res.user.roles[0].display_name
                    );
                    $("#myformCreateConciliacionUser select[name=idrol")
                        .val(res.user.roles[0].id)
                        .prop("disabled", true)
                        .hide();
                }
            } else {
                $("#myformCreateConciliacionUser input[name=id]")
                    .val("")
                    .prop("disabled", true);
                $("#myformCreateConciliacionUser input[name=name]")
                    .val("")
                    .prop("disabled", false);
                $("#myformCreateConciliacionUser input[name=lastname]")
                    .val("")
                    .prop("disabled", false);
                $("#myformCreateConciliacionUser input[name=email]")
                    .val("")
                    .prop("disabled", false);
                $("#myformCreateConciliacionUser input[name=tel1")
                    .val("")
                    .prop("disabled", false);
                $("#myformCreateConciliacionUser #lbl_role_name").text("");
                $("#myformCreateConciliacionUser select[name=idrol")
                    .prop("disabled", false)
                    .show();
            }
            $("#wait").css("display", "none");
        },
        error: function (xhr, textStatus, thrownError) {
            alert(
                "Hubo un error con el servidor, consulte con el administrador"
            );
            $("#wait").css("display", "none");
        },
    });
}

</script>
@endpush