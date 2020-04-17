<div class="card">
    <div class="card-header">
        {{strtoupper($form->title)}}

        @if(Auth::user()->roles->first()->name != "client" && $action_view_status)
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        @endif

    </div>

    {{--<div class="card-content collapse  @if(Auth::user()->roles->first()->name == "client" || !$action_view_status) show @endif" style="">--}}
    <div class="card-content collapse show" style="">
        <div class="card-body" style="overflow-x: auto;min-height: 500px;">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="mb-2">
                {{--{!! $form->description !!}--}}
            </div>
            @if($form_collective->submit_type == 'group')
                @if($editable == 1)
                    <form action="{{($submit_url == null)?route('submit_form'): $submit_url}}"
                          @if($contain_file > 0) enctype="multipart/form-data" @endif
                          method="post">@csrf
                        <input type="hidden" name="form_id" value="{{$form_collective->id}}">
                        <input type="hidden" name="request_id" value="{{$request_id}}">
                        @endif
                        @endif
                        @component('components.structures.render.'.$structure,
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
