<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class general_setting extends Model
{
    protected $table = 'general_settings';
    protected $fillable = [
        'settings_label_id', 'label_name','code','description','general_setting_label_status'
    ];
}
