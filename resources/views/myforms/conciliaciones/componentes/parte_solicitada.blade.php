@php

    $solicitados = $conciliacion->personasPorTipo('convocado')->get();
@endphp

<div class="card card-outline card-info" id="parte_solicitada_rep_legal">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <h4 align="center" style="color: #000000; font-weight: bold;">
                   PERSONAS SOLICITADAS</h4>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="contentFormsParteCovocada">

            @foreach ($solicitados as $key => $solicitado)
                <form id="myFormParteConvocada_{{ $key }}" class="myFormParteConvocada modern-form-card"
                    method="POST">

                    <div class="form-card">

                        {{-- HEADER --}}
                        <div class="form-card-header">
                            <div class="form-title">
                                <i class="fas fa-user"></i>
                                Parte convocada
                                <span class="form-number">{{ $key + 1 }}</span>
                            </div>
                        </div>

                        {{-- BODY --}}
                        <div class="row form-card-body" id="content_solicitada">
                            <input hidden type="text" name="conc_persona_externa_id" value="{{ $solicitado->id }}">
                            <input hidden type="text" name="persona_externa_id"
                                value="{{ $solicitado->persona_externa_id }}">
                            @include('myforms.categorias.refs_aditional_data', [
                                'data' => $solicitado->persona->preguntas()->orderBy('orden', 'asc')->get(),
                                'col' => 3,
                                'model' => $solicitado,
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
                                'user' => $solicitado,
                            ])
                            <div class="col-md-12">
                                {{--  <h3>
                        Información socio-económica
                    </h3> --}}
                            </div>
                            @include('myforms.components_user.aditional_data', [
                                'data' => getReferencesDataBySection('enfoque_diferencial', 'users'),
                                'user' => $solicitado,
                            ])
                        </div>

                        {{-- FOOTER --}}
                        {{--  <div class="form-card-footer">
                    <button data-type="197" type="button" class="btn btn-primary btn_save_parte_convocada"
                        data-index="{{ $key }}">
                        <i class="fas fa-save mr-1"></i>
                        Guardar información
                    </button>
                </div> --}}

                    </div>

                </form>
            @endforeach
        </div>
    </div>
</div>