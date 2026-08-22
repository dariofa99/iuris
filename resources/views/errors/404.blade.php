<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IURIS | Página no encontrada</title>


    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <style>

        * {
            box-sizing: border-box;
        }


        html,
        body {

            width: 100%;
            height: 100%;

            margin: 0;
        }


        body {

            font-family: 'Montserrat', sans-serif;

            background: #f8fafb;

            color: #202c30;

            overflow: hidden;
        }


        /* =========================================
           CONTENEDOR
        ========================================= */

        .page {

            width: 100%;

            min-height: 100vh;

            position: relative;

            display: flex;

            flex-direction: column;

            padding: 35px 6% 25px;
        }


        /* =========================================
           DECORACIÓN DE FONDO
        ========================================= */

        .background {

            position: absolute;

            inset: 0;

            overflow: hidden;

            pointer-events: none;

            z-index: 0;
        }


        .circle {

            position: absolute;

            border-radius: 50%;

            border: 1px solid rgba(0, 105, 117, .08);
        }


        .circle.one {

            width: 500px;
            height: 500px;

            right: -280px;
            top: -250px;
        }


        .circle.two {

            width: 350px;
            height: 350px;

            left: -220px;
            bottom: -220px;
        }


        .circle.three {

            width: 180px;
            height: 180px;

            right: 12%;
            bottom: -120px;

            background: rgba(0, 105, 117, .025);

            border: none;
        }


        /* =========================================
           HEADER
        ========================================= */

        .header {

            position: relative;

            z-index: 2;

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding-bottom: 20px;

            border-bottom: 1px solid #e1e7e9;
        }


        .brand {

            display: flex;

            align-items: center;

            gap: 13px;
        }


        .brand img {

            width: 42px;

            height: 42px;

            object-fit: contain;
        }


        .brand-info {

            display: flex;

            flex-direction: column;
        }


        .brand-name {

            font-size: 17px;

            font-weight: 800;

            letter-spacing: 2px;

            color: #202c30;
        }


        .brand-name span {

            color: #006975;
        }


        .brand-subtitle {

            margin-top: 3px;

            font-size: 9px;

            color: #7b878c;

            text-transform: uppercase;

            letter-spacing: 1.3px;
        }


        .university {

            font-size: 11px;

            color: #7b878c;

            text-align: right;

            text-transform: uppercase;

            letter-spacing: 1px;
        }


        .university strong {

            display: block;

            color: #202c30;

            font-size: 12px;

            margin-bottom: 4px;
        }


        /* =========================================
           CONTENIDO
        ========================================= */

        .main {

            position: relative;

            z-index: 2;

            flex: 1;

            display: flex;

            align-items: center;

            justify-content: center;

            text-align: center;
        }


        .content {

            width: 100%;

            max-width: 900px;

            margin-top: -20px;
        }


        /* =========================================
           PEQUEÑA ETIQUETA
        ========================================= */

        .label {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            color: #006975;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 2px;

            margin-bottom: 18px;
        }


        .label i {

            font-size: 12px;
        }


        /* =========================================
           404
        ========================================= */

        .error-number {

            position: relative;

            font-size: clamp(130px, 23vw, 270px);

            line-height: .78;

            font-weight: 800;

            letter-spacing: -18px;

            color: transparent;

            -webkit-text-stroke: 2px #006975;

            margin-left: -18px;

            user-select: none;
        }


        /* Segunda capa */

        .error-number::after {

            content: '404';

            position: absolute;

            left: 0;

            right: 0;

            top: 6px;

            color: rgba(0, 105, 117, .045);

            -webkit-text-stroke: 0;

            z-index: -1;

            transform: translate(9px, 9px);
        }


        /* =========================================
           LÍNEA DE RUTA
        ========================================= */

        .route {

            width: 300px;

            height: 34px;

            margin: 5px auto 18px;

            position: relative;
        }


        .route-line {

            position: absolute;

            left: 0;

            right: 0;

            top: 17px;

            height: 1px;

            background: #cbd5d8;
        }


        .route-line::after {

            content: '';

            position: absolute;

            width: 65px;

            height: 2px;

            left: -65px;

            top: -1px;

            background: #006975;

            animation: routeMove 2.4s linear infinite;
        }


        .route-dot {

            position: absolute;

            width: 9px;

            height: 9px;

            border-radius: 50%;

            background: #006975;

            top: 13px;

            left: 0;

            box-shadow: 0 0 0 5px rgba(0,105,117,.08);
        }


        .route-end {

            position: absolute;

            width: 9px;

            height: 9px;

            border-radius: 50%;

            border: 2px solid #9da9ad;

            background: #f8fafb;

            top: 13px;

            right: 0;
        }


        /* =========================================
           TITULO
        ========================================= */

        .title {

            margin: 0;

            font-size: clamp(27px, 3vw, 40px);

            font-weight: 700;

            color: #202c30;

            letter-spacing: -.8px;
        }


        .title span {

            color: #006975;
        }


        /* =========================================
           DESCRIPCIÓN
        ========================================= */

        .description {

            max-width: 580px;

            margin: 17px auto 0;

            color: #748085;

            font-size: 14px;

            line-height: 1.8;
        }


        /* =========================================
           BOTONES
        ========================================= */

        .actions {

            margin-top: 28px;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 20px;
        }


        .btn-primary {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 13px 24px;

            background: #006975;

            color: white;

            text-decoration: none;

            border-radius: 5px;

            font-size: 13px;

            font-weight: 600;

            transition: all .25s ease;
        }


        .btn-primary:hover {

            background: #00535d;

            color: white;

            text-decoration: none;

            transform: translateY(-2px);

            box-shadow: 0 7px 18px rgba(0,105,117,.18);
        }


        .btn-secondary {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            color: #6d797e;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            transition: all .25s ease;
        }


        .btn-secondary:hover {

            color: #006975;

            text-decoration: none;
        }


        /* =========================================
           FOOTER
        ========================================= */

        .footer {

            position: relative;

            z-index: 2;

            padding-top: 18px;

            border-top: 1px solid #e1e7e9;

            display: flex;

            justify-content: space-between;

            align-items: center;

            color: #9aa5a9;

            font-size: 10px;

            line-height: 1.6;
        }


        .footer strong {

            color: #69767b;
        }


        .status {

            display: flex;

            align-items: center;

            gap: 7px;
        }


        .status-dot {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background: #006975;
        }


        /* =========================================
           ANIMACIONES
        ========================================= */

        @keyframes routeMove {

            0% {

                left: -3px;

                opacity: 0;
            }

            15% {

                opacity: 1;
            }

            85% {

                opacity: 1;
            }

            100% {

                left: 250px;

                opacity: 0;
            }
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 650px) {

            body {

                overflow: auto;
            }


            .page {

                padding: 22px 25px;
            }


            .university {

                display: none;
            }


            .error-number {

                font-size: 130px;

                letter-spacing: -8px;

                margin-left: -8px;
            }


            .route {

                width: 220px;
            }


            .actions {

                flex-direction: column;

                gap: 18px;
            }


            .footer {

                flex-direction: column;

                gap: 10px;

                text-align: center;
            }
        }


        @media (max-width: 400px) {

            .brand-subtitle {

                display: none;
            }


            .error-number {

                font-size: 110px;
            }


            .title {

                font-size: 25px;
            }
        }

    </style>

