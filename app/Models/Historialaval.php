<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historialaval  extends Model  // implements Auditable
{
    //use AuditableTrait;

    protected $table = 'historialaval';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'idmilitante ', 'idcorporacion', 'periodo', 'electo', 'estado', 'votos',
        'coalicion', 'nombrecoalicion','renuncio', 'observaciones', 'fecharenuncia', 'idremplazo',
        'idcuentasclaras','ccreposicion', 'ccobservaciones', 'cccreated_at', 'ccupdated_at'
    ];

    public function militante(){
        return $this->belongsTo(Militante::class, 'idmilitante');
    }

    public function corporacion(){
        return $this->belongsTo(Corporacion::class, 'idcorporacion');
    }

    public function remplazo(){
        return $this->belongsTo(Militante::class, 'idremplazo');
    }

    public function cuentasclaras(){
        return $this->belongsTo(Cuentasclara::class, 'idcuentasclaras');
    }

}
