<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'firstname'=>fake()->firstNameMale(),
            'middlename'=>fake()->lastName(),
            'surname'=>fake()->lastName(),
            'suffix'=>fake()->suffix(),
            'role_id'=>1,
            'username'=>'admin',
            'password'=>Hash::make('admin123'),
            'img'=>'hecker.png'
        ]);

        User::create([
            'firstname'=>fake()->firstNameMale(),
            'middlename'=>fake()->lastName(),
            'surname'=>fake()->lastName(),
            'suffix'=>fake()->suffix(),
            'role_id'=>10,
            'username'=>'dcc',
            'password'=>Hash::make('admin123'),
            'img'=>'hecker.png'
        ]);

        User::create([
            'firstname'=>fake()->firstNameMale(),
            'middlename'=>fake()->lastName(),
            'surname'=>fake()->lastName(),
            'suffix'=>fake()->suffix(),
            'role_id'=>3,
            'username'=>'po',
            'password'=>Hash::make('admin123'),
            'img'=>'hecker.png'
        ]);

        User::create([
            'firstname'=>fake()->firstNameMale(),
            'middlename'=>fake()->lastName(),
            'surname'=>fake()->lastName(),
            'suffix'=>fake()->suffix(),
            'role_id'=>2,
            'username'=>'staff',
            'password'=>Hash::make('admin123'),
            'img'=>'hecker.png'
        ]);

        User::create([
            'firstname'=>fake()->firstNameMale(),
            'middlename'=>fake()->lastName(),
            'surname'=>fake()->lastName(),
            'suffix'=>fake()->suffix(),
            'role_id'=>9,
            'username'=>'hr',
            'password'=>Hash::make('admin123'),
            'img'=>'hecker.png'
        ]);
    }
}
