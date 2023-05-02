<?php

namespace App\Rules;

use App\Models\Process;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSameProcessInProgram implements ValidationRule
{

    public function __construct(public int $program_id) {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = Process::query()
        ->where('process_name',$value)
        ->where('program_id',$this->program_id)
        ->count();

        if ($result) {
            $fail('The process already exists!');
        }
    }
}
