<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditPermissionsToAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academics', function($table){
            $table->boolean('student_profile_edit')->nullable();;
            $table->boolean('staff_profile_edit')->nullable();;
            $table->boolean('schedule_update')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('academics', function($table){
            $table->dropColumn('student_profile_edit');
            $table->dropColumn('staff_profile_edit');
            $table->dropColumn('schedule_update');
        });
    }
}
