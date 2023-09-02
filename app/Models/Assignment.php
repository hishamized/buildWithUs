<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Assignment extends Model
{
    protected $fillable = [
        'job_id',
        'client_id',
        'employee_id',
        'application_id',
        'assignment_status',
        // Add other fillable fields as needed
    ];

    // Define the relationship with the Job model
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    // Define the relationship with the User model for the client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Define the relationship with the User model for the employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Define the relationship with the Application model
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
