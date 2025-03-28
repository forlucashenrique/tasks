<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'finish_date_limit',
        'finished',
        'finished_date',
        'excluded_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }

    public function getFinishedDateLimitAttribute()
    {
        return Carbon::parse($this->finish_date_limit)->format('Y-m-d');
    }

    public function getTimeToFinishAttribute()
    {
        $carbon_created_at = Carbon::parse($this->created_at)->startOfDay();
        $carbon_finish_date_limit  = Carbon::parse($this->finish_date_limit)->startOfDay();
        $timeToFinish = $carbon_created_at->diffInDays($carbon_finish_date_limit);

        return $timeToFinish;
    }

    public function getStatusAttribute()
    {
        return $this->finished ? 'done' : 'pending';
    }
}
