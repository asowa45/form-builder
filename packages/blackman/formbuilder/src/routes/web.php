<?php

$namespace = "Formbuilder\Http\Controllers";

Route::group([
    'namespace' =>  $namespace,
    'prefix'    =>  'form-builder'
], function (){
    Route::get('/', function () {
        return ['hello','Here we are!!!'];
    });
});
