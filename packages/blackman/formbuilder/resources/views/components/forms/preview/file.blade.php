<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}" style="font-weight: 600;">{{$form_field->label}}</label>
        <br>
        <input type="file"
               @if($form_field->required == 1)  required @endif
               @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
               @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
               class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
               style="{{$form_field->inline_css}}"
               name="{{$form_field->name}}"
               id="{{$form_field->name}}">
        <small class="form-text text-muted">
            The file must be of type
            @foreach($form_field->file_types as $key=>$file_type)
                {{strtoupper($file_type)}},
            @endforeach
            @if($form_field->file_size > 0)
                and must be up to {{$form_field->file_size}}Mb
            @endif
        </small>
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($form_field->name) }}</strong>
                </span>
        @endif
    </div>
    @if($form_field->column_size == 12)
        <hr>
    @endif
</div>