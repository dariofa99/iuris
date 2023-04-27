@extends('layouts.dashboard')
@section('area_forms')

@include('msg.success')


@section('titulo_general')
Calendario
@endsection

@section('titulo_area')
Agenda
@endsection
@section('area_buttons')
<div class="row">
  <div class="col-md-6 col-sm-offset-6">
    <select class="form-control" id="horariourl">
      <option value="estudiantes" @if($tipo == "estudiantes") selected @endif>Horario estudiantes</option>
      <option value="docentes" @if($tipo == "docentes") selected @endif>Horario docentes</option>
      
    </select>
 
  </div>
</div>
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
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = 1,
        m    = 7,
        y    = 2017

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
        {!! $events !!}
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
        //console.log(calEvent.start);
        infoEvent=calEvent;
        
        if (calEvent.modal == "turnosest") {
          
          datemodalcalendarest(calEvent.clbd,calEvent.hrbd,calEvent.datev);
          $('#mymodal').modal('show');
        } else if (calEvent.modal == "turnosdoc") {
             
          datemodalcalendardoc(calEvent.clbd,calEvent.hrbd,calEvent.datev,calEvent.registableasis);

          $('#mymodaldoc').modal('show');
        }
        // change the border color just for fun
        //$(this).css('border-color', 'red');
      },
      dayClick: function(date, jsEvent, view) {
        //alert('Clicked on: ' + date.format());
        // change the day's background color just for fun
        @if(currentUser()->hasRole('amatai') || currentUser()->hasRole('diradmin') || currentUser()->hasRole("dirgral"))
        var dataasisdoc = {};
        var dateevent = date.format();
        dataasisdoc.fecha=dateevent;
        dataasisdoc.reposicion=1;
        dataasisdoc.tipo_asis=1;
        dataasisdoc=JSON.stringify(dataasisdoc); 
        dataasisdoc = dataasisdoc.replace(/\"/g, "'");   
        
        var fecha_string = fechastring(dateevent);
        $("#tituloturnosdoc").html(fecha_string);
        $("#turnosdoc").html(`
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group text-center">
                <label>Reposición turnos docentes</label>
                
              </div>
            </div>
          </div>
          <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Docente:</label>
                  <select class="form-control" id="select_doc_horario_calendar">
                    <option value="0">Seleccione...</option>
                    @foreach($docentes as $docente)
                    <option value="{{$docente->idnumber}}">{{$docente->full_name}}</option>
                    @endforeach
                    <option>3</option>
                    <option>4</option>
                  </select>
                </div>
              </div>
            </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Hora inicio:</label>
                  <div class="input-group">
                    <input type="text" id="inicio_insert_asisdoc" class="form-control timepicker">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                    <!-- /.input group -->
                </div>
                  <!-- /.form group -->
              </div>
            </div>
            <div class="col-sm-6">
              <div class="bootstrap-timepicker">
                <div class="form-group">
                  <label>Hora fin:</label>
                  <div class="input-group">
                    <input type="text" id="fin_insert_asisdoc" class="form-control timepicker">

                    <div class="input-group-addon">
                      <i class="fa fa-clock-o"></i>
                    </div>
                  </div>
                    <!-- /.input group -->
                </div>
                  <!-- /.form group -->
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Anotación:</label>
                  <textarea class="form-control" id="textarea_insert_asisdoc" rows="3" placeholder="" style="margin: 0px 56.6px 0px 0px; max-width: 100%; width: 538px; height: 117px;"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer" id="fotermodaldoc">
            <button type="button" id="button_insert_asisdoc" data-asisdoc = "`+dataasisdoc+`" class="btn btn-primary">Guardar cambios</button>
          </div>
        `);
          $('.timepicker').timepicker({
				    showInputs: false
			    });
        
        $('.fc-day').css('background-color', '#fff');
        $(this).css('background-color', '#ededed');
        $('#mymodaldoc').modal('show');
      @endif
      }
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {

      e.preventDefault()
      //Save color

      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
    }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

  

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })




  function addcalendar(title, start, end, color, id, docidnumber){

    var eventSource = new Object();
    eventSource.title = title; // this should be string
    eventSource.start = start; // this should be date object
    eventSource.end = end;
    eventSource.backgroundColor = color;
    eventSource.borderColor = color;
    eventSource.textColor = '#663cdf';
    eventSource.url = '';
    eventSource.clbd = docidnumber;
    eventSource.hrbd = id;
    eventSource.datev = start;
    eventSource.modal = 'turnosdoc';
    eventSource.registableasis = '1';

    var newEvent = new Array();
    newEvent[0] = eventSource;
    $('#calendar').fullCalendar( 'addEventSource', newEvent);
  }
                







