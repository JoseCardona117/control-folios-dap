<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcuerdoDap extends Model
{
    protected $table = 'acuerdos_dap';
    protected $fillable = [
        'minuta_id',
        'description',
        'responsable',
        'estado',
        'fecha_compromiso',
        'fecha_cumplimiento',
        'observaciones',
    ];

    public function minuta()
    {
        return $this->belongsTo(MinutaDap::class, 'minuta_id');
    }
}
