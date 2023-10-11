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

    ];


    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }


    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }


    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }


    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
}
