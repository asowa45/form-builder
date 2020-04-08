<?php
/**
 * Created by PhpStorm.
 * User: mantey
 * Date: 08/04/2020
 * Time: 12:28 PM
 */

namespace FormBuilder\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Str;


class FormFieldChildren extends Model
{
    protected $table = 'form_field_children';
    protected $fillable = ['field_id','form_id','parent_form_id','name','value'];

    public function form()
    {
        return $this->belongsTo('FormBuilder\Models\Form','form_id');
    }

    public function parent_form()
    {
        return $this->belongsTo('FormBuilder\Models\Form','parent_form_id');
    }
}