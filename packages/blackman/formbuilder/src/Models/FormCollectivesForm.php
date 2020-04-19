<?php

namespace FormBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class FormCollectivesForm extends Model
{
    //
    protected $fillable = ['form_collective_id','form_id','order','active'];

    public function form()
    {
        return $this->belongsTo('FormBuilder\Models\Form','form_id');
    }

    public function forms()
    {
        return $this->hasMany('FormBuilder\Models\Form','form_id','id');
    }

    public function form_collective()
    {
        return $this->belongsTo('FormBuilder\Models\FormCollective','form_collective_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActiveForms($query)
    {
        return $query->where('active','=',1);
    }

    public function scopeIdOrder($query)
    {
        return $query->orderBy('order','asc');
    }
}
