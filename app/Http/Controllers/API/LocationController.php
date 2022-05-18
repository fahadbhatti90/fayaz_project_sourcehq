<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\countries;
use App\Models\states;
use App\Models\tax_label;
use App\Models\location;
use App\Models\location_tax;

class LocationController extends Controller
{
    private $status_code = 200;

    public function GetLocationsData(Request $request){
        $countries = countries::all();
        $states = states::all();
        $tax_label = tax_label::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get location data.", "countries" => $countries, "states" => $states, "tax_label" => $tax_label]);
    }

    public function AddLocation(Request $request){
    $validator = Validator::make($request->all(), [
        "location_name" => "required",
        "business_id" => "required",
        "address" => "required",
        "state" => "required",
        "country" => "required",
        "zipcode" => "required",
        "tax_labels" => "required.*",
        "tax_labels" => "required",
    ]);

    if ($validator->fails()) {
        return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
    }
        $locationDataArray = array(
            "location_name" => $request->location_name,
            "business_id" => $request->business_id,
            "address" => $request->address,
            "state" => $request->state,
            "country" => $request->country,
            "zip_code" => $request->zipcode,
            "location_status" => 1
        );
        $location = location::create($locationDataArray);
        $location_id = $location->id;
        $tax_labels = json_decode($request->tax_labels, true);

        foreach($tax_labels as $tax_label){
            $labels = array(
                "business_id" => $request->business_id,
                "location_id" => $location_id,
                "tax_label_id" => $tax_label['tax_labels'],
                "tax_percentage" => $tax_label['tax'],
                "location_tax_status" => 1
            );
            $location_tax = location_tax::create($labels);
        }
        if ($location_tax){
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Location added successfully.", "location_data" => $location]);
        } else {
        return response()->json(["status" => "failed", "error" => false, "message" => "failed to add location"]);
        }
        }
        public function ShowAllLocations(){
            $location = location::all();
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get location data.", "location" => $location]);
        }
        }