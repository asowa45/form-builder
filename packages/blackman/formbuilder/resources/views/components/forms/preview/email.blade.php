@php
$email = '';
if($tableName == 'contact_informations'){
$email = Auth::user()->email;
}
else {
$email = old($form_field->name);
}
@endphp
<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}</label>
        <br>
        @if($tableName == 'contact_informations')
            <input type="hidden" name="{{$form_field->name}}" value="{{$email}}">
        @endif
        <input type="email"
               @if($form_field->required == 1)  required @endif
               @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
               @if($form_field->disabled == 1)  disabled @endif
               placeholder="{{$form_field->placeholder}}"
               class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
               style="{{$form_field->inline_css}}"
               value="{{$email}}"
               name="{{$form_field->name}}"
               id="{{$form_field->name}}">
    </div>
    @if ($errors->has($form_field->name))
        <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($form_field->name) }}</strong>
                </span>
    @endif
</div>