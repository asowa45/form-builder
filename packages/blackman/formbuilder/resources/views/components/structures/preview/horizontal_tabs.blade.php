<div class="row">
    <div class="col-md-12">
        @php
            $parent_table = \Illuminate\Support\Str::plural($form->table_name);
        @endphp
        <ul class="nav nav-tabs nav-top-border" id="myTab" role="tablist">
            @php
                $count = 1;
            @endphp
            @if($form_collective->cover_page == 1)
                <li class="nav-item">
                    <a class="nav-link active" id="form_tab_{{$count}}" data-toggle="tab" href="#{{$form->slug}}" role="tab"
                       aria-controls="{{$form->slug}}" aria-selected="true">
                        Important Note
                    </a>
                </li>
                @php
                    $count = 0;
                @endphp
            @endif
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
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
                $count = 1;
                $editable = 1;
            @endphp
            @if($form_collective->cover_page == 1)
                <div class="tab-pane fade show active" id="{{$form->slug}}" role="tabpanel"
                     aria-labelledby="form_tab_{{$count}}">
                    <div class="card-body">
                        @isset($form->description)
                            {!! $form->description !!}
                        @endisset
                    </div>
                </div>
                @php
                    $count = 0;
                @endphp
            @endif

            {{--LOOPS THROUGH THE FORMS BELONGING TO THE COLLECTIVE--}}
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();

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
                            @php
                                $subform_level = 0;
                            @endphp
                            @foreach($form_info->fields->sortBy('order') as $form_field)
                                @component('components.forms.preview.'.$form_field->input_type,['form_field'=>$form_field,
                                'editable'=>$editable,'subform_level'=>$subform_level,'tableName'=>$tableName])
                                @endcomponent
                                @if($form_info->sub_forms)
                                    @foreach($form_info->sub_forms->sortBy('order') as $subform)
                                        @php
                                            $form = $subform->form;
                                            $form_fields = $subform->form->fields;
                                            $form_table = \Illuminate\Support\Str::plural($form->table_name);
                                            $tableName = \Illuminate\Support\Str::plural($parent_table."_sub_".$form_table);
                                        @endphp
                                        @component('components.forms.preview.sub_form.child_form',['subform'=>$subform,
                                        'subform_level'=>$subform_level,'parent_table'=>$parent_table,
                                        'form_field'=>$subform->form->fields,'editable'=>$editable,'tableName'=>$tableName])
                                        @endcomponent
                                    @endforeach
                                @endif
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