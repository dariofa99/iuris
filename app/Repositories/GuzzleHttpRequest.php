<?php
namespace App\Repositories;

use GuzzleHttp\Client;

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
                'timeout' => 1, // Máximo tiempo de espera total (segundos)
                'connect_timeout' => 1, // Tiempo máximo para conectar al servidor
            ]);
    
            return json_decode($response->getBody()->getContents());
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Si hay un problema de conexión (servidor caído o no accesible)
            return (object) ['url' => url('/notfound')];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Si hay otro error en la solicitud
            return (object) ['url' => url('/notfound')];
        }
    }
 
    
    public function post($url,$request){
          $response = $this->client->request('POST', $url,['json' => $request]);
          return json_decode($response->getBody()->getContents());
      }
}

