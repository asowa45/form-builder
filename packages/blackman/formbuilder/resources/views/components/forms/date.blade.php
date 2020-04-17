<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>
        {{--<input type="date"--}}
               {{--@if($form_field->required == 1)  required @endif--}}
               {{--@if($form_field->autocomplete == 1)  autocomplete="yes" @endif--}}
               {{--@if($form_field->disabled == 1 || $editable == 0)  disabled @endif--}}
               {{--class="form-control {{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"--}}
               {{--value="@if($form_data != null) {{$form_data[$form_field->default_value]}} @else {{old($form_field->name)}} @endif"--}}
               {{--name="{{$form_field->name}}" id="{{$form_field->name}}" style="{{$form_field->inline_css}}">--}}
        <div class='input-group date datetimepicker'>
            <input type='text'
                   @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
                   @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
                   class="form-control {{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
                   @if($form_field->required == 1)  required @endif
                   @if($form_data != null && empty($form_data['for_empty_request_id']))
                      @if(!empty($form_data[$form_field->name]))
                        value="{{date('d-m-Y H:i',strtotime($form_data[$form_field->name]))}}"
                      @else
                        value="{{date('d-m-Y H:i')}}"
                      @endif
                   @else
                        value="{{date('d-m-Y H:i')}}"
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