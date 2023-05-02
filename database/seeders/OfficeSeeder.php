<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Office::insert([
            'office_name'=> 'Library',
            'office_description'=>'Library',
            'area_id'=> 1,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        Office::insert([
            'office_name'=> 'Clinic',
            'office_description'=>'Clinic',
            'area_id'=> 1,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        Office::insert([
            'office_name'=> 'Registrar',
            'office_description'=>'Registrar',
            'area_id'=> 1,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        Office::insert([
            'office_name'=> 'Cashier',
            'office_description'=>'Cashier',
            'area_id' => 1,
            'created_at' =>now(),
            'updated_at' =>now(),
        ]);
    }
}
