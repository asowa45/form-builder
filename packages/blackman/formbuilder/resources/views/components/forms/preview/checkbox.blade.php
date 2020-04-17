<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label>{{$form_field->label}}</label>
        <br>
        @if(isset($form_field->options))
            @php
                $options = json_decode($form_field->options);
            //dd($options);
            @endphp
            @foreach($options as $key=>$option)
                <div class="form-check form-check-inline">
                    <input class="form-check-input{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}" type="checkbox"
                           @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
                           style="{{$form_field->inline_css}}"
                           {{($form_field->default_value == $option->opt_value)? 'checked':''}}
                           name="{{$form_field->name}}@if($form_field->is_multiple == true)[]@endif"
                           id="{{$form_field->name.'_'.$key}}"
                           @if($form_field->required == 1)  required @endif
                           value="{{$option->opt_value}}">
                    <label class="form-check-label" for="{{$form_field->name.'_'.$key}}">{{$option->opt_name}}</label>
                </div>
            @endforeach
        @else
            No Options available
        @endif
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($form_field->name) }}</strong>
                </span>
        @endif
    </div>
</div>