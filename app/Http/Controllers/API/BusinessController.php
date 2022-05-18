<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\business;
use App\Models\client;
use App\Models\general_business_settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;
use File;
use App\Models\currencies;
use App\Models\languages;

class BusinessController extends Controller
{
    private $status_code = 200;
    public function get_business_groups(Request $request){
        $general_setting = general_business_settings::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "General Business Settings.", "general_setting" => $general_setting]);
    }
    public function submit_business(Request $request){
        $validator = Validator::make($request->all(), [
            "business_name" => "required",
            'business_url' => "required",
            'crm_business' => "required",
            'business_phone' => "required",
            'career_portal' => "required",
            'sub_domain'=> "required",
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $dataArray = array(
            "business_name" => $request->business_name,
            "business_url" => $request->business_url,
            "crm_business" => $request->crm_business,
            "business_phone" => $request->business_phone,
            "career_portal" => $request->career_portal,
            'sub_domain' => $request->sub_domain,
            'founding_year' => 0
        );
        $business = business::create($dataArray);
        $business_id = $business->id;
        $userId = Auth::user()->id;
        $client = client::find($userId);
        $client->business_id = $business_id;
        $client->save();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Client Business Added Successfully."]);
    }
    public function ManageAccountBusiness(Request $request){
        $clientId = Auth::user()->id;
        $client = client::where('id',$clientId)->with('client_business')->first();
        $client_business = $client->client_business;
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get client business.", "client_business" => $client_business]);
    }
    public function UpdateAccountBusiness(Request $request){

        $validator = Validator::make($request->all(), [
            "id" => "required",
            "business_name" => "required",
            'business_mission' => "required",
            'business_url' => "required",
            'crm_business' => "required",
            'logo'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'company_number'=> "required",
            'vat_tax_id'=> "required",
            'founding_year'=> "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $file_name = 'company-logo-'.time().'.'.$image->getClientOriginalExtension();
            $path = storage_path('app/public/uploads/images/CompanyLogo');
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            //$path = public_path('CompanyLogo');
            $destinationPath = storage_path('app/public/uploads/images/CompanyLogo');
            $image->move($destinationPath, $file_name);
        }else{
            return response()->json(["status" => "failed", "error" => false, "message" => "Please upload file."]);
        }
        $dataArray = array(
            "business_name" => $request->business_name,
            'business_mission' => $request->business_mission,
            'business_url' => $request->business_url,
            'crm_business' => $request->crm_business,
            'business_phone' => $request->business_phone,
            'logo'=> $file_name,
            'company_number'=> $request->company_number,
            'vat_tax_id'=> $request->vat_tax_id,
            'founding_year'=> $request->founding_year
        );
        $update_client_business = business::where('id',$request->id)->update($dataArray);
        $clientId = Auth::user()->id;
        $client = client::where('id',$clientId)->with('client_business')->first();
        $client_business = $client->client_business;
       if($update_client_business){
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Account Updated Successfully.","client_business" => $client_business]);
       }
        return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! there is an error","client_business" => $client_business]);
    }
    public function SetUpPrimaryData(Request $request){
        $clientId = Auth::user()->id;
        $client_business = client::where('id',$clientId)->with('client_business')->first();
        $data = [
            "id"=> $client_business->client_business->id,
            "business_name"=> $client_business->client_business->business_name,
            "business_mission"=> $client_business->client_business->business_mission,
            "business_url"=> $client_business->client_business->business_url,
            "crm_business"=> $client_business->client_business->crm_business,
            "business_phone"=> $client_business->client_business->business_phone,
            "career_portal"=> $client_business->client_business->career_portal,
            "sub_domain"=> $client_business->client_business->sub_domain,
            "business_compliance"=> $client_business->client_business->business_compliance,
            "business_country"=> $client_business->client_business->business_country,
            "business_state"=> $client_business->client_business->business_state,
            "sso_meta_data"=> $client_business->client_business->sso_meta_data,
            "logo"=> asset('storage/uploads/images/CompanyLogo/' . $client_business->client_business->logo),
            "company_number"=> $client_business->client_business->company_number,
            "vat_tax_id"=> $client_business->client_business->vat_tax_id,
            "founding_year"=> $client_business->client_business->founding_year,
            "language"=> $client_business->client_business->language,
            "currency"=> $client_business->client_business->currency,
            "business_status"=> $client_business->client_business->business_status,
            "created_at"=> $client_business->client_business->created_at,
            "updated_at"=> $client_business->client_business->updated_at
        ];
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get client business.", "client_business" => $data]);
    }
    public function ShowLanguageCurrency(Request $request){
        $clientId = Auth::user()->id;
        $client = client::where('id',$clientId)->with('client_business')->first();
        $client_business = $client->client_business;
        $languages = languages::all();
        $currencies = currencies::all();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Get client business.",'client_business'=>$client_business , "languages" => $languages , "currencies" => $currencies]);
    }
    public function UpdateLanguageCurrency(Request $request){
        $validator = Validator::make($request->all(), [
            "id" => "required",
            "language" => "required",
            'currency' => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $dataArray = array(
            "language" => $request->language,
            'currency' => $request->currency,
        );
        $update_client_business = business::where('id',$request->id)->update($dataArray);
        $clientId = Auth::user()->id;
        $client = client::where('id',$clientId)->with('client_business')->first();
        $client_business = $client->client_business;
        if($update_client_business){
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "One Step away to setup your account.","client_business" => $client_business]);
        }
        return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! there is an error","client_business" => $client_business]);
    }
}
