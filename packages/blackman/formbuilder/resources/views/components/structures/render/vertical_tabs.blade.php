<div class="row">
    <div class="col-md-12">
        @php
            $parent_table = str_plural($form->table_name);
            $complete = 0;
            $total_forms = $form_collectives_forms->count();
            if ($request){
                $complete = $request->status;
            }
            $next_step = $step + 1 - $complete;
            if($next_step > $total_forms)
            {
                $next_step = $total_forms;
            }
        @endphp
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
                        <a class="nav-link {{($count == $next_step)?'active':''}} block" id="form_tab_{{$count}}" data-toggle="tab" href="#{{$form_info->slug}}" role="tab"
                           aria-controls="{{$form_info->slug}}" aria-selected="true">
                            {{$form_info->title}}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{--TABS CONTENT--}}
            <div class="tab-content" id="myTabContent">
                @php
                    $count = 0;
                @endphp
                @foreach($form_collectives_forms as $form_collectives_form)
                    @php
                        $form_info = $form->where('id','=',$form_collectives_form->form_id)->first();
                        $form_table = str_plural($form_info->table_name);
                        $tableName = str_plural($parent_table."_".$form_table);
                        if (isset($request_id)){
                            $form_data = \Illuminate\Support\Facades\DB::table($tableName)
                            ->where('clearance_request_id',\Illuminate\Support\Facades\Crypt::decrypt($request_id))
                            ->first();
                            $form_data = (array)$form_data;
                        }else{
                            $form_data = null;
                        }

                        //dd($form_data['names']);
                        if (!isset($form_data)){
                            $form_data = null;
                        }
                        $count++;
                    @endphp
                    <div class="tab-pane fade  {{($count == $next_step)?'show active':''}}" id="{{$form_info->slug}}" role="tabpanel"
                         aria-labelledby="form_tab_{{$count}}">
                        <div class="card-body">
                            @isset($form_info->description)
                                {!! $form_info->description !!}
                                <hr>
                            @endisset
                            @if($form_collective->submit_type == 'individual' && $editable == 1)
                                @if($count <= $next_step)
                                <form action="{{($submit_url == null)?route('submit_form'): $submit_url}}"
                                      @if($contain_file > 0) enctype="multipart/form-data" @endif
                                      method="post">@csrf
                                    <input type="hidden" name="form_id" value="{{$form_info->id}}">
                                    <input type="hidden" name="request_id" value="@if($request_id != null) {{$request_id }} @endif">
                                    <input type="hidden" name="step" value="{{$count}}">
                                    <input type="hidden" name="collective_id" value="{{$form_collective->id}}">
                                    @endif
                                    @endif
                                    <div class="row">
                                        @foreach($form_info->fields as $form_field)
                                            @component('components.forms.'.$form_field->input_type,['form_field'=>$form_field,
                                            'form_data'=>$form_data,'form_table'=>$form_table,'editable'=>$editable])
                                            @endcomponent
                                        @endforeach
                                    </div>
                                    @if($form_collective->submit_type == 'individual' && $editable == 1)
                                        @if($count <= $next_step)
                                        <button type="submit" class="btn btn-success mt-2 mb-3">Save</button>
                                        @endif
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