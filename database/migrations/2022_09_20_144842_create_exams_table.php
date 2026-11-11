<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('category_id');
            $table->uuid('question_title_id');
            $table->string('title');
            $table->double('price');
            $table->integer('duration_type');
            $table->integer('duration')->nullable();
            $table->text('description');
            $table->tinyInteger('random_question');
            $table->tinyInteger('random_answer');
            $table->tinyInteger('show_answer');
            $table->tinyInteger('show_question_number_navigation');
            $table->tinyInteger('show_question_number');
            $table->tinyInteger('next_question_automatically');
            $table->tinyInteger('show_prev_next_button');
            $table->tinyInteger('type_option');
            $table->integer('total_tolerance')->nullable();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('question_title_id')
                  ->references('id')
                  ->on('question_titles')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
};
