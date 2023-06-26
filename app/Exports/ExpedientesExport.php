<?php namespace App\Exports;

use App\Expediente;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
class ExpedientesExport implements FromCollection,WithHeadings,WithTitle
{

    public $data;
    public $header;
    public $title;

    public function __construct($data,$header,$title)
    {
       $this->data = $data;
       $this->header = $header;
       $this->title = $title;
    }
    public function headings(): array
    {
        return $this->header;
    }

    public function title(): string
    {
        return $this->title;
    }
   public function collection()
    {      
        return $collection = collect($this->data);        
    } 
}