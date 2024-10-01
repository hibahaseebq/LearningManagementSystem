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
        Schema::create('quiz_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('marks_obtained')->nullable(); // Marks obtained for the quiz
            $table->string('status')->default('assigned'); // Status can be 'assigned', 'completed', etc.
            $table->integer('attempt')->default(1); // Track the attempt number
            $table->dateTime('assigned_at');
            $table->dateTime('activation_date');
            $table->dateTime('expiration_date');
            $table->softDeletes(); // Soft delete to archive records
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
        Schema::dropIfExists('quiz_assignments');
    }
};
