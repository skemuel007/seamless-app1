<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->unsigned()->unique();
            $table->bigInteger('user_id')->unsigned()->unique();
            $table->timestamps();

            // create foreign key relationship
            $table->foreign('course_id')->references('id')
                ->on('courses')->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')->references('id')
                ->on('users')->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_registrations');
    }
}
