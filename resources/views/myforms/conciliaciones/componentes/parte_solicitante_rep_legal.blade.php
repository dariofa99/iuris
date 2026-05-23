@php
    $solicitados = $conciliacion->personasPorTipo('convocado')->get();

    $representantes_legales = $conciliacion->personasPorTipo('representante_legal')->get();

    //dd($solicitados);

@endphp

@foreach ($representantes_legales as $key => $representante_legal)
    <form id="myFormParteConvocada_{{ $key }}" class="myFormParteConvocada modern-form-card" method="POST">

        <div class="card">

            <input type="hidden" value="{{ $solicitados[$key]->id }}">
            {{-- HEADER --}}
            <div class="card-header">
                <div class="form-title">
                    <i class="fas fa-user"></i>
                    Representante Legal
                    <span class="form-number">{{ $key + 1 }}</span>
                </div>
            </div>

            {{-- BODY --}}
            <div class="row card-body" id="content_solicitada">
                <input type="hidden" name="persona_externa_id" value="{{ $representante_legal->id }}">
                @include('myforms.categorias.refs_aditional_data', [
                    'data' => $representante_legal->persona->preguntas()->orderBy('orden', 'asc')->get(),
                    'col' => 3,
                    'model' => $representante_legal,
                    // 'design' => 'card_question',
                ])
                <div class="col-md-12">
                    <h4>
                       INFORMACIÓN IDENTITARIA Y DE INCLUSIVIDAD
                    </h4>
                </div>
                @include('myforms.components_user.aditional_data', [
                    'data' => getReferencesDataBySection('discapacidad', 'users'),
                    //'discaform' => 'discaform',
                    'user' => $representante_legal,
                ])
                <div class="col-md-12">
                   {{--  <h3>
                        Información socio-económica
                    </h3> --}}
                </div>
                @include('myforms.components_user.aditional_data', [
                    'data' => getReferencesDataBySection('enfoque_diferencial', 'users'),
                    'user' => $representante_legal,
                ])



            </div>

            {{-- FOOTER --}}
            <div class="card-footer">
                <button data-type="197" type="button" class="btn btn-primary btn_save_parte_convocada"
                    data-index="{{ $key }}">
                    <i class="fas fa-save mr-1"></i>
                    Guardar información
                </button>
            </div>

        </div>

    </form>
@endforeach








{{-- @foreach ($conciliacion->usuarios()->where('tipo_usuario_id', 197)->get() as $key => $parte_)
    @if ($parte_->tipo_persona->id == 238)
        <div class="row">
            <div class="col-md-10">
                <h4>
                    Representante Legal para el Convocado:
                    {{ $parte_->name }} {{ $parte_->lastname }}


                </h4>
            </div>
        </div> --}}
{{--  <div class="row">
            <div class="col-md-2">
                <button id="btn_opaddrpl-{{ $key }}" data-key="{{ $key }}"
                    class="btn_opaddrpl btn btn-success btn-xs btn-block" type="button">
                    <i class="fa fa-user"></i>
                    Agregar
                </button>
            </div>
        </div> --}}
{{--  @php
            $hasrep_legales = false;
        @endphp
        @forelse ($parte_->conc_rep_legal as $key_ => $conciliacion_rep_legal)
            @php
                $rep_legal = $conciliacion->getUserByFilter([
                    'tipo_usuario_id' => 198,
                    'user_id' => $conciliacion_rep_legal->pivot->user_replegal_id,
                ]);
                $hasrep_legales = true;
            @endphp


            <div class="card card card-outline card-success p-2 list_user_rep_legal_form-{{ $key }}">
                <div class="row">
                    @include('myforms.conciliaciones.componentes.formulario_rep_legal', [
                        'disabled' => 'disabled',
                        'user' => $rep_legal,
                    ])
                    @include('myforms.components_user.identitaria', [
                        'user' => $rep_legal,
                        'disabled' => 'disabled',
                    ])
                    @include('myforms.components_user.discapacidad', [
                        'user' => $rep_legal,
                        'disabled' => 'disabled',
                        'discaform' => 'discaform',
                    ])
                </div>
            </div>





        @empty
        @endforelse

        <div class="card card-outline card-success" id="user_rep_legal_form-{{ $key }}"
            style="display: {{ $hasrep_legales ? 'none' : 'block' }};">
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
                        @include('myforms.components_user.identitaria', [
                            'disabled' => '',
                        ])
                        @include('myforms.components_user.discapacidad', [
                            'disabled' => '',
                            'discaform' => 'discaform',
                        ])

                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" data-type="198"
                                class="btn btn-primary btn-lg btn-block btn_add_replegal"
                                data-key="{{ $key }}" id="btn_add_replegal">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach --}}

{{-- <div id="user_rep_legal_form">
    @include('myforms.conciliaciones.componentes.user_replegal_form')
</div> --}}
