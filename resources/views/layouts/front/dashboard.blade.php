<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}" id="token"> 

  <title>Iuris</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  {!! Html::style('bootstrap/css/bootstrap.min.css') !!}
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  {!! Html::style('dist/css/AdminLTE.min.css')!!}
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
   {!! Html::style('dist/css/skins/_all-skins.min.css')!!}
  {!! Html::style('/css/styles.css?v=2')!!}
  {{-- {!! Html::style('/plugins/jQueryUI/jquery-ui.theme.min.css')!!}
   --}}
   <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />

  
  <link href="{{ asset('plugins/alertifyJS/css/alertify.min.css') }}" rel="stylesheet" type="text/css" />
      <link href="{{ asset('plugins/alertifyJS/css/themes/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
     
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

<link href="/img/favicon.ico" rel="icon" type="image/x-icon" />




 <!--               css adicionales          -->
  <!-- daterange picker -->
  {!! Html::style('plugins/daterangepicker/daterangepicker.css')!!}
  <!-- bootstrap datepicker -->
  {!! Html::style('plugins/datepicker/datepicker3.css')!!}
  {!! Html::style('plugins/datepicker/css/bootstrap-datetimepicker.min.css')!!}
  <!-- iCheck for checkboxes and radio inputs -->
  {!! Html::style('plugins/iCheck/all.css')!!}
  <!-- Bootstrap Color Picker -->
  {!! Html::style('plugins/colorpicker/bootstrap-colorpicker.min.css')!!}

  <!-- Bootstrap time Picker -->
  {!! Html::style('plugins/timepicker/bootstrap-timepicker.min.css')!!}
  {!! Html::style('plugins/select2/select2.min.css')!!}

   <!--              / css adicionales          -->
<!-- select fucniones avanzadas -->
  {!! Html::style('plugins/btpselect/dist/css/bootstrap-select.css')!!} 
{{ Html::script('plugins/btpselect/dist/js/bootstrap-select.js', array('defer' => 'defer')) }}

<!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{asset('/plugins/sweetalert2/sweetalert2.min.css')}}">
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
  
  

      <!--               css calendario          -->

<!-- Bootstrap 3.3.7 -->
<!--
  <link rel="stylesheet" href="{{asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
  Ionicons
  <link rel="stylesheet" href="{{asset('bower_components/Ionicons/css/ionicons.min.css')}}"> -->
  <!-- fullCalendar -->
  <link rel="stylesheet" href="{{asset('plugins/fullcalendar/fullcalendar.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/fullcalendar/fullcalendar.print.min.css')}}" media="print">

         <!--              / css calendario          -->

      <!--               css datatable         -->
{!! Html::style('plugins/datatables.net-bs/css/dataTables.bootstrap.min.css')!!}




   <!--               /css datatable         -->

   
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-green fixed {{(Request::is('graficas') ? 'sidebar-collapse' : '' )}}">





<!-- Site wrapper -->
<div class="wrapper">

  @include('layouts.front.header')

 




  <!-- =============================================== -->

  @include('layouts.front.sidebar')

  <!-- =============================================== -->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
 
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-6">
              <h3 class="box-title">@yield('titulo_area')</h3>
            </div>
            <div class="col-md-6">
              @yield('area_buttons')
            </div>
          </div>
     
        </div>
        <div class="box-body">
        <input type="hidden" value="50" id="streamconsul">
        <input type="hidden" id="user_session_idnumber" value="{{Auth::user()->idnumber}}">

          @yield('area_forms')
         
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
           <!-- /.descomentar esta linea para poner texto -->
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 














 










 




  <!-- =============================================== -->

  <footer class="main-footer" style="padding: 0px;">
    <div class="row">
      <div class="col-md-7">
        <strong><a href="https://www.facebook.com/Amatai-Ingenier%C3%ADa-Inform%C3%A1tica-1055511737884664/" target="_blank">AMATAI Ingeniería Informática</a></strong>
      </div>
      <div class="col-md-3">
        <div class="pull-right hidden-xs">
      <b>Version</b> 3.0
      </div>
    </div>
      <div class="col-md-2">
        <div id="fecha_sistema"> </div>
      </div>
      </div>
    </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


<script language="Javascript" type="text/javascript">
function getDateServer(){
  return  fecha_serv = "{!!date('Y-m-d')!!}"
}
  

</script>


