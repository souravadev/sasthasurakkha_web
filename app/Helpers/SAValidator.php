<?php

namespace App\Helpers;

class SAValidator {

    public function digit($value, $digit) {
        return (preg_match('/^\d{' . $digit . '}$/', $value) === 1);
    }

    public function phone($phone) {
        return SAValidator::digit($phone, 10);
    }

    public function email($email) {
        return preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $email);
    }
}