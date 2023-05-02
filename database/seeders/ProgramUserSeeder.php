<?php

namespace Database\Seeders;

use App\Models\ProgramUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProgramUser::insert([
            'program_id'=>1,
            'user_id'=>2
        ]);
    }
}
