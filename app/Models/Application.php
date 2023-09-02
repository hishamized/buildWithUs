<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Application extends Model
{
    // Define the table name associated with this model
    protected $table = 'applications';

    // Define the fillable fields that can be mass-assigned
    protected $fillable = [
        'job_id', // Foreign key for the job
        'user_id', // Foreign key for the user (artisan)
        // Add other fields specific to your application if needed
    ];

    // Define the relationship to the Job model
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    // Define the relationship to the User model (artisan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isNotHired()
{
    // Check if the application is not hired (based on your business logic)
    return $this->status !== 'Accepted'; // Assuming 'hired' is the status when an application is hired
}

}

