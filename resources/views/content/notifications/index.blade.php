@extends('layouts.dashboard')


@push('styles')
    <!-- aqui van los estilos de cada vista -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css"
        integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />

    <style>
        body {
            margin-top: 20px;
            background-color: #f0f2f5;
        }

        .dropdown-list-image {
            position: relative;
            height: 2.5rem;
            width: 2.5rem;
        }

        .dropdown-list-image img {
            height: 2.5rem;
            width: 2.5rem;
        }

        .btn-light {
            color: #2cdd9b;
            background-color: #e5f7f0;
            border-color: #d8f7eb;
        }
    </style>
@endpush

@section('navbar')
    <!-- aqui va el menu de cada vista -->
    @include('content.navbar')
@endsection

@section('titulo_area')
   
@endsection


@section('area_buttons')

@endsection


@section('area_forms')
    @include('msg.alerts')
    {{--  <div class="row">
        <div class="col-md-12">
            <div class="p-0" id="content-notifications">

                @include('content.notifications.partials.ajax.index')

            </div>
        </div>
    </div> --}}
    <div class="container-fluid notification-page">

        <div class="notification-wrapper">

            <div class="notification-topbar">
                <h4 class="mb-0">🔔 Centro de notificaciones</h4>

                <div class="notification-actions">
                    {{-- <button class="btn btn-sm btn-outline-primary" id="markAllRead">
                        Marcar todas como leídas
                    </button> --}}
                </div>
            </div>

            <div id="content-notifications" class="notification-container">
                @include('content.notifications.partials.ajax.index')
            </div>

        </div>

    </div>

@stop
@push('scripts')
    <!-- aqui van los scripts de cada vista -->
@endpush
