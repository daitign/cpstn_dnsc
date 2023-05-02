<?php

namespace Database\Seeders;

use App\Models\Directory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DirectorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Directory::insert([
            ['name' => 'Evidences', 'parent_id' => null, 'created_at' => now()],
            ['name' => 'Process Manuals', 'parent_id' => null, 'created_at' => now()],
            ['name' => 'Audit Reports', 'parent_id' => null, 'created_at' => now()],
            ['name' => 'Consolidated Reports', 'parent_id' => null, 'created_at' => now()],
            ['name' => 'Survey Reports', 'parent_id' => null, 'created_at' => now()],
            [
                'name' => 'Administrations',
                'parent_id' => 1,
                'created_at' => now()
            ],[
                'name' => 'Academics',
                'parent_id' => 1,
                'created_at' => now()
            ],
            [
                'name' => 'Admin Hall',
                'parent_id' => 6,
                'created_at' => now()
            ],
            [
                'name' => 'Library',
                'parent_id' => 6,
                'created_at' => now()
            ],
            [
                'name' => 'Clinic',
                'parent_id' => 6,
                'created_at' => now()
            ],
            [
                'name' => 'Canteen',
                'parent_id' => 6,
                'created_at' => now()
            ],
            [
                'name' => 'Dorm',
                'parent_id' => 6,
                'created_at' => now()
            ],
            [
                'name' => '2021',
                'parent_id' => 8,
                'created_at' => now()
            ],
            [
                'name' => '2022',
                'parent_id' => 8,
                'created_at' => now()
            ],
            [
                'name' => '2023',
                'parent_id' => 8,
                'created_at' => now()
            ],
        ]);
    }
}
