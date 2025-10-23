<script>
    @if (Session::has('message-information') && config('app.name') != 'ConciliApp')
        localStorage.removeItem("keyCircularActualPausas");

        var keyCir = localStorage.getItem("keyCircularActualTurnos");
         $("#modal_t").text("Información importante!");
        var message = '';
        var message = getMotivationalMessage();
        if (keyCir == null) {
            message = getCircular();
        }else{
           $("#modal_t").text("");
            message = getMotivationalMessage();
        }

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

    function getCarrousel(start, end) {

        var carrousel = `<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                          <ol class="carousel-indicators">`;

        for (let i = start; i < end; i++) {
            if (i == start) {
                carrousel += `<li data-target="#carouselExampleIndicators"  data-slide-to="` + i +
                    `" class="active "></li>`;
            } else {
                carrousel += `<li style="background-color: black;" data-target="#carouselExampleIndicators" data-slide-to="` + i + `"></li>`;
            }
        }

        carrousel += `                   
                          </ol>
                          <div class="carousel-inner"> `;
        for (let i = start; i < end; i++) {
            if (i == start) {
                carrousel +=
                    `<div class="carousel-item active">
                                      <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva`+i+`.JPG') }}" alt="Slide ` +
                    i + `">
                    </div>
                             `       ;
            } else {
                carrousel +=
                    `<div class="carousel-item">
                                      <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva`+i+`.JPG') }}" alt="Slide ` +
                    i + `"></div>`

            }
        }

        carrousel += `
                                                     
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
            "data-not": "keyCircularActualTurnos"
        }))

        return carrousel;
    }

    function MostrarGuia() {
        // $("#mymodalShowAlerts").modal("hide");
        @if(currentUser()->hasRole('estudiante') or currentUser()->hasRole('amatai'))
          var message = getCarrousel(1,13);
          @else
          var message = getCarrousel(13,28); 
        @endif
        //var message = getCarrousel();
        //var message = getMantenimientoMessage();
        $("#modal-show-alerts-content").html(message);
        $("#mymodalShowAlerts").modal("show");

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
                          <a class="btn btn-success carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span style="background-color:black" class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                          </a>
                        </div>`;
        $("#contentNotButtonDis").append($("<button>", {
            class: "btn btn-danger",
            id: "btnNotFalse",
            text: "No volver a mostrar",
            "data-not": "keyCircularActualTurnos"
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

    function getMotivationalMessage() {

        const frases = [{
                texto: "La única manera de hacer un gran trabajo es amar lo que haces.",
                autor: "Steve Jobs"
            },
            {
                texto: "Confía en tu potencial. ¡Puedes hacer mucho más de lo que piensas!",
                autor: ""
            },
            {
                texto: "Confía en tu esfuerzo, estás abriendo puertas que aún no ves.",
                autor: ""
            },
            {
                texto: "El éxito es la suma de pequeños esfuerzos repetidos día tras día.",
                autor: ""
            },
            {
                texto: "No te compares con los demás, eres único y valioso.",
                autor: ""
            },
            {
                texto: "No importa que vayas despacio, lo importante es que no te detengas.",
                autor: ""
            },
            {
                texto: "A la cima no se llega superando a los demás, sino superándote a ti mismo.",
                autor: ""
            },
            {
                texto: "La educación es el arma más poderosa que puedes usar para cambiar el mundo.",
                autor: "Nelson Mandela"
            },
            {
                texto: "Hoy es un gran día para empezar algo nuevo. ¡Confía en ti y da el primer paso!",
                autor: ""
            },
            {
                texto: "Eres capaz de lograr cosas increíbles. Confía en el proceso y sigue adelante.",
                autor: ""
            },
            {
                texto: "El fracaso no es el fin, sino la señal de que estás intentando algo grandioso.",
                autor: ""
            },
            {
                texto: "El esfuerzo puede ser invisible, pero los resultados siempre brillan.",
                autor: ""
            }
        ];




        const randomIndex = Math.floor(Math.random() * frases.length);
        const frase = frases[randomIndex];



        var message = '';
        message += `
  <div style="
    background: linear-gradient(135deg, #ffffff, #f5f7ff);
    border-radius: 20px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    font-family: 'Poppins', sans-serif;
    color: #333;
    transition: all 0.3s ease;
    animation: fadeIn 0.6s ease;
  ">
    <p id="textoFrase" style="
      font-size: 1.4rem;
      font-style: italic;
      line-height: 1.6;
      color: #444;
      margin-bottom: 15px;
    ">
      “${frase.texto}”
    </p>
    <p id="autorFrase" style="
      font-weight: 600;
      color: #5563DE;
      font-size: 1rem;
      margin-bottom: 0;
    ">
      ${frase.autor ? `– ${frase.autor}` : ''}
    </p>
    <hr style="
      border: none;
      height: 2px;
      background: linear-gradient(90deg, #5563DE, #74ABE2);
      margin: 20px 0;
    ">
    <span style="
      font-size: 0.9rem;
      color: #777;
      display: block;
    ">
      🕒 Últ. actualización: <b>22 de octubre de 2025</b><br>
      Soporte: 314-7404937 - 310-6038006, darioj99@udenar.edu.co
    </span>
  </div>
  <style>
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
`;
        return message;
    }

    function getCircular() {
        var keyCir = localStorage.getItem("keyCircularActualTurnos");
        var message = '';
        if (keyCir == null) {
            message = `<embed  src="{{ asset('recursos/CircularActualTurnos.pdf#toolbar=0') }}" id="pdfViewer" >`
            /* message +=
                `<button class="btn btn-success" data-not="keyCircularActualTurnos" id="btnNotFalse" sandbox >No volver a mostrar!</button>` */
            message +=
                `<button class="btn btn-info" onclick='MostrarGuia()'  id="btnMostrarGuia" >Ver guia de usuario</button>`
        }
        return message;
    }
</script>
