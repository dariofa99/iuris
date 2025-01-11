@component('components.b4.modal_medium')
    @slot('trigger')
        mymodalAgendaCitacion
    @endslot

    @slot('title')
        Citación a estudiante
    @endslot


    @slot('body')
        <div class="row justify-content-center">
            <div class="col-md-12" id="ct_forcitaest">
                <div class="card-body">
                    <!-- Título del evento -->                    
                    <h5 class="event-title mb-3" id="title">Juan Pérez - EXP001</h5>

                    <label>Motivo</label>
                    <p class="event-motivo mb-2" id="motivo">Motivo: Reunión con padres</p>

                    <label>Fecha</label>
                    <p class="event-date" id="fecha_larga">Fecha: 30 de noviembre de 2024, 10:00 AM</p>

                    <!-- Botón opcional -->
                    
                </div>
            </div>
        </div>
        
    @endslot
@endcomponent
<!-- /modal -->
