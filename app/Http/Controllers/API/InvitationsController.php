<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Mail\ClientInvitationEmail;
use App\Models\client;
use Illuminate\Http\Request;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InvitationsController extends Controller
{
    private $status_code = 200;
    public function storeInvitation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "invitation_emails.*"=> "required",
            "invitation_emails"=> "required"
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $invitation_emails = json_decode($request->invitation_emails, true);
         foreach ($invitation_emails as $invitation_email){
             $user_status = client::where("email", $invitation_email['email'])->first();
             if (!is_null($user_status)) {
                 return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! email already registered"]);
             }
             $user_status = Invitation::where("email", $invitation_email['email'])->first();
             if (!is_null($user_status)) {
                 $res=Invitation::where("email", $invitation_email['email'])->delete();
             }
             $token = substr(md5(rand(0, 9) . $invitation_email['email'] . time()), 0, 32);
             $Invitation = new Invitation;
             $Invitation->email = $invitation_email['email'];
             $Invitation->role_id = $invitation_email['role_id'];
             $Invitation->invitation_token = $token;
             $Invitation->save();
             $invitation_link = urldecode(env('APP_FRONTEND_URL') . '/member/'. $token);
             $details = [
                 'title' => 'Register Invitation',
                 'body' => $invitation_link
             ];
             $mail = Mail::to($invitation_email['email'])->send(new ClientInvitationEmail($details));
         }
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Invitation email sent successfully."]);
    }
    /**
     * Override the application registration form. Get the email that has been associated with the invitation and
     * pass it to the view.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "invitation_token" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "validation_failed", "message" => "validation_error", "errors" => $validator->errors()]);
        }
        $invitation_token = $request->invitation_token;
        $invitation = Invitation::where('invitation_token', $invitation_token)->first();
        if (is_null($invitation)) {
            return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! No invitation exist against this email"]);
        }
        $user_status = client::where("email", $invitation->email)->first();
        if (!is_null($user_status)) {
            return response()->json(["status" => "failed", "error" => false, "message" => "Whoops! email already registered"]);
        }
        return response()->json(["status" => $this->status_code, "success" => true, "message" => "Show Registration Form.", "data" => $invitation]);
    }
}