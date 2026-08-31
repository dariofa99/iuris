@php
    $solicitados = $conciliacion->personasPorTipo('convocado')->get();

    $representantes_legales = $conciliacion->personasPorTipo('representante_legal')->get();

@endphp

@foreach ($representantes_legales as $key => $representante_legal)
    <form id="myFormParteConvocada_{{ $key }}" class="myFormParteConvocada modern-form-card" method="POST">

        <div class="form-card">

            <input type="hidden" value="{{$solicitados[$key]->id}}">
            {{-- HEADER --}}
            <div class="form-card-header">
                <div class="form-title">
                    <i class="fas fa-user"></i> 
                   Rep. legal de convocado 
                    {{ $solicitados[$key]->persona->name }} {{ $solicitados[$key]->persona->lastname }}
                    <span class="form-number">{{ $key + 1 }}</span>
                </div>
            </div>

            {{-- BODY --}}
            <div class="row form-card-body" id="content_solicitada">
                <input type="hidden" name="persona_externa_id" value="{{ $representante_legal->id }}">
                @include('myforms.categorias.refs_aditional_data', [
                    'data' => $representante_legal->persona->preguntas()->orderBy('orden', 'asc')->get(),
                    'col' => 3,
                    'model' => $representante_legal,
                    'disabled' => 'disabled',
                ])
            </div>

            {{-- FOOTER --}}
          {{--   <div class="form-card-footer">
                <button data-type="197" type="button" class="btn btn-primary btn_save_parte_convocada"
                    data-index="{{ $key }}">
                    <i class="fas fa-save mr-1"></i>
                    Guardar información
                </button>
            </div> --}}

        </div>

    </form>
@endforeach



{{-- @foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $parte_)
    @if ($parte_->tipo_persona->id == 238)
        <div class="row">
            <div class="col-md-10">
                <h4>
                    Rep. legal para el convocado 
                    {{ $parte_->name }} {{ $parte_->lastname }}


                </h4>
            </div>
        </div>
        @forelse ($parte_->conc_rep_legal as $key_ => $conciliacion_rep_legal)
            @php
                $rep_legal = $conciliacion->getUserByFilter([
                    'tipo_usuario_id' => 198,
                    'user_id' => $conciliacion_rep_legal->pivot->user_replegal_id,
                ]);
            @endphp


            <div class="card list_user_rep_legal_form-{{ $key }}">
                <div class="card-header">
                    Información del representante legal
                    <span class="badge badge-info">
                        {{ $key_ + 1 }}                   
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        @include('myforms.conciliaciones.componentes.formulario_rep_legal', [
                            'disabled' => 'disabled',
                            'user' => $rep_legal,
                        ])
                    </div>
                </div>
              
            </div>
           
        @empty
            Todavia no hay usuarios
        @endforelse

    @endif
@endforeach  --}}