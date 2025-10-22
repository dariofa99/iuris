@component('components.b4.modal_extra_large')
    @slot('trigger')
        mymodalShowAlerts
    @endslot
    @slot('size')
        modal-dialog modal-lg
    @endslot

    @slot('title')
        <h3>Información importante!</h3>
    @endslot
    @push('styles')
        <!-- aqui van los estilos de cada vista -->
      
        <style>
            body {
                background: linear-gradient(135deg, #74ABE2, #5563DE);
                color: white;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: "Poppins", sans-serif;
            }

            .btn-motivar {
                font-size: 1.2rem;
                padding: 12px 24px;
                border-radius: 50px;
                background: #fff;
                color: #5563DE;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-motivar:hover {
                background: #5563DE;
                color: #fff;
                transform: scale(1.05);
            }

            .modal-content {
                background: linear-gradient(145deg, #fff, #f7f9ff);
                border-radius: 1rem;
                text-align: center;
                color: #333;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            }

            .modal-header {
                border: none;
                font-weight: 700;
                font-size: 1.3rem;
                color: #5563DE !important;
            }

            .frase-texto {
                font-size: 1.3rem;
                font-style: italic;
                margin: 20px 0;
                color: #444;
            }

            .autor {
                font-weight: 600;
                color: #5563DE;
            }
        </style>
    @endpush

    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')
        
        <div id='modal-show-alerts-content'>


        </div>
    @endslot

    @slot('footer')
        <div id="contentNotButtonDis">

        </div>
    @endslot
@endcomponent
<!-- /modal -->
