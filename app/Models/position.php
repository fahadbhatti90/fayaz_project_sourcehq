<?php

namespace App\Models;

use App\Models\jobs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class position extends Model
{
    use HasFactory;
    protected $table = 'positions';
    protected $fillable = [
        "business_id",
        "name",
        "code",
        "position_status"
    ];
    public function user()
    {
        return $this->belongsTo(jobs::class,'job_position');
    }
}
