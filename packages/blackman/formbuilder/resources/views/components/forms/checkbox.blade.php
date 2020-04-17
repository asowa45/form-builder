<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label>{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif</label>
        <br>

        @if(isset($form_field->options))
            @php
                $options = json_decode($form_field->options);
            @endphp
            @foreach($options as $key=>$option)
                <div class="form-check form-check-inline">
                    <input class="form-check-input{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}" type="checkbox"
                           @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
                           style="{{$form_field->inline_css}}"

                           @if(( !empty($form_data) && empty($form_data['for_empty_request_id'])) && count($form_data) > 0 )
                               @php
                                   $documents = @unserialize($form_data[$form_field->name]);
                                   if (!empty($documents)) {
                                        foreach ($documents as $document) {
                                          echo ($document == $option->opt_value)? 'checked':'' ;
                                        }
                                   } else {
                                       echo ($form_data[$form_field->name] == $option->opt_value)? 'checked':'' ;
                                   }
                               @endphp
                           @else
                           {{($form_field->default_value == $option->opt_value)? 'checked':'coded'}}
                           @endif


                           name="{{$form_field->name}}@if($form_field->is_multiple == true)[]@endif"
                           @if($form_field->name == 'same_as_requestors') onchange="autofillAgent()" @endif
                           @if($form_field->name == 'same_as_traders') onchange="autofillTrader()" @endif
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