import { AgendasService } from './services/agendas.js';

const agendasService = new AgendasService();

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');


    var infoEvent;
    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
        ele.each(function () {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            }

            // store the Event Object in the DOM element so we can get to it later
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

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d = 1,
        m = 7,
        y = 2017

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

            console.log(date);
            console.log(allDay);

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

            // change the day's background color just for fun

            /*  $('.timepicker').timepicker({
                 showInputs: false
             }); */
            console.log(date);
            console.log(jsEvent);
            console.log(view);
            
            $('.fc-day').css('background-color', '#fff');
            $(this).css('background-color', '#ededed');
           

        }

    });
});

$(document).ready(function () {


    console.log("si");

});