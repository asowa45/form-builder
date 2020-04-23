<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fields');
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')
                ->on('forms')->onDelete('cascade');;
            $table->string('input_type',100);
            $table->string('name',250);
            $table->string('label',250);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('order');
            $table->json('attributes')->nullable();
            $table->json('options')->nullable();
            $table->json('rules')->nullable();
            $table->string('data_type',50)->nullable();
            $table->string('placeholder',250)->nullable();
            $table->string('class',250)->nullable();
            $table->string('inline_css',250)->nullable();
            $table->tinyInteger('column_size')->default(6);
            $table->string('file_types',100)->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->string('default_value',254)->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('disabled')->default(false);
            $table->boolean('autocomplete')->default(true);
            $table->float('step')->default(1);
            $table->boolean('checked')->default(false);
            $table->boolean('is_multiple')->default(false);
            $table->boolean('is_dropdown_button')->default(false);
            $table->text('button_dropdown_options')->nullable();
            $table->text('button_url')->nullable();
            $table->tinyInteger('showBy')->default(2);
            $table->string('auto_options', 100)->nullable();
            $table->tinyInteger('has_auto_options')->default(false);
            $table->tinyInteger('hasChild')->default(false);
            $table->json('forms')->nullable();
            $table->string('workflow_actors',100)->nullable();
            $table->boolean('contains_data')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
