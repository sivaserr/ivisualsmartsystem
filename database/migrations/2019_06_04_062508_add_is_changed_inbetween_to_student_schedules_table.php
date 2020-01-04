<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsChangedInbetweenToStudentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_schedules', function($table){
            $table->boolean('is_changed_inbetween')->dafault(false);
            $table->date('changed_date')->nullable();
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
            $table->dropColumn('is_changed_inbetween');
            $table->dropColumn('changed_date');
        });
    }
}
