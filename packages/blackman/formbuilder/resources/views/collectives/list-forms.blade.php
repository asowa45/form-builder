@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{$form->title}} - List of Forms</div>

                    <div class="card-body">
                        {{--<a href="{{route('form.add')}}" class="btn btn-info">Edit Form Field</a><br><br>--}}
                        <a href="{{route('form_collectives')}}" class="btn btn-secondary mb-3">Back to Form Collectives</a>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h5>Add To Forms</h5>
                        <form action="{{route('form_collective.update',[$form->form_collective->id])}}" method="post">
                            @csrf {{method_field('PUT')}}
                            <div class="repeater-default" id="forms">
                                <div data-repeater-list="forms">
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="form-group mb-1 col-md-7">
                                                <label for="required">Select Form<span class="text-mute"></span></label>
                                                <br>
                                                <select name="form" id="form" style="width: 100%;" class="form-control select2" required>
                                                    <option value="">--Choose--</option>
                                                    @foreach($all_forms as $main_form)
                                                        {{--@if(in_array($main_form->id,(array)$listOfForms))--}}
                                                        {{--{{(in_array($main_form->id,$listOfForms))?'disabled':''}}--}}
                                                        {{--@endif--}}
                                                        <option value="{{$main_form->id}}">
                                                            {{$main_form->title}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mb-1 col-md-3">
                                                <label for="required">Position<span class="text-mute"></span></label>
                                                <br>
                                                <input type="number" class="form-control" name="order" id="order" required>
                                            </div>
                                            <div class="form-group col-sm-12 col-md-2 text-center mb-1" style="margin-top: 0.9rem">
                                                <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i> Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span data-repeater-create class="btn btn-outline-info">
                                        <i class="fa fa-plus"></i> Add Form
                                    </span>
                                    <button type="submit" class="btn btn-success ml-3">Save</button>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width:30px">Order</th>
                                <th scope="col">Label</th>
                                {{--<th scope="col">Description</th>--}}
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($form_collectives_forms as $getform)
                                @php
                                    $form = $all_forms->where('id','=',$getform->form_id)->first();
                                @endphp
                                <tr>
                                    <td>{{$getform->order}}</td>
                                    <td>{{$form->title}}</td>
{{--                                    <td>{{$form->description}}</td>--}}
                                    <td>
                                        <a href="{{route('form.edit',[$form->id])}}" class="btn btn-secondary btn-sm">
                                            <i class="icon-edit"></i> Edit
                                        </a>
                                        <a href="{{route('form.builder',[$form->id])}}" class="btn btn-primary btn-sm">
                                            <i class="icon-eye"></i> Render
                                        </a>
                                        <a href="{{route('form.activate',[$form->id])}}"
                                           class="btn @if($form->active == 1) btn-warning @else btn-success @endif btn-sm">
                                            <i class="icon-check"></i> @if($form->active == 1) Deactivate @else Activate @endif
                                        </a>
                                        <a class="dropdow-item btn btn-danger btn-sm" href=""
                                           onclick="event.preventDefault();
                                                   document.getElementById('delete-form-{{$form->id}}').submit();">
                                            <i class="icon-close"></i> {{ __('Remove') }}
                                        </a>

                                        <form id="delete-form-{{$form->id}}" action="{{ route('form_collective.form.delete',[$form->id]) }}" method="POST" style="display: none;">
                                            @csrf {{method_field('DELETE')}}
                                            <input type="hidden" name="form_collective_id" value="{{$collective_id}}">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('end-script')
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/form-repeater.js') }}"></script>
@endpush
