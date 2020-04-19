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
                        @if ($form_collective->generate == 0)
                            <form action="{{route('generate_collective_form_tables')}}" method="POST">
                                @csrf
                                <input type="hidden" name="collective_id" value="{{$form_collective->id}}">
                                <button type="submit" class="btn btn-success mt-3 mb-3">
                                    <i class="fa fa-cogs mr-1"></i> Generate & Save Form
                                </button>
                            </form>
                        @endif
                        @if($form_collective->submit_type == 'group')
                        <form>
                            @endif
                            @component('formbuilder::components.structures.preview.'.$structure,['form_collectives_forms'=>$form_collectives_forms,
                            'form'=>$form,'form_collective'=>$form_collective])
                            @endcomponent
                            @if($form_collective->submit_type == 'group')
                                <button class="btn btn-success mt-2 mb-3">Save</button>
                            @endif
                        </form>
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
                // var description = theName.$("option:selected").val();
                console.log(theName);
                $("[id^='sc_0_']").hide().find('input, textarea, button, select').prop("disabled", true);
                if (theName > 0){
                    $('#sc_0_'+theName).show().find('input, textarea, button, select').prop("disabled", false);
                }
            }
        </script>
    @endpush
@endif