<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @php
                $count = 0;
            @endphp
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                    $count++;
                @endphp
            <li class="nav-item">
                <a class="nav-link {{($count == 1)?'active':''}}" id="form_tab_{{$count}}" data-toggle="tab" href="#{{$form_info->slug}}" role="tab"
                   aria-controls="{{$form_info->slug}}" aria-selected="true">
                    {{$form_info->title}}
                </a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content" id="myTabContent">
            @php
                $count = 0;
            @endphp
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                    $count++;
                @endphp
            <div class="tab-pane fade  {{($count == 1)?'show active':''}}" id="{{$form_info->slug}}" role="tabpanel"
                 aria-labelledby="form_tab_{{$count}}">
                <div class="card-body">
                    @isset($form_info->description)
                        <p>{{$form_info->description}}</p>
                        <hr>
                    @endisset
                <div class="row">
                    @foreach($form_info->fields as $form_field)
                        @component('components.forms.'.$form_field->input_type,['form_field'=>$form_field])
                        @endcomponent
                    @endforeach
                </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    {{--@foreach($form_fields as $form_field)--}}
    {{--@component('components.forms.'.$form_field->input_type,['form_field'=>$form_field])--}}
    {{--@endcomponent--}}
    {{--@endforeach--}}
</div>