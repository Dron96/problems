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
            $table->string('name', 250);
            $table->unsignedBigInteger('problem_id');
            $table->string('status')->nullable()->default(null);
            $table->date('deadline')->nullable();
            $table->unsignedBigInteger('executor_id')->nullable();
            $table->string('plan')->nullable();
            $table->string('team')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('executor_id')->references('id')->on('users');
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
