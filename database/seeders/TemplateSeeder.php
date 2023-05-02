<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('templates')->insert([
            ['name' => 'Administrator', 'parent_id' => null, 'content' => ''],
            ['name' => 'Staff', 'parent_id' => null, 'content' => ''],
            ['name' => 'Process Owner', 'parent_id' => null, 'content' => ''],
            ['name' => 'Internal Lead Auditor', 'parent_id' => null, 'content' => ''],
            ['name' => 'Internal Quality Auditor', 'parent_id' => null, 'content' => ''],
            ['name' => 'External Lead Auditor', 'parent_id' => null, 'content' => ''],
            ['name' => 'External Quality Auditor', 'parent_id' => null, 'content' => ''],
            ['name' => 'Quality Assurance Director', 'parent_id' => null, 'content' => ''],
            ['name' => 'Human Resources', 'parent_id' => null, 'content' => ''],
            ['name' => 'Document Control Custodian', 'parent_id' => null, 'content' => ''],
            ['name' => 'College Management Team', 'parent_id' => null, 'content' => ''],
        ]);
    }
}
