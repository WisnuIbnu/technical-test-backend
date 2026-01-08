<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveQuota;
use App\Services\LeaveService; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveApiController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $quota = LeaveQuota::where('user_id', $user->id)
            ->where('year', date('Y'))
            ->first();

        return response()->json([
            'user' => $user->name,
            'remaining_quota' => $quota ? ($quota->total_quota - $quota->used_quota) : 0,
            'data' => $leaves
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('leave-attachments', 'public');
                $data['attachment'] = $path;
            }

            $leaveService = new LeaveService();
            $leave = $leaveService->createRequest($request->user(), $data);

            return response()->json([
                'message' => 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan.',
                'data' => $leave
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengajukan cuti.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if ($request->user()->role !== 'Admin') {
            return response()->json(['message' => 'Forbidden: Hanya Admin yang diizinkan.'], 403);
        }

        $request->validate([
            'status' => 'required|in:approve,rejected'
        ]);

        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => "Status pengajuan berhasil diubah menjadi {$request->status}",
            'data' => $leave
        ]);
    }
}