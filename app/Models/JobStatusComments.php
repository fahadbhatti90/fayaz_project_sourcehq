<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatusComments extends Model
{
    use HasFactory;
    protected $table = 'job_status_comments';
    protected $fillable = [
        "job_status",
        "status_change_reason",
        "status_change_comment"
    ];
}
