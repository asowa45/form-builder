<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>
        <select
                @if(isset($form_field->options) || $form_field->option_forms->count() > 0)
                @php
                    if ($form_field->hasChild == 1){
                        $options = json_decode($form_field->forms);
                    }else{
                        $options = json_decode($form_field->options);
                    }
                @endphp
               @if($form_field->required == 1)  required @endif
               @if($form_field->name == 'description_of_cargos')  onchange="description_of_cargos()"
                @elseif($form_field->hasChild == 1) onchange="showSubFormSelect_{{$subform_level}}('{{$form_field->name}}')" @endif
               @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
               class="form-control select2 {{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
               {{--value="{{old($form_field->name)}}"--}}
               style="width: 100%; {{$form_field->inline_css}}"
                name="{{$form_field->name}}@if($form_field->is_multiple == true)[]@endif"
               id="{{$form_field->name}}">
                <option value="">--Choose--</option>
                @isset($form_field->options)
                    @foreach($options as $key=>$option)
                        @php
                            $value = $option->opt_value;
                            if($form_field->hasChild == 1){
                                $value = $option->c_form;
                            }
                        @endphp
                        <option
                            @if(old($form_field->name) == $option->opt_value)
                            @elseif(!empty($form_data[$form_field->name]))
                            {{($form_data[$form_field->name] == $option->opt_value)?'selected':''}}
                            @elseif((!empty($form_data['for_empty_request_id']) || $form_data == null) && ($form_field->default_value == $option->opt_value))
                            selected
                            @endif value="{{$value}}">{{$option->opt_name}}</option>
                    @endforeach
                @endisset
            @else
                No Options available
            @endif

        </select>
        @if ($errors->has($form_field->name))
            <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($form_field->name) }}</strong>
                </span>
        @endif
    </div>
</div>