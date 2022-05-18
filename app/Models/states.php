<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class states extends Model
{
    use HasFactory;
    protected $table = 'states';
    protected $fillable = [
        "fk_country_id",
        "state_name"
    ];
}
