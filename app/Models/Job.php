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
        'site_pictures' => 'json', // Define site_pictures as a JSON casted attribute
        'site_address' => 'json',  // Define site_address as a JSON casted attribute
        'skill_set' => 'json',     // Define skill_set as a JSON casted attribute
    ];

    // Define the relationship between Job and User (Client)
    public function user()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function applications()
{
    return $this->hasMany(Application::class, 'job_id');
}



}
