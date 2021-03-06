<?php


namespace Koost89\LoginLinks\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLinkToken extends Model
{
    protected $fillable = ['url', 'visits_allowed'];

    public function hasExceededVisitLimit()
    {
        return $this->visits >= $this->visits_allowed;
    }
}
