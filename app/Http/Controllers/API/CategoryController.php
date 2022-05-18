<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $status_code = 200;
    public function ShowAllJobCategories(Request $request){
        $all_job_categories = category::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get job catagories.",'all_job_categories'=>$all_job_categories]);
    }

    public function AddCategories(Request $request){
        $validator = Validator::make($request->all(), [
            "business_id" => "required",
            "name" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $categoryDataArray = array(
            "business_id" => $request->business_id,
            "name" => $request->name,
            "code" => $request->code,
            "category_status" => 1,
        );
        $category = category::create($categoryDataArray);
        if ($category){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Category added successfully.", "Category data" => $category]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add category"]);
        }
    }

}

