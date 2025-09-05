<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Data\SAOTPData;
use App\Helpers\SAAuthUtility;
use App\Helpers\SALang;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class SAOTPController extends Controller {

    public function resend(Request $request) {
        try {
            $token_data = SAAuthUtility::get_data_from_jwt_token();

            $otp_data = new SAOTPData(
                $token_data['action_id'],
                $token_data['user_id']
            );

            $otp_data->trigger(true);

            return response()->json([
                'status' => true,
                'message' => SALang::$otp_send_successfully
            ]);

        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function verify(Request $request) {
         try {
            $token_data = SAAuthUtility::get_data_from_jwt_token();

            $otp_data = new SAOTPData(
                $token_data['action_id'],
                $token_data['user_id'],
                $token_data['purpose_id'],
                $request->otp
            );

            $data = $otp_data->verify();

            if(empty($data)) {
                return response()->json([
                    'status' => false
                ]);
            }

            return response()->json([
                'status' => true
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}