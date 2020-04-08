<?php

$namespace = "FormBuilder\Http\Controllers";

Route::group([
    'namespace' =>  $namespace,
    'prefix'    =>  'form-builder',
    'middleware'    =>  'web'
], function (){

    Route::get('/', 'FormsController@index')->name('forms');
    Route::get('/create', 'FormsController@create')->name('form.add');
    Route::post('/create', 'FormsController@store')->name('form.save');
    Route::get('/edit/{form_id}', 'FormsController@edit')->name('form.edit');
    Route::put('/update/{form_id}', 'FormsController@update')->name('form.update');
    Route::get('/change-status/{form_id}', 'FormsController@activate')->name('form.activate');
    Route::get('/build-fields', 'FormsController@build_fields')->name('form.builder');
    Route::get('/form-preview/{form_id}', 'FormsController@form_preview')->name('form.preview');
    Route::get('/form-remove/{form_id}', 'FormsController@destroy')->name('form.delete');

    Route::get('/collective-forms', function (){
        return "Collective Forms";
    })->name('form_collectives');
});
