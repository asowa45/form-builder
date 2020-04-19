@extends('layouts.app')
@push('end-styles')
    {{--<link rel="stylesheet" type="text/css" href="{{asset('wysihtml5/bootstrap3-wysihtml5.min.css')}}">--}}
@endpush

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">Edit Form</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('form.update',[$form->id]) }}">
                        @csrf {{method_field('PUT')}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">{{ __('Title') }}</label>

                                    <div>
                                        <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                               name="title" value="{{ old('title')? old('title'):$form->title }}" required autofocus>

                                        @if ($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('title') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="table_name">{{ __('Table Name') }}</label>

                                    <div class="">
                                        <input id="table_name" type="text" class="form-control{{ $errors->has('table_name') ? ' is-invalid' : '' }}"
                                               disabled readonly value="{{ old('table_name')? old('table_name'):$form->table_name }}" required autofocus>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}</label>

                                    <div>
                                    <textarea name="description"  id="editor1" cols="30" rows="7"
                                              class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{ old('description')? old('title'):$form->description }}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="">
                                        <div class="form-check">
                                            <input class="form-check-input" name="active" @if($form->active == 1) checked @endif
                                            type="checkbox" value="1" id="active">
                                            <label class="form-check-label" for="active">
                                                Set form to active
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="">
                                        <div class="form-check">
                                            <input class="form-check-input" name="collective" type="checkbox" value="1"
                                                   @if($form->collective == 1) checked @endif id="collective">
                                            <label class="form-check-label" for="collective">
                                                Create a <strong>Collective Form</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="">
                                        <div class="form-check">
                                            <input class="form-check-input" name="workflow" type="checkbox" value="1"
                                                   @if($form->workflow == 1) checked @endif id="workflow">
                                            <label class="form-check-label" for="workflow">
                                                Create a <strong>WorkFlow Form</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        {{ __('Update Form') }}
                                    </button>
                                    <a href="{{URL::previous()}}" class="btn btn-secondary left">
                                        {{ __('Back') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('end-script')
    {{--<script src="{{asset('ckeditor/ckeditor.js')}}" type="text/javascript"></script>--}}
    {{--<script src="{{asset('wysihtml5/bootstrap3-wysihtml5.all.min.js')}}" type="text/javascript"></script>--}}
    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('editor1')
            //bootstrap WYSIHTML5 - text editor
            // $('.textarea').wysihtml5();
        });
    </script>
@endpush
