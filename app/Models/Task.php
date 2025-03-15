<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y H:i:s');
    }

    public function getFinishedDateLimitAttribute()
    {
        return Carbon::parse($this->end_date)->format('d/m/Y');
    }
}
