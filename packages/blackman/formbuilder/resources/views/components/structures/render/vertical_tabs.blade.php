<div class="row">

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

    <div class="col-3">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            {{--ADDS THE COVER PAGE FORM OR PARENT FORM AS FIRST TAB--}}
            @if($form_collective->cover_page == 1)
                @php $count = 0; @endphp
                <a class="nav-link {{($count == $active_step)?'active':''}}" id="form_tab_{{$count}}"
                   data-toggle="pill" href="#{{$form->slug}}" role="tab"
                   aria-controls="{{$form->slug}}" aria-selected="true">
                    Important Note
                </a>
            @endif

            {{--LOOPS THROUGH THE VARIOUS FORMS--}}
            @php $count = 0;@endphp
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                @endphp
                @php $count++; @endphp
                <a class="nav-link {{($count == $active_step)?'active':''}}" id="form_tab_{{$count}}"
                   data-toggle="pill" href="#{{$form_info->slug}}" role="tab"
                   aria-controls="{{$form_info->slug}}" aria-selected="true">
                    {{$form_info->title}}
                </a>
            @endforeach
        </div>
    </div>
    <div class="col-9">
        <div class="tab-content" id="v-pills-tabContent">
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
            @php $count = 0; @endphp
            @foreach($form_collectives_forms as $form_collectives_form)
                @php
                    $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                    $form_table = \Illuminate\Support\Str::plural($form_info->table_name);
                    $tableName = \Illuminate\Support\Str::plural($parent_table."_".$form_table);
                    $parent_table_FK = \Illuminate\Support\Str::singular($parent_table)."_id";
                    if (isset($request_id)){
                        $form_data = \Illuminate\Support\Facades\DB::table($tableName)
                        ->where("$parent_table_FK",\Illuminate\Support\Facades\Crypt::decrypt($request_id))
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
                <div class="tab-pane fade  {{($count == $active_step)?'show active':''}}" id="{{$form_info->slug}}" role="tabpanel"
                     aria-labelledby="form_tab_{{$count}}">
                    <div class="card-body">
                        @isset($form_info->description)
                            {!! $form_info->description !!}
                            <hr>
                        @endisset
                        @if($form_collective->submit_type == 'individual' && $editable == 1)
                            <form action="{{($submit_url == null)?route('submit_form'): $submit_url}}"
                                  @if($contain_file) enctype="multipart/form-data" @endif
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
                                @if($form_collective->submit_type == 'individual' && $editable == 1)
                                    <button type="submit" class="btn btn-success mt-2 mb-3">Save</button>
                            </form>
                            {{--@ability('webmaster|client','add-requests|edit-requests')--}}
                            {{--@if($form_info->id == 14 && isset($request))--}}
                            {{--<form action="{{route('clearance_request.submit')}}" method="POST">--}}
                            {{--@csrf--}}
                            {{--<input type="hidden" name="request_id"--}}
                            {{--value="{{\Illuminate\Support\Facades\Crypt::encrypt($request->id)}}">--}}
                            {{--<button type="submit" class="btn btn-success box-shadow-1">--}}
                            {{--<i class="fa fa-check-circle"></i> Submit Request--}}
                            {{--</button>--}}
                            {{--</form>--}}
                            {{--@endif--}}
                            {{--@endrole--}}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>