<?php
namespace App\Traits;

use App\ReferencesData;
use App\ReferencesStaticData;

/**
 * 
 */
trait RefDataManage
{
    public function getDataVal($ref_id, $ref_option)
    {
        $ref_data = $this->aditional_data()
            ->where([
                'reference_data_id' => $ref_id,
                'reference_data_option_id' => $ref_option
            ])->first();

        if ($ref_data) {
            return $ref_data;
        }
        return false; 
    }

    
    public function getStaticDataValByShortName($name,$section,$option_id=null){
        $ref_data = ReferencesData::where(
            ['short_name'=>$name,
            'section'=>$section,
            'table'=>$this->getTable(),
            ])->first();
        //return $ref_data;

         if ($ref_data) {           
            $data = $this->aditional_static_data()
            ->where([
                'reference_data_id'=>$ref_data->id,
                //'reference_data_option_id'=>$option_id == null ? $ref_data->options[0]->id : $option_id
                    ])->first();
            if($data){               
                return $data;
            }
        }
        ;
       return false;
    }

    public function getStaticDataLabel($name,$section){
        $ref_data = ReferencesStaticData::where(['name'=>$name,'section'=>$section])->first();
      //dd( $ref_data->options[0]);
        if ($ref_data) {  
                $ref_data->options ;//= $ref_data->options;     
                return $ref_data;            
        }
       return false; 
    }

    
     public function getAdDataByQuestion($question)
    {
        return $this->aditional_data()
            ->whereHas('pregunta', function ($q) use ($question) {
                $q->where('short_name', $question);
            })
            ->with('pregunta','opcion');
    }

}