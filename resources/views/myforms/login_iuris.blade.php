{{-- 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h4 class="card-header bg-white" style="text-align: center">
                    Consultorios Jurídicos
                </h4>
                <div class="card-body">
                    <div class="card-login-body">
                        <img src="{{ asset('dist/img/online-justice.png') }}" alt=""><br>
                        Solicite asesoría jurídica de manera virtual.
                    </div>

                    <div class="card-footer bg-white" style="text-align: center;border-top:1px solid rgb(235, 235, 235)">
                    <a href="/solicitudes/expedientes/recepcion/?paso=1" class="btn btn-warning">
                            CONTINUAR
                        </a>
                    </div>

                </div>
             
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <h4 class="card-header bg-white" style="text-align: center">
                    Centro de conciliación
                </h4>
                <div class="card-body" >
                    <div class="card-login-body" >
                        <img src="{{ asset('dist/img/collaboration.png') }}" alt=""><br>
                        Solicite y gestione de manera eficiente, rápida y segura un proceso conciliatorio.
                        <h3>En periodo de pruebas, solicite la atención de manera presencial.</h3>

                    </div>

                    <div class="card-footer bg-white" style="text-align: center;border-top:1px solid rgb(235, 235, 235)">
                        <a href="#" class="btn btn-warning" id="btn_solicitar_conciliacion">
                           CONTINUAR (!En periodo de pruebas)
                       </a> 
                   </div>


                </div>
                
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a target="_blank" style="border-bottom:1px solid gray;color: black;font-size:14px" href="/videos">
                Ver videos de ayuda
            </a>
        </div>
    </div>
</div>

 
 --}}

@php 
 $videos = [
    [
        'url' => 'https://www.youtube.com/embed/Mp90dIBGjfc',
        'title' => 'Presentación',
    ],
    [
        'url' => 'https://www.youtube.com/embed/73hyBnrZaro',
        'title' => 'Registro civil, personas intersexuales',
    ],
    [
        'url' => 'https://www.youtube.com/embed/qa_VPa3_oCs',
        'title' => 'Registro civil de defunción',
    ],
    [
        'url' => 'https://www.youtube.com/embed/9qx0-JBUReI',
        'title' => 'Acoso escolar',
    ],
    [
        'url' => 'https://www.youtube.com/embed/m3sVn2VzdD0',
        'title' => 'Registro civil de nacimiento',
    ],
    [
        'url' => 'https://www.youtube.com/embed/QXKzkj8Suy0',
        'title' => 'Lesiones personales dolosas',
    ],
    [
        'url' => 'https://www.youtube.com/embed/aKb5OLxDUIM',
        'title' => 'Registro civil de matrimonio',
    ],
    [
        'url' => 'https://www.youtube.com/embed/rfXFT75fx40',
        'title' => 'Habeas corpus',
    ],
    [
        'url' => 'https://www.youtube.com/embed/P_nI9e1UVyI',
        'title' => 'Servicios públicos domiciliarios',
    ],
    [
        'url' => 'https://www.youtube.com/embed/lb96_GiE210',
        'title' => 'Maltrato animal',
    ],
    [
        'url' => 'https://www.youtube.com/embed/VbDRgUWkqRg',
        'title' => 'Feminicidio',
    ],
    [
        'url' => 'https://www.youtube.com/embed/QsuHKWLguuA',
        'title' => 'Derecho de los ríos',
    ],
    [
        'url' => 'https://www.youtube.com/embed/h68FQlgc-ME',
        'title' => 'Nulidad electoral',
    ],
];

@endphp

<hr class="my-3">

<div class="container my-5">
    <h3 class="text-center mb-4">Conozca más con nuestros videos informativos</h3>
    <div class="row g-4">
        @foreach ($videos as $item)
        <div class="col-md-4 videos">
            <div class="card shadow-sm">
                <div class="video-wrapper">
                    <iframe src="{{$item["url"]}}" loading="lazy" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                 
                </div>
                <div class="card-body text-center">
                    <h6 class="card-title">{{$item["title"]}}</h6>
                </div>
            </div>
        </div>
        @endforeach
       
        <!-- Repite para más videos -->
    </div>
</div>
@include('myforms.frm_modal_login')
@include('myforms.frm_modal_solicitud_atencion_virtual') 