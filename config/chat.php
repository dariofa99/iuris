<?php

return [
    "domain"=>"https://cjuridicos.udenar.edu.co",
    'connection' => [       
        'key' => env('CHAT_APP_KEY'),
        'password' => env('CHAT_APP_SECRET'),
        'code' => env('CHAT_APP_ID'),     
    ]
];