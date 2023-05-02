<?php

namespace Database\Seeders;

use App\Models\Process;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Process::create([
            'process_name'=>'PSU',
            'process_description'=>'Procurement Supply Unit',
            'program_id'=>1
        ]);
    }
}