function datemodalcalendarest(color,horario,fecha) {
 
  //console.log("estudiantes:"+color+"----------"+horario+"--------"+fecha);     
  var numid=1;
   $("#contencalendarid").html('');
   $('#fechaestasis').val(fecha);

          var textcolor = [];
          textcolor["105"]="amarrillo";
          textcolor["106"]="azul";
          textcolor["107"]="verde";
          textcolor["108"]="gris";
          textcolor["109"]="rojo";
          var codcolor = [];
          codcolor["105"] = "#fdd835";
          codcolor["106"] = "#0073b7";
          codcolor["107"] = "#00a65a";
          codcolor["108"] = "#a0afb3";
          codcolor["109"] = "#f56954";

  // var texthorario = ["8AM a 10AM", "10AM a 12M", "2PM a 4PM", "4PM a 6PM"];
              var texthorario = [];
        texthorario["110"] = "8AM a 10AM";
        texthorario["111"] = "10AM a 12M";
        texthorario["112"] = "2PM a 4PM";
        texthorario["113"] = "4PM a 6PM";

  $("#tituloturnos").html('<div class="col-md-6">Turno: <span style="color: white; background-color: '+codcolor[color]+'; border-radius: 7px; padding: 0px 15px 0px 15px;"> '+textcolor[color]+'</span></div><div class="col-md-4">Horario: '+texthorario[horario]+'</div>');
        //define turno presencial o virtual
          var weeknumber = moment(fecha, "YYYY-MM-DD").isoWeek();
          var parimparweek = parImpar(weeknumber);//1 par, 0 impar
          var estadoturno=""//virtual,presencial

          var fechacalendar = moment(fecha).format('YYYY-MM-DD HH:mm:ss');
          var horacalendar = moment(fechacalendar).format('H');
   
          var route = "/consultahor/"+color+"/"+horario+"/"+fecha;

/*

          $.get(route, function(res){

             if (res == "") {
               $('#contencalendarid').html('No hay información');
                  } else {
          $(res).each(function(key, value){

            var idparimpar = parImpar(parseInt(key+1));//1 par, 0 impar
            if (parimparweek == 1) {
              if (idparimpar == 1) {
                estadoturno = "Presencial" 
              } else {
                estadoturno = "Virtual"
              }
            } else {
              if (idparimpar == 0) {
                estadoturno = "Presencial" 
              } else {
                estadoturno = "Virtual"
              }
            }

                
@if (currentUser()->hasRole('coordprac') OR currentUser()->hasRole('diradmin') OR currentUser()->hasRole('dirgral') OR currentUser()->hasRole('amatai'))
                   $('#contencalendarid').append('<tr id="row_'+key+'">'+
                    '<td><span class="lbl_index" id="lbl_index-'+key+'">'+numid+'</span></td>'+
                    '<td>'+value.name+' '+value.lastname+''+
                    '<input type="hidden" id="idasis'+key+'" value="'+value.id+'" name="idasis[]">'+
                    '<input type="hidden" id="idnumberestasis'+key+'" value="'+value.idnumber+'" name="idnumberestasis[]"></td>'+
                    '<td>'+value.ref_nombre+'</td>'+
                    '<td><select class="form-control required" id="idasisestasis'+key+'" name="idasisestasis[]">'+
                    '<option value="121">Asistió</option>'+
                    '<option value="122">Falta simple</option>'+
                    '<option value="123">Falta doble</option>'+
                    '<option value="124">Permiso sin falta</option>'+
                    '</select></td>'+
                    '<td><select class="form-control required" id="idlugarestasis'+key+'" name="idlugarestasis[]"  >'+
                    '<option value="130">Consultorios</option>'+
                    '<option value="131">C.J. Virtuales</option>'+
                    '<option value="132">Of. Desplazados</option>'+
                    '<option value="133">Externo</option>'+
                    '<option value="134">Otro</option>'+
                    '</select></td>'+
                    '<td><textarea class="form-control required" required rows="1" id="comentarioestasis'+key+'" name="comentarioestasis[]" style="height: 35px;min-height: 33px;max-height: 150px;"></textarea></td>'+
                    '</tr>');

                     if (typeof value.astid_tip_asist === "undefined") {
                      $('#idasisestasis'+key).val('121');
                      } else {
                      var optionasis = '<option value="125">Reposición</option>'+
                                        '<option value="126">Falta reposición</option>'+
                                        '<option value="127">Turno extenporaneo</option>'+
                                        '<option value="128">Turno fijo</option>';
                        $('#idasisestasis'+key).append(optionasis);
                        $('#idasisestasis'+key).val(value.astid_tip_asist);
                      }
                     if (typeof value.astid_lugar === "undefined") {
                      $('#idlugarestasis'+key).val('130');
                    } else {
                      $('#idlugarestasis'+key).val(value.astid_lugar);
                    }
                     if ( typeof value.astdescrip_asist === "undefined") {
                      $('#comentarioestasis'+key).val(estadoturno);
                    } else {
                      $('#comentarioestasis'+key).val(value.astdescrip_asist);
                      $("#idasis"+key).val(value.id);
                    }
                     numid=parseInt(numid+1);
@else

$('#contencalendarid').append('<tr><td>'+parseInt(key+1)+'</td><td>'+value.name+' '+value.lastname+'</td><td>'+value.ref_nombre+' (Asiste: '+estadoturno+') </td></tr>');

@endif

          });
  $('#idestlistcal').val('');//borra contenido contador lista estudiantes calendario
  $('#idestlistcal').val(parseInt(parseInt(numid)));//coloca el contador de la lista de estudiantes calendario

        }

          });

  */

   // }


   $.ajax({
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'GET',
		datatype: 'json',
		data: {},
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      $("#wait").css("display", "block");   
    },
    success:function(res){		
      	



      
      if (res == "") {
               $('#contencalendarid').html('No hay información');
                  } else {
          $(res).each(function(key, value){
            if (horacalendar == 8 || horacalendar == 14 || value.cursando_id == 114 || value.cursando_id == 115) {
              
              var idparimpar = parImpar(parseInt(key+1));//1 par, 0 impar
              if (parimparweek == 1) {
                if (idparimpar == 1) {
                  estadoturno = "Presencial" 
                } else {
                  estadoturno = "Presencial"
                }
              } else {
                if (idparimpar == 0) {
                  estadoturno = "Presencial" 
                } else {
                  estadoturno = "Presencial"
                }
              }
            } else { estadoturno = "Presencial"}
                
@if (currentUser()->hasRole('coordprac') OR currentUser()->hasRole('diradmin') OR currentUser()->hasRole('dirgral') OR currentUser()->hasRole('amatai'))
                   $('#contencalendarid').append('<tr id="row_'+key+'">'+
                    '<td><span class="lbl_index" id="lbl_index-'+key+'">'+numid+'</span></td>'+
                    '<td>'+value.name+' '+value.lastname+''+
                    '<input type="hidden" id="idasis'+key+'" value="'+value.id+'" name="idasis[]">'+
                    '<input type="hidden" id="idnumberestasis'+key+'" value="'+value.idnumber+'" name="idnumberestasis[]"></td>'+
                    '<td>'+value.ref_nombre+'</td>'+
                    '<td><select class="form-control required" id="idasisestasis'+key+'" name="idasisestasis[]">'+
                    '<option value="121">Asistió</option>'+
                    '<option value="122">Falta simple</option>'+
                    '<option value="123">Falta doble</option>'+
                    '<option value="124">Permiso sin falta</option>'+
                    '</select></td>'+
                    '<td><select class="form-control required" id="idlugarestasis'+key+'" name="idlugarestasis[]"  >'+
                    '<option value="130">Consultorios</option>'+
                    '<option value="131">C.J. Virtuales</option>'+
                    '<option value="132">Of. Desplazados</option>'+
                    '<option value="133">Externo</option>'+
                    '<option value="134">Otro</option>'+
                    '</select></td>'+
                    '<td><textarea class="form-control required" required rows="1" id="comentarioestasis'+key+'" name="comentarioestasis[]" style="height: 35px;min-height: 33px;max-height: 150px;"></textarea></td>'+
                    '</tr>');

                     if (typeof value.astid_tip_asist === "undefined") {
                      $('#idasisestasis'+key).val('121');
                      } else {
                      var optionasis = '<option value="125">Reposición</option>'+
                                        '<option value="126">Falta reposición</option>'+
                                        '<option value="127">Turno extenporaneo</option>'+
                                        '<option value="128">Turno fijo</option>';
                        $('#idasisestasis'+key).append(optionasis);
                        $('#idasisestasis'+key).val(value.astid_tip_asist);
                      }
                     if (typeof value.astid_lugar === "undefined") {
                      $('#idlugarestasis'+key).val('130');
                    } else {
                      $('#idlugarestasis'+key).val(value.astid_lugar);
                    }
                     if ( typeof value.astdescrip_asist === "undefined") {
                      $('#comentarioestasis'+key).val(estadoturno);
                    } else {
                      $('#comentarioestasis'+key).val(value.astdescrip_asist);
                      $("#idasis"+key).val(value.id);
                    }
                     numid=parseInt(numid+1);
@else

$('#contencalendarid').append('<tr><td>'+parseInt(key+1)+'</td><td>'+value.name+' '+value.lastname+'</td><td>'+value.ref_nombre+' (Asiste: '+estadoturno+') </td></tr>');

@endif

          });
  $('#idestlistcal').val('');//borra contenido contador lista estudiantes calendario
  $('#idestlistcal').val(parseInt(parseInt(numid)));//coloca el contador de la lista de estudiantes calendario

        }

    $("#wait").css("display", "none");


    
	
		},
    error:function(xhr, textStatus, thrownError){
      $("#wait").css("display", "none");
        alert("Hubo un error con el servidor ERROR:: este es"+thrownError,textStatus);
    }

	});	




}

  function datemodalcalendardoc(color,id,fecha,registableasis) {
 
   
    var fecha_string = fechastring(fecha);
    var fechafin=fecha.split(" ");
    var dataasisdoc = {};
    
    $("#tituloturnosdoc").html(fecha_string);
    if (registableasis == '1') { 
      route = "/consultahordocasis/"+color+"/"+id+"/"+fecha;
    } else {
      route = "/consultahordoc/"+color+"/"+id+"/"+fecha;
    }

    $.get(route, function(res){
      if (res == "") {
        $('#turnosdoc').html('No hay información');
      } else {
       
        $(res).each(function(key, value){
          var checkradioasis ="checked";
          var checkradioper ="";
          var textareaasis="";         

          if (registableasis == '1') { 
            dataasisdoc.id=value.id;
            var inicioarray = value.inicio.split(" ");
            var finarray = value.fin.split(" ");
            value.inicio = inicioarray[1];
            value.fin = finarray[1];
            textareaasis=value.descripcion;
            if ( value.tipo_asis == '149') {
              checkradioasis ="checked";
              checkradioper ="";
            } else if ( value.tipo_asis == '150' ) {
              checkradioasis ="";
              checkradioper ="checked";
            }
            
          } else {
            dataasisdoc.inicio=fecha;
            dataasisdoc.fin=fechafin[0]+" "+value.fin;
            dataasisdoc.docidnumber=value.docidnumber;
            dataasisdoc.reposicion=0;


          }
         
          dataasisdoc=JSON.stringify(dataasisdoc); 
          dataasisdoc = dataasisdoc.replace(/\"/g, "'");  


          var horain = converthoras (value.inicio);
          var horafi = converthoras (value.fin);
            
          var datosdoc = `
            <div class="row">
              <div class="col-sm-12">
                <label>Docente:</label> `+value.nombre_completo+`
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <label>Hora inicio:</label> `+horain+`
              </div>
              <div class="col-sm-6">
                <label>Hora fin:</label> `+horafi+`
              </div>
            </div>
        `;
        @if (currentUser()->hasRole('diradmin') OR currentUser()->hasRole('dirgral') OR currentUser()->hasRole('amatai'))
        var optionsdocadmin=`
        <div class="row">
          <div class="col-md-12">
            <label>Novedades:</label>
            <div class="row">
            <div class="col-md-12">
            <div class="form-group">
              <div class="radio">
                <label>
                 <input type="radio" name="regisdocasis" id="regisdocasis1" value="149" `+checkradioasis+`>
                      Registrar asistencia
                </label>
              </div>
            </div>
      
            <div class="form-group">
              <div class="radio">
                <label>
                  <input type="radio" name="regisdocasis" id="regisdocasis2" value="150" `+checkradioper+`>
                    Registrar permiso
                </label>
              </div>
            </div>
                        
          </div>
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Anotación:</label>
                <textarea class="form-control" name="descripregisdocasis" id="descripregisdocasis" rows="3" placeholder="" style="margin: 0px 56.6px 0px 0px; max-width: 100%; width: 538px; height: 117px;">`+textareaasis+`</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer" id="fotermodaldoc">
          <button type="button" id="btnasisenciadocmodal" data-asisdoc="`+dataasisdoc+`" class="btn btn-primary">Guardar cambios</button>
        </div>
        `
        @else
          var optionsdocadmin="";
        @endif
          $("#turnosdoc").html(datosdoc+optionsdocadmin);

        });
       
      }
    });


}


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

$("#mymodaldoc").on('click','#button_insert_asisdoc',function(e){
  if ($("#select_doc_horario_calendar").val() != 0) {
    
    var dataasisdoc=$(this).attr('data-asisdoc');
    dataasisdoc = dataasisdoc.replace(/\'/g, '"');
    var dataasisdoc = JSON.parse(dataasisdoc);
    dataasisdoc.docidnumber=$("#select_doc_horario_calendar").val();
    dataasisdoc.descripcion=$("#textarea_insert_asisdoc").val();
    dataasisdoc.inicio = dataasisdoc.fecha+" "+getTwentyFourHourTime ($("#inicio_insert_asisdoc").val());
    dataasisdoc.fin = dataasisdoc.fecha+" "+getTwentyFourHourTime ($("#fin_insert_asisdoc").val());
    delete dataasisdoc["fecha"];
    var dataevent = {};
    dataevent.title=$("#select_doc_horario_calendar option:selected").text();


    regisasisdoc (dataasisdoc, "insert",dataevent);

  }
  




});

$("#mymodaldoc").on('click','#btnasisenciadocmodal',function(e){
    var dataasisdoc=$(this).attr('data-asisdoc');
    dataasisdoc = dataasisdoc.replace(/\'/g, '"');
    var dataasisdoc = JSON.parse(dataasisdoc);
    dataasisdoc.tipo_asis=$('input:radio[name=regisdocasis]:checked').val();
    dataasisdoc.descripcion=$("#descripregisdocasis").val();
    if ( dataasisdoc.id ) {
      regisasisdoc (dataasisdoc, "update");
    } else {
      regisasisdoc (dataasisdoc, "insert");
    }

});

function regisasisdoc (mydata, type, event) {
  if (type == "update") {
    var route = "/horario/updatehordocasis";
  } else if (type == "insert") {
	  var route = "/horario/regishordocasis";
  }

	var token = $("#token").val();
	
    $.ajax({ 
		url: route,
		headers: { 'X-CSRF-TOKEN' : token },
		type:'post',
		datatype: 'json',
		data:mydata,
		cache: false,
		 beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");
    },

        /*muestra div con mensaje de 'regristrado'*/

        success:function(res){
         
            if (res.tipo_asis == "149") {
              infoEvent.backgroundColor='#cef2d9';
            } else if ( res.tipo_asis == "150" ) {
              infoEvent.backgroundColor='#f0e1ab';
            }
          if (type == "insert") {
            if (event) {
	            addcalendar(event.title, res.inicio, res.fin, '#fff', res.id, res.docidnumber);
            } else {
              infoEvent.hrbd= res.id;
              infoEvent.registableasis=1;
              $('#calendar').fullCalendar('updateEvent', infoEvent);
            }
          } else if (type == "update") {
              $('#calendar').fullCalendar('updateEvent', infoEvent);
          }

          $('#mymodaldoc').modal('hide');
        	alert('¡Información guardada con éxito!');


		},

    error:function(xhr, textStatus, thrownError){
        alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
    }



	});
}

var v_users =[];

$("#addest").click(function(){
//console.log(v_users)
  if (v_users.length <= 0) {
    getEstudiantes();
  } else {
    llenarDatos();
  }

});


function get_curso(obj){
    var idnumber = $(obj).val();
    var id = $(obj).attr('id');
    var numero = getIdAttr(id,'-',1);
    $(v_users).each(function(key,value){
        if (value.idnumber==idnumber) {
          $("#lbl_ref_c-"+numero).text(value.ref_nombre_curso);
        }
    });
}
function getEstudiantes() {
          var route = "/students/get";
        $.ajax({
        url: route,
        headers: { 'X-CSRF-TOKEN' : token },
        type:'POST',
        datatype: 'json',
        //data: {'id':id},
        cache: false,
        contentType: false,
        processData: false,
        /*muestra div con mensaje de 'regristrado'*/
        beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");
        },
        success:function(res){

          $(res).each(function(key, value){
            user={
              "idnumber":value.idnumber,
              "full_name":value.full_name,
              "ref_nombre_curso":value.ref_nombre
            }
              v_users.push(user);
  //console.log(value.idnumber+'---'+value.full_name);
          });
          //console.log(v_users);
          llenarDatos();

        },
        error:function(xhr, textStatus, thrownError){
            alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
             $("#wait").css("display", "block");
        }
        });
}

function llenarDatos() {
 var idest = $("#tbl_turnos_list .lbl_index").length;
 idestu= idest+1;
$('#contencalendarid').append('<tr id="row_'+idest+'">'+
  '<td><span class="lbl_index" id="lbl_index-'+idest+'">'+idestu+'</span><input type="hidden" name="idasis[]"></td>'+
  '<td><select class="form-control required selectpicker estselectest2 select_users" onChange="get_curso(this);" data-live-search="true" id="idnumberestasis-'+idest+'" name="idnumberestasis[]" required>'+
  '<option value="">Seleccione el estudiante...</option>'+
  '</select></td>'+
  '<td><span id="lbl_ref_c-'+idest+'"></span></td>'+
  '<td><select class="form-control required" id="idasisestasis'+idest+'" name="idasisestasis[]">'+
      '<option value="125">Reposición</option>'+
      '<option value="126">Falta reposición</option>'+
      '<option value="127">Turno extenporaneo</option>'+
      '<option value="128">Turno fijo</option>'+
  '</select></td>'+
  '<td><select class="form-control required" id="idlugarestasis'+idest+'" name="idlugarestasis[]"  >'+
      '<option value="130">Consultorios</option>'+
      '<option value="131">C.J. Virtuales</option>'+
      '<option value="132">Of. Desplazados</option>'+
      '<option value="133">Externo</option>'+
      '<option value="134">Otro</option>'+
  '</select></td>'+
  '<td><textarea class="form-control required" rows="1" id="comentarioestasis'+idest+'" name="comentarioestasis[]" style="height: 35px;min-height: 33px;max-height: 150px;" required>.</textarea>'+
  '</td>'+
  '<td><button class="btn btn-danger btn_delete_row" type="button" id="btn_delete_row-'+idest+'"><i class="fa fa-minus-circle"></i></button></td>'+
  '</tr>');
var option = '';
  $(v_users).each(function(key, value){
     option +='<option value="'+value.idnumber+'">'+value.full_name.toUpperCase()+'</option>';
  });
  $("#idnumberestasis-"+idest).append(option);//coloca una nueva opcion
  $(".estselectest2").selectpicker("refresh");//refresca el select
  $('#idestlistcal').val('');//borra contenido contador lista estudiantes calendario
  $('#idestlistcal').val(parseInt(idest+1));//coloca el contador de la lista de estudiantes calendario
}

//Envia formulario

$("#myFormCalendar").submit(function(){
var data = $(this).serialize();
errors = validateForm('myFormCalendar');
if (errors.length<=0) {
  //store_asistencia(data);
 // console.log(data);
}


//return false;


});

$("#tbl_turnos_list").on('click','.btn_delete_row',function(){
  var id = getIdAttr($(this).attr('id'),'-');
  $("#row_"+id).remove();
  this_id = parseInt(getIdAttr($(this).attr('id'),'-'));
  var next_lbl = $("#tbl_turnos_list #lbl_index-"+(this_id+1));  
  //console.log(next_lbl) 
   if (next_lbl.length>0) {      
      var next_row = $("#row_"+(parseInt(this_id)+1));  
      var idnumberestasis =  $("#idnumberestasis-"+(parseInt(this_id)+1));
      var lbl_ref_c = $("#lbl_ref_c-"+(parseInt(this_id)+1)); 
      var btn_delete_row = $("#btn_delete_row-"+(parseInt(this_id)+1));        
        while(next_lbl.length > 0){
           $("#tbl_turnos_list .lbl_index").each(function(index,obj){      
               var row = $("#row_"+(index));
               //console.log(row)
              if (row.length == 0) {    
                next_lbl.attr('id','lbl_index-'+(parseInt(index)));
                idnumberestasis.attr('id','idnumberestasis-'+(parseInt(index)));
                lbl_ref_c.attr('id','lbl_ref_c-'+(parseInt(index))); 
                next_row.attr('id','row_'+(parseInt(index)));
                btn_delete_row.attr('id','btn_delete_row-'+(parseInt(index)));
                next_lbl.text((index+1));  
                next_lbl = $("#lbl_index-"+(parseInt(index)+1));
                idnumberestasis = $("#idnumberestasis-"+(parseInt(index)+1));
                lbl_ref_c = $("#lbl_ref_c-"+(parseInt(index)+1));
                btn_delete_row = $("#btn_delete_row-"+(parseInt(index)+1));
                next_row = $("#row_"+(parseInt(index)+1));
              }
           });        
        }   
    }
  
});

/////store

function store_asistencia(data){

        var route = "/horarios";
        $.ajax({
        url: route,
        type:'POST',
        datatype: 'json',
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        /*muestra div con mensaje de 'regristrado'*/
        beforeSend: function(xhr){
      xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
      //$("#wait").css("display", "block");
        },
        success:function(res){

         // console.log(res);

        },
        error:function(xhr, textStatus, thrownError){
            alert("Hubo un error con el servidor ERROR::"+thrownError,textStatus);
             $("#wait").css("display", "none");
        }
        });

}

function parImpar(numero) {
  if(numero % 2 == 0) {
    return 1;
  }
  else {
    return 0;
  }
}

$( "#horariourl" ).change(function() {

        var data = this.value;
      if (data == 'estudiantes') {
        window.location='/horarios/estudiantes';
      } else if (data == 'docentes') {
        var url = '/horarios/docentes';
        window.location=url;
      }
  
});

  })

</script>
<style type="text/css">

  /*#btn_modal_req{visibility: hidden !important;}*/

</style>
@endsection




@stop
