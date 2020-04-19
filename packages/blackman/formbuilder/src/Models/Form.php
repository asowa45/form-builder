<?php

namespace FormBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Form extends Model
{
    protected $fillable = ['title','slug','table_name','description','step','active','collective', 'workflow'];

    public function fields()
    {
        return $this->hasMany('FormBuilder\Models\Field');
    }
    public function sub_forms()
    {
        return $this->hasMany('FormBuilder\Models\FormFieldChildren','parent_form_id');
    }
//
    public function setTableNameAttribute($value)
    {
        $this->attributes['table_name'] = Str::snake(Str::plural($value));
    }
//
    public function scopeActiveForms($query)
    {
        return $query->where('active', '=', 1)->where('collective', '=', 0);
    }
//
    public function scopeCollectives($query)
    {
        return $query->where('collective', '=', 1);
    }
//
    public function scopeActive($query)
    {
        return $query->where('active', '=', 1);
    }
//
    public function scopeActiveCollectives($query)
    {
        return $query->where('collective', '=', 1)->where('active', '=', 1);
    }
//
    public function form_collective()
    {
        return $this->hasOne('FormBuilder\Models\FormCollective','form_id');
    }

    public function form_collectives()
    {
        return $this->belongsToMany('FormBuilder\Models\FormCollective','form_id');
    }

    public function form_collectives_forms()
    {
        return $this->hasMany('FormBuilder\Models\FormCollectivesForm','form_id');
    }

}
