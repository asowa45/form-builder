<?php
/**
 * Created by PhpStorm.
 * User: Sowee - Makedu
 * Date: 11/1/2018
 * Time: 10:17 AM
 */

namespace FormBuilder\Helpers;


class FormEntities
{

    public function getFieldTypes($type = null)
    {
        $labels = [
            'color'             => 'Color',
            'checkbox'          => 'Checkbox',
            'date'              => 'Date',
            'dateTime'          => 'DateTime',
            'wysiwyg'           => 'Description',
            'select'            => 'Dropdown',
            'email'             => 'Email',
            'file'              => 'File',
            'label'             => 'Label',
            'number'            => 'Number',
            'numeric_text'      => 'Numeric Text',
            'password'          => 'Password',
            'radio'             => 'Radio',
            'tel'               => 'Telephone',
            'textarea'          => 'Textarea',
            'text'              => 'Text',
            'time'              => 'Time',
            'button'            => 'Button',
        ];
        if ($type == null){
            return $labels;
        }else{
            return $labels[$type];
        }
    }

    public function getDefaultAttributes($type = null)
    {
        $labels = ['name','placeholder','class','min','max','required',
            'autocomplete','value','disabled'];
        if ($type == null){
            return $labels;
        }else{
            return $labels[$type];
        }
    }

    public function getFieldTypeAttribute($type = null)
    {
        $labels = [
            'dropdown'          =>  ['option','selected'],
            'checkbox'          =>  ['checked','option'],
            'radio'             =>  ['checked','option']
        ];
        if ($type == null){
            return $labels;
        }else{
            return $labels[$type];
        }
    }
}
