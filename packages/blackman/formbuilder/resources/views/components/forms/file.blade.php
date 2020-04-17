<div class="col-md-{{$form_field->column_size}}">
    <div class="form-group">
        <label for="{{$form_field->name}}" style="font-weight: 600;">{{$form_field->label}}
            @if($form_field->required == 1)<span class="required">*</span>@endif
            <br>
            @if($form_field->name == "last_ports_of_calls")
                @php $tempLink = "Ports_of_Call.xlsx";@endphp
                <a href="{{route('download.doc_temp',[$tempLink])}}" target="_blank">Click to download template</a>
            @endif
            @if($form_field->name == "imo_crew_lists")
                @php $tempLink = "Crewlist.xlsx";@endphp
                <a href="{{route('download.doc_temp',[$tempLink])}}" target="_blank">Click to download template</a>
            @endif
        </label>
        <br>
        @if($editable == 0 && ($form_data != null && empty($form_data['for_empty_request_id'])))
            @php
                $attachLink = $form_data[$form_field->name];
                if ($cid != null){
                    $attachLink = strtolower($cid)."/RequestorFiles/".$form_data[$form_field->name];
                }
            @endphp
            @if ($cid != null)
                <a href="{{route('clearance_request.client.attachments',[$request_id])}}" target="_blank" class="btn btn-primary square">
                    <i class="ft-download"></i> Download File
                </a>
            @elseif(\Auth::user()->ability('client|webmaster','download-repository-files') && $attachLink != null)
                <a href="{{url('download?filePath='.$attachLink)}}" class="btn btn-primary square">
                    <i class="ft-download"></i> Download File
                </a>
                @elseif(\Auth::user()->ability('client|webmaster','download-repository-files') && $attachLink == null)
                <span class="btn btn-secondary disabled square">
                    <i class="ft-eye-off"></i> No file uploaded
                </span>
            @else
                <span class="btn btn-secondary disabled square">
                    <i class="ft-eye-off"></i> Cannot download file
                </span>
            @endif
        @elseif($editable == 1 && isset($form_data[$form_field->name]) && ($form_data != null && empty($form_data['for_empty_request_id'])))
            @php
                //$attachLink = $form_data[$form_field->name];
                $attachLink = $form_data[$form_field->name];
                if ($cid != null){
                    $attachLink = strtolower($cid)."/RequestorFiles/".$form_data[$form_field->name];
                }
                $id = $form_data['id'];
            @endphp
            @if(\Auth::user()->ability('client|webmaster','download-repository-files'))
            <a href="{{url('download?filePath='.$attachLink)}}" class="btn btn-primary btn-sm square mr-1 mb-1">
                <i class="ft-download"></i> {{$form_data[$form_field->name]}}
            </a>
            <a class="btn btn-danger btn-sm mb-1" href="{{url('set-field-null?table='.$tableName.'&id='.$id.'&col='.$form_field->name)}}"
               onclic="event.preventDefault(); document.getElementById('delete-form-{{$form_field->name}}').submit();">
                <i class="ft-x"></i> Remove
            </a>
            @else
                <span class="btn btn-secondary disabled square">
                    <i class="ft-eye-off"></i> Cannot download file
                </span>
            @endif
        @else
            <input type="file"
                   @if($form_field->required == 1)  required @endif
                   @if($form_field->autocomplete == 1)  autocomplete="yes" @endif
                   @if($form_field->disabled == 1 || $editable == 0)  disabled @endif
                   class="form-control{{ $errors->has($form_field->name) ? ' is-invalid' : '' }} {{$form_field->class}}"
                   style="{{$form_field->inline_css}}"
                   name="{{$form_field->name}}"
                   id="{{$form_field->name}}">
            <small class="form-text text-muted">
                The file must be of type
                @foreach($form_field->file_types as $key=>$file_type)
                    {{strtoupper($file_type)}},
                @endforeach
                @if($form_field->file_size > 0)
                    and must be up to {{$form_field->file_size}}Mb
                @endif
            </small>
            @if ($errors->has($form_field->name))
                <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first($form_field->name) }}</strong>
                    </span>
            @endif
        @endif
    </div>
    @if($form_field->column_size == 12)
        <hr>
    @endif
</div>
