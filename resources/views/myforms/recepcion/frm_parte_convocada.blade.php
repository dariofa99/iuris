@push('styles')
    <style>
        /* contenedor general */
        #contentFormsParteCovocada {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        /* card */
        .form-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .06);
            overflow: hidden;
            transition: .25s ease;
        }

        /* hover suave */
        .form-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 35px rgba(0, 0, 0, .10);
        }

        /* header */
        .form-card-header {
            padding: 16px 22px;
            background: linear-gradient(135deg, #f8f9fa, #eef2f6);
            border-bottom: 1px solid #e9ecef;
        }

        .form-title {
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* número */
        .form-number {
            background: #0d6efd;
            color: white;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* body */
        .form-card-body {
            padding: 22px;
        }

        /* footer */
        .form-card-footer {
            padding: 16px 22px;
            border-top: 1px solid #eee;
            text-align: right;
            background: #fafbfc;
        }

        /* botón moderno */
        .btn_save_parte_convocada {
            border-radius: 10px;
            padding: 8px 18px;
            font-weight: 500;
        }
    </style>
@endpush

@php
    $user = $conciliacion->getUser(197);
    $users = $conciliacion->usuarios()->where('tipo_usuario_id', 197)->get();
    $numConv = $conciliacion->getStaticDataValByShortName('no._convocados', 'asunto');
    $numConv = $numConv->value;
    $solicitados = $conciliacion->personasPorTipo('convocado')->get();

@endphp

<div id="contentFormsParteCovocada">

    @foreach ($solicitados as $key => $solicitado)
        <form id="myFormParteConvocada_{{ $key }}" class="myFormParteConvocada modern-form-card" method="POST">

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
                <div class="form-card-footer">
                    <button data-type="197" type="button"
                        class="btn btn-primary btn_save_parte_convocada"
                        data-index="{{ $key }}">
                        <i class="fas fa-save mr-1"></i>
                        Guardar información
                    </button>
                </div>

            </div>

        </form>
    @endforeach

   

</div>
