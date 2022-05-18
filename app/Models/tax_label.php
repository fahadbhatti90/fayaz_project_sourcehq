<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tax_label extends Model
{
    use HasFactory;
    protected $table = 'tax_labels';
    protected $fillable = [
        "business_id",
        "label_name",
        "label_code",
        "tax_label_status"
    ];
}
