<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    public $timestamps = [ "created_at" ];
    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'verification_code'
    ];
}