<!-- jQuery 2.2.3 -->
{!! Html::script('plugins/jQuery/jquery-2.2.3.min.js')!!}
<!-- Bootstrap 3.3.6 -->
{!! Html::script('bootstrap/js/bootstrap.min.js')!!}
<!-- jQuery UI -->
{{-- {!! Html::script('plugins/jQueryUI/jquery-ui.min.js')!!}
{!! Html::script('plugins/jQueryUI/jquery-ui-lan-es.js')!!} --}}

<!-- SlimScroll -->
{!! Html::script('plugins/slimScroll/jquery.slimscroll.min.js')!!}
<!-- FastClick -->
{!! Html::script('plugins/fastclick/fastclick.js')!!}
<!-- AdminLTE App -->
{!! Html::script('dist/js/app.min.js')!!}








<!-- adicionales -->

<!-- script aliases -->
{!! Html::script('dist/js/page.js')!!}

<!-- Select2 -->
{!! Html::script('plugins/select2/select2.full.min.js')!!}
<!-- InputMask -->
{!! Html::script('plugins/input-mask/jquery.inputmask.js')!!}
{!! Html::script('plugins/input-mask/jquery.inputmask.date.extensions.js')!!}
{!! Html::script('plugins/input-mask/jquery.inputmask.extensions.js')!!}
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>


{!! Html::script('plugins/daterangepicker/daterangepicker.js')!!}
<!-- bootstrap datepicker -->
{!! Html::script('plugins/datepicker/bootstrap-datepicker.js')!!}
{!! Html::script('plugins/datepicker/js/moment.min.js')!!}

{!! Html::script('plugins/datepicker/js/bootstrap-datetimepicker.min.js')!!}
{!! Html::script('plugins/datepicker/js/lan-es.js')!!}
<!-- bootstrap color picker -->
{!! Html::script('plugins/colorpicker/bootstrap-colorpicker.min.js')!!}
<!-- bootstrap time picker -->
{!! Html::script('plugins/timepicker/bootstrap-timepicker.min.js')!!}
<!-- iCheck 1.0.1 -->
{!! Html::script('plugins/iCheck/icheck.min.js')!!}
<!-- amCharts-->
<!-- Resources -->
{{-- <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
 --}}
{!! Html::script('plugins/amcharts/amcharts.js')!!}
{!! Html::script('plugins/amcharts/serial.js')!!}
{!! Html::script('plugins/amcharts/pie.js')!!}


<!-- /adicionales -->
{!! Html::script('plugins/dropzone/dropzone.js')!!}
{!! Html::script('plugins/tableSorter/jquery.tablesorter.min.js')!!}

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>

<script src="{{ asset('plugins/alertifyJS/alertify.min.js') }}"></script>
<!-- SweetAlert2 -->
  <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<!-- NewPush -->
<script>var tokendefault = '';</script>
<script src="{{asset('plugins/new-push/io.js?v=3')}}"></script>
<script src="{{asset('js/newpush.js?v=3')}}"></script>
{{-- <scriptsrc="asset('js/pushconnected.js?v=3')"></script>--}}

<!-- /propios -->
{!! Html::script('scripts_serv.js?v=3')!!}
{!! Html::script('js/AdminRoles.js?v=3')!!}
{!! Html::script('js/java.js?v=3')!!}
{!! Html::script('js/graficas.js?v=3')!!}
{!! Html::script('js/excel.js?v=3')!!}


@yield('script_calendar')

 @stack('scripts')

<div id="wait" style="display:none; position: absolute; width: 100%;min-height: 100%;height: auto;position: fixed;top:0; left:0;background-color: rgba(236, 240, 245, 0.8);" ><img src="{{asset('img/logo2.png')}}" id="load" width="67" height="71" style="margin-top:18%;margin-left:48%;padding:2px;" /><br><span style="margin-top:18%;margin-left:48%;padding:2px;color:#848484;font-size: 16px;">Cargando...<span></div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var mySelect = $('#first-disabled2');

    $('#special').on('click', function () {
      mySelect.find('option:selected').prop('disabled', true);
      mySelect.selectpicker('refresh');
    });

    $('#special2').on('click', function () {
      mySelect.find('option:disabled').prop('disabled', false);
      mySelect.selectpicker('refresh');
    });

    $('#basic2').selectpicker({
      liveSearch: true,
      maxOptions: 1
    });

  

   

  });
</script>
</body>
</html>
