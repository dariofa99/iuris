<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Acceso restringido | IURIS</title>

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        body {

            font-family: Arial, Helvetica, sans-serif;

            background:
                radial-gradient(
                    circle at 20% 20%,
                    rgba(0, 105, 117, .12),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 80% 80%,
                    rgba(31, 139, 148, .10),
                    transparent 30%
                ),
                #f6f9fa;

            color: #26383d;

            overflow: hidden;
        }


        /* =========================================
           FONDO
        ========================================= */

        .page {

            position: relative;

            width: 100%;
            height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            overflow: hidden;
        }


        /* círculos decorativos */

        .circle {

            position: absolute;

            border-radius: 50%;

            border: 1px solid rgba(0, 105, 117, .10);
        }

        .circle-one {

            width: 420px;
            height: 420px;

            left: -180px;
            top: -180px;
        }

        .circle-two {

            width: 650px;
            height: 650px;

            right: -300px;
            bottom: -300px;
        }

        .circle-three {

            width: 180px;
            height: 180px;

            right: 12%;
            top: 12%;

            border-color: rgba(0, 105, 117, .06);
        }


        /* =========================================
           CONTENIDO
        ========================================= */

        .content {

            position: relative;

            z-index: 5;

            width: 90%;

            max-width: 900px;

            display: grid;

            grid-template-columns: 1fr 1fr;

            align-items: center;

            gap: 60px;
        }


        /* =========================================
           ILUSTRACIÓN
        ========================================= */

        .illustration {

            position: relative;

            height: 360px;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        .number {

            position: absolute;

            font-size: 210px;

            font-weight: 800;

            line-height: 1;

            letter-spacing: -15px;

            color: rgba(0, 105, 117, .08);

            user-select: none;
        }


        /* candado */

        .lock {

            position: relative;

            width: 145px;

            height: 125px;

            margin-top: 50px;

            background: #006975;

            border-radius: 18px;

            box-shadow:
                0 20px 45px rgba(0, 105, 117, .25);

            animation: floatLock 4s ease-in-out infinite;
        }


        .lock::before {

            content: "";

            position: absolute;

            width: 72px;

            height: 72px;

            left: 22px;

            top: -55px;

            border: 16px solid #006975;

            border-bottom: 0;

            border-radius: 50px 50px 0 0;
        }


        .keyhole {

            position: absolute;

            width: 18px;

            height: 28px;

            background: white;

            border-radius: 10px;

            left: 63px;

            top: 42px;
        }


        .keyhole::after {

            content: "";

            position: absolute;

            width: 8px;

            height: 18px;

            background: white;

            left: 5px;

            top: 16px;
        }


        @keyframes floatLock {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }


        /* =========================================
           TEXTO
        ========================================= */

        .text {

            text-align: left;
        }


        .brand {

            display: flex;

            align-items: center;

            gap: 10px;

            margin-bottom: 25px;

            font-size: 18px;

            font-weight: 700;

            color: #006975;
        }


        .brand-line {

            width: 35px;

            height: 3px;

            background: #006975;

            border-radius: 5px;
        }


        h1 {

            margin: 0;

            font-size: 42px;

            line-height: 1.1;

            color: #26383d;
        }


        h1 span {

            color: #006975;
        }


        .code {

            margin-top: 12px;

            font-size: 15px;

            font-weight: 700;

            letter-spacing: 2px;

            color: #8a9a9e;
        }


        .message {

            margin-top: 25px;

            max-width: 500px;

            font-size: 16px;

            line-height: 1.7;

            color: #66777c;
        }


        /* =========================================
           BOTÓN
        ========================================= */

        .actions {

            margin-top: 30px;

            display: flex;

            gap: 12px;
        }


        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 12px 22px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            transition: all .2s ease;
        }


        .btn-primary {

            background: #006975;

            color: white;

            box-shadow:
                0 8px 20px rgba(0, 105, 117, .20);
        }


        .btn-primary:hover {

            background: #005661;

            transform: translateY(-2px);

            box-shadow:
                0 12px 25px rgba(0, 105, 117, .28);
        }


        .btn-secondary {

            color: #006975;

            border: 1px solid #d5e1e3;

            background: white;
        }


        .btn-secondary:hover {

            background: #f1f7f8;
        }


        /* =========================================
           PIE
        ========================================= */

        .footer {

            position: absolute;

            bottom: 25px;

            left: 0;

            width: 100%;

            text-align: center;

            font-size: 12px;

            color: #9aa8ab;

            z-index: 5;
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 750px) {

            body {
                overflow: auto;
            }

            .page {
                min-height: 100vh;

                height: auto;

                padding: 60px 20px 90px;
            }

            .content {

                grid-template-columns: 1fr;

                gap: 10px;

                text-align: center;
            }

            .illustration {

                height: 260px;
            }

            .number {

                font-size: 150px;
            }

            .lock {

                transform: scale(.8);

                margin-top: 40px;
            }

            .text {

                text-align: center;
            }

            .brand {

                justify-content: center;
            }

            .actions {

                justify-content: center;

                flex-wrap: wrap;
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


        <!-- Decoración -->

        <div class="circle circle-one"></div>

        <div class="circle circle-two"></div>

        <div class="circle circle-three"></div>


        <!-- Contenido -->

        <div class="content">


            <!-- Ilustración -->

            <div class="illustration">

                <div class="number">
                    403
                </div>

                <div class="lock">

                    <div class="keyhole"></div>

                </div>

            </div>


            <!-- Mensaje -->

            <div class="text">

                <div class="brand">

                    <div class="brand-line"></div>

                    IURIS

                </div>


                <h1>
                    Acceso <span>restringido</span>
                </h1>


                <div class="code">
                    ERROR 403 · ACCESO DENEGADO
                </div>


                <p class="message">

                    Lo sentimos, no tienes los permisos necesarios
                    para acceder a esta sección del sistema.
                    Si consideras que se trata de un error,
                    comunícate con el administrador de IURIS.

                </p>


                <div class="actions">

                    <a
                        href="{{ $url }}"
                        class="btn btn-primary">

                        ← Regresar

                    </a>


                    <a
                        href="/dashboard/"
                        class="btn btn-secondary">

                        Ir al inicio

                    </a>

                </div>

            </div>

        </div>


        <!-- Footer -->

        <footer class="footer">


        <div>

            <strong>IURIS</strong>

            &nbsp;·&nbsp; Consultorios Jurídicos y Centro de Conciliación

            “Eduardo Alvarado Hurtado” &nbsp;·&nbsp;

   

           

            Universidad de Nariño

        </div>


    </footer>


    </div>

</body>

</html>