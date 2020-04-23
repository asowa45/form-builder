<?php
/**
 * Created by PhpStorm.
 * User: Sowee
 * Date: 20/04/2020
 * Time: 6:29 PM
 */

namespace FormBuilder\src\Helpers;


use FormBuilder\Models\Form;
use Illuminate\Support\Facades\DB;

class FormRender
{
    protected $editable;

    protected $submit_url;



    /**
     * This function renders the form view for submission
     * @param (string or integer) $form
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function renderForm($form)
    {
        $form = Form::where('table_name', '=', $form)->orWhere('id', '=', $form)->first();
        $form_collective = $form->form_collective;
        $structure = $form->form_collective->structure_type;
        $contain_file = $this->containFile($form_collective->id);
        $form_collectives_forms = $form->form_collective->form_collectives_forms->where('active', '=', 1)->sortBy('order');
        $view = view('formbuilder::component.forms.render.render', compact(
            'form', 'structure', 'contain_file', 'form_collectives_forms'
        ))->render();
        return response()->json($view);
    }

    public function containFile($form_collective_id)
    {
        $contain_file = DB::table('form_collectives_forms')
            ->join('forms', 'form_collectives_forms.form_id', '=', 'forms.id')
            ->join('fields', 'forms.id', '=', 'fields.form_id')
            ->where('form_collective_id', '=', $form_collective_id)
            ->where('fields.input_type', '=', 'file')
            ->count();
        if ($contain_file > 0){
            return true;
        }
        else {
            return false;
        }
    }
}