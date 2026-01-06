<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function eaterGroups(Request $request){
        return response()->json([
            'success' => true,
            'data' => $request->user()->school->eaterGroups,
        ]);
    }
}