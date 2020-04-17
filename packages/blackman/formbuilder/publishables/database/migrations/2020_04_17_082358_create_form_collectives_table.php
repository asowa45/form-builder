<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormCollectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('form_collectives');
        Schema::create('form_collectives', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_id');
            $table->foreign('form_id')
                ->references('id')->on('forms')
                ->onDelete('cascade');
            $table->string('structure_type')->default('tabs');
            $table->string('submit_type')->default('individual');
            $table->string('process_type')->default('steps');
            $table->boolean('active')->default(true);
            $table->boolean('generate')->default(false);
            $table->boolean('cover_page')->default(false);
            $table->boolean('user_id');
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
        Schema::dropIfExists('form_collectives');
    }
}
