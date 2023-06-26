<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AuditLog extends Model
{
    protected $table="audit_log"; //el modelo se va a relacionar con la tabla
    protected $fillable=['user_name','user_id','event','model_description','table_name'];//que campos tiene la
    protected $event;
    protected $table_n;
    protected $description;
    protected $item = null;

    public function store(){
        $this->create([
            'user_name'=>auth()->user() ? auth()->user()->name ." ".auth()->user()->lastname : 'Sin iniciar sesión',
            'user_id'=>auth()->user() ? auth()->user()->id : 'Sin iniciar sesión',
            'event'=>$this->event,
            'model_description'=> ($this->event != 'updated') ? $this->getModelDescription() : $this->getModelUpdateDescription() ,
            'table_name'=> $this->getTableName(),
        ]); 
      //  DB::table('audit_log')->insert();
        return true;
        
     }      
     public function setEvent($event){        
        $this->event = $event; 
      return $this;        
     }

     public function setItem($item){
        $this->item = $item; 
      return $this;        
   }
   public function getItem(){
     return $this->item;        
 }

    public function setTable($table_name){
      $this->table_n = $table_name; 
      return $this;        
    }

    public function setModelDescription($description){
      $this->description = ($description); 
      return $this;        
    }

    public function getTableName(){
        $table_ = $this->table_n;
        if($table_ == null ){
          $table_ = 'undefined_table';
        }   
        return $table_;        
    }

    public function getModelDescription(){
      //$description =  $this->description;
      if($this->description == null ){
        $this->description = '{"information":"undefinded"}';      }  
      return $this->description;        
  }


  public function getModelUpdateDescription(){
    if($this->item!=null){
      if($this->item->id and $this->item->id!=null){
        $description = [
          'item_id'=>$this->item->id,
          'description' =>json_decode($this->description)
        ];
      }else{
        $description = [
          'item_id'=>'id is not defined',
          'description'=>json_decode($this->description)
        ];
      } 
      $this->description = $description ;    
    }
   
    if($this->description == null ){
      $this->description = '{"information":"undefinded"}';
    }  
    return json_encode($this->description);        
}

      
       
       

    
    

}