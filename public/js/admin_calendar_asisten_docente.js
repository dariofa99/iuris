import { HorariosDocenteService } from "./services/horarios_docente.js";
const horariosDocenteService = new HorariosDocenteService();
$(function () {

    showCalendar();

    $("#docente_id").on("change", async () => {
        $("#calendar").fullCalendar('refetchEvents');
    })

    $("#btnEliminarAsistenciaDocente").click(async function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de eliminar la asistencia del turno?',
            text: "Los cambios no podran ser revertidos!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No, cancelar'
        }).then(async (result) => {
            if (result.value) {
                $("#wait").show();
                var request = convertFormToJSON("turnosdoc");

                let response = await horariosDocenteService.deleteAsistencia(request.asistencia_id, request);
                toastr.success("Eliminado con éxito", "", {
                    positionClass: "toast-top-right",
                    timeOut: "4000",
                });
                $("#calendar").fullCalendar('refetchEvents');
                $("#myModal_reporasistencia").modal('hide');
                //window.location.reload(true);
            }
        });


    });
    $("#btnRegistrarTurnoDocente").click(async function (e) {
        e.preventDefault();
        // $("#wait").show();
        const form = document.getElementById("turnosdoc");
        if (!form) return;
        var isvalid = validateForms(form);
        console.log(form);

        if (isvalid) {
            //form.requestSubmit();
            var request = convertFormToJSON("turnosdoc");
            request.hora_inicio = normalizarHora(request.hora_inicio);
            request.hora_fin = normalizarHora(request.hora_fin);

            let response = await horariosDocenteService.store_asistencia(request);
            $("#calendar").fullCalendar('refetchEvents');
            $("#myModal_reporasistencia").modal('hide');
            toastr.success("Asistencia registrada correctamente", "Éxito!", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });

        } else {
            toastr.error("Hay campos que son obligatorios", "Atención!", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            form.reportValidity();
        }
    });

    $("#btnActualizarTurnoDocente").click(async function (e) {
        e.preventDefault();
        // $("#wait").show();
        const form = document.getElementById("turnosdoc");
        if (!form) return;
        var isvalid = validateForms(form);
        console.log(form);

        if (isvalid) {
            //form.requestSubmit();
            var request = convertFormToJSON("turnosdoc");
            request.hora_inicio = normalizarHora(request.hora_inicio);
            request.hora_fin = normalizarHora(request.hora_fin);

            let response = await horariosDocenteService.updateAsistencia(request);
            $("#calendar").fullCalendar('refetchEvents');
            $("#myModal_reporasistencia").modal('hide');
            toastr.success("Asistencia actualizada correctamente", "Éxito!", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });

        } else {
            toastr.error("Hay campos que son obligatorios", "Atención!", {
                positionClass: "toast-top-right",
                timeOut: "4000",
            });
            form.reportValidity();
        }
    })


    $(".iuris-novedades").on('change', 'input[type="radio"]', async function (e) {
        e.preventDefault();
        console.log($(this).val());
        var tipo_asis = $(this).val();
        $("#div_reposicion").html("");
        if (tipo_asis == "150") {
            var content = contentRepos();
            $("#div_reposicion").html(content);
            $("#div_reposicion").show();
        } else {
            $("#div_reposicion").html("");
            $("#div_reposicion").hide();
        }

    })

});

function normalizarHora(hora) {
    if (!hora || hora.length !== 5) return hora;
    return `${hora}:00`;
}


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