</head>


<body>


@php

    if (!isset($url)) {
        $url = '/dashboard/';
    }

@endphp


<div class="page">


    {{-- =====================================
         FONDO
    ====================================== --}}

    <div class="background">

        <div class="circle one"></div>

        <div class="circle two"></div>

        <div class="circle three"></div>

    </div>


    {{-- =====================================
         HEADER
    ====================================== --}}

    <header class="header">


        <div class="brand">


            <img
                src="{{ asset('img/logo_der.png') }}"
                alt="IURIS">


            <div class="brand-info">

                <div class="brand-name">

                    <span>IURIS</span>

                </div>

                <div class="brand-subtitle">

                    Plataforma de gestión jurídica

                </div>

            </div>


        </div>


        <div class="university">

            

            Consultorios Jurídicos y Centro de Conciliación  “Eduardo Alvarado Hurtado”
<strong>
                Universidad de Nariño
            </strong>
        </div>


    </header>


    {{-- =====================================
         CONTENIDO
    ====================================== --}}

    <main class="main">


        <div class="content">


            <div class="label">

                <i class="fa fa-map-signs"></i>

                Ruta no encontrada

            </div>


            <div class="error-number">

                404

            </div>


            {{-- Línea de navegación --}}

            <div class="route">

                <div class="route-line"></div>

                <div class="route-dot"></div>

                <div class="route-end"></div>

            </div>


            <h1 class="title">

                Parece que esta página

                <span>tomó otro camino.</span>

            </h1>


            <div class="description">

                La dirección que estás intentando consultar
                no corresponde a una página disponible en IURIS.

                <br>

                Verifica la dirección o regresa al sistema
                para continuar trabajando.

            </div>


            <div class="actions">


                <a
                    href="{{ $url }}"
                    class="btn-primary">

                    <i class="fa fa-home"></i>

                    Regresar a IURIS

                </a>


                <a
                    href="javascript:history.back();"
                    class="btn-secondary">

                    <i class="fa fa-arrow-left"></i>

                    Página anterior

                </a>


            </div>


        </div>


    </main>


    {{-- =====================================
         FOOTER
    ====================================== --}}

    <footer class="footer">


        <div>

            <strong>IURIS</strong>

            · Consultorios Jurídicos y Centro de Conciliación

            “Eduardo Alvarado Hurtado”

        </div>


        <div class="status">

            <span class="status-dot"></span>

            Universidad de Nariño

        </div>


    </footer>


</div>


</body>

</html>