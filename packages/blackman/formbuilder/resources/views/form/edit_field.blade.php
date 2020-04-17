<form action="{{route('form_field.update')}}" method="post">
    @csrf {{method_field('PUT')}}
    <input type="hidden" name="field_id" value="{{$field->id}}">
    <input type="hidden" name="prev_name" value="{{$field->name}}">
    <div class="accordion" id="accordionExample">
        <div class="card mb-1" style="border-bottom: 1px solid rgb(223, 223, 223);">
            <div class="card-header" style="background-color: #fff; padding: 20px; border-bottom: 0;" id="item">

                <div class="row">
                    <div class="form-group col-md-1">
                        <label for="order">Order</label>
                        <br>
                        <input type="number" min="0" required class="form-control" value="{{old('order')?old('order'):$field->order}}" name="order" id="order">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="label">Label Title</label>
                        <br>
                        <input type="text" required value="{{old('label')?old('label'):$field->label}}" class="form-control" name="label" id="label" placeholder="Eg. Student Number">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="input_type">Input Type</label>
                        <br>
                        <select class="form-control" name="input_type" onchange="select_option()" required id="input_type">
                            <option> </option>
                            @foreach($fields as $key=>$ff)
                                <option {{($field->input_type == $key)?'selected': ''}} value="{{$key}}">{{$ff}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="name">Name</label>
                        <br>
                        <input type="text" readonly required value="{{$field->name}}" class="form-control" name="name" id="name" placeholder="Eg. first_name">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="required">Required? <span class="text-mute"></span></label>
                        <br>
                        <select name="required" class="form-control" id="required">
                            <option {{($field->required==0)?'selected': ''}} value="0">No</option>
                            <option {{($field->required==1)?'selected': ''}} value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="autocomplete">Autocomplete? <span class="text-mute"></span></label>
                        <br>
                        <select name="autocomplete" style="width: 100%;" onchange="hasAutoOption()" class="form-control" id="autocomplete">
                            <option {{($field->autocomplete==0)?'selected': ''}} value="0">No</option>
                            <option {{($field->autocomplete==1)?'selected': ''}} value="1">Yes</option>
                        </select>
                    </div>

                </div>
                <hr>
                <div id="show_autoOptions">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="has_auto_options">Has Auto-complete options? <span class="text-mute"></span></label>
                            <br>
                            <select name="has_auto_options" style="width: 100%;" onchange="hasAnOption()" class="form-control" id="has_auto_options">
                                <option {{($field->has_auto_options == 0)?'selected': ''}} value="0">No</option>
                                <option {{($field->has_auto_options == 1)?'selected': ''}} value="1">Yes, Single Option</option>
                                <option {{($field->has_auto_options ==2)?'selected': ''}} value="2">Yes, Multiple Options</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="the_opts">
                            <label for="auto_option">Select Option <span class="text-mute"></span></label>
                            <br>
                            <select name="auto_option" style="width: 100%;" class="form-control select2" id="auto_option">
                                <option value="0"></option>
                                @foreach($lookups as $lookup)
                                    <option {{($field->auto_options==$lookup->code)?'selected': ''}} value="{{$lookup->code}}">{{$lookup->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" id="show_hasChild" style="display: none;">
                    <div class="form-group col-md-2">
                        <label for="is_multiple">Multiple Select? <span class="text-mute"></span></label>
                        <br>
                        <select name="is_multiple" style="width: 100%;" class="form-control select" id="is_multiple">
                            <option {{($field->is_multiple==0)?'selected': ''}} value="0">No</option>
                            <option {{($field->is_multiple==1)?'selected': ''}} value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="required">Has no sub form? <span class="text-mute"></span></label>
                        <br>
                        <select name="hasChild" style="width: 100%;" onchange="isChecked()" class="form-control select" id="hasChild">
                            <option {{($field->hasChild==0)?'selected': ''}} value="0">No</option>
                            <option {{($field->hasChild==1)?'selected': ''}} value="1">Yes</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3" id="show_type" style="display: none">
                        <label for="required" style="margin-top: 1.1rem;"></label>
                        <br>
                        <select readonly="readonly" name="showBy" style="width: 100%;" class="form-control select" id="showBy">
                            <option selected value="2">Show As Options</option>
                            {{--<option {{(old('required')==0)?'selected': ''}} disabled value="1">Show All Forms</option>--}}
                        </select>
                    </div>
                </div>


                {{--SHOWS ONLY WHEN BUTTON INPUT TYPE IS SELECTED--}}
                <div class="row" id="show_button_dropdown" style="display: none;">

                    <div class="form-group col-md-2">
                        <label for="is_multiple">Is Dropdown Button? <span class="text-mute"></span></label>
                        <br>
                        <select name="is_dropdown_button" style="width: 100%;" class="form-control select" onchange="isDropdown()" id="is_dropdown_button">
                            <option {{($field->is_dropdown_button==0)?'selected': ''}} value="0">No</option>
                            <option {{($field->is_dropdown_button==1)?'selected': ''}} value="1">Yes</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6" id="show_button_url">
                        <label for="button_url">Button URL? <span class="text-mute"></span></label>
                        <br>
                        <input type="text" value="{{$field->button_url}}" class="form-control" name="button_url" id="button_url" placeholder="www.vimye.com">
                    </div>


                    @php
                        $dropdown_options_fields = json_decode($field->button_dropdown_options);
                        $button_option_display = 'none';
                        if($field->is_dropdown_button == 1){
                            $button_option_display = 'block';
                        }else{
                            $button_option_display = 'none';
                        }
                    @endphp

                    <div class="form-group col-md-12 repeater-default" id="show_button_dropdown_options" style="display: {{$button_option_display}};">
                        <div data-repeater-list="button_dropdown_options">
                            <h6>Add Options</h6>
                            @if(!empty($dropdown_options_fields))
                            @foreach($dropdown_options_fields as $key=>$option)
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="form-group mb-1 col-md-4">
                                        <input type="text" class="form-control" name="opt_name" value="{{($field->is_dropdown_button == 1)?$option->opt_name:''}}"
                                               id="opt_name" placeholder="Display Name">
                                    </div>
                                    <div class="form-group mb-1 col-md-4">
                                        <select name="opt_value" style="width: 100%;" class="form-control select2" id="opt_value">
                                            <option value="0">--- Choose Option Value  ---</option>
                                            @foreach($lookups as $lookup)
                                                <option {{($option->opt_value==$lookup->code)?'selected': ''}} value="{{$lookup->code}}">{{$lookup->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="form-group col-sm-12 col-md-1 text-center mb-1">
                                        <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                                <div data-repeater-item>
                                    <div class="row">
                                        <div class="form-group mb-1 col-md-4">
                                            <input type="text" class="form-control" name="opt_name" id="opt_name" placeholder="Display Name">
                                        </div>
                                        <div class="form-group mb-1 col-md-4">
                                            <select name="opt_value" style="width: 100%;" class="form-control select2" id="opt_value">
                                                <option value="0">--- Choose Option Value  ---</option>
                                                @foreach($lookups as $lookup)
                                                    <option {{(old('auto_option')==$lookup->code)?'selected': ''}} value="{{$lookup->code}}">{{$lookup->name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group col-sm-12 col-md-1 text-center mb-1">
                                            <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                                <span data-repeater-create class="btn btn-outline-info">
                                    <i class="fa fa-plus"></i> Add an option
                                </span>
                        </div>
                    </div>
                </div>


                @php
                    $the_fields = json_decode($field->options);
                    $the_field_forms = json_decode($field->forms);
                    $form_display = 'none';
                    $option_display = 'none';
                    if($field->hasChild == 1){
                        $form_display = 'block';
                        $option_display = 'none';
                    }else{
                        $form_display = 'none';
                        $option_display = 'block';
                    }
                @endphp
                <div class="repeater-default" style="display: {{$option_display}};" id="show_options">
                    <div data-repeater-list="options">
                        <h6>Add Options</h6>
                        @foreach($the_fields as $key=>$option)
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="form-group mb-1 col-md-5">
                                        <input type="text" class="form-control" value="{{($field->hasChild == 0)?$option->opt_name:''}}" name="opt_name" id="opt_name"
                                               placeholder="Display Name">
                                    </div>
                                    <div class="form-group mb-1 col-md-5">
                                        <input type="text" class="form-control" value="{{($field->hasChild == 0)?$option->opt_value:''}}" name="opt_value" id="opt_value"
                                               placeholder="Value">
                                    </div>
                                    <div class="form-group col-sm-12 col-md-2 text-center mb-1">
                                        <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i> Delete</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <span data-repeater-create class="btn btn-outline-info">
                            <i class="fa fa-plus"></i> Add an option
                        </span>
                    </div>
                </div>

                <div class="repeater-default" style="display: {{$form_display}};" id="show_forms">
                    <div data-repeater-list="forms">
                        <h6>Select Forms</h6>
                        @foreach($the_field_forms as $key=>$option)
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="form-group mb-1 col-md-4">
                                        <input type="text" class="form-control" name="opt_name" id="opt_name" value="{{($field->hasChild == 1)?$option->opt_name:''}}"
                                               placeholder="Display Name">
                                    </div>
                                    <div class="form-group mb-1 col-md-3">
                                        <input type="text" class="form-control" name="opt_value"  value="{{($field->hasChild == 1)?$option->opt_value:''}}"
                                               id="opt_value" placeholder="Value">
                                    </div>
                                    <div class="form-group mb-1 col-md-4">
                                        <select name="c_form" id="c_form" class="pickForm form-control">
                                            <option value="0">-- Choose Form --</option>
                                            @foreach($forms as $theForm)
                                                @php
                                                    $c_form = '';
                                                    if($field->hasChild == 1){
                                                        if(!empty($option->c_form)) {
                                                            $c_form = $option->c_form;
                                                        }
                                                    }
                                                @endphp
                                                <option {{($c_form == $theForm->id)?'selected':''}} value="{{$theForm->id}}">{{$theForm->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-12 col-md-1 text-center mb-1">
                                        <button type="button" class="btn btn-danger" data-repeater-delete> <i class="ft-x"></i></button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <span data-repeater-create class="btn btn-outline-info">
                            <i class="fa fa-plus"></i> Add an option
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 file_options">
                        <label for="file_type">Allowed file types</label>
                        <br>
                        @php
                            $file_type = "";
                            if (!empty( $field->file_types)) {
                                foreach ( $field->file_types as $type) {
                                    $file_type .= $type.",";
                                }
                            }
                        @endphp
                        <input type="text" placeholder="pdf,xlsx,docx,txt" class="form-control"
                               value="{{old('file_type')?old('file_type') : rtrim($file_type,",")}}" name="file_type" id="file_type">
                    </div>
                    <div class="form-group col-md-2 file_options">
                        <label for="file_size">Max File Size (Mb)</label>
                        <br>
                        <input type="number" class="form-control" value="{{old('file_size')?old('file_size'):$field->file_size}}" name="file_size" id="file_size">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-12 description_option" style="display: none;">
                        <label for="description">Write Description</label>
                        <br>
                        <textarea name="description"  id="editor1" cols="30" rows="7"
                                  class="textarea form-control{{ $errors->has('description') ? ' is-invalid' : '' }}">{{old('description')?old('description'):$field->description}}</textarea>
                    </div>
                </div>

                <a class="dp text-right btn btn-secondary" href="#" type="button" data-toggle="collapse" data-target="#thebody" aria-expanded="true" aria-controls="thebody">
                    <i class="fa fa-expand" aria-hidden="true"></i> More Attributes
                </a>
            </div>

            <div id="thebody" class="collapse bb" aria-labelledby="item" data-parent="#accordionExample">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="placeholder">Placeholder</label>
                            <br>
                            <input type="text" placeholder="Enter placeholder" value="{{old('placeholder')?old('placeholder'):$field->placeholder}}" class="form-control" name="placeholder" id="placeholder">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="default_value">Default Value</label>
                            <br>
                            <input type="text" value="{{old('default_value')?old('default_value'):$field->default_value}}" class="form-control" name="default_value" id="default_value">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="class">Class <small>(separate option with a space)</small></label>
                            <br>
                            <input type="text" value="{{old('class')?old('class'):$field->class}}" placeholder="text-center col-md-2 card" class="form-control" name="class" id="class">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="disabled">Disabled? <span class="text-mute"></span></label>
                            <br>
                            <select name="disabled" class="form-control" id="disabled">
                                <option {{($field->disabled==1)?'selected': ''}} value="1">Yes</option>
                                <option {{($field->disabled==0)?'selected': ''}} value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inline_css">Inline CSS</label>
                            <br>
                            <input type="text" placeholder="font-size:13px,text-align:center" class="form-control"
                                   value="{{old('inline_css')?old('inline_css'):$field->inline_css}}" name="inline_css" id="inline_css">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="min">Min</label>
                            <br>
                            <input type="number" class="form-control" value="{{old('min')?old('min'):$field->min}}" name="min" id="min">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="max">Max</label>
                            <br>
                            <input type="number" class="form-control" value="{{old('max')?old('max'):$field->max}}" name="max" id="max">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="step">Step</label>
                            <br>
                            <input type="number" step="0.001" class="form-control" value="{{(old('step'))?old('step'):$field->step}}" name="step" id="step">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="column_size">Column Size <span class="text-mute"></span></label>
                            <br>
                            <select name="column_size" class="form-control" id="column_size">
                                @for($a=1; $a <= 12; $a++)
                                    <option {{($field->column_size==$a)?'selected': ''}} value="{{$a}}">{{$a}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-success square">Submit Fields</button>

</form>
