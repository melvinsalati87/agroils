<?php

namespace App\Models\Templates;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $fillable = [
        'template_uuid',
        'me'
    ]
}
