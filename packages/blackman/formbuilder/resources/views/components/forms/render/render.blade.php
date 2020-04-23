
@if(!$is_collective)
    @if($editable == 1)
    <form action="{{($submit_url == null)?route('submit_form'): $submit_url}}"
          @if($contain_file) enctype="multipart/form-data" @endif
          method="post">@csrf
            <input type="hidden" name="request_id" value="{{$request_id}}">
            <input type="hidden" name="form_id" value="{{$form->id}}">
        @endif
        @component('formbuilder::components.structures.render.single',
        ['editable'=>$editable,'is_collective'=>$is_collective, 'form'=>$form,'request_id'=>$request_id,
        'request'=>$request, 'contain_file'=>$contain_file, 'submit_url'=>$submit_url])
        @endcomponent

        @if($editable == 1)
            <button type="submit" class="btn btn-success mt-2 mb-3">Save</button>
    </form>
        @endif
@else
    @if($form_collective->submit_type == 'group')
        @if($editable == 1)
            <form action="{{($submit_url == null)?route('submit_form'): $submit_url}}"
                  @if($contain_file) enctype="multipart/form-data" @endif
                  method="post">@csrf
                <input type="hidden" name="request_id" value="{{$request_id}}">
                <input type="hidden" name="form_id" value="{{$form_collective->id}}">
                @endif
                @endif
                @component('formbuilder::components.structures.render.'.$structure,
                ['form_collectives_forms'=>$form_collectives_forms,'editable'=>$editable,
                'is_collective'=>$is_collective,
                'form'=>$form,'request_id'=>$request_id,'request'=>$request,'contain_file'=>$contain_file,
                'form_collective'=>$form_collective,'step'=>$step,'submit_url'=>$submit_url])
                @endcomponent
                @if($form_collective->submit_type == 'group')
                    @if($editable == 1)
                        <button type="submit" class="btn btn-success mt-2 mb-3">Save</button>
            </form>
        @endif
    @endif
@endif
@push('end-script')
    @if($editable == 0)
        <script>
            $("document").ready(function () {

                $('input[type="radio"].showSubForm_0:checked').each(function () {
                    var $this = $(this), key = $this.data('subformid');
                    if (key > 0){
                        $('#sc_0_'+key).show();
                    }
                });

                $('input[type="radio"].showSubForm_1:checked').each(function () {
                    var $this = $(this), key = $this.data('subformid');
                    if (key > 0){
                        $('#sc_1_'+key).show();
                    }
                });

                $('input[type="radio"].showSubForm_2:checked').each(function () {
                    var $this = $(this), key = $this.data('subformid');
                    if (key > 0){
                        $('#sc_2_'+key).show();
                    }
                });
            });
        </script>
    @endif
@endpush
