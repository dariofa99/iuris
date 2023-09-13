@extends('layouts.dashboard')

@push('styles')
    <!-- aqui van los estilos de cada vista -->
@endpush

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('area_forms')
    <div class="row">
        <div class="col-md-12">
            <h3>
                Hola! {{ currentUser()->name }} {{ currentUser()->lastname }}
            </h3>

            <h4>
                <p>
                    Bienvenido a <strong>{{ config('app.name') }}
                    </strong>!!
                </p>
            </h4>
        </div>
    </div> 
    @if (
        (isset($sedes) and count($sedes) >= 2 and count(Auth::user()->sedes) <= 0) ||
            auth()->user()->can('cambiar_sede'))
        <div class="row">
            @foreach ($sedes as $key => $sede)
                <div class="col-md-4">
                    <form id="myFormCambiarSede-{{ $sede->id_sede }}" action="{{ url('/change/sedes') }}" method="GET">
                        <div class="card card-outline card-success">
                            <!-- Default panel contents -->
                            <div class="card-body">
                                <input type="hidden" name="sede_id" value="{{ $sede->id_sede }}">
                                <h6>{{ $sede->ubicacion }}</h6>
                            </div>
                            <div class="card-footer">
                                <button data-id="{{ $sede->id_sede }}"
                                    {{ (session()->has('sede') and session()->get('sede')->id_sede == $sede->id_sede) ? 'disabled' : '' }}
                                    type="button" class="btn btn-success btn_change_sede">
                                    
                                    {{ (session()->has('sede') and session()->get('sede')->id_sede == $sede->id_sede) ? 'Seleccionada' : 'Seleccionar' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    <!-- aqui van los scripts de cada vista -->
    <script>
        $(document).ready(function() {
            $(".btn_change_sede").on("click", function(e) {
                var id = $(this).attr("data-id");
                Swal.fire({
                    title: "Esta seguro de seleccionar esta sede?",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, seleccionar!",
                    cancelButtonText: "No, cancelar",
                }).then((result) => {
                    if (!result.value) {
                        e.preventDefault();
                        return false;
                    } else {
                        $("#myFormCambiarSede-" + id).submit();
                    }
                });
            });
        })
    </script>
@endpush
