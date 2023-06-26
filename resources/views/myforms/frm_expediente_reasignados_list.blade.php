@extends('layouts.dashboard')
@section('titulo_general')
Expedientes
@endsection

@section('titulo_area')
Reasignaciones
@endsection
@section('area_forms')

@include('msg.success')

<div class="row">
  <div class="col-md-12">
  <div class="box-body table-responsive no-padding">
    <table class="table">
      <thead>
        <th>
          Expediente
        </th>
        <th>
          Estudiante
        </th>
        <th>
          Motivo Asignación
        </th>
        <th>
          Tipo Asignación
        </th>
        <th>
          Fecha
        </th>
        <th>
          Acción
        </th>
      </thead>
      <tbody>
        @foreach($expreasignados as $asignacion)
          <tr>
            <td>
              {{ $asignacion->asigexp_id }}
            </td>
            <td>
              {{$asignacion->estudiante->name}} {{$asignacion->estudiante->lastname}}
            </td>
            <td>
              {{ $asignacion->motivo_asig->nom_motivo }}
            </td>
            <td>
              {{ $asignacion->tipo_asig->nombre_asig }}
            </td>
            <td>
              {{ $asignacion->created_at }}
            </td>
            <td>
              {!! link_to_route('expedientes.edit', $title = 'Ir al Caso', $parameters = $asignacion->asigexp_id, $attributes = ['class'=>'btn btn-success btn-sm btn-edit-le']) !!}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $expreasignados->appends(request()->query())->links() }}
    </div>
  </div>
</div>



@stop