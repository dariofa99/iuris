<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Iuris</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">
  {!! Html::style('/css/styles.css')!!}

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>



  <![endif]-->



<link href="/img/favicon.ico" rel="icon" type="image/x-icon" />

<style>
.alert {
    padding: 10px !important;
}

</style>


</head>
<body class="hold-transition login-page">
<div class="login-box">
  
  <!-- /.login-logo -->
  <div class="login-box-body">
   
    <div class="login-logo">
      <h4><b>Consultorios Jur&iacute;dicos Virtuales</b></h4>


     <img class="profile-user-img img-responsive img-circle" src="/dist/img/logoudenar.jpg" >
      <h5>Ingrese el número de radicado</h5>
    </div>

    
    @if( Session::has( 'danger' ))
    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span>
        {{ Session::get( 'danger' ) }}
    </div>
    @endif

    <form action="/autorizacion/verificar" method="post">
      {!! csrf_field() !!}
      <div class="row">
        <div class="col-md-12">
          <div class="form-group has-feedback">
        <input id='num_radicado' name='num_radicado' type="text" class="form-control" placeholder="No. Radicado">
        <span class="glyphicon glyphicon-registration-mark form-control-feedback"></span>
      </div>
        </div>
      </div>
   

      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Verificar</button>
        </div>
      </div>
  
    <hr>
       


    </form>
    <br>
    <center><strong><a href="https://www.facebook.com/Amatai-Ingenier%C3%ADa-Inform%C3%A1tica-1055511737884664/" target="_blank">AMATAI Ingeniería Informática</a></strong></center>
    <!-- /.social-auth-links -->

   

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional 
    });
  });
</script>
</body>
</html>
