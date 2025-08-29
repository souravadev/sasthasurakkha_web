<?php

namespace App\Http\Controllers\Api\v1;

use App\Data\SAEUserData;
use App\Data\SAOTPData;
use App\Helpers\SAAuthUtility;
use App\Helpers\SAConst;
use App\Helpers\SALang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class SAAuthController extends Controller
{
    public function execute(Request $request) {
        $status = true;
        $message = null;
        $data = null;

        try {
            $validated_req_body = SAAuthController::valid_resuest_body($request);

            if(!empty($validated_req_body)) {
                $euser_data = new SAEUserData(
                    null,
                    null,
                    $request->full_name,
                    $request->email,
                    $request->phone
                );

                $final_user_id = null;

                $old_user = $euser_data->fetch();

                if(empty($old_user)) {
                    //User not exists. Create new user

                    $new_user = $euser_data->insert();

                    $final_user_id = $new_user->user_id;
                } else {
                    $final_user_id = $old_user->user_id;
                }

                $otp_data = new SAOTPData(
                    null,
                    $final_user_id,
                    SAConst::$purpose_id_login
                );

                $otp_data->trigger();

                $otp_token = SAAuthUtility::generate_otp_token($final_user_id, $otp_data);

                $data = [
                    "token" => $otp_token
                ];

                $status = true;
            }
        } catch(Exception $err) {
            $message = $err->getMessage();
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }

    private function valid_resuest_body(Request $request) : array {
        $validated = $request->validate([
            'phone' => 'required|digits:10'
        ]);


        return $validated;
    }

    public function authenticate(Request $request) {
        try {
            $token_data = SAAuthUtility::get_data_from_jwt_token();

            $otp_ctrl = new SAOTPData(
                $token_data['action_id'],
                $token_data['user_id'],
                $token_data['purpose_id'],
                $request->otp
            );

            $otp_data = $otp_ctrl->verify();

            if(empty($otp_data)) {
                throw new Exception(SALang::$invalid_otp);
            }

            $user_data_obj = new SAEUserData($token_data['user_id']);
            $user_data = $user_data_obj->fetch();

            SAAuthUtility::invalidate_token();
            
            $auth_token = SAAuthUtility::generate_auth_token($user_data, true);

            return response()->json([
                'status' => true,
                'token' => $auth_token
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
