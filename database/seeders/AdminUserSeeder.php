<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unit = Unit::where('slug', 'kantor-kemenag-tana-toraja')->first();

        $user = User::firstOrCreate(
            ['email' => 'admin@sirita.local'],
            [
                'name' => 'Super Admin SIRITA',
                'password' => Hash::make('password'),
                'unit_id' => $unit?->id,
                'status' => 'active',
            ],
        );

        $user->assignRole('Super Admin');
    }
}
