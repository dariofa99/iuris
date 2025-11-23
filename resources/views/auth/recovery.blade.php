@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0"><i class="fas fa-user-edit"></i> Registro de Usuario</h4>
                    </div>

                    <div class="card-body p-4">
                        <form>

                            <!-- Nombre y apellido -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-user"></i> Nombre</label>
                                    <input type="text" class="form-control" placeholder="Ingresa tu nombre">
                                </div>
                                <div class="form-group col-md-6">
                                    <label><i class="fas fa-user"></i> Apellido</label>
                                    <input type="text" class="form-control" placeholder="Ingresa tu apellido">
                                </div>
                            </div>

                            <!-- Usuario -->
                            <div class="form-group">
                                <label><i class="fas fa-id-card"></i> Usuario</label>
                                <input type="text" class="form-control" placeholder="Nombre de usuario">
                            </div>

                            <!-- Email -->
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" class="form-control" placeholder="correo@ejemplo.com">
                            </div>

                            <!-- Teléfono -->
                            <div class="form-group">
                                <label><i class="fas fa-phone"></i> Teléfono</label>
                                <input type="number" class="form-control" placeholder="Número de teléfono">
                            </div>

                            <!-- Contraseña -->
                            <div class="form-group">
                                <label><i class="fas fa-lock"></i> Contraseña</label>
                                <input type="password" class="form-control" placeholder="********">
                            </div>

                            <!-- Botones -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg px-4">
                                    <i class="fas fa-check"></i> Registrar
                                </button>

                                <button type="reset" class="btn btn-secondary btn-lg px-4 ml-2">
                                    <i class="fas fa-undo"></i> Limpiar
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
    <script>
        /* $(document).ready(function() {
               
                $("#myFormResetPassword").on("submit", function(e) {
                   // e.preventDefault();
                    $("#wait").show()
                });
             
            }); */
    </script>
@endpush
