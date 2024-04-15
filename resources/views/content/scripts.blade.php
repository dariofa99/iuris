<script>
    @if (Session::has('message-information') && config('app.name') != 'ConciliApp')
        

        var keyCir = localStorage.getItem("keyCircNotas2Corte");
            if (keyCir == null) {
              var message = getCircular();
            } else {
              var message = getGeneralMessage();
            }        
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
            "data-not":"keyCirActualizaCierreClose"
        }))

        return carrousel;
    }


    function getCarrouselDocentes() {
        var carrousel = `<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                          <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                          </ol>
                          <div class="carousel-inner">
                            <div class="carousel-item active">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva1.0.JPG') }}" alt="First slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva1.1.JPG') }}" alt="Second slide">
                            </div>
                            <div class="carousel-item">
                              <img class="d-block w-100" src="{{ asset('dist/img/update/Diapositiva1.2.JPG') }}" alt="Third slide">
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
            "data-not":"keyCircActDosClose"
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
        message += `<span> Últ. Actualización: 14 de abril de 2024 <br>
                        Si el problema persiste comuníquese al 3106038006  
                      </span>`;

        return message;
    }

    function getMantenimientoMessage() {
        var message = '';
        message += '<div class="alert alert-info" style="font-size:19px">';
        message += `<h4>
                      <strong >
                        Atención!<br>
                        Teniendo en cuenta que Iuris entrará en periodo de 
                        vacaciones a partir del día
                        8 de diciembre del año en curso, se recuerda que
                        se pausaran los días en los que evalua el sistema a
                        excepción de las fechas solicitadas por el docente 
                        para realizar correcciones en los respectivos casos o actuaciones.
                   </strong> <br>
                          <br>
                      
                    </h4> </div>`;
        message += ``;

        return message;
    }

    function getCircular() {
        var keyCir = localStorage.getItem("keyCircNotas2Corte");
        var message = '';
        if (keyCir == null) {
            message = `<embed  src="{{ asset('recursos/CircularNotas2C.pdf#toolbar=0') }}" id="pdfViewer" >`
            message += `<button class="btn btn-success" data-not="keyCircNotas2Corte" id="btnNotFalse" sandbox >No volver a mostrar!</button>`
        }       
        return message;
    }
</script>
