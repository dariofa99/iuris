<?php
namespace App\Repositories;

use GuzzleHttp\Client;

class GuzzleHttpRequest{

    public $client;
    function __construct($url){
        
        $this->client = new Client(
            ['base_uri'=>$url,
            'verify'=> false,
            'timeout' => 36000,
            'auth' => [ config('env'), config('env')],
            ]
        );
       
    }

    public function get($url,$request){
       //dd($request);
        //$response = $this->client->request('GET', $url,['json' => $request]);
        try {
            $response = $this->client->request('GET', $url, [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($request),
            ]);
        
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            // Manejar el error
            dd($e->getMessage());
        }
    }
 
    
    public function post($url,$request){
          $response = $this->client->request('POST', $url,['json' => $request]);
          return json_decode($response->getBody()->getContents());
      }
}

