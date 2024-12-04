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
    var calendarEl = document.getElementById('calendar');
    var infoEvent;
    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
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
        //Random default events
        events: "/search/citas/for/calendar",
        editable: false,
        droppable: true, // this allows things to be dropped onto the calendar !!!
        drop: function (date, allDay) { // this function is called when something is dropped
        },
        eventRender: function (event, element) {
            // Personaliza el contenido del evento con un salto de línea
            const formattedTitle = event.title.replace("-", "<br>");
            element.find('.fc-title').html(formattedTitle);
        },
        eventClick: function (calEvent, jsEvent, view) {
            const formattedTitle = calEvent.title.replace("-", "<br>");
            $("#ct_forcitaest h5[id='title']").html(formattedTitle)
            $("#ct_forcitaest p[id='motivo']").text(calEvent.motivo)
            $("#ct_forcitaest p[id='fecha_larga']").text(calEvent.fecha_larga)
            $("#mymodalAgendaCitacion").modal("show")
        },
        dayClick: function (date, jsEvent, view) {
            $('.fc-day').css('background-color', '#fff');
            $(this).css('background-color', '#ededed');
        }
    });
});

