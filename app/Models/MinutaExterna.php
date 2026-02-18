<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinutaExterna extends Model
{
    protected $fillable = [
        'folio',
        'motivo',
        'fecha_reunion',
        'convoca',
        'observaciones',
        'evidencia',
    ];
}
