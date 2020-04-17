@php
    $sub_form = $subform->form;
@endphp
<div class="col-md-12" id="sc_{{$subform_level}}_{{$sub_form->id}}" style="display: none">
    <input type="hidden" name="child_form_id" value="{{$sub_form->id}}">
    <h6>{{$sub_form->title}}</h6>
    <hr>
    <div class="row">
        @foreach($sub_form->fields->sortBy('order') as $form_field)
            @php
                $subform_level += 1;
            @endphp
            @component('components.forms.preview.'.$form_field->input_type,['form_field'=>$form_field,
            'tableName'=>$tableName,'editable'=>$editable,'subform_level'=>$subform_level])
            @endcomponent
            @if($form_field->option_forms)
                @foreach($sub_form->sub_forms->where('field_id','=',$form_field->id)->sortBy('order') as $subform)
                    @php
                        $form = $subform->form;
                        $form_fields = $subform->form->fields;
                        $form_table = \Illuminate\Support\Str::plural($form->table_name);
                        $tableName = \Illuminate\Support\Str::plural($parent_table."_sub_".$form_table);
                    @endphp
                    @component('components.forms.preview.sub_form.child_form',['subform'=>$subform,'editable'=>$editable,
                    'form_fields'=>$form_fields,'tableName'=>$tableName,
                    'form_table'=>$form_table,'subform_level'=>$subform_level])
                    @endcomponent
                @endforeach
            @endif
        @endforeach
    </div>
</div>