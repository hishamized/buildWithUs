<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';


    protected $fillable = [
        'user_id',
        'profile_picture',
        'street_address',
        'country',
        'state',
        'city',
        'pin_code',
        'contact_no',
        'alternate_contact_no',
        'date_of_birth',
        'adhaar_card',
        'upi_id',
        'skill_set',
        'resume',
    ];

    protected $dates = ['date_of_birth'];

    public function user()
{
    return $this->belongsTo(User::class);
}

}
