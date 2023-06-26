<?php
namespace App\Traits;
/**
 * 
 */
trait UploadFile
{
    
    public function uploadFile($file,$filePath){
        $docum = $file;
        $nombre_arch= $docum->getClientOriginalName();
        $nombre_arch = htmlentities($nombre_arch);
        $nombre_arch = preg_replace('/\&(.)[^;]*;/', '\\1', $nombre_arch);
        $file_name = preg_replace('([^A-Za-z0-9. ])', '', $nombre_arch);           
        $file_name = time().'_'.md5($file_name).'.'.$docum->extension();
        $file_route = $filePath.'/'.$file_name;     
        $size = $docum->getSize();   

        \Storage::disk($this->disk)->put($file_route, file_get_contents($docum->getRealPath() ) );
        $complet_path = \Storage::disk($this->disk)->url($file_route);
        $file = new \App\File();
        $file->original_name = $docum->getClientOriginalName();   
        $file->encrypt_name = $file_name;  
        $file->path = $complet_path; 
        $file->size = $size;             
        $file->save();
        return $file;
    }

    public function setDisk($disk){
        $this->disk = $disk;
        return $this;
    }

}

