<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>
        @if($editable == 0 && ($form_data != null && empty($form_data['for_empty_request_id'])))
            <input type="text" class="form-control" value="+{{$form_data[$form_field->name]}}" disabled="disabled">
        @else
        <div class='input-group forTel'>
            <div class="input-group-prepend">
                <span class="input-group-text">
                  +
                </span>
            </div>
        <input type="tel"
               @if($form_field->required == 1)  required @endif
               placeholder="{{$form_field->placeholder}}"
               @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
               @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
               class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
               style="{{$form_field->inline_css}}"
               minlength="@if($form_field->min){{$form_field->min}}@else 10 @endif"
               maxlength="@if($form_field->max){{$form_field->max}}@else 15 @endif"
               {{--value="{{old($form_field->name)}}"--}}
               @if($form_table == "contact_informations" && !isset($form_data[$form_field->name]))
               @php
                   $val = session('request_placeholder_info');
               @endphp
               value = "{{$val[$form_field->name]}}"
               @else
               value="@if($form_data != null && empty($form_data['for_empty_request_id'])){{$form_data[$form_field->name]}}@else{{old($form_field->name)}}@endif"
               @endif
               name="{{$form_field->name}}"
               id="{{$form_field->name}}">
        </div>
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($form_field->name) }}</strong>
            </span>
        @endif
        @endif
    </div>
</div>