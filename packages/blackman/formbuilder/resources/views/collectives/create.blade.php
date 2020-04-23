@extends('formbuilder::layouts.base')
@push('head-scripts')@endpush
@section('content')
    <div class="container">
        <h4>{{strtoupper($form->title)}}</h4>
        <p>{!! $form->description !!}</p>
        <a href="{{route('form_collectives')}}" class="btn btn-light mb-3">Back</a>
        {{--@if($form->fields->count() > 0)--}}
        @isset($form_collective)
        <a href="{{route('form_collective.preview',[$form->id])}}" target="_blank" class="btn btn-outline-secondary ml-3 mb-3">
            <i class="fa fa-eye"></i> Preview</a>
        @endisset
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4>Enter Form Collective properties</h4>
                        <hr>
                        <form action="{{route('form_collective.save',[$form_id])}}" method="post">
                            @csrf
                            <input type="hidden" name="form_id" value="{{$form_id}}">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="cover_page">Set as Cover page<span class="text-mute"></span></label>
                                    <br>
                                    <select name="cover_page" style="width: 100%;" class="form-control select2" id="cover_page">
                                        <option value="">--Choose--</option>
                                        <option @if($form_collective){{($form_collective->cover_page == 0)?'selected': ''}}@endif value=0>No</option>
                                        <option @if($form_collective){{($form_collective->cover_page ==1)?'selected': ''}}@endif value=1>Yes</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="structure_type">Structure Type<span class="text-mute"></span></label>
                                    <br>
                                    <select name="structure_type" style="width: 100%;" class="form-control select2" id="structure_type">
                                        <option value="">--Choose--</option>
                                        {{--<option {{(old('structure_type')=='accordion')?'selected': ''}} value="accordion">Accordion</option>--}}
                                        <option @if($form_collective){{($form_collective->structure_type =='horizontal_tabs')?'selected': ''}}@endif value="horizontal_tabs">Horizontal Tabs</option>
                                        <option @if($form_collective){{($form_collective->structure_type =='vertical_tabs')?'selected': ''}}@endif value="vertical_tabs">Vertical Tabs</option>
                                        {{--<option {{(old('structure_type')=='full')?'selected': ''}} value="full">Full</option>--}}
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="submit_type">Submit Type<span class="text-mute"></span></label>
                                    <br>
                                    <select name="submit_type" style="width: 100%;" class="form-control select2" id="submit_type">
                                        <option value="">--Choose--</option>
                                        <option @if($form_collective){{($form_collective->submit_type =='individual')?'selected': ''}}@endif value="individual">Individual</option>
                                        <option @if($form_collective){{($form_collective->submit_type =='group')?'selected': ''}}@endif value="group">Group</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="required">Process Type<span class="text-mute"></span></label>
                                    <br>
                                    <select name="process_type" style="width: 100%;" class="form-control select2" id="process_type">
                                        <option value="">--Choose--</option>
                                        <option @if($form_collective){{($form_collective->process_type =='steps')?'selected': ''}}@endif value="steps">Steps</option>
                                        <option @if($form_collective){{($form_collective->process_type =='open')?'selected': ''}}@endif value="group">Open</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    {{--<label for="required">Process Type<span class="text-mute"></span></label>--}}
                                    <br>
                                    <button type="submit" name="properties" value="1" class="btn btn-success square">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        @if($form_collective)
                            <h4>Add Forms to Collective</h4>
                            <hr>
                            <form action="{{route('form_collective.save',[$form_id])}}" method="post">
                                @csrf
                                <input type="hidden" name="form_collective_id" value="{{$form_collective->id}}">
                                <input type="hidden" name="form_id" value="{{$form_id}}">
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label for="form">Select Form<span class="text-mute"></span></label>
                                        <br>
                                        <select name="form" id="form" style="width: 100%;" class="form-control select2"
                                        required>
                                            <option value="">--Choose--</option>
                                            @foreach($forms as $form)
                                                <option value="{{$form->id}}">{{$form->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="order">Position<span class="text-mute"></span></label>
                                        <br>
                                        <input type="number" class="form-control" name="order" id="order" required>
                                    </div>
                                </div>
                                <button type="submit" name="add_form" value="1" class="btn btn-success"> Save</button>
                            </form>
                        @else
                            <h5>You have to add properties first.</h5>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        @if($form_collectives_forms)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of Forms</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width:30px">Order</th>
                                <th scope="col">Label</th>
                                <th scope="col">Description</th>
                                <th scope="col" style="width:300px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($form_collectives_forms as $getform)
                                <tr>
                                    <td>{{$getform->order}}</td>
                                    <td>{{$getform->form->title}}</td>
                                    <td>{{$getform->form->description}}</td>
                                    <td>
                                        <a href="{{route('form.edit',[$getform->form->id])}}" class="btn btn-secondary btn-sm">
                                            <i class="icon-edit"></i> Edit
                                        </a>
                                        <a href="{{route('form.builder',[$getform->form->id])}}" class="btn btn-primary btn-sm">
                                            <i class="icon-eye"></i> Render
                                        </a>
                                        <a href="{{route('form.activate',[$getform->form->id])}}"
                                           class="btn @if($getform->form->active == 1) btn-warning @else btn-success @endif btn-sm">
                                            <i class="icon-check"></i> @if($getform->form->active == 1) Deactivate @else Activate @endif
                                        </a>
                                        <a class="dropdow-item btn btn-danger btn-sm" href=""
                                           onclick="event.preventDefault();
                                                   document.getElementById('delete-form-{{$getform->form->id}}').submit();">
                                            <i class="icon-close"></i> {{ __('Remove') }}
                                        </a>

                                        <form id="delete-form-{{$getform->form->id}}" action="{{ route('form_collective.form.delete',[$getform->form->id]) }}" method="POST" style="display: none;">
                                            @csrf {{method_field('DELETE')}}
                                            <input type="hidden" name="form_collective_id" value="{{$form_collective->id}}">
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
        @endif
    </div>
@endsection
@push('script')

@endpush
