@php
    $sub_form = $subform->form;
@endphp
<div class="col-md-12" id="sc_{{$subform_level}}_{{$sub_form->id}}" style="display: none">
    <div class="sub-box">
        <input type="hidden" name="child_form_id[]" value="{{$sub_form->id}}">
        <input type="hidden" name="tb_name[]" value="{{$tableName}}">
        <div class="row">
            @foreach($form_fields->sortBy('order') as $key => $form_field)
                @if($sub_form->id == 41 || $sub_form->id == 28)
                    @continue
                @endif
                @php
                    $subform_level += 1;
                @endphp

                @component('formbuilder::components.forms.'.$form_field->input_type,['form_field'=>$form_field,'cid'=>$cid,
                'editable'=>$editable,'form_data'=>$form_data,'tableName'=>$tableName,'form_table'=>$form_table,
                'subform_level'=>$subform_level,'request_id'=>$request_id])
                @endcomponent

                @if($sub_form->sub_forms)
                    @foreach($sub_form->sub_forms->where('field_id','=',$form_field->id)->sortBy('order') as $subform)
                        @php
                            $form = $subform->form;
                            $form_fields = $subform->form->fields;
                            $form_table = str_plural($form->table_name);
                            $tableName = str_plural($parent_table."_sub_".$form_table);

                            if (isset($request_id)){
                                $form_data_sub = \Illuminate\Support\Facades\DB::table($tableName)
                                ->where('clearance_request_id',\Illuminate\Support\Facades\Crypt::decrypt($request_id))
                                ->first();
                                $form_data_sub = (array)$form_data_sub;
                            }else{
                                $form_data_sub = null;
                            }

                            if (!isset($form_data_sub)){
                                $form_data_sub = null;
                            }
                        @endphp
                        @component('formbuilder::components.forms.render.sub_form.child_form',['subform'=>$subform,'editable'=>$editable,
                        'form_fields'=>$form_fields,'form_data'=>$form_data_sub,'tableName'=>$tableName,'cid'=>$cid,
                        'form_table'=>$form_table,'subform_level'=>$subform_level,'request_id'=>$request_id])
                        @endcomponent
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
</div>
