<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeccionDap extends Model
{
    protected $table = 'secciones_dap';
    protected $primaryKey = 'id';
    public $timestamps = false; // si NO tiene created_at, updated_at

    protected $fillable = [
        'nombre',
        'codigo'
    ];
}
