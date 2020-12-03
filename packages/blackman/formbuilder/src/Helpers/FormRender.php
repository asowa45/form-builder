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
    protected $editable = true;

    protected $submit_url;

    protected $request = null;
    protected $step = 1;
    protected $request_id = null;
    protected $request_placeholder_info = null;
    protected $structure = null;
    protected $form_collective = null;
    protected $contain_file = null;
    protected $form_collectives_forms = null;
    protected $is_collective = false;

    public function submitUrl($submit_url)
    {
        $this->submit_url = $submit_url;
    }

    public function request($request)
    {
        $this->request = $request;
    }

    public function step($step)
    {
        $this->step = $step;
    }

    public function request_id($request_id)
    {
        $this->request_id = $request_id;
    }

    public function request_placeholder_info($request_placeholder_info)
    {
        $this->request_placeholder_info = $request_placeholder_info;
    }

    public function structure($structure)
    {
        $this->structure = $structure;
    }

    public function form_collective($form_collective)
    {
        $this->form_collective = $form_collective;
    }

    public function contain_file($contain_file)
    {
        $this->contain_file = $contain_file;
    }

    public function form_collectives_forms($form_collectives_forms)
    {
        $this->form_collectives_forms = $form_collectives_forms;
    }

    public function is_collective($is_collective)
    {
        $this->is_collective = $is_collective;
    }

    /**
     * This function renders the form view for submission
     * @param string $table_name
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function renderHtmlForm($table_name)
    {
        $form = Form::where('table_name', '=', $table_name)->first();

        //////////////////////////////////////////////////////////////

        $submit_url = $this->submit_url;
        $request = $this->request;
        $step = $this->step;
        $editable = $this->editable;
        $request_id = $this->request_id;
        $request_placeholder_info = $this->request_placeholder_info;
        $structure = $this->structure;
        $form_collective = $this->form_collective;
        $contain_file = $this->contain_file;
        $form_collectives_forms = $this->form_collectives_forms;
        $is_collective = $this->is_collective;

        if ($form->collective) {
            $this->is_collective = true;
        }

        if ($this->is_collective) {
            $structure = $form->form_collective->structure_type;
            $form_collective = $form->form_collective;
            $contain_file = $this->containFile($form_collective->id);
            $form_collectives_forms = $form->form_collective->form_collectives_forms
                ->where('active', '=', 1)->sortBy('order');
        }

        $view = view('formbuilder::components.forms.render.render', compact('form', 'request', 'step', 'contain_file',
            'request_id', 'structure', 'editable', 'form_collective', 'is_collective',
            'form_collectives_forms', 'submit_url', 'request_placeholder_info'))->render();
        return response()->json($view);

        //////////////////////////////////////////////////////////////

//        $form_collective = $form->form_collective;
//        $structure = $form->form_collective->structure_type;
//        $contain_file = $this->containFile($form_collective->id);
//        $form_collectives_forms = $form->form_collective->form_collectives_forms->where('active', '=', 1)->sortBy('order');
//        $view = view('formbuilder::component.forms.render.render', compact(
//            'form', 'structure', 'contain_file', 'form_collectives_forms'
//        ))->render();
//        return response()->json($view);
    }

    /**
     * @param $table_name
     * @param array|null $view_data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function renderView($table_name,array $view_data = null)
    {
        $form_view = $this->renderHtmlForm($table_name);
        $form = Form::where('table_name', '=', $table_name)->first();
        $_data = [
            'form_view'=>$form_view,
            'form'=>$form,
            'editable'=>$this->editable,
        ];
        if ($view_data) {
            $data = array_merge($_data,$view_data);
        }
        else {
            $data = $_data;
        }

        return view('formbuilder::components.forms.render.render_form', $data);
    }

    /**
     * This function is for custom rendering of the form view for submission
     * @param string $table_name
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function renderedForm($table_name)
    {
        $form = Form::where('table_name', '=', $table_name)->first();

        //////////////////////////////////////////////////////////////

        $submit_url = $this->submit_url;
        $request = $this->request;
        $step = $this->step;
        $editable = $this->editable;
        $request_id = $this->request_id;
        $request_placeholder_info = $this->request_placeholder_info;
        $structure = $this->structure;
        $form_collective = $this->form_collective;
        $contain_file = $this->contain_file;
        $form_collectives_forms = $this->form_collectives_forms;
        $is_collective = $this->is_collective;

        if ($form->collective) {
            $this->is_collective = true;
        }

        if ($this->is_collective) {
            $structure = $form->form_collective->structure_type;
            $form_collective = $form->form_collective;
            $contain_file = $this->containFile($form_collective->id);
            $form_collectives_forms = $form->form_collective->form_collectives_forms
                ->where('active', '=', 1)->sortBy('order');
        }

        $view = view('formbuilder::components.forms.render.render', compact('form', 'request', 'step', 'contain_file',
            'request_id', 'structure', 'editable', 'form_collective', 'is_collective',
            'form_collectives_forms', 'submit_url', 'request_placeholder_info'))->render();
        return $view;
    }

    /**
     * This function is only for custom form rendering view
     * @param $table_name
     * @param array|null $view_data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function renderFormView($table_name,array $view_data = null)
    {
        $form_view = $this->renderedForm($table_name);
        $form = Form::where('table_name', '=', $table_name)->first();
        $_data = [
            'form_view'=>$form_view,
            'form'=>$form,
            'editable'=>$this->editable,
        ];

        if ($view_data) {
            $data = array_merge($_data,$view_data);
        }
        else {
            $data = $_data;
        }

        return  $data;
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