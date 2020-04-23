<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs nav-top-border" id="myTab" role="tablist">

            {{--ADDS THE COVER PAGE FORM OR PARENT FORM AS FIRST TAB--}}
            @if($form_collective->cover_page == 1)
                @php
                    $count = 0;
                @endphp
                <li class="nav-item">
                    <a class="nav-link active" id="form_tab_{{$count}}"
                       data-toggle="tab" href="#{{$form->slug}}" role="tab"
                       aria-controls="{{$form->slug}}" aria-selected="true">
                        Important Note
                    </a>
                </li>
            @endif

            {{--LOOPS THROUGH THE VARIOUS FORMS--}}
            @php $count = 0; @endphp
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form_collectives_form->form;
                    $count++;
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{($form_collective->cover_page == 0 && $count == 1)?'active':''}}" id="form_tab_{{$count}}"
                       data-toggle="tab" href="#{{$form_info->slug}}" role="tab"
                       aria-controls="{{$form_info->slug}}" aria-selected="true">
                        {{$form_info->title}}
                    </a>
                </li>
            @endforeach
        </ul>

        {{--TABS CONTENT--}}
        <div class="tab-content" id="myTabContent">
            @if($form_collective->cover_page == 1)
                @php
                    $count = 0;
                @endphp
                <div class="tab-pane fade show active" id="{{$form->slug}}" role="tabpanel"
                     aria-labelledby="form_tab_{{$count}}">
                    <div class="card-body">
                        @isset($form->description)
                            {!! $form->description !!}
                        @endisset
                    </div>
                </div>
            @endif
            @php $count = 0; @endphp
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $count++;
                    $form_info = $form_collectives_form->form;
                @endphp
                <div class="tab-pane fade  {{($form_collective->cover_page == 0 && $count == 1)?'show active':''}}" id="{{$form_info->slug}}" role="tabpanel"
                     aria-labelledby="form_tab_{{$count}}">
                    <div class="card-body">
                        @isset($form_info->description)
                            {!! $form_info->description !!}
                            <hr>
                        @endisset
                        @if($form_collective->submit_type == 'individual')
                            <form action="#">
                                <input type="hidden" name="form_id" value="{{$form_info->id}}">
                                <input type="hidden" name="step" value="{{$count}}">
                                <input type="hidden" name="collective_id" value="{{$form_collective->id}}">
                                @endif
                                <div class="row">
                                    @php $subform_level = 0;@endphp
                                    @foreach($form_info->fields->sortBy('order') as $form_field)
                                        @component('formbuilder::components.forms.preview.'.$form_field->input_type,['form_field'=>$form_field])
                                        @endcomponent
                                        @if($form_info->sub_forms)
                                            @foreach($form_info->sub_forms->where('field_id','=',$form_field->id)->sortBy('order') as $subform)
                                                @component('formbuilder::components.forms.preview.sub_form.child_form',['subform'=>$subform,
                                                'form_fields'=>$form_fields,'subform_level'=>$subform_level])
                                                @endcomponent
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>
                                @if($form_collective->submit_type === 'individual')
                                    <button type="button" class="btn btn-success mt-2 mb-3">Save</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
