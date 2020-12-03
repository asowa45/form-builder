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
            {!! $form_view !!}
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
