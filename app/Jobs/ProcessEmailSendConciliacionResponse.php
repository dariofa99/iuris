<?php

namespace App\Jobs;

use App\Conciliacion;
use App\Mail\RegConciliacionCorregir;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ProcessEmailSendConciliacionResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $users;
    protected $cuerpo_correo;
    protected $conciliacion;
    protected $asunto;
    protected $user_created;

    public function __construct(
        $users,
        $cuerpo_correo,
        Conciliacion $conciliacion,
        $asunto,
        $user_created
    ) {
         Log::info($users);
        $this->users = $users;
        $this->cuerpo_correo = $cuerpo_correo;
        $this->conciliacion = $conciliacion;
        $this->asunto = $asunto;
        $this->user_created = $user_created;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info($this->cuerpo_correo);
        Mail::to($this->users)->send(new RegConciliacionCorregir(
            $this->users,
            $this->cuerpo_correo,
            $this->conciliacion,
            $this->asunto,
            $this->user_created,
        ));
    }
}
