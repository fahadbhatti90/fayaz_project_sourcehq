<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\business;
use App\Models\client;
use App\Models\Invitation;
use App\Models\User;
use App\Models\ClientConfirmationCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;
use App\Mail\ClientConfirmationCodeEmail;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use File;

class ClientController extends Controller
{
    private $status_code = 200;

    public function clientSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $userDataArray = array(
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "client_status" => 1,
        );
        $user_status = client::where("email", $request->email)->first();
        if (!is_null($user_status)) {
            return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! email already registered"]);
        }
        $client = client::create($userDataArray);
        if (!is_null($client)) {
            $client->assignRole([1]);
            $five_digit_random_number = random_int(10000, 99999);
            $ClientConfirmationCodes = new ClientConfirmationCodes;
            $ClientConfirmationCodes->confirmation_code = $five_digit_random_number;
            $client->confirmation_codes()->save($ClientConfirmationCodes);
            $details = [
                'title' => 'Confimation Code',
                'code' => $five_digit_random_number ,
                'link' => urldecode(env('APP_FRONTEND_URL') . '/email-confirmation')
            ];
            $mail = Mail::to($request->email)->send(new ClientConfirmationCodeEmail($details));
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully , check your email for confrimation code.", "data" => $client]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to register"]);
        }
    }

    // ------------ [ Client Login ] -------------------
    public function clientLogin(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                "email" => "required|email",
                "password" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "validation_error" => $validator->errors()]);
        }
        $user = client::where('email', $request->email)->where('client_status', 1)->first();
        if (empty($user)){
            return response()->json(["status" => "failed", "error" => true, "message" => "Client not exist'."]);
        }
        $user_role_id = $user->roles->first()->id;
        if ($user_role_id == 1 && $user->isConfirmed == 0){
            $this->sendClientConfirmationEmail($user->id);
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "Your account is not confirmed , a confirmation code is sent to your email.", "data" => $user_role_id]);
        }
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Laravel-9-Passport-Auth')->accessToken;
            $data = client::where("email", $request->email)->first();
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $data, 'token' => $token]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "Unable to login. Incorrect password."]);
        }
    }

    // ------------ [ Client Login ] -------------------
    public function validatePasswordRequest(Request $request)
    {
        DB::table('password_resets')->where('email', $request->email)
            ->delete();
        //You can add validation login here
        $user = client::where("email", $request->email)->get();
//Check if the user exists
        if ($user->count() < 1) {
            return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! User does not exist"]);
        }


//Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);

//Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();
        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "A reset link has been sent to your email address.", "data" => $request->email]);
        } else {
            return response()->json(["status" => "failed", "error" => true, "message" => "A Network Error occurred. Please try again.", "data" => $request->email]);
        }

    }

    private function sendResetEmail($email, $token)
    {
//Retrieve the user from the database
        //$link = 'http://localhost:3000/reset/' . $token;
        $link = urldecode(env('APP_FRONTEND_URL') . '/' . $token);
        try {
            $details = [
                'title' => 'Password Reset email',
                'body' => $link
            ];
            $mail = Mail::to($email)->send(new PasswordReset($details));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'token' => 'required']);
        //check if payload is valid before moving on
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
// Validate the token
        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();
// Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) {
            return response()->json(["status" => "failed", "error" => true, "message" => "Token is invalid'."]);
        }
        $user = client::where('email', $tokenData->email)->first();
// Redirect the user back if the email is invalid
        if (!$user) {
            return response()->json(["status" => "failed", "error" => true, "message" => "Email not found'."]);
        }
