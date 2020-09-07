<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('creator')->unsigned();
            $table->integer('problem_id')->unsigned();
            $table->boolean('in_work')->default(false);
            $table->string('status')->nullable()->default(null);
            $table->date('deadline')->nullable();
            $table->integer('executor')->unsigned()->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator')->references('id')->on('users');
            $table->foreign('executor')->references('id')->on('users');
            $table->foreign('problem_id')->references('id')->on('problems');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solutions');
    }
}
