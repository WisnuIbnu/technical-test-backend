<?php

namespace Database\Seeders;

use App\Models\leave_quota;
use App\Models\LeaveQuota;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // 1. Buat Admin
        $admin = User::create([
            'name'     => 'Admin Ganteng',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'), // password: password
            'role'     => 'Admin',
        ]);

        // 2. Buat Staff
        $staff = User::create([
            'name'     => 'Staff Rajin',
            'email'    => 'staff@test.com',
            'password' => Hash::make('password'), // password: password
            'role'     => 'Staff',
        ]);

        // 3. Berikan Kuota Cuti untuk Staff (Tahun 2024 & 2025)
        LeaveQuota::create([
            'user_id'     => $staff->id,
            'year'        => 2025,
            'total_quota' => 12,
            'used_quota'  => 0,
        ]);
        
        LeaveQuota::create([
            'user_id'     => $staff->id,
            'year'        => 2026,
            'total_quota' => 12,
            'used_quota' => 10,
        ]);
    }
}
