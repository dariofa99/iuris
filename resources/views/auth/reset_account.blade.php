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
            0%, 100% {
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
                        <form id="myFormResetAccount">
                            @csrf
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <!-- Tipo de documento y número -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-id-card"></i> Tipo de documento</label>
                                    <select disabled class="form-control" required name="tipodoc_id">
                                        <option value="">-- Selecciona tipo de documento --</option>
                                        @foreach ($tipodoc as $key => $tipo)
                                            <option {{$user->tipodoc_id == $key ? 'selected':''}} value="{{ $key }}">{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-hashtag"></i> Número de documento</label>
                                    <input disabled name="idnumber" type="text" class="form-control"
                                        placeholder="Ingresa tu número de documento" required value="{{$user->idnumber}}">
                                </div>
                            </div>
                            <!-- Nombre y apellido -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-user"></i> Nombre</label>
                                    <input name="name" type="text" class="form-control"
                                        placeholder="Ingresa tu nombre" value="{{$user->name}}" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-user"></i> Apellido</label>
                                    <input name="lastname" type="text" value="{{$user->lastname}}" class="form-control"
                                        placeholder="Ingresa tu apellido" disabled>
                                </div>
                            </div>



                            <!-- Usuario -->
                            {{--  <div class="form-group">
                                <label><i class="fas fa-id-card"></i> Usuario</label>
                                <input type="text" class="form-control" placeholder="Nombre de usuario">
                            </div>
 --}}
                            <!-- Email -->
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input value="{{$user->email}}" name="correo" type="email" class="form-control" placeholder="correo@ejemplo.com" disabled>
                            </div>

                            <!-- Teléfono -->
                            <div class="form-group">
                                <label><i class="fas fa-phone"></i> Teléfono</label>
                                <input name="tel" type="number" value="{{$user->tel1}}" class="form-control" placeholder="Número de teléfono" disabled>
                            </div>

                      <!-- Contraseña -->
                            <div class="form-group">
                                <label><i class="fas fa-lock"></i> Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="********" required>
                            </div>
 
                            <!-- Botones -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4">
                                    <i class="fas fa-check"></i> Enviar
                                </button>

                               
                            </div>

                        </form>

                  

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="module" src={{ asset('js/recovery_account.js?v=' . config('app_config.asset_version')) }}></script>

  
@endpush
