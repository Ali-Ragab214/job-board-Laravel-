<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Candidate extends Model
{
    use HasFactory;
protected $fillable = [
    'user_id',
    'resume',
    'headline',
    'location',
    'experience_years',
    'phone',
];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }
    public function applications()
    {
    return $this->hasMany(Application::class);
    }
}
