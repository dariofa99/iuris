@foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $parte_)
    @if ($parte_->tipo_persona->id == 238)
        <div class="row">
            <div class="col-md-10">
                <h4>
                    Representante Legal para el Convocado:
                    {{ $parte_->name }} {{ $parte_->lastname }}


                </h4>
            </div>

            <div class="col-md-2">
                <button id="btn_opaddrpl-{{ $key }}" data-key="{{ $key }}"
                    class="btn_opaddrpl btn btn-success btn-xs btn-block" type="button">
                    <i class="fa fa-user"></i>
                    Agregar
                </button>
            </div>
        </div>
        @forelse ($parte_->conc_rep_legal as $key_ => $conciliacion_rep_legal)
            @php
                $rep_legal = $conciliacion->getUserByFilter([
                    'tipo_usuario_id' => 198,
                    'user_id' => $conciliacion_rep_legal->pivot->user_replegal_id,
                ]);
            @endphp


            <div class="card card card-outline card-success p-2 list_user_rep_legal_form-{{ $key }}">
                <div class="row">
                    @include('myforms.conciliaciones.componentes.formulario_rep_legal', [
                        'disabled' => 'disabled',
                        'user' => $rep_legal,
                    ])
                    @include('myforms.components_user.identitaria',[
                        'user'=>$rep_legal,
                         'disabled' => 'disabled',
                    ])
                    @include('myforms.components_user.discapacidad',[
                        'user'=>$rep_legal,
                        'disabled' => 'disabled',
                        'discaform' => 'discaform',
                    ])
                </div>
            </div>





        @empty
            Todavia no hay usuarios
        @endforelse

        <div class="card card-outline card-success" id="user_rep_legal_form-{{ $key }}" style="display: none;">
            <div class="card-header">
                <h4> Agregar Representante Legal </h4>
            </div>
            <div class="card-body">
                <form class="myUserRepLegalForm" id="myUserRepLegalForm-{{ $key }}"
                    data-view="user_replegal_form" data-juridico="{{ $parte_->id }}">
                    <div class="row">
                        @include('myforms.conciliaciones.componentes.formulario_rep_legal', [
                            'disabled' => '',
                        ])
                        @include('myforms.components_user.identitaria',[
                            'disabled' => '',
                           

                        ])
                        @include('myforms.components_user.discapacidad',[
                            'disabled' => '',
                            'discaform' => 'discaform',
                        ])
                     
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" data-type="198"
                                class="btn btn-primary btn-xs btn-block btn_add_replegal" data-key="{{ $key }}"
                                id="btn_add_replegal">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

{{-- <div id="user_rep_legal_form">
    @include('myforms.conciliaciones.componentes.user_replegal_form')
</div> --}}
