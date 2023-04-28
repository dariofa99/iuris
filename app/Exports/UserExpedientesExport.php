<?php namespace App\Exports;

use App\Expediente;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class UserExpedientesExport implements FromView,ShouldAutoSize,WithTitle
{

    public $data;
   

    public function __construct($data)
    {
       $this->data = $data;
      
    }
    
    public function title(): string
    {
        return 'Expedientes';
    }

   

    public function view(): View
    {
      
            return view('report.estudiante_exp', [
                'expedientes' => $this->data,                
            ]);
    }
}