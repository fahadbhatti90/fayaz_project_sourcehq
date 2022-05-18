<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\business;

//class client extends Model
class client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'business_id', 'first_name', 'middle_name', 'last_name', 'user_name', 'sso_id' , 'email' , 'password' , 'auth_code' , 'client_status' , 'client_role' , 'profile_img' , 'currency','isConfirmed'
    ];
    /**
     * Get the phone associated with the user.
     */
    public function confirmation_codes()
    {
        return $this->hasOne(ClientConfirmationCodes::class, 'fk_client_id','id');
    }
    /**
     * Get the phone associated with the user.
     */
    public function client_business()
    {
        //return $this->belongsTo(business::class);
        return $this->hasOne(business::class, 'id','business_id');
    }
    public function hiring_manager()
    {
        return $this->belongsTo(jobs::class,'hiring_manager');
    }
    public function secondary_hiring_manager()
    {
        return $this->belongsTo(jobs::class,'hiring_manager_1');
    }

}
