{{--@if($form_field->name == 'humanitarian_agencies')--}}
    {{--{{$form_field}}--}}
{{--@endif--}}
<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>
        <input type="text"
               @if($form_field->required == 1)  required @endif
               placeholder="{{$form_field->placeholder}}"
               @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
               class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }}"
               style="{{$form_field->inline_css}}"
               value="@if(old($form_field->name)){{old($form_field->name)}}@elseif(!empty($form_data)){{ !empty($form_data[$form_field->name]) ? $form_data[$form_field->name] : ""}}@else{{old($form_field->name)}}@endif"
               name="{{$form_field->name}}"
               id="{{$form_field->name}}">
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($form_field->name) }}</strong>
            </span>
        @endif
    </div>
</div>