<header class="main-header" >

    <!-- Logo -->
   {{--  <a href="/dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="{{ asset('dist/img/consultorios-min.png') }}" alt="Sis Image" style="height: 45px;"> <b>Iuris</b></span>
    </a> --}}

    <a href="/dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>I</b>U</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
    {{-- <img src="{{ asset('dist/img/consultorios-min.png') }}" alt="Sis Image" style="height: 45px;"> 
     --}}     
     <img src="{{ asset('dist/img/conciliapp_logo_horizontal.png') }}" alt="Sis Image" style="height: 45px;">
       <b>
        {{-- Iuris --}} 
        </b>
      </span>
    </a>
   
  
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" {{-- style="background-color: #2b4b5a !important" --}}>
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav" id="menu-notification">


  <!-- aqui empiezan las notificaciones se puede descomentar...........................................  -->
             <!-- Messages: style can be found in dropdown.less-->
              
            @include('layouts.notifications',[
              'user'=>Auth::user()
            ])
      




       
        </ul>
      </div>
    </nav>
  </header>
