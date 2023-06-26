<?php namespace App\Exports;

use App\Expediente;
use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
class NotasExport implements FromView,WithHeadings,WithTitle
{

    public $data;
    public $header;
    public $title;
    public $segmentos;

    public function __construct($data,$header,$segmentos,$title)
    {
       $this->data = $data;
       $this->header = $header;
       $this->title = $title;
       $this->segmentos = $segmentos;
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

    public function view(): View
    {
      // dd($this->segmentos);
            return view('report.notas.frm_notas_view', [
                'data' => $this->data,
                'header' => $this->header,
                'segmentos'=>$this->segmentos,
            ]);
    }
}