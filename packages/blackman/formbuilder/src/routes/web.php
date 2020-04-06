<?php

$namespace = "Formbuilder\Http\Controllers";

Route::group([
    'namespace' =>  $namespace,
    'prefix'    =>  'form-builder'
], function (){
    Route::get('/', 'FormsController@index');
});
