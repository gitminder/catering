<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ApiErrorCode;
use App\Http\Controllers\Controller;
use App\Models\Eater;
use App\Models\EaterGroup;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EatersController extends Controller
{
    public function create(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|min:1|max:100',
                'surname' => 'required|string|min:1|max:100',
                'patronymic' => 'string|min:1|max:100',
                'eatergroup_id' => 'integer|exists_not_deleted:eatergroups,id',
                'bgl' => ['required', 'in:0,1'],
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        }
        $eaterGroup = EaterGroup::find($request->eatergroup_id);
        //echo $eaterGroup->school_id; die;
        //echo $request->user()->school_id; die;
        if ($eaterGroup->school_id != $request->user()->school_id) {
            return ApiResponse::validationError('Bad eatergroup_id');
        }
        $eater = Eater::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'patronymic' => $request->patronymic,
            'eatergroup_id' => $request->eatergroup_id,
            'bgl' => $request->bgl,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
            ]
        ], 201);
    }
    public function connect(Request $request){
        try {
            $request->validate([
                'eater_id' => 'integer|exists:eaters,id',
                'eatergroup_id' => 'integer|exists_not_deleted:eatergroups,id',
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::validationError($e->errors());
        }
        $eaterGroup = EaterGroup::find($request->eatergroup_id);
        if ($eaterGroup->school_id != $request->user()->school_id) {
            return ApiResponse::validationError('Bad eatergroup_id');
        }
        $eater = Eater::find($request->eater_id);
            $eater->eatergroup_id = $request->eatergroup_id;
            $eater->save();

        return response()->json([
            'success' => true,
            'data' => [
            ]
        ], 201);
    }
    public function list(Request $request){
        return response()->json([
            'success' => true,
            'data' => $request->user()->eaters,
        ]);
    }
}