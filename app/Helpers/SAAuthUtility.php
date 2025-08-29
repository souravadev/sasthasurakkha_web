<?php

namespace App\Helpers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class SAAuthUtility {

    public static function generate_jwt_token($sub, $claims) {
        try {
            $payload = JWTFactory::sub($sub)
            ->customClaims($claims)
            ->make();

            $token = JWTAuth::encode($payload)->get();

            return $token;
        } catch(JWTException $e) {
            return $e;
        }
    }

    public static function get_data_from_jwt_token() {
        $payload = JWTAuth::parseToken()->getPayload();

        return $payload->toArray();
    }

    public static function generate_auth_token($user_data, $is_logged_in = false) {
        return SAAuthUtility::generate_jwt_token($user_data->user_id, [
            'user_id' => $user_data->user_id,
            'guid' => $user_data->guid,
            'is_logged_in' => $is_logged_in
        ]);
    }

    public static function generate_otp_token($user_id, $otp_data) {
        return SAAuthUtility::generate_jwt_token($user_id, [
            'user_id' => $user_id,
            'action_id' => $otp_data->action_id,
            'purpose_id' => $otp_data->purpose_id
        ]);
    }

    public static function invalidate_token() {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public static function is_user_logged_in() {
        $token_data = SAAuthUtility::get_data_from_jwt_token();

        return ($token_data['is_logged_in'] ?? false) == true;
    }
}