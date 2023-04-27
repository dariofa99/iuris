

<!DOCTYPE html>
<html lang="en-GB">
<head>
    <title>My Title</title>

    @section('head')
        
    @show
</head>
<body>

maestra

    @yield('content')

    @section('footer')
       <!-- jQuery 2.2.3 -->
{!! Html::script('plugins/jQuery/jquery-2.2.3.min.js')!!}
<!-- Bootstrap 3.3.6 -->
{!! Html::script('bootstrap/js/bootstrap.min.js')!!}
<!-- SlimScroll -->
{!! Html::script('plugins/slimScroll/jquery.slimscroll.min.js')!!}
<!-- FastClick -->
{!! Html::script('plugins/fastclick/fastclick.js')!!}
<!-- AdminLTE App -->
{!! Html::script('dist/js/app.min.js')!!}
<!-- AdminLTE for demo purposes -->

{!! Html::script('prueba.js')!!}
{!! Html::script('plugins/datatables/jquery.dataTables.min.js')!!}
{!! Html::script('plugins/datatables/dataTables.bootstrap.min.js')!!}






<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

    @show
</body>
</html>