@component('components.b4.modal_large')
    @slot('trigger')
        mymodalListaCitasDia
    @endslot

    @slot('title')
        Citas del día
    @endslot


    @slot('body')
        <div class="row justify-content-center">
            <div class="col-md-12" id="ct_forcitaest">
                <div class="card-body">
                    <!-- Título del evento -->                    
                    <table id="table_list_citas_day" border="1" role="table" style="width: 100%; border-collapse: collapse;">
                        
                        <thead>
                            <tr>
                                <th scope="col">Estudiante - Expediente</th>
                                <th scope="col">Motivo</th>
                                <th scope="col">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
        
    @endslot
@endcomponent
<!-- /modal -->
