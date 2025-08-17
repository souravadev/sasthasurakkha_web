<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\SALang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\SAUtility;
use Exception;
use Illuminate\Support\Facades\DB;

class SAAuthController extends Controller
{
    public function execute(Request $request)
    {
        $status = true;
        $message = null;
        $data = null;

        try {
            $validated_req_body = SAAuthController::valid_resuest_body($request);

            if(!empty($validated_req_body)) {
                //create user id
                $user_id = SAUtility::generate_user_id();
                $rem_token = SAUtility::generate_remember_token($user_id);
                $full_name = SAUtility::generate_full_name(
                    $request->first_name,
                    $request->middle_name,
                    $request->last_name
                );

                $otp = SAUtility::generate_otp();
                $action_id = SAUtility::generate_action_id();

                $db_data = DB::table('eusers')->select()->where('phone', $request->phone)->first();

                if($db_data) {
                    throw new Exception(SALang::$user_phone_already_exists);
                }


                //User
                $result = DB::table('eusers')->insert([
                    'id' => $user_id,
                    'phone' => $request->phone,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'full_name' => $full_name,
                    'remember_token' => $rem_token
                ]);

                //otp
                $otp_result = DB::table('otps')->insert([
                    'id' => $action_id,
                    'user_id' => $user_id,
                    'phone' => $request->phone,
                    'otp' => $otp,
                    'purpose_id' => 4,
                    'expiry_at' => SAUtility::get_otp_expiry_time()
                ]);

                if($result && $otp_result) {
                    $status = true;

                    $data = $result;
                }
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
            'first_name' => 'required',
            'phone' => 'required|digits:10'
        ]);


        return $validated;
    }
}
