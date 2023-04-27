
<table class="table">
<thead>
                               <th>Concepto</th><th>Nombre archivo</th><th></th>
                               </thead>
                               <tbody>
                               @foreach ($solicitud->files()->where('type_category_id',164)->get() as $file)
<tr id="itemd-{{$file->id}}">
    <td>{{$file->pivot->concept}}</td>
    <td>
   <a target="_blank" href="{{url('/descargar/documento',$file->id)}}">  {{$file->original_name}}</a>
 
    
    </td>
    <td width="20%">
     @if($file->pivot->user_id==currentuser()->id)
     <button class="btn btn-primary btn_doc_edit" data-id="{{$file->id}}"><i class="fa fa-edit"></i></button>
     <button class="btn btn-danger btn_doc_delete" data-id="{{$file->id}}"><i class="fa fa-trash"></i></button>
    @endif
     </td>
    </tr>
@endforeach
                               </tbody>


</table>