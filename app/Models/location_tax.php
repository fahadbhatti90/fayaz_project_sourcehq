<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\location;

class location_tax extends Model
{
    use HasFactory;
    protected $table = 'location_taxes';
    protected $fillable = [
        "business_id",
        "location_id" ,
        "tax_label_id" ,
        "tax_percentage" ,
        "location_tax_status"
    ];
    /**
     * Get the post that owns the comment.
     */
    public function location()
    {
        return $this->belongsTo(location::class);
    }
}
