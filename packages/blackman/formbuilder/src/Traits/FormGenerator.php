<?php
namespace FormBuilder\Traits;

use FormBuilder\Models\Form;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use \Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


trait FormGenerator
{
    protected $rules = array();

    public function generate_form($form_id,$parent_table=null)
    {
        $form = Form::find($form_id);
        $form_fields = $form->fields;
        $table = Str::plural($form->table_name);
        if ($parent_table == null){
            $table_name = Str::plural($form->table_name);
        }
        else{
            $table_name = Str::plural($parent_table).'_'.Str::plural($form->table_name);
        }

        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function (Blueprint $table) use ($form_fields,$parent_table) {
                $table->increments('id');
                if ($parent_table != null){
                    $table->integer($parent_table.'_id');
                }
                foreach ($form_fields as $form_field) {

                    if (in_array($form_field->input_type, ['color', 'checkbox', 'select', 'email', 'file', 'password', 'text'])) {

                        $table->string($form_field->name)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['number'])) {
                        
                        $table->float($form_field->name)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['tel'])) {
                        
                        $table->char($form_field->name, 20)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['textarea'])) {
                        
                        $table->text($form_field->name)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['date'])) {
                        
                        $table->dateTime($form_field->name)->nullable();
                        
                    } elseif (in_array($form_field->input_type, ['time'])) {
                        
                        $table->time($form_field->name)->nullable();
                        
                    }
                }
                $table->timestamps();
            });
            $command = 'make:model';
            $params = [
                'name' => Str::studly(Str::singular($table_name)),
            ];

            //CREATES A MODEL FOR THE PARENT FORM TABLE IN THE DEFAULT MODELS DIRECTORY
            Artisan::call($command, $params);
        }

        if ($form->sub_forms->isNotEmpty()) {
            $subforms = $form->sub_forms;
            foreach($subforms as $form){
                $this->generate_form($form->form_id,$table);
            }
        }
    }
}
