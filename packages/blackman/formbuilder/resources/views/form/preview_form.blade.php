@extends('formbuilder::layouts.base')
@push('head-scripts')@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{strtoupper($form->title)}}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{route('form.generate_form')}}" method="POST">
                            <a href="{{\Illuminate\Support\Facades\URL::previous()}}"
                               class="btn btn-secondary square mt-2 mb-1"><i class="fa fa-arrow-left mr-1"></i>Go Back</a>
                            @csrf
                            <input type="hidden" name="form_id" value="{{$form->id}}">
                            <input type="hidden" name="form_type" value="single">
                            <button type="submit" class="btn btn-success mt-2 mb-1">
                                <i class="fa fa-cogs mr-1"></i> Generate & Save Form
                            </button>
                        </form>
                            <hr>
                        @php
                            $tableName = $form->table_name;
                            $parent_table = $tableName;
                        @endphp

                        <div class="row">
                            @php
                                $subform_level = 0;
                            @endphp
                            @foreach($form_fields as $form_field)
                                @component('formbuilder::components.forms.preview.'.$form_field->input_type,['form_field'=>$form_field,
                                'editable'=>$editable,'tableName'=>$tableName,'subform_level'=>$subform_level])
                                @endcomponent
                                @if($form->sub_forms)
                                    @foreach($form->sub_forms as $subform)
                                        @php
                                            $form = $subform->form;
                                            $form_fields = $subform->form->fields;
                                            $form_table = \Illuminate\Support\Str::plural($form->table_name);
                                            $tableName = \Illuminate\Support\Str::plural($parent_table."_sub_".$form_table);
                                        @endphp
                                        @component('formbuilder::components.forms.preview.sub_form.child_form',['subform'=>$subform,
                                        'form_fields'=>$form_fields,'editable'=>$editable,'subform_level'=>$subform_level,
                                        'tableName'=>$tableName,'parent_table'=>$parent_table])
                                        @endcomponent
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@if($form->sub_forms)
    @push('script')
        <script>

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

            function showSubFormSelect_0(key) {
                var theName = $("select#"+key+">option:selected").val();
                $("[id^='sc_0_']").hide().find('input, textarea, button, select').prop("disabled", true);
                if (theName > 0){
                    $('#sc_0_'+theName).show().find('input, textarea, button, select').prop("disabled", false);
                }
            }

            function showSubFormSelect_1(key) {
                var theName = $("select#"+key+">option:selected").val();
                $("[id^='sc_1_']").hide().find('input, textarea, button, select').prop("disabled", true);
                if (theName > 0){
                    $('#sc_1_'+theName).show().find('input, textarea, button, select').prop("disabled", false);
                }
            }

            {{--var c_url = "{{route('getAutocompleteCountries')}}";--}}
            {{--var p_url = "{{route('getAutocompletePorts')}}";--}}

            // $('.countries').typeahead({
            //
            //     source:  function (query, process) {
            //
            //         return $.get(c_url, { query: query }, function (data) {
            //
            //             return process(data);
            //
            //         });
            //
            //     }
            //
            // });
            // $('.ports').typeahead({
            //
            //     source:  function (query, process) {
            //
            //         return $.get(p_url, { query: query }, function (data) {
            //
            //             return process(data);
            //
            //         });
            //
            //     }
            //
            // });
        </script>
    @endpush
@endif