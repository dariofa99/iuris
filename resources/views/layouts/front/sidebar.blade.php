 <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">

         {!! HTML::image('thumbnails/'.currentUser()->image,'User Image', array('class' => 'img-circle')) !!}
           
        </div>

        <div class="pull-left info">
          

          <p>
            @if(currentUser()->turno)
            <span  class="badge {{ (currentUser()->getColorTurno(currentUser()->turno->color->ref_value)) }}">.</span>

            @endif
              {{  currentUser()->name  }} </p>
          <a href="#"><label class="label ">Online</label> </a>
        </div>
        
      </div>

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MENÚ DE NAVEGACIÓN</li> 
  

    


{{-----------------------------------menu expedientes----------------------------------------}}

               <li class="treeview active">
         
        
          <a href="#">
            <i class="fa fa-folder-open"></i> <span>
              
                Casos activos
              </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
       
          @foreach(currentUser()->solicitudes()->whereIn('type_status_id',[162,165,155,156,154])->get() as $key => $value)
            <li>
              <a href="{{ url('/oficina/solicitante/solicitud',$value->id) }}">
              {{ $value->number }} <small>({{ $value->estado->ref_nombre }})</small>
            </a>          
        </li>  
          @endforeach
         
             
        
           
          </ul>
        </li>

{{-----------------------------------menu expedientes----------------------------------------}}

            @if ( isset($active_expe))
              <li class="treeview {{ $active_expe }}">
            @else
               <li class="treeview">
            @endif 
        
          <a href="#">
            <i class="fa fa-folder-open"></i> <span>
              
             Historial casos              </span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
             
          @foreach(currentUser()->solicitudes()->whereIn('type_status_id',[157,158])->get() as $key => $value)
            <li><a href="{{ url('/oficina/solicitante/solicitud',$value->id) }}">{{ $value->number }}</a>          
            </li> 
          @endforeach
             
         
           
          </ul>
        </li>


<!--         <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Multilevel</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li onclick="LoadContent(this.id)" id="directory"><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
            <li>
              <a href="#"><i class="fa fa-circle-o"></i> Level One
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                <li>
                  <a href="#"><i class="fa fa-circle-o"></i> Level Two
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
          </ul>
        </li> -->



@if(currentUser()->can("crear_conciliaciones") and currentUser()->hasRole("solicitante"))           
        <li class="treeview">        
                  <a href="#">
                  <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                    <span>Conciliaciones</span>  
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
            <ul class="treeview-menu">        
              <li><a href="{{route("front.conciliaciones")}}"> Mis conciliaciones </a></li>   
              {{-- <li><a href="/audiencias"> Agenda audiencias </a></li>  
              <li><a href="{{route('reportes.create')}}"> Administrar formatos </a></li> 
              --}}        
            </ul>
@if(count($auth_conciliaciones)>0)
            <ul class="treeview-menu">        
              <li><a href="{{route("front.conciliaciones.solicitud")}}"> Solicitudes de conciliación </a></li>   
              {{-- <li><a href="/audiencias"> Agenda audiencias </a></li>  
              <li><a href="{{route('reportes.create')}}"> Administrar formatos </a></li> 
              --}}        
            </ul>
@endif
      </li>
@endif
          







    
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
