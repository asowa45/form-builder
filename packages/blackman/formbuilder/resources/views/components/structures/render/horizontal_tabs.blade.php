<div class="row">
    <div class="col-md-12">
        @php
            $parent_table = \Illuminate\Support\Str::plural($form->table_name);
            $complete = 0;
            $cid = "";
            $total_forms = $form_collectives_forms->count();
            if ($request){
                $complete = $request->status;
                $cid = $request->CID;
            }
            $active_step = $step;
        $forCargo = "";
        @endphp
        {{--TABS--}}
        <ul class="nav nav-tabs nav-top-border {{$active_step}}" id="myTab" role="tablist">
            @php
                $count = 1;
            @endphp

            {{--ADDS THE COVER PAGE FORM OR PARENT FORM AS FIRST TAB--}}
            @if($form_collective->cover_page == 1)
                @php
                    $count = 0;
                @endphp
                <li class="nav-item">
                    <a class="nav-link {{($count == $active_step)?'active':''}}" id="form_tab_{{$count}}"
                       data-toggle="tab" href="#{{$form->slug}}" role="tab"
                       aria-controls="{{$form->slug}}" aria-selected="true">
                        Important Note
                    </a>
                </li>
            @endif

            {{--LOOPS THROUGH THE VARIOUS FORMS--}}
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                @endphp
                @if($cargo_info && ($form_info->table_name == 'importers' || $form_info->table_name == 'notify_party'))
                    {{--@php--}}
                        {{--$count +=2;--}}
                    {{--@endphp--}}
                    @continue
                @endif
                @php $count++; @endphp
                <li class="nav-item">
                    <a class="nav-link {{($count == $active_step)?'active':''}}" id="form_tab_{{$count}}" data-toggle="tab" href="#{{$form_info->slug}}" role="tab"
                       aria-controls="{{$form_info->slug}}" aria-selected="true">
                        {{$form_info->title}}
                    </a>
                </li>
            @endforeach
        </ul>

        {{--TABS CONTENT--}}
        <div class="tab-content" id="myTabContent">
            @php $count = 1; @endphp
            @if($form_collective->cover_page == 1)
                @php
                    $count = 0;
                @endphp
                <div class="tab-pane fade {{($count == $active_step)?'show active':''}}" id="{{$form->slug}}" role="tabpanel"
                     aria-labelledby="form_tab_{{$count}}">
                    <div class="card-body">
                        @isset($form->description)
                            {!! $form->description !!}
                        @endisset
                    </div>
                </div>
            @endif
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                    $form_table = \Illuminate\Support\Str::plural($form_info->table_name);
                    $tableName = \Illuminate\Support\Str::plural($parent_table."_".$form_table);
                    if (isset($request_id)){
                        $form_data = \Illuminate\Support\Facades\DB::table($tableName)
                        ->where('clearance_request_id',\Illuminate\Support\Facades\Crypt::decrypt($request_id))
                        ->first();
                        $form_data = (array)$form_data;
                    }else{
                        $form_data = null;
                    }

                    if (!isset($form_data)){
                        $form_data = null;
                    }

                @endphp
                @php $count++; @endphp
                @if($cargo_info && ($form_table == 'importers' || $form_table == 'notify_party'))
                    @continue
                @endif
                <div class="tab-pane fade  {{($count == $active_step)?'show active':''}}" id="{{$form_info->slug}}" role="tabpanel"
                     aria-labelledby="form_tab_{{$count}}">
                    <div class="card-body">
                        @isset($form_info->description)
                            {!! $form_info->description !!}
                            <hr>
                        @endisset
                        @if($form_collective->submit_type == 'individual' && $editable == 1)
                            <form action="{{($submit_url == null)?route('submit_form'): $submit_url}}"
                                  @if($contain_file > 0) enctype="multipart/form-data" @endif
                                  method="post" id="{{$form_info->table_name}}">@csrf
                                <input type="hidden" name="form_id" value="{{$form_info->id}}">
                                <input type="hidden" name="request_id" value="@if($request_id != null) {{$request_id }} @endif">
                                <input type="hidden" name="step" value="{{$count}}">
                                <input type="hidden" name="collective_id" value="{{$form_collective->id}}">
                        @endif
                        <div class="row">
                            @php $subform_level = 0;@endphp
                            @foreach($form_info->fields->sortBy('order') as $form_field)
                                @component('formbuilder::components.forms.'.$form_field->input_type,['form_field'=>$form_field,'tableName'=>$tableName,'cid'=>$cid,
                                'form_data'=>$form_data,'form_table'=>$form_table,'editable'=>$editable,'subform_level'=>$subform_level,'request_id'=>$request_id])
                                @endcomponent
                                {{--@php $display = 'none';@endphp--}}
                                {{--@if(isset($form_data[$form_field->name]) && $form_field->input_type == 'radio')--}}
                                    {{--@php $display = 'block'; @endphp--}}
                                {{--@endif--}}
                                @if($form_info->sub_forms)
                                    @foreach($form_info->sub_forms->where('field_id','=',$form_field->id)->sortBy('order') as $subform)
                                        @php
                                            $form = $subform->form;
                                            $form_fields = $subform->form->fields;
                                            $form_table = \Illuminate\Support\Str::plural($form->table_name);
                                            $tableName = \Illuminate\Support\Str::plural($parent_table."_sub_".$form_table);

                                            if (isset($request_id)){
                                                $form_data = \Illuminate\Support\Facades\DB::table($tableName)
                                                ->where('clearance_request_id',\Illuminate\Support\Facades\Crypt::decrypt($request_id))
                                                ->first();
                                                $form_data = (array)$form_data;
                                            }else{
                                                $form_data = null;
                                            }

                                            if (!isset($form_data)){
                                                $form_data = null;
                                            }
                                        @endphp
                                        @component('formbuilder::components.forms.render.sub_form.child_form',['subform'=>$subform,'editable'=>$editable,
                                        'form_fields'=>$form_fields,'form_data'=>$form_data,'tableName'=>$tableName,'request_id'=>$request_id,
                                        'form_table'=>$form_table,'parent_table'=>$parent_table,'subform_level'=>$subform_level,'cid'=>$cid])
                                        @endcomponent
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                        @if($form_collective->submit_type === 'individual' && $editable === 1)
                            <button type="submit" class="btn btn-success mt-2 mb-3">Save</button>
                            </form>
                            @ability('webmaster|client','add-requests|edit-requests')
                                @if($form_info->id == 14 && isset($request))
                                    <form action="{{route('clearance_request.submit')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="request_id"
                                               value="{{\Illuminate\Support\Facades\Crypt::encrypt($request->id)}}">
                                        <button type="submit" class="btn btn-success box-shadow-1">
                                            <i class="fa fa-check-circle"></i> Submit Request
                                        </button>
                                    </form>
                                @endif
                            @endrole
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>
