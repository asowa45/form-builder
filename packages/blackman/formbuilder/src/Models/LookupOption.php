<?php

namespace FormBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class LookupOption extends Model
{
    //
    protected $fillable = ['name','code'];

    public function options()
    {
        return $this->hasMany('FormBuilder\Models\Option');
    }
}
