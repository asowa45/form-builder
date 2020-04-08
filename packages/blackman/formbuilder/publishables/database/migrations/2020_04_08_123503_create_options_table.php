<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fullname');
            $table->string('shortname');
            $table->unsignedInteger('lookup_option_id');
//            $table->foreign('lookup_option_id')
//                ->references('lookup_options')->on('id')
//                ->onDelete('cascade');
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
        Schema::dropIfExists('options');
    }
}
