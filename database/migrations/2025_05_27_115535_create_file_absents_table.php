<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileAbsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_absents', function (Blueprint $table) {
            $table->id();
             $table->string('cadetno');
             $table->string('name');
             $table->string('company');
             $table->string('events');
            $table->date('date');
            $table->string('time');
            $table->string('reason');
            $table->integer('status');
            $table->date('date_approved')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('training_id');
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
        Schema::dropIfExists('file_absents');
    }
}
