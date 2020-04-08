<?php

namespace FormBuilder\Http\Controllers;

use App\Http\Controllers\Controller;
use FormBuilder\Helpers\FormEntities;
use FormBuilder\Models\Field;
use FormBuilder\Models\Form;
use FormBuilder\Models\LookupOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FormsController extends Controller
{

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
            'active' => 'nullable|string|min:0',
        ]);
        $active = 0;
        if (isset($request->active)){
            $active = 1;
        }

        Form::where('id','=',$id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'active' => $active,
        ]);

        return redirect()->route('forms');
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
            $form->active == 0;
            $form->save();
            session()->flash('status','Form Deactivated.');
        }else{
            $form->active == 1;
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
//        return "Builder";
        $form = Form::find($form_id);
        $form_fields = Field::where('form_id','=',$form_id)->orderBy('order','asc')
            ->orderBy('label','asc')->get();
        $forms = Form::where('id','<>',$form_id)->orderBy('title','asc')->get();
//
        $f_fields = new FormEntities();
        $lookups = LookupOption::all();
        $fields = $f_fields->getFieldTypes();
        $roles = [];
//
//        return $form;
        return view('formbuilder::form.build_field', compact('fields','form','forms','form_fields','lookups', 'roles'));
    }

    /**
     * @param $form_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form_preview($form_id)
    {
        return "Form Preview";
//        $form = Form::find($form_id);
//        $form_fields = Field::where('form_id','=',$form_id)->orderBy('order','asc')
//            ->orderBy('label','asc')->get();
//        $f_fields = new FormEntities();
//        $editable = 1;
//        return view('forms.preview_form', compact('f_fields','form','form_fields','editable'));
    }

}
