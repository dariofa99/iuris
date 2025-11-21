<?php

namespace App\Jobs;

use App\Expediente;
use App\Notifications\SendMailAndNotificationGeneral;
use App\Notifications\SolicitudEstudiantesProcesosJuricosExp;
use App\Services\UsersService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessSendNotificationGeneral implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $concepto;
    private $user_created;
    private $subject;
    private $url;
    private $users;

    public function __construct($users, string $concepto, string $user_created, string $subject, string $url = null)
    {
        $this->concepto = $concepto;
        $this->user_created = $user_created;
        $this->subject = $subject;
        $this->url = $url;
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Notification::send($this->users, new SendMailAndNotificationGeneral($this->concepto, $this->user_created, $this->subject, $this->url));
        foreach ($this->users as $email) {
            (new AnonymousNotifiable())
                ->route('mail', $email)
                ->notify(new SendMailAndNotificationGeneral(
                    $this->concepto,
                    $this->user_created,
                    $this->subject,
                    $this->url
                ));
        }
    }
}
