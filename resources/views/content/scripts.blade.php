<script>
    @if (Session::has('message-information') && config('app.name') != 'ConciliApp')
        localStorage.removeItem('keyCir', true);
        if(localStorage.getItem('keyCarouselNotiClose')){
          var message = getGeneralMessage();
        }else{
          var message = getCarrousel();
        }        
        $("#modal-show-alerts-content").html(message);
        $("#mymodalShowAlerts").modal("show");
    @endif

    $("#mymodalShowAlerts").on("click", '#btnNotFalse', function(e) {
        localStorage.setItem('keyCarouselNotiClose', true);
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
                        $("#contentNotButtonDis").append($("<button>",{
                          class:"btn btn-danger",
                          id:"btnNotFalse",
                          text:"No volver a mostrar"
                        }))
              
        return carrousel;
    }

    function getGeneralMessage() {
        var message = '';
        message += '<div class="alert alert-warning" style="font-size:18px">';
        message += `<h4>
                      <strong style="border-bottom:1px solid white">
                        Bienvendido a {{ Str::upper(config('app.name')) }}!</strong> <br>
                      Recuerda que estamos actualizando la plataforma, si presentas algún problema refresca el navegador
                      con las teclas CTRL+F5 <i>o</i> CTRL+fn+F5 (portátiles). Tener en cuenta para conexión desde dispositivos móviles. <br>
                      
                    </h4> </div>`;
        message += `<span> Últ. Atualización: 6 de nov. 2023 <br>
                        Si el problema persiste comunícate al 3106038006  
                      </span>`;

        return message;
    }

    function getCircular() {
        var keyCir = localStorage.getItem("keyCir");
        var message = '';
        if (keyCir == null) {
            message = `<embed  src="{{ asset('recursos/Circular.pdf#toolbar=0') }}" id="pdfViewer" >`
            message += `<button class="btn btn-success" id="btnNotFalse" sandbox >No volver a mostrar!</button>`

        }
        return message;
    }
</script>
