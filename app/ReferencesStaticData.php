<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferencesStaticData extends Model 
{ 
    protected $table="references_static_table"; //el modelo se va a relacionar con la tabla
    protected $fillable=['display_name','name','categories','table','section','is_visible','type_data_id'];//que campos tiene la


    public function getCategory(){
        if($this->categories=='conciliaciones'){
            return 'Conciliaciones';
        }else{
            return "Sin categoría";
        }  
    }
 
    public function options(){
        return $this->hasMany(ReferenceStaticDataOptions::class,'references_static_data_id','id'); 
    } 

}
