<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Thumbnail;
use Intervention\Image\ImageManager;

class ThumbnailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $thumbnails = Thumbnail::all();
        return view('myforms.thumbnails', compact('thumbnails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('myforms.thumbnailForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $file = Input::file('image');
       //Creamos una instancia de la libreria instalada   
       $image = \Image::make(\Input::file('image'));
       //Ruta donde queremos guardar las imagenes
       $path = public_path().'/thumbnails/';

       // Guardar Original
       $image->save($path.$file->getClientOriginalName());
       // Cambiar de tamaño
       $image->resize(120,120);
       // Guardar
       $image->save($path.'thumb_'.$file->getClientOriginalName());
       
       //Guardamos nombre y nombreOriginal en la BD
       $thumbnail = new Thumbnail();
       $thumbnail->name = Input::get('name');
       $thumbnail->image = $file->getClientOriginalName();
       $thumbnail->save();
       

       
       return redirect()->route('thumbnail.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
