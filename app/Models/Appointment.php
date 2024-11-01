<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'name',
        'phone',
        'age',
        'place',
        'prescription',
        'token',
    ];

    /**
     * Relationship with the User model
     * An appointment belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with the Schedule model
     * An appointment belongs to a schedule.
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

   
}