<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes; // Added soft deletes for archiving quizzes

    protected $fillable = ['quiz_name', 'total_marks', 'duration', 'description', 'starts_at', 'ends_at'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function quizAssignments()
    {
        return $this->hasMany(QuizAssignment::class);
    }
}
