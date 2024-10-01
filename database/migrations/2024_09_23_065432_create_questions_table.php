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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->longText('question'); // Corrected from longTextext
            $table->json('options'); // JSON field to store multiple answers
            $table->string('correct_answer');
            $table->string('selected_answer')->nullable(); // Nullable since it's set after an attempt
            $table->integer('marks')->default(0); // Marks per question, assuming integer values
            $table->softDeletes(); // Soft delete for archiving questions
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
        Schema::dropIfExists('questions');
    }
};
