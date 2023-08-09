<?php 
if(!isset($modo_nav_crl)){
//modo oscuro/claro barra lateral 
$modo_nav_crl="light";
$modo_sidebar="light";
//modo oscuro/claro nav superior 
$color_nav="green";
$modo_nav="dark";
}
?>


  <nav class="main-header navbar navbar-expand navbar-{{ $color_nav  }} navbar-{{ $modo_nav  }}">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars">
      </i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link"></a>
      </li>
      
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <!--<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">-->
        <div class="input-group-append">
          <!--<button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>-->
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    @include('content.navbar_right')

  </nav>