@php
$estudiantes=[];
$tipos=[];
    foreach ($conciliacion->usuarios as $key => $usert) {
      if($usert->hasRole("estudiante") and ($usert->pivot->tipo_usuario_id==203 || $usert->pivot->tipo_usuario_id==204)){
        $estudiantes[$usert->idnumber] = $usert;
      
    }
    }
@endphp

@foreach($estudiantes as $key => $user)
@php
    $user->origen = 5;
@endphp

<tr>
 
    <td>{{$user->name}} {{$user->lastname}}</td>
    <td>{{$user->email}}</td>
    <td>
      @foreach ($user->tipo_conciliacion()
      ->where('conciliacion_id',$conciliacion->id)
      ->get() as $tipo)
      {{$tipo->ref_nombre}};   
      @endforeach
      
    </td>
    <td>
      <span class="badge" style="display: block; background-color:{{$user->estado_conciliacion()->where(['tipo_usuario_id'=>$user->pivot->tipo_usuario_id,'conciliacion_id'=>$conciliacion->id])->first()->color}}">
          {{$user->estado_conciliacion()->where(['tipo_usuario_id'=>$user->pivot->tipo_usuario_id,'conciliacion_id'=>$conciliacion->id])->first()->ref_nombre}}

      </span>
  </td>
    <td>{{getSmallDateWithHour($user->pivot->created_at)}}</td>
    <td>   

      @if (count($user->get_notas_ext($conciliacion->id,['puntualidad'])['puntualidad']) > 0 and 
      $user->get_notas_ext($conciliacion->id,['puntualidad'])['puntualidad']['encontrado'] and
      ($user->get_notas_ext($conciliacion->id,['puntualidad'])['puntualidad']['estidnumber'] == currentUser()->idnumber || currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai'))
      )
        
      {{-- <a target="_blank" href="/notas/ver/estudiante?idnumber={{$user->idnumber}}&origen=conciliaciones" data-user="{{$user->idnumber}}" data-class="btn_edit_notas" data-pivot="{{$user->pivot->id}}" class="btn btn-sm  btn-info  ">  
       Ver notas
      </a>   --}} 
      <button data-type="{{$user->pivot->tipo_usuario_id}}" type="button" data-user="{{$user->idnumber}}" data-pivot="{{$user->pivot->id}}" class="btn btn-sm  btn_edit_notas btn-info">  
        Ver
       </button>
      @elseif(currentUser()->hasRole('coord_centro_conciliacion') || currentUser()->hasRole('amatai'))
      
      <button data-type="{{$user->pivot->tipo_usuario_id}}" type="button" data-user="{{$user->idnumber}}" data-pivot="{{$user->pivot->id}}" class="btn btn-sm  btn_add_usuario_notas btn-primary">  
        Agregar notas
      </button>

      @endif

     
      @if (count($user->get_notas_ext($conciliacion->id,['puntualidad'])['puntualidad']) > 0 and 
      $user->get_notas_ext($conciliacion->id,['puntualidad'])['puntualidad']['encontrado'] and 
      $user->get_notas_ext($conciliacion->id,['puntualidad'])['puntualidad']['docevidnumber'] == currentUser()->idnumber)
      <button type="button" data-user="{{$user->idnumber}}" data-pivot="{{$user->pivot->id}}"  data-section="general" class="btn btn-danger btn-sm btn_delete_notas_con">  
            Eliminar
            </button>
      @endif
  
    </td>
</tr>

@endforeach