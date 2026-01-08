<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolioDap extends Model
{
    protected $table = 'folios_dap';
    protected $primaryKey = 'id';
    public $timestamps = false; // si NO tiene created_at, updated_at

    protected $fillable = [
        'folio',
        'id_seccion',
        'responsable',
        'asunto',
        'dirigido',
        'fecha',
        'archivo'
    ];

    //Relacion con Secciones
    public function seccion()
    {
        return $this->belongsTo(
            SeccionDap::class,
            'id_seccion',
            'id_seccion'
        );
    }

    //Relación con usuarios
    public function responsableUsuario()
    {
        return $this->belongsTo(
            User::class,    
            'responsable',  //FK en folios_dap
            'id_uaa'        //PK alternativa
        );
    }
}
