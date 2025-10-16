<?php

namespace App\Traits;

trait CodeGenerator
{
    public function getCode(string $name)
    {

        $initials = $this->getNameInitials($name);

        $firstFourDigit = rand(1000, 9999);

        $year = now()->format('Y');
        $reverseYear = strrev((string) $year);

        return "{$initials}{$reverseYear}{$firstFourDigit}";

    }

    protected function getNameInitials(string $name): string
    {
        $words = preg_split('/\s+/', $name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials;
    }
}
