@foreach($estado->files as $key => $file)

<div class="row file_pdf">
    <div class="col-md-8">
        {{$file->original_name}}
       
    </div>
    <div class="col-md-2" align="center">
        <a target="_blank" href="/conciliaciones/download/file/{{$file->id}}"><i class="fa fa-download"></i></a>
    </div>
    <div align="center" class="col-md-2">
        <input data-status_id="{{$estado->type_status_id}}" class="chk_compar_con_f" data-id="{{$file->id}}" type="checkbox" name="compartir_id[]" value="{{$file->id}}" id="{{$file->id}}">
    </div>
</div>


@endforeach