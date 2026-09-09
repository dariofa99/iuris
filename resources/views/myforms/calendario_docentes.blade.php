@extends('layouts.dashboard')
@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet" href="{{ asset('/plugins/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-select/bootstrap.css') }}">
@endpush
@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection
@section('titulo_area')
    Agenda
@endsection
@section('area_buttons')
    <div class="row">
        <div class="col-md-6 col-sm-offset-6">
            <select class="form-control" id="horariourl">
                <option value="estudiantes" @if ($tipo == 'estudiantes') selected @endif>Horario estudiantes</option>
                <option value="docentes" @if ($tipo == 'docentes') selected @endif>Horario docentes</option>

            </select>

        </div>
    </div>
@endsection

@section('area_forms')


    <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
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




    @include('myforms.turnos.frm_modal_registrar_turno_docente')

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <!-- Latest compiled and minified JavaScript -->
    {!! Html::script('plugins/fullcalendar/fullcalendar.min.js') !!}
    {!! Html::script('plugins/fullcalendar/dist/locale/es.js') !!}
    <script src="{{ asset('/plugins/bootstrap-select/bootstrap.js') }}"></script>

    <script type="module" src={{ asset('js/admin_horarios.js?v=' . config('app_config.asset_version')) }}></script>

    <!-- Page specific script -->
    <script>
        $(function() {

            showCalendar();




        })


        function init_events(ele) {
            $("#wait").show();
            ele.each(function() {

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
                events: '/horarios/docentes/',
                editable: true, // 🔹 Permite mover eventos (drag & drop)
                // eventStartEditable: true, // 🔹 Permite mover el inicio del evento
                eventDurationEditable: true, // 🔹 Permite cambiar la duración
                droppable: true, // 🔹 Permite arrastrar desde fuera si usas “external events”
                eventOverlap: true, // 🔹 Permite superponer eventos
                eventConstraint: null, // 🔹 Permite mover fuera del horario
                eventDidMount: function(info) {
                    if (info.event.extendedProps.tipo === 'estudiante') {
                        info.el.style.cursor = 'default'; // cambia el cursor
                    }
                    console.log(info);

                },
                drop: function(date) {
                    alert("Dropped on " + date.format());
                },
                eventDrop: function(event, delta, revertFunc) {


                },

                loading: function(isLoading) {
                    if (isLoading) {
                        $("#wait").show(); // Muestra el loader
                    } else {
                        $("#wait").hide(); // Oculta el loader
                    }
                },
                eventRender: function(event, element, view) {

                    const start = moment(event.start).format('HH:mm');
                    const end = moment(event.end).format('HH:mm');

                    /*
                    |--------------------------------------------------------------------------
                    | Imagen del docente
                    |--------------------------------------------------------------------------
                    */

                    const img = document.createElement('img');

                    img.src = event.image ?
                        event.image :
                        '/thumbnails/default.jpg';

                    img.style.width = '55px';
                    img.style.height = '55px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '50%';
                    img.style.display = 'block';
                    img.style.margin = '8px auto 5px';
                    img.style.border = '3px solid rgba(255,255,255,0.8)';
                    img.style.boxShadow = '0 2px 6px rgba(0,0,0,0.25)';


                    /*
                    |--------------------------------------------------------------------------
                    | Contenedor del evento
                    |--------------------------------------------------------------------------
                    */

                    element.find('.fc-content').css({
                        'height': '130px',
                        'font-size': '13px',
                        'text-align': 'center',
                        'padding': '5px'
                    });


                    /*
                    |--------------------------------------------------------------------------
                    | Hora
                    |--------------------------------------------------------------------------
                    */

                    element.find('.fc-time').html(
                        `<strong>${start} - ${end}</strong>`
                    );

                    element.find('.fc-time').css({
                        'display': 'block',
                        'font-size': '12px',
                        'margin-bottom': '3px'
                    });


                    /*
                    |--------------------------------------------------------------------------
                    | Imagen
                    |--------------------------------------------------------------------------
                    */

                    element.find('.fc-content').append(img);


                    /*
                    |--------------------------------------------------------------------------
                    | Nombre del docente
                    |--------------------------------------------------------------------------
                    */

                    const formattedTitle =
                        event.title.replace("-", "<br>");

                    element.find('.fc-title').html(
                        `<div class="nombre-docente">
                            ${formattedTitle}
                        </div>`
                    );


                    element.find('.fc-title').css({
                        'font-size': '13px',
                        'font-weight': '600',
                        'line-height': '18px',
                        'white-space': 'normal',
                        'margin-top': '4px'
                    });


                    /*
                    |--------------------------------------------------------------------------
                    | Evento EXTRA
                    |--------------------------------------------------------------------------
                    */

                    if (event.tipo == "extra") {

                        element.find('.fc-time').html(
                            '<strong>Extra</strong>'
                        );
                    }

                },
                eventClick: function(calEvent, jsEvent, view) {
                    console.log(calEvent);
                    $("#turnosdoc #hora_inicio").val(calEvent.hora_inicio);
                    $("#turnosdoc #hora_fin").val(calEvent.hora_fin);
                    $("#turnosdoc #nombre_docente").text(calEvent.nombre);
                    $("#turnosdoc #avatar_docente").attr("src", calEvent.image);
                    $("#myModal_reporasistencia").modal('show');
                },
                dayClick: function(date, jsEvent, view) {
                    console.log(date);
                }
            });
        }









        function datemodalcalendarest(color, horario, fecha) {

            //console.log("estudiantes:"+color+"----------"+horario+"--------"+fecha);     
            var numid = 1;
            $("#contencalendarid").html('');
            $('#fechaestasis').val(fecha);

            var textcolor = [];
            textcolor["105"] = "amarrillo";
            textcolor["106"] = "azul";
            textcolor["107"] = "verde";
            textcolor["108"] = "gris";
            textcolor["109"] = "rojo";
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

            $("#tituloturnos").html(
                '<div class="col-md-6">Turno: <span style="color: white; background-color: ' + codcolor[
                    color] + '; border-radius: 7px; padding: 0px 15px 0px 15px;"> ' + textcolor[color] +
                '</span></div><div class="col-md-4">Horario: ' + texthorario[horario] + '</div>');
            //define turno presencial o virtual
            var weeknumber = moment(fecha, "YYYY-MM-DD").isoWeek();
            var parimparweek = parImpar(weeknumber); //1 par, 0 impar
            var estadoturno = "" //virtual,presencial

            var fechacalendar = moment(fecha).format('YYYY-MM-DD HH:mm:ss');
            var horacalendar = moment(fechacalendar).format('H');

            var route = "/consultahor/" + color + "/" + horario + "/" + fecha;


            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'GET',
                datatype: 'json',
                data: {},
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
                    $("#wait").css("display", "block");
                },
                success: function(res) {





                    if (res == "") {
                        $('#contencalendarid').html('No hay información');
                    } else {
                        $(res).each(function(key, value) {
                            if (horacalendar == 8 || horacalendar == 14 || value
                                .cursando_id == 114 || value.cursando_id == 115) {

                                var idparimpar = parImpar(parseInt(key +
                                    1)); //1 par, 0 impar
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
                            } else {
                                estadoturno = "Presencial"
                            }

                            @if (currentUser()->hasRole('coordprac') or
                                    currentUser()->hasRole('diradmin') or
                                    currentUser()->hasRole('dirgral') or
                                    currentUser()->can('admin_turnos_estudiantes') or
                                    currentUser()->hasRole('amatai'))
                                $('#contencalendarid').append('<tr id="row_' + key + '">' +
                                    '<td><span class="lbl_index" id="lbl_index-' + key +
                                    '">' + numid + '</span></td>' +
                                    '<td>' + value.name + ' ' + value.lastname + '' +
                                    '<input type="hidden" id="idasis' + key +
                                    '" value="' + value.id + '" name="idasis[]">' +
                                    '<input type="hidden" id="idnumberestasis' + key +
                                    '" value="' + value.idnumber +
                                    '" name="idnumberestasis[]"></td>' +
                                    '<td>' + value.ref_nombre + '</td>' +
                                    '<td><select class="form-control form-control-sm required" id="idasisestasis' +
                                    key + '" name="idasisestasis[]">' +
                                    '<option value="121">Asistió</option>' +
                                    '<option value="122">Falta simple</option>' +
                                    '<option value="123">Falta doble</option>' +
                                    '<option value="126">Falta reposición</option>' +
                                    '<option value="124">Permiso sin falta</option>' +
                                    '</select></td>' +
                                    '<td><select class="form-control  form-control-sm required" id="idlugarestasis' +
                                    key + '" name="idlugarestasis[]"  >' +
                                    '<option value="130">Consultorios</option>' +
                                    '<option value="131">C.J. Virtuales</option>' +
                                    '<option value="132">Of. Desplazados</option>' +
                                    '<option value="133">Externo</option>' +
                                    '<option value="134">Otro</option>' +
                                    '</select></td>' +
                                    '<td><textarea class="form-control  form-control-sm required" required rows="1" id="comentarioestasis' +
                                    key +
                                    '" name="comentarioestasis[]" style="height: 33px;min-height: 33px;max-height: 150px;"></textarea></td>' +
                                    '</tr>');

                                if (typeof value.astid_tip_asist === "undefined") {
                                    $('#idasisestasis' + key).val('121');
                                } else {
                                    var optionasis =
                                        '<option value="125">Reposición</option>' +
                                        '<option value="126">Falta reposición</option>' +
                                        '<option value="127">Turno extenporaneo</option>' +
                                        '<option value="128">Turno fijo</option>';
                                    $('#idasisestasis' + key).append(optionasis);
                                    $('#idasisestasis' + key).val(value.astid_tip_asist);
                                }
                                if (typeof value.astid_lugar === "undefined") {
                                    $('#idlugarestasis' + key).val('130');
                                } else {
                                    $('#idlugarestasis' + key).val(value.astid_lugar);
                                }
                                if (typeof value.astdescrip_asist === "undefined") {
                                    $('#comentarioestasis' + key).val(estadoturno);
                                } else {
                                    $('#comentarioestasis' + key).val(value
                                        .astdescrip_asist);
                                    $("#idasis" + key).val(value.id);
                                }
                                numid = parseInt(numid + 1);
                            @else

                                $('#contencalendarid').append('<tr><td>' + parseInt(key +
                                        1) + '</td><td>' + value.name + ' ' + value
                                    .lastname + '</td><td>' + value.ref_nombre +
                                    ' (Asiste: ' + estadoturno + ') </td></tr>');
                            @endif

                        });
                        $('#idestlistcal').val(
                            ''); //borra contenido contador lista estudiantes calendario
                        $('#idestlistcal').val(parseInt(parseInt(
                            numid))); //coloca el contador de la lista de estudiantes calendario

                    }

                    $("#wait").css("display", "none");




                },
                error: function(xhr, textStatus, thrownError) {
                    $("#wait").css("display", "none");
                    alert("Hubo un error con el servidor ERROR:: este es" + thrownError,
                        textStatus);
                }

            });




        }

        function datemodalcalendardoc(color, id, fecha, registableasis) {}


        function converthoras(time) {
            // Check correct time format and split into components
            time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

            if (time.length > 1) { // If time format correct
                time = time.slice(1); // Remove full string match value
                time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            time[3] = " ";
            if (time[0] < 10) {
                time[0] = "0" + time[0];
            }
            //time = time.replace(":00PM", " PM");
            //time = time.replace(":00AM", " AM");
            return time.join(''); // return adjusted time or original string
        }

        function fechastring(fecha) {
            const meses = {
                "01": "Enero",
                "02": "Febrero",
                "03": "Marzo",
                "04": "Abril",
                "05": "Mayo",
                "06": "Junio",
                "07": "Julio",
                "08": "Agosto",
                "09": "Septiembre",
                "10": "Octubre",
                "11": "Noviembre",
                "12": "Diciembre"
            };
            var n_fecha = [];
            var new_fecha = [];
            nfecha = fecha.split(" ");
            new_fecha = nfecha[0].split("-");
            var fecha_string = new_fecha[2] + " de " + meses[new_fecha[1]] + " del " + new_fecha[0];
            return fecha_string;
        }

        function getTwentyFourHourTime(amPmString) {
            var d = new Date("1/1/2013 " + amPmString);
            var horas_fun = d.getHours();
            var minutes = d.getMinutes();
            if (horas_fun < 10) {
                horas_fun = "0" + horas_fun;
            }
            if (minutes < 10) {
                minutes = "0" + minutes;
            }
            return horas_fun + ":" + minutes + ":00";
        }

        $("#mymodaldoc").on('click', '#button_insert_asisdoc', function(e) {
            if ($("#select_doc_horario_calendar").val() != 0) {

                var dataasisdoc = $(this).attr('data-asisdoc');
                dataasisdoc = dataasisdoc.replace(/\'/g, '"');
                var dataasisdoc = JSON.parse(dataasisdoc);
                dataasisdoc.docidnumber = $("#select_doc_horario_calendar").val();
                dataasisdoc.descripcion = $("#textarea_insert_asisdoc").val();
                dataasisdoc.inicio = dataasisdoc.fecha + " " + getTwentyFourHourTime($(
                    "#inicio_insert_asisdoc").val());
                dataasisdoc.fin = dataasisdoc.fecha + " " + getTwentyFourHourTime($(
                    "#fin_insert_asisdoc").val());
                delete dataasisdoc["fecha"];
                var dataevent = {};
                dataevent.title = $("#select_doc_horario_calendar option:selected").text();


                regisasisdoc(dataasisdoc, "insert", dataevent);

            }





        });

        $("#mymodaldoc").on('click', '#btnasisenciadocmodal', function(e) {
            var dataasisdoc = $(this).attr('data-asisdoc');
            dataasisdoc = dataasisdoc.replace(/\'/g, '"');
            var dataasisdoc = JSON.parse(dataasisdoc);
            dataasisdoc.tipo_asis = $('input:radio[name=regisdocasis]:checked').val();
            dataasisdoc.descripcion = $("#descripregisdocasis").val();
            if (dataasisdoc.id) {
                regisasisdoc(dataasisdoc, "update");
            } else {
                regisasisdoc(dataasisdoc, "insert");
            }

        });

        function regisasisdoc(mydata, type, event) {
            if (type == "update") {
                var route = "/horario/updatehordocasis";
            } else if (type == "insert") {
                var route = "/horario/regishordocasis";
            }

            var token = $("#token").val();

            $.ajax({
                url: route,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                type: 'post',
                datatype: 'json',
                data: mydata,
                cache: false,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
                    //$("#wait").css("display", "block");
                },

                /*muestra div con mensaje de 'regristrado'*/

                success: function(res) {

                    if (res.tipo_asis == "149") {
                        infoEvent.backgroundColor = '#cef2d9';
                    } else if (res.tipo_asis == "150") {
                        infoEvent.backgroundColor = '#f0e1ab';
                    }
                    if (type == "insert") {
                        if (event) {
                            addcalendar(event.title, res.inicio, res.fin, '#fff', res.id, res
                                .docidnumber);
                        } else {
                            infoEvent.hrbd = res.id;
                            infoEvent.registableasis = 1;
                            $('#calendar').fullCalendar('updateEvent', infoEvent);
                        }
                    } else if (type == "update") {
                        $('#calendar').fullCalendar('updateEvent', infoEvent);
                    }

                    $('#mymodaldoc').modal('hide');
                    alert('¡Información guardada con éxito!');


                },

                error: function(xhr, textStatus, thrownError) {
                    alert("Hubo un error con el servidor ERROR::" + thrownError, textStatus);
                }



            });
        }

        var v_users = [];

        /* 


               

               function getEstudiantes() {
                   var route = "/students/get";
                   $.ajax({
                       url: route,
                       headers: {
                           'X-CSRF-TOKEN': token
                       },
                       type: 'POST',
                       datatype: 'json',
                       //data: {'id':id},
                       cache: false,
                       contentType: false,
                       processData: false,
                       
                       beforeSend: function(xhr) {
                           xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));
                           //$("#wait").css("display", "block");
                       },
                       success: function(res) {

                           $(res).each(function(key, value) {
                               user = {
                                   "idnumber": value.idnumber,
                                   "full_name": value.full_name,
                                   "ref_nombre_curso": value.ref_nombre
                               }
                               v_users.push(user);
                               //console.log(value.idnumber+'---'+value.full_name);
                           });
                           //console.log(v_users);
                           llenarDatos();

                       },
                       error: function(xhr, textStatus, thrownError) {
                           alert("Hubo un error con el servidor ERROR::" + thrownError, textStatus);
                           $("#wait").css("display", "block");
                       }
                   });
               } */






        $("#tbl_turnos_list").on('click', '.btn_delete_row', function() {
            var id = getIdAttr($(this).attr('id'), '-');
            $("#row_" + id).remove();
            this_id = parseInt(getIdAttr($(this).attr('id'), '-'));
            var next_lbl = $("#tbl_turnos_list #lbl_index-" + (this_id + 1));
            //console.log(next_lbl) 
            if (next_lbl.length > 0) {
                var next_row = $("#row_" + (parseInt(this_id) + 1));
                var idnumberestasis = $("#idnumberestasis-" + (parseInt(this_id) + 1));
                var lbl_ref_c = $("#lbl_ref_c-" + (parseInt(this_id) + 1));
                var btn_delete_row = $("#btn_delete_row-" + (parseInt(this_id) + 1));
                while (next_lbl.length > 0) {
                    $("#tbl_turnos_list .lbl_index").each(function(index, obj) {
                        var row = $("#row_" + (index));
                        //console.log(row)
                        if (row.length == 0) {
                            next_lbl.attr('id', 'lbl_index-' + (parseInt(index)));
                            idnumberestasis.attr('id', 'idnumberestasis-' + (parseInt(index)));
                            lbl_ref_c.attr('id', 'lbl_ref_c-' + (parseInt(index)));
                            next_row.attr('id', 'row_' + (parseInt(index)));
                            btn_delete_row.attr('id', 'btn_delete_row-' + (parseInt(index)));
                            next_lbl.text((index + 1));
                            next_lbl = $("#lbl_index-" + (parseInt(index) + 1));
                            idnumberestasis = $("#idnumberestasis-" + (parseInt(index) + 1));
                            lbl_ref_c = $("#lbl_ref_c-" + (parseInt(index) + 1));
                            btn_delete_row = $("#btn_delete_row-" + (parseInt(index) + 1));
                            next_row = $("#row_" + (parseInt(index) + 1));
                        }
                    });
                }
            }

        });



        function parImpar(numero) {
            if (numero % 2 == 0) {
                return 1;
            } else {
                return 0;
            }
        }

        $("#horariourl").change(function() {

            var data = this.value;
            if (data == 'estudiantes') {
                window.location = '/horarios/estudiantes';
            } else if (data == 'docentes') {
                var url = '/horarios/docentes';
                window.location = url;
            }

        });
    </script>
    <style type="text/css">
        /*#btn_modal_req{visibility: hidden !important;}*/
    </style>
@endpush
