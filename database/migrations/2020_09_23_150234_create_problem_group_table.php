<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_problem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('problem_id');
            $table->unsignedBigInteger('group_id');

            $table->foreign('problem_id')->references('id')->on('problems');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_problem');
    }
}
