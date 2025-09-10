<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');
        $adminRecords = [
            [
                'id' => 1,
                'name' => 'Admin',
                'type' => 'admin',
                'mobile' => 8005559801,
                'email' => 'a@a.a',
                'password' => $password,
                'image' => '',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        Admin::insert($adminRecords);
    }
}
