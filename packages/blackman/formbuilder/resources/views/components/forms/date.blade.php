<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>
        <div class='input-group date datetimepicker'>
            <input type='date'
                   @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
                   @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
                   class="form-control {{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
                   @if($form_field->required == 1)  required @endif
                   @if($form_data != null && empty($form_data['for_empty_request_id']))
                      @if(!empty($form_data[$form_field->name]))
                        value="{{date('Y-m-d',strtotime($form_data[$form_field->name]))}}"
                      @else
                        value="{{date('Y-m-d')}}"
                      @endif
                   @else
                        value="{{date('Y-m-d')}}"
                   @endif
                   name="{{$form_field->name}}" id="{{$form_field->name}}"/>
            <div class="input-group-append">
            <span class="input-group-text">
              <span class="fa fa-calendar"></span>
            </span>
            </div>
        </div>
    </div>
{{--{{dd($form_data)}}--}}
    @if ($errors->has($form_field->name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($form_field->name) }}</strong>
        </span>
    @endif
</div>