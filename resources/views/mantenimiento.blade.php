<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>IURIS</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

#wait{
        z-index: 200000;
}
#load{
animation: spin 5s infinite;
}
@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
.table-buscar-expe{
        width: 100%;
        padding: 3px;
}

        </style>

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                    @else
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    ¡LO SENTIMOS!
                </div>
                <p style="color:#000;font-weight: 600;font-size: 20px;">Estamos realizando mantenimiento.</p>
                <p style="color:#000;font-weight: 600;font-size: 20px;" >IURIS estará disponible desde las 7:00 AM del -Miércoles- 11 de enero.</p>
<p> <div id="wait"><img src="{{asset('img/logo2.png')}}" id="load" width="67" height="71" /></div>
</p>
            </div>

        </div>
    </body>
</html>

