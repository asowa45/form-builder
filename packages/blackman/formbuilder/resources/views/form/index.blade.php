@extends('formbuilder::layouts.base')
@push('head-scripts')@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of Forms</div>

                    <div class="card-body">
                        <a href="{{route('form.add')}}" class="btn btn-info mb-3 mr-3">Add Form</a>
                        <a href="{{route('form_collectives')}}" class="btn btn-secondary mb-3">Form Collectives</a>
                        <p>Showing all Forms</p>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Title</th>
                                {{--<th scope="col">Description</th>--}}
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($forms as $form)
                                <tr>
                                    <td>{{$form->title}}</td>
                                    {{--<td>{{$form->description}}</td>--}}
                                    <td>
                                        <a href="{{route('form.edit',[$form->id])}}" title="Edit" class="btn btn-primary btn-sm">
                                            Edit
                                        </a>
                                        <a href="{{route('form.preview',[$form->id])}}" title="Preview Form" class="btn btn-primary btn-sm">
                                            Preview
                                        </a>
                                        <a href="{{route('form.builder',[$form->id])}}"
                                           class="btn btn-primary btn-sm" title="Build Form">
                                            Build Form
                                        </a>
                                        <a href="{{route('form.activate',[$form->id])}}"
                                           class="btn @if($form->active == 1) btn-warning @else btn-success @endif btn-sm">
                                            <i class="fa fa-lock-open"></i> @if($form->active == 1) Deactivate @else Activate @endif
                                        </a>
                                        <a class="dropdow-item btn btn-danger btn-sm" href=""
                                           onclick="document.getElementById('delete-form-{{$form->id}}').submit();" title="Delete">
                                            Delete
                                        </a>
                                        <form id="delete-form-{{$form->id}}" action="{{ route('form.delete',[$form->id]) }}" method="POST" style="display: none;">
                                            @csrf {{method_field('DELETE')}}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{--                        {{ $forms->links() }}--}}
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