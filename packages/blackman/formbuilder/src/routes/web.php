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

    //Form Collectives
    Route::get('/form-collectives', 'FormCollectiveController@index')->name('form_collectives');
    Route::get('/form-collective/{id}/edit', 'FormCollectiveController@edit')->name('form_collective.edit');
    Route::get('/form-collective/{id}/create', 'FormCollectiveController@create')->name('form_collective.create');
    Route::get('/form-collective/{id}/view', 'FormCollectiveController@view')->name('form_collective.view');
    Route::post('/form-collective/{id}/save', 'FormCollectiveController@save')->name('form_collective.save');
    Route::get('/form-collective/{formId}/render', 'FormCollectiveController@form_render')->name('form_collective.render');
    Route::get('/form-collective/{formId}/preview', 'FormCollectiveController@form_preview')->name('form_collective.preview');
//Route::get('form-collective/{id}/edit','FormCollectiveController@edit')->name('form_collective.edit');
    Route::put('/form-collective/{id}/update', 'FormCollectiveController@update')->name('form_collective.update');
    Route::get('/form-collective/activate/{id}', 'FormCollectiveController@activate')->name('form_collective.activate');
    Route::get('/generate/form-collective-tables/{id}', 'FormsController@generate_tables')->name('form_collective.generate_tables');
    Route::delete('/form-collective/{id}', 'FormCollectiveController@destroy')->name('form_collective.delete');
    Route::delete('/form-collective/{id}/form', 'FormCollectiveController@delete_form')->name('form_collective.form.delete');
    Route::post('/form-collective/generate-form', 'FormCollectiveController@generate_collective_form_tables')->name('generate_collective_form_tables');

    Route::post('/submit-form','FormsController@submitRenderedForm')->name('submit_form');

    Route::get('/download', 'FormsController@getDownload')->name('download_file');
    Route::get('/set-field-null', 'FormsController@remove_input_file')->name('form_value_input.nullable');
    Route::delete('/set-field-null', 'FormsController@remove_file')->name('form_value.nullable');
});
