@foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $parte_)
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
@endforeach