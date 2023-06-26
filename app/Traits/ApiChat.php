<?php
namespace App\Traits;

trait ApiChat{
    public function __construct(){
        parent::__construct();
    }

    
    public $room;



    public function image($image){        
        $this->image = $image;
        return $this;
    }
}