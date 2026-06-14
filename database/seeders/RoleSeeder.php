<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar Role
        $roles = [
            "Admin",
            "Lapangan"
        ];

        foreach ($roles as $value) {
            Role::firstOrCreate(['name' => $value]);
        }
    }
}
