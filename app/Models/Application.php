<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Application extends Model
{

    protected $table = 'applications';


    protected $fillable = [
        'job_id',
        'user_id',

    ];


    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isNotHired()
{

    return $this->status !== 'Accepted';
}

}

