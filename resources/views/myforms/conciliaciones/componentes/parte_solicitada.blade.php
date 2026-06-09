@php
    $user = $conciliacion->getUser(197);
     $solicitados = $conciliacion->personasPorTipo('convocado')->get();
@endphp
<div class="card card-outline card-info" id="parte_solicitada">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <h4 align="center"> <strong> PARTE SOLICITADA </strong>
                </h4>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row" id="content_solicitada"
            style="display: {{ $user->idnumber != null ? 'block' : 'block' }};width:100%">

            <div id="user_solicitante_form">
                {{-- @include('myforms.conciliaciones.componentes.user_partesolicitada_form') --}}
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
                                <input type="text" name="persona_externa_id" value="{{ $solicitado->id }}">
                                @include('myforms.categorias.refs_aditional_data', [
                                    'data' => $solicitado->persona->preguntas()->orderBy('orden', 'asc')->get(),
                                    'col' => 3,
                                    'model' => $solicitado,
                                    // 'design' => 'card_question',
                                ])
                            </div>

                            {{-- FOOTER --}}
                            {{-- <div class="form-card-footer">
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
</div>
