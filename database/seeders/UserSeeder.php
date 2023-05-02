<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
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
        $roles = Role::get();
        foreach($roles as $role)
        {
            User::create([
                'firstname'=>fake()->firstNameMale(),
                'middlename'=>fake()->lastName(),
                'surname'=>fake()->lastName(),
                'suffix'=>fake()->suffix(),
                'role_id'=> $role->id,
                'username'=> strtolower(\str_replace(' ','_',$role->role_name)),
                'password'=>Hash::make('admin123'),
                'img'=> 'hecker.png'
            ]);
        }
    }
}
