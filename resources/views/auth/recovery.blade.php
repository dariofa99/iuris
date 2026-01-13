@extends('layouts.app')

@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <style>
        .code-input {
            width: 50px;
            height: 60px;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 0;
        }

        .code-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .code-input.filled {
            background-color: #f0f8ff;
            border-color: #28a745;
        }

        #codeInputContainer {
            gap: 12px !important;
            margin: 30px 0;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        /* Estilos para el loader en el botón */
        .btn-loading {
            position: relative;
            color: transparent;
            pointer-events: none;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spinner 0.8s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .btn-success.btn-loading {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success.btn-loading:hover {
            background-color: #28a745;
            border-color: #28a745;
        }

        /* Animación alternativa más elegante */
        .pulse-dots {
            display: inline-flex;
            gap: 4px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #fff;
            animation: pulse 1.4s infinite;
        }

        .pulse-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .pulse-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(0.8);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0"><i class="fas fa-user-edit"></i> Recuperación de cuenta de usuario</h4>
                    </div>

                    <div class="card-body p-4">
                        <!-- Formulario de datos de cuenta -->
                        <form id="myFormRecoveryAccount">

                            <!-- Tipo de documento y número -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-id-card"></i> Tipo de documento</label>
                                    <select class="form-control" required name="tipodoc_id">
                                        <option value="">-- Selecciona tipo de documento --</option>
                                        @foreach ($tipodoc as $key => $tipo)
                                            <option value="{{ $key }}">{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-hashtag"></i> Número de documento</label>
                                    <input name="idnumber" type="text" class="form-control"
                                        placeholder="Ingresa tu número de documento" required>
                                </div>
                            </div>
                            <!-- Nombre y apellido -->
                            {{--   <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-user"></i> Nombre</label>
                                    <input name="name" type="text" class="form-control"
                                        placeholder="Ingresa tu nombre">
                                </div>
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-user"></i> Apellido</label>
                                    <input name="lastname" type="text" class="form-control"
                                        placeholder="Ingresa tu apellido">
                                </div>
                            </div> --}}



                            <!-- Usuario -->
                            {{--  <div class="form-group">
                                <label><i class="fas fa-id-card"></i> Usuario</label>
                                <input type="text" class="form-control" placeholder="Nombre de usuario">
                            </div>
 --}}
                            <!-- Email -->
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input name="email" type="email" class="form-control"
                                    placeholder="correo@ejemplo.com">
                            </div>

                            <!-- Teléfono -->
                            <div class="form-group">
                                <label><i class="fas fa-phone"></i> Teléfono</label>
                                <input name="phone" type="number" class="form-control" placeholder="Número de teléfono">
                            </div>

                            {{--   <!-- Contraseña -->
                            <div class="form-group">
                                <label><i class="fas fa-lock"></i> Contraseña</label>
                                <input type="password" class="form-control" placeholder="********">
                            </div>
 --}}
                            <!-- Botones -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4">
                                    <i class="fas fa-check"></i> Enviar
                                </button>

                                <button type="reset" class="btn btn-secondary btn-lg px-4 ml-2">
                                    <i class="fas fa-undo"></i> Limpiar
                                </button>
                            </div>

                        </form>

                        <!-- Formulario de código de verificación -->
                        <form id="myFormVerificationCode" style="display: none;">
                            <div class="text-center mb-4">
                                <h5><i class="fas fa-shield-alt"></i> Ingresa tu código de verificación</h5>
                                <p class="text-muted">Te hemos enviado un código de 6 dígitos a tu correo</p>
                            </div>

                            <div class="form-group">
                                <div class="d-flex justify-content-center gap-2" id="codeInputContainer">
                                    <input type="text" class="code-input" maxlength="1" inputmode="numeric"
                                        pattern="[0-9]">
                                    <input type="text" class="code-input" maxlength="1" inputmode="numeric"
                                        pattern="[0-9]">
                                    <input type="text" class="code-input" maxlength="1" inputmode="numeric"
                                        pattern="[0-9]">
                                    <input type="text" class="code-input" maxlength="1" inputmode="numeric"
                                        pattern="[0-9]">
                                    <input type="text" class="code-input" maxlength="1" inputmode="numeric"
                                        pattern="[0-9]">
                                    <input type="text" class="code-input" maxlength="1" inputmode="numeric"
                                        pattern="[0-9]">
                                </div>
                                <input type="hidden" id="fullCode" name="verification_code">
                                <input type="hidden" name="idnumber" id="idnumber">
                                <input type="hidden" name="newemail" id="newemail">
                                <input type="hidden" name="newphone" id="newphone">
                            </div>

                            <div class="text-center mt-5">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-check-circle"></i> Verificar código
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg px-5 ml-2"
                                    id="btnBackToForm">
                                    <i class="fas fa-arrow-left"></i> Atrás
                                </button>
                            </div>

                           {{--  <div class="text-center mt-3">
                                <p class="text-muted">¿No recibiste el código?
                                    <a href="#" id="btnResendCode">Solicitar nuevamente</a>
                                </p>
                            </div> --}}
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module" src={{ asset('js/recovery_account.js?v=' . config('app_config.asset_version')) }}></script>

    <script>
        $(document).ready(function() {
            const codeInputs = document.querySelectorAll('.code-input');
            const fullCodeInput = document.getElementById('fullCode');
            const dataForm = document.getElementById('myFormRecoveryAccount');
            const codeForm = document.getElementById('myFormVerificationCode');
            const btnBackToForm = document.getElementById('btnBackToForm');

            // Manejo de entrada de dígitos
            codeInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    // Solo permitir números
                    if (!/^[0-9]$/.test(e.target.value)) {
                        e.target.value = '';
                        return;
                    }

                    // Agregar clase filled
                    e.target.classList.add('filled');

                    // Mover al siguiente input
                    if (e.target.value && index < codeInputs.length - 1) {
                        codeInputs[index + 1].focus();
                    }

                    // Actualizar el valor oculto
                    updateFullCode();
                });

                // Manejo de borrado (Backspace)
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        input.value = '';
                        input.classList.remove('filled');
                        if (index > 0) {
                            codeInputs[index - 1].focus();
                        }
                        updateFullCode();
                    }
                });

                // Manejo de pegado
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = paste.replace(/\D/g, '').split('');

                    digits.forEach((digit, i) => {
                        if (i < codeInputs.length) {
                            codeInputs[i].value = digit;
                            codeInputs[i].classList.add('filled');
                        }
                    });

                    updateFullCode();
                    if (digits.length > 0) {
                        codeInputs[Math.min(digits.length, codeInputs.length - 1)].focus();
                    }
                });
            });

            // Actualizar código completo
            function updateFullCode() {
                const code = Array.from(codeInputs).map(input => input.value).join('');
                fullCodeInput.value = code;
            }



            // Volver al formulario de datos
            btnBackToForm.addEventListener('click', () => {
                codeForm.style.display = 'none';
                dataForm.style.display = 'block';
                // Limpiar código
                codeInputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('filled');
                });
                updateFullCode();
            });



            // Reenviar código
            document.getElementById('btnResendCode').addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Reenviar código');
                // Aquí agregar lógica para reenviar código
                alert('Se ha enviado un nuevo código a tu correo');
            });

            // Remover opciones NIT y Tarjeta de Identidad del select
            $('select[required]').find('option').each(function() {
                const text = $(this).text().toLowerCase();
                if (text.includes('nit') || text.includes('tarjeta de identidad')) {
                    $(this).remove();
                }
            });
        });
    </script>
@endpush
