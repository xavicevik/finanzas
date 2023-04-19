<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\Model;


class Solicitudaval extends Model implements Auditable
{
    use AuditableTrait;


    protected $guard = 'solicitudaval';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'idmilitante',
        'estado',
        'estado_ant',
        'iddelegado',
        'fechasolicitud',
        'fecharevision',
        'fechaaprobacion',
        'idcorporacion',
        'idrevisor',
        'periodo',
        'observaciones'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fechasolicitud' => 'datetime',
    ];


    public function militante(){
        return $this->belongsTo(Militante::class, 'idmilitante');
    }

    public function delegado(){
        return $this->belongsTo(User::class, 'iddelegado');
    }

    public function revisor(){
        return $this->belongsTo(User::class, 'idrevisor');
    }

    public function estados(){
        return $this->belongsTo(Estado::class, 'estado');
    }

    public function estadosant(){
        return $this->belongsTo(Estado::class, 'estado_ant');
    }

    public function corporacion() {
        return $this->belongsTo(Corporacion::class, 'idcorporacion');
    }


}
