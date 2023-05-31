<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Area;
use App\Models\Survey;
use App\Models\SurveyArea;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = Area::offices()->get()->toArray();
        $courses = Area::where('type', 'program')->whereHas('parent', function($q) {
            $q->where('type', 'institute');
        })->get()->toArray();

        $limit = 1000;
        for($x = 0; $x <= $limit; $x++) {
            $key = array_rand($offices);
            $office = $offices[$key];
            
            $types = ['Student', 'Visitor'];
            $key = array_rand($types);
            $type = $types[$key];

            $types = ['Student', 'Visitor'];
            $key = array_rand($types);
            $type = $types[$key];

            $occupations = ['Teacher', 'Programmer', 'Web Developer', 'Cashier', 'Manager'];
            $key = array_rand($occupations);
            $occupation = $occupations[$key];

            $key = array_rand($courses);
            $course = $courses[$key];
            
            $year = rand(2019, 2023);

            $suggestions = ['Great', 'Need Improvement', 'Good Job', 'Highly Recommended', 'Good'];
            $key = array_rand($suggestions);
            $suggestion = $suggestions[$key];
            

            $survey = Survey::create([
                'name' => fake()->firstName() . ' ' .fake()->lastName(),
                'contact_number' => fake()->phoneNumber,
                'type' => $type,
                'course' => $type == 'Student' ? $course['area_description'] : '',
                'course_year' => $type == 'Student' ? $year : '',
                'occupation' => $type == 'Visitor' ? $occupation : '',
                'suggestions' => $suggestion ?? '',
            ]);

            SurveyArea::create([
                'survey_id' => $survey->id,
                'area_id' => $office['id'],
                'promptness' => rand(2,5),
                'engagement' => rand(2,5),
                'cordiality' => rand(2,5),
            ]);
            echo "Generating Survey ".$x." out of ".$limit."\n";
        }
    }
}
