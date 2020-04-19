<?php

namespace FormBuilder\Http\Controllers;

//use App\Classes\ToastNotification;
use App\Http\Controllers\Controller;
use FormBuilder\Models\Form;
use FormBuilder\Models\FormCollective;
use FormBuilder\Models\FormCollectivesForm;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FormCollectiveController extends Controller
{

    public function index()
    {
        $collectives = Form::collectives()->get();
        return view('formbuilder::collectives.index', compact('collectives'));
    }

    public function create($form_id)
    {
        $form_collective = FormCollective::where('form_id','=',$form_id)->first();
        $form = Form::find($form_id);
        $forms = Form::where('collective','=',0)->where('active','=',1)->get();

        $form_collectives_forms = $form->form_collective->form_collectives_forms;
//        dd($form_collectives_forms);
        return view('formbuilder::collectives.create',
            compact('form_collective','forms','form_id','form','form_collectives_forms'));
    }

    public function save(Request $request, $form_id)
    {
//        dd($request->all());
        if (isset($request->properties)) {
            $this->validate($request,[
                'form_id' => 'required|numeric|min:1',
                'structure_type' => 'required|string',
                'submit_type' => 'required|string',
                'process_type' => 'required|string',
//            'forms' => 'nullable|array|min:1',
            ]);
//        dd($request->all());

            FormCollective::updateOrCreate([
                'form_id' => $request->form_id,
                'structure_type' => $request->structure_type,
                'submit_type' => $request->submit_type,
                'process_type' => $request->process_type,
//            'user_id' => Auth::id(),
            ]);
            session()->flash('status','Form Collective properties updated.');
        }

        if (isset($request->add_form)) {
//        dd($request->all());
            $this->validate($request,[
                'form_id' => 'required|numeric|min:1',
                'form_collective_id' => 'required|numeric',
                'order' => 'required|numeric|min:0',
            ]);

            FormCollectivesForm::create([
                'form_id' => $request->form_id,
                'form_collective_id' => $request->form_collective_id,
                'order' => $request->order,
                'active' => 1,
            ]);
            session()->flash('status','Form Added to Collective.');
        }

        return redirect()->back();
    }

    public function view($col_id)
    {
        $collective = FormCollective::find($col_id);
        $collective_id = $collective->id;
        $form = Form::find($collective->form_id);
        $form_collectives_forms = $form->form_collective->form_collectives_forms;
        $all_forms = Form::where('collective','=',0)->where('active','=',1)->get();
        $listOfForms = $form_collectives_forms->pluck('form_id');

        if (!$collective->structure_type){
            return redirect()->route('form_collective.view');
        }

        return view('formbuilder::collectives.list-forms', compact('collective_id','form','all_forms','form_collectives_forms','listOfForms'));
    }

    public function delete_form(Request $request,$form_id)
    {
        $form = FormCollectivesForm::where('form_id','=',$form_id)
            ->where('form_collective_id','=',$request->form_collective_id)
            ->first();
        if ($form){
            $form->delete();
            session()->flash('status','Form Deleted.');
        }else{
            session()->flash('error','This form does not exist in the form collection.');
        }
        return back();
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'forms' => 'required|array|min:1',
        ]);
        foreach ($request->forms as $key=>$option){
            FormCollectivesForm::updateOrCreate([
                'form_id' => $option['form'],
                'form_collective_id' => $id
            ],[
                'order' => $option['order'],
                'active' => 1,
            ]);
        }
        session()->flash('status','A New Form Added');
        return back();
    }

    public function form_preview($form_id)
    {
        $form = Form::find($form_id);
        $form_collective = $form->form_collective;

        if (!$form_collective && $form->collective == 1){
            return redirect()->route('form_collective.create',[$form_id]);
        }
//        dd($form_collective);
        $structure = $form->form_collective->structure_type;
        $form_collectives_forms = $form->form_collective->form_collectives_forms->sortBy('order');
        return view('formbuilder::collectives.preview_form',compact('form','structure','form_collective','form_collectives_forms'));
    }

    public function form_render($form_id)
    {
        $form = Form::find($form_id);
        $form_collective = $form->form_collective;
        $structure = $form->form_collective->structure_type;
        $form_collectives_forms = $form->form_collective->form_collectives_forms->sortBy('order');
        return view('formbuilder::collectives.render_form',compact('form','structure','form_collective','form_collectives_forms'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generate_collective_form_tables(Request $request)
    {
        //GET THE PARTICULAR FORM COLLECTIVE DETAILS
        $form_collective = FormCollective::find($request->collective_id);

        //GET THE PARENT FORM COLLECTIVE
        $parent_form = $form_collective->form;
        $parent_table = str_plural($parent_form->table_name);

        //CREATE A PARENT TABLE TO REFERENCE ALL OTHER RELATED TABLES
        if (!Schema::hasTable($parent_table)) {
            Schema::create($parent_table, function (Blueprint $table) use ($form_collective,$parent_table) {
                $table->increments('id');
                if ($form_collective->process_type == 'steps') {
                    $table->tinyInteger('step')->default(0);
                    $table->tinyInteger('counter')->default(0);
                }
                $table->tinyInteger('status');
                $table->unsignedInteger('user_id');
                $table->foreign('user_id',substr('users_'.$parent_table, 0,10))
                    ->references('id')->on('users');
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            });
        }

        //GET ALL FORMS ASSOCIATED TO THE FORM COLLECTIVE (PARENT FORM) SELECTED
        $form_collectives_forms = $form_collective->form_collectives_forms;
        $total_forms = $form_collectives_forms->count();
        $counter = 0;

        //LOOPS THROUGH THE ASSOCIATED FORMS TO GENERATE TABLES
        foreach($form_collectives_forms as $form_collectives_form) {
            //SELECTS EACH FORM TO CREATE THE TABLE
            $form = $form_collectives_form->form;

            //CONCATENATE THE PARENT TABLE NAME WITH THE SELECT FORM TABLE NAME
            $tableName = str_plural($parent_table."_".$form->table_name);

            //GET ALL THE FIELDS BELONGING TO THE SELECTED FORM
            $form_fields = $form->fields;

            //CREATE THE SCHEMA FOR THE SELECTED FORM
            //CHECKS IF THE TABLE ALREADY EXISTS
            if (Schema::hasTable(str_plural($tableName))) {

                //UPDATES THE TABLE BY ADDING THE NEW COLUMNS
                $this->update_form_table($tableName,$form_fields,$parent_form,$form,$hasChild=false);
            }
            else{
                //CREATES A NEW TABLE
                $this->generate_form_table($tableName,$form_fields,$parent_form,$form,$hasChild=false);
            }

            //SET COUNTER TO THE TOTAL NUMBER OF FORMS EXPECTED TO BE GENERATED
            $counter +=1;
        }

        //COMPARE THE COUNTER TO THE TOTAL EXPECTED FORM TO GENERATE AND SET 'generate = true'
        if ($total_forms == $counter){

            $command = 'make:model';

            $params = [
                'name' => studly_case(str_singular($parent_form->table_name)),
            ];

            //CREATES A MODEL FOR THE PARENT FORM TABLE IN THE DEFAULT MODELS DIRECTORY
            Artisan::call($command, $params);

            //SETS THE GENERATE COLUMN TO TRUE
            $form_collective->generate = false;
            $form_collective->save();
        }
        notify(new ToastNotification('Successful','The Form and its related tables have been generated.','success'));
        session()->flash('status','Table created.');
        return back();
    }

    /**
     * @param $tableName
     * @param $form_fields
     * @param $parent_form
     * @param $form
     * @param bool $hasChild
     */
    protected function update_form_table($tableName,$form_fields,$parent_form,$form,$hasChild=false){

        Schema::table($tableName, function (Blueprint $table) use ($form_fields,$parent_form,$tableName,$form,$hasChild) {

            $parent_id = str_singular($parent_form->table_name).'_id';
            if (!Schema::hasColumn($tableName, $parent_id)) {

                $table->unsignedInteger($parent_id);
                $table->foreign($parent_id,substr($form->table_name.'_'.$parent_form->table_name, 0,10))
                    ->references('id')
                    ->on(str_plural($parent_form->table_name))
                    ->onDelete('cascade');
            }
            //BUILDING THE DATA-TYPES AND COLUMNS USING THE FIELDS ATTRIBUTES AND FIELDS RESPECTIVELY
            foreach ($form_fields as $form_field) {
                $name = str_replace('-','_',$form_field->name);
                if (Schema::hasColumn($tableName, $name)) {
                    continue;
                }else{
                    if (in_array($form_field->input_type, ['color', 'checkbox', 'radio', 'select', 'email', 'file', 'password', 'text', 'numeric_text'])) {
                        
                        $table->string($name)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['number'])) {
                        
                        $table->float($name)->nullable();
                        
                    } elseif (in_array($form_field->input_type,['tel'])){
                        
                        $table->char($name,20)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['textarea'])) {
                        
                        $table->text($name)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['date'])) {
                        
                        $table->dateTime($name)->nullable();
                        
                    }
                    elseif (in_array($form_field->input_type,['time'])){
                     
                        $table->time($name)->nullable();
                        
                    }
                }
            }
            //END OF BUILDING
        });

        if ($form->sub_forms->count() > 0){

            //LOOPS THROUGH  THE LIST OF SUB-FORMS TO GENERATE TABLES
            foreach($form->sub_forms as $s_form){
                $form = $s_form->form;

                //SELECTS EACH FORM TO CREATE THE TABLE
                $parent_table = $parent_form->table_name;
                //CONCATENATE THE PARENT TABLE NAME WITH THE SELECT FORM TABLE NAME
                $tableName = str_plural($parent_table."_sub_".$form->table_name);

                //GET ALL THE FIELDS BELONGING TO THE SELECTED FORM
                $form_fields = $form->fields;

                //CHECK IF THE SUB-FORM ALREADY EXIST
                if (Schema::hasTable(str_plural($tableName))) {

                    //UPDATES THE TABLE BY ADDING THE NEW COLUMNS
                    $this->update_form_table($tableName,$form_fields,$parent_form,$form,TRUE);
                }
                else{
                    //CREATES A NEW TABLE
                    $this->generate_form_table($tableName,$form_fields,$parent_form,$form,TRUE);
                }
            }
        }
    }

    /**
     * @param $tableName
     * @param $form_fields
     * @param $parent_form
     * @param $form
     * @param bool $hasChild
     */
    protected function generate_form_table($tableName,$form_fields,$parent_form,$form,$hasChild=false){
        Schema::create($tableName, function (Blueprint $table) use ($form_fields,$parent_form,$form,$hasChild) {
            $parent_id = str_singular($parent_form->table_name).'_id';
            $table->increments('id');
            $table->unsignedInteger($parent_id);
            $table->foreign(str_singular($parent_form->table_name).'_id',
                substr($form->table_name.'_'.$parent_form->table_name, 0,10))
                ->references('id')
                ->on(str_plural($parent_form->table_name))
                ->onDelete('cascade');

            foreach ($form_fields as $form_field) {

                $name = str_replace('-','_',$form_field->name);
                if (in_array($form_field->input_type, ['color', 'checkbox', 'radio', 'select', 'email', 'file', 'password', 'text', 'numeric_text'])) {

                    $table->string($name)->nullable();

                } elseif (in_array($form_field->input_type, ['number'])) {
                    
                    $table->float($name)->nullable();
                    
                } elseif (in_array($form_field->input_type,['tel'])){
                    
                    $table->char($name,20)->nullable();

                } elseif (in_array($form_field->input_type, ['textarea'])) {

                    $table->text($name)->nullable();

                } elseif (in_array($form_field->input_type, ['date'])) {
                    
                    $table->dateTime($name)->nullable();
                    
                }elseif (in_array($form_field->input_type,['time'])){
                    
                    $table->time($name)->nullable();

                }
            }

            $table->timestamps();
        });
    }
}
