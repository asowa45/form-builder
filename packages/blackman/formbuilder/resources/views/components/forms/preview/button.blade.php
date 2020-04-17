<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">

        <br>
        <div class="btn-group" style="{{$form_field->inline_css}}">

            {{--@if($form_field->is_dropdown_button == 1)--}}
                {{--<button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown"--}}
                        {{--aria-haspopup="true" aria-expanded="false">{{$form_field->label}}</button>--}}

                {{--<div class="dropdown-menu" x-placement="bottom-start">--}}
                    {{--@php--}}
                        {{--$dropdown_options_fields = json_decode($form_field->button_dropdown_options);--}}
                    {{--@endphp--}}

                    {{--@if(!empty($dropdown_options_fields))--}}
                        {{--@foreach($dropdown_options_fields as $key=>$option)--}}
                            {{--<a class="dropdown-item" target="_blank" href="{{ route('workflow.download', ["value" => $option->opt_value, "download_value" => $option->opt_value])  }}">{{$option->opt_name}}</a>--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--</div>--}}

            {{--@else--}}
                <a class="btn btn-success btn-block" target="_blank"
                   href="{{(!empty($form_field->button_url) ? $form_field->button_url : '#')}}">{{$form_field->label}}</a>
            {{--@endif--}}

        </div>


    </div>
</div>