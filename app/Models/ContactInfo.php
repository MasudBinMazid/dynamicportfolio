<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = [
        'email','phone','address','github','linkedin','twitter','map_embed','cv_url'
    ];
}
