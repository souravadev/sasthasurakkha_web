<?php

namespace App\Helpers;

use Carbon\Carbon;
use InvalidArgumentException;

class SAUtility {

    public static function generate_random_number(int $digit = 1) : int {
        if($digit < 1) {
            throw new InvalidArgumentException(SAUtility::replace_str(
                SALang::$digit_must_be_greater_than_number,
                [$digit]
            ));
        }

        $min = str_pad('1', $digit, '0');
        $max = str_pad('', $digit, '9');

        return random_int($min, $max);
    }

    public static function replace_str($str, array $actuals = []) : string {
        if(is_array($actuals) && count($actuals) > 0) {
            return sprintf($str, ...$actuals);
        }
    
        return $str;
    }

    public static function generate_user_id() : int {
        return SAUtility::generate_random_number(8);
    }

    public static function generate_otp() : int {
        return SAUtility::generate_random_number(6);
    }

    public static function generate_remember_token($user_id) : string {
        return $user_id . '|' . SAUtility::get_current_timestamp()->valueOf();
    }

    public static function generate_full_name($first, $middle, $last) : string {
        return trim($first . " " . $middle . " " . $last);
    }

    public static function get_current_timestamp() {
        return Carbon::now();
    }

    public static function get_otp_expiry_time() {
        return SAUtility::get_current_timestamp()->addMinutes(10);
    }

    public static function generate_action_id() {
        return SAUtility::generate_random_number(10);
    }
}