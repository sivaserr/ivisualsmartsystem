<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdsToStudentsSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_schedules', function($table){
            $table->integer('staff_id2')->nullable();
            $table->integer('staff_id3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_schedules', function($table){
            $table->dropColumn('staff_id2');
            $table->dropColumn('staff_id3');
        });
    }
}
