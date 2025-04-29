<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\CloseNumber;

class UniqueCloseNumber implements ValidationRule
{
    protected $date;
    protected $managerId;

    public function __construct($date, $managerId)
    {
        $this->date = $date;
        $this->managerId = $managerId;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (CloseNumber::where('date', $this->date)
            ->where('section', $value)
            ->where('manager_id', $this->managerId)
            ->exists()) {
            $fail('သင်ရွေးထားသော Section အတွက် ပိတ်သီးအစတည်းကပိတ်ပြီးပါပြီ');
        }
    }
}
