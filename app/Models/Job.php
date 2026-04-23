<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Job extends Model
{
    use HasFactory;

    protected $fillable = [
    'employer_id',
    'category_id',
    'title',
    'slug',
    'description',
    'responsibilities',
    'qualifications',
    'salary_min',
    'salary_max',
    'location',
    'work_type',
    'experience_level',
    'status',
    'application_deadline',
];

    public function employer()
{
    return $this->belongsTo(Employer::class);
}

public function category()
{
    return $this->belongsTo(JobCategory::class);
}

public function skills()
{
    return $this->belongsToMany(Skill::class);
}

public function applications()
{
    return $this->hasMany(Application::class);
}
}
