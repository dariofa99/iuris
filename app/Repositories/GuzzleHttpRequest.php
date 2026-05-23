<?php
namespace App\Repositories;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GuzzleHttpRequest{

    public $client;
    function __construct($url){      
       
        $this->client = new Client(
            [
            'base_uri'=>$url,
            'verify'=> false,
            'timeout' => 36000,
            'auth' => [ config('env'), config('env')],
            ]
        );
       
    }

    public function get($url, $request)
    {
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($request),
                'timeout' => 30, // Máximo tiempo de espera total (segundos)
                'connect_timeout' => 10, // Tiempo máximo para conectar al servidor
            ]);
    
            return json_decode($response->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Si hay un problema de conexión (servidor caído o no accesible)

            return (object) ['url' => url('/notfound'), 'error' => 'No se pudo conectar al servidor de chat.', 'details' => $e->getMessage()];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Si hay otro error en la solicitud
            Log::error('Error en la solicitud al servidor de chat: ' . $e->getMessage());
            return (object) ['url' => url('/notfound'), 'error' => 'Error en la solicitud al servidor de chat.', 'details' => $e->getMessage()];
        }
    }
 
    
    public function post($url,$request){
          $response = $this->client->request('POST', $url,['json' => $request]);
          return json_decode($response->getBody()->getContents());
      }
}

