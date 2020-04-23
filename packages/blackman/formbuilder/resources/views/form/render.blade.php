<div class="card">
    <div class="card-header">
        {{strtoupper($form->title)}}
    </div>

    <div class="card-content collapse show" style="">
        <div class="card-body" style="overflow-x: auto;min-height: 500px;">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if($form_collective->submit_type == 'group')
                @if($editable == 1)
                    <form action="{{($submit_url == null)?route('submit_form'): $submit_url}}"
                          @if($contain_file) enctype="multipart/form-data" @endif
                          method="post">@csrf
                        <input type="hidden" name="form_id" value="{{$form_collective->id}}">
                        <input type="hidden" name="request_id" value="{{$request_id}}">
                        @endif
                        @endif
                        @component('formbuilder::components.structures.render.'.$structure,
                        ['form_collectives_forms'=>$form_collectives_forms,'editable'=>$editable,'cargo_info'=>$cargo_info,
                        'form'=>$form,'request_id'=>$request_id,'request'=>$request,'contain_file'=>$contain_file,
                        'form_collective'=>$form_collective,'step'=>$step,'submit_url'=>$submit_url])
                        @endcomponent
                        @if($form_collective->submit_type == 'group')
                            @if($editable == 1)
                                <button type="submit" class="btn btn-success mt-2 mb-3">Save</button>
                    </form>
                @endif
            @endif

        </div>
    </div>
</div>
@push('end-script')
    @if($editable == 0)
        <script src="{{asset('form-builder/js/form-builder-noneditable-form.js')}}"></script>
    @endif
@endpush
