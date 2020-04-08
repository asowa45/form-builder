<?php

namespace FormBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Field extends Model {
    protected $fillable = ['form_id', 'input_type', 'name', 'label', 'order', 'rules', 'disabled', 'step', 'auto_options',
        'options', 'data_type', 'placeholder', 'class', 'inline_css', 'file_types', 'column_size', 'forms', 'description',
        'min', 'max', 'default_value', 'required', 'autocomplete', 'checked', 'file_size', 'showBy', 'hasChild','attributes',
        'has_auto_options', 'workflow_actors', 'is_dropdown_button', 'is_multiple', 'button_dropdown_options', 'button_url'];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'forms' => 'array',
        'button_dropdown_options' => 'array',
        'rules' => 'array',
        'file_types' => 'array',
    ];

    public function form() {
        return $this->belongsTo('FormBuilder\Models\Form');
    }

    public function option_forms() {
        return $this->hasMany('FormBuilder\Models\FormFieldChildren', 'field_id');
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = strtolower(Str::snake(Str::plural($value)));
    }

}
