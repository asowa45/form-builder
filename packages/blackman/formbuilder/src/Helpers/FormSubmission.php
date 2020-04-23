<?php
namespace FormBuilder\Traits;

use FormBuilder\Models\Form;
use FormBuilder\Models\FormCollective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use \Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


trait FormSubmission
{
    protected $rules = array();

    public function submit(Request $request) {

        if (isset($request->collective_id)) {
            $requestFormCollective = FormCollective::find($request->collective_id);
            $requestForm = $requestFormCollective->form;
        } else {
            $requestForm = Form::find($request->form_id);
        }

        if ($requestForm->collective == 1) {
            $request_id = $this->form_collective($request);
        } else {
            $request_id = $this->form_single($request);
        }
        return $request_id;
    }
    public function form_collective(Request $request)
    {
        //GET THE PARTICULAR FORM COLLECTIVE DETAILS
        $form_collective = FormCollective::find($request->collective_id);
        $theRequest_id = null;
        if ($request->request_id != null){
            $theRequest_id = Crypt::decrypt($request->request_id);
        }
        if (!$form_collective){
            session()->flash('error','Sorry, something went wrong!');
            return back();
        }

        //GET THE PARENT FORM COLLECTIVE
        $parent_form = $form_collective->form;

        //GET THE FORMS ASSOCIATED TO THE FORM COLLECTIVE SELECTED
        $form_collectives_form = $form_collective->form_collectives_forms->where('form_id','=',$request->form_id)->first();

        //SELECTS EACH FORM TO CREATE THE TABLE
        $form = $form_collectives_form->form;

        //GET ALL THE FIELDS BELONGING TO THE SELECTED FORM
        $form_fields = $form->fields;
        /**
         * Apply the Validation Rule for the form here
         *
         * $this->validate($request,[array of rules]);
         *
         */
        $rules = $this->rules;
        if ($rules == null ){
            $rules = $this->validation_rules($form_fields);
        }
        try{
            $this->validate($request,$rules);
        }
        catch (\Exception $exception){}

        //INSERT INTO THE PARENT TABLE AND RETRIEVE THE ID TO BE INSERTED INTO THE SUBSEQUENT TABLES
        $parent_data = array();
        $parent_data['status'] = 0;
        $parent_data['updated_at'] = date('Y-m-d H:i:s', strtotime(now()));

        //CHECKS IF THE REQUEST ALREADY EXIST
        if ($theRequest_id == null){
            if($form_collective->process_type == 'steps'){
                $parent_data['step'] = $request->step;
            }
            else{
                $parent_data['status'] = 1;
            }
            $parent_data['user_id'] = Auth::id();
            $parent_data['created_at'] = date('Y-m-d H:i:s', strtotime(now()));
            $theRequest_id = DB::table($parent_form->table_name)->insertGetId($parent_data);
        }
        else{
             DB::table($parent_form->table_name)
                ->where('id', $theRequest_id)
                ->first();
            if($form_collective->process_type == 'steps'){
                $parent_data['step'] = $request->step;
            }
            else{
                $parent_data['status'] = 1;
            }
            DB::table($parent_form->table_name)
                ->where('id','=', $theRequest_id)
                ->update($parent_data);
        }

        //CONCATENATE THE PARENT TABLE NAME WITH THE SELECT FORM TABLE NAME
        $tableName = Str::plural(Str::plural($parent_form->table_name)."_".$form->table_name);
        //CREATE THE SCHEMA FOR THE SELECTED FORM
        //CHECKS IF THE TABLE ALREADY EXISTS
        $this->updateIfTableExist($tableName,$theRequest_id,$parent_form,$request,$form->id);

        if (isset($request->child_form_id) and sizeof($request->child_form_id) > 0){

            for ($a=0 ; $a < sizeof($request->child_form_id) ; $a++){
                $formId = $request['child_form_id'][$a];
                $tbName = $request['tb_name'][$a];
                $this->updateIfTableExist($tbName,$theRequest_id,$parent_form,$request,$formId);
            }
        }
        return Crypt::encrypt($theRequest_id);
    }

    public function form_single(Request $request)
    {
        //GET THE FORM DETAILS
        $form = Form::find($request->form_id);

        $theRequest_id = null;
        if ($request->request_id != null){
            $theRequest_id = Crypt::decrypt($request->request_id);
        }

        if (!$form){
            session()->flash('error','Sorry, something went wrong!');
            return back();
        }

        //GET ALL THE FIELDS BELONGING TO THE SELECTED FORM
        $form_fields = $form->fields;
        /**
         * Apply the Validation Rule for the form here
         *
         * $this->validate($request,[array of rules]);
         *
         */
        $rules = $this->rules;
        if ($rules == null ){
            $rules = $this->validation_rules($form_fields);
        }

        try{
            $this->validate($request,$rules);
        }
        catch (\Exception $exception){}

        //INSERT INTO THE TABLE AND RETRIEVE THE ID
        $parent_data = array();
        $form_values = null;
        $parent_data['updated_at'] = date('Y-m-d H:i:s', strtotime(now()));

        //CHECKS IF THE REQUEST ALREADY EXIST
        if ($theRequest_id == null){
            $parent_data['created_at'] = date('Y-m-d H:i:s', strtotime(now()));

            $form_values = $this->gather_form_data($request,$form_fields,$parent_data);

            $theRequest_id = DB::table($form->table_name)->insertGetId($form_values);
        }
        else{
            $form_values = $this->gather_form_data($request,$form_fields,$parent_data);
            DB::table($form->table_name)
                ->where('id','=', $theRequest_id)
                ->update($form_values);
        }
        return Crypt::encrypt($theRequest_id);
    }

