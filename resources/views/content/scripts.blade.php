<script>
    @if (Session::has('message-information') && config('app.name') != 'ConciliApp')
        localStorage.removeItem("keyCircularActualVacNavidad");

        var keyCir = localStorage.getItem("keyCircularActualVacNavidad");
        $("#modal_t").text("");
        var message = '';
        var message = getMotivationalMessage();
        if (keyCir == null) {
            message = getMotivationalMessage();
        } else {
            $("#modal_t").text("");
            // message = getHtmlCircular();
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

    $("#mymodalShowAlerts").on('shown.bs.modal', function() {
        $("#modal-announce").text("Información importante. Modal abierto con instrucciones sobre incidencias.");
        $("#modalContentStart").focus();
    });


    function getCarrousel(start, end) {
        const d = new Date("2021-03-25");
        d.getFullYear();
        console.log(d.getFullYear());


        var carrousel = `<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                          <ol class="carousel-indicators">`;

        for (let i = start; i < end; i++) {
            if (i == start) {
                carrousel += `<li data-target="#carouselExampleIndicators"  data-slide-to="` + i +
                    `" class="active "></li>`;
            } else {
                carrousel += `<li  data-target="#carouselExampleIndicators" data-slide-to="` + i + `"></li>`;
            }
        }

        carrousel += `                   
                          </ol>
                          <div class="carousel-inner"> `;
        for (let i = start; i < end; i++) {
            if (i == start) {
                carrousel +=
                    `<div class="carousel-item active">
                                      <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva`+i+`.JPG?v=${d.getTime()}') }}" alt="Slide ` +
                    i + `">
                    </div>
                             `;
            } else {
                carrousel +=
                    `<div class="carousel-item">
                                      <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva`+i+`.JPG?v=${d.getTime()}') }}" alt="Slide ` +
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
        @if (currentUser()->hasRole('estudiante') or currentUser()->hasRole('amatai'))
            var message = getCarrousel(1, 13);
        @else
            var message = getCarrousel(13, 28);
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
        $("#contentNotButtonDis").append($("<button>", {
            class: "btn btn-danger",
            id: "btnNotFalse",
            text: "No volver a mostrar",
            "data-not": "keyCircularActualVacNavidad"
        }));
        return `
<div class="container-fluid" style="font-size:18px;">

    <!-- HEADER NAVIDEÑO -->
    <div class="text-center mb-4">
        <h2 class="font-weight-bold" style="color:#c0392b;">
           <i class="fas fa-bell"></i> Atención

        </h2>
        <p class="text-muted">Actualización para el periodo de vacaciones</p>
    </div>

    <!-- CARD INFO -->
    <div class="card shadow-sm mb-4" style="border-left:6px solid #c0392b;">
        <div class="card-body">

            <p class="mb-3">
                Estimados estudiantes, recuerden que durante el periodo de vacaciones,
                <strong>el sistema pone en pausa los días en los cuales se aplican notas de cero</strong>,
                <span class="text-danger font-weight-bold">a excepción de las actuaciones con fecha límite puesta por el docente.</span>
            </p>

           
        </div>
    </div>

    <!-- MENSAJE NAVIDEÑO -->
    <div class="text-center py-4 px-3" 
         style="background:#f9f2eb;border-radius:12px; border:1px solid #f0e6dc;">
        <h4 style="color:#8e2b2b;" class="font-weight-bold mb-2">
            🎄 🎄 <i class="fas fa-tree"></i> 🎄  🎄
        </h4>
        <p class="mb-2">
            Desde la administración de <strong>IURIS</strong>, 
            les deseamos unas felices fiestas llenas de paz, armonía, alegría y muchos éxitos.
        </p>
      


    </div>

    <!-- FOOTER -->
    <div class="text-center mt-4 text-muted" style="font-size:14px;">
        <i class="fas fa-calendar-alt"></i>
        Última actualización: <strong>2 de diciembre de 2025</strong>
    </div>

</div>
`;
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
                texto: "El éxito no llega de la noche a la mañana, se construye con el esfuerzo de cada día.",
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
            },
            {
                texto: "Cada día es una nueva oportunidad para aprender y crecer.",
                autor: ""
            },
            {
                texto: "La perseverancia es la clave del éxito. No te rindas.",
                autor: ""
            },
            {
                texto: "Cada tarea hecha es un paso más hacia tu meta.",
                autor: ""
            },
            {
                texto: "El éxito no llega de la noche a la mañana, se construye con el esfuerzo de cada día.",
                autor: ""
            },
            {
                texto: "Cada tarea hecha es un paso más hacia tu meta.",
                autor: ""
            },
            {
                texto: "No te rindas: lo que hoy cuesta, mañana será parte de tu fortaleza.",
                autor: ""
            },
            {
                texto: "Equivocarse también es aprender; lo importante es no dejar de intentarlo.",
                autor: ""
            },
            {
                texto: "Cree en ti, incluso cuando el camino parezca difícil.",
                autor: ""
            },
            {
                texto: "Tu mayor competencia eres tú mismo: sé mejor que ayer.",
                autor: ""
            },
            {
                texto: "No estudias solo para pasar una materia, estudias para construir tu futuro.",
                autor: ""
            },
            {
                texto: "Los sueños se cumplen cuando decides trabajar por ellos.",
                autor: ""
            },
            {
                texto: "Cada hora de estudio es una inversión en la persona que quieres ser.",
                autor: ""
            },
            {
                texto: "Hoy estudiante, mañana profesional: todo empieza con disciplina.",
                autor: ""
            },
            {
                texto: "Sí se puede, paso a paso.",
                autor: ""
            },
            {
                texto: "Aprender es crecer.",
                autor: ""
            },
            {
                texto: "El éxito es la suma de pequeños esfuerzos repetidos día tras día.",
                autor: "Robert Collier"
            },
            {
                texto: "Cada logro comienza con la decisión de intentarlo.",
                autor: "John F. Kennedy"
            },
            {
                texto: "Nuestra mayor gloria no está en no caer nunca, sino en levantarnos cada vez que caemos.",
                autor: "Confucio"
            },
            {
                texto: "La educación es el arma más poderosa que puedes usar para cambiar el mundo.",
                autor: "Nelson Mandela"
            },
            {
                texto: "Cree que puedes y ya estás a medio camino.",
                autor: "Theodore Roosevelt"
            },
            {
                texto: "El aprendizaje nunca agota la mente.",
                autor: "Leonardo da Vinci"
            },
            {
                texto: "No fracases por falta de intentarlo.",
                autor: "Benjamin Franklin"
            },
            {
                texto: "El futuro pertenece a quienes creen en la belleza de sus sueños.",
                autor: "Eleanor Roosevelt"
            },
            {
                texto: "El éxito no es definitivo, el fracaso no es fatal: lo que cuenta es el valor para continuar.",
                autor: "Winston Churchill"
            },
            {
                texto: "Dime y lo olvido, enséñame y lo recuerdo, involúcrame y lo aprendo.",
                autor: "Benjamin Franklin"
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
      🕒 Últ. actualización: <b>23 de enero de 2026</b><br>
      Soporte: Registre sus incidencias <a href="/incidencias" target="_blank"
        style="color: #5563DE; text-decoration: underline;">Aquí!</a>
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

    function getHtmlCircular() {
        $("#contentNotButtonDis").append($("<button>", {
            class: "btn btn-danger",
            id: "btnNotFalse",
            text: "No volver a mostrar",
            "data-not": "keyCircularActualIncidenciasU"
        }));

        return `
<div class="container-fluid">

      <!-- Punto de enfoque para narrador -->
    <div id="modalContentStart" tabindex="-1"></div>

    <!-- Área invisible para narración automática -->
    <div id="modal-announce" class="sr-only" aria-live="assertive" aria-atomic="true">
        Información importante sobre la gestión de incidencias en la plataforma IURIS.
    </div>

    <div class="text-center mb-4">
        <h4 class="font-weight-bold">
            <i class="fas fa-exclamation-circle text-primary"></i>
            Información importante sobre la gestión de incidencias
        </h4>
        <p class="text-muted">Plataforma IURIS</p>
    </div>

    <div class="alert alert-primary" role="alert" style="font-size: 15px;">
        <i class="fas fa-info-circle"></i>
        Estimados(as) administrativos, docentes y estudiantes:
        Con el propósito de mejorar la claridad en las solicitudes de incidencias y fortalecer la trazabilidad,
        se ha implementado una nueva función en la plataforma <strong>IURIS</strong>.
    </div>

    <p class="text-justify">
        Esta actualización permite centralizar y organizar solicitudes como eliminación o modificación de notas,
        cambios de docente, reapertura de casos, ajustes administrativos, entre otros.<br>
       <b>Para garantizar exactitud y evitar errores, las solicitudes para eliminar notas requieren información detallada.</b>
    </p>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-2">
            <i class="fas fa-edit"></i>
            Ejemplos de solicitud correcta
        </div>
        <div class="card-body">
            <ul>
                <li class="mb-2">
                    “Eliminar nota de cero relacionada con la actuación "Demanda X".
                    aplicada el <strong>15 de septiembre de 2025</strong> por vencimiento del plazo.
                    ”

                </li>

                <li class="mb-2">
                    “Eliminar notas de cero
                     correspondientes al periodo
                    <strong>10 de septiembre → 11 de noviembre de 2025</strong>.”
                </li>

                             

            </ul>

            <small class="text-secondary">
                Ser explícito evita duplicidades, malos entendidos y solicitudes no autorizadas.
            </small>
        </div>
    </div>

    <h5 class="font-weight-bold mb-3">
        <i class="fas fa-map-marker-alt text-danger"></i>
        ¿Dónde encontrar la nueva función?
    </h5>

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h6 class="font-weight-bold">
                <i class="fas fa-folder-open text-info"></i>
                1. Administración de Expedientes
            </h6>
            <h5 class="mb-2 text-justify mt-2">
                 <i class="fas fa-paper-plane text-info"></i>
                 <b>PASO UNO:</b>
                Encontrará un botón para reportar incidencias directamente desde la gestión de expedientes.
            </h5>
            <div class="text-center">
                <img src="{{ asset('dist/img/update/Diapositiva1.JPG') }}"
                     class="img-fluid rounded shadow" style="max-height: 400px;"
                     alt="Ubicación del botón en expedientes">
            </div>
             <h5 class="mb-2 text-justify mt-2">
                <i class="fas fa-paper-plane text-info"></i>
                <b>PASO DOS:</b> Pestaña “Reporte” — Reporte de incidencia
            </h5>

            <p class="text-justify">
                Esta pestaña está destinada a la creación de una nueva solicitud de incidencia.
                Aquí usted podrá realizar el reporte incluyendo todos los detalles importantes.
            </p>

            <p class="font-weight-bold mb-1">Opciones disponibles:</p>
            <ul>
                <li>Eliminación o modificación de nota</li>
                <li>Cambio de docente</li>
                <li>Reapertura de caso</li>
                <li>Ajustes en turnos</li>
                <li>Otros incidentes administrativos</li>
            </ul>

            <p class="text-justify">
                Luego deberá redactar el motivo explicando claramente la situación.
                Se recomienda incluir:
            </p>

            <ul>
                <li>Código del expediente</li>
                <li>Tipo de nota o actuación</li>
                <li>Fechas o periodos involucrados</li>
                <li>Detalles que eviten confusiones o rechazos</li>
            </ul>

            
            <p class="text-justify">
                Una vez enviada la solicitud, esta será revisada por el administrador y podrá recibir el estado:
                <strong>Aprobada</strong>, <strong>Rechazada</strong> o <strong>Pendiente de revisión</strong>.
            </p>

                <h5 class="mb-2 text-justify mt-2">
                <i class="fas fa-paper-plane text-info"></i>
                <b>PASO TRES:</b> “Mis solicitudes” — Seguimiento y gestión
            </h5>

            <p class="text-justify">
                Esta pestaña muestra todas las incidencias enviadas y su evolución.
                Su diseño permite un seguimiento claro, accesible y ordenado.
            </p>

            <p class="font-weight-bold mb-1">Aquí podrá:</p>

            <ul>
                <li>Consultar el estado de cada solicitud:
                    <strong>En revisión, Aprobada o Rechazada</strong>.</li>
                <li>Leer los comentarios del administrador.</li>
                <li>Editar una solicitud que aún esté en revisión.</li>
                <li>Volver a solicitar revisión si fue rechazada y existe nueva información.</li>
            </ul>

            <p class="text-justify mb-0">
                Este flujo permite que cada incidencia tenga un registro histórico completo,
                facilitando la trazabilidad, la transparencia y el correcto manejo del expediente.
            </p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h6 class="font-weight-bold">
                <i class="fas fa-list-alt text-success"></i>
                2. Menú lateral → Incidencias
            </h6>
            <p class="mb-2 text-justify">
                Para incidencias que no se relacionan con un caso, como turnos o cuentas, encontrará el enlace en el menú lateral, opción "Incidencias", enlace "Solicitar atención".
                Siga las instrucciones mencionadas en los pasos anteriores.
                <br>
            </p>
            <div class="text-center">
                <img src="{{ asset('dist/img/update/Diapositiva2.JPG') }}"
                     class="img-fluid rounded shadow" style="max-height: 400px;"
                     alt="Ubicación del botón incidencias en menú lateral">
            </div>
        </div>
    </div>

    <div class="alert mt-4" role="alert">
        <i class="fas fa-check-circle"></i>
        Agradecemos su colaboración.
    </div>

</div>
    `;

    }

    function getHtmlCircularD() {
        return `
<div class="container-fluid">

    <div class="text-center mb-4">
        <h4 class="font-weight-bold">
            <i class="fas fa-exclamation-circle text-primary"></i>
            Información importante sobre la gestión de incidencias
        </h4>
        <p class="text-muted">Plataforma IURIS</p>
    </div>

    <div class="alert alert-primary" role="alert" style="font-size: 15px;">
        <i class="fas fa-info-circle"></i>
        Estimados(as) administrativos, docentes y estudiantes:
        Con el propósito de mejorar la claridad en las solicitudes de incidencias y fortalecer la trazabilidad,
        se ha implementado una nueva función en la plataforma <strong>IURIS</strong>.
    </div>

    <p class="text-justify">
        Esta actualización permite centralizar y organizar solicitudes como eliminación o modificación de notas,
        cambios de docente, reapertura de casos, ajustes administrativos, entre otros.
        Para garantizar exactitud y evitar errores, algunas solicitudes requieren información detallada.
    </p>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white py-2">
            <i class="fas fa-edit"></i>
            Ejemplos de solicitud correcta
        </div>
        <div class="card-body">

            <p class="text-muted mb-2">
                Cuando solicite <strong>eliminación de notas con calificación cero</strong>, el mensaje debe incluir datos explícitos:
            </p>

            <ul>
                <li class="mb-2">
                    “Eliminar nota de cero en el expediente
                    <strong>2025B-001</strong> relacionada con la actuación <em>‘Demanda X’</em>.”
                </li>

                <li class="mb-2">
                    “Eliminar nota de cero en el expediente
                    <strong>2025B-001</strong> correspondiente al periodo
                    <strong>10 de septiembre → 11 de noviembre de 2025</strong>.”
                </li>
            </ul>

            <small class="text-secondary">
                Ser explícito evita duplicidades, malos entendidos y solicitudes no autorizadas.
            </small>
        </div>
    </div>


    <!-- ⭐⭐⭐ BLOQUE NUEVO AÑADIDO AQUÍ ⭐⭐⭐ -->

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white py-2">
            <i class="fas fa-bell"></i>
            Información sobre las pestañas de gestión de incidencias
        </div>

        <div class="card-body">

            <!-- 🔵 SECCIÓN 1 — NOTIFICAR -->
            <h5 class="font-weight-bold mb-3">
                <i class="fas fa-paper-plane text-info"></i>
                1. Pestaña “Notificar” — Reporte de incidencia
            </h5>

            <p class="text-justify">
                Esta pestaña está destinada a la creación de una nueva solicitud de incidencia.
                Aquí usted podrá realizar el reporte incluyendo todos los detalles importantes.
            </p>

            <p class="font-weight-bold mb-1">Opciones disponibles:</p>
            <ul>
                <li>Eliminación o modificación de nota</li>
                <li>Cambio de docente</li>
                <li>Reapertura de caso</li>
                <li>Ajustes en turnos</li>
                <li>Otros incidentes administrativos</li>
            </ul>

            <p class="text-justify">
                Luego deberá redactar el motivo explicando claramente la situación.
                Se recomienda incluir:
            </p>

            <ul>
                <li>Código del expediente</li>
                <li>Tipo de nota o actuación</li>
                <li>Fechas o periodos involucrados</li>
                <li>Detalles que eviten confusiones o rechazos</li>
            </ul>

            <p class="font-italic text-secondary">
                Ejemplos sugeridos para usuarios con lector de pantalla:
            </p>

            <div class="bg-light p-3 rounded mb-3" style="border-left: 4px solid #007bff;">
                <p class="mb-2">
                    “Solicito eliminar la nota de cero registrada en el expediente
                    <strong>2025B-001</strong> correspondiente a la actuación ‘Demanda X’.”
                </p>

                <p class="mb-0">
                    “Solicito eliminar la nota de cero relacionada con el periodo del
                    <strong>10 de septiembre</strong> al <strong>11 de noviembre de 2025</strong>.”
                </p>
            </div>

            <p class="text-justify">
                Una vez enviada la solicitud, esta será revisada por el administrador y podrá recibir el estado:
                <strong>Aprobada</strong>, <strong>Rechazada</strong> o <strong>Pendiente de revisión</strong>.
            </p>

            <hr>

            <!-- 🟢 SECCIÓN 2 — HISTORIAL -->
            <h5 class="font-weight-bold mb-3">
                <i class="fas fa-history text-success"></i>
                2. Pestaña “Historial” — Seguimiento y gestión
            </h5>

            <p class="text-justify">
                Esta pestaña muestra todas las incidencias enviadas y su evolución.
                Su diseño permite un seguimiento claro, accesible y ordenado.
            </p>

            <p class="font-weight-bold mb-1">Aquí podrá:</p>

            <ul>
                <li>Consultar el estado de cada solicitud:
                    <strong>En revisión, Aprobada o Rechazada</strong>.</li>
                <li>Leer los comentarios del administrador.</li>
                <li>Editar una solicitud que aún esté en revisión.</li>
                <li>Volver a solicitar revisión si fue rechazada y existe nueva información.</li>
            </ul>

            <p class="text-justify mb-0">
                Este flujo permite que cada incidencia tenga un registro histórico completo,
                facilitando la trazabilidad, la transparencia y el correcto manejo del expediente.
            </p>

        </div>
    </div>

    <!-- ⭐⭐⭐ FIN DEL BLOQUE NUEVO ⭐⭐⭐ -->


    <!-- AQUÍ SIGUEN TUS IMÁGENES ORIGINALMENTE -->

    <h5 class="font-weight-bold mb-3">
        <i class="fas fa-map-marker-alt text-danger"></i>
        ¿Dónde encontrar la nueva función?
    </h5>

    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h6 class="font-weight-bold">
                <i class="fas fa-folder-open text-info"></i>
                1. Administración de Expedientes
            </h6>
            <p class="mb-2 text-justify">
                Encontrará un botón para reportar incidencias directamente desde el expediente,
                lo que agiliza la verificación.
            </p>
            <div class="text-center">
                <img src="TU_IMAGEN_1.png"
                     class="img-fluid rounded shadow"
                     alt="Ubicación del botón en expedientes">
            </div>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h6 class="font-weight-bold">
                <i class="fas fa-list-alt text-success"></i>
                2. Menú lateral → Incidencias
            </h6>
            <p class="mb-2 text-justify">
                Para incidencias que no se relacionan con un caso, como turnos o cuentas.
            </p>
            <div class="text-center">
                <img src="TU_IMAGEN_2.png"
                     class="img-fluid rounded shadow"
                     alt="Ubicación del botón incidencias en menú lateral">
            </div>
        </div>
    </div>

    <div class="alert alert-success mt-4" role="alert">
        <i class="fas fa-check-circle"></i>
        Agradecemos su colaboración.
    </div>

</div>
    `;
    }
</script>
