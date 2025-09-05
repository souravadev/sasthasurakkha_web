<?php

namespace App\Http\Controllers\Api\v1\Home;

use App\Data\SAAreaData;
use App\Helpers\SAConst;
use Exception;
use Illuminate\Support\Facades\Request;

class SAHomeControllerFree {
    public function execute(Request $request) {
        try {
            $counties_avl_data = SAAreaData::fetch_counties(
                SAConst::$available_counties
            );


            return response()->json([
                "status" => true,
                "county_data" => $counties_avl_data
            ]);

        } catch(Exception $e) {
            return response()->json([
                "status" => false,
                "error" => $e->getMessage()
            ]);
        }
    }
}