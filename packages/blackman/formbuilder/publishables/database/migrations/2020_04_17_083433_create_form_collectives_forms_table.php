<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormCollectivesFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('form_collectives_forms');
        Schema::create('form_collectives_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_collective_id');
            $table->foreign('form_collective_id')
                ->references('id')->on('form_collectives')
                ->onDelete('cascade');
            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')->on('forms')
                ->onDelete('cascade');
            $table->tinyInteger('order');
            $table->boolean('active');
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
        Schema::dropIfExists('form_collectives_forms');
    }
}
