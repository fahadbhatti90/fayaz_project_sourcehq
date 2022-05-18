<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\tax_label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxLabelController extends Controller
{
    private $status_code = 200;
    public function ShowAllTaxLabels(Request $request){
        $all_tax_labels = tax_label::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get tax labels.",'all_tax_labels'=>$all_tax_labels]);
    }
    public function AddTaxLabel(Request $request){
        $validator = Validator::make($request->all(), [
            "business_id" => "required",
            "label_name" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $TaxLabelDataArray = array(
            "business_id" => $request->business_id,
            "label_name" => $request->label_name,
            "label_code" => $request->label_name,
            "tax_label_status" => 1,
        );
        $tax_label = tax_label::create($TaxLabelDataArray);
        if ($tax_label){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Tax Label added successfully.", "Tax Label data" => $tax_label]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to add Tax Label"]);
        }
    }
}
