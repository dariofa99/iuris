@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')


@section('titulo_general')
Calendario
@endsection

@section('titulo_area')
Agenda audiencias de conciliación
@endsection
@section('area_buttons')

@endsection




                 @include('modals.modal_calendar')
                 <!-- /modal -->

 



<div class="row">

        <!-- /.col -->
        <div class="col-md-12">
          <div class="box box-primary" >
            <div class="box-body no-padding" style="margin: 5px 5px 5px 5px;">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
<div id="calendaridlist"></div>

<input type="hidden" id="idestlistcal" value="">

@section('script_calendar')
<!--calendar -->




<!-- fullCalendar -->
{!! Html::script('plugins/moment/moment.js')!!}
{!! Html::script('plugins/fullcalendar/fullcalendar.min.js')!!}
<!-- Page specific script -->
<script>

  $(function () {



   

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var colores = {'yellow': '#f39c12',
      'gray': '#d2d6de',
      'blue': '#0073b7',
      'green':'#00a65a',
      'red': '#dd4b39',
      'black' : '#111',
      'purple' : '#605ca8',
      'aqua' : '#00c0ef',
      'light-blue' : '#3c8dbc',
      'navy' : '#001f3f',
      'teal' : '#39cccc',
      'olive' : '#3d9970',
      'lime' : '#01ff70',
      'orange' : '#ff851b',
      'fuchsia' : '#f012be',
      'maroon' : '#d81b60' }

    var date = new Date()
    var d    = 1,
        m    = 12,
        y    = 2021

    $('#calendar').fullCalendar({
     
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'hoy',
        month: 'mes',
        week : 'semana',
        day  : 'día'
      },
      //Random default events
      events    : [
        @foreach($conciliaciones as $conciliacion)

        {
          title          : '{{ $conciliacion->num_conciliacion }}',
          start          : "{{ $conciliacion->fecha }} {{ date('H:i:s', strtotime($conciliacion->hora)) }}",
          end            : "{{ $conciliacion->fecha }} {{ date('H:i:s', strtotime($conciliacion->hora)) }}",
          allDay         : false,
          url            : '/conciliaciones/{{ 	$conciliacion->id_conciliacion }}/edit#menu4',
          backgroundColor: colores.{{ $conciliacion->estado->color }},
          borderColor    : colores.{{ $conciliacion->estado->color }}, 
          description    : 'Estado: {{ $conciliacion->estado->ref_nombre }}<br>Hora: {{ $conciliacion->hora }}',
        },
        @endforeach
      ],
      editable  : false,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)


  
        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }

      },
      eventClick: function(calEvent, jsEvent, view) {
          if (calEvent.url) {
            window.open(calEvent.url, "_blank");
            return false;
           }
      },
      eventRender: function(eventObj, $el) {
        $el.popover({
        title: eventObj.title,
        content: eventObj.description,
        trigger: 'hover',
        placement: 'top',
        container: 'body',
        html: true
        });
      }
    })

 


                

function converthoras (time) {
	// Check correct time format and split into components
	time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
  
	if (time.length > 1) { // If time format correct
	  time = time.slice (1);  // Remove full string match value
	  time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
	  time[0] = +time[0] % 12 || 12; // Adjust hours
	}
	time[3]= " ";
	if (time[0] < 10) {
		time[0]="0"+time[0];
	} 
	//time = time.replace(":00PM", " PM");
	 //time = time.replace(":00AM", " AM");
	return time.join (''); // return adjusted time or original string
}

function fechastring(fecha) {
  const meses = {
    "01":"Enero", "02": "Febrero", "03":"Marzo",
    "04": "Abril","05": "Mayo", "06":"Junio","07": "Julio",
    "08":"Agosto","09":"Septiembre", "10":"Octubre",
    "11":"Noviembre", "12":"Diciembre"
    };
    var n_fecha = [];
    var new_fecha = [];
    nfecha= fecha.split(" ");
    new_fecha = nfecha[0].split("-");
    var fecha_string = new_fecha[2]+" de "+meses[new_fecha[1]]+" del "+new_fecha[0];
    return fecha_string;
}
function getTwentyFourHourTime(amPmString) { 
	var d = new Date("1/1/2013 " + amPmString); 
	var horas_fun = d.getHours();
	var minutes = d.getMinutes();
	if (horas_fun < 10) {horas_fun = "0"+horas_fun;}
	if (minutes < 10) {minutes = "0"+minutes;}
	return horas_fun +":"+ minutes+":00"; 
}







});




</script>
<style type="text/css">

  /*#btn_modal_req{visibility: hidden !important;}*/

</style>
@endsection




@stop
