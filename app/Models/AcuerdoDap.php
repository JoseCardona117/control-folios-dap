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

    protected static function booted()
    {
        static::updating(function ($acuerdo) {
            if ($acuerdo->isDirty('estado')) {
                if(in_array($acuerdo->estado, ['cumplido', 'no_cumplido'])) {
                    $acuerdo->fecha_cumplimiento = now();
                }

                if(in_array($acuerdo->estado, ['pendiente', 'en_proceso'])) {
                    $acuerdo->fecha_cumplimiento = null;
                }
            }
        });

        static::updated(function ($acuerdo) {

            if (!$acuerdo->wasChanged('estado')) {
                return;
            }
            $minuta = $acuerdo->minuta;


            $acuerdosAbiertos = $minuta->acuerdos()
                ->whereIn('estado', ['pendiente', 'en_proceso'])
                ->exists();

            // if($acuerdosAbiertos) {
            //     $minuta->update(['estado' => 'abierta']);
            // } else {
            //     $minuta->update(['estado' => 'cerrada']);
            // }

            $minuta->estado = $acuerdosAbiertos ? 'abierta' : 'cerrada';
            $minuta->save();


        });
    }

    public function minuta()
    {
        return $this->belongsTo(MinutaDap::class, 'minuta_id');
    }
}
