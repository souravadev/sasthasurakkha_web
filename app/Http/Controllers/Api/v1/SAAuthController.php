<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SAAuthController extends Controller
{
    public function execute(Request $request)
    {
        return response()->json([
            'status' => 'success'
        ]);
    }
}
