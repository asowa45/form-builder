<div class="row">
    <div class="col-md-12">
        @php
            $parent_table = \Illuminate\Support\Str::plural($form->table_name);
            $complete = $subform_level = 0;
            $form_data = null;
            $form_info = $form;
            $form_table = \Illuminate\Support\Str::plural($form_info->table_name);
            $tableName = \Illuminate\Support\Str::plural($form_info->table_name);
            $cid = "";
            if (isset($request_id)){
                $form_data = \Illuminate\Support\Facades\DB::table($tableName)->find(
                \Illuminate\Support\Facades\Crypt::decrypt($request_id));
                $form_data = (array)$form_data;
            }
        @endphp
        <div class="row">
            @foreach($form_info->fields->sortBy('order') as $form_field)
                @component('formbuilder::components.forms.'.$form_field->input_type,['form_field'=>$form_field,'tableName'=>$tableName,'cid'=>$cid,
                'form_data'=>$form_data,'form_table'=>$form_table,'editable'=>$editable,'subform_level'=>$subform_level,'request_id'=>$request_id])
                @endcomponent
            @endforeach
        </div>
    </div>
</div>
