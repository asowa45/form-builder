<div class="row">
    <div class="col-md-12">
        <div class="nav-vertical">
            {{--TABS--}}
            <ul class="nav nav-tabs nav-left nav-border-left" id="myTab" role="tablist">
                @php
                    $count = 0;
                @endphp
                @foreach($form_collectives_forms as $form_collectives_form)
                    @php
                        $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                        $count++;
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{($count == 1)?'active':''}} block" id="form_tab_{{$count}}" data-toggle="tab" href="#{{$form_info->slug}}" role="tab"
                           aria-controls="{{$form_info->slug}}" aria-selected="true">
                            {{$form_info->title}}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content" id="myTabContent">
                @php
                    $count = 0;
                    $editable = 0;
                @endphp
                @foreach($form_collectives_forms as $form_collectives_form)
                    @php
                        $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                        $count++;
                        $tableName = $form_info->table_name;
                    @endphp
                    <div class="tab-pane fade  {{($count == 1)?'show active':''}}" id="{{$form_info->slug}}" role="tabpanel"
                         aria-labelledby="form_tab_{{$count}}">
                        <div class="card-body">
                            @isset($form_info->description)
                                {!! $form_info->description !!}
                                <hr>
                            @endisset
                            @if($form_collective->submit_type == 'individual')
                                <form>
                                    @endif
                                    <div class="row">
                                        @foreach($form_info->fields as $form_field)
                                            @component('components.forms.preview.'.$form_field->input_type,['form_field'=>$form_field
                                            ,'tableName'=>$tableName,'editable'=>$editable])
                                            @endcomponent
                                        @endforeach
                                    </div>
                                    @if($form_collective->submit_type == 'individual')
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{--@foreach($form_fields as $form_field)--}}
    {{--@component('components.forms.'.$form_field->input_type,['form_field'=>$form_field])--}}
    {{--@endcomponent--}}
    {{--@endforeach--}}
</div>