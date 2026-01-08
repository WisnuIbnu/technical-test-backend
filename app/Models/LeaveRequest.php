<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'duration',
        'reason',
        'attachment',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected static function booted()
    {
        static::updated(function ($leaveRequest) {
            // Jika status berubah menjadi 'approve'
            if ($leaveRequest->isDirty('status') && $leaveRequest->status === 'approve') {
                
                $quota = LeaveQuota::where('user_id', $leaveRequest->user_id)
                    ->where('year', \Carbon\Carbon::parse($leaveRequest->start_date)->year)
                    ->first();

                if ($quota) {
                    $quota->increment('used_quota', $leaveRequest->duration);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
