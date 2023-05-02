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
        
        $directories = [
            [
                'name' => 'Manuals', 
                'automatic_child' => true,
            ],
            [
                'name' => 'Evidences',
                'automatic_child' => true,
            ],
            [
                'name' => 'Templates',
                'sub_directory' => [
                    'Process Owner',
                    'Auditor',
                    'Lead Auditor',
                    'Human Resources'
                ]
            ],
            [
                'name' => 'Audit Reports', 
                'automatic_child' => true,
            ],
            [
                'name' => 'Survey Reports'
            ],
        ];

        $sub_directory = [
            [
                'name' => 'Administration',
                'child_directories' => [
                    'Library',
                    'Clinic',
                    'Registrar',
                    'Cashier'
                ]
            ],
            [
                'name' => 'Academics',
                'child_directories' => [
                    'IC',
                ]
            ],
        ];

        $years = ['2021', '2022', '2023'];

        foreach($directories as $item) {
            $directory = Directory::where('name', $item['name'])->first();
            if(empty($directory)) {
                $directory = Directory::create([
                    'name' => $item['name']
                ]);
            }

            if(!empty($item['sub_directory']))
            {
                foreach($item['sub_directory'] as $child)
                {
                    Directory::create([
                        'name' => $child,
                        'parent_id' => $directory->id
                    ]);
                }
            }

            if(!empty($item['automatic_child'])) {
                foreach($sub_directory as $child) {
                    $dir = Directory::create([
                        'name' => $child['name'],
                        'parent_id' => $directory->id,
                        'area_dependent' => true
                    ]);
                    foreach($child['child_directories'] as $child) {
                        $child = Directory::create([
                            'name' => $child,
                            'parent_id' => $dir->id
                        ]);

                        foreach($years as $year) {
                            $year = Directory::create([
                                'name' => $year,
                                'parent_id' => $child->id
                            ]);

                            if($dir->name == 'Academics')
                            {
                                Directory::create([
                                    'name' => '1st Semester',
                                    'parent_id' => $year->id
                                ]);

                                Directory::create([
                                    'name' => '2nd Semester',
                                    'parent_id' => $year->id
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
