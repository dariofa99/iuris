@component('components.b4.modal_large')

	@slot('trigger')
		mymodalShowAlerts
	@endslot
	@slot('size')
	modal-dialog modal-lg
	@endslot

	@slot('title')
		<h3>Información importante!</h3>
	@endslot

 
	@slot('body')


@section('msg-contenido')
Registrado
@endsection
@include('msg.ajax.success')
<div id='modal-show-alerts-content'>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
          <li data-target="#carousel-example-generic" data-slide-to="1"></li>
         {{--  <li data-target="#carousel-example-generic" data-slide-to="2"></li> --}}
        </ol>
      
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox" >
          <div class="item active img_carrousel" >
            <img src="{{asset("/dist/img/update/Diapositiva1.JPG")}}"  alt="...">
            <div class="carousel-caption">
             
            </div>
          </div>
          <div class="item img_carrousel" >
            <img src="{{asset("/dist/img/update/Diapositiva2.JPG")}}"  alt="...">
            <div class="carousel-caption">
              
            </div>
          </div>
         
        </div>
      
        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true" style="color:black"></span>
          <span class="sr-only">Atras</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" style="color:black" aria-hidden="true"></span>
          <span class="sr-only">Adelante</span>
        </a>
      </div>
</div>

	@endslot
@endcomponent
<!-- /modal -->










