<?php

namespace FormBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //

    protected $fillable = ['fullname','shortname','lookup_option_id'];

    public function lookup()
    {
        return $this->belongsTo('FormBuilder\Models\LookupOption');
    }
}
