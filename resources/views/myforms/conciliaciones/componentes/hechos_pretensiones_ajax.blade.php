@foreach($conciliacion->hechos_pretensiones()->where('tipo_id',$tipo_id)->get() as $key => $hecho)
<span class="badge badge-info">
  {{ $key + 1 }}                   
</span>
<div class="content_he_pret count_input_descrip_hepr count_input_descrip_hepr_{{$tipo_id}}">
  <textarea disabled @if(($conciliacion->estado_id!=177 and $conciliacion->estado_id!=179)  and !auth()->user()->can('act_conciliacion'))
    disabled 
    class="form-control"
    @else 
        class="form-control "
    @endif rows="4" data-name="hechos"  required>{{$hecho->descripcion}}</textarea>

    <div class="btn-group" style="display: block">
      &nbsp; &nbsp;
      <small>
      <i> Creado por: {{$hecho->user->name}} {{$hecho->user->lastname}}. {{getSmallDateWithHour($hecho->created_at)}}</i>
      </small>
     
      @if($hecho->user_id == auth()->user()->id)
     {{--  @if(($conciliacion->estado_id==174 || $conciliacion->estado_id==176 || $conciliacion->estado_id==194)) --}}
      @if($tipo_id=='207' and !Request::has("id"))
      <a href="#" data-id="{{$hecho->id}}" data-estado_id="{{$hecho->estado_id}}"  class="btn_estado_hepr pull-right btn_hepr"> Estado </a>
      @endif
      <a href="#"  data-id="{{$hecho->id}}" class="btn_editar_hepr float-right btn_hepr m-1"> Editar </a> 
      <a href="#" data-id="{{$hecho->id}}" class="btn_eliminar_hepr float-right btn_hepr m-1"> Eliminar</a>
     {{--  @endif --}}
      @endif
       
      </div>
</div>
       
@endforeach  