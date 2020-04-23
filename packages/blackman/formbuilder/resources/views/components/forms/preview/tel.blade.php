<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}</label>
        <br>
        <input type="tel"
               @if($form_field->required == 1)  required @endif
               placeholder="{{$form_field->placeholder}}"
               @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
               @if($form_field->disabled == 1)  disabled @endif
               class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
               style="{{$form_field->inline_css}}"
               minlength="@if($form_field->min){{$form_field->min}}@else 10 @endif"
               maxlength="@if($form_field->max){{$form_field->max}}@else 15 @endif"
               value="{{$form_field->default_value}}"
               name="{{$form_field->name}}"
               id="{{$form_field->name}}">
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($form_field->name) }}</strong>
            </span>
        @endif
    </div>
</div>