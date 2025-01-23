<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rest_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_time_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->date('at_rest_date');
            $table->date('finish_rest_date')->nullable();
            $table->time('at_rest_time');
            $table->time('finish_rest_time')->nullable();
            $table->time('total_rest_time')->nullable();
            $table->timestamp('created_at')->useCurrent()->nullable();
            $table->timestamp('updated_at')->useCurrent()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rest_times');
    }
}
