<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    protected $table = 'invitations';
    protected $fillable = [
        'email', 'role_id', 'invitation_token', 'registered_at',
    ];
    public function generateInvitationToken() {
      return   $this->invitation_token = substr(md5(rand(0, 9) . $this->email . time()), 0, 32);
    }
    /**
     * @return string
     */
    public function getLink() {
        return urldecode(env('APP_FRONTEND_URL') . '/invitation_token=' . $this->invitation_token);
    }
}
