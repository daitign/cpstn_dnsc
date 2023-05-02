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
        Role::insert([
            [
                'role_name'=>'Administrator',
            ],
            [
                'role_name'=>'Staff',
            ],
            [
                'role_name'=> 'Process Owner',
            ],
            [
                'role_name'=>'Internal Lead Auditor',
            ],
            [
                'role_name'=>'Internal Quality Auditor',
            ],
            [
                'role_name'=>'External Lead Auditor',
            ],
            [
                'role_name'=>'External Quality Auditor',
            ],
            [
                'role_name'=>'Quality Assurance Director',
            ],
            [
                'role_name'=>'Human Resources',
            ],
            [
                'role_name'=>'Document Control Custodian',
            ],
            [
                'role_name'=>'College Management Team',
            ],
        ]);
    }
}
