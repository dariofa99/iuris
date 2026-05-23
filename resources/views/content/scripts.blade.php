<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contenedor = document.getElementById('mensajeMotivacional');
        if (contenedor) {
            contenedor.innerHTML = getMotivationalMessage();
        }
        0
        setInterval(() => {
            contenedor.innerHTML = getMotivationalMessage();
        }, 60000);
    });

    @if (Session::has('message-information') && config('app.name') != 'ConciliApp')
        localStorage.removeItem("keyCircularActualVacSemanaSanta");

        var keyCir = localStorage.getItem("keyCircularActualFinPeriodo");
        $("#modal_t").text("");
        var message = '';
         
        if (keyCir == null) {
            message = getHtmlCircularFinPeriodo();
            $("#modal-show-alerts-content").html(message);
            $("#mymodalShowAlerts").modal("show");
        } else {
            $("#modal_t").text("");
            // message = getMotivationalMessage();
        }
      

        //var message = getMantenimientoMessage();
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

    function getHTMLDocument() {

        $("#contentNotButtonDis").append($("<button>", {
            class: "btn btn-outline-secondary",
            id: "btnNotFalse",
            text: "No volver a mostrar",
            "data-not": "keyNotaAsistenciaConsultorio"
        }));

        return `
<div class="container-fluid" style="font-size:17px; max-width:960px; line-height:1.6;" role="article" aria-label="Circular académica sobre nota de asistencia">

    <!-- HEADER -->
    <div class="text-center mb-4">
        <h2 class="font-weight-bold" style="color:#0d6efd;">
            <i class="fas fa-clipboard-check" aria-hidden="true"></i>
            Circular Académica
        </h2>
        <p class="text-muted">
           
        </p>
    </div>


    <!-- CARD PRINCIPAL -->
    <div class="card shadow-sm mb-4" style="border-left:1px solid #0d6efd; border-radius:12px;">
        <div class="card-body">

            <p>
               La unidad de Consultorios Jurídicos y Centro de Conciliación “Eduardo Alvarado Hurtado”, de la Universidad de Nariño, recuerda a los estudiantes, que, en cumplimiento de lo establecido en el Estatuto Estudiantil y el Reglamento interno, la <b>ASISTENCIA</b> a los turnos y demás actividades académicas programadas en esta materia, es obligatoria y un componente esencial del proceso formativo. En consecuencia, se <b>DA A CONOCER QUE, DESDE AHORA</b>, podrán consultar dicha calificación directamente en IURIS. 
            </p>

            <p>
               Para ese efecto, se da también a conocer, que la calificación de la asistencia en IURIS, se calcula de forma proporcional según los turnos efectivamente asistidos, frente a los turnos programados, mediante la siguiente fórmula .
            </p>

            <div class="alert alert-primary mt-3" style="border-radius:10px;">
                <strong>Fórmula de cálculo:</strong><br>
                Nota de asistencia = Turnos asistidos × ( 5 ÷ Turnos que debía asistir )
            </div>

            <p class="mb-0">
                La calificación máxima posible es <strong>5.0</strong> y se ajusta proporcionalmente 
                de acuerdo con el cumplimiento de la asistencia registrada.
            </p>

        </div>
    </div>



    <!-- PONDERACIONES -->
    <div class="card shadow-sm mb-4" style="border-left:1px solid #198754; border-radius:12px;">
        <div class="card-body">

            <h5 class="font-weight-bold mb-3">
                <i class="fas fa-percentage" aria-hidden="true"></i>
                Ponderación dentro de la nota final
            </h5>

            <p>
                La nota de asistencia ha hecho parte de la evaluación integral del Consultorio Jurídico, incorporándose dentro de la calificación final conforme a la estructura académica de cada modalidad:
            </p>

            <ul style="margin-bottom:0;">
                <li><strong>Solo Expedientes:</strong> Expedientes 70% – Asistencia 30%</li>
                <li><strong>Expedientes + Conciliaciones:</strong> Expedientes 50% – Conciliaciones 20% – Asistencia 30%</li>
                <li><strong>Expedientes + Conciliaciones + Defensas de oficio:</strong> Expedientes 30% – Conciliaciones 20% – Defensas 20% – Asistencia 30%</li>
            </ul>

        </div>
    </div>



    <!-- MENSAJE INSTITUCIONAL -->
    <div class="card shadow-sm" style="border-left:1px solid #6c757d; border-radius:12px;">
        <div class="card-body">

            <p class="mb-2">
                La socialización de esta información tiene como propósito que los estudiantes conozcan de manera oportuna cómo se consolida su desempeño académico y brindar mayor claridad y transparencia sobre la forma en como se calcula su nota final, igualmente, para recordar el reconocimiento que hace el Consultorio Jurídico a la constancia, el compromiso y la participación de los estudiantes en las actividades académicas y en el servicio a la comunidad.
            </p>

            <p class="mb-0">
                Se invita a todos los estudiantes a realizar seguimiento periódico de su registro de asistencia a través del sistema IURIS.
            </p>

        </div>
    </div>



    <!-- FOOTER -->
    <div class="text-center text-muted mt-4" style="font-size:15px;">
        Consultorios Jurídicos y Centro de Conciliación “Eduardo Alvarado Hurtado”<br>
        Vigente a partir de la fecha
    </div>

</div>
`;
    }


    function getMantenimientoMessage() {
        $("#contentNotButtonDis").append($("<button>", {
            class: "btn btn-danger",
            id: "btnNotFalse",
            text: "No volver a mostrar",
            "data-not": "keyCircularActualVacSemanaSanta"
        }));
        return `
<div class="container-fluid" style="font-size:18px;">

    <!-- HEADER NAVIDEÑO -->
    <div class="text-center mb-4">
        <h2 class="font-weight-bold" style="color:#502997;">
           <i class="fas fa-bell"></i> Atención

        </h2>
        <p class="text-muted" style="font-style: italic; border-left: 3px solid #d4af7a; padding-left: 10px;">
    📅 <strong>Recordatorio</strong> para el periodo de vacaciones de Semana Santa
</p>
    </div>

    <!-- CARD INFO -->
    <div class="card shadow-sm mb-4" style="border-left:6px solid #502997;">
        <div class="card-body">

            <p class="mb-3">
                Estimados estudiantes, recuerden que durante el periodo de vacaciones,
                <strong>el sistema pone en pausa los días en los cuales se aplican notas de cero</strong>,
                <span class="text-danger font-weight-bold">a excepción de las actuaciones con fecha límite puesta por el docente asesor.</span>
            </p>

           
        </div>
    </div>`;
        `

    <!-- MENSAJE NAVIDEÑO -->
 <div class="text-center py-4 px-3" 
     style="background:#f9f2eb;border-radius:12px; border:1px solid #f0e6dc;">
    <h4 style="color:#8e2b2b;" class="font-weight-bold mb-2">
        ✝️ 🌿 <i class="fas fa-dove"></i> 🌿 ✝️
    </h4>
    <p class="mb-2">
        Desde la administración de <strong>IURIS</strong>, 
        les deseamos una Santa Semana llena de reflexión, paz interior, renovación espiritual y bendiciones junto a sus seres queridos.
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
                texto: "El éxito no es la clave de la felicidad. La felicidad es la clave del éxito.",
                autor: "Albert Schweitzer"
            },
            {
                texto: "No te compares con los demás, eres único y valioso.",
                autor: "Dr. Seuss"
            },
            {
                texto: "El futuro pertenece a quienes creen en la belleza de sus sueños.",
                autor: "Eleanor Roosevelt"
            },
            {
                texto: "Cree que puedes y ya estás a medio camino.",
                autor: "Theodore Roosevelt"
            },
            {
                texto: "El éxito es ir de fracaso en fracaso sin perder el entusiasmo.",
                autor: "Winston Churchill"
            },
            {
                texto: "La educación es el arma más poderosa que puedes usar para cambiar el mundo.",
                autor: "Nelson Mandela"
            },
            {
                texto: "Nuestra mayor gloria no está en no caer nunca, sino en levantarnos cada vez que caemos.",
                autor: "Confucio"
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
                texto: "El éxito no es definitivo, el fracaso no es fatal: lo que cuenta es el valor para continuar.",
                autor: "Winston Churchill"
            },
            {
                texto: "Dime y lo olvido, enséñame y lo recuerdo, involúcrame y lo aprendo.",
                autor: "Benjamin Franklin"
            },
            {
                texto: "El único modo de hacer un gran trabajo es amar lo que haces.",
                autor: "Steve Jobs"
            },
            {
                texto: "No importa lo lento que vayas, siempre y cuando no te detengas.",
                autor: "Confucio"
            },
            {
                texto: "La perseverancia no es una carrera larga; son muchas carreras cortas, una tras otra.",
                autor: "Walter Elliot"
            },
            {
                texto: "El éxito suele llegar a quienes están demasiado ocupados para buscarlo.",
                autor: "Henry David Thoreau"
            },
            {
                texto: "La diferencia entre lo ordinario y lo extraordinario es ese pequeño extra.",
                autor: "Jimmy Johnson"
            },
            {
                texto: "No te lamentes por los errores, aprende de ellos y sigue adelante.",
                autor: "Vince Lombardi"
            },
            {
                texto: "El único límite para nuestros logros de mañana está en nuestras dudas de hoy.",
                autor: "Franklin D. Roosevelt"
            },
            {
                texto: "Haz de tu pasión tu propósito, y un día se convertirá en tu profesión.",
                autor: "Oprah Winfrey"
            },
            {
                texto: "El talento gana partidos, pero el trabajo en equipo y la inteligencia ganan campeonatos.",
                autor: "Michael Jordan"
            },
            {
                texto: "No cuentes los días, haz que los días cuenten.",
                autor: "Muhammad Ali"
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
                texto: "El optimismo es la fe que conduce al logro.",
                autor: "Helen Keller"
            },
            {
                texto: "Lo que obtienes al lograr tus metas no es tan importante como lo que te conviertes al lograrlas.",
                autor: "Zig Ziglar"
            },
            {
                texto: "El conocimiento te dará poder, pero el carácter te dará respeto.",
                autor: "Bruce Lee"
            },
            {
                texto: "La disciplina es el puente entre metas y logros.",
                autor: "Jim Rohn"
            },
            {
                texto: "El éxito no está en vencer siempre, sino en nunca darse por vencido.",
                autor: "Vicente del Bosque"
            },
            {
                texto: "La motivación nos impulsa a empezar, el hábito nos permite continuar.",
                autor: "Jim Ryun"
            },
            {
                texto: "Hoy es difícil, mañana será peor, pero pasado mañana habrá sol.",
                autor: "Jack Ma"
            },
            {
                texto: "La excelencia no es un acto, es un hábito.",
                autor: "Aristóteles"
            },
            {
                texto: "El 80% del éxito se debe a presentarse.",
                autor: "Woody Allen"
            },
            {
                texto: "Lo importante es no dejar de hacerse preguntas.",
                autor: "Albert Einstein"
            },
            {
                texto: "El verdadero signo de la inteligencia no es el conocimiento, sino la imaginación.",
                autor: "Albert Einstein"
            },
            {
                texto: "No puedes tener una vida positiva y una mente negativa.",
                autor: "Joyce Meyer"
            },
            {
                texto: "Lo que piensas, te conviertes. Lo que sientes, lo atraes. Lo que imaginas, lo creas.",
                autor: "Buda"
            },
            {
                texto: "El propósito de nuestras vidas es ser felices.",
                autor: "Dalai Lama"
            },
            {
                texto: "No esperes. El momento nunca será el adecuado.",
                autor: "Napoleon Hill"
            },
            {
                texto: "El único lugar donde el éxito viene antes que el trabajo es en el diccionario.",
                autor: "Vidal Sassoon"
            },
            {
                texto: "El éxito es hacer lo que amas y encontrar la manera de que te paguen por ello.",
                autor: "Nelson Mandela"
            }, {
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

        return frase.texto;


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
      🕒 Últ. actualización: <b>10 de febrero de 2026</b><br>
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

    function getHtmlCircularFinPeriodo() {

    $("#contentNotButtonDis").append($("<button>", {
        class: "btn btn-danger",
        id: "btnNotFalse",
        text: "No volver a mostrar",
        "data-not": "keyCircularActualFinPeriodo"
    }));

    return `

<div class="container-fluid">

    <div id="modalContentStart" tabindex="-1"></div>

    <div id="modal-announce"
         class="sr-only"
         aria-live="assertive"
         aria-atomic="true">

        Información importante sobre sustitución de casos estudiantiles.
    </div>

    <div class="text-center mb-4">

        <div class="mb-3">
            <i class="fas fa-exchange-alt text-warning"
               style="font-size:55px;"></i>
        </div>

        <h3 class="font-weight-bold">
            Sustitución de casos y continuidad de atención
        </h3>

        <p class="text-muted">
            Plataforma IURIS
        </p>

    </div>

    <div class="alert alert-warning shadow-sm">

        <h5 class="font-weight-bold mb-3">
            <i class="fas fa-exclamation-triangle"></i>
            Información importante para estudiantes
        </h5>

        <p class="mb-2 text-justify">
            Teniendo en cuenta el cierre de año académico y el proceso de
            <strong>sustitución de casos</strong>, se recuerda que cuando un
            expediente es asignado a un nuevo estudiante,
            este adquiere la responsabilidad de asumir la continuidad del caso.
        </p>

        <p class="mb-0 text-justify">
            Por esta razón, el nuevo estudiante debe comunicarse oportunamente
            con el usuario solicitante para informarle sobre el cambio de asignación
            y ponerse al tanto de la situación actual del expediente.
        </p>

    </div>

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-dark text-white">

            <i class="fas fa-tasks"></i>
            Responsabilidades del estudiante asignado

        </div>

        <div class="card-body">

            <div class="mb-3">

                <div class="d-flex mb-3">

                    <div class="mr-3">
                        <span class="badge badge-primary p-2">
                            1
                        </span>
                    </div>

                    <div>
                        <strong>Contactar al usuario solicitante</strong>
                        <br>
                        <small class="text-muted">
                            Informar que el expediente ya no será gestionado
                            por el estudiante anterior.
                        </small>
                    </div>

                </div>

                <div class="d-flex mb-3">

                    <div class="mr-3">
                        <span class="badge badge-info p-2">
                            2
                        </span>
                    </div>

                    <div>
                        <strong>Revisar el expediente y contextualizarse</strong>
                        <br>
                        <small class="text-muted">
                            Verificar actuaciones, observaciones y estado actual del caso.
                        </small>
                    </div>

                </div>

                <div class="d-flex">

                    <div class="mr-3">
                        <span class="badge badge-danger p-2">
                            3
                        </span>
                    </div>

                    <div>
                        <strong>Registrar hechos y respuesta</strong>
                        <br>
                        <small class="text-muted">
                            El sistema evaluará automáticamente la gestión
                            dentro de los siguientes 5 días calendario.
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="alert alert-danger shadow-sm">

        <h5 class="font-weight-bold">
            <i class="fas fa-exclamation-circle"></i>
            Importante
        </h5>

        <p class="mb-2 text-justify">
            Si el estudiante no registra los hechos y la respuesta correspondiente
            dentro del plazo establecido,
            el sistema podrá generar automáticamente una calificación en cero (0).
        </p>

        <p class="mb-0 text-justify">
            Estas notas serán reflejadas en el
            <strong>periodo académico actual</strong>.
        </p>

    </div>

    <div class="text-center mt-4">

        <div class="alert alert-light border shadow-sm mb-0">

            <i class="fas fa-check-circle text-success"></i>

            Agradecemos su compromiso y responsabilidad
            en la atención oportuna de los usuarios.

        </div>

    </div>

</div>

    `;
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
