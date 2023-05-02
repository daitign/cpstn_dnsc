<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Program::insert([
            [
                'program_name'=>'BSIT',
                'program_description'=>'Bachelor of Science in Information Technology',
                'institute_id'=>1,
                'created_at'=>now(),
                'updated_at'=>now()
            ]
        ]);
    }
}
