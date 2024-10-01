<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAssignment extends Model
{
    use HasFactory, SoftDeletes; // Added soft deletes for archiving quiz assignments

    protected $fillable = ['quiz_id', 'user_id', 'marks_obtained', 'status', 'attempt', 'assigned_at', 'activation_date', 'expiration_date'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Removed the quizAttempts relationship
}
