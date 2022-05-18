<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientConfirmationCodes extends Model
{
    use HasFactory;
    protected $table = 'client_confirmation_codes';
    protected $fillable = [
        'fk_client_id', 'confirmation_code',
    ];
    /**
     * Get the user that owns the phone.
     */
    public function clients()
    {
        return $this->belongsTo(client::class);
    }
}
