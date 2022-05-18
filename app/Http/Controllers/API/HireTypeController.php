<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\hire_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HireTypeController extends Controller
{
    private $status_code = 200;
    public function ShowAllHireType(Request $request){
        $all_job_hire_type = hire_type::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get hire types.",'all_job_hire_type'=>$all_job_hire_type]);
    }

    public function AddHireType(Request $request){
        $validator = Validator::make($request->all(), [
            "business_id" => "required",
            "name" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $HireTypeDataArray = array(
            "business_id" => $request->business_id,
            "name" => $request->name,
            "code" => $request->name,
            "hire_type_status" => 1,
        );
        $HireType = hire_type::create($HireTypeDataArray);
        if ($HireType){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Hire Type added successfully.", "Hire Type data" => $HireType]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add Hire Type"]);
        }
    }
}