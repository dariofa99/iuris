@foreach ($data as $key => $reference)
    @if (isset($paginate))
        @php
            $page = 0;
            if (Request::has('page')) {
                $page = Request::get('page');
            }
        @endphp
        @if ($page == $key)
            @include('myforms.categorias.partials.ajax.pregunta')
        @endif
       
    @else
        @include('myforms.categorias.partials.ajax.pregunta')
    @endif
@endforeach
@if (isset($paginate))
            
                <div class="col-md-12 mt-3">
                    @if(($page)>0)
                    <a class="btn btn-default" href={{ url('conciliacion/evaluar/encuesta?page=' . ($page - 1)) }}>
                        Atras
                    </a>
                    @endif
                    @if(($page + 1) < count($data))
                    <a class="btn btn-primary" href={{ url('conciliacion/evaluar/encuesta?page=' . ($page + 1)) }}>
                        Siguiente
                    </a>
                    @endif
                </div>
           
 
@endif
