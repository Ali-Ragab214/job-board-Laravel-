<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Employer extends Model
{
    use HasFactory;
   protected $fillable = [
        'user_id',
        'company_name',
        'company_logo',
        'company_description',
        'company_website',
        'company_location',
        'phone',
    ];

    public function user()
    {
return $this->belongsTo(User::class, 'user_id');    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }



}
