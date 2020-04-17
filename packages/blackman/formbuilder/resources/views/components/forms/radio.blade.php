<div class="col-md-{{$form_field->column_size}}">
    {{--@if($form_field->name == 'humanitarian_assistances' || $form_field->name == 'humanitarian_agencies')--}}
    {{--{{dd($form_field)}}--}}
    {{--{{($form_data[$form_field->name] == $option->opt_value)? 'checked':''}}--}}
    {{--@endif--}}
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>
        @if(isset($form_field->options) || $form_field->option_forms->count() > 0)
            @php
            $checked = 'checked="checked"';
            if ($form_field->hasChild == 1){
                $options = json_decode($form_field->forms);
            }else{
                $options = json_decode($form_field->options);
            }
            @endphp
            @foreach($options as $key=>$option)
                @php $theId = $form_field->name.'_'.$key; @endphp
                <div class="form-check form-check-inline">
                    <input class="form-check-input{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}
                    @if(isset($option->c_form)) showSubForm_{{$subform_level}} @endif" type="radio"
                           @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
                           style="{{$form_field->inline_css}}"
                           name="{{$form_field->name}}"

                           @if(isset($form_data[$form_field->name]) && $form_data[$form_field->name] == $option->opt_value)
                           {{--{{($form_data[$form_field->name] == $option->opt_value)? 'checked':''}}--}}
                           {{$checked}}
                           @elseif(old($form_field->name) == $option->opt_value)
                           {{$checked}}
                           @elseif($form_field->default_value == $option->opt_value)
                           {{--@elseif(!empty($form_data['for_empty_request_id']) || $form_data == null)--}}
                           {{--{{($form_field->default_value == $option->opt_value)? 'checked':''}}--}}
                           {{$checked}}
                           @endif
                           {{--@php--}}
                               {{--if(isset($form_data[$form_field->name]) && $form_data[$form_field->name] == $option->opt_value){--}}
                                {{--echo 'checked="checked"';--}}
                               {{--}--}}
                               {{--elseif (old($form_field->name) == $option->opt_value){--}}
                                {{--echo 'checked="checked"';--}}
                               {{--}--}}
                               {{--elseif ($form_field->default_value == $option->opt_value){--}}
                                {{--echo 'checked="checked"';--}}
                               {{--}--}}
                           {{--@endphp--}}
                           onchange="@if(isset($option->c_form)) showSubForm_{{$subform_level}}({{$option->c_form}});@endif
                           @if($form_field->name == 'humanitarian_assistances')humanitarian(); @endif"
                           @if(isset($option->c_form)) data-subformid="{{$option->c_form}}" data-subformlevel='{{$subform_level}}'@endif
                           id="{{$form_field->name.'_'.$key}}"
                           @if($form_field->required == 1)  required @endif
                           onclick="@if($form_field->name == 'vessel_types')vessel_type(); @endif @if($form_field->name == 'type_of_cargos') cargo_vehicles(); @endif"
                           value="{{$option->opt_value}}">
                    <label class="form-check-label" for="{{$form_field->name.'_'.$key}}">{{$option->opt_name}}</label>

                    @if ($errors->has($form_field->name))
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($form_field->name) }}</strong>
                </span>
                    @endif
                </div>
            @endforeach
        @else
            No Options available
        @endif
    </div>
</div>