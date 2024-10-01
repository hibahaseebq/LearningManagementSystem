<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes; // Added soft deletes for archiving questions

    protected $fillable = ['quiz_id', 'question', 'options', 'correct_answer', 'marks'];

    protected $casts = [
        'options' => 'array', // This will cast the JSON field 'options' to an array
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
