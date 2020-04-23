<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>

        <input type="text"
               @if($form_field->required == 1)  required @endif
               placeholder="{{$form_field->placeholder}}"
               @if($form_field->autocomplete == 1)  autocomplete="off" @endif
               @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
               class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }}
                {{$form_field->class}} @if($form_field->autocomplete >= 1) {{$form_field->auto_options}} @endif
                @if($form_field->has_auto_options > 1) tagsInput auto_multi @endif"
               @if($form_field->has_auto_options > 1) data-role="tagsinput" @endif
               style="{{$form_field->inline_css}}"
                   value="{{old($form_field->name,isset($form_data[$form_field->name])?$form_data[$form_field->name]:$form_field->default_value)}}"
               name="{{$form_field->name}}"
               id="{{$form_field->name}}">
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($form_field->name) }}</strong>
            </span>
        @endif
    </div>
</div>
