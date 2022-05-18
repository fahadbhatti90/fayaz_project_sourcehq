<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    private $status_code    =        200;

    public function userSignUp(Request $request) {
        // dd("sdfs");
        $validator              =        Validator::make($request->all(), [
            "name"              =>          "required",
            "email"             =>          "required|email",
            "password"          =>          "required",
            "phone"             =>          "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }

        $name                   =       $request->name;
        $name                   =       explode(" ", $name);
        $first_name             =       $name[0];
        $last_name              =       "";

        if(isset($name[1])) {
            $last_name          =       $name[1];
        }

        $userDataArray          =       array(
            "first_name"         =>          $first_name,
            "last_name"          =>          $last_name,
            "full_name"          =>          $request->name,
            "email"              =>          $request->email,
            "password"           =>          bcrypt($request->password),
            "phone"              =>          $request->phone
        );

        $user_status            =           User::where("email", $request->email)->first();

        if(!is_null($user_status)) {
           return response()->json(["status" => "failed", "success" => false, "message" => "Whoops! email already registered"]);
        }

        $user                   =           User::create($userDataArray);
        $user->assignRole("Admin");

        if(!is_null($user)) {
           
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully", "data" => $user]);
        }

        else {
            return response()->json(["status" => "failed", "success" => false, "message" => "failed to register"]);
        }
    }


    // ------------ [ User Login ] -------------------
    public function userLogin(Request $request) {

        $validator          =       Validator::make($request->all(),
            [
                "email"             =>          "required|email",
                "password"          =>          "required"
            ]
        );

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_error" => $validator->errors()]);
        }

         $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Laravel-9-Passport-Auth')->accessToken;
            $data            =           User::where("email", $request->email)->first();
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $data,'token'=>$token]);
            // return response()->json(['token' => $token], 200);
        } else {
            return response()->json(["status" => "failed", "success" => false, "message" => "Unable to login. Incorrect password."]);
        }

    
        

        
    }

    // ------------------ [ User Detail ] ---------------------
    public function userInfo() {
        $user = auth()->user();
      
     return response()->json(['user' => $user], 200);
    }
}