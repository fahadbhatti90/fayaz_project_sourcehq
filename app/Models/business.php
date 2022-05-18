<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\client;

class business extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $table = 'businesses';
    protected $fillable = [
        'business_name','business_mission','business_url','crm_business','business_phone','career_portal','sub_domain','business_compliance','business_country','business_state','sso_meta_data','logo' ,'company_number','vat_tax_id','founding_year','language','currency','business_status'
    ];
    /**
     * Get the user that owns the phone.
     */
    public function business_clients()
    {
        //return $this->hasOne(client::class, 'business_id','id');
        return $this->belongsTo(client::class);
    }
}
