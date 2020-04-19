<?php

namespace FormBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class FormCollective extends Model
{
    //
    protected $fillable = ['form_id','structure_type','submit_type','process_type','active','user_id'];

    public function forms()
    {
        return $this->hasMany('FormBuilder\Models\Form','form_id');
    }

    public function collective_form()
    {
        return $this->hasOne('FormBuilder\Models\FormCollectivesForm','form_collective_id');
    }

    public function form_collectives_forms()
    {
        return $this->hasMany('FormBuilder\Models\FormCollectivesForm','form_collective_id');
    }

    public function form()
    {
        return $this->belongsTo('FormBuilder\Models\Form','form_id');
    }
}
