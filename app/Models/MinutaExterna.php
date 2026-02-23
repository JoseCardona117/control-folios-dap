<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinutaExterna extends Model
{
    protected $table = 'minutas_externas';
    protected $fillable = [
        'folio',
        'motivo',
        'fecha_reunion',
        'convoca',
        'observaciones',
        'evidencia',
    ];

    public function acuerdos()
    {
        return $this->hasMany(AcuerdoExterno::class, 'minuta_id');
    }
}
