<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class UserAditionalData extends Model
{
    protected $table = 'user_aditional_data';
    protected $fillable = ['value',
    'value_is_other',
    'reference_data_id',
    'reference_data_option_id',
    'user_id'];


    public function reference()
    {
       return $this->belongsTo(ReferencesData::class,'reference_data_id','id');
    } 

    public static function boot() {
	    parent::boot();
	    static::created(function($item) {
	        Event::dispatch('adduserdata.created', $item);
	    });
	    static::updated(function($item) {
            Event::dispatch('adduserdata.updated', $item);
	    });
	    static::deleted(function($item) {
	        Event::dispatch('adduserdata.deleted', $item);
	    });
    }

}