    /**
     * @param $request
     * @param $form_fields
     * @param array $form_values
     * @return array
     */
    public function gather_form_data($request,$form_fields,$form_values = array())
    {
        foreach ($form_fields as $form_field){
            if ($form_field->name == 'more_notes' || $form_field->input_type == 'label' || $form_field->contains_data == 0){
                continue;
            }

            if($form_field->input_type == 'file') {

                $keys = array_keys($request->all());
                $fieldName = $form_field->name;
                if (!in_array($fieldName, $keys)) {
                    continue;
                }

                if ($request->hasFile($form_field->name)) {
                    // Get filename with extension
                    $filenameWithExt = $request->file($form_field->name)->getClientOriginalName();
                    // Get just filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    // Get just ext
                    $extension = $request->file($form_field->name)->getClientOriginalExtension();
                    //Filename to store
                    $filename = str_replace(' ', '_', $filename);
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    // Upload file
                    $request->file($form_field->name)->storeAs('public/attachments', $fileNameToStore);
                    $form_values[$form_field->name] = $fileNameToStore;
                }
            }
            else {
                if ($form_field->input_type == 'date'){
                    $form_values[$form_field->name] = date('Y-m-d H:i',strtotime($request[$form_field->name]));
                }
                else{
                    $form_values[$form_field->name] = $request[$form_field->name];
                }
            }
        }
        return $form_values;
    }

    /**
     * @param $tableName
     * @param $theRequest_id
     * @param $parent_form
     * @param $request
     * @param $form_id
     * @return int|mixed
     */
    public function updateIfTableExist($tableName,$theRequest_id,$parent_form,$request,$form_id)
    {
        $form = Form::find($form_id);
        $form_fields = $form->fields;

        if (Schema::hasTable($tableName)) {

            //CONTINUE IF THE TABLE EXIST
            $form_values = array();
            $parent_id = $theRequest_id;
            $parent_fk_id = Str::singular($parent_form->table_name).'_id';
            $form_values[$parent_fk_id] = $parent_id;
            $form_values['updated_at'] = date('Y-m-d H:i:s', strtotime(now()));

            //Collate all the fields of the form
            $theform_values = $this->gather_form_data($request,$form_fields,$form_values);

            //CHECK IF TABLE ALREADY CONTAIN A DATA WITH RESPECT TO THE PARENT FK ID
            if($tableRecord = DB::table($tableName)->where($parent_fk_id,'=',$parent_id)->first()){

                DB::table($tableName)
                    ->where($parent_fk_id,'=', $parent_id)
                    ->update($theform_values);
                return $tableRecord->id;
            }
            else{
                $theform_values['created_at'] = date('Y-m-d H:i:s', strtotime(now()));
                return $this->perform_insertion($tableName,$theform_values);
            }
        }
    }

    /**
     * @param $form_fields
     * @return array
     */
    public function validation_rules($form_fields)
    {
        $rules = array();
        foreach ($form_fields as $field){
            if($field->contains_data == 0){
                continue;
            }
            if (isset($field->rules)){
                $rules[$field->name] = $field->rules;
            }
        }
        return $rules;
    }

    //RETURNS THE TABLE ID
    public function perform_insertion($tableName = null,$form_values = null)
    {
        $id = DB::table($tableName)->insertGetId($form_values);
        return $id;
    }

    /**
     * @param object $request
     * @param int $request_id
     * @return bool
     * This function is to check if the request form has been completed and can be submitted.
     * Thus the submit button is enabled for submission.
     */
    public function canSubmission(object $request,int $request_id)
    {
        //Get the array list of all expected forms id to be completes before submission
        $form_collective = FormCollective::find($request->collective_id);
        $form_collectives_form = $form_collective->form_collectives_forms->where('form_collective_id','=',$request->collective_id)->pluck('form_id');
        $expectedForms = $form_collectives_form->toArray();

        //Check if the request has gone or is going through the process for SUBMISSION
        $submission = RequestSubmissionTracker::where('clearance_request_id','=',$request_id)->first();

        if (!$submission){
            //Create a new record for a request that has beginning the submission process
            $newSubmission = RequestSubmissionTracker::create([
                'clearance_request_id' => $request_id,
                'expected_form_id' => json_encode($expectedForms),
                'remaining_form_id' => json_encode($expectedForms),
            ]);
        }

        //If a new record is inserted, assign the object of the new to the variable $submission
        if (isset($newSubmission)){
            $submission = $newSubmission;
        }

        //Check if the current form has been saved before during the process
        $remainingForms = json_decode($submission->remaining_form_id);


//        $isCargo = DB::table('clearance_requests_cargo_infos')
//            ->where('clearance_request_id','=',$request_id)
//            ->where('information_on_cargos','=','Cargo')
//            ->first();
//
//        if ($isCargo){
//            $cargo_options = DB::table('clearance_requests_sub_ct_cargos')
//                ->where('clearance_request_id','=',$request_id)
//                ->whereIn('type_of_cargos',['Containerised','Vehicles'])
//                ->first();
//            if ($cargo_options){
//                if (($key = array_search(9, $remainingForms)) !== false) {
//                    unset($remainingForms[$key]);
//                }
//                if (($key = array_search(11, $remainingForms)) !== false) {
//                    unset($remainingForms[$key]);
//                }
//            }
//        }

        if (($key = array_search($request->form_id, $remainingForms)) !== false) {
            unset($remainingForms[$key]);
        }

        $submission->remaining_form_id = json_encode(array_values($remainingForms));
        $submission->save();

        //Return true if remaining forms is null else return false
        if (sizeof(json_decode($submission->remaining_form_id)) < 1) {
            $clearanceRequest = ClearanceRequest::find($request_id);
            $clearanceRequest->status = 1;
            $clearanceRequest->save();
            return true;
        }
        else{
            return false;
        }
    }
}
