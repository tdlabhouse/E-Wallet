<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class IsValidPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $this->lengthPasses = (Str::length($value) == 7);
        $this->uppercasePasses = (Str::lower($value) !== $value);
        $this->numericPasses = ((bool)preg_match('/[0-9]/', $value));
        $this->specialCharacterPasses = ((bool)preg_match('/[^A-Za-z0-9]/', $value));

        return ($this->lengthPasses && $this->uppercasePasses && $this->numericPasses && $this->specialCharacterPasses);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch (true) {
            case !$this->uppercasePasses
                && $this->numericPasses
                && $this->specialCharacterPasses:
                return 'Password harus terdiri dari 7 karakter dan mengandung setidaknya 1 karakter huruf besar.';

            case !$this->numericPasses
                && $this->uppercasePasses
                && $this->specialCharacterPasses:
                return 'Password harus terdiri dari 7 karakter dan mengandung setidaknya 1 angka.';

            case !$this->specialCharacterPasses
                && $this->uppercasePasses
                && $this->numericPasses:
                return 'Password harus 7 karakter dan mengandung minimal 1 karakter khusus';

            case !$this->uppercasePasses
                && !$this->numericPasses
                && $this->specialCharacterPasses:
                return 'Password Harus terdiri dari 7 karakter dan mengandung setidaknya 1 karakter huruf besar dan 1 angka.';

            case !$this->uppercasePasses
                && !$this->specialCharacterPasses
                && $this->numericPasses:
                return 'Password harus 7 karakter dan mengandung minimal 1 karakter huruf besar dan 1 karakter khusus.';

            case !$this->uppercasePasses
                && !$this->numericPasses
                && !$this->specialCharacterPasses:
                return 'Password harus 7 karakter dan mengandung minimal 1 karakter huruf besar, 1 angka, dan 1 karakter khusus.';

            default:
                return 'Password harus 8 karakter.';
        }
    }
}
