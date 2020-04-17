<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}</label>
        <br>
        <input type="number"
               @if($form_field->required == 1)  required @endif
               @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
               @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
               placeholder="{{$form_field->placeholder}}"
               @isset($form_field->min)  min="{{$form_field->min}}" @endif
               @isset($form_field->max)  max="{{$form_field->max}}" @endif
               class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
               style="{{$form_field->inline_css}}"
               @php
               $num_value = $form_field->default_value;
               @endphp
               value="{{$num_value}}"
               step="{{$form_field->step}}"
               name="{{$form_field->name}}"
               id="{{$form_field->name}}">
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($form_field->name) }}</strong>
                </span>
        @endif
    </div>
</div>