<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    private $status_code = 200;
    public function ShowAllDepartments(Request $request){
        $all_job_departments = department::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get job catagories.",'all_job_categories'=>$all_job_departments]);
    }
    public function AddDepartment(Request $request){
        $validator = Validator::make($request->all(), [
            "business_id" => "required",
            "name" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $departmentDataArray = array(
            "business_id" => $request->business_id,
            "name" => $request->name,
            "code" => $request->name,
            "department_status" => 1,
        );
        $department = department::create($departmentDataArray);
        if ($department){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Department added successfully.", "Department data" => $department]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add category"]);
        }
    }
}
