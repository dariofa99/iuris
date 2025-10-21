import { AgendasService } from './services/agendas.js';

const agendasService = new AgendasService();
$(document).ready(function () {
    $("#consultar_citas_dia").on("click", async function (e) {
        let response = await agendasService.searchCitasOfDay();
        var tr = `<tr>
                    <td colspan="3">No hay citas para el día de hoy.</td>
                </tr>`;
        if (response.length > 0) {
            tr = "";
            response.forEach(element => {
                tr += `<tr>
                        <td>${element.title}</td>
                        <td>${element.motivo}</td>
                        <td>${element.fecha_larga}</td>
                    </tr>`;
            });
        }
        $("#table_list_citas_day tbody").html(tr)
        $("#mymodalListaCitasDia").modal("show")
    });
});


document.addEventListener('DOMContentLoaded', function () {

    $("#docente_id").on("change", function (e) {
        let docente_id = $(this).val();
        $('#calendar').fullCalendar('removeEvents');
        $('#calendar').fullCalendar('removeEventSources');
        $('#calendar').fullCalendar('addEventSource', '/search/turn/of/teachers/?docente_id=' + docente_id);
        // $('#calendar').fullCalendar('refetchEvents');
        //showCalendar(docente_id);
    });

    $("#myFormAgendarTurnoDocente").on("click", ".btn_act_turno", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON("myFormAgendarTurnoDocente");
        request["estado_id"] = $(this).attr("data-status")
        var mensaje_accion = "";
        if (request["estado_id"] == 261) mensaje_accion = "¿Esta segur@ de actualizar el turno?";
        if (request["estado_id"] == 262) mensaje_accion = "¿Esta segur@ de aprobar el turno?";
        if (request["estado_id"] == 263) mensaje_accion = "¿Esta segur@ de rechazar el turno?";
        Swal.fire({
            title: mensaje_accion,
            icon: 'info',
            //text: "Recuerde que no se puede revertir los cambios.",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Si, actualizar',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show()
                var response = await agendasService.actualizarTurnoDocente(request);
                if (response.success === false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });

                } else if (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: response.message,
                    })
                }
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('refetchEvents');
                $("#mymodalAgendaCitacionWithDocente").modal("hide")
                $("#wait").hide()
            }
        });
        $("#mymodalAgendaCitacionWithDocente").modal("hide")
    });


    $("#myFormAgendarTurnoDocente").on("click", "#btn_asig_turno", async function (e) {
        e.preventDefault();
        var request = convertFormToJSON("myFormAgendarTurnoDocente");

        Swal.fire({
            title: '¿Esta segur@ de agendar el turno?',
            icon: 'info',
            //text: "Recuerde que no se puede revertir los cambios.",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Si, agendar',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show()
                var response = await agendasService.agendarTurnoDocente(request);

                if (response.success === false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });

                } else if (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: response.message,
                    })
                }
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('refetchEvents');
                $("#mymodalAgendaCitacionWithDocente").modal("hide")
                $("#wait").hide()
            }
        });

    });

    $("#myFormAgendarTurnoDocente").on("click", "#btn_delete_turno", async function (e) {
        e.preventDefault();
        var request = $(this).attr("data-id")

        Swal.fire({
            title: '¿Esta seguro@ de eliminar el turno?',
            icon: 'info',
            text: "Recuerde que no se puede revertir los cambios.",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            /* cancelButtonColor: '#d33', */
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show()
                var response = await agendasService.eliminarTurnoDocente(request);

                if (response.success === false) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                    });

                } else if (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Exito',
                        text: response.message,
                    })
                }

                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('refetchEvents');
                $("#mymodalAgendaCitacionWithDocente").modal("hide")
            }
        });


        $("#wait").hide()
    });

    showCalendar(1232541);

    // showCalendar();

    function showCalendar(docente_id) {
        $("#wait").show();
        //    $('#calendar').fullCalendar('destroy');

        // $("#wait").css("display", "block");
        var calendarEl = document.getElementById('calendar');
        var infoEvent;
        /* initialize the external events
         -----------------------------------------------------------------*/
        function init_events(ele) {
            $("#wait").show();
            ele.each(function () {

                var eventObject = {
                    title: $.trim($(this).text()) // use the element's text as the event title
                }
                $(this).data('eventObject', eventObject)
                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 1070,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                })
            })
            $("#wait").hide();
        }

        init_events($('#external-events div.external-event'))
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'hoy',
                month: 'mes',
                week: 'semana',
                day: 'día'
            },
            defaultView: 'month',

            // 🔹 Evita mostrar días de otros meses
            showNonCurrentDates: false,  // Oculta días del mes anterior/siguiente
            fixedWeekCount: false,      // o 'agendaWeek'
            allDaySlot: false,            // quita el slot de "todo el día"
            minTime: "07:00:00",          // hora mínima visible
            maxTime: "18:00:00",          // hora máxima visible
            slotDuration: "00:40:00",
            //Random default events
            events: '/search/turn/of/teachers/',
            editable: false,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function (date, allDay) { // this function is called when something is dropped
            },
            loading: function (isLoading) {
                if (isLoading) {
                    $("#wait").show();  // Muestra el loader
                } else {
                    $("#wait").hide();  // Oculta el loader
                }
            },
            eventRender: function (event, element) {
                // Personaliza el contenido del evento con un salto de línea
                const start = moment(event.start).format('HH:mm');
                const end = moment(event.end).format('HH:mm');
                const estado = event.title == "" ? "Disponible" : "";
                element.find('.fc-time').html(`${start} - ${end}: ${estado}`);
                if (event.tipo == "extra") {
                    element.find('.fc-time').html("Extra");
                }

                // cambia el contenido del evento
                //    element.find('.fc-title').html(`${start} - ${end}<br>${event.title}`);
                const formattedTitle = event.title.replace("-", "<br>");
                element.find('.fc-title').html("<br>" + formattedTitle);
                $("#wait").hide();
            },
            eventClick: function (calEvent, jsEvent, view) {
                const formattedTitle = calEvent.title.replace("-", "<br>");
                $("#ct_forcitaest h5[id='title']").html(formattedTitle)
                $("#ct_forcitaest p[id='motivo']").text(calEvent.motivo)
                $("#ct_forcitaest span[id='docente_nombre']").text(calEvent.docente_nombre)
                $("#ct_forcitaest p[id='fecha_larga']").text(calEvent.fecha_larga)

                const start = moment(calEvent.start);
                const end = moment(calEvent.end);
                const docenteId = calEvent.docente;

                const fecha = start.format("YYYY-MM-DD");
                const horaInicio = start.format("HH:mm");
                const horaFin = end.format("HH:mm");
                $("#info_adicional_turno").hide();
                if (calEvent.role_user == 'estudiante') {
                    if (calEvent.estado !== 'libre') {
                        $("#ct_forcitaest_btn button[id='btn_asig_turno']").remove(); // Quitar el botón de enviar si está ocupado
                    } else {
                        //agregar el boton si no existe
                        if ($("#ct_forcitaest_btn button[id='btn_asig_turno']").length === 0) {
                            const boton = `<button type="button" id="btn_asig_turno" class="btn btn-success btn-block">Solicitar turno</button>`;
                            $("#ct_forcitaest_btn").append(boton);
                        }
                    }

                    if (calEvent.can_delete === true && (calEvent.estado == 260 || calEvent.estado == 262)) {
                        $("#ct_forcitaest_btn button[id='btn_delete_turno']").remove();
                        const boton = `<button type="button" data-id="${calEvent.turno_id}" id="btn_delete_turno" class="btn btn-danger btn-block"><i class="fas fa-trash"></i> Eliminar turno</button>`;
                        $("#ct_forcitaest_btn").append(boton);


                    } else {
                        //agregar el boton si no existe
                        $("#ct_forcitaest_btn button[id='btn_delete_turno']").remove(); // Quitar el botón de enviar si está ocupado
                    }

                    if (calEvent.tipo === "extra" && calEvent.estado == 'libre') {
                        $("#info_adicional_turno").show();
                    }
                }
                if (calEvent.role_user == 'docente') {


                    if (calEvent.tipo === "normal") {
                        $("#ct_forcitaest_btn input[name='turno_id']").remove();
                        $("#ct_forcitaest_btn .btn_act_turno").remove(); // Quitar el botón de enviar si está ocupado
                        if (calEvent.estado === 260) {
                            $("#ct_forcitaest_btn .btn_act_turno").remove();
                            $("#ct_forcitaest_btn input[name='turno_id']").remove();
                            //if ($("#ct_forcitaest_btn button[id='btn_asig_turno']").length === 0) {
                            const boton = `<button type="button" data-status="261" id="btn_act_turno" class="btn btn-success btn-block btn_act_turno">Marcar atendido</button>`;
                            const txtid = `<input type="hidden" value="${calEvent.turno_id}" name="turno_id">`;
                            $("#ct_forcitaest_btn").append(boton);
                            $("#ct_forcitaest_btn").append(txtid);
                            // }

                        }
                    }


                    if (calEvent.tipo === "extra") {
                        $("#ct_forcitaest_btn .btn_act_turno").remove();
                        $("#ct_forcitaest_btn").find(".btn_act_turno").remove();
                        $("#ct_forcitaest_btn input[name='turno_id']").remove();
                        if (calEvent.estado === 260) {
                            $("#ct_forcitaest_btn").find(".btn_act_turno").remove();
                            $("#ct_forcitaest_btn .btn_act_turno").remove();
                            $("#ct_forcitaest_btn input[name='turno_id']").remove();
                            //if ($("#ct_forcitaest_btn button[id='btn_asig_turno']").length === 0) {
                            const boton = `<button type="button" data-status="262" class="btn_act_turno btn btn-success btn-block">Aprobar turno</button>`;
                            const botonD = `<button type="button" data-status="263" class="btn_act_turno btn btn-warning btn-block">Rechazar turno</button>`;
                            const txtid = `<input type="hidden" value="${calEvent.turno_id}" name="turno_id">`;
                            $("#ct_forcitaest_btn").append(boton);
                            $("#ct_forcitaest_btn").append(botonD);
                            $("#ct_forcitaest_btn").append(txtid);
                            // }

                        }

                        if (calEvent.estado === 262) {
                            $("#ct_forcitaest_btn .btn_act_turno").remove();
                            $("#ct_forcitaest_btn input[name='turno_id']").remove();
                            //if ($("#ct_forcitaest_btn button[id='btn_asig_turno']").length === 0) {
                            const boton = `<button type="button" data-status="261" id="btn_act_turno" class="btn btn-success btn-block btn_act_turno">Marcar atendido</button>`;
                            const txtid = `<input type="hidden" value="${calEvent.turno_id}" name="turno_id">`;
                            $("#ct_forcitaest_btn").append(boton);
                            $("#ct_forcitaest_btn").append(txtid);
                            // }

                        }
                    }

                }
                // Llenar campos del modal
                $("#turnoFecha").val(fecha);
                $("#turnoHoraInicio").val(horaInicio);
                $("#turnoHoraFin").val(horaFin);
                $("#turnoDocenteId").val(docenteId);

                // Rellenar campos del modal
                $("#turnoFecha").val(fecha);
                $("#turnoHoraInicio").val(horaInicio);
                $("#turnoHoraFin").val(horaFin);


                $("#mymodalAgendaCitacionWithDocente").modal("show")
            },
            dayClick: function (date, jsEvent, view) {
                // alert('Has hecho click en: ' + date.format());
                $('.fc-day').css('background-color', '#fff');
                $(this).css('background-color', '#ededed');
            }
        });
    }
});

