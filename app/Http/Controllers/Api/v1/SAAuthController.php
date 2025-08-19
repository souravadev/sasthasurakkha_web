<?php

namespace App\Http\Controllers\Api\v1;

use App\Data\SAEUserData;
use App\Data\SAOTPData;
use App\Helpers\SAUtility;
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
                    $request->first_name,
                    $request->middle_name,
                    $request->last_name,
                    null,
                    $request->email,
                    $request->phone
                );

                $new_user = $euser_data->insert();

                //
                SAUtility::set_user_session($new_user->id);

                //Otp
                $otp_data = new SAOTPData(
                    null,
                    $new_user->id,
                    '4' //user login
                );

                $otp_data->trigger();

                $data = $new_user;

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
            'first_name' => 'required',
            'phone' => 'required|digits:10'
        ]);


        return $validated;
    }

    public function authenticate(Request $request) {
        try {
            $ctrl = new SAOTPController();
            $data = $ctrl->verify($request)->getData(true);

            if($data['status']) {
                //login
                
            }

            return response()->json([
                'status' => $data['status'],
                'message' => $data['message'] ?? null
            ]);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
