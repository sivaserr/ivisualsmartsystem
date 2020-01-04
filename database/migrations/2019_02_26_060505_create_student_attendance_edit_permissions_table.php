<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentAttendanceEditPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendance_edit_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('degree');
            $table->string('department');
            $table->integer('year');
            $table->integer('semester');
            $table->string('section');
            $table->boolean('status');
            $table->date('semester_start');
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
        Schema::dropIfExists('student_attendance_edit_permissions');
    }
}
