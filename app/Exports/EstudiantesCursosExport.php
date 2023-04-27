<?php namespace App\Exports;

use App\Expediente;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
class EstudiantesCursosExport implements FromView,WithColumnWidths,ShouldAutoSize,WithTitle
{

    public $data;
   

    public function __construct($data)
    {
       $this->data = $data;
      
    }
    
    public function title(): string
    {
        return 'Estudiantes';
    }

    public function columnWidths(): array
    {
        return [
           'A' => 10, 
                   
        ];
    }

    public function view(): View
    {
      
            return view('report.estudiantes_cursos', [
                'users' => $this->data,                
            ]);
    }
}