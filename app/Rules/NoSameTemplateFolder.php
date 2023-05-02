<?php

namespace App\Rules;

use App\Models\Template;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

use function PHPUnit\Framework\isNull;

class NoSameTemplateFolder implements ValidationRule
{
    public function __construct(public $parent) {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $result = Template::query()
        ->where(function($query)use($value){
            $query->where('folder_name',$value);
            if (isNull($this->parent)) {
                $query->whereNull('template_id');
            }
            else{
                $query->where('template_id',$this->parent);
            }
        })
        ->count();
        if ($result) {
            $fail('Folder already exists!');
        }

    }
}
