<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class collaborator_members extends Model
{
    use HasFactory;
    protected $table = 'collaborator_members';
    protected $fillable = [
        "business_id",
        "name",
        "collaborator_member_status"
    ];
}
