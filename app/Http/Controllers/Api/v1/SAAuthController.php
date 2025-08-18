<?php

namespace App\Http\Controllers\Api\v1;

use App\Data\SAEUserData;
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
                $euser_data = new SAEUserData(
                    null,
                    null,
                    $request->first_name,
                    $request->middle_name,
                    $request->last_name,
                    null,
                    null,
                    $request->phone
                );

                $new_user = $euser_data->insert();

                $data = $new_user;

                $otp = SAUtility::generate_otp();
                $action_id = SAUtility::generate_action_id();


                //User
                

                //otp
                // $otp_result = DB::table('otps')->insert([
                //     'id' => $action_id,
                //     'user_id' => $user_id,
                //     'phone' => $request->phone,
                //     'otp' => $otp,
                //     'purpose_id' => 4,
                //     'expiry_at' => SAUtility::get_otp_expiry_time()
                // ]);

                // if($result && $otp_result) {
                //     $status = true;

                //     $data = $result;
                // }
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
