@extends('formbuilder::layouts.base')
@section('content')
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

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

            @component('formbuilder::components.forms.render.render',[
                'form'=>$form,'request'=>$request,'form_collective'=>$form_collective,'editable'=>$editable,
                'structure'=>$structure,'request_id'=>$request_id,'contain_file'=>$contain_file,'step'=>$step,
                'form_collectives_forms'=>$form_collectives_forms,'submit_url'=>$submit_url,
                'is_collective'=>$is_collective
            ])@endcomponent
        </div>
    </div>
    </div>
@endsection
@if($form->sub_forms)
    @push('script')
        @if($editable == 1)
            <script src="{{ asset('form-builder/js/form-builder-editable-form.js') }}"></script>
        @endif
    @endpush
@endif
