<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    private $status_code = 200;
    public function ShowAllJobPositions(Request $request){
        $all_job_positions = position::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get job positions.",'all_job_positions'=>$all_job_positions]);
    }

    public function AddPosition(Request $request){
        $validator = Validator::make($request->all(), [
            "business_id" => "required",
            "name" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $PositionDataArray = array(
            "business_id" => $request->business_id,
            "name" => $request->name,
            "code" => $request->name,
            "position_status" => 1,
        );
        $HireType = position::create($PositionDataArray);
        if ($HireType){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Position added successfully.", "Position data" => $HireType]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add Position"]);
        }
    }
}
