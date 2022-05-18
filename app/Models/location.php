<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\location_tax;
use App\Models\jobs;

class location extends Model
{
    use HasFactory;
    protected $table = 'locations';
    protected $fillable = [
        "business_id",
        "location_name",
        "address",
        "state",
        "country",
        "zip_code",
        "location_status",
    ];
    /**
     * Get the comments for the blog post.
     */
    public function location_tax()
    {
        return $this->hasMany(location_tax::class);
    }
    /**
     * Get the user that owns the phone.
     */
    public function jobs()
    {
        return $this->belongsTo(jobs::class);
    }
}
