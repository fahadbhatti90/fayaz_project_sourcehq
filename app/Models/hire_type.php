<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hire_type extends Model
{
    use HasFactory;
    protected $table = 'hire_type';
    protected $fillable = [
        "business_id",
        "name",
        "code",
        "hire_type_status"
    ];
}
