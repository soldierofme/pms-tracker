<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'record_menstrual_dates',
        'record_ovulation_dates',
        'days_before'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}