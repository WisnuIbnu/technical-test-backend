<?php

namespace App\Services;

use App\Models\leave_request;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Exception;

class LeaveService {
    public function createRequest($user, $data) {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $duration = $start->diffInDays($end) + 1;

        $quota = $user->leaveQuotas()->where('year', $start->year)->first();
        if (!$quota || ($quota->total_quota - $quota->used_quota) < $duration) {
            throw new Exception("Sisa kuota tidak cukup.");
        }

        return LeaveRequest::create(array_merge($data, [
            'user_id' => $user->id,
            'duration' => $duration,
            'status' => 'pending'
        ]));
    }
}