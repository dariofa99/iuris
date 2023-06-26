@if($encabezado!=null)
<header style="width: 100%;text-align:{{$encab_conf->encabezado_align}}">
    <img width="{{$encab_conf->encab_width}}" height="{{$encab_conf->encab_height}}px" src="{{asset('/storage/'.$encabezado->path)}}" alt="">
</header>
@endif