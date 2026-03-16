@component('components.b4.modal_medium')
    @slot('trigger')
        myModal_InfoInderminada
    @endslot

    @slot('title')
        !Atención!
    @endslot


    @slot('body')
    
        <!-- Modal -->
        <div>

            <div class="alert alert-info py-2 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Recuerde que en esta opción solo aplica en casos de familia, tales como:
            </div>

            <ul class="list-unstyled small">

                <li class="mb-2">
                    <i class="fas fa-angle-right text-primary mr-2"></i>
                    Fijación de cuota de alimentos
                </li>

                <li class="mb-2">
                    <i class="fas fa-angle-right text-primary mr-2"></i>
                    Cuidado personal
                </li>

                <li class="mb-2">
                    <i class="fas fa-angle-right text-primary mr-2"></i>
                    Custodia
                </li>

                <li class="mb-2">
                    <i class="fas fa-angle-right text-primary mr-2"></i>
                    Régimen de visitas
                </li>

                <li class="mb-2">
                    <i class="fas fa-angle-right text-primary mr-2"></i>
                    Declaración de unión marital de hecho
                </li>

                <li>
                    <i class="fas fa-angle-right text-primary mr-2"></i>
                    Otros asuntos que no se pueden expresar en dinero
                </li>

            </ul>

        </div>
    @endslot
@endcomponent
<!-- /modal -->
