<!DOCTYPE html>
<html>
    <head>
        <title>Regresa</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #000;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 40px;
                margin-bottom: 40px;
            }
            
            .emsa {
                font-size: 20px;
                margin-bottom: 40px;
            }
            a{
                
                color: #85C1E9;
                
            }
        </style>
    </head>
    <body>
        @php
        if (!isset($url)) {
            $url = '/dashboard/';
        }
        @endphp
        <div class="container">
            <div class="content">
                <div class="title">Parece que te perdiste, <a href="{{$url}}">regresa!</a></div>
                <div class="emsa">AMATAI Ingeniería Informática</div>
            </div>
        </div>
    </body>
</html>
