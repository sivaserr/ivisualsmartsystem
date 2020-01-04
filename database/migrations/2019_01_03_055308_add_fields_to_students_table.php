<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student', function($table){
            $table->string('parent_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('scholar')->nullable();
            $table->string('bus_number')->nullable();
            $table->string('room_number')->nullable();
            $table->integer('tenth_percentage')->nullable();
            $table->string('twelveth_or_diploma')->nullable();
            $table->integer('twelveth_percentage')->nullable();
            $table->integer('twelveth_cutoff')->nullable();
            $table->integer('diploma_percentage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student', function($table){
            $table->dropColumn('parent_phone');
            $table->dropColumn('address');
            $table->dropColumn('scholar');
            $table->dropColumn('bus_number');
            $table->dropColumn('room_number');
            $table->dropColumn('tenth_percentage');
            $table->dropColumn('twelth_or_diploma');
            $table->dropColumn('twelth_percentage');
            $table->dropColumn('twelth_cutoff');
            $table->dropColumn('diploma_percentage');
        });
    }
}
