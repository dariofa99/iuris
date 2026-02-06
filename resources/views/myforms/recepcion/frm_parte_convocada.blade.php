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
    // dd($numConv);
@endphp

<div id="contentFormsParteCovocada">

@if ($numConv > 0)
    @for ($i = 0; $i < $numConv; $i++)
        @php
            $user = isset($users[$i]) ? $users[$i] : $conciliacion->getUser(00);
        @endphp
        {{--    <form id="myFormParteConvocada_{{ $i }}" class="myFormParteConvocada" method="POST">
                <div class="box_section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                Ingrese la información de la persona convocada
                                <span class="badge badge-light">{{ $i + 1 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="content_solicitada"
                        style="display: {{ ($user->idnumber == null and $conciliacion->getStaticDataValByShortName('informacion_parte_convocada_(ej._nombres,_direccion,_telefono)', 'parte_solicitada')) ? 'none' : 'auto' }}">
                        @include('myforms.conciliaciones.componentes.formulario_parte_solicitada', [
                            'disabled' => $user->idnumber == null ?: 'disabled',
                        ])
                        @include('myforms.components_user.identitaria', [
                            'disabled' => $user->idnumber == null ?: 'disabled',
                        ])
                        @include('myforms.components_user.discapacidad', [
                           'disabled' => $user->idnumber == null ?: 'disabled',
                            'discaform' => 'discaform',
                        ])
                    </div>
 
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-primary btn_save_parte_convocada"
                            data-index="{{ $i }}">Guardar información
                        </button>
                    </div>
                </div>
            </form> --}}
        <form id="myFormParteConvocada_{{ $i }}" class="myFormParteConvocada modern-form-card"
            method="POST">

            <div class="form-card">

                {{-- HEADER --}}
                <div class="form-card-header">
                    <div class="form-title">
                        <i class="fas fa-user"></i>
                        Parte convocada
                        <span class="form-number">{{ $i + 1 }}</span>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="row form-card-body" id="content_solicitada"
                    style="display: {{ ($user->idnumber == null and $conciliacion->getStaticDataValByShortName('informacion_parte_convocada_(ej._nombres,_direccion,_telefono)', 'parte_solicitada')) ? 'none' : 'auto' }}">

                    @include('myforms.conciliaciones.componentes.formulario_parte_solicitada', [
                        'disabled' => $user->idnumber == null ?: 'disabled',
                    ])

                    @include('myforms.components_user.identitaria', [
                        'disabled' => $user->idnumber == null ?: 'disabled',
                    ])

                    @include('myforms.components_user.discapacidad', [
                        'disabled' => $user->idnumber == null ?: 'disabled',
                        'discaform' => 'discaform',
                    ])

                </div>

                {{-- FOOTER --}}
                <div class="form-card-footer">
                    <button data-type="197" {{$user->idnumber == null ?: 'disabled'}} type="button" class="btn btn-primary {{$user->idnumber != null ?: 'btn_save_parte_convocada'}} "
                        data-index="{{ $i }}">
                        <i class="fas fa-save mr-1"></i>
                        Guardar información
                    </button>
                </div>

            </div>

        </form>

        <hr>
    @endfor
@endif

</div>
