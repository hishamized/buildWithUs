<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_title',
        'job_description',
        'job_requirements',
        'hiring_capacity',
        'site_address',
        'job_type',
        'start_date',
        'end_date',
        'expiration_date',
        'site_pictures',
        'wage',
        'currency',
        'skill_set',
        'client_id',
    ];

    protected $casts = [
        'site_pictures' => 'json',
        'site_address' => 'json',
        'skill_set' => 'json',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function applications()
{
    return $this->hasMany(Application::class, 'job_id');
}

public function assignments()
{
    return $this->hasMany(Assignment::class, 'job_id');
}




}
