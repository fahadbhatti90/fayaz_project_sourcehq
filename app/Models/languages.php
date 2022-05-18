<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class languages extends Model
{
    use HasFactory;
    protected $table = 'languages';
    protected $fillable = [
        'name','code'
    ];
}