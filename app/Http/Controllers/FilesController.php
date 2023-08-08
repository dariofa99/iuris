<?php

namespace App\Http\Controllers;

use App\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class FilesController extends Controller
{

    public function download($fileid){
        $id = 100;
        if(auth()->user()) $id = auth()->user()->id;
        
        array_map('unlink', glob(public_path('act_temp/'.$id.'___*')));//elimina los archivos que el 
		$file= File::find($fileid);
        
        if ($file) {
            try {
                $rutaDeArchivo = storage_path($file->path);
                $filename = $id.'___'.$file->original_name;			
                copy( $rutaDeArchivo, public_path("act_temp/".$filename));	
                return redirect("act_temp/".$filename); 
            } catch (\Throwable $th) {
                echo "<h3>El archivo que buscas ya no existe!</h3><small><i>Amatai&copy".date("Y")."</i></small><br>$th";
            }
			
		}
    }
}