<?php

$namespace = "FormBuilder\Http\Controllers";

Route::group([
    'namespace' =>  $namespace,
    'prefix'    =>  'form-builder',
    'middleware'    =>  'web'
], function (){

    Route::get('/forms', 'FormsController@index')->name('forms');
    Route::get('/create', 'FormsController@create')->name('form.add');
    Route::post('/create', 'FormsController@store')->name('form.save');
    Route::get('/edit/{form_id}', 'FormsController@edit')->name('form.edit');
    Route::put('/update/{form_id}', 'FormsController@update')->name('form.update');
    Route::get('/change-status/{form_id}', 'FormsController@activate')->name('form.activate');
    Route::get('/build-fields/{form_id}', 'FormsController@build_form')->name('form.builder');
    Route::post('/build-fields/{form_id}', 'FormsController@save_field')->name('form.builder.save');
    Route::get('/form-preview/{form_id}', 'FormsController@form_preview')->name('form.preview');
    Route::get('/form-remove/{form_id}', 'FormsController@destroy')->name('form.delete');

    Route::get('form-field-edit', 'FormsController@edit_field')->name('form_field.edit');
    Route::put('form-field-update', 'FormsController@update_field')->name('form_field.update');
    Route::delete('form-field-delete/{id}', 'FormsController@destroy_field')->name('form_field.delete');

    //  Generating Form Table
    Route::get('generate/form-table/{id}', 'FormsController@create_form_table')->name('create_form_table');
    Route::post('form/generate-form', 'FormsController@create_form_table')->name('form.generate_form');

    Route::get('/collective-forms', function (){
        return "Collective Forms";
    })->name('form_collectives');
});
