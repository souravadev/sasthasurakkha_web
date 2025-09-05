<?php

namespace App\Data;

use App\Helpers\SAConst;
use Illuminate\Support\Facades\DB;

class SAAreaData {

    public static function fetch_counties($county_ids) {
        $county_ids ??= SAConst::$available_counties;

        $data = DB::table('counties')
            ->select()
            ->whereIn('county_id', $county_ids)
            ->get();

        if(!empty($data)) {
            return $data;
        }
    }
}