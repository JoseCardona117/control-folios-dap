<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinutaDap extends Model
{

    protected $table = 'minutas_dap';
    protected $fillable =  [
        'folio',
        'motivo',
        'fecha_reunion',
        'convoca',
        'estado',
        'observaciones',
        'evidencia',
    ];

    protected $cast = [
        'fecha_reunion' => 'date',
        'fecha_cumplimiento' => 'date',
    ];

    public function acuerdos()
    {
        return $this->hasMany(AcuerdoDap::class, 'minuta_id');
    }
}
