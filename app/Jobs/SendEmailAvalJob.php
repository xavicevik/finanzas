<?php

namespace App\Jobs;

use App\Mail\Notificaciones;
use App\Models\Militante;
use App\Models\User;
use App\Notifications\EmailAvalNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SendEmailAvalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    public $subject, $line1, $line2, $line3, $action, $val, $iduser, $emailcc, $tipouser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($iduser, $subject, $line1, $line2, $line3, $action, $val, $emailcc, $tipouser)
    {
        $this->subject = $subject;
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->line3 = $line3;
        $this->action = $action;
        $this->val = $val;
        $this->iduser = $iduser;
        $this->emailcc = $emailcc;
        $this->tipouser = $tipouser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = Militante::find($this->iduser);
        $user->notify(new EmailAvalNotification($this->subject, $this->line1, $this->line2, $this->line3, $this->action, $this->val, $this->emailcc, $this->tipouser));
    }
}
