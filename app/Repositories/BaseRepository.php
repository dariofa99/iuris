<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $validateSede;
    protected $model;
    private $relations;
    public $query;

    //protected $connection = 'mysql';
    public function __construct(Model $model, array $relations = [])
    {
        $this->model = $model;
        $this->relations = $relations;
        $this->validateSede = true;
    }

    protected function applyValidateSede($query){
        if($this->validateSede){
            $this->query = $query->whereHas('sedes',function($query1){
               $query1->where(['sede_id'=>session('sede')->id_sede]);                    
            });
        }
    }
    public function findWithFilter(array $filter) : ?Model {
        $this->query =   $this->model;  
        $this->applyValidateSede($this->query);   
        $this->validateFilter($filter);
        return $this->query->first();
    }
    protected function validateFilter(array $filter){
        foreach ($filter as $column => $value) {
            $date = \DateTime::createFromFormat('Y-m-d', $value);
           if ($date instanceof \DateTime) {
               $this->query =  $this->query->whereDate($column , $value);
           }else{
               $this->query = $this->query->where($column , $value);
           }
       } 
    }

    public function getWithFilter(Array $filter) : ?Collection {
        $this->query = $this->model;  
        $this->applyValidateSede($this->query);        
        return $this->query->where($filter)->get();
    }

    public function all()
    {
        $this->query = $this->model;
        if(!empty($this->relations)) {
            $this->query = $this->query->with($this->relations);
        }

        return $this->query->get();
    }

    public function find(int $id){   
        $this->query =   $this->model;  
        $this->applyValidateSede($this->query);  
        return $this->query->find($id);
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

    public function setValidateSede($validate)
    {
        $this->validateSede = $validate;
        return $this;
    }

    
   
}
