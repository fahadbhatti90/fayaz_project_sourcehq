<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class selected_collaborator_members extends Model
{
    use HasFactory;
    protected $table = 'selected_collaborator_members';
    protected $fillable = [
        "job_id",
        "collaborator_members_id"
    ];
}