//Hash and update the new password
        $user->password = bcrypt($request->password);
        $user->update(); //or $user->save();
        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
            ->delete();
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Password Updated Successfully."]);
    }

    public function verifyConfirmationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "confirmation_code" => "required",
            'client_id' => "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $check_confirmation = ClientConfirmationCodes::where("fk_client_id", $request->client_id)->where("confirmation_code", $request->confirmation_code)->first();
        if (is_null($check_confirmation)) {
            return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! invalid confirmation code"]);
        }
        $client = client::where('id', $request->client_id)->first();
        $client->isConfirmed = 1;
        $client->update(); //
        if (Auth::loginUsingId($request->client_id)) {
            $token = auth()->user()->createToken('Laravel-9-Passport-Auth')->accessToken;
            $data = client::where("id", $request->client_id)->first();
            return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $data, 'token' => $token]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "Unable to login. Incorrect password."]);
        }
    }

    public function resendConfirmationCode(Request $request)
    {
        return $response = $this->sendClientConfirmationEmail($request->client_id);
    }

    private function sendClientConfirmationEmail($client_id){

        $user = client::where('id', $client_id)->first();
        if (empty($user)){
            return response()->json(["status" => "failed", "error" => true, "message" => "Client not exist'."]);
        }elseif($user->isConfirmed != 1) {
        $ClientConfirmationCodes = ClientConfirmationCodes::where('fk_client_id', $user->id)->delete();
        $five_digit_random_number = random_int(10000, 99999);
        $ClientConfirmationCodes = new ClientConfirmationCodes;
        $ClientConfirmationCodes->fk_client_id = $user->id;
        $ClientConfirmationCodes->confirmation_code = $five_digit_random_number;
        $ClientConfirmationCodes->save();
        $details = [
            'title' => 'Confimation Code',
            'code' => $five_digit_random_number ,
            'link' => urldecode(env('APP_FRONTEND_URL') . '/email-confirmation')
        ];
        $mail = Mail::to($user->email)->send(new ClientConfirmationCodeEmail($details));
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Your account is not confirmed , a confirmation code is sent to your email.", "data" => $user]);
        }
        }

    public function addUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "required|email",
            "password" => "required",
            'profile_image'=> 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'role_id' => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $user_status = client::where("email", $request->email)->first();
        if (!is_null($user_status)) {
            return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! email already registered"]);
        }
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $file_name = 'company-logo-'.time().'.'.$image->getClientOriginalExtension();
            $path = storage_path('app/public/uploads/images/CompanyLogo');
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            }
            $destinationPath = storage_path('app/public/uploads/images/CompanyLogo');
            $image->move($destinationPath, $file_name);
        }else{
            return response()->json(["status" => "failed", "error" => false, "message" => "Please upload file."]);
        }
        $get_business_ids = business::first();
        $business_id = $get_business_ids->id;
        $userDataArray = array(
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "business_id" => $business_id,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "profile_image" => $file_name ,
            "role_id" => $request->first_name,
            "isConfirmed" => 1,
            "client_status" => 1
        );
        $client = client::create($userDataArray);
        if (!is_null($client)) {
            //update invitation
            $Invitation = Invitation::where('email', $client->email)->first();
            $Invitation->registered_at = date('Y-m-d H:i:s');
            $Invitation->update(); //
            $client->assignRole([$request->role_id]);
            if (Auth::loginUsingId($client->id)) {
                $token = auth()->user()->createToken('Laravel-9-Passport-Auth')->accessToken;
                $data = client::where("id", $client->id)->first();
                return response()->json(["status" => $this->status_code, "success" => true, "message" => "You have logged in successfully", "data" => $data, 'token' => $token]);
            } else {
                return response()->json(["status" => "failed", "error" => false, "message" => "Unable to login. Incorrect password."]);
            }
            //return response()->json(["status" => $this->status_code, "success" => true, "message" => "Registration completed successfully.", "data" => $client]);
        } else {
            return response()->json(["status" => "failed", "error" => false, "message" => "failed to register"]);
        }
    }

    public function ChangeClientStatus(Request $request){
        $validator = Validator::make($request->all(), [
            "client_status" => "required",
            "client_id" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $client = client::where('id', $request->client_id)->first();
        $client->client_status = $request->client_status;
        $client->update(); //
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Client status updated successfully.", "data" => $client]);
    }
}
