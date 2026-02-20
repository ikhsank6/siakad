<?php

namespace App\Livewire\Concerns;

use Illuminate\Validation\Rules\Password;

trait WithPasswordValidation
{
    /**
     * Get the password validation rules.
     */
    protected function getPasswordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            'confirmed',
        ];
    }

    /**
     * Calculate password strength score (0-4).
     */
    public function getPasswordStrengthProperty(): int
    {
        $password = $this->password ?? '';

        if (empty($password)) {
            return 0;
        }

        $score = 0;

        // Length
        if (strlen($password) >= 8) {
            $score++;
        }
        if (strlen($password) >= 12) {
            $score++;
        }

        // Complexity
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasLower = preg_match('/[a-z]/', $password);
        if ($hasUpper && $hasLower) {
            $score++;
        }

        $hasNumber = preg_match('/\d/', $password);
        $hasSymbol = preg_match('/[^A-Za-z0-9]/', $password);
        if ($hasNumber && $hasSymbol) {
            $score++;
        }

        return min(4, $score);
    }

    /**
     * Get password requirement statuses.
     */
    public function getPasswordRequirementsProperty(): array
    {
        $password = $this->password ?? '';

        return [
            'min_length' => strlen($password) >= 12,
            'mixed_case' => preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password),
            'numbers' => preg_match('/\d/', $password),
            'symbols' => preg_match('/[^A-Za-z0-9]/', $password),
        ];
    }
}
