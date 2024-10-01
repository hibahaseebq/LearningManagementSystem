<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;



class User extends Authenticatable implements JWTSubject
{
    use HasRoles;

    use HasApiTokens, HasFactory, Notifiable;
  

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function students()
    {
        return $this->hasOne(Student::class);
    }

    public function quizAssignments()
    {
        return $this->hasMany(QuizAssignment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Usually the primary key (id)
    }

    public function getJWTCustomClaims()
    {
        return []; // Custom claims can be added here if needed
    }
   
}
