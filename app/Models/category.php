<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\jobs;


class category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        "business_id",
        "name",
        "code",
        "category_status"
    ];

    public function user()
    {
        return $this->belongsTo(jobs::class,'job_category');
    }
}