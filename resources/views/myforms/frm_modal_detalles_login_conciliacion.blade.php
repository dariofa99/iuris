@component('components.b4.modal_large')

    @slot('trigger')
        myModal_detalles_login_conc
    @endslot

    @slot('title')
        <h5>
            Antes de empezar
        </h5>
    @endslot


    @slot('body')
        @section('msg-contenido')
            Registrado
        @endsection
        @include('msg.ajax.success')


        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="servicios-tab" data-toggle="tab" href="#servicios_tab" role="tab"
                            aria-controls="servicios_tab" aria-selected="false">
                            Servicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tramites-tab" data-toggle="tab" href="#tramites_tab" role="tab"
                            aria-controls="tramites_tab" aria-selected="false">
                            Tramite audiencia de conciliación
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent" style="margin-top: 10px !important">

                    <div class="tab-pane fade show active" id="servicios_tab" role="tabpanel" aria-labelledby="servicios-tab">
                        <p style="text-align: justify">POBLACION OBJETO – La población objeto del Centro
                            de Conciliación “Eduardo Alvarado Hurtado” a
                            quien presta sus servicios, corresponde a la
                            población del Departamento de Nariño y en
                            especial a la ciudadanía de Pasto de estratos
                            económicos 1,2, 3 y personas que se encuentren
                            en situación económica precaria y población
                            vulnerable, como personas en situación de
                            desplazamiento forzado, madres comunitarias
                            activas, discapacitados, padres o madres
                            cabeza de familia, adultos mayores y miembros
                            de minorías étnicas.
                        </p>

                        <p>
                            El Centro de Conciliación “Eduardo Alvarado Hurtado” ofrece los siguientes servicios:
                        </p>
                        <ul>
                            <li>
                                Conciliación totalmente gratuita, y de
                                atención preferente a población de escasos recursos.
                            </li>
                            <li>
                                Solución alternativa de conflictos en las áreas: civil, comercial, familia, y en
                                general en cualquier asunto susceptible de transacción o desistimiento.
                            </li>
                        </ul>
                    </div>

                    <div class="tab-pane fade show" id="tramites_tab" role="tabpanel" aria-labelledby="tramites-tab">
                        <p style="text-align: justify">
                            Se verificarán las circunstancias del asunto y si es admisible, por lo cual se estudiará si el
                            asunto es conciliable o no, conforme las competencias radicadas en el centro de conciliación.
                            <br>
                            Una vez verificada la solicitud y admitida por el Centro de Conciliación, se realizan las citaciones
                            a las partes, con la indicación de la fecha, hora y medio por el cual se desarrollará la audiencia
                            de conciliación.
                            <br>
                            Para tal efecto, se designará un estudiante adscrito al Centro de Conciliación.
							<br>
						
                        </p>
						<strong>Recuerde: Para realizar la solicitud por este medio
							deberá contar con el número de documento de identificacíon
							de la persona convocada a la conciliación. Si el convocado es
							 persona jurídica se requiere documento de certificado de existencia
							  y representación legal en formato pdf de no más de 3 meses de antiguedad.
						</strong><br>
						<input type="checkbox" name="valida_regla" id="valida_regla">
						<label for="valida_regla" style="font-weight: normal">Comprendo los terminos y condiciones</label><br>
						<button id="btn_continuar_conciliacion" disabled  class="btn btn-primary m-2">
							Continuar
						</button> 
                       
                    </div>

                </div>

				

            </div>
        </div>
    @endslot
@endcomponent
<!-- /modal -->
