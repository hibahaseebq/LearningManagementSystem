<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_assignment_id', 'video_path', 'comment'];

    public function quizAssignment()
    {
        return $this->belongsTo(QuizAssignment::class);
    }
}
