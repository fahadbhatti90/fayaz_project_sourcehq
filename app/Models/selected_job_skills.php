<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class selected_job_skills extends Model
{
    use HasFactory;
    protected $table = 'selected_job_skills';
    protected $fillable = [
        "job_id",
        "skill_id"
    ];
}
