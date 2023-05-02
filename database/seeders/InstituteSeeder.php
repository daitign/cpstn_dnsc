<?php

namespace Database\Seeders;

use App\Models\Institute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Institute::insert([
            'institute_name'=>'IC',
            'institute_description'=>'Institute of Computing',
            'area_id'=>2,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
    }
}
