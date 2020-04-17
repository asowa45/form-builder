<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>
        @if(isset($form_field->options) || $form_field->option_forms->count() > 0)
            @php
                if ($form_field->hasChild == 1){
                $options = json_decode($form_field->forms);
                }
                else{
                    $options = json_decode($form_field->options);
                }
            @endphp
            @foreach($options as $key=>$option)
                <div class="form-check form-check-inline">
                    <input class="form-check-input{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}" type="radio"
                           @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
                           style="{{$form_field->inline_css}}"
                           name="{{$form_field->name}}"
                           {{($form_field->default_value == $option->opt_value)? 'checked':''}}
                           @isset($option->c_form)  onclick="showSubForm_{{$subform_level}}({{$option->c_form}})" @endisset
                           id="{{$form_field->name.'_'.$key}}"
                           @if($form_field->required == 1)  required @endif
                           value="{{$option->opt_value}}">
                    <label class="form-check-label" for="{{$form_field->name.'_'.$key}}">{{$option->opt_name}}</label>
                </div>
            @endforeach
        @else
            No Options available
        @endif
    </div>
</div>