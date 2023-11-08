@component('components.b4.modal_medium')
    @slot('trigger')
        mymodalPausarExpediente
    @endslot

    @slot('title')
        Pausar expediente
    @endslot


    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')

        <div class="row">
            <div class="col-md-12">
                <form id="myformPausarExpediente">
                    <input type="hidden" name="id" id="autorizacion_id">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="nombre_estudiante">Pausar desde:</label>
                            <input type="date" required class="form-control required form-control-sm" id="nombre_estudiante"
                                name="fecha_inicial" readonly value="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nombre_estudiante">Hasta:</label>
                            <input type="date" min="{{date('Y-m-d')}}" required class="form-control required form-control-sm" id="nombre_estudiante"
                                name="fecha_final">
                         </div>                      

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">Crear</button>
                        </div>


                    </div>

                </form>
            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
