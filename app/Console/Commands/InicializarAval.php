<?php

namespace App\Console\Commands;

use App\Models\Boleta;
use App\Models\Cuentasclara;
use App\Models\Detallesesion;
use App\Models\Historialaval;
use App\Models\Militante;
use App\Models\Sesionventa;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InicializarAval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avales:iniciar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicializar el año de Avales y generar histórico';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        try {
            DB::beginTransaction();
            $militantes = Militante::where('avalado', 1)->get();
            foreach ($militantes as $militante) {
                $historial = new Historialaval();
                $historial->idmilitante = $militante->id;
                $historial->periodo = $militante->periodo?$militante->periodo:Carbon::now()->year-1;
                $historial->idcorporacion = $militante->idcorporacion;
                $historial->electo = $militante->electo?$militante->electo:0;
                $historial->votos = $militante->votos;
                $historial->coalicion = $militante->coalicion;
                $historial->estado = 1;
                $historial->nombrecoalicion = $militante->nombrecoalicion;
                $historial->observaciones = $militante->observaciones;
                $historial->renuncio = $militante->renuncio;
                $historial->fecharenuncia = $militante->fecharenuncia;
                $historial->idremplazo = $militante->idremplazo;
                $cc = Cuentasclara::where('idmilitante', $militante->id)->first();
                $historial->idcuentasclaras = $cc?$cc->id:null;
                $historial->save();

                $militante->periodo = null;
                $militante->idcorporacion = null;
                $militante->electo = null;
                $militante->votos = 0;
                $militante->coalicion = null;
                $militante->avalado = 0;
                $militante->nombrecoalicion = '';
                $militante->renuncio = null;
                $militante->fecharenuncia = null;
                $militante->idremplazo =null;
                $militante->save();
            }
            DB::commit();
        } catch (Throwable $e){
            DB::rollBack();
        }
    }
}
