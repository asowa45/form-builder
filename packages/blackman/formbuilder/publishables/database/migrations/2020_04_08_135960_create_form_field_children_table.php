<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormFieldChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('form_field_children');
        Schema::create('form_field_children', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')
                ->on('forms')->onDelete('cascade');
            $table->unsignedInteger('parent_form_id');
            $table->string('name',250);
            $table->string('value',250);
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
        Schema::dropIfExists('form_field_children');
    }
}
