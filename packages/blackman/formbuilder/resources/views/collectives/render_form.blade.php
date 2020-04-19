@extends('formbuilder::layouts.base')
@push('head-scripts')
    {{--<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/daterange/daterangepicker.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/forms/extended/form-extended.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/plugins/pickers/daterange/daterange.css')}}">--}}
    {{--<link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap-tagsinput.css')}}">--}}
@endpush
@section('content')
    <div class="row">
        <div class="col-md-9">

            @if (session('message'))
                    @php
                        $messages = session('message');
                    @endphp
                <div class="alert alert-warning alert-dismissible mb-2" role="alert">
                    <h5 class="danger">Please complete and save the following tabs before you submit.</h5>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
                    <ul>
                        @foreach($messages as $key=>$message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $action_view_status = false;
                if (!empty(app('request')->request_id)) {
                   $url_value = \Illuminate\Support\Facades\Crypt::decrypt(app('request')->request_id);
                   $action_view_status = ( $url_value == $request->id ) ? true : false;
                }
                $cid = "";
                if (isset($request)){
                    $cid = $request->CID;
                }
            @endphp

            @if (Auth::user()->hasPermission('perform-workflow') && app('request')->request_id &&
            $action_view_status && $workflow_form_fields->isNotEmpty())
                    <div class="">
                        <div class="form-group" style="">
                            <a class="btn btn-primary" target="_blank" href="{{route("clearance_request.workflow", app('request')->request_id)}}">
                                Process Request WorkFlow</a>
                            <a href="{{route('brief-report.edit',app('request')->request_id)}}"
                               class="btn btn-primary" target="_blank">Brief Inspection Report</a>
                        </div>
                    </div>
            @endif

            @component('components.forms.render.render',[
                'form'=>$form,'request'=>$request,'form_collective'=>$form_collective,'editable'=>$editable,
                'structure'=>$structure,'request_id'=>$request_id,'contain_file'=>$contain_file,'step'=>$step,
                'form_collectives_forms'=>$form_collectives_forms,'cargo_info'=>$cargo_info,'submit_url'=>$submit_url,
                'action_view_status'=>$action_view_status,'request_placeholder_info'=>$request_placeholder_info
            ])@endcomponent

            @isset($request)
                @if(sizeof($trails) > 0)
                    @component('components.status-box',['trails'=>$trails,'last_trail'=>$last_trail,'stages'=>$stages])@endcomponent
                @endif
            @endisset

            {{--@if(Auth::user()->ability('administrator|monitors|coordinators|project-manager|oic|webmaster','perform-workflow') &&--}}
            {{--$action_view_status && $workflow_form_fields->isNotEmpty())--}}
            {{--<div class="card">--}}
                {{--<div class="card-header">--}}
                   {{--ACTION--}}
                    {{--<a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>--}}
                    {{--<div class="heading-elements">--}}
                        {{--<ul class="list-inline mb-0">--}}
                            {{--<li><a data-action="collapse"><i class="ft-minus"></i></a></li>--}}
                        {{--</ul>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="card-content collapse show" style="">--}}
                    {{--<div class="card-body">--}}
                        {{--@if (session('status'))--}}
                            {{--<div class="alert alert-success" role="alert">--}}
                                {{--{{ session('status') }}--}}
                            {{--</div>--}}
                        {{--@endif--}}
                        {{--@php--}}
                            {{--$parent_table = str_plural($workflow_form->table_name);--}}
                            {{--$workflow_tableName = $workflow_form->table_name;--}}

                            {{--//We want to get port selected by client--}}
                            {{--$clearance_request = \App\ClearanceRequest::find( \Illuminate\Support\Facades\Crypt::decrypt($request_id) );--}}
                            {{--$port_of_destinations = !empty($clearance_request->route_and_port->port_of_destinations) ? $clearance_request->route_and_port->port_of_destinations : "";--}}

                        {{--@endphp--}}

                            {{--<form action="{{route('workflow.submit.action')}}" method="post" enctype="multipart/form-data">--}}
                                {{--@csrf--}}
                                {{--<input type="hidden" name="request_id" value="{{$request_id}}">--}}
                                {{--<input type="hidden" name="request_status" value="{{$request->request_status->id}}">--}}
                                {{--<input type="hidden" name="table_name" value="{{$workflow_tableName}}">--}}
                                {{--<input type="hidden" name="current_action_taken" value="">--}}

                            {{--<div class="row">--}}
                                {{--@php--}}
                                    {{--$subform_level = 0;--}}
                                    {{--$editable = $workflow_editable;--}}
                                    {{--$form_data = null;--}}

                                     {{--if (isset($request_id)){--}}
                                            {{--if (\Illuminate\Support\Facades\Schema::hasTable($workflow_tableName)) {--}}
                                                 {{--$form_data = \Illuminate\Support\Facades\DB::table($workflow_tableName)--}}
                                                     {{--->where('request_id',\Illuminate\Support\Facades\Crypt::decrypt($request_id))--}}
                                                     {{--->first();--}}
                                                 {{--$form_data = (array)$form_data;--}}
                                            {{--}--}}
                                     {{--}--}}

                                     {{--//TODO we have to find a way to get the return to sender value for coordinator--}}
                                     {{--if(empty($form_data) && $request->request_status->name == "Return To Sender") {--}}
                                             {{--$form_data = \Illuminate\Support\Facades\DB::table('desk_review_processings')--}}
                                                 {{--->where('request_id',\Illuminate\Support\Facades\Crypt::decrypt($request_id))--}}
                                                 {{--->first();--}}
                                                {{--$form_data = (array)$form_data;--}}
                                     {{--}--}}

                                     {{--if (empty($form_data)){--}}
                                            {{--$form_data = array();--}}
                                            {{--$form_data['for_empty_request_id'] = \Illuminate\Support\Facades\Crypt::decrypt($request_id);--}}
                                     {{--}--}}

                                     {{--//This is to get the inspector's document review--}}
                                     {{--$desk_review_processings = \Illuminate\Support\Facades\DB::table('desk_review_processings')--}}
                                         {{--->select('review_summaries')--}}
                                         {{--->where('request_id',\Illuminate\Support\Facades\Crypt::decrypt($request_id))--}}
                                         {{--->first();--}}

                                     {{--if(!empty($desk_review_processings->review_summaries)) {--}}
                                        {{--$form_data['review_summaries'] = $desk_review_processings->review_summaries;--}}
                                     {{--}--}}

                                     {{--$form_table = null;--}}

                                {{--@endphp--}}

                                {{--@foreach($workflow_form_fields as $form_field)--}}

                                    {{--@component('components.forms.'.$form_field->input_type,['form_field'=>$form_field,--}}
                                    {{--'form_data'=>$form_data,'form_table'=>$form_table,'editable'=>$editable,--}}
                                    {{--'subform_level'=>$subform_level,'cid'=>$cid])--}}
                                    {{--@endcomponent--}}

                                    {{--@if($workflow_form->sub_forms)--}}

                                        {{--@php--}}
                                            {{--//TODO issue with get the first index--}}
                                          {{--// $current_action_taken = $workflow_form_fields[2]->name;--}}
                                            {{--if ($form_field->input_type == "select") {--}}
                                                 {{--$current_action_taken =  $form_field->name;--}}
                                            {{--}--}}
                                        {{--@endphp--}}


                                        {{--@foreach($workflow_form->sub_forms->where('field_id','=',$form_field->id)->sortBy('order') as $key => $subform)--}}

                                            {{--@php--}}
                                                {{--$form = $subform->form;--}}
                                                {{--$form_fields = collect();--}}
                                                {{--$form_table = str_plural($form->table_name);--}}

                                                {{--foreach ($subform->form->fields as $key => $form_field) {--}}

                                                    {{--$selected_roles = unserialize($form_field->workflow_actors);--}}
                                                    {{--$auth_user_role = Auth::user()->roles->first()->id;--}}

                                                    {{--if (!empty($selected_roles)) {--}}
                                                        {{--if (in_array($auth_user_role, $selected_roles)) {--}}
                                                            {{--$form_fields->push($form_field);--}}
                                                        {{--}--}}
                                                    {{--}--}}
                                                {{--}--}}

                                            {{--@endphp--}}

                                            {{--@component('components.forms.render.sub_form.child_form',['subform'=>$subform,'editable'=>$editable,'cid'=>$cid,--}}
                                            {{--'form_fields'=>$form_fields,'form_data'=>$form_data,'tableName'=>$workflow_tableName, 'form_table'=>$form_table,--}}
                                            {{--'parent_table'=>$parent_table,'subform_level'=>$subform_level,'request_id'=>$request->id])--}}
                                            {{--@endcomponent--}}

                                        {{--@endforeach--}}

                                    {{--@endif--}}

                                {{--@endforeach--}}
                             {{--</div>--}}

                              {{--<button type="submit" class="btn btn-success mt-1 mb-2" id="action_submit_btn" style="display: none;">Submit</button>--}}

                            {{--</form>--}}

                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
           {{--@endif--}}

            @if(isset($request))
                @ability('webmaster|administrator|super-admin','admin-change-request-status')
                    @component('components.return_push',['return_push'=>$return_push,'request_id'=>$request_id])@endcomponent
                @endability
            @endif

            @if(isset($request))
                @ability('webmaster|administrator','view-audit-trails')
                    @component('components.request-audit',['audit_trails'=>$audit_trails])@endcomponent
                @endability
            @endif

        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Summary</div>

                <div class="card-body">
                    @isset($request)
                        @php
                            $parent_table = str_plural($form->table_name);
                            $complete = 0;
                            if ($request){
                                $complete = $request->status;
                            }
                        @endphp
                        @if(Auth::user()->ability('client|webmaster','replicate-requests') && in_array($request->request_status_id,[3,7,8,19]))
                            <div class="mb-2">
                                <form action="{{route('replicate_request')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="request_id"
                                           value="{{\Illuminate\Support\Facades\Crypt::encrypt($request->id)}}">
                                    <button class="btn btn-primary box-shadow-1 square">
                                        <i class="fa fa-clone"></i> Replicate Request
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endisset
                    <div class="form-group">
                        <label for="name">{{ __('Created Date') }}</label>
                        <input type="text" disabled readonly class="form-control" @isset($request)
                        value="{{ date('M d, Y h:i:s A',strtotime($request->created_at)) }}" @endisset>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('Status') }}</label>
                        <input type="text" disabled readonly class="form-control" @isset($request)
                        value="{{ strtoupper($request->request_status->name) }}"
                               style="color: {{ $request->request_status->color_code }};" @endisset>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('Vessel Name') }}</label>
                        <input type="text" disabled readonly class="form-control" @isset($request)
                        value="{{ $request->vessel_information->names }}" @endisset>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('Requesting Entity') }}</label>
                        <input type="text" disabled readonly class="form-control" @isset($request)
                        value="{{ $request->contact_information->names }}" @endisset>
                    </div>
                    <div class="form-group">
                        <label for="name">{{ __('Tracking Number') }}</label>
                        <input type="text" disabled readonly class="form-control" @isset($request)
                        value="{{ $request->tracking_no }}" @endisset>
                    </div>
                    @if(!Auth::user()->hasRole('client'))
                        <div class="form-group">
                            <label for="name">{{ __('Reference Number') }}</label>
                            <input type="text" disabled readonly class="form-control" @isset($request)
                            value="{{ $request->reference_no }}" @endisset>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Clearance Number') }}</label>
                            <input type="text" disabled readonly class="form-control" @isset($request)
                            value="{{ $request->clearance_no }}" @endisset>
                        </div>
                        <div class="form-group">
                            <label for="name">{{ __('Current Assignee') }}</label>

                            @if(isset($request))
                                @php
                                    $assignee = "";
                                    $user_clearance_request = \Illuminate\Support\Facades\DB::table('user_clearance_requests')
                                                    ->where('request_id',$request->id)
                                                    ->where('role_id','=',4)
                                                    ->select('user_id')
                                                    ->first();

                                    if(!empty($user_clearance_request)) {
                                        $current_assignee  = \Illuminate\Support\Facades\DB::table('users')
                                                    ->where('id',$user_clearance_request->user_id)
                                                    //->select('email','name')
                                                    ->first();
                                        $assignee = $current_assignee->name;
                                    }
                                @endphp
                            @else
                                @php
                                    $assignee = "";
                                @endphp
                            @endif
                            <input type="text" disabled readonly class="form-control" @if(!empty($assignee))
                            value="{{$assignee}}" @else value="Not Assigned" @endif>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@push('vendor-script')
    <script src="{{asset('app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.date.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/forms/extended/typeahead/typeahead.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/forms/extended/typeahead/bloodhound.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/bootstrap-tagsinput.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/typeahead.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/picker.time.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/pickers/pickadate/legacy.js')}}" type="text/javascript"></script>
    <script src="{{asset('app-assets/vendors/js/forms/extended/inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
@endpush
@if($form->sub_forms)
@push('end-script')
    <script src="{{asset('js/bootstrap3-typeahead.min.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/forms/extended/form-inputmask.js')}}" type="text/javascript"></script>
<script>
    $("document").ready(function () {
        $('.datetimepicker').datetimepicker({
            format:'DD-MM-YYYY HH:mm',
            // minDate:new Date()
        });
    });
</script>
@if($editable == 1)
    <script>
    $("document").ready(function () {

        $("input[type=radio]:checked").each(function() {
            var formId = $(this).data('subformid');
            var formLevel = $(this).data('subformlevel');

            openSubForm(formLevel,formId);
        });



        autofillAgent();
        autofillTrader();
        humanitarian();
        vessel_type();
        description_of_cargos();
        cargo_vehicles();
        workflow_action();
        check_tanker_grade();
        setClientPort();
    });

    function openSubForm(level,form_id) {
        if (form_id > 0){
            $('#sc_'+level+'_'+form_id).show().find('input, textarea, button, select').prop("disabled", false);
        }
    }

    function description_of_cargos() {
        var description = $('select#description_of_cargos option:selected').val();
        if (description !== 'Other (Specify)'){
            $('textarea#cargo_grade_descriptions').prop('disabled',true);
        }else{
            $('textarea#cargo_grade_descriptions').prop('disabled',false);
        }
    }

    function autofillAgent() {
        var checked = $('input[name="same_as_requestors"]:checked').val();
        if(checked == 1){
            var requestor = {!! $requestor_info !!}
                // var form = $('form#local_agents');
                $('form#local_agents #names').val(requestor.contact_person);
            $('form#local_agents #phones').val(requestor.telephone_numbers);
            $('form#local_agents #mobile_numbers').val(requestor.mob_numbers);
            $('form#local_agents #addresses').val(requestor.addresses);
            $('form#local_agents #emails').val(requestor.emails);
            $('form#local_agents #faxes').val(requestor.faxes);
            $('form#local_agents #websites').val(requestor.websites);
        }
        // else{
        //     $('form#local_agents #names').val("");
        //     $('form#local_agents #phones').val("");
        //     $('form#local_agents #mobile_numbers').val("");
        //     $('form#local_agents #addresses').val("");
        //     $('form#local_agents #emails').val("");
        //     $('form#local_agents #faxes').val("");
        //     $('form#local_agents #websites').val("");
        // }
    }

    function workflow_action() {

       var current_action_taken = "{!! !empty($current_action_taken) ? $current_action_taken : "" !!}";

       var action_value = 123456789;
       switch (current_action_taken) {
           case "inspectors_actions":
               action_value = {!! !empty($form_data['inspectors_actions']) ? $form_data['inspectors_actions'] : 123456789 !!};
               break;
           case "monitors_actions":
               action_value = {!! !empty($form_data['monitors_actions']) ? $form_data['monitors_actions'] : 123456789 !!};
               break;
           case "coordinators_actions":
               action_value = {!! !empty($form_data['coordinators_actions']) ? $form_data['coordinators_actions'] : 123456789 !!};
               break;
           default:
               action_value = {!! !empty($form_data['actions']) ? $form_data['actions'] : 123456789 !!};
               break;
       }

        if (action_value !== 123456789 ) {
            var change = $("[name="+ current_action_taken + "]").find('option[value="'+action_value +'"]').length;

            if (change > 0) {
                console.log(change);

                $("[name="+ current_action_taken + "]").val(action_value).trigger('change');
                $("[name="+ current_action_taken + "]").prop('selected', true);

                showSubFormSelect_0(current_action_taken);
            }

        } else {

            //TODO issue with default selected value caused this
            $("[name="+ current_action_taken + "]").val("").trigger('change');
            $("[name="+ current_action_taken + "]").prop('selected', false);

            $("#action_submit_btn").css("display", "none");
            $("input[name='current_action_taken']").val("");
        }


    }

    function autofillTrader() {
        var checked = $('input[name="same_as_traders"]:checked').val();
        if(checked == 1){
            var importer = {!! $importer_info !!}
            $('form#notify_party #names').val(importer.names);
            $('form#notify_party #phones').val(importer.phones);
            $('form#notify_party #mobile_numbers').val(importer.mobile_numbers);
            $('form#notify_party #addresses').val(importer.addresses);
            $('form#notify_party #emails').val(importer.emails);
            $('form#notify_party #faxes').val(importer.faxes);
            $('form#notify_party #websites').val(importer.websites);
        }
        // else{
        //     $('form#notify_party #names').val("");
        //     $('form#notify_party #phones').val("");
        //     $('form#notify_party #mobile_numbers').val("");
        //     $('form#notify_party #addresses').val("");
        //     $('form#notify_party #emails').val("");
        //     $('form#notify_party #faxes').val("");
        //     $('form#notify_party #websites').val("");
        // }
    }

    function showSubForm_0(key) {
        $("[id^='sc_0_']").hide().find('input, textarea, button, select').prop("disabled", true);
        if (key > 0){
            $('#sc_0_'+key).show().find('input, textarea, button, select').prop("disabled", false);
        }
    }

    function showSubForm_1(key) {
        $("[id^='sc_1_']").hide().find('input, textarea, button, select').prop("disabled", true);
        if (key > 0){
            $('#sc_1_'+key).show().find('input, textarea, button, select').prop("disabled", false);
        }
    }

    function showSubForm_2(key) {
        $("[id^='sc_2_']").hide().find('input, textarea, button, select').prop("disabled", true);
        if (key > 0){
            $('#sc_2_'+key).show().find('input, textarea, button, select').prop("disabled", false);
        }
    }

    function showSubFormSelect_0(key) {
        var theName = $("select#"+key+">option:selected").val();

        $("[id^='sc_0_']").hide().find('input, textarea, button, select').prop("disabled", true);
        if (theName > 0){
            $('#sc_0_'+theName).show().find('input, textarea, button, select').prop("disabled", false);

            $("#action_submit_btn").css("display", "block");
            $("input[name='current_action_taken']").val(key);

            return
        }

        //TODO watch it out so ww will be able to do it for multiple actions
        if ((key === "actions" || key === "admin_actions") && theName === "0") {
            $("#action_submit_btn").css("display", "block");
            $("input[name='current_action_taken']").val(key);
            return
        }

        $("#action_submit_btn").css("display", "none");
        $("input[name='current_action_taken']").val("");

    }

    function setClientPort() {
        let current_table_name = "{!! !empty($workflow_tableName) ? $workflow_tableName : "" !!}";

        if (current_table_name === "change_destination_ports" || current_table_name === "issueds_clearance") {
            let port_destination = $("#port_destinations").val();
            if (port_destination === "") {
                let clients_port = "{!! !empty($port_of_destinations) ? $port_of_destinations : ""  !!}";
                console.log("Null ", "{!! !empty($port_of_destinations) ? $port_of_destinations : "" !!}");

                $("[name=port_destinations]").val(clients_port.trim()).trigger('change');

            }
        }

    }

    function humanitarian() {
        var theName = $("input[name='humanitarian_assistances']:checked").val();

        if(theName === 'Not Applicable'){
            // $('input[name="humanitarian_assistances"]:not(:checked)').prop("disabled", true);
            $('form#cargo_infos>div>div>div>input#humanitarian_agencies').prop("disabled", true).val('');
            $('form#cargo_infos>div>div>div>textarea#description_cargos').prop("disabled", true).val('');
            $('form#cargo_infos>div>div>div>input#containers').prop("disabled", true).val('');
            $('form#cargo_infos>div>div>div>input#grades').prop("disabled", true).val('');
            $('form#cargo_infos>div>div>div>input#weights').prop("disabled", true).val('');
        }
        else{
            // $('input[name="humanitarian_assistances"]').prop("disabled", false);
            $('form#cargo_infos>div>div>div>input#humanitarian_agencies').prop("disabled", false);
            $('form#cargo_infos>div>div>div>textarea#description_cargos').prop("disabled", false);
            $('form#cargo_infos>div>div>div>input#containers').prop("disabled", false);
            $('form#cargo_infos>div>div>div>input#grades').prop("disabled", false);
            $('form#cargo_infos>div>div>div>input#weights').prop("disabled", false);
        }
    }

    function cargo_vehicles() {
        var vehicle = $('input[name="type_of_cargos"]:checked').val();
        if(vehicle === 'Vehicles' || vehicle === 'Containerised'){
            $("input[name='humanitarian_assistances'][value='Not Applicable']").prop("checked", true);
            humanitarian();
        }
        // else{
        //     $("input[name='humanitarian_assistances'][value='Part of Cargo']").prop("checked", true);
        //     humanitarian();
        // }
    }

    function vessel_type() {
        var dhow = $('input[name="vessel_types"]:checked').val();
        if(dhow === 'Dhow'){
            $('#other_vessel_types').prop("disabled", true).val('');
            $('input#satellite_phones').prop("disabled", true).val('N/A');
            $('#imos').prop("readonly", true).val('N/A');
            $("input[name='humanitarian_assistances'][value='Not Applicable']").prop("checked", true);
            humanitarian();
        }else if(dhow === 'Other'){
            $('input[type="text"]#other_vessel_types').prop("disabled", false);
            $('input#satellite_phones').prop("disabled", false);
            $('input[type="text"]#imos').prop("readonly", false);
        }
        else{
            $('#imos').prop("readonly", false);
            $('#satellite_phones').prop("disabled", false);
            $('#other_vessel_types').prop("disabled", true).val('');
        }
    }

    function check_tanker_grade() {
        var grade = $('input[name="tanker_grades"]').val();
        if(grade <= 0){
            $('#tanker_grade_descriptions').prop("disabled", true).val('');
            $('select#description_of_tankers').prop("disabled", true).val('Ballast (Empty)');
            $('input#tanker_weights').prop("disabled", true).val(0);
            $('input#humanitarian_assistances_2').prop("checked", true);
            humanitarian();
        }else if(grade <= 1){
            $('#tanker_grade_descriptions').prop("disabled", true).val('');
            $('select#description_of_tankers').prop("disabled", false);
            $('input#tanker_weights').prop("disabled", false);
        }
        else{
            $('#tanker_grade_descriptions').prop("disabled", false);
            $('select#description_of_tankers').val("Other (Specify)");
            $('input#tanker_weights').prop("disabled", false);
        }
        $('.select2').select2();
    }
</script>
@endif
<script>
    var c_url = "{{route('getAutocompleteCountries')}}";
    var p_url = "{{route('getAutocompletePorts')}}";
    var monitors_email_url = "{{route('getAutocompleteMonitorsEmail')}}";
    var inspectors_email_url = "{{route('getAutocompleteInspectorsEmail')}}";
    var coordinators_email_url = "{{route('getAutocompleteCoordinatorsEmail')}}";

    $('.countries').typeahead({

        source:  function (query, process) {

            return $.get(c_url, { query: query }, function (data) {

                return process(data);

            });

        }

    });

    var thePorts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: '{!! url("/dir/json/ports.json") !!}'
    });

    // initialize the bloodhound suggestion engine
    thePorts.initialize();

    $(".ports").typeahead({
        minLength: 1,
        hint: true,
        items: 7,
        source:thePorts.ttAdapter()
    });

    $('.monitors_email').typeahead({

        displayText: function(item) {
            return item.user
        },

        afterSelect: function(item) {
            this.$element[0].value = item.email
        },

        source:  function (query, process) {

            return $.get(monitors_email_url, { query: query }, function (data) {

                return process(data);

            });

        }

    });

    $('.inspectors_email').typeahead({

        displayText: function(item) {
            return item.user
        },

        afterSelect: function(item) {
            this.$element[0].value = item.email
        },

        source:  function (query, process) {

            return $.get(inspectors_email_url, { query: query }, function (data) {

                return process(data);

            });

        }

    });

</script>
<script>
    var tagPorts = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: '{{url("/dir/json/ports.json")}}',
            filter: function(list) {
                return $.map(list, function(portname) {
                    return { name: portname }; });
            }
        }
    });
    tagPorts.initialize();

    $('.tagsInput.auto_multi').tagsinput({
        typeaheadjs: {
            name: 'tagPorts',
            displayKey: 'name',
            valueKey: 'name',
            source: tagPorts.ttAdapter()
        }
    });
</script>
@endpush
@endif
