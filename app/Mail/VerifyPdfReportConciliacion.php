<?php

namespace App\Mail;

use App\ConciliacionEstadoFileCompartido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class VerifyPdfReportConciliacion extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;
  //  public $clave;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ConciliacionEstadoFileCompartido $notification)
    {
       $this->notification = $notification;
      // $this->clave = $clave;
    }

    /** 
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      
        return $this->from('iuris@udenar.edu.co', 'Verificación de documento')
        ->subject("Documentos conciliación")
        ->view('myforms.mails.frm_verify_pdf_report_conciliacion');
    }
}
