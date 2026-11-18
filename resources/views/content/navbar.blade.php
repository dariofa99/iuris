<?php 
if(!isset($modo_nav_crl)){
//modo oscuro/claro barra lateral 
$modo_nav_crl="light";
$modo_sidebar="light";
//modo oscuro/claro nav superior 
$color_nav="green";
$modo_nav= config('app.name') == 'ConciliApp' ? 'light' : 'dark';
}
?>


  <nav class="main-header navbar navbar-expand navbar-{{ $color_nav }} navbar-{{ $modo_nav }} iuris-topbar {{ config('app.name') == 'ConciliApp' ? 'iuris-topbar--conciliapp' : '' }}">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link iuris-topbar__toggle" data-widget="pushmenu" href="#" role="button"
          aria-label="Contraer o expandir el menú" title="Menú">
          <i class="fas fa-bars" aria-hidden="true"></i>
        </a>
      </li>
    </ul>

    <!-- Right navbar links -->
    @include('content.navbar_right')

  </nav>