function showCalendar(docente_id) {
    $("#wait").show();
    //    $('#calendar').fullCalendar('destroy');

    // $("#wait").css("display", "block");
    var calendarEl = document.getElementById('calendar');
    var infoEvent;
    /* initialize the external events
     -----------------------------------------------------------------*/


    //init_events($('#external-events div.external-event'))


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
        showNonCurrentDates: false, // Oculta días del mes anterior/siguiente
        fixedWeekCount: false, // o 'agendaWeek'
        allDaySlot: false, // quita el slot de "todo el día"
        minTime: "07:00:00", // hora mínima visible
        maxTime: "18:00:00", // hora máxima visible
        slotDuration: "00:40:00",
        hiddenDays: [0, 6],
        //Random default events
        events: function (start, end, timezone, callback) {

            const docenteId = $('#docente_id').val();

            $.ajax({
                url: '/horarios/docentes/',
                type: 'GET',
                data: {
                    docente_id: docenteId,
                    start: start.format('YYYY-MM-DD'),
                    end: end.format('YYYY-MM-DD')
                },
                success: function (response) {
                    callback(response);
                },
                error: function (xhr) {
                    console.error(xhr);
                    callback([]);
                }
            });

        },
        editable: true, // 🔹 Permite mover eventos (drag & drop)
        // eventStartEditable: true, // 🔹 Permite mover el inicio del evento
        eventDurationEditable: true, // 🔹 Permite cambiar la duración
        droppable: true, // 🔹 Permite arrastrar desde fuera si usas “external events”
        eventOverlap: true, // 🔹 Permite superponer eventos
        eventConstraint: null, // 🔹 Permite mover fuera del horario
        eventDidMount: function (info) {
            if (info.event.extendedProps.tipo === 'estudiante') {
                info.el.style.cursor = 'default'; // cambia el cursor
            }


        },
        drop: function (date) {
            alert("Dropped on " + date.format());
        },
        eventDrop: function (event, delta, revertFunc) {


        },

        loading: function (isLoading) {
            if (isLoading) {
                $("#wait").show(); // Muestra el loader
            } else {
                $("#wait").hide(); // Oculta el loader
            }
        },
        eventRender: function (event, element, view) {

            const start = moment(event.start).format('HH:mm');
            const end = moment(event.end).format('HH:mm');


            /*
            |--------------------------------------------------------------------------
            | Imagen del docente
            |--------------------------------------------------------------------------
            */

            const image = event.image ?
                event.image :
                '/thumbnails/default.jpg';


            /*
            |--------------------------------------------------------------------------
            | Contenido del evento
            |--------------------------------------------------------------------------
            */
            var text = '';
            if (event.asistencia_id != null) {
                console.log(event);
                text = `(${moment(event.hora_inicio_asis, "HH:mm:ss").format("h:mm A")} - ${moment(event.hora_fin_asis, "HH:mm:ss").format("h:mm A")})`;
            }

            const contenido = `

             <div class="row">
                                <div class="col-md-12">
                                     <div class="evento-docente-hora">
                                        ${moment(start, "HH:mm:ss").format("h:mm A")} - ${moment(end, "HH:mm:ss").format("h:mm A")}
                                    </div>
                                </div>                         
                               
                                <div class="col-md-12">
                                      <div class="evento-docente-titulo">
                                        ${event.title}
                                    </div>
                                </div>    
                                
                                <div class="col-md-12">
                                    <div class="evento-docente-estado" style="background-color: ${event.tipo_asis_color}; font-weight: bold;">
                                        ${event.tipo_asis_nombre} ${text}
                                    </div>
                                </div>
                             </div> `;






            /*
            |--------------------------------------------------------------------------
            | Reemplazar contenido de FullCalendar
            |--------------------------------------------------------------------------
            */

            element
                .find('.fc-content')
                .html(contenido);


            /*
            |--------------------------------------------------------------------------
            | Estilos del evento
            |--------------------------------------------------------------------------
            */

            element.find('.fc-content').css({
                'padding-right': '15px',

            });


            /*
            |--------------------------------------------------------------------------
            | Evento extra
            |--------------------------------------------------------------------------
            */

            if (event.tipo == "extra") {

                element
                    .find('.evento-docente-hora')
                    .html('Extra');
            }

        },
        eventClick: function (calEvent, jsEvent, view) {
            console.log(calEvent);
            $("#turnosdoc #asistencia_id").val("");
            $("#turnosdoc #btnRegistrarTurnoDocente").text("Guardar asistencia").prop("disabled", false).show();
            $("#turnosdoc #btnActualizarTurnoDocente").text("Actualizar asistencia").prop("disabled", true).hide();
            $("#turnosdoc #btnEliminarAsistenciaDocente").text("Eliminar asistencia").prop("disabled", true).hide();
            $("#div_reposicion").hide();
            $("#div_reposicion").html("");
            resetDisabledForm("turnosdoc");

            //limpiarForm("turnosdoc");

            $("#turnosdoc #hora_inicio").val(calEvent.hora_inicio);
            $("#turnosdoc #turno_id").val(calEvent.id);
            $("#turnosdoc #fecha_turno").val(calEvent.fecha_turno);
            $("#turnosdoc #hora_fin").val(calEvent.hora_fin);
            $("#turnosdoc #nombre_docente").text(calEvent.nombre);
            $("#turnosdoc #avatar_docente").attr("src", calEvent.image);
            $("#turnosdoc #avatar_docente").attr("alt", calEvent.nombre);
            $(".iuris-novedades input[value='149']").prop("checked", true);
            $("#descripregisdocasis").val("");
            if (calEvent.asistencia_id != null) {
                $("#turnosdoc #btnEliminarAsistenciaDocente").text("Eliminar asistencia").prop("disabled", false).show();
                $("#turnosdoc #btnActualizarTurnoDocente").text("Actualizar asistencia").prop("disabled", false).show();
                $("#turnosdoc #btnRegistrarTurnoDocente").text("Guardar asistencia").prop("disabled", true).hide();
                $(".iuris-novedades input[type='radio']").filter(`[value="${calEvent.tipo_asis}"]`).prop("checked", true);
                $("#turnosdoc #hora_inicio").val(calEvent.hora_inicio_asis);
                $("#turnosdoc #hora_fin").val(calEvent.hora_fin_asis);
                $("#descripregisdocasis").val(calEvent.descripcion);
                $("#turnosdoc #asistencia_id").val(calEvent.asistencia_id);
                if (calEvent.tipo_asis == 150) {
                    $("#div_reposicion").show();
                    $("#div_reposicion").html(contentRepos());

                    $("#turnosdoc #fecha_repo").val(calEvent.fecha_reposicion);
                    $("#turnosdoc #hora_inicio_repo").val(calEvent.hora_inicio_reposicion);
                    $("#turnosdoc #hora_fin_repo").val(calEvent.hora_fin_reposicion);
                    disabledForm("turnosdoc")
                    $("#turnosdoc #btnActualizarTurnoDocente").text("Debe eliminar la reposición").prop("disabled", true).show();
                    $("#turnosdoc #btnEliminarAsistenciaDocente").text("Eliminar asistencia").prop("disabled", true).show();
                    if (!calEvent.has_reposicion) {
                        $("#turnosdoc #btnActualizarTurnoDocente").text("Actualizar asistencia").prop("disabled", false).show();
                        $("#turnosdoc #btnEliminarAsistenciaDocente").text("Eliminar asistencia").prop("disabled", false).show();
                        resetDisabledForm("turnosdoc")
                        $("#turnosdoc #fecha_repo").val("");
                        $("#turnosdoc #hora_inicio_repo").val("");
                        $("#turnosdoc #hora_fin_repo").val("");
                    }
                }
                //$("#turnosdoc #btnRegistrarTurnoDocente").text("Actualizar Asistencia").prop("disabled", true);
            }
            $("#myModal_reporasistencia").modal('show');
        },
        dayClick: function (date, jsEvent, view) {

        }
    });
}


function contentRepos() {
    return ` <div class="col-md-12">
                        <div class="iuris-section-title">

                            <i class="fas fa-calendar-alt"></i>

                            <span>Fecha de reposición del turno</span>

                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">

                            <label class="iuris-form-label">
                                Fecha
                            </label>

                            <div class="iuris-input-icon">

                                <i class="far fa-clock"></i>

                                <input type="date" value="" class="form-control" required id="fecha_repo" name="fecha_repo">

                            </div>

                        </div>

                    </div>
                    <div class="col-md-4">

                        <div class="form-group">

                            <label class="iuris-form-label">
                                Hora inicio
                            </label>

                            <div class="iuris-input-icon">

                                <i class="far fa-clock"></i>

                                <input type="time" class="form-control" required name="hora_inicio_repo" id="hora_inicio_repo" >

                            </div>

                        </div>

                    </div>
                    
                    <div class="col-md-4">

                        <div class="form-group">

                            <label class="iuris-form-label">
                                Hora fin
                            </label>

                            <div class="iuris-input-icon">

                                <i class="far fa-clock"></i>
 
                                <input type="time" class="form-control" required id="hora_fin_repo" name="hora_fin_repo" >

                            </div>

                        </div>

                    </div>`
}
