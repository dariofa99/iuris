<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IURIS | Regresa</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            width: 100%;
            height: 100%;
        }

        body {

            font-family: 'Montserrat', sans-serif;

            background: #f5f7f8;

            color: #1f2933;

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

            overflow: hidden;
        }


        /* =========================
           DECORACIÓN DE FONDO
        ========================= */

        .background-circle {

            position: absolute;

            border-radius: 50%;

            pointer-events: none;
        }

        .circle-one {

            width: 420px;
            height: 420px;

            background: rgba(0, 105, 117, .06);

            top: -220px;
            right: -100px;
        }

        .circle-two {

            width: 350px;
            height: 350px;

            background: rgba(32, 44, 48, .05);

            bottom: -200px;
            left: -100px;
        }


        /* =========================
           CONTENEDOR
        ========================= */

        .wrapper {

            width: 100%;

            max-width: 850px;

            padding: 25px;

            position: relative;

            z-index: 2;
        }


        /* =========================
           CARD
        ========================= */

        .card {

            background: #ffffff;

            border-radius: 16px;

            overflow: hidden;

            box-shadow:
                0 15px 45px rgba(32, 44, 48, .12);

            border: 1px solid #e5e9eb;
        }


        /* =========================
           CABECERA
        ========================= */

        .card-header {

            background: #202c30;

            padding: 22px 30px;

            display: flex;

            align-items: center;

            justify-content: space-between;
        }


        .brand {

            color: white;

            font-size: 14px;

            font-weight: 600;

            letter-spacing: .3px;
        }


        .brand span {

            color: #78c8d0;

            font-weight: 700;
        }


        .system {

            color: rgba(255, 255, 255, .65);

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 1px;
        }


        /* =========================
           CONTENIDO
        ========================= */

        .card-body {

            padding: 55px 45px 45px;

            text-align: center;
        }


        /* ICONO */

        .icon-container {

            width: 100px;

            height: 100px;

            margin: 0 auto 25px;

            border-radius: 50%;

            background: #e8f4f5;

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;
        }


        .icon-container::after {

            content: '';

            position: absolute;

            width: 115px;

            height: 115px;

            border-radius: 50%;

            border: 1px solid rgba(0, 105, 117, .12);
        }


        .icon-container i {

            font-size: 42px;

            color: #006975;
        }


        /* TITULO */

        .title {

            font-size: 32px;

            font-weight: 600;

            color: #202c30;

            margin-bottom: 12px;
        }


        .title span {

            color: #006975;
        }


        /* DESCRIPCIÓN */

        .description {

            max-width: 560px;

            margin: 0 auto 30px;

            font-size: 15px;

            line-height: 1.8;

            color: #6b7780;
        }


        /* =========================
           BOTÓN
        ========================= */

        .btn-back {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 10px;

            padding: 13px 25px;

            background: #006975;

            color: #fff;

            border-radius: 7px;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            box-shadow:
                0 6px 15px rgba(0, 105, 117, .20);

            transition: all .25s ease;
        }


        .btn-back:hover {

            background: #00535d;

            color: white;

            text-decoration: none;

            transform: translateY(-2px);

            box-shadow:
                0 9px 20px rgba(0, 105, 117, .25);
        }


        .btn-back i {

            font-size: 16px;
        }


        /* =========================
           FOOTER
        ========================= */

        .card-footer {

            border-top: 1px solid #edf0f1;

            padding: 18px 30px;

            text-align: center;

            color: #9aa4a9;

            font-size: 11px;
        }


        .card-footer strong {

            color: #66747a;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 600px) {

            .card-header {

                padding: 18px 20px;

            }

            .system {

                display: none;

            }

            .card-body {

                padding: 40px 25px;

            }

            .title {

                font-size: 26px;

            }

            .description {

                font-size: 14px;

            }
        }

        .icon-container img {
            animation: rotarLogo 40s linear infinite;
        }

        @keyframes rotarLogo {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

</head>


<body>


    <div class="background-circle circle-one"></div>

    <div class="background-circle circle-two"></div>


    @php

        if (!isset($url)) {
            $url = '/dashboard/';
        }

    @endphp


    <div class="wrapper">


        <div class="card">


            {{-- CABECERA --}}

            <div class="card-header">

                <div class="brand">

                    <span>IURIS</span>

                    &nbsp;|&nbsp;
                    Consultorios Jurídicos y Centro de Conciliación Eduardo Alvarado Hurtado


                </div>

             {{--    <div class="system">

                    Universidad de Nariño

                </div> --}}

            </div>


            {{-- CONTENIDO --}}

            <div class="card-body">


                <div class="icon-container">

                    {{-- <i class="fa fa-compass"></i> --}}
                    <img src="{{ asset('img/logo_der.png') }}" alt="Logo IURIS" style="width: 60px; height: 60px;">

                </div>


                <div class="title">

                    Parece que 

                    <span>hubo un problema.</span>

                </div>


                <div class="description">

                    La página que estás buscando ha expirado.


                    Presiona el botón de regresar para volver a la plataforma y vuelve a iniciar sesión.

                    <br><br>

                    Si sigue presentando el problema, contacta al administrador del sistema.


                </div>


                <a href="{{ $url }}" class="btn-back">

                    <i class="fa fa-arrow-left"></i>

                    Regresar

                </a>


            </div>


            {{-- FOOTER --}}

            <div class="card-footer">

                Plataforma <strong>IURIS</strong>

                &nbsp;·&nbsp;

                Consultorios Jurídicos y Centro de Conciliación Eduardo Alvarado Hurtado

                &nbsp;·&nbsp;

                Universidad de Nariño
                &nbsp;·&nbsp;



            </div>


        </div>


    </div>


</body>

</html>
