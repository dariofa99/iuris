<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IURIS | Error 500</title>

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

            background: #f7f9fa;

            color: #202c30;

            overflow: hidden;
        }


        /* =====================================
           CONTENEDOR PRINCIPAL
        ===================================== */

        .page {

            width: 100%;

            min-height: 100vh;

            display: flex;

            position: relative;
        }


        /* =====================================
           PANEL IZQUIERDO
        ===================================== */

        .visual {

            width: 48%;

            min-height: 100vh;

            background: #202c30;

            position: relative;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        /* Decoración */

        .visual::before {

            content: '';

            position: absolute;

            width: 500px;

            height: 500px;

            border-radius: 50%;

            border: 1px solid rgba(120, 200, 208, .15);

            top: -180px;

            left: -180px;
        }


        .visual::after {

            content: '';

            position: absolute;

            width: 650px;

            height: 650px;

            border-radius: 50%;

            border: 1px solid rgba(120, 200, 208, .08);

            bottom: -350px;

            right: -250px;
        }


        /* Línea decorativa */

        .line {

            position: absolute;

            width: 1px;

            height: 65%;

            background: linear-gradient(
                transparent,
                #006975,
                transparent
            );

            right: 0;

            top: 17%;
        }


        /* =====================================
           CONTENIDO DEL PANEL
        ===================================== */

        .visual-content {

            position: relative;

            z-index: 2;

            text-align: center;

            width: 100%;

            padding: 30px;
        }


        /* Logo */

        .logo {

            width: 80px;

            height: 80px;

            object-fit: contain;

            margin-bottom: 25px;

            opacity: .95;

            animation: floating 4s ease-in-out infinite;
        }


        /* IURIS */

        .iuris {

            color: #fff;

            font-size: 17px;

            font-weight: 700;

            letter-spacing: 4px;

            margin-bottom: 5px;
        }


        .institution {

            color: rgba(255,255,255,.55);

            font-size: 10px;

            text-transform: uppercase;

            letter-spacing: 1.5px;

        }


        /* =====================================
           500 GIGANTE
        ===================================== */

        .error-number {

            font-size: clamp(130px, 18vw, 230px);

            line-height: .9;

            font-weight: 800;

            letter-spacing: -12px;

            margin: 45px 0 25px;

            color: transparent;

            -webkit-text-stroke: 2px #78c8d0;

            text-shadow:
                10px 10px 0 rgba(0,105,117,.25);

            animation: errorFloat 5s ease-in-out infinite;
        }


        .internal {

            color: rgba(255,255,255,.55);

            font-size: 11px;

            letter-spacing: 3px;

            text-transform: uppercase;
        }


        /* =====================================
           PANEL DERECHO
        ===================================== */

        .content {

            width: 52%;

            min-height: 100vh;

            display: flex;

            align-items: center;

            padding: 50px 8%;
        }


        .content-inner {

            max-width: 560px;
        }


        /* Pequeña etiqueta */

        .eyebrow {

            display: flex;

            align-items: center;

            gap: 10px;

            color: #006975;

            font-size: 12px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 2px;

            margin-bottom: 20px;
        }


        .eyebrow::before {

            content: '';

            width: 28px;

            height: 2px;

            background: #006975;
        }


        /* Título */

        h1 {

            margin: 0;

            font-size: clamp(38px, 4vw, 58px);

            line-height: 1.1;

            font-weight: 700;

            color: #202c30;

            letter-spacing: -2px;
        }


        h1 span {

            color: #006975;
        }


        /* Texto */

        .description {

            margin-top: 25px;

            color: #6d797e;

            font-size: 15px;

            line-height: 1.9;

            max-width: 500px;
        }


        /* =====================================
           BOTONES
        ===================================== */

        .actions {

            margin-top: 35px;

            display: flex;

            align-items: center;

            gap: 15px;

            flex-wrap: wrap;
        }


        .btn-primary {

            display: inline-flex;

            align-items: center;

            gap: 10px;

            padding: 14px 25px;

            background: #006975;

            color: #fff;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            border-radius: 5px;

            transition: .25s ease;
        }


        .btn-primary:hover {

            background: #00535d;

            color: white;

            text-decoration: none;

            transform: translateY(-2px);
        }


        .btn-secondary {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            color: #657176;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            padding: 14px 10px;

            transition: .25s ease;
        }


        .btn-secondary:hover {

            color: #006975;

            text-decoration: none;
        }


        /* =====================================
           PIE
        ===================================== */

        .footer {

            margin-top: 55px;

            padding-top: 18px;

            border-top: 1px solid #e3e8ea;

            color: #a0aaae;

            font-size: 10px;

            line-height: 1.7;
        }


        .footer strong {

            color: #667278;
        }


        /* =====================================
           ANIMACIONES
        ===================================== */

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-7px);
            }
        }


        @keyframes errorFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }


        /* =====================================
           RESPONSIVE
        ===================================== */

        @media (max-width: 800px) {

            body {
                overflow: auto;
            }

            .page {
                display: block;
            }

            .visual {
                width: 100%;
                min-height: 48vh;
            }

            .content {
                width: 100%;
                min-height: auto;
                padding: 50px 30px;
            }

            .error-number {
                margin: 30px 0 15px;
                font-size: 130px;
            }

            .line {
                display: none;
            }

            .footer {
                margin-top: 40px;
            }
        }


        @media (max-width: 450px) {

            .visual {
                min-height: 42vh;
            }

            .error-number {
                font-size: 105px;
                letter-spacing: -7px;
            }

            .logo {
                width: 65px;
                height: 65px;
            }

            .content {
                padding: 40px 25px;
            }

            h1 {
                font-size: 36px;
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
         PANEL VISUAL
    ====================================== --}}

    <section class="visual">

        <div class="line"></div>

        <div class="visual-content">


            <img
                src="{{ asset('img/logo_der.png') }}"
                alt="Logo IURIS"
                class="logo">


            <div class="iuris">
                IURIS
            </div>


            <div class="institution">
                Universidad de Nariño
            </div>


            <div class="error-number">
                500
            </div>


            <div class="internal">
                Error interno del sistema
            </div>


        </div>

    </section>


    {{-- =====================================
         CONTENIDO
    ====================================== --}}

    <section class="content">

        <div class="content-inner">


            <div class="eyebrow">
                Atención
            </div>


            <h1>

                Algo no salió
                <br>

                <span>como esperábamos.</span>

            </h1>


            <div class="description">

                IURIS encontró un inconveniente al procesar
                tu solicitud. Nuestro sistema no pudo completar
                la operación en este momento.

                <br><br>

                Puedes intentar nuevamente o regresar al
                inicio de la plataforma.

                Si el problema persiste, reporta <a href="{{url('/incidencias#problempage')}}">aquí</a> este error al administrador del sistema.

            </div>


            <div class="actions">


                <a
                    href="{{ $url }}"
                    class="btn-primary">

                    <i class="fa fa-home"></i>

                    Regresar a IURIS

                </a>


                <a
                    href="javascript:location.reload();"
                    class="btn-secondary">

                    <i class="fa fa-refresh"></i>

                    Intentar nuevamente

                </a>


            </div>


            <div class="footer">

                Plataforma <strong>IURIS</strong>

                · Consultorios Jurídicos y Centro de Conciliación
                “Eduardo Alvarado Hurtado”

                <br>

                Universidad de Nariño

            </div>


        </div>

    </section>


</div>


</body>

</html>