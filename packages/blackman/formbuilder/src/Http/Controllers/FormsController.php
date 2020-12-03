<?php

namespace FormBuilder\Http\Controllers;

use App\Http\Controllers\Controller;
use FormBuilder\Helpers\FormEntities;
use FormBuilder\Helpers\FormGenerator;
use FormBuilder\Models\Field;
use FormBuilder\Models\Form;
use FormBuilder\Models\FormFieldChildren;
use FormBuilder\Models\LookupOption;
//use FormBuilder\Traits\FormGenerator;
use FormBuilder\Traits\FormSubmission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FormsController extends Controller
{
    use FormSubmission;
//    use FormGenerator;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $forms = Form::activeForms()->orderBy('title','asc')->get();
        return view('formbuilder::form.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('formbuilder::form.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string|min:3|unique:forms',
            'description' => 'nullable|string|min:3',
            'table_name' => 'required|string|min:2|unique:forms',
            'active' => 'nullable|boolean|min:0',
            'collective' => 'nullable|boolean|min:0',
        ]);
        $active = 0;
        $collective = 0;
        $workflow = 0;
        if (isset($request->active)){
            $active = 1;
        }

        if (isset($request->collective)){
            $collective = 1;
        }

        if (isset($request->workflow)){
            $workflow = 1;
        }

        $form = Form::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug(Str::singular($request->table_name)),
            'table_name' => $request->table_name,
            'active' => $active,
            'collective' => $collective,
            'workflow' => $workflow,
        ]);

        if ($collective == 1){
            return redirect()->route('form_collective.create',[$form->id]);
        }
        else{
            return redirect()->route('form.builder',[$form->id]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $form = Form::find($id);
        return view('formbuilder::form.edit',compact('form'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' => 'required|string|min:3|unique:forms,id',
            'description' => 'nullable|string|min:3',
            'active' => 'nullable|numeric|min:0',
            'collective' => 'nullable|numeric|min:0',
            'workflow' => 'nullable|numeric|min:0',
        ]);
        $active = $collective = $workflow = 0;
        if (isset($request->active)){
            $active = 1;
        }
        if (isset($request->collective)){
            $collective = 1;
        }
        if (isset($request->workflow)){
            $workflow = 1;
        }

        Form::where('id','=',$id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'active' => $active,
            'collective' => $collective,
            'workflow' => $workflow,
        ]);

        session()->flash('status','Form Updated.');
        if ($collective == 1){
            return redirect()->route('form_collective.create',[$id]);
        }
        else{
            return redirect()->route('forms');
        }
    }

    /**
     * Activate the specified resource from storage.
     *
     * @param  int  $form_id
     * @return \Illuminate\Http\Response
     */
    public function activate($form_id)
    {
        $form = Form::find($form_id);
        if ($form->active == 1){
            $form->active = 0;
            $form->save();
            session()->flash('status','Form Deactivated.');
        }else{
            $form->active = 1;
            $form->save();
            session()->flash('status','Form Activated.');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $form_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($form_id)
    {
        $count = Form::find($form_id)->form_collectives_forms->count();
        if ($count > 0){
            session()->flash('error','This form is in use. Unlink with all form collections before you can delete.');
        }else{
            Form::destroy($form_id);
            session()->flash('status','Form Deleted.');
        }
        return back();
    }

    /**
     * @param $form_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function build_form($form_id)
    {
        $form = Form::find($form_id);
        $form_fields = Field::where('form_id','=',$form_id)->orderBy('order','asc')
            ->orderBy('label','asc')->get();
        $forms = Form::where('id','<>',$form_id)->orderBy('title','asc')->get();
        $f_fields = new FormEntities();
        $lookups = LookupOption::all();
        $fields = $f_fields->getFieldTypes();
        $roles = [];

        return view('formbuilder::form.build_field', compact('fields','form','forms','form_fields','lookups', 'roles'));
    }

    /**
     * @param $form_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form_preview($form_id)
    {
        $form = Form::find($form_id);
        $form_fields = Field::where('form_id','=',$form_id)->orderBy('order','asc')
            ->orderBy('label','asc')->get();
        $f_fields = new FormEntities();
        $editable = 1;
        return view('formbuilder::form.preview_form', compact('f_fields','form','form_fields','editable'));
    }

    /**
     * @param Request $request
     * @param $form_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_field(Request $request,$form_id)
    {
        $this->validate($request,[
            'order'        => 'required|numeric|min:0',
            'name'        => 'required|string|min:2',
            'label'        => 'required|string|min:2',
            'description'  => 'nullable|string|min:2',
            'input_type'   => 'required|string|min:3',
            'required'     => 'nullable|boolean|min:0|max:1',
            'default_value'=> 'nullable|string',
            'file_type'    => 'nullable|string',
            'button_url'    => 'nullable|string',
            'disabled'     => 'nullable|boolean|min:0|max:1',
            'is_multiple'     => 'nullable|boolean|min:0|max:1',
            'is_dropdown_button'     => 'nullable|boolean|min:0|max:1',
            'max'          => 'nullable|numeric',
            'min'          => 'nullable|numeric',
            'autocomplete' => 'nullable|boolean|min:0|max:1',
            'placeholder'  => 'nullable|string',
            'class'        => 'nullable|string',
            'options'      => 'nullable|array',
            'button_dropdown_options'      => 'nullable|array',
            'auto_option'  => 'nullable|string',
            'forms'         => 'nullable|array',
            'inline_css'   => 'nullable|string',
            'hasChild'      => 'nullable|boolean',
            'showBy'        => 'nullable|numeric|min:0|max:2',
            'column_size'  => 'nullable|numeric|min:1|max:12',
        ]);

        $options = json_encode($request->options);
        $button_dropdown_options = json_encode($request->button_dropdown_options);
        $forms = json_encode($request->forms);

        $workflow_actors = null;
        if (!empty($request->actors)) {
            $workflow_actors = serialize($request->actors);
        }

        $file_types = [];

        if (in_array($request->input_type,['file'])){
            $file_types = explode(',',$request->file_type);
        }

        $conditions = "";
        if ($request->required == 1) {
            $conditions = 'required';
        }else{
            $conditions = 'nullable';
        }

        if (in_array($request->input_type, ['color', 'checkbox', 'select', 'textarea', 'password', 'text', 'dateTime'])) {
            $conditions .= '|string';
        }

        if ($request->input_type == 'number'|| $request->input_type == 'numeric_text') {
            $conditions .= '|numeric';
        }

        if ($request->input_type == 'email') {
            $conditions .= '|email';
        }

        if ($request->input_type == 'file') {
            $conditions .= '|file';
        }

        if (isset($request->file_type)) {
            $conditions .= '|mimes:'.$request->file_type;
        }

        if ($request->input_type == 'tel') {
            if (isset($request->min) && isset($request->max)) {
                $conditions .= '|string|min:'.$request->min.',|max:'.$request->max;
            }elseif (isset($request->min) && !isset($request->max)){
                $conditions .= '|string|min:'.$request->min.'|max:15';
            }elseif (!isset($request->min) && isset($request->max)){
                $conditions .= '|string|min:10,|max:';
            }else{
                $conditions .= '|string|min:10|max:15';
            }
        }else{
            if (isset($request->min)) {
                $conditions .= '|min:'.$request->min;
            }

            if (isset($request->max)) {
                $conditions .= '|max:'.$request->max;
            }
        }

        $theField = Field::updateOrCreate([
            'name'          => strtolower(Str::snake(str_replace('-','_',$request->name))),
            'form_id'    => $form_id,
        ],[
            'order'        => $request->order,
            'label'        => $request->label,
            'description'  => $request->description,
            'input_type'   => $request->input_type,
            'required'     => $request->required,
            'default_value'=> $request->default_value,
            'file_types'    => $file_types,
            'file_size'    => $request->file_size,
            'disabled'     => $request->disabled,
            'is_multiple'     => $request->is_multiple,
            'is_dropdown_button'     => $request->is_dropdown_button,
            'button_url'     => $request->button_url,
            'max'          => $request->max,
            'min'          => $request->min,
            'step'         => $request->step,
            'rules'          => $conditions,
            'autocomplete' => $request->autocomplete,
            'has_auto_options' => $request->has_auto_options,
            'placeholder'  => $request->placeholder,
            'class'        => $request->class,
            'column_size'  => $request->column_size,
            'forms'         => $forms,
            'hasChild'      => $request->hasChild,
            'showBy'        => $request->showBy,
            'options'      => $options,
            'button_dropdown_options'      => $button_dropdown_options,
            'inline_css'   => $request->inline_css,
            'auto_options'   => $request->auto_option,
            'workflow_actors'   => $workflow_actors,
        ]);

        $forms = $request->forms;
        if ($request->hasChild > 0){
            foreach ($forms as $key=>$form){
                if ($form['c_form'] >= 1){
                    $c_form = $form['c_form'];
                    $c_name = $form['opt_name'];
                    $c_value = $form['opt_value'];
                    FormFieldChildren::create([
                        'field_id'          =>      $theField->id,
                        'parent_form_id'    =>      $form_id,
                        'form_id'           =>      $c_form,
                        'name'              =>      $c_name,
                        'value'             =>      $c_value
                    ]);
                }
            }
        }

//        notify(new ToastNotification('Successful','New form field added.','success'));
        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function edit_field(Request $request)
    {
        $value = $request->field_id;
        $field = Field::find($value);
        $forms = Form::where('id','<>',$field->form_id)->orderBy('title','asc')->get();
        $f_fields = new FormEntities();
        $lookups = LookupOption::all();
        $fields = $f_fields->getFieldTypes();
//        $roles = DB::table('roles')->orderBy('id','asc')->get();

        $selected_roles = null;
//        $workflow_form = Form::where('id', $field->form_id)->first();
//        if (!empty($workflow_form->workflow) && !empty($field->workflow_actors)) {
//            $selected_roles = unserialize($field->workflow_actors);
//        }


        $theView = view('formbuilder::form.edit_field',
            compact('field','fields','forms','lookups'))->render();
        return response()->json(['theView'=>$theView,'field'=>$request]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_field(Request $request)
    {

        $this->validate($request,[
            'field_id'     => 'required|numeric|min:1',
            'order'        => 'required|numeric|min:0',
            'name'        => 'required|string|min:2',
            'label'        => 'required|string|min:2',
            'description'  => 'nullable|string|min:2',
            'input_type'   => 'required|string|min:3',
            'required'     => 'nullable|boolean|min:0|max:1',
            'default_value'=> 'nullable|string',
            'file_type'    => 'nullable|string',
            'button_url'    => 'nullable|string',
            'disabled'     => 'nullable|boolean|min:0|max:1',
            'is_multiple'     => 'nullable|boolean|min:0|max:1',
            'is_dropdown_button'     => 'nullable|boolean|min:0|max:1',
            'max'          => 'nullable|numeric',
            'min'          => 'nullable|numeric',
            'autocomplete' => 'nullable|boolean|min:0|max:1',
            'placeholder'  => 'nullable|string',
            'class'        => 'nullable|string',
            'options'      => 'nullable|array',
            'auto_option'  => 'nullable|string',
            'column_size'   => 'nullable|numeric|min:1|max:12',
            'inline_css'   => 'nullable|string',
        ]);

        $options = json_encode($request->options);
        $button_dropdown_options = json_encode($request->button_dropdown_options);
        $forms = json_encode($request->forms);

        $workflow_actors = null;
        if (!empty($request->actors)) {
            $workflow_actors = serialize($request->actors);
        }

        $form_field = $request;
        $value = $request->field_id;
        $prev_name = $request->prev_name;
        $name = $request->name;
        $field = Field::find($value);
        $form = $field->form;
        $form_table_name = $form->table_name;
        $g_parents = DB::table('forms')
            ->join('form_collectives','forms.id','=','form_collectives.form_id')
            ->join('form_collectives_forms','form_collectives.id','=','form_collectives_forms.form_collective_id')
            ->where('form_collectives_forms.form_id','=',$form->id)
            ->pluck('table_name','forms.id');
        $file_types = [];
        if (isset($request->options)){
            if (sizeof($request->options) < 1){
                $options = null;
            }
        }
        if (in_array($request->input_type,['file'])){
            $file_types = explode(',',$request->file_type);
        }
        if ($request->required == 1) {
            $conditions = 'required';
        }else{
            $conditions = 'nullable';
        }
        if (in_array($request->input_type, ['color', 'checkbox', 'select', 'textarea', 'password', 'text','time','dateTime'])) {
            $conditions .= '|string';
        }
        if ($request->input_type == 'email') {
            $conditions .= '|email';
        }
        if ($request->input_type == 'number' || $request->input_type == 'numeric_text') {
            $conditions .= '|numeric';
        }
        if ($request->input_type == 'file') {
            $conditions .= '|file';
        }
        if (isset($request->file_type)) {
            $conditions .= '|mimes:'.$request->file_type;
        }
        if ($request->input_type == 'tel') {
            if (isset($request->min) && isset($request->max)) {
                $conditions .= '|string|min:'.$request->min.',|max:'.$request->max;
            }elseif (isset($request->min) && !isset($request->max)){
                $conditions .= '|string|min:'.$request->min.'|max:15';
            }elseif (!isset($request->min) && isset($request->max)){
                $conditions .= '|string|min:10,|max:';
            }else{
                $conditions .= '|string|min:10|max:15';
            }
        }
        else{
            if (isset($request->min)) {
                $conditions .= '|min:'.$request->min;
            }

            if (isset($request->max)) {
                $conditions .= '|max:'.$request->max;
            }
        }
        $field_name = strtolower(Str::snake(str_replace('-','_',$request->name)));
        $new_field = Field::updateOrCreate([
            'id'=>$request->field_id
        ],
            [
                'name'         => $request->name,
                'order'        => $request->order,
                'label'        => $request->label,
                'description'  => $request->description,
                'input_type'   => $request->input_type,
                'required'     => $request->required,
                'default_value'=> $request->default_value,
                'file_types'   => $file_types,
                'file_size'    => $request->file_size,
                'button_url'    => $request->button_url,
                'rules'         => $conditions,
                'disabled'     => $request->disabled,
                'is_multiple'     => $request->is_multiple,
                'is_dropdown_button'     => $request->is_dropdown_button,
                'max'          => $request->max,
                'min'          => $request->min,
                'step'         => $request->step,
                'autocomplete' => $request->autocomplete,
                'auto_options' => $request->auto_option,
                'has_auto_options' => $request->has_auto_options,
                'placeholder'  => $request->placeholder,
                'column_size'  => $request->column_size,
                'class'        => $request->class,
                'options'      => $options,
                'button_dropdown_options'      => $button_dropdown_options,
                'forms'         => $forms,
                'hasChild'      => $request->hasChild,
                'showBy'        => $request->showBy,
                'inline_css'   => $request->inline_css,
                'workflow_actors'   => $workflow_actors,
            ]);

        $forms = $request->forms;
        if ($request->hasChild == 1){
            FormFieldChildren::where('field_id','=',$request->field_id)
                ->where('parent_form_id','=',$new_field->form_id)
                ->delete();
            foreach ($forms as $key=>$form){
                if ($form['c_form'] >= 1) {
                    $c_form = $form['c_form'];
                    $c_name = $form['opt_name'];
                    $c_value = $form['opt_value'];
                    FormFieldChildren::create([
                        'field_id'          => $request->field_id,
                        'parent_form_id'    => $new_field->form_id,
                        'form_id'           => $c_form,
                        'name'              => $c_name,
                        'value'             => $c_value
                    ]);
                }
            }
        }

        foreach ($g_parents as $key=>$g_parent){
            $counter = 0;
            $form_table = $g_parent."_".$form_table_name;
            Schema::table($form_table, function (Blueprint $table) use($prev_name,$name,$form_table,$form_field,$new_field) {
                if (Schema::hasColumn($form_table, $prev_name) && $prev_name != $name) {
                    $table->renameColumn($prev_name, $name);
                }
            });

            Schema::table($form_table, function (Blueprint $table) use($name,$form_field,$form_table,$key) {
                if (Schema::hasColumn($form_table,$name)) {
                    if (in_array($form_field->input_type, ['color', 'checkbox', 'radio', 'select', 'email', 'file', 'password', 'text', 'numeric_text'])) {
                        if ($form_field->required == 1) {
                            $table->string($name)->change();
                        } else {
                            $table->string($name)->nullable()->change();
                        }
                    }
                    elseif (in_array($form_field->input_type, ['number'])) {
                        if ($form_field->required == 1) {
                            $table->float($name)->change();
                        } else {
                            $table->float($name)->nullable()->change();
                        }
                    }
                    elseif (in_array($form_field->input_type,['tel'])){
                        if ($form_field->required == 1){
                            $table->string($name,20)->change();
                        }else{
                            $table->string($name,20)->nullable()->change();
                        }
                    }
                    elseif (in_array($form_field->input_type, ['textarea'])) {
                        if ($form_field->required == 1) {
                            $table->text($name)->change();
                        } else {
                            $table->text($name)->nullable()->change();
                        }
                    }
                    elseif (in_array($form_field->input_type, ['date'])) {
                        if ($form_field->required == 1) {
                            $table->date($name)->change();
                        } else {
                            $table->date($name)->nullable()->change();
                        }
                    }
                    elseif (in_array($form_field->input_type, ['dateTime'])) {
                        if ($form_field->required == 1) {
                            $table->dateTime($name)->change();
                        } else {
                            $table->dateTime($name)->nullable()->change();
                        }
                    }
                    elseif (in_array($form_field->input_type,['time'])){
                        if ($form_field->required == 1){
                            $table->time($name)->change();
                        }else{
                            $table->time($name)->nullable()->change();
                        }
                    }
                }
//                    else{
//                $form_collective = FormCollective::where('form_id','=',$key)->first();
//                $form_collective->generate = 0;
//                $form_collective->save();
            });
        }
//        notify(new ToastNotification('Successful','Field has been updated.','success'));
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy_field($id)
    {
        Field::destroy($id);
        session()->flash('status','Field Deleted.');
        return back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_form_table(Request $request)
    {
        $form_id = $request->form_id;

        $generate_form = new FormGenerator();

        $generate_form->generate_form($form_id);

        session()->flash('status','Table created.');
        return back();
    }

    public function submitRenderedForm(Request $request)
    {
        $request_id = $this->submit($request);
        if ($request_id) {
            $message = "A new record has been added";
            $type = "success";
            session()->flash($type,$message);
        }
        return back();
    }


    /**
     * @param Request $request
     * @return mixed
     *
     * Downloads the selected files uploaded for a request
     */
    public function getDownload(Request $request)
    {
        $file= $request->filePath;

        try {

            $file_exits =  \Illuminate\Support\Facades\Storage::exists("public/attachments/".$file);
            if ($file_exits) {
                return \Illuminate\Support\Facades\Storage::download( "public/attachments/".$file );
            } else {
                $file_exits =  \Illuminate\Support\Facades\Storage::exists($file);
                if ($file_exits) {
                    return \Illuminate\Support\Facades\Storage::download( $file );
                }
            }

        }catch (\Exception $e) {
            Log::critical($e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return back();
        }

    }

    public function remove_input_file(Request $request)
    {
        DB::table($request['table'])->where('id','=',$request['id'])->update([$request['col']=> null]);

        return back();
    }

    public function remove_file(Request $request)
    {
        DB::table($request->table)->where('id','=',$request->id)->update([$request->col => null]);

        return back();
    }
}
