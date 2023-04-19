<?php

namespace App\Http\Controllers;

use App\Exports\ClientesExport;
use App\Exports\MilitantesExport;
use App\Exports\UsersExport;
use App\Imports\MilitantesImport;
use App\Jobs\SendEmailAvalJob;
use App\Mail\Notificaciones;
use App\Models\Archivo;
use App\Models\Audits;
use App\Models\Cuentasclara;
use App\Models\Departamento;
use App\Models\Estado;
use App\Models\Historial;
use App\Models\Imagen;
use App\Models\Militante;
use App\Models\Solicitudaval;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use File;
use ZipArchive;


class AvalController extends Controller
{
    const canPorPagina = 15;
    const nuCreacion = 1;
    const nuModificacion = 2;
    const nuSancion = 3;
    const nuEliminacion = 4;
    const nuRenuncia = 5;
    const nuRemplazo = 6;
    const nuAprobacion = 7;
    const nuSolicitudcc = 9;
    const nuReposicioncc = 10;
    const nuSolicitudaval = 13;

    function __construct()
    {
        $this->middleware('permission:aval-list|aval-create|aval-edit|aval-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $filtros = json_decode($request->filtros);
        $roluser = Auth::user()->load('roles')->roles->first()->id;

        if ($request->has('sortBy') && $request->sortBy <> ''){
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'fechasolicitud';
        }

        if ($request->has('sortOrder') && $request->sortOrder <> ''){
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'asc';
        }

        $solicitudes = Solicitudaval::orderBy($sortBy, $sortOrder)
                                    ->with('militante')
                                    ->with('estados')
                                    ->with('estadosant')
                                    ->with('delegado')
                                    ->with('revisor')
                                    ->with('corporacion')
                                    ->with('militante.archivos')
                                    ->with('militante.solicitud');

        if ($buscar <> '') {
            $solicitudes = $solicitudes
                        ->where('nombre', 'like', '%'. $buscar . '%')
                        ->orWhere('documento', 'like', '%'. $buscar . '%')
                        ->orWhere('estado', 'like', '%'. $buscar . '%');
        }

        if (!is_null($filtros)) {
            if (isset($filtros->idmilitante) && $filtros->idmilitante <> '-' && $filtros->idmilitante <> 0 && $filtros->idmilitante <> null) {
                $solicitudes = $solicitudes->where('idmilitante', $filtros->idmilitante);
            }
            if(!is_null($filtros->fechainicioregistro) && $filtros->fechainicioregistro <> '' && $filtros->fechainicioregistro <> null) {
                $solicitudes = $solicitudes->where('fechasolicitud', '>=', $filtros->fechainicioregistro);
            }
            if(!is_null($filtros->fechafinregistro) && $filtros->fechafinregistro <> '' && $filtros->fechafinregistro <> null) {
                $solicitudes = $solicitudes->where('fechasolicitud', '<=', $filtros->fechafinregistro);
            }
            if(!is_null($filtros->fechainiciorevision) && $filtros->fechainiciorevision <> '' && $filtros->fechainiciorevision <> null) {
                $solicitudes = $solicitudes->where('fecharevision', '>=', $filtros->fechainiciorevision);
            }
            if(!is_null($filtros->fechafinrevision) && $filtros->fechafinrevision <> '' && $filtros->fechafinrevision <> null) {
                $solicitudes = $solicitudes->where('fecharevision', '<=', $filtros->fechafinrevision);
            }
            if(!is_null($filtros->fechainicioaprobacion) && $filtros->fechainicioaprobacion <> '' && $filtros->fechainicioaprobacion <> null) {
                $solicitudes = $solicitudes->where('fechaaprobacion', '>=', $filtros->fechainicioaprobacion);
            }
            if(!is_null($filtros->fechafinaprobacion) && $filtros->fechafinaprobacion <> '' && $filtros->fechafinaprobacion <> null) {
                $solicitudes = $solicitudes->where('fechaaprobacion', '<=', $filtros->fechafinaprobacion);
            }

            if (!is_null($filtros->documento) && $filtros->documento <> '' && $filtros->documento <> 0) {
                $solicitudes = $solicitudes->join('militantes', 'militantes.id', '=', 'solicitudavals.idmilitante')
                    ->where('militantes.documento', 'like', "%$filtros->documento%")
                    ->select('solicitudavals.*');
            }
            if (!is_null($filtros->nombre) && $filtros->nombre <> '' && $filtros->nombre <> 0) {
                $solicitudes = $solicitudes->join('militantes as n', 'n.id', '=', 'solicitudavals.idmilitante')
                    ->where('n.nombre', 'like', "%$filtros->nombre%")
                    ->orWhere('n.apellido', 'like', "%$filtros->nombre%")
                    ->select('solicitudavals.*');
            }

            if (!is_null($filtros->iddepartamento) && $filtros->iddepartamento <> '' && $filtros->iddepartamento <> 0) {
                $solicitudes = $solicitudes->join('militantes as d', 'd.id', '=', 'solicitudavals.idmilitante')
                    ->where('d.iddepartamento', $filtros->iddepartamento)
                    ->select('solicitudavals.*');
            }
            if (!is_null($filtros->idciudad) && $filtros->idciudad <> '' && $filtros->idciudad <> 0) {
                $solicitudes = $solicitudes->join('militantes as c', 'c.id', '=', 'solicitudavals.idmilitante')
                    ->where('c.idciudad', $filtros->idciudad)
                    ->select('solicitudavals.*');
            }
            if (!is_null($filtros->idcorporacion) && $filtros->idcorporacion <> '-' && $filtros->idcorporacion <> 0 && $filtros->idcorporacion <> null) {
                $solicitudes = $solicitudes->where('idcorporacion', $filtros->idcorporacion);
            }
            if (!is_null($filtros->estado) && $filtros->estado <> '-' && $filtros->estado <> 0 && $filtros->estado <> null) {
                $solicitudes = $solicitudes->where('estado', $filtros->estado);
            }
            if (!is_null($filtros->estado_ant) && $filtros->estado_ant <> '-' && $filtros->estado_ant <> 0 && $filtros->estado_ant <> null) {
                $solicitudes = $solicitudes->where('estado_ant', $filtros->estado_ant);
            }
            if (!is_null($filtros->iddelegado) && $filtros->iddelegado <> '-' && $filtros->iddelegado <> 0 && $filtros->iddelegado <> null) {
                $solicitudes = $solicitudes->where('iddelegado', $filtros->iddelegado);
            }
            if (!is_null($filtros->idrevisor) && $filtros->idrevisor <> '-' && $filtros->idrevisor <> 0 && $filtros->idrevisor <> null) {
                $solicitudes = $solicitudes->where('idrevisor', $filtros->idrevisor);
            }

        }
        $militante = null;
        if ($roluser == 3) {
            $solicitudes = $solicitudes->where('idmilitante', Auth::user()->id);
            $solicitudes = $solicitudes->orderBy($sortBy, $sortOrder);

            $militante = Militante::where('id',Auth::user()->id)->with('archivos')->with('solicitud')->with('solicitud.estados')->first();
        } elseif (Auth::user()->rol->nombre == 'Delegado') {
            $solicitudes = $solicitudes->where('iddelegado', Auth::user()->id)
                ->orderBy('fechasolicitud', 'asc');
        } else {

        }
        $solicitudes = $solicitudes->paginate(self::canPorPagina);

        //$militante = null;
        //if ($request->id > 0) {
        //    $militante = Militante::where('id',Auth::user()->id)->with('archivos')->with('solicitud')->with('solicitud.estados')->first();
        //}

        $delegados = User::where('idrol', 13)->get();
        $revisores = User::where('idrol', 14)->get();
        $departamentos = Departamento::all();
        $estados = Estado::where('id', '>', 13)->get();

        if ($request->has('ispage') && $request->ispage){
            return ['solicitudes' => $solicitudes];
        } else {

            return Inertia::render('Avales/Index', ['solicitudes' => $solicitudes,
                                                              'militante' => $militante,
                                                              'delegados' => $delegados,
                                                              'departamentos' => $departamentos,
                                                              'revisores' => $revisores,
                                                              'estados' => $estados,
                                                              'roluser' => $roluser,
                                                              '_token' => csrf_token()]);
        }
    }

    public function downloadZip(Request $request) {
        $zip = new ZipArchive();

        $fileName = "solicitudAval_$request->idmilitante.zip";

        if ($zip->open(public_path("storage/archivos/$fileName"), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files(public_path("storage/archivos/$request->idmilitante/"));

            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
            $zip->close();
        }

        return response()->download(public_path("storage/archivos/$fileName"));
    }

    public function storefile(Request $request) {
        try{
            DB::beginTransaction();

            if ($request->idtipoarchivo == 33) {
                $allowedfileExtension = ['jpg'];
                $msg = 'La foto debe estár en formato jpg';
            } else {
                $allowedfileExtension = ['pdf'];
                $msg = 'Solo se permiten archivos pdf';
            }
            $codigo = 1;

            if(isset($request->file)){
                $file = $request->file;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);

                if($check) {
                    $archivo = new Archivo();
                    $archivo->idmilitante = $request->idmilitante;
                    $archivo->idtipoarchivo = $request->idtipoarchivo;
                    $archivo->nombre = $filename;

                    $archivo->extension = $extension;
                    $filename = time(). '_' . $filename;

                    if (config('app.env') == 'local') {
                        $path = $file->storeAs("archivos/$request->idmilitante", $filename);
                        $archivo->url = Storage::url($path);
                    } else {
                        $path = $file->storeAs("archivos/$request->idmilitante", $filename, 's3');
                        $archivo->url = Storage::disk('s3')->url($path);
                    }
                    $archivo->tamaño = $file->getSize();
                    $archivo->save();
                } else {
                    $codigo = -1;
                    $archivo = null;
                    $mensaje = $msg;
                }
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
                $mensaje = 'Archivo actualizado';
            }
        } catch (Throwable $e){
            DB::rollBack();

            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return ['archivo' => $archivo, 'mensaje' => $mensaje, 'codigo' => $codigo];
    }


    public function deletefile(Request $request) {
        $archivo = Archivo::find($request->id);
        $idtipoarchivo= $archivo->idtipoarchivo;
        Storage::delete($archivo->url);
        $archivo->delete();

        return ['idtipoarchivo' => $idtipoarchivo];
    }

    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $codigo = 1;
            $correodelegado = '';

            $solicitud = Solicitudaval::where('idmilitante', $request->idmilitante)->first();

            if($solicitud) {
                $codigo = -1;
                $mensaje = "Ya existe una solicitud registrada [$solicitud->id]";
            } else {
                $solicitud = new Solicitudaval();
                $solicitud->idmilitante = $request->idmilitante;
                $solicitud->estado = 17;
                $solicitud->estado_ant = 10;
                $solicitud->fechasolicitud = Carbon::now()->toDateTimeString();
                $solicitud->idcorporacion = $request->idcorporacion;
                //$solicitud->observaciones = $request->observaciones;
                $solicitud->periodo = $request->periodo;
                if (Auth::user()->roles[0]->id == 13) {
                    $solicitud->iddelegado = Auth::user()->id;
                    $correodelegado = Auth::user()->email;
                }

                $solicitud->save();
                $this->setHistorial($request->idmilitante, self::nuSolicitudaval, 'Se realizó registro de solicitud de Aval', 6);

                $subject = config('mail.solicitudes.registrar.subject'). ' ['.$solicitud->id."]" ;
                $line1 = config('mail.solicitudes.registrar.line1');
                $action = config('mail.solicitudes.registrar.action');
                $line2 = config('mail.solicitudes.registrar.line2'). ' ['.$solicitud->id."]" ;
                $line3 = config('mail.solicitudes.registrar.line3');
                $val = '';

                $militante = Militante::find($request->idmilitante);
                $militante->idsolicitudaval = $solicitud->id;
                $militante->save();

                //$iduser = 1;
                $iduser = $request->idmilitante;
                SendEmailAvalJob::dispatch($iduser, $subject, $line1, $line2, $line3, $action, $val, $correodelegado, Auth::user()->roles[0]->id);
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
                $mensaje = "Se registró la solicitud [$solicitud->id]";
            }
        } catch (Throwable $e){
            DB::rollBack();
            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return ['solicitud' => $solicitud?$solicitud:0, 'mensaje' => $mensaje, 'codigo' => $codigo];

    }

    public function actualizar(Request $request)
    {
        try{
            DB::beginTransaction();
            $codigo = 1;
            $correodelegado = '';

            $solicitud = Solicitudaval::where('idmilitante', $request->idmilitante)->where('estado', 14)->first();

            if(!$solicitud) {
                $codigo = -1;
                $mensaje = "No existe solicitud o no se encuentra en el estado requerido";
            } else {
                $solicitud->idmilitante = $request->idmilitante;
                $solicitud->estado_ant = $solicitud->estado;
                $solicitud->estado = 17;
                $solicitud->fechasolicitud = Carbon::now()->toDateTimeString();
                //$solicitud->observaciones = $request->observaciones;
                $solicitud->save();
                $this->setHistorial($request->idmilitante, self::nuSolicitudaval, 'Se realizó la actualización de la solicitud de Aval', 6);

                $subject = config('mail.solicitudes.actualizar.subject'). ' ['.$solicitud->id."]" ;
                $line1 = config('mail.solicitudes.actualizar.line1');
                $action = config('mail.solicitudes.actualizar.action');
                $line2 = config('mail.solicitudes.actualizar.line2'). ' ['.$solicitud->id."]" ;
                $line3 = config('mail.solicitudes.actualizar.line3');
                $val = '';

                $militante = Militante::find($request->idmilitante);
                $militante->idsolicitudaval = $solicitud->id;
                $militante->save();

                if (!is_null($solicitud->iddelegado)) {
                    $delegado = User::where('id', $solicitud->iddelegado)->first();
                    $correodelegado = $delegado->email;
                }

                //$iduser = 1;
                $iduser = $request->idmilitante;
                SendEmailAvalJob::dispatch($iduser, $subject, $line1, $line2, $line3, $action, $val, $correodelegado, Auth::user()->roles[0]->id);
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
                $mensaje = "Se actualizó la solicitud [$solicitud->id]";
            }
        } catch (Throwable $e){
            DB::rollBack();
            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return ['solicitud' => $solicitud?$solicitud:0, 'mensaje' => $mensaje, 'codigo' => $codigo];
    }

    public function revisar(Request $request)
    {
        try{
            DB::beginTransaction();
            $codigo = 1;
            $correodelegado = '';

            $solicitud = Solicitudaval::where('idmilitante', $request->idmilitante)->where('estado', 17)->first();

            if(!$solicitud) {
                $codigo = -1;
                $mensaje = "No existe solicitud o no se encuentra en el estado requerido";
            } else {
                $solicitud->idmilitante = $request->idmilitante;
                $solicitud->estado_ant = $solicitud->estado;
                $solicitud->estado = 15;
                $solicitud->fecharevision = Carbon::now()->toDateTimeString();
                //$solicitud->observaciones = $request->observaciones;
                $solicitud->idrevisor = Auth::id();
                $solicitud->save();
                $this->setHistorial($request->idmilitante, self::nuSolicitudaval, 'Se realizó la revisión de solicitud de Aval', 6);

                $subject = config('mail.solicitudes.revisar.subject'). ' ['.$solicitud->id."]" ;
                $line1 = config('mail.solicitudes.revisar.line1');
                $action = config('mail.solicitudes.revisar.action');
                $line2 = config('mail.solicitudes.revisar.line2'). ' ['.$solicitud->id."]" ;
                $line3 = config('mail.solicitudes.revisar.line3');
                $val = '';

                $militante = Militante::find($request->idmilitante);
                $militante->idsolicitudaval = $solicitud->id;
                $militante->save();

                if (!is_null($solicitud->iddelegado)) {
                    $delegado = User::where('id', $solicitud->iddelegado)->first();
                    $correodelegado = $delegado->email;
                }

                //$iduser = 1;
                $iduser = $request->idmilitante;
                SendEmailAvalJob::dispatch($iduser, $subject, $line1, $line2, $line3, $action, $val, $correodelegado, Auth::user()->roles[0]->id);
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
                $mensaje = "Se reviso la solicitud [$solicitud->id]";
            }
        } catch (Throwable $e){
            DB::rollBack();
            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return ['solicitud' => $solicitud?$solicitud:0, 'mensaje' => $mensaje, 'codigo' => $codigo];
    }

    public function rechazar(Request $request)
    {
        try{
            DB::beginTransaction();
            $codigo = 1;
            $correodelegado = '';

            $solicitud = Solicitudaval::where('idmilitante', $request->idmilitante)->whereIn('estado', [17])->first();

            if(!$solicitud) {
                $codigo = -1;
                $mensaje = "No existe solicitud o no se encuentra en el estado requerido";
            } else {
                $solicitud->idmilitante = $request->idmilitante;
                $solicitud->estado_ant = $solicitud->estado;
                $solicitud->estado = 14;
                $solicitud->fecharevision = Carbon::now()->toDateTimeString();
                $solicitud->observaciones = $request->observaciones;
                $solicitud->idrevisor = Auth::id();
                $solicitud->save();
                $this->setHistorial($request->idmilitante, self::nuSolicitudaval, 'Se ha rechazado la solicitud de aval. '.$request->observaciones, 6);

                $subject = config('mail.solicitudes.rechazar.subject'). ' ['.$solicitud->id."]" ;
                $line1 = config('mail.solicitudes.rechazar.line1');
                $action = config('mail.solicitudes.rechazar.action');
                $line2 = config('mail.solicitudes.rechazar.line2'). ' ['.$solicitud->id."]" ;
                $line3 = config('mail.solicitudes.rechazar.line3');
                $val = '';

                $militante = Militante::find($request->idmilitante);
                $militante->idsolicitudaval = $solicitud->id;
                $militante->save();

                if (!is_null($solicitud->iddelegado)) {
                    $delegado = User::where('id', $solicitud->iddelegado)->first();
                    $correodelegado = $delegado->email;
                }

                //$iduser = 1;
                $iduser = $request->idmilitante;
                SendEmailAvalJob::dispatch($iduser, $subject, $line1, $line2, $line3, $action, $val, $correodelegado, Auth::user()->roles[0]->id);
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
                $mensaje = "Se rechazó la solicitud [$solicitud->id]";
            }
        } catch (Throwable $e){
            DB::rollBack();
            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return ['solicitud' => $solicitud?$solicitud:0, 'mensaje' => $mensaje, 'codigo' => $codigo];

    }

    public function avalar(Request $request)
    {
        try{
            DB::beginTransaction();
            $codigo = 1;
            $correodelegado = '';

            $solicitud = Solicitudaval::where('idmilitante', $request->idmilitante)->where('estado', 15)->first();

            if(!$solicitud) {
                $codigo = -1;
                $mensaje = "No existe solicitud o no se encuentra en el estado requerido";
            } else {
                $solicitud->idmilitante = $request->idmilitante;
                $solicitud->estado_ant = $solicitud->estado;
                $solicitud->estado = 16;
                $solicitud->fechaaprobacion = Carbon::now()->toDateTimeString();
                //$solicitud->observaciones = $request->observaciones;
                $solicitud->idrevisor = Auth::id();
                $solicitud->save();
                $this->setHistorial($request->idmilitante, self::nuSolicitudaval, 'Se realizó el avál del militante', 6);

                $militante = Militante::where('id', $request->idmilitante)->first();
                $militante->avalado = true;
                $militante->idcorporacion = $solicitud->idcorporacion;
                $militante->periodo = $solicitud->periodo;
                $militante->save();

                $subject = config('mail.solicitudes.avalar.subject'). ' ['.$solicitud->id."]" ;;
                $line1 = config('mail.solicitudes.avalar.line1');
                $action = config('mail.solicitudes.avalar.action');
                $line2 = config('mail.solicitudes.avalar.line2'). ' ['.$solicitud->id."]" ;
                $line3 = config('mail.solicitudes.avalar.line3');
                $val = '';

                $militante = Militante::find($request->idmilitante);
                $militante->idsolicitudaval = $solicitud->id;
                $militante->save();

                if (!is_null($solicitud->iddelegado)) {
                    $delegado = User::where('id', $solicitud->iddelegado)->first();
                    $correodelegado = $delegado->email;
                }

                //$iduser = 1;
                $iduser = $request->idmilitante;
                SendEmailAvalJob::dispatch($iduser, $subject, $line1, $line2, $line3, $action, $val, $correodelegado, Auth::user()->roles[0]->id);
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
                $mensaje = "Se avaló la solicitud [$solicitud->id]";
            }
        } catch (Throwable $e){
            DB::rollBack();
            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return ['solicitud' => $solicitud?$solicitud:0, 'mensaje' => $mensaje, 'codigo' => $codigo];

    }

    public function anular(Request $request)
    {
        try{
            DB::beginTransaction();
            $codigo = 1;
            $correodelegado = '';

            $solicitud = Solicitudaval::where('idmilitante', $request->idmilitante)->where('estado', 15)->first();

            if(!$solicitud) {
                $codigo = -1;
                $mensaje = "No existe solicitud o no se encuentra en el estado requerido";
            } else {
                $solicitud->idmilitante = $request->idmilitante;
                $solicitud->estado_ant = $solicitud->estado;
                $solicitud->estado = 9;
                $solicitud->fechaaprobacion = Carbon::now()->toDateTimeString();
                $solicitud->observaciones = $request->observaciones;
                $solicitud->idrevisor = Auth::id();
                $solicitud->save();
                $this->setHistorial($request->idmilitante, self::nuSolicitudaval, 'Se anulo la solicitud de aval', 6);

                $subject = config('mail.solicitudes.anular.subject'). ' ['.$solicitud->id."]" ;;
                $line1 = config('mail.solicitudes.anular.line1');
                $action = config('mail.solicitudes.anular.action');
                $line2 = config('mail.solicitudes.anular.line2'). ' ['.$solicitud->id."]" ;
                $line3 = config('mail.solicitudes.anular.line3');
                $val = '';

                $militante = Militante::find($request->idmilitante);
                $militante->idsolicitudaval = $solicitud->id;
                $militante->save();

                if (!is_null($solicitud->iddelegado)) {
                    $delegado = User::where('id', $solicitud->iddelegado)->first();
                    $correodelegado = $delegado->email;
                }

                //$iduser = 1;
                $iduser = $request->idmilitante;
                SendEmailAvalJob::dispatch($iduser, $subject, $line1, $line2, $line3, $action, $val, $correodelegado, Auth::user()->roles[0]->id);
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
                $mensaje = "Se anulo la solicitud [$solicitud->id]";
            }
        } catch (Throwable $e){
            DB::rollBack();
            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return ['solicitud' => $solicitud?$solicitud:0, 'mensaje' => $mensaje, 'codigo' => $codigo];

    }


    public function indexAuditoria(Request $request)
    {
        $buscar = $request->buscar;
        $filtros = json_decode($request->filtros);

        if ($request->has('sortBy') && $request->sortBy <> ''){
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'id';
        }

        if ($request->has('sortOrder') && $request->sortOrder <> ''){
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'desc';
        }

        $auditorias = Audit::orderBy($sortBy, $sortOrder)
                             ->join('users', 'user_id', 'users.id')
                             ->join('militantes', 'auditable_id', 'militantes.id')
                             ->select('audits.*', 'users.nombre AS nombreusuario', 'users.apellido AS apellidousuario',
                                      'militantes.nombre AS nombremilitante', 'militantes.apellido AS apellidomilitante');

        if ($buscar <> '') {
            $auditorias = $auditorias
                ->where('nombre', 'like', '%'. $buscar . '%')
                ->orWhere('apellido', 'like', '%'. $buscar . '%')
                ->orWhere('email', 'like', '%'. $buscar . '%')
                ->orWhere('documento', 'like', '%'. $buscar . '%');
        }

        if (!is_null($filtros)) {
            if(!is_null($filtros->fechainicio) && $filtros->fechainicio <> '' && $filtros->fechainicio <> null) {
                $auditorias = $auditorias->where('audits.updated_at', '>=', $filtros->fechainicio);
            }
            if(!is_null($filtros->fechafin) && $filtros->fechafin <> '' && $filtros->fechafin <> null) {
                $auditorias = $auditorias->where('audits.updated_at', '<=', $filtros->fechafin);
            }
            if(!is_null($filtros->usuario) && $filtros->usuario <> '-') {
                $auditorias = $auditorias->join('users as t1', 'audits.user_id', '=', 't1.id')
                    ->where('t1.nombre', 'like', '%'.$filtros->usuario.'%')
                    ->orWhere('t1.apellido', 'like', '%'.$filtros->usuario.'%')
                    ->orWhere('t1.documento', 'like', '%'.$filtros->usuario.'%');
            }
            if(!is_null($filtros->militante) && $filtros->militante <> '-') {
                $auditorias = $auditorias->join('militantes as t2', 'audits.auditable_id', '=', 't2.id')
                    ->where('t2.nombre', 'like', '%'.$filtros->militante.'%')
                    ->orWhere('t2.apellido', 'like', '%'.$filtros->militante.'%')
                    ->orWhere('t2.documento', 'like', '%'.$filtros->militante.'%');
            }
            if (!is_null($filtros->evento) && $filtros->evento <> '-') {
                $auditorias = $auditorias->where('event', $filtros->evento);
            }
        }

        $auditorias = $auditorias->paginate(self::canPorPagina);

        if ($request->has('ispage') && $request->ispage){
            return ['auditorias' => $auditorias];
        } else {
            return Inertia::render('Militantes/Indexauditoria', ['auditorias' => $auditorias, '_token' => csrf_token()]);
        }
    }

    public function getHistorial(Request $request)
    {
        $historial = Historial::where('idmilitante', $request->idmilitante)
                        ->with('usuario')
                        ->with('tipo')
                        ->orderby('updated_at', 'desc')
                        ->paginate(10);

        return ['historial' => $historial];
    }

    public function getCuentasClaras(Request $request)
    {
        $cuentasclaras = Cuentasclara::where('idmilitante', $request->idmilitante)
            ->with('archivo')
            ->with('estadonombre')
            ->first();

        return ['cuentasclaras' => $cuentasclaras];
    }

    public function getArchivos(Request $request)
    {
        $archivos =  Archivo::where('idmilitante', $request->idmilitante)
                              ->with('tipoarchivo')
                              ->get();

        return ['archivos' => $archivos];
    }

    public function archivoupload(Request $request) {
        try{
            DB::beginTransaction();

            $allowedfileExtension = ['pdf','jpg','png','docx','ppt', 'ppt', 'pptx','txt','csv' , 'doc', 'xls', 'xlsx'];
            $codigo = 1;

            if(isset($request->file)){
                $file = $request->file;
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);

                if($check) {
                    $archivo = new Archivo();
                    $archivo->idtipoarchivo = $request->idtipoarchivo;
                    $archivo->idmilitante = $request->idmilitante;
                    $archivo->nombre = $filename;

                    $archivo->extension = $extension;
                    $filename = time(). '_' . $filename;

                    if (config('app.env') == 'local') {
                        $path = $file->storeAs('archivos', $filename);
                        $archivo->url = Storage::url($path);
                    } else {
                        $path = $file->storeAs('archivos', $filename, 's3');
                        $archivo->url = Storage::disk('s3')->url($path);
                    }
                    $archivo->tamaño = $file->getSize();
                    $archivo->save();
                } else {
                    $codigo = -1;
                    $mensaje = 'La extensión de al menos un archivo no es permitida';
                }
            }

            if ($codigo == -1) {
                DB::rollBack();
            } else {
                DB::commit();
            }

            $mensaje = 'Archivo actualizado';
        } catch (Throwable $e){
            DB::rollBack();

            $codigo = -1;
            $mensaje = 'Se ha presentado un error';
        }
        return redirect()->back()->with('message', $mensaje);
        //return ['codigo' => $codigo, 'mensaje' => $mensaje];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Militante $militante)
    {
        $estadorenuncia = $militante->renuncio;
        try{
            DB::beginTransaction();
            $observaciones = 'El militante ha sido actualizado';
            /*
            $validation  = Validator::make($request->all(), [
                'nombre' => ['required', 'string', 'max:255'],
                'apellido' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'movil' => ['required', 'string', 'max:255'],
                'documento' => ['required', 'string', 'max:255'],
                'idtipos_documento' => 'required|numeric|gt:0',
                'iddepartamento' => 'required|numeric|gt:0',
                'idciudad' => 'required|numeric|gt:0',
                'idinscripcion' => 'required|numeric|gt:0',
                'idgenero' => 'required|numeric',
                'idniveleducativo' => 'required|numeric',
                'idgrupoetnico' => 'required|numeric',
            ],
                [
                    'nombre.required' => 'Ingrese el nombre',
                    'apellido.required' => 'Ingrese el apellido',
                    'email.required' => 'Ingrese el email',
                    'movil.required' => 'Ingrese el teléfono celular',
                    'documento.required' => 'Ingrese el número de identificacion',
                    'idtipos_documento.numeric' => 'Seleccione un tipo de documento',
                    'iddepartamento.numeric' => 'Seleccione un Departamento',
                    'idciudad.numeric' => 'Seleccione una ciudad',
                    'idinscripcion.numeric' => 'Seleccione la inscripción',
                    'idgenero.numeric' => 'Seleccione un género',
                    'idniveleducativo.numeric' => 'Seleccione el nivel educativo',
                    'idgrupoetnico.numeric' => 'Seleccione un grupo étnico',
                ])->validate();

            if ($validation->fails()) {
                dd($validation);
                return Response::make(['error' => $validation->errors()], 400);
            }
            */

            $militante->update($request->all());
            $this->setHistorial($militante->id, self::nuModificacion, $observaciones);
            if ($militante->renuncio == 1 && $estadorenuncia == 0) {
                $this->setRenuncia($militante);
            }
            DB::commit();

            return redirect()->back()->with('message', 'Usuario modificado satisfactoriamente');

        } catch (Throwable $e){
            DB::rollBack();

            return redirect()->back()->with('message', 'Error');
        }
    }

    public function updateEstado(Request $request, Militante $militante)
    {
        try{
            DB::beginTransaction();

            $mensajeserror = [];

            if ($request->tipo == 'sancionar') {
                $tipo = self::nuSancion;
                $estado = 11;
            } elseif ($request->tipo == 'aprobar') {
                $tipo = self::nuAprobacion;
                $estado = 1;

                if ($militante->nombre == '' || is_null($militante->nombre)) {
                    $mensajeserror[] = 'El nombre no puede estar vacio';
                }
                if ($militante->apellido == '' || is_null($militante->apellido)) {
                    $mensajeserror[] = 'El apellido no puede estar vacio';
                }
                if ($militante->documento == '' || is_null($militante->documento)) {
                    $mensajeserror[] = 'El documento no puede estar vacio';
                }
                if ($militante->email == '' || is_null($militante->email)) {
                    $mensajeserror[] = 'El correo no puede estar vacio';
                }
                $valFormulario = Archivo::where('idmilitante', $militante->id)
                                         ->where('idtipoarchivo', 1)
                                         ->first();
                if (!$valFormulario) {
                    $mensajeserror[] = 'Debe incluir el formulario de inscripción';
                }
                /*
                $valCertificado = Archivo::where('idmilitante', $militante->id)
                                        ->where('idtipoarchivo', 8)
                                        ->first();
                if (!$valCertificado) {
                    $mensajeserror[] = 'Debe realizar el curso y aprobar la evaluación';
                }
                */

            } elseif ($request->tipo == 'renunciar') {
                $tipo = self::nuRenuncia;
                $estado = 10;
            }

            if (sizeof($mensajeserror) == 0) {
                $militante->estado = $estado;
                $militante->save();
                $this->setHistorial($militante->id, $tipo, $request->observaciones);
                DB::commit();

                if($request->tipo == 'aprobar') {
                    $this->getCertificado($militante);
                }
                $estado = true;
            } else {
                DB::rollBack();
                $estado = false;
            }

            return ['estado' => $estado, 'mensajeserror' => $mensajeserror];
            //return redirect()->back()->with('message', 'Usuario modificado satisfactoriamente');

        } catch (Throwable $e){
            DB::rollBack();

            return redirect()->back()->with('message', 'Error');
        }
    }

    public function ccupdate(Request $request, Militante $militante)
    {
        try{
            DB::beginTransaction();

            if ($request->tipo == 'solicitar') {
                $tipo = self::nuSolicitudcc;
                $estado = 3;
                $militante->cccreated_at = $request->cccreated_at;
            } elseif ($request->tipo == 'reposicion') {
                $tipo = self::nuReposicioncc;
                $estado = 1;
                $militante->ccupdated_at = $request->ccupdated_at;
                $militante->ccreposicion = $request->ccreposicion;

            }

            $militante->ccestado = $estado;
            $militante->ccobservaciones = $militante->ccobservaciones." \n".$request->ccobservaciones;
            $militante->save();
            $this->setHistorial($militante->id, $tipo, $request->ccobservaciones);
            DB::commit();

            return redirect()->back()->with('message', 'Usuario modificado satisfactoriamente');

        } catch (Throwable $e){
            DB::rollBack();

            return redirect()->back()->with('message', 'Erro');
        }
    }

    public function registroHistorial(Request $request) {
        $this->setHistorial($request->id, $request->tipo, $request->observaciones);

        return redirect()->back()->with('message', 'Usuario modificado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Militante::where('id', $request->id)->first();

        $user->estado = 0;
        $user->save();

        return redirect()->back()->with('message', 'El Militante ha renunciado satisfactoriamente');
    }

    public function MilitantesExport(Request $request)
    {
        return Excel::download(new MilitantesExport($request), 'militantes.xlsx');
    }

    public function UsersExport(Request $request)
    {
        return Excel::download(new UsersExport($request), 'users.xlsx');
    }

    public function ClientesExport(Request $request)
    {
        return Excel::download(new ClientesExport($request), 'clientes.xlsx');
    }

    private function setRenuncia(Militante $renuncia) {
        $remplazo = Militante::where('id', $renuncia->idremplazo)->first();
        $remplazo->avalado = $renuncia->avalado;
        $remplazo->idcorporacion = $renuncia->idcorporacion;
        $remplazo->periodo = $renuncia->periodo;
        $remplazo->electo = $renuncia->electo;
        $remplazo->votos = $renuncia->votos;
        $remplazo->coalicion = $renuncia->coalicion;
        $remplazo->nombrecoalicion = $renuncia->nombrecoalicion;
        $remplazo->observaciones = $remplazo->observaciones.' - Remplazo de '.$renuncia->documento;
        $remplazo->save();
        $this->setHistorial($remplazo->id, self::nuRemplazo, 'Remplazo');

        //$renuncia->avalado = 0;
        $renuncia->idcorporacion = null;
        $renuncia->periodo = null;
        $renuncia->electo = 0;
        $renuncia->votos = null;
        $renuncia->coalicion = 0;
        $renuncia->nombrecoalicion = null;
        $renuncia->save();
        $this->setHistorial($renuncia->id, self::nuRenuncia, 'Renunció');
    }

    public static function setHistorial(int $idmilitante, int $idtipohistorial, string $observaciones = null, int $userid = null) {
        $historial = new Historial();
        $historial->idmilitante = $idmilitante;
        $historial->idtipohistorial = $idtipohistorial;
        $historial->idusuario = $userid?$userid:Auth::user()->id;
        //$historial->idusuario = Auth::user()?Auth::user()->id:$userid;
        $historial->observaciones = $observaciones;
        $historial->save();
    }

    public static function getCertificado(Militante $militante) {
        $url = url('storage/img/plantilla.jpg');

        $nombre = $militante->full_name;
        $cedula = $militante->documento;
        $dia = Carbon::create($militante->fechacreacion)->day;
        $mes = Carbon::create($militante->fechacreacion)->monthName;
        $anio = Carbon::create($militante->fechacreacion)->year;

        switch($militante->idgenero) {
            case 1:
                $genero = 'El Señor';
                break;
            case 2:
                $genero = 'La Señora';
                break;
            default:
                $genero = 'El Señor(a)';
        }

        $text  = "$genero $nombre, identificaddo con la cedula de ciudadanía ";
        $text2 = "No. $cedula, se encuentra registrado como militante del partido ";
        $text3 = "Colombia Renaciente, desde el $dia, de $mes, de $anio.";

        $data = [
            'text' => $text.$text2.$text3,
            'url' => $url
        ];

        $filename = 'certificado_'.$militante->id.$militante->documento.'.pdf';
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('pdf.certificado', $data);

        $output = $pdf->output();
        file_put_contents(public_path('storage').'/pdf/'.$filename, $output, FILE_APPEND);

        $archivo = new Archivo();
        $archivo->idtipoarchivo = 4;
        $archivo->idmilitante = $militante->id;
        $archivo->nombre = $filename;
        $archivo->extension = 'pdf';
        $archivo->url = url('storage/pdf').'/'.$filename;
        $archivo->tamaño = 1;//filesize($archivo->url);
        $archivo->save();

    }

    public static function importar(Request $request) {
        try {
            $id = Carbon::now()->unix();
            session([ 'import' => $id ]);

            Excel::queueImport(new MilitantesImport($id, Auth::user(), $request->file('file')->getClientOriginalName()), $request->file('file')->store('temp'));
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $fallas = $e->failures();

            foreach ($fallas as $falla) {
                $falla->row(); // fila en la que ocurrió el error
                $falla->attribute(); // el número de columna o la "llave" de la columna
                $falla->errors(); // Errores de las validaciones de laravel
                $falla->values(); // Valores de la fila en la que ocurrió el error.
            }
        }

        return redirect()->back()->with('message', 'Archivo importado correctamente');
    }

    public function status()
    {
        $id = session('import');

        return response([
            'start_date' => (string) cache("start_date_$id"),
            'end_date' => (string) cache("end_date_$id"),
            'current_row' => (int) cache("current_row_$id"),
            'total_rows' => (int) cache("total_rows_$id"),
            'statusfinal' => (string) cache("statusfinal_$id"),
        ]);
    }

}
