$(document).ready(function(){
    var idcon =$("#activar_salas_alternas_audiencia").attr("data-id")
    socket.on('LIuOgI52dWJxe0ZMRoomAlterConciliacionDisabled'+idcon, function(data){
        //deshabilita la entrada a salas alterna en las audiencias de conciliacion
       //console.log('desactiva el acceso a las salas alternas')
       $( "#btm_access_room_alter"+idcon).css("display","none");
       $("#btm_access_room_alter"+idcon).removeAttr('onclick');
    });
    getDatosAsigEstConciliacion(idcon)


   
    $( "#audiencia_fecha" ).change(function() {
        getColorTurno(this.value);
    });


    $("#btn_invitacion_sala_alterna").on('click', function () {
        $("#info_modal_salas_alternas_audiencia_list").html('')
        var id = $(this).attr('data-id')
        var route = "/conciliacion/numusers/salasalternasaudiencia/"+id;
        $.ajax({
            url: route,
            type: "GET",
            datatype: "json",
            data: {},
            cache: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
                $("#wait").show();
            },
            success: function (res) {
                //se crean las salas
                var cont = res.salas.length
                if (cont >= 1) {
                    const response = salasalternascreatebd(id,cont)
                    response.then(function (){
                            chekcedfuntion(res.salasalternas);
                            $("#nueva_sala_alterna_audiencia").attr("data-cont",cont+1)
                        })
                        .catch(function (errors) {
                        })
                    //se activan los check
                } else if (cont == 0) {
                    cont = 1
                    getSalasAudiencia(id,cont)
                    $("#nueva_sala_alterna_audiencia").attr("data-cont",cont+1)
                }
    
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
    
    
        $("#myModal_audiencia_salas_alternas").modal("show");
        $("#nueva_sala_alterna_audiencia").prop( "disabled", false );
    
    });

    $("#ul-users-chat").on("click",'.btn_open_chat',function (e) {
        var request = {
          "conciliacion_id": $("#conciliacion_id").val(),
          "useridnumber":$(this).attr("data-idnumber")
        }
        getChat(request)
    });

   var iframe = document.getElementsByClassName("embed-responsive-item")
   console.log(iframe[0]);

    $("iframe").contents().find("btn-message").on("click",function (e) {
       // alert("ss")
    });

});//fin document ready

function getColorTurno(value){
    let namecolors = ['Amarrillo','Azul','Verde','Gris','Rojo'];
        let daycolors = ["#fdd835","#0073b7","#00a65a","#a0afb3","#f56954"];
        var fecha1 = moment($("#prdfecha_inicio").val());//moment("2022-10-18");
        var day_fecha_ini= fecha1.day() - 1;// dia de la smeana de la fecha inicial lunes 0
        var fecha1 = fecha1.subtract(day_fecha_ini, "days");//inicia la semana siempre en lunes
        var fecha2 = moment(value);
        var semday = fecha2.day(); // dia de la semana, lunes inicia en 1
        var day = semday-1;//lunes inicia en 0
        var semanas = fecha2.diff(fecha1, 'weeks');
        var y = 0;
        for (var i = 0; i < semanas; i++) {
            y++
            if (y==5) {y=0}
        }
        var daysemcolor= day+y;
        if (daysemcolor > 4) {daysemcolor=daysemcolor-5;}
        $( "#audiencia_label_color_day" ).css("background-color", daycolors[daysemcolor])
                                        .html(namecolors[daysemcolor]);
}

function getChat(request) {
   
    var route = "/conciliacion/chat/"+request.useridnumber;    
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
        success: function (res) {           
            $("#chat-conciliacion").html(res)
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



function editRolEstudentAudiencia(idnumber) {
    $( "#label_rol_est"+idnumber).hide();
    $( "#select_rol_est"+idnumber).show();

}



function salasalternascreatebd(id,cont) {
    return new Promise(function(resolve) {
    var numsalasok = 0
    for (let i = 0; i < cont; i++) {
        getSalasAudiencia(id,i+1)
        .then(function (){
            numsalasok++
            if (numsalasok == cont) {
                resolve("ok");
            }
        })
        .catch(function (errors) {
        })
    }


    })
}

function chekcedfuntion(salasalternas) {
    salasalternas.forEach(user => {
        $("#"+user.access+"_"+user.idnumber).prop( "checked", true );
        $("#tr"+user.access+"_"+user.idnumber).css("text-decoration", "auto");


        //$(".salas_alternas_check_user_"+this.value).prop( "disabled", true );
        //$(".salas_alternas_check_user_"+this.value).prop( "checked", false );
        //$(this).prop( "disabled", false );
        //$(this).prop( "checked", true );
        //console.log("#info_modal_salas_alternas_audiencia_list #"+user.access+"_"+user.idnumber)
    });
}

$("#nueva_sala_alterna_audiencia").on('click', function () {
    var id = $(this).attr("data-id")
    var cont = $(this).attr("data-cont")
    var numusers = $(this).attr("data-numusers")
    var capacidadsalas = numusers/2
    //console.log(capacidadsalas)
    if (capacidadsalas >= cont) {
        $("#btn_eliminar_sala_alterna_"+(cont-1)).css("display", "none");
        $(this).attr("data-cont",parseInt(cont)+1)
        getSalasAudiencia(id,cont)
        //console.log(id)
    } else {
        Toast.fire({
            title: 'No hay suficientes usuarios para añadir más salas alternas.',
            type: 'warning',
            timer: 5000,
        });
        $(this).prop( "disabled", true );
    }
})

$("#info_modal_salas_alternas_audiencia_list").on('click', '.btn_salas_alternas_options', function () {
    var functions = $(this).attr("data-function");
    if (functions == 'entry') {

    } else if (functions == 'copy') {

    } else if (functions == 'delete') {
        var cont = $("#nueva_sala_alterna_audiencia").attr("data-cont")
        $("#nueva_sala_alterna_audiencia").attr("data-cont",parseInt(cont)-1)
        $("#btn_eliminar_sala_alterna_"+(cont-2)).css("display", "initial");
        //console.log("#sala_alterna_num_"+(cont-1));
        $("#sala_alterna_num_"+(cont-1)+" input[type=checkbox]:checked").each(function() { //detecta checks seleccionados para quitar el bloqueo de usuarios en otras salas
            $(".salas_alternas_user_"+this.value).css("text-decoration", "auto");
            $(".salas_alternas_check_user_"+this.value).prop( "disabled", false );
            //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado");
        });
        $("#sala_alterna_num_"+$(this).attr("data-info")).remove();
        $("#nueva_sala_alterna_audiencia").prop( "disabled", false );

    }

})

$("#info_modal_salas_alternas_audiencia_list").on('change', '.salas_alternas_check_user', function () {
//$('.salas_alternas_check_user').change(function() {

    if(this.checked) {
        $(".salas_alternas_user_"+this.value).css("text-decoration", "line-through");
        $("#tr"+this.id).css("text-decoration", "auto");
        $(".salas_alternas_check_user_"+this.value).prop( "disabled", true );
        $(".salas_alternas_check_user_"+this.value).prop( "checked", false );
        $(this).prop( "disabled", false );
        $(this).prop( "checked", true );
        this.setAttribute("checked", "checked");
        //console.log(this.id,"---",this.value)
    } else {
        $(".salas_alternas_user_"+this.value).css("text-decoration", "auto");
        $(".salas_alternas_check_user_"+this.value).prop( "disabled", false );
        this.removeAttribute("checked");
    }

});

function getSalasAudiencia(id,cont) {
    return new Promise(function(resolve) {
        var route = "/conciliacion/users/salasalternasaudiencia/"+id+"/"+cont;
        $.ajax({
            url: route,
            type: "GET",
            datatype: "json",
            data: {},
            cache: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
                $("#wait").show();
            },
            success: function (res) {
                $( "#info_modal_salas_alternas_audiencia_list" ).append( res.view );
                $("#info_modal_salas_alternas_audiencia_list input[type=checkbox]:checked").each(function() { //detecta checks seleccionados para bloquear usuarios en otras salas
                    $(".salas_alternas_user_"+this.value).css("text-decoration", "line-through");
                    $("#tr"+this.id).css("text-decoration", "auto");
                    $(".salas_alternas_check_user_"+this.value).prop( "disabled", true );
                    $(".salas_alternas_check_user_"+this.value).prop( "checked", false );
                    $(this).prop( "disabled", false );
                    $(this).prop( "checked", true );
                    //console.log("Checkbox " + $(this).prop("id") +  " (" + $(this).val() + ") Seleccionado"+this.value);
                });
                $("#wait").hide();
                resolve("1");
            },
            error: function (xhr, textStatus, thrownError) {
                /* alert(
                    "Hubo un error con el servidor ERROR::" + thrownError,
                    textStatus
                ); */
                $("#wait").hide();
            },
        });
    })
}

function postMsgSocketSend(room,data) {

    var route = "/msg/socketjs";
    $.ajax({
        url: route,
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        datatype: "json",
        data: {
            room: room,
            data: data
        },
        cache: false,
        beforeSend: function (xhr) {
            $("#wait").css("display", "block");
            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                $("#token").attr("content")
            );
        },
        success: function (res) {
            console.log('que jue vea2')
            $("#wait").css("display", "none");
        },
        error: function (xhr, textStatus, thrownError) {
            $("#wait").css("display", "none");
            alert(
                "Hubo un error con el servidor ERROR::" + thrownError,
                textStatus
            );
        },
    });
}

function postSalasAlternasSend(idnumbers,rooms,id) {
    var route = "/conciliacion/create/salasalternasaudiencia";
    $.ajax({
        url: route,
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        datatype: "json",
        data: {
            idnumbers: idnumbers,
            rooms: rooms,
            id: id
        },
        cache: false,
        beforeSend: function (xhr) {
            $("#wait").css("display", "block");
            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                $("#token").attr("content")
            );
        },
        success: function (res) {
            if (res == "1") {
                Toast.fire({
                    title: 'La invitación fue enviada con éxito.',
                    type: 'success',
                    timer: 5000,
                });
            }
            $("#wait").css("display", "none");
        },
        error: function (xhr, textStatus, thrownError) {
            $("#wait").css("display", "none");
            alert(
                "Hubo un error con el servidor ERROR::" + thrownError,
                textStatus
            );
        },
    });
}

$("#activar_salas_alternas_audiencia").on('click', function () {
    var id = $(this).attr("data-id")
    //envia mensaje socket desactivando todas las salas
    var roomdisabled = ["RoomAlterConciliacionDisabled"+id]
    //console.log(roomdisabled)
    var data = {"id_conciliacion":id}
    postMsgSocketSend(roomdisabled,data)

    var roomactiveuser = []
    var roomactivename = []
    $("#info_modal_salas_alternas_audiencia_list input[type=checkbox]:checked").each(function() { //detecta checks seleccionados para enviar la invitacion
        //pendiente2: enviar mesaje socket solo a selecionados
        roomactiveuser.push($(this).val())
        roomactivename.push($(this).attr('data-room'))
        //console.log("RoomAlterConciliacionactive"+$(this).val());
    });
    if (roomactiveuser.length <= 0) {
        var roomactiveuser = []
        var roomactivename = []
    }
        var id = {"id_conciliacion":id}
        postSalasAlternasSend(roomactiveuser,roomactivename,id)

    $("#myModal_audiencia_salas_alternas").modal("hide");
})


$("#btm_save_date_audiencia").on('click', function () {
    var id = $(this).attr("data-id")
    var fecha = $("#audiencia_fecha").val()
    var hora = $("#audiencia_hora").val()
    if (fecha != "" & hora != "" & id != "") {
        var route = "/conciliacion/audiencia/create";
        $.ajax({
            url: route,
            headers: { "X-CSRF-TOKEN": token },
            type: "POST",
            datatype: "json",
            data: {
                id:id, fecha:fecha, hora:hora
            },
            cache: false,
            beforeSend: function (xhr) {
                $("#wait").css("display", "block");
                xhr.setRequestHeader(
                    "X-CSRF-TOKEN",
                    $("#token").attr("content")
                );
            },
            success: function (res) {
                $("#wait").css("display", "none");
                Toast.fire({
                    title: 'La audiencia se ha guardado con exito.',
                    type: 'success',
                    timer: 5000,
                });
                location.reload();
            },
            error: function (xhr, textStatus, thrownError) {
                $("#wait").css("display", "none");
                alert(
                    "Hubo un error con el servidor ERROR:" + thrownError,
                    textStatus
                );
            },
        });
    } else {
        Toast.fire({
            title: 'Debe seleccionar la fecha y hora para poder guardar la audiencia.',
            type: 'warning',
            timer: 5000,
        });
    }
 
})

$("#btm_edit_date_audiencia").on('click', function () {
    $(this).css("display","none")
    $(".edit_audiencia").css("display","block")
    $(".edit_audiencia_existe").css("display","none")
});
$("#btm_cancel_date_audiencia").on('click', function () {
    $(this).css("display","none")
    $("#btm_edit_date_audiencia").css("display","block")
    $(".edit_audiencia").css("display","none")
    $(".edit_audiencia_existe").css("display","block")
})

$("#copy-stream-audiencia").on("click", function () {
    copiarAlPortapapeles("content-text-stream-audiencia");
});

function getDatosAsigEstConciliacion(idconciliacion) {
        var route = "/conciliacion/est/rol/"+idconciliacion;
        $.ajax({
            url: route,
            type: "GET",
            datatype: "json",
            data: {},
            cache: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
                $("#wait").show();
            },
            success: function (res) {

                $.each(res.usersrol, function (key, value) {
                    $("#label_rol_est_conciliacion"+value.idnumber).html(value.ref_nombre)
                                                                   .removeAttr( "style" );
                    $("#btn_habilityEditRol_Est"+value.idnumber).attr("data-state",value.tipo_usuario_id)
                    //console.log(value);
                });

                $.each(res.cont_est, function (key, value) {
                    $("#label_num_conciliacion_est"+value.idnumber).html(value.numconsi)
                                                                   .removeAttr( "style" );
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

function editRolEstudentAudiencia(idnumber) {
    var idrol = $("#btn_habilityEditRol_Est"+idnumber).attr('data-state')
    var stateoption = ""
    var route = "/conciliacion/estados/rol";
        $.ajax({
            url: route,
            type: "GET",
            datatype: "json",
            data: {},
            cache: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
                $("#wait").show();
            },
            success: function (res) {
                console.log(idnumber,idrol);
                $.each(res.rollist, function (key, value) {
                    stateoption = ""
                    if (idrol == value.id) { stateoption = "selected" }
                    $("#select_rol_est_conciliacion"+idnumber).append('<option value="' + value.id + '" '+ stateoption +'>' + value.ref_nombre + '</option>');
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

    $("#label_rol_est_conciliacion"+idnumber).css('display','none')
    $("#btn_habilityEditRol_Est"+idnumber).css('display','none')
    $("#select_rol_est_conciliacion"+idnumber).css('display','block')
    $("#btn_UpdateRol_est"+idnumber).css('display','block')
    $("#btn_hide_edit_rol_conciliacion_est"+idnumber).css('display','block')

}


function hideupdaterolest(idnumber) {
    $("#label_rol_est_conciliacion"+idnumber).show()
    $("#btn_habilityEditRol_Est"+idnumber).show()
    $("#select_rol_est_conciliacion"+idnumber).hide()
    $("#btn_UpdateRol_est"+idnumber).hide()
    $("#btn_hide_edit_rol_conciliacion_est"+idnumber).hide()

}

function Updaterolest_conciliacion(idnumber) {

    var idrol = $("#select_rol_est_conciliacion"+idnumber).val()
    var idconciliacion = $("#select_rol_est_conciliacion"+idnumber).attr('data-id')
    var textselect = $('select[id="select_rol_est_conciliacion'+idnumber+'"] option:selected').text()
    console.log(textselect)
    var email_categoria = '';
    if(idrol==203 || idrol==204){
        email_categoria = 'mensaje_sol_conciliador'
    }else{

    }
    var route = "/conciliacion/update/est/rolconciliacion";
    $.ajax({
        url: route, 
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        datatype: "json",
        data: {
            id:idnumber, idrol:idrol, idconciliacion:idconciliacion,categoria:email_categoria
        },
        cache: false,
        beforeSend: function (xhr) {
            $("#wait").css("display", "block");
            xhr.setRequestHeader(
                "X-CSRF-TOKEN",
                $("#token").attr("content")
            );
        },
        success: function (res) {
            if (res.state == 1 || res.state == true) {
                Toast.fire({
                    title: 'La asignación se ha actualizado con exito.',
                    type: 'success',
                    timer: 5000,
                });
                if (res.action == 'delete') {
                    $("#label_rol_est_conciliacion"+idnumber).html('sin asignar')
                                                             .css({"font-weight": "100", "font-size": "13px"});
                    $("#btn_habilityEditRol_Est"+idnumber).attr("data-state","")
                    $("#label_num_conciliacion_est"+idnumber).html($("#label_num_conciliacion_est"+idnumber).html()-1)
                                                                    .css("font-weight", "100");
                } else {
                    $("#label_rol_est_conciliacion"+idnumber).html(textselect)
                    .removeAttr( "style" );
                    $("#btn_habilityEditRol_Est"+idnumber).attr("data-state",idrol)
                    if (res.action == 'insert') {
                    $("#label_num_conciliacion_est"+idnumber).html(parseInt($("#label_num_conciliacion_est"+idnumber).html())+1)
                    .removeAttr( "style" );
                    }

                }
               // window.location.reload(true)
            } else {
                Toast.fire({
                    title: 'Error en la asignación, contactar al administrador.',
                    type: 'danger',
                    timer: 5000,
                });
            }
            //location.reload();
            $("#wait").css("display", "none");
        },
        error: function (xhr, textStatus, thrownError) {
            $("#wait").css("display", "none");
            alert(
                "Hubo un error con el servidor ERROR::" + thrownError,
                textStatus
            );
        },
    });

    $("#label_rol_est_conciliacion"+idnumber).css('display','block')
    $("#btn_habilityEditRol_Est"+idnumber).css('display','block')
    $("#select_rol_est_conciliacion"+idnumber).css('display','none')
    $("#btn_UpdateRol_est"+idnumber).css('display','none')
    $("#btn_hide_edit_rol_conciliacion_est"+idnumber).css('display','none')

    

}

$("#search_data_cursando_est_conciliacion").on('click', function () {
    var idcursando = $("#select_data_cursando_est_conciliacion").val()
    var idcon = $(this).attr('data-id')
    var route = "/conciliacion/turnos/estudiantes/asig/"+idcursando+"/"+idcon;
    listTrunosEstConciliacion(route,idcon)
})

$("#view_all_data_cursando_est_conciliacion").on('click', function () {
    var idcon = $(this).attr('data-id')
    var route = "/conciliacion/turnos/estudiantes/asig/all/"+idcon;
    listTrunosEstConciliacion(route,idcon)
})


function listTrunosEstConciliacion(route,idcon) {
    var route = "/conciliacion/turnos/estudiantes/asig/"+idcursando+"/"+idcon;
        $.ajax({
            url: route,
            type: "GET",
            datatype: "json",
            data: {},
            cache: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
                $("#wait").show();
            },
            success: function (res) {
               
                $("#list_turno_estudiantes_conciliacion").html(res)
                $("#wait").hide();
                getDatosAsigEstConciliacion(idcon)
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



$( "#chatroomconciliacion").on("change",function() {
    var route = "/conciliacion/chat/"+this.value;    
    $.ajax({
        url: route,
        type: "GET",
        datatype: "json",
        data: {},
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $("#token").attr("content"));
            $("#wait").show();
        },
        success: function (res) {           
            $("#chat-conciliacion").html(res)
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
});
