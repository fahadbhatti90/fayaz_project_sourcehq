<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class general_business_settings extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $table = 'general_business_settings';
    protected $fillable = [
        'label_name', 'business_setting_label_status'
    ];
}
