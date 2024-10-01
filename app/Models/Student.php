<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'cnic', 'date_of_birth',
        'phone_number', 'email', 'cv_path', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
