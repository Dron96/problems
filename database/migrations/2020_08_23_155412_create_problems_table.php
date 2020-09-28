<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->unsignedBigInteger('creator_id');
            $table->string('description', 350)->nullable();
            $table->string('possible_solution', 250)->nullable();
            $table->string('status')->default('На рассмотрении');
            $table->string('experience', 350)->nullable();
            $table->string('result', 350)->nullable();
            $table->string('urgency')->default('Обычная');
            $table->string('importance')->default('Обычная');
            $table->unsignedInteger('progress')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problems');
    }
}
