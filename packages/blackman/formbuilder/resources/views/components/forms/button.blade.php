<div class="col-md-{{$form_field->column_size}}">

    @if($form_field->is_dropdown_button == 1)

        <div class="form-group">
            <div class="btn-group" style="{{$form_field->inline_css}}">
            <button type="button" class="btn btn-success btn-block dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">{{$form_field->label}}</button>

            <div class="dropdown-menu" x-placement="bottom-start">
                @php
                    $dropdown_options_fields = json_decode($form_field->button_dropdown_options);
                @endphp

                @if(!empty($dropdown_options_fields))
                    @foreach($dropdown_options_fields as $key=>$option)
                        <a class="dropdown-item" target="_blank" href="{{ route('workflow.download', ["value" => $option->opt_value, "download_value" => $option->opt_value])  }}">{{$option->opt_name}}</a>
                    @endforeach
                @endif
            </div>
            </div>
        </div>

    @else

        <div class="form-group" style="{{$form_field->inline_css}}">
            @php
                $button_url = $form_field->button_url;
                $request_id = (!empty($form_data['for_empty_request_id'])) ? $form_data['for_empty_request_id'] : $form_data['request_id'];
                //$request_id = $request_id;

                if (strpos($button_url, "[request_id]") !== false) {
                    $button_url = str_replace("[request_id]",$request_id, $form_field->button_url);
                }

                if (strpos($button_url, "[domain_name]") !== false) {
                    $button_url = str_replace("[domain_name]",\Illuminate\Support\Facades\URL::to('/'), $button_url);
                }
            @endphp


            <a class="btn btn-primary btn-block" target="_blank"
               href="{{(!empty($button_url) ? $button_url : '#')}}">{{$form_field->label}}</a>
        </div>

    @endif


</div>