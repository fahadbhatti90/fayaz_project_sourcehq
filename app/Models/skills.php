<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\jobs;

class skills extends Model
{
    use HasFactory;
    protected $table = 'skills';
    protected $fillable = [
        "business_id",
        "name",
        "skill_status"
    ];
    public function jobs()
    {
        return $this->belongsToMany(jobs::class);
    }
}
