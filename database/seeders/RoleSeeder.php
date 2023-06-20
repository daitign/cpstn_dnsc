<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Administrator',
            'Staff',
            'Process Owner',
            'Internal Auditor',
            'Internal Lead Auditor',
            'Quality Assurance Director',
            'Human Resources',
            'Document Control Custodian',
            'College Management Team',
        ];

        foreach($roles as $role) {
            Role::updateOrCreate(['role_name' => $role]);
        }
    }
}
