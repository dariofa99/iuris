<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $validateSede;
    protected $model;
    private $relations;

    //protected $connection = 'mysql';
    public function __construct(Model $model, array $relations = [])
    {
        $this->model = $model;
        $this->relations = $relations;
        $this->validateSede = true;
    }

    public function all()
    {
        $query = $this->model;
        if(!empty($this->relations)) {
            $query = $query->with($this->relations);
        }

        return $query->get();
    }

    public function find(int $id){   
       // $query =   $this->model;  
        return $this->model->whereHas('sedes',function($query1){
            if($this->validateSede)$query1->where(['sede_id'=>session('sede')->id_sede]);                    
        })->find($id);
    }



    public function save(Model $model)
    {
        $model->save();

        return $model;
    }

    public function delete(Model $model)
    {
        $model->delete();

        return $model;
    }

    public function setValidateSede($validate = true)
    {
        $this->validateSede = $validate;
        return $this;
    }
}
