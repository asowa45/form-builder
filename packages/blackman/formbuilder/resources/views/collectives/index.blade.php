@extends('formbuilder::layouts.base')
@push('head-scripts')@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of Form Collectives</div>

                    <div class="card-body">
                        <a href="{{route('form.add')}}" class="btn btn-info mb-3 mr-3">Add Form</a>
                        <a href="{{route('forms')}}" class="btn btn-secondary mb-3">Forms</a>
                        <p>Showing all Form Collectives</p>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Structure</th>
                                <th scope="col">Submit Type</th>
                                <th scope="col">Process Type</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($collectives as $collective)
                                <tr>
                                    @if(isset($collective->form_collective))
                                    <td>
                                        <a hre="{{route('form_collective.view',[$collective->form_collective->id])}}">
                                            {{$collective->title}}
                                        </a>
                                    </td>
                                    <td>
                                        {{ucwords($collective->form_collective->structure_type)}}
                                    </td>
                                    <td>{{ucwords($collective->form_collective->submit_type)}}</td>
                                    <td>{{ucwords($collective->form_collective->process_type)}}</td>
                                    @else
                                        <td>{{$collective->title}}</td>
                                        <td colspan="3" class="text-center text-danger">No Data was provided. Delete and add again</td>
                                    @endif
                                    <td>
                                        <a href="{{route('form_collective.create',[$collective->id])}}" title="Open" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-eye"></i> Open
                                        </a>
                                        <a href="{{route('form.edit',[$collective->id])}}" title="Edit" class="btn btn-secondary btn-sm">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a target="_blank" href="{{route('form_collective.preview',[$collective->id])}}" title="Preview Form" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i> Preview
                                        </a>
                                        <a class="dropdow-item btn btn-danger btn-sm" href="{{ route('form.delete',[$collective->id]) }}"
                                           onclick="event.preventDefault();
                                                   document.getElementById('delete-form-{{$collective->id}}').submit();" title="Delete">
                                            <i class="fa fa-times"></i> Remove
                                        </a>
                                        <a href="{{route('form.activate',[$collective->id])}}"
                                           class="btn @if($collective->active == 1) btn-warning @else btn-success @endif btn-sm">
                                            <i class="fa fa-lock-open"></i> @if($collective->active == 1) Deactivate @else Activate @endif
                                        </a>

                                        <form id="delete-form-{{$collective->id}}" action="{{ route('form.delete',[$collective->id]) }}" method="POST" style="display: none;">
                                            @csrf {{method_field('DELETE')}}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
{{--                        {{ $collectives->links() }}--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function destroy(key) {
            {{--event.preventDefault();--}}
                    {{--document.getElementById('delete-form-{{$form->id}}').submit();--}}
            if (confirm("Data will be lost after Deletion. Delete?")){
                document.getElementById('delete-form-'+key).submit();
            }
        }
    </script>
@endpush