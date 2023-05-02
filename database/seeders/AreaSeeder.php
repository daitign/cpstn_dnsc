<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::insert([
            [
                'area_name'=>'Administration',
                'area_description'=>'Administration',
                'created_at'=>now(),
                'updated_at'=>now()
            ],
            [
                'area_name'=>'Academics',
                'area_description'=>'Academics',
                'created_at'=>now(),
                'updated_at'=>now()
            ],
        ]);
    }
}
