<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;
use App\Permission;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Role extends EntrustRole
{
    use EntrustUserTrait;
    
    protected $table = 'roles';

     protected $fillable = [
        'name', 'display_name', 'description'
    ];

    public function permissions(){
       return $this->belongsToMany('App\Permission','permission_role')
       ->withPivot('permission_id','role_id');
    }
    
}
