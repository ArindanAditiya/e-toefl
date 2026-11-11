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
        Schema::create('grade_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->uuid('category_id');
            $table->uuid('exam_id');
            $table->uuid('user_id');
            $table->uuid('value_category_id')->nullable();
            $table->uuid('grade_id');
            $table->integer('total_correct');
            $table->integer('total_wrong');
            $table->decimal('grade', 5, 2);

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('exam_id')
                  ->references('id')
                  ->on('exams')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('grade_id')
                  ->references('id')
                  ->on('grades')
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
        Schema::dropIfExists('grade_details');
    }
};
