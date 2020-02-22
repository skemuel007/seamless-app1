<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // three (3) new columns defined for courses table
            $table->string('course_name')->unique();
            $table->string('course_code')->unique();
            $table->bigInteger('unit')->default(3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            // drop tables when migration is reversed
            $table->dropUnique('course_name');
            $table->dropUnique('course_code');
        });

        Schema::table('courses', function (Blueprint $table) {
            // drop tables when migration is reversed
            $table->dropColumn('unit');
        });
    }
}
