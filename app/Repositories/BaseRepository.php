<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected $validateSede;
    protected $withLike;
    public $model;
    private $relations;
    public $query;

    //protected $connection = 'mysql';
    public function __construct(Model $model, array $relations = [])
    {
        $this->model = $model;
        $this->relations = $relations;
        $this->validateSede = true;
        $this->withLike = false;
        $this->query = $model;
    }

    protected function applyValidateSede()
    {
        if($this->model!=null)$this->query = $this->model;
        if ($this->validateSede and method_exists($this->model, 'sedes')) {
            $this->query = $this->query->whereHas('sedes', function ($query1) {
                $query1->where(['sede_id' => session('sede')->id_sede]);
            });
        }
    }
    public function findWithFilter(array $filter): ?Model
    {
       // $this->query = $this->model;
        $this->applyValidateSede();
        $this->validateFilter($filter);
        return $this->query->first();
    }
    protected function validateFilter(array $filter)
    {
        foreach ($filter as $column => $value) {
            $date = \DateTime::createFromFormat('Y-m-d', $value);
            if ($date instanceof \DateTime) {
                if ($this->withLike) {
                    $this->query =  $this->query->whereDate($column, "like", "%" . $value . "%");
                } else {
                    $this->query =  $this->query->whereDate($column, "=", $value);
                }
            } else {
                if ($this->withLike) {
                    $this->query =  $this->query->where($column, "like", "%" . $value . "%");
                } else {
                    $this->query = $this->query->where($column, "=", $value);
                }
            }
        }
    }

    public function getWithFilter(array $filter): ?Collection
    {
        //$this->query = $this->model;
        $this->applyValidateSede();
        $this->validateFilter($filter);
        return $this->query->get();
    }

    public function all()
    {
        $this->applyValidateSede();
        if (!empty($this->relations)) {
            $this->query = $this->query->with($this->relations);
        }
        return $this->query->get();
    }

    public function find(int $id)
    {
        $this->applyValidateSede();
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

    public function setValidateSede(bool $validate)
    {
        $this->validateSede = $validate;
        return $this;
    }

    public function setValidateWithLike($validate)
    {
        $this->withLike = $validate;
        return $this;
    }

    public function setRelations($relations = [])
    {
        $this->relations = $relations;
        return $this;
    }
}
