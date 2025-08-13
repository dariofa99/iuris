<script>
    @if (Session::has('message-information') && config('app.name') != 'ConciliApp')
        localStorage.removeItem("keyCircularActualSS");


        var message = getMantenimientoMessage();



        //var message = getMantenimientoMessage();
        $("#modal-show-alerts-content").html(message);
        $("#mymodalShowAlerts").modal("show");
    @endif

    $("#mymodalShowAlerts").on("click", '#btnNotFalse', function(e) {
        var item = $(this).attr("data-not")
        localStorage.setItem(item, true);
        $("#mymodalShowAlerts").modal("hide");
        e.preventDefault();

    })

    function getCarrousel() {
        var carrousel = `<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                          <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                          </ol>
                          <div class="carousel-inner">
                            <div class="carousel-item active">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva1.JPG') }}" alt="First slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva2.JPG') }}" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva3.JPG') }}" alt="Third slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva4.JPG') }}" alt="Third slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva5.JPG') }}" alt="Third slide">
                            </div>                            
                          </div>
                          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                          </a>
                        </div>`;
        $("#contentNotButtonDis").append($("<button>", {
            class: "btn btn-danger",
            id: "btnNotFalse",
            text: "No volver a mostrar",
            "data-not": "keyCirActualizaCierreClose"
        }))

        return carrousel;
    }


    function getCarrouselDocentes() {
        var carrousel = `<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
          <div class="oculto">
        Teniendo en cuenta la necesidad de brindar alternativas que permitan la gestión de casos de asesoría vencidos que se evalúan por el sistema (Cerrado - sistema) ahora IURIS permite a los docentes, director general y director administrativo volver a Evaluar y Cerrar estos casos teniendo en cuenta los siguientes parámetros.
        Si el caso está Cerrado - sistema y NO fue enviado a solicitud de cierre podrá asignar una nota de 0 a 3. 
        Si el caso está Cerrado - sistema y fue enviado a solicitud de cierre podrá asignar una nota de 0 a 5. 

        Tenga en cuenta que la nueva nota se asignará al corte en el cual se venció el caso.

        Para Evaluar y cerrar caso en administración del caso, pestaña Cierre de caso de clic en el botón Volver a evaluar y cerrar caso

          </div>                
          
          <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>

                          </ol>
                          <div class="carousel-inner">
                            <div class="carousel-item active">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva1.JPG') }}" alt="First slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva2.JPG') }}" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva3.JPG') }}" alt="Third slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva4.JPG') }}" alt="Third slide">
                            </div>                         
                          </div>
                          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span style="background-color:black" class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                          </a>
                          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span style="background-color:black" class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                          </a>
                        </div>`;
        $("#contentNotButtonDis").append($("<button>", {
            class: "btn btn-danger",
            id: "btnNotFalse",
            text: "No volver a mostrar",
            "data-not": "keyCircCierreCaso"
        }))

        return carrousel;
    }

    function getGeneralMessage() {
        var message = '';
        message += '<div class="alert alert-danger" style="font-size:19px">';
        message += `<h4>
                      <strong style="border-bottom:1px solid white">
                        Bienvendido a {{ Str::upper(config('app.name')) }}!</strong> <br>
                        Recuerde que estamos actualizando la plataforma, si se presenta algún problema refresque el navegador
                        con las teclas CTRL+F5 <i>o</i> CTRL+fn+F5 (portátiles). Tener en cuenta para conexión desde dispositivos móviles. <br>
                      
                    </h4> </div>`;
        message += `<span> Últ. Actualización: 11 de agosto de 2025 <br>
                        Si el problema persiste comuníquese al 314-7404937 - 310-6038006  
                      </span>`;

        return message;
    }

    function getMantenimientoMessage() {
        var message = '';
        message += '<div class="" style="font-size:19px">';
        message += `<h1>
                    <strong >
                        Atención!<br>
                        Instrucción para la reasignación de casos. <br></h1>
                        Estimados estudiantes recuerden que, en caso de que un asunto o caso le sea reasignado, es su deber y obligación actualizar la información registrada en los campos de “Datos del caso”, Hechos y Respuesta de estudiante.
                        Para ello, deberá contactar a la persona usuaria con el fin de verificar, confirmar o complementar la información previamente redactada por el/la estudiante anterior.
Esta actualización debe reflejar, con sus propias palabras, los hechos y la respuesta, asegurando que el registro sea claro, preciso y fidedigno a lo manifestado por la persona usuaria.
                    </strong>
                    <br>
                          <br>
                      
                     </div>`;
        message += `<hr>`;
        message += `<span> Últ. Actualización: 12 de agosto de 2025 <br>
                         
                      </span>`;

        return message;
    }

    function getCircular() {
        var keyCir = localStorage.getItem("keyCircularActualCortes");
        var message = '';
        if (keyCir == null) {
            message = `<embed  src="{{ asset('recursos/CircularActualPausas.pdf#toolbar=0') }}" id="pdfViewer" >`
            message +=
                `<button class="btn btn-success" data-not="keyCircularActualCortes" id="btnNotFalse" sandbox >No volver a mostrar!</button>`
        }
        return message;
    }
</script>